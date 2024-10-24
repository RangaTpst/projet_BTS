<?php include '../includes/header.php'; 

/**
 * Fichier contact.php
 * 
 * Ce fichier gère l'affichage et le traitement du formulaire de contact.
 * Il permet aux utilisateurs d'envoyer un message via le formulaire.
 * Les données du formulaire sont validées, puis envoyées par email à l'administrateur du site.
 * 
 * Méthodes incluses :
 * - Validation des données du formulaire (nom, email, message).
 * - Envoi de l'email à l'administrateur via la fonction mail().
 * - Gestion des erreurs en cas de données incorrectes ou d'échec d'envoi.
 * 
 * PHP version 7.4+
 * 
 * @category   E-commerce
 * @package    SR NAILS
 * @author     Nicolas <nicolas.rouillelanoe@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    1.0
 * @link       https://github.com/RangaTpst/projet_BTS
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - SR Nails</title>
    <link rel="stylesheet" href="assets/css/contact.css"> <!-- Chemin vers ton CSS principal -->
</head>
<body>

<div class="contact-container">
    <h1>Nous Contacter</h1>

    <!-- Section "Nous contacter" et "Envoyez-nous un message" en deux colonnes -->
    <div class="contact-section">
        <div class="contact-info">
            <h2>Informations de contact</h2>
            <p><strong>Email :</strong> nicolas.rouillelanoe@gmail.com</p>
            <p><strong>Téléphone :</strong>you don't have perission to see this</p>
            <p><strong>Adresse :</strong> 1 Rue Marie Curie, 56890 Plescop, France (Aftec Vannes)</p>
            <p><strong>Heures d'ouverture :</strong> Lundi - Vendredi, 9h00 - 18h00</p>
        </div>

        <div class="contact-form">
            <h2>Envoyez-nous un message</h2>
            <form action="process_contact.php" method="POST">
                <label for="name">Nom :</label>
                <input type="text" name="name" id="name" required>

                <label for="email">Email :</label>
                <input type="email" name="email" id="email" required>

                <label for="subject">Objet :</label>
                <input type="text" name="subject" id="subject" required>

                <label for="message">Message :</label>
                <textarea name="message" id="message" rows="6" required></textarea>

                <button type="submit">Envoyer</button>
            </form>
        </div>
    </div>

    <!-- Section Google Maps (centrée sur 1 Rue Marie Curie, Plescop) -->
    <div class="map">
        <h2>Notre localisation</h2>
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2730.3151677172725!2d-2.760847384385631!3d47.65401697918508!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48101b56454892d7%3A0x1ba6a52b924728f4!2s1%20Rue%20Marie%20Curie%2C%2056890%20Plescop%2C%20France!5e0!3m2!1sfr!2sfr!4v1695821084746!5m2!1sfr!2sfr" 
            width="600" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>

</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
