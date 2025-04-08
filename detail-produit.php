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
                <span class="price"><?php echo number_format($product['prix'], 0, ',', ' '); ?> FCFA</span>
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

            <div class="product-actions">
                <form method="POST" action="ajouter-au-panier.php" class="mb-3">
                    <input type="hidden" name="annonce_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="action-button cart-button">
                        <i class="fas fa-cart-plus"></i> 
                        <span>Ajouter au panier</span>
                    </button>
                </form>
                
                <form method="POST" action="achat-direct.php">
                    <input type="hidden" name="annonce_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="action-button buy-button">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Acheter maintenant</span>
                    </button>
                </form>
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

<style>
.product-actions {
    margin-top: 30px;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.action-button {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 15px 25px;
    border: none;
    border-radius: 8px;
    font-size: 1.1em;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.action-button i {
    margin-right: 10px;
    font-size: 1.2em;
}

.cart-button {
    background-color: #fff;
    color: #3498db;
    border: 2px solid #3498db;
    margin-bottom: 15px;
}

.cart-button:hover {
    background-color: #3498db;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.2);
}

.buy-button {
    background-color: #2ecc71;
    color: #fff;
    border: 2px solid #2ecc71;
}

.buy-button:hover {
    background-color: #27ae60;
    border-color: #27ae60;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(46, 204, 113, 0.2);
}

.product-actions form {
    margin: 0;
}

@media (max-width: 768px) {
    .action-button {
        padding: 12px 20px;
        font-size: 1em;
    }
    
    .product-actions {
        padding: 15px;
        margin-top: 20px;
    }
}
</style>

<?php 
include 'components/footer.php'; 
?>
