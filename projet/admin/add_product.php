<?php
/**
 * Fichier admin_add_product.php
 * 
 * Ce fichier gère l'ajout de produits par un administrateur.
 * Il vérifie si l'utilisateur est connecté et a les droits d'administrateur, puis affiche un formulaire pour ajouter un produit.
 * 
 * Méthodes incluses :
 * - Vérification des droits d'administration.
 * - Affichage du formulaire d'ajout de produit.
 * - Redirection si l'utilisateur n'est pas autorisé.
 * 
 * PHP version 7.4+
 * 
 * @category   Administration
 * @package    SR NAILS
 * @author     Nicolas <nicolas.rouillelanoe@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    1.0
 * @link       https://github.com/RangaTpst/projet_BTS
 */

// Démarrer la session pour vérifier l'authentification
session_start();

/**
 * Vérification des droits d'administration.
 * 
 * Si l'utilisateur n'est pas connecté ou n'a pas le rôle 'admin', il est redirigé vers la page de connexion.
 */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Si l'utilisateur est admin, afficher le formulaire d'ajout de produit
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit - Admin</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Lien vers le fichier CSS -->
</head>
<body>
    <?php include '../includes/header.php'; ?> <!-- Inclusion du header -->
    
    <h1>Ajouter un nouveau produit</h1>

    <!-- Formulaire pour ajouter un produit -->
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
