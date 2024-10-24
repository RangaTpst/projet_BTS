<?php
/**
 * Fichier login.php
 * 
 * Ce fichier gère la connexion des utilisateurs.
 * Il vérifie les informations d'identification soumises par l'utilisateur (nom d'utilisateur ou email et mot de passe),
 * et démarre une session en cas de succès. Il offre également une fonctionnalité "Rester connecté" via des cookies.
 * Si les informations sont incorrectes, un message d'erreur est renvoyé.
 * 
 * Méthodes incluses :
 * - Validation des champs de formulaire (identifiant, mot de passe).
 * - Vérification de l'existence de l'utilisateur en base de données.
 * - Comparaison du mot de passe saisi avec le mot de passe haché stocké en base de données.
 * - Gestion des sessions utilisateur et des cookies "Rester connecté".
 * - Redirection en fonction du rôle (admin ou utilisateur normal).
 * 
 * PHP version 7.4+
 * 
 * @category   Authentication
 * @package    SR NAILS
 * @author     Nicolas <nicolas.rouillelanoe@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    1.0
 * @link       https://github.com/RangaTpst/projet_BTS
 */

session_start();
require_once '../config/db.php'; // Inclure la connexion à la base de données

$error = '';

/**
 * Gestion des cookies pour reconnecter l'utilisateur.
 * 
 * Si l'utilisateur n'a pas de session active mais a des cookies valides, 
 * on restaure sa session à partir des informations contenues dans les cookies.
 */
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id']) && isset($_COOKIE['username']) && isset($_COOKIE['role'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['role'] = $_COOKIE['role'];
}

/**
 * Redirection si l'utilisateur est déjà connecté.
 * 
 * Si l'utilisateur est déjà authentifié via une session active, on le redirige 
 * vers son tableau de bord en fonction de son rôle (admin ou utilisateur).
 */
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../admin/admin_dashboard.php");
    } else {
        header("Location: user_dashboard.php");
    }
    exit();
}

/**
 * Traitement du formulaire de connexion.
 * 
 * Lorsque le formulaire est soumis, les informations d'identification (nom d'utilisateur/email et mot de passe) 
 * sont vérifiées dans la base de données.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = $_POST['identifier']; // Peut être soit le nom d'utilisateur, soit l'email
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']); // Vérifier si la case "Rester connecté" est cochée

    /**
     * Requête SQL pour vérifier l'utilisateur via le nom d'utilisateur ou l'email.
     * 
     * @param string $identifier Identifiant utilisateur (nom d'utilisateur ou email).
     * @param string $password Mot de passe de l'utilisateur.
     */
    $query = "SELECT * FROM users WHERE username = :identifier1 OR email = :identifier2";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':identifier1', $identifier, PDO::PARAM_STR);
    $stmt->bindParam(':identifier2', $identifier, PDO::PARAM_STR);
    $stmt->execute();
    
    $user = $stmt->fetch();

    /**
     * Vérification du mot de passe avec password_verify().
     * 
     * Si l'utilisateur est trouvé et que le mot de passe est correct, on initialise la session utilisateur.
     */
    if ($user && password_verify($password, $user['password'])) {
        // Initialisation de la session utilisateur
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        /**
         * Gestion de la fonctionnalité "Rester connecté".
         * 
         * Si l'utilisateur a coché la case "Rester connecté", des cookies sont créés pour 30 jours.
         */
        if ($remember_me) {
            setcookie('user_id', $user['id'], time() + (86400 * 30), "/", "", false, true); // 86400 = 1 jour, httponly=true
            setcookie('username', $user['username'], time() + (86400 * 30), "/", "", false, true);
            setcookie('role', $user['role'], time() + (86400 * 30), "/", "", false, true);
        }

        // Redirection en fonction du rôle de l'utilisateur
        if ($user['role'] == 'admin') {
            header("Location: ../admin/admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit();
    } else {
        $error = "Nom d'utilisateur, email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="assets/css/login.css"> <!-- Assurez-vous que le chemin est correct -->
</head>
<body>
    <!-- Inclure le header -->
    <?php include '../includes/header.php'; ?> <!-- Assurez-vous que le chemin est correct -->

    <div class="login-container">
        <h2>Connexion</h2>

        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label for="identifier">Nom d'utilisateur ou Email :</label>
            <input type="text" name="identifier" required>

            <label for="password">Mot de passe :</label>
            <input type="password" name="password" required>

            <!-- Case à cocher pour rester connecté -->
            <div class="checkbox-container">
                <input type="checkbox" name="remember_me" id="remember_me">
                <label for="remember_me">Rester connecté</label>
            </div>

            <button type="submit">Se connecter</button>
        </form>

        <p>Pas encore de compte ? <a href="signup.php">Créer un compte</a></p>
    </div>
</body>
</html>
