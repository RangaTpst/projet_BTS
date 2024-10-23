<?php
// Inclure la connexion à la base de données
require_once '../config/db.php'; // Adapter le chemin selon votre structure de dossier

// Vérifier si l'utilisateur est bien admin
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $price = floatval($_POST['price']);
    
    // Traitement de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = basename($_FILES['image']['name']);
        $image_tmp_path = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_error = $_FILES['image']['error'];

        // Extensions autorisées pour l'image
        $allowed_extensions = array("jpg", "jpeg", "png", "gif");
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        
        // Vérifier l'extension du fichier
        if (in_array(strtolower($image_extension), $allowed_extensions)) {
            // Vérifier la taille du fichier (limite à 5MB)
            if ($image_size < 5000000) {
                // Définir le chemin absolu pour le dossier uploads
                $upload_dir = '../public/uploads/';
                $image_path = $upload_dir . $image_name;

                // Déplacer l'image vers le dossier "uploads"
                if (move_uploaded_file($image_tmp_path, $image_path)) {
                    // Insérer le produit dans la base de données
                    $sql = "INSERT INTO products (name, description, price, image) VALUES (:name, :description, :price, :image)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':image', $image_name);

                    if ($stmt->execute()) {
                        // Si l'ajout est un succès
                        header("Location: admin_dashboard.php?success=1");
                        exit();
                    } else {
                        echo "Erreur lors de l'ajout du produit à la base de données.";
                    }
                } else {
                    echo "Erreur lors du téléchargement de l'image.";
                }
            } else {
                echo "L'image est trop volumineuse. La taille maximale autorisée est de 5MB.";
            }
        } else {
            echo "Format de fichier non autorisé. Seules les extensions JPG, JPEG, PNG et GIF sont acceptées.";
        }
    } else {
        echo "Veuillez télécharger une image valide.";
    }
} else {
    echo "Aucune donnée reçue.";
}
?>
