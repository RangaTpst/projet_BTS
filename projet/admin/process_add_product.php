<?php
/**
 * Fichier process_add_product.php
 * 
 * Ce fichier traite l'ajout d'un nouveau produit par un administrateur.
 * Il vérifie les données envoyées via le formulaire, traite l'image du produit et insère les informations dans la base de données.
 * 
 * Méthodes incluses :
 * - Vérification des droits d'administration.
 * - Récupération et validation des données du formulaire.
 * - Traitement et vérification du fichier image.
 * - Insertion du produit dans la base de données.
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

require_once '../config/db.php'; // Connexion à la base de données

/**
 * Vérification des droits d'administration.
 * 
 * Si l'utilisateur n'est pas connecté ou n'a pas le rôle 'admin', il est redirigé vers la page de connexion.
 */
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

/**
 * Vérification de la soumission du formulaire.
 * 
 * Si le formulaire d'ajout de produit est soumis, on récupère les données du produit et on traite l'image.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et validation des données du formulaire
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $price = floatval($_POST['price']);

    /**
     * Traitement de l'image du produit.
     * 
     * Vérification de l'image téléchargée (extension, taille, erreurs) et déplacement de l'image dans le répertoire 'uploads'.
     */
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = basename($_FILES['image']['name']);
        $image_tmp_path = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_error = $_FILES['image']['error'];

        // Extensions autorisées
        $allowed_extensions = array("jpg", "jpeg", "png", "gif");
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);

        /**
         * Vérification de l'extension du fichier image.
         * 
         * Seules les extensions JPG, JPEG, PNG et GIF sont autorisées.
         */
        if (in_array(strtolower($image_extension), $allowed_extensions)) {
            /**
             * Vérification de la taille du fichier image.
             * 
             * La taille maximale autorisée est de 5MB.
             */
            if ($image_size < 5000000) {
                // Définir le répertoire de téléchargement des images
                $upload_dir = '../public/uploads/';
                $image_path = $upload_dir . $image_name;

                /**
                 * Déplacement de l'image vers le dossier 'uploads'.
                 * 
                 * Si le téléchargement réussit, les informations du produit sont insérées dans la base de données.
                 */
                if (move_uploaded_file($image_tmp_path, $image_path)) {
                    // Insertion du produit dans la base de données
                    $sql = "INSERT INTO products (name, description, price, image) VALUES (:name, :description, :price, :image)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':image', $image_name);

                    /**
                     * Exécution de la requête d'insertion.
                     * 
                     * Si l'ajout du produit réussit, l'administrateur est redirigé vers le tableau de bord avec un message de succès.
                     */
                    if ($stmt->execute()) {
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
