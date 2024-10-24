<?php
/**
 * Fichier user_dashboard.php
 * 
 * Ce fichier affiche le tableau de bord pour les utilisateurs connectés.
 * Il récupère et affiche les informations de l'utilisateur connecté (nom d'utilisateur, email, date d'inscription)
 * et propose des actions telles que la modification du profil, la consultation des commandes, et la déconnexion.
 * 
 * Méthodes incluses :
 * - Vérification de la connexion de l'utilisateur.
 * - Récupération des informations de l'utilisateur en base de données.
 * - Affichage des informations de l'utilisateur et des actions disponibles.
 * 
 * PHP version 7.4+
 * 
 * @category   User Dashboard
 * @package    SR NAILS
 * @author     Nicolas <nicolas.rouillelanoe@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    1.0
 * @link       https://github.com/RangaTpst/projet_BTS
 */

require_once '../config/db.php'; // Inclusion du fichier de configuration pour la base de données

/**
 * Démarrer la session si elle n'est pas déjà démarrée.
 * 
 * Vérifie l'état de la session avec session_status() et la démarre si elle n'est pas active.
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Vérification de la connexion de l'utilisateur.
 * 
 * Si l'utilisateur n'est pas connecté (pas de session active), il est redirigé vers la page de connexion.
 */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/**
 * Récupération des informations de l'utilisateur connecté.
 * 
 * Requête SQL pour récupérer le nom d'utilisateur, l'email et la date d'inscription de l'utilisateur connecté.
 * 
 * @param int $_SESSION['user_id'] Identifiant unique de l'utilisateur connecté (stocké dans la session).
 * @return array Tableau associatif contenant les informations de l'utilisateur.
 */
$query = "SELECT username, email, registration_date FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

/**
 * Gestion des erreurs de récupération des informations.
 * 
 * Si l'utilisateur n'est pas trouvé en base de données, un message d'erreur est affiché.
 */
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
