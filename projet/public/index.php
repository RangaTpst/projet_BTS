<?php

require_once '../core/product-functions.php';

// Récupérer les 3 derniers produits
$query = "SELECT * FROM products ORDER BY created_at DESC LIMIT 4";
$stmt = $pdo->prepare($query);
$stmt->execute();
$recentProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - SR Nails</title>
    <link rel="stylesheet" href="../public/assets/css/styles.css">
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <!-- Section Hero -->
    <section class="hero">
        <h1>Bienvenue chez SR Nails</h1>
        <p>"Des ongles sublimes, posés en un clin d'œil."</p>
    </section>

    <!-- Carrousel -->
    <div class="carousel-container">
        <div class="carousel-slide active">
            <img src="../public/assets/image/about-slide.jpg" alt="À propos de SR Nails">
            <div class="carousel-caption">
                <h2>À propos de SR Nails</h2>
                <p>Nous offrons des produits de qualité pour embellir vos ongles.</p>
                <a href="about.php" class="btn">En savoir plus</a>
            </div>
        </div>
        <div class="carousel-slide">
            <img src="../public/assets/image/product-slide.jpg" alt="Nos produits">
            <div class="carousel-caption">
                <h2>Nos Produits</h2>
                <p>Découvrez notre collection de faux ongles de qualité.</p>
                <a href="products.php" class="btn">Voir nos produits</a>
            </div>
        </div>
        <div class="carousel-slide">
            <img src="../public/assets/image/contact-slide.jpg" alt="Contactez-nous">
            <div class="carousel-caption">
                <h2>Nous Contacter</h2>
                <p>Besoin d'aide ? Contactez notre équipe pour plus d'informations.</p>
                <a href="contact.php" class="btn">Nous contacter</a>
            </div>
        </div>

        <!-- Boutons de contrôle -->
        <button class="prev-btn" onclick="plusSlides(-1)">&#10094;</button>
        <button class="next-btn" onclick="plusSlides(1)">&#10095;</button>
    </div>

    <!-- Section des produits récents -->
    <section class="products-section">
        <h2>Nos derniers produits</h2>
        <div class="products-grid">
            <?php foreach ($recentProducts as $product): ?>
                <a href="product-details.php?id=<?= $product['id'] ?>" class="product-card">
                    <div class="product-image">
                        <img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="overlay">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p><?= htmlspecialchars($product['description']) ?></p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>

    <script>
    let slideIndex = 0;
    const slides = document.querySelectorAll('.carousel-slide');
    showSlides(slideIndex);

    // Fonction pour afficher les slides
    function showSlides(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            slide.style.opacity = '0'; // Masquer les autres slides
        });
        slides[index].classList.add('active');
        slides[index].style.opacity = '1'; // Afficher la slide active
    }

    // Fonction pour changer de slide manuellement
    function plusSlides(n) {
        slideIndex += n;
        if (slideIndex >= slides.length) {
            slideIndex = 0;
        }
        if (slideIndex < 0) {
            slideIndex = slides.length - 1;
        }
        showSlides(slideIndex);
    }

    // Changement automatique des slides toutes les 5 secondes
    setInterval(() => {
        slideIndex++;
        if (slideIndex >= slides.length) {
            slideIndex = 0;
        }
        showSlides(slideIndex);
    }, 5000); // Change toutes les 5 secondes
</script>


</body>
</html>
