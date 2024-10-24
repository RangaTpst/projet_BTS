<?php
/**
 * Fichier signup.php
 * 
 * Ce fichier gère l'inscription des nouveaux utilisateurs sur le site.
 * Il valide les informations fournies par l'utilisateur, crée un compte en base de données, 
 * et démarre une session utilisateur en cas de succès.
 * 
 * Méthodes incluses :
 * - Validation des champs de formulaire (nom, email, mot de passe).
 * - Vérification de l'existence de l'utilisateur dans la base de données.
 * - Hashage sécurisé du mot de passe.
 * - Insertion des informations de l'utilisateur dans la base de données.
 * - Démarrage de la session utilisateur après succès de l'inscription.
 * 
 * PHP version 7.4+
 * 
 * @category   Authentication
 * @package    SRNAILS
 * @author     Nicolas <nicolas.rouillelanoe@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    1.0
 * @link       https://github.com/RangaTpst/projet_BTS
 */

/**
 * Vérification de la soumission du formulaire.
 * 
 * Si le formulaire a été soumis, les données utilisateur sont traitées.
 */
require_once '../config/db.php'; // Inclure la connexion à la base de données
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        /**
     * Nettoyage et validation des données du formulaire.
     * 
     * Les champs "username", "email" et "password" doivent être remplis.
     */
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérification de la correspondance des mots de passe
    if ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
            /**
     * Vérification de l'existence de l'utilisateur dans la base de données.
     * 
     * Cette requête prépare la vérification si un utilisateur existe déjà avec cet email.
     * 
     * @param string $email Email de l'utilisateur à vérifier.
     */
        $query = "SELECT * FROM users WHERE username = :username OR email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->fetch()) {
            $error = "Le nom d'utilisateur ou l'email est déjà pris.";
        } else {
                /**
     * Hashage sécurisé du mot de passe utilisateur.
     * 
     * Utilisation de la fonction password_hash() pour hacher le mot de passe.
     * 
     * @param string $password Mot de passe à hacher.
     * @return string Mot de passe haché.
     */
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    /**
     * Insertion des informations de l'utilisateur dans la base de données.
     * 
     * @param string $username Nom d'utilisateur.
     * @param string $email Adresse email.
     * @param string $hashed_password Mot de passe haché.
     */
            $insertQuery = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, 'user')";
            $insertStmt = $pdo->prepare($insertQuery);
            $insertStmt->bindParam(':username', $username);
            $insertStmt->bindParam(':email', $email);
            $insertStmt->bindParam(':password', $hashedPassword);
        /**
         * Démarrage de la session utilisateur après inscription réussie.
         * 
         * La session contient les informations de l'utilisateur nouvellement inscrit.
         */
            if ($insertStmt->execute()) {
                $success = "Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.";
            } else {
                $error = "Une erreur est survenue lors de la création de votre compte.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/login.css"> <!-- Même CSS que login.css -->
    <title>Inscription</title>
</head>
<body>
    <div class="signup-container"> <!-- Utilisation de signup-container -->
        <h2>Créer un compte</h2>

        <?php if (!empty($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php elseif (!empty($success)): ?>
            <p class="success-message"><?php echo $success; ?></p>
        <?php endif; ?>

        <form method="POST" action="signup.php">
            <div class="input-group">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" name="username" required>
            </div>

            <div class="input-group">
                <label for="email">Email :</label>
                <input type="email" name="email" required>
            </div>

            <div class="input-group">
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" required>
            </div>

            <div class="input-group">
                <label for="confirm_password">Confirmer le mot de passe :</label>
                <input type="password" name="confirm_password" required>
            </div>

            <button type="submit" class="submit-btn">S'inscrire</button>
        </form>

        <p class="login-link">Vous avez déjà un compte ? <a href="login.php">Se connecter</a></p>
    </div>
</body>
</html>
