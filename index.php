<?php
session_start();

$pageTitle = "Accueil";
$currentPage = 'home';
include 'components/header.php';

$cartItems = $_SESSION['cart'] ?? [];

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<section class="hero">
    <h1 class="hero-title">Bienvenue chez <span class="highlight">Daaral bi</span></h1>
    <p class="hero-description">
        Votre plateforme en ligne dédiée à l'achat et à la vente de bétail ! Que vous soyez un éleveur souhaitant proposer vos animaux ou un acheteur à la recherche du meilleur bétail, nous mettons à votre disposition un espace sécurisé et facile à utiliser.
    </p>
    <p class="hero-tagline">DAARAL BI, le bétail à portée de clics !</p>
    <div class="hero-buttons">
        <a href="annonces.php" class="button button-primary">
            <i class="fas fa-shopping-cart"></i>
            Acheter maintenant
        </a>
        <a href="login.php?from=vendor" class="button button-secondary">
            <i class="fas fa-store"></i>
            Devenir vendeur
        </a>
    </div>
</section>

<section class="popular-listings">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">Annonces Populaires</h1>
            <p class="section-description">
                Découvrez notre sélection des meilleures offres de bétail, choisies pour leur qualité exceptionnelle et leur excellent rapport qualité-prix.
            </p>
        </div>

        <div class="listings-grid">
            <?php for($i = 1; $i <= 6; $i++): ?>
            <div class="listing-card">
                <div class="listing-image">
                    <img src="img\moutonladoum2.jpeg" alt="Animal <?= $i ?>">
                    <span class="listing-badge">Populaire</span>
                </div>
                <div class="listing-content">
                    <h3 class="listing-title">Mouton Ladoum de race pure</h3>
                    <div class="listing-price">250.000 FCFA</div>
                    <div class="listing-details">
                        <span class="listing-detail">
                            <i class="fas fa-weight"></i> 45 kg
                        </span>
                        <span class="listing-detail">
                            <i class="fas fa-calendar"></i> 18 mois
                        </span>
                    </div>
                    <div class="listing-footer">
                        <div class="listing-location">
                            <i class="fas fa-map-marker-alt"></i>
                            Dakar, Sénégal
                        </div>
                        <div class="listing-actions">
                            <form action="panier.php" method="post">
                                <input type="hidden" name="product_id" value="<?= $i ?>">
                                <input type="hidden" name="product_name" value="Mouton Ladoum de race pure">
                                <input type="hidden" name="seller" value="Éleveur Exemple">
                                <input type="hidden" name="price" value="250000">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="action-btn" title="Ajouter au Panier">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </form>
                            <button class="action-btn" title="Ajouter aux favoris">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="action-btn" title="Partager">
                                <i class="fas fa-share-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<section class="testimonials">
    <h2 class="section-title">Ce que disent nos clients</h2>
    <div class="testimonials-grid">
        <div class="testimonial-card">
            <img src="img\mamadoudiallo.png" alt="Client 1" class="client-image">
            <p class="testimonial-text">"Excellent service ! J'ai trouvé exactement ce que je cherchais."</p>
            <p class="client-name">Mamadou Diallo</p>
            <p class="client-location">Dakar</p>
        </div>
        <div class="testimonial-card">
            <img src="img\fatimasow.png" alt="Client 2" class="client-image">
            <p class="testimonial-text">"Plateforme très fiable et professionnelle."</p>
            <p class="client-name">Fatou Sow</p>
            <p class="client-location">Saint-Louis</p>
        </div>
        <div class="testimonial-card">
            <img src="img\ousmanefaye.png" alt="Client 3" class="client-image">
            <p class="testimonial-text">"Je recommande vivement Daaral Bi !"</p>
            <p class="client-name">Ousmane Fall</p>
            <p class="client-location">Thiès</p>
        </div>
    </div>
</section>

<?php include 'components/footer.php'; ?>

<style>
.user-profile-bar {
    background-color: #f8f9fa;
    padding: 15px 0;
    margin-bottom: 30px;
    border-bottom: 1px solid #e9ecef;
}

.profile-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.user-email {
    font-weight: 500;
    color: #333;
}

.social-auth {
    display: flex;
    gap: 10px;
}

.btn-social {
    display: flex;
    align-items: center;
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    color: white;
    font-size: 14px;
    cursor: pointer;
    transition: opacity 0.3s;
}

.btn-social:hover {
    opacity: 0.9;
}

.btn-social i {
    margin-right: 8px;
}

.btn-facebook {
    background-color: #3b5998;
}

.btn-google {
    background-color: #db4437;
}

.btn-logout {
    display: flex;
    align-items: center;
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    color: #dc3545;
    font-size: 14px;
    text-decoration: none;
    transition: opacity 0.3s;
}

.btn-logout:hover {
    opacity: 0.9;
}

.btn-logout i {
    margin-right: 8px;
}
</style>
