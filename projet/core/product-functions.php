<?php
// Inclure la connexion à la base de données
require_once '../config/db.php';

// Fonction pour récupérer un nombre limité de produits récents
function getRecentProducts($limit = 3) {
    global $pdo;

    try {
        $query = "SELECT * FROM products ORDER BY created_at DESC LIMIT :limit";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll();

        return $products;
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des produits récents : " . $e->getMessage();
        return [];
    }
}
?>
