<?php
require_once '../config/db.php'; // Inclure la connexion à la base de données
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérification de la correspondance des mots de passe
    if ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'utilisateur ou l'email existe déjà
        $query = "SELECT * FROM users WHERE username = :username OR email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->fetch()) {
            $error = "Le nom d'utilisateur ou l'email est déjà pris.";
        } else {
            // Hacher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insérer l'utilisateur avec le rôle 'user' par défaut
            $insertQuery = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, 'user')";
            $insertStmt = $pdo->prepare($insertQuery);
            $insertStmt->bindParam(':username', $username);
            $insertStmt->bindParam(':email', $email);
            $insertStmt->bindParam(':password', $hashedPassword);

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
