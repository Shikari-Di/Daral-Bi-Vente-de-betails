<?php 
$pageTitle = "Accueil";
$currentPage = 'home';
include 'components/header.php';
?>

<section class="hero">
    <h1 class="hero-title">Bienvenue chez <span class="highlight">Daaral bi</span></h1>
    <p class="hero-description">
        Votre plateforme en ligne dédiée à l'achat et à la vente de bétail ! Que vous soyez un éleveur souhaitant proposer vos animaux ou un acheteur à la recherche du meilleur bétail, nous mettons à votre disposition un espace sécurisé et facile à utiliser.
    </p>
    <p class="hero-tagline">DAARAL BI, le bétail à portée de clics !</p>
    <div class="hero-buttons">
        <a href="/acheter" class="button button-primary">
            <i class="fas fa-shopping-cart"></i>
            Acheter maintenant
        </a>
        <a href="/devenir-vendeur" class="button button-secondary">
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
                    <img src="assets/images/animal<?= $i ?>.jpg" alt="Animal <?= $i ?>">
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
            <img src="path/to/client2.jpg" alt="Client 2" class="client-image">
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
