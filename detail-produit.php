<?php 
$pageTitle = "Détail Produit";
$currentPage = 'annonces';
include 'components/header.php';
include 'config/database.php'; // Fichier de connexion à la base de données

$productID = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    $query = $pdo->prepare("
        SELECT 
            annonces.*, 
            localisations.nom AS localisation, 
            categories.nom AS categorie, 
            races.nom AS race, 
            utilisateurs.nom AS vendeur, 
            utilisateurs.numero AS telephone
        FROM annonces
        INNER JOIN localisations ON annonces.localisation_id = localisations.id
        INNER JOIN categories ON annonces.categorie_id = categories.id
        INNER JOIN races ON annonces.race_id = races.id
        INNER JOIN utilisateurs ON annonces.utilisateur_id = utilisateurs.id
        WHERE annonces.id = :id
    ");
    $query->bindParam(':id', $productID, PDO::PARAM_INT);
    $query->execute();
    $product = $query->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "<p>Produit non disponible.</p>";
        include 'components/footer.php';
        exit;
    }
} catch (PDOException $e) {
    echo "<p>Erreur : " . $e->getMessage() . "</p>";
    include 'components/footer.php';
    exit;
}
?>
<section class="product-detail">
    <div class="container">
        <div class="product-image-column">
            <div class="main-image-container">
                <img src="assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['titre']); ?>" class="main-image">
                <button class="favorite-button">
                    <i class="fas fa-heart"></i>
                </button>
            </div>
        </div>

        <div class="product-info-column">
            <div class="product-header">
                <h1 class="product-title"><?php echo htmlspecialchars($product['titre']); ?></h1>
                <span class="product-status">Publié par : <?php echo htmlspecialchars($product['vendeur']); ?></span>
                
            </div>

            <div>
                <span class="product-phone">Contact : <?php echo htmlspecialchars($product['telephone']); ?></span>
            </div>
            
            <div class="product-price">
                <span class="price-amount">FCFA <?php echo number_format($product['prix'], 0, ',', ' '); ?></span>
            </div>

            <div class="product-location">
                <i class="fas fa-map-marker-alt"></i>
                <span><?php echo htmlspecialchars($product['localisation']); ?></span>
            </div>

            <div class="product-description">
                <h3>Description</h3>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            </div>

            <div class="product-details">
                <div class="description-item">
                    <span class="icon">📅</span>
                    <span class="label">Âge :</span>
                    <span class="value"><?php echo htmlspecialchars($product['age']); ?> mois</span>
                </div>
                <div class="description-item">
                    <span class="icon">⚖️</span>
                    <span class="label">Poids :</span>
                    <span class="value"><?php echo htmlspecialchars($product['poids']); ?> kg</span>
                </div>
                <div class="description-item">
                    <span class="icon">🐑</span>
                    <span class="label">Race :</span>
                    <span class="value"><?php echo htmlspecialchars($product['race']); ?></span>
                </div>
                <div class="description-item">
                    <span class="icon">📂</span>
                    <span class="label">Catégorie :</span>
                    <span class="value"><?php echo htmlspecialchars($product['categorie']); ?></span>
                </div>
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
    <a href="index.php" class="return-button">
        <i class="fas fa-arrow-left"></i>
        Retour à l'accueil
    </a>
</div>

<?php 
include 'components/footer.php'; 
?>
