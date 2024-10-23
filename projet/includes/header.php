<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifier s'il existe déjà des cookies pour se reconnecter
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id']) && isset($_COOKIE['username']) && isset($_COOKIE['role'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['role'] = $_COOKIE['role'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SR Nails</title>
    <!-- Inclure le fichier CSS -->
    <link rel="stylesheet" href="../public/assets/css/header.css"> <!-- Assurez-vous que le chemin est correct -->
</head>
<body>
<header>
    <h1>SR Nails</h1>
    
    <!-- Menu à gauche (Accueil, Nos Produits, Contact) -->
    <nav class="menu-left">
        <a href="../public">Accueil</a>
        <a href="../public/products.php">Nos Produits</a>
        <a href="../public/about.php">L'entreprise</a>
        <a href="../public/contact.php">Contact</a>

    </nav>

    <!-- Menu à droite (Tableau de bord, Se connecter, Se déconnecter) -->
    <nav class="menu-right">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <!-- Si l'utilisateur n'est pas connecté, afficher le bouton de connexion -->
            <a href="login.php" class="login-btn">Se connecter</a>
        <?php else: ?>
            <!-- Si l'utilisateur est connecté, afficher son nom, un lien vers son tableau de bord et l'option de déconnexion -->
            <span>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</span>

            <!-- Lien vers le tableau de bord en fonction du rôle de l'utilisateur -->
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="../admin/admin_dashboard.php" class="dashboard-btn">Tableau de bord Admin</a>
            <?php else: ?>
                <a href="user_dashboard.php" class="dashboard-btn">Tableau de bord</a>
            <?php endif; ?>

            <a href="../public/logout.php" class="logout-btn">Se déconnecter</a>
        <?php endif; ?>
    </nav>
</header>
