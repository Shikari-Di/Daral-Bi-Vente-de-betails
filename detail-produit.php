<?php 
$pageTitle = "Détail Produit";
$currentPage = 'annonces';
include 'components/header.php';
?>

<section class="product-detail">
    <div class="container">
        <div class="product-image-column">
            <div class="main-image-container">
                <img src="path/to/sheep.jpg" alt="Mouton" class="main-image">
                <button class="favorite-button">
                    <i class="fas fa-heart"></i>
                </button>
            </div>
        </div>

        <div class="product-info-column">
            <div class="product-header">
                <h1 class="product-title">Mouton</h1>
                <span class="product-status">Disponible</span>
            </div>

            <div class="product-price">
                <span class="price-amount">FCFA 250.000</span>
            </div>

            <div class="product-location">
                <i class="fas fa-map-marker-alt"></i>
                <span>Kolda</span>
            </div>

            <div class="product-description">
                <h3>Description</h3>
                <div class="description-list">
                    <div class="description-item">
                        <span class="icon">🐑</span>
                        <span class="label">Race :</span>
                        <span class="value">Ladoum</span>
                    </div>
                    <div class="description-item">
                        <span class="icon">📅</span>
                        <span class="label">Age :</span>
                        <span class="value">2 ans</span>
                    </div>
                    <div class="description-item">
                        <span class="icon">⚖️</span>
                        <span class="label">Poids :</span>
                        <span class="value">80 kg</span>
                    </div>
                    <div class="description-item">
                        <span class="icon">🌿</span>
                        <span class="label">Alimentation :</span>
                        <span class="value">Naturelle</span>
                    </div>
                    <div class="description-item">
                        <span class="icon">💉</span>
                        <span class="label">État de santé :</span>
                        <span class="value">Excellent, tous vaccins à jour</span>
                    </div>
                </div>
            </div>

            <div class="product-actions">
                <button class="buy-button">
                    <i class="fas fa-shopping-cart"></i>
                    Acheter maintenant
                </button>
                <button class="contact-button">
                    <i class="fas fa-envelope"></i>
                    Contacter le vendeur
                </button>
            </div>
        </div>
    </div>
</section>

<section class="similar-products">
    <div class="container">
        <h2 class="section-title">Produits similaires</h2>
        <div class="products-grid">
            <!-- Mouton 1 -->
            <a href="/detail-produit" class="product-card">
                <img src="path/to/sheep1.jpg" alt="Mouton 1" class="product-image">
                <div class="product-info">
                    <h3>Mouton Ladoum</h3>
                    <p class="description">2 ans, 75kg</p>
                    <p class="price">FCFA 200.000</p>
                </div>
            </a>
            <!-- Mouton 2 -->
            <a href="/detail-produit" class="product-card">
                <img src="path/to/sheep2.jpg" alt="Mouton 2" class="product-image">
                <div class="product-info">
                    <h3>Mouton Bali Bali</h3>
                    <p class="description">1.5 ans, 65kg</p>
                    <p class="price">FCFA 180.000</p>
                </div>
            </a>
            <!-- Mouton 3 -->
            <a href="/detail-produit" class="product-card">
                <img src="path/to/sheep3.jpg" alt="Mouton 3" class="product-image">
                <div class="product-info">
                    <h3>Mouton Touabir</h3>
                    <p class="description">2.5 ans, 85kg</p>
                    <p class="price">FCFA 300.000</p>
                </div>
            </a>
            <!-- Mouton 4 -->
            <a href="/detail-produit" class="product-card">
                <img src="path/to/sheep4.jpg" alt="Mouton 4" class="product-image">
                <div class="product-info">
                    <h3>Mouton Peulh</h3>
                    <p class="description">1.8 ans, 70kg</p>
                    <p class="price">FCFA 220.000</p>
                </div>
            </a>
            <!-- Mouton 5 -->
            <a href="/detail-produit" class="product-card">
                <img src="path/to/sheep5.jpg" alt="Mouton 5" class="product-image">
                <div class="product-info">
                    <h3>Mouton Waralé</h3>
                    <p class="description">2 ans, 80kg</p>
                    <p class="price">FCFA 280.000</p>
                </div>
            </a>
            <!-- Mouton 6 -->
            <a href="/detail-produit" class="product-card">
                <img src="path/to/sheep6.jpg" alt="Mouton 6" class="product-image">
                <div class="product-info">
                    <h3>Mouton Djallonké</h3>
                    <p class="description">1.5 ans, 60kg</p>
                    <p class="price">FCFA 150.000</p>
                </div>
            </a>
        </div>
    </div>
</section>

<div class="return-home">
    <a href="/" class="return-button">
        <i class="fas fa-arrow-left"></i>
        Retour à l'accueil
    </a>
</div>

<?php include 'components/footer.php'; ?>
