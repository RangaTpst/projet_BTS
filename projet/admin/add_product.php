<?php
// Inclure la vérification si l'utilisateur est connecté et s'il est admin
session_start();

// Vérifier si l'utilisateur est connecté et est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Rediriger si l'utilisateur n'est pas un admin
    header("Location: login.php");
    exit();
}

// Si l'administrateur est connecté, afficher le formulaire
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit - Admin</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Lien vers votre fichier CSS -->
</head>
<body>
    <?php include '../includes/header.php'; ?> <!-- Assurez-vous que le chemin est correct -->

<h1>Ajouter un nouveau produit</h1>

<form action="process_add_product.php" method="POST" enctype="multipart/form-data">
    <label for="name">Nom du produit :</label>
    <input type="text" name="name" id="name" required><br><br>

    <label for="description">Description :</label>
    <textarea name="description" id="description" required></textarea><br><br>

    <label for="price">Prix :</label>
    <input type="number" step="0.01" name="price" id="price" required><br><br>

    <label for="image">Image du produit :</label>
    <input type="file" name="image" id="image" required><br><br>

    <button type="submit">Ajouter le produit</button>
</form>

</body>
</html>
