<?php
/**
 * Fichier admin_dashboard.php
 * 
 * Ce fichier permet à l'administrateur de gérer les utilisateurs (promotion/déclassement) et les produits (ajout, modification, suppression).
 * Il vérifie si l'utilisateur est bien un administrateur avant d'afficher les options de gestion.
 * 
 * Méthodes incluses :
 * - Vérification des droits d'administration.
 * - Gestion de la promotion et rétrogradation des utilisateurs.
 * - Gestion de la suppression des produits.
 * - Récupération des utilisateurs et des produits depuis la base de données.
 * 
 * PHP version 7.4+
 * 
 * @category   Administration
 * @package    SR NAILS
 * @author     Nicolas <nicolas.rouillelanoe@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    1.0
 * @link       https://github.com/RagaTpst/projet_BTS
 */

session_start();
require_once '../config/db.php'; // Inclusion du fichier de connexion à la base de données

/**
 * Vérification des droits d'administration.
 * 
 * Si l'utilisateur n'est pas connecté ou n'a pas le rôle 'admin', il est redirigé vers la page de connexion.
 */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

/**
 * Suppression d'un produit.
 * 
 * Si le paramètre 'delete' est présent dans l'URL, le produit correspondant est supprimé de la base de données.
 * 
 * @param int $_GET['delete'] Identifiant du produit à supprimer.
 */
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $deleteQuery = "DELETE FROM products WHERE id = :id";
    $stmt = $pdo->prepare($deleteQuery);
    $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Le produit a été supprimé avec succès.');</script>";
        echo "<script>window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Erreur lors de la suppression du produit.');</script>";
    }
}

/**
 * Récupération des utilisateurs.
 * 
 * Requête SQL pour récupérer tous les utilisateurs (non-admin) pour la promotion en administrateurs.
 * Requête SQL distincte pour récupérer tous les administrateurs pour rétrogradation.
 * 
 * @return array Liste des utilisateurs et administrateurs.
 */
$query = "SELECT * FROM users WHERE role != 'admin'";
$stmt = $pdo->query($query);
$users = $stmt->fetchAll();

$queryAdmins = "SELECT * FROM users WHERE role = 'admin'";
$stmtAdmins = $pdo->query($queryAdmins);
$admins = $stmtAdmins->fetchAll();

/**
 * Promotion d'un utilisateur en administrateur.
 * 
 * Si le formulaire de promotion est soumis, le rôle de l'utilisateur sélectionné est modifié en 'admin'.
 * 
 * @param int $_POST['user_id'] Identifiant de l'utilisateur à promouvoir.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['promote_user'])) {
    $userId = $_POST['user_id'];
    $newRole = 'admin';

    $updateQuery = "UPDATE users SET role = :role WHERE id = :id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':role', $newRole);
    $updateStmt->bindParam(':id', $userId, PDO::PARAM_INT);

    if ($updateStmt->execute()) {
        echo "<script>alert('L\'utilisateur a été promu en administrateur.');</script>";
        echo "<script>window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Erreur lors de la mise à jour du rôle.');</script>";
    }
}

/**
 * Rétrogradation d'un administrateur en utilisateur simple.
 * 
 * Si le formulaire de rétrogradation est soumis, le rôle de l'administrateur sélectionné est modifié en 'user'.
 * 
 * @param int $_POST['admin_id'] Identifiant de l'administrateur à rétrograder.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['demote_user'])) {
    $adminId = $_POST['admin_id'];
    $newRole = 'user';

    $updateQuery = "UPDATE users SET role = :role WHERE id = :id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':role', $newRole);
    $updateStmt->bindParam(':id', $adminId, PDO::PARAM_INT);

    if ($updateStmt->execute()) {
        echo "<script>alert('L\'administrateur a été rétrogradé en utilisateur simple.');</script>";
        echo "<script>window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Erreur lors de la rétrogradation.');</script>";
    }
}

/**
 * Récupération des produits disponibles.
 * 
 * Requête SQL pour récupérer tous les produits dans la base de données afin de les afficher dans le tableau de bord.
 * 
 * @return array Liste des produits disponibles.
 */
$query = "SELECT * FROM products";
$stmt = $pdo->query($query);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Gestion des utilisateurs et des produits</title>
    <link rel="stylesheet" href="../public/assets/css/admin.css"> <!-- Le lien vers ton fichier CSS -->

    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">

        <!-- Formulaires de gestion des utilisateurs -->
        <div class="form-container">
            <!-- Premier formulaire : Promouvoir en admin -->
            <form method="POST" action="admin_dashboard.php">
                <h3>Gestion des utilisateurs</h3>
                <label for="user_id">Sélectionnez un utilisateur à promouvoir administrateur :</label>
                <select name="user_id" required>
                    <option value="">Sélectionnez un utilisateur</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="promote_user">Promouvoir en admin</button>
            </form>

            <!-- Deuxième formulaire : Rétrograder en utilisateur -->
            <form method="POST" action="admin_dashboard.php">
                <h3>Administrateurs actuels</h3>
                <label for="admin_id">Sélectionnez un administrateur à rétrograder :</label>
                <select name="admin_id" required>
                    <option value="">Sélectionnez un administrateur</option>
                    <?php foreach ($admins as $admin): ?>
                        <option value="<?php echo $admin['id']; ?>"><?php echo $admin['username']; ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="demote_user">Rétrograder en utilisateur</button>
            </form>
        </div>

        <!-- Liste des produits disponibles -->
        <h2>Liste des produits disponibles</h2>

        <!-- Bouton pour ajouter un produit -->
        <a href="add_product.php" class="btn-add-product">Ajouter un nouveau produit</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom du produit</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($products): ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['description']); ?></td>
                            <td><?php echo htmlspecialchars($product['price']); ?> €</td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn-edit">Modifier</a>
                                <a href="admin_dashboard.php?delete=<?php echo $product['id']; ?>" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Aucun produit disponible pour le moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            // Initialiser Select2 sur les champs de sélection
            $('select').select2({
                placeholder: "Sélectionnez un utilisateur ou un administrateur",
                allowClear: true,
                width: '100%'
            });
        });
    </script>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
