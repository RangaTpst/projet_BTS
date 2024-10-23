<?php
session_start();
require_once '../config/db.php'; // Inclure la connexion à la base de données

$error = '';

// Vérifier s'il existe déjà des cookies pour se reconnecter
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id']) && isset($_COOKIE['username']) && isset($_COOKIE['role'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['role'] = $_COOKIE['role'];
}

// Redirection vers la page dashboard si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../admin/admin_dashboard.php");
    } else {
        header("Location: user_dashboard.php");
    }
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = $_POST['identifier']; // Peut être soit le nom d'utilisateur, soit l'email
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']); // Vérifier si la case "Rester connecté" est cochée

    // Préparer et exécuter la requête pour vérifier l'utilisateur via le nom d'utilisateur ou l'email
    $query = "SELECT * FROM users WHERE username = :identifier1 OR email = :identifier2";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':identifier1', $identifier, PDO::PARAM_STR);
    $stmt->bindParam(':identifier2', $identifier, PDO::PARAM_STR);
    $stmt->execute();
    
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // L'utilisateur est authentifié, on initialise la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Si "Rester connecté" est coché, créer des cookies pour 30 jours
        if ($remember_me) {
            setcookie('user_id', $user['id'], time() + (86400 * 30), "/", "", false, true); // 86400 = 1 jour, httponly=true
            setcookie('username', $user['username'], time() + (86400 * 30), "/", "", false, true);
            setcookie('role', $user['role'], time() + (86400 * 30), "/", "", false, true);
        }

        // Rediriger selon le rôle
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
