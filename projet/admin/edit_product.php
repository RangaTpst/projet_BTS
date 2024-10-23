<?php
session_start();
require_once '../config/db.php';

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Vérifier si l'ID du produit est présent dans l'URL
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$product_id = $_GET['id'];

// Récupérer les informations du produit
$query = "SELECT * FROM products WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Produit introuvable.";
    exit();
}

// Mise à jour des informations du produit
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

        <form method="POST" action="edit_product.php?id=<?php echo $product_id; ?>">
            <label for="name">Nom du produit :</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

            <label for="description">Description :</label>
            <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

            <label for="price">Prix :</label>
            <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>"step="0.01" min="0" required>

            <button type="submit">Mettre à jour le produit</button>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
