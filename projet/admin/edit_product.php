<?php
/**
 * Fichier edit_product.php
 * 
 * Ce fichier permet à l'administrateur de modifier les informations d'un produit existant.
 * Il vérifie si l'utilisateur est un administrateur, récupère les informations du produit à modifier,
 * et met à jour la base de données avec les nouvelles informations fournies.
 * 
 * Méthodes incluses :
 * - Vérification des droits d'administration.
 * - Récupération des informations du produit à modifier.
 * - Mise à jour du produit dans la base de données.
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
 * Vérification de l'ID du produit.
 * 
 * Si l'identifiant du produit n'est pas présent dans l'URL, l'utilisateur est redirigé vers le tableau de bord.
 */
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$product_id = $_GET['id'];

/**
 * Récupération des informations du produit.
 * 
 * Requête SQL pour récupérer les informations du produit à modifier, en utilisant l'ID passé dans l'URL.
 * 
 * @param int $_GET['id'] Identifiant unique du produit.
 * @return array Informations du produit récupérées depuis la base de données.
 */
$query = "SELECT * FROM products WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Produit introuvable.";
    exit();
}

/**
 * Mise à jour des informations du produit.
 * 
 * Si le formulaire est soumis, les nouvelles informations (nom, description, prix) sont mises à jour dans la base de données.
 * 
 * @param string $_POST['name'] Nouveau nom du produit.
 * @param string $_POST['description'] Nouvelle description du produit.
 * @param float $_POST['price'] Nouveau prix du produit.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $updateQuery = "UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':name', $name);
    $updateStmt->bindParam(':description', $description);
    $updateStmt->bindParam(':price', $price);
    $updateStmt->bindParam(':id', $product_id);

    if ($updateStmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Erreur lors de la mise à jour du produit.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le produit</title>
    <link rel="stylesheet" href="../public/css/admin_dashboard.css"> <!-- Lien vers le fichier CSS -->
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h2>Modifier le produit</h2>

        <!-- Formulaire pour modifier un produit existant -->
        <form method="POST" action="edit_product.php?id=<?php echo $product_id; ?>">
            <label for="name">Nom du produit :</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

            <label for="description">Description :</label>
            <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

            <label for="price">Prix :</label>
            <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" step="0.01" min="0" required>

            <button type="submit">Mettre à jour le produit</button>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
