<?php

require_once '../config/db.php';

// Démarrer la session seulement si elle n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les informations de l'utilisateur connecté
$query = "SELECT username, email, registration_date FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Erreur : utilisateur non trouvé.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord utilisateur</title>
    <link rel="stylesheet" href="../public/assets/css/dashboard.css"> <!-- Lien vers le CSS spécifique du dashboard -->
</head>
<body>
    <?php include '../includes/header.php'; ?> <!-- Inclure le header -->
    
    <div class="user-dashboard">
        <h2>Bienvenue, <?php echo htmlspecialchars($user['username']); ?></h2>
        <div class="user-info">
            <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Date d'inscription :</strong> <?php echo htmlspecialchars($user['registration_date']); ?></p>
        </div>
        <div class="user-actions">
            <a href="edit_profile.php" class="btn">Modifier le profil</a>
            <a href="orders.php" class="btn">Mes commandes</a>
            <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?> <!-- Inclure le footer -->
</body>
</html>
