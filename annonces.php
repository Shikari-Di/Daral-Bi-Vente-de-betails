<?php 
$pageTitle = "Annonces";
$currentPage = 'annonces';
include 'components/header.php';
include 'db.php'; // Inclure la connexion à la base de données

$whereClauses = [];
$params = [];

// Filtrer par catégorie
if (!empty($_GET['category'])) {
    $whereClauses[] = "categorie_id = ?";
    $params[] = $_GET['category'];
}

// Filtrer par prix
if (!empty($_GET['min_price']) && !empty($_GET['max_price'])) {
    $whereClauses[] = "prix BETWEEN ? AND ?";
    $params[] = $_GET['min_price'];
    $params[] = $_GET['max_price'];
}

// Filtrer par localisation
if (!empty($_GET['location'])) {
    $whereClauses[] = "localisation_id = ?";
    $params[] = $_GET['location'];
}

// Filtrer par race
if (!empty($_GET['race'])) {
    $whereClauses[] = "race_id = ?";
    $params[] = $_GET['race'];
}

// Construire la requête SQL
$sql = "SELECT 
            annonces.id, 
            annonces.titre, 
            annonces.description, 
            annonces.prix, 
            annonces.poids, 
            annonces.age, 
            annonces.image, 
            localisations.nom AS localisation, 
            annonces.date_creation 
        FROM annonces
        JOIN localisations ON annonces.localisation_id = localisations.id";

if (!empty($whereClauses)) {
    $sql .= " WHERE " . implode(' AND ', $whereClauses);
}

$sql .= " ORDER BY annonces.date_creation DESC";

// Pagination
$limit = 10; // Nombre d'annonces par page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql .= " LIMIT $limit OFFSET $offset";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcul du nombre total d'annonces
$totalAnnonces = $pdo->query("SELECT COUNT(*) FROM annonces")->fetchColumn();
$totalPages = ceil($totalAnnonces / $limit);
?>

<section class="annonces-page">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">Nos Annonces</h1>
            <p class="section-description">
                Découvrez notre sélection de bétail de qualité. Utilisez les filtres pour affiner votre recherche.
            </p>
        </div>

        <div class="annonces-container">
            <!-- Filtres -->
            <aside class="filters-sidebar">
                <div class="filters-header">
                    <h2>Filtres</h2>
                    <button class="reset-filters">
                        <i class="fas fa-redo-alt"></i>
                        Réinitialiser
                    </button>
                </div>

                <form class="filters-form" method="GET">
                    <!-- Catégorie -->
                    <div class="filter-group">
                        <h3>Catégorie</h3>
                        <select name="category" class="category-select">
                            <option value="">Toutes les catégories</option>
                            <?php
                            $categories = $pdo->query("SELECT id, nom FROM categories")->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($categories as $categorie): ?>
                                <option value="<?php echo $categorie['id']; ?>" 
                                    <?php echo (!empty($_GET['category']) && $_GET['category'] == $categorie['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($categorie['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Prix -->
                    <div class="filter-group">
                        <h3>Prix</h3>
                        <div class="price-range">
                            <div class="price-inputs">
                                <input type="number" name="min_price" placeholder="Min" class="price-input">
                                <span>-</span>
                                <input type="number" name="max_price" placeholder="Max" class="price-input">
                            </div>
                        </div>
                    </div>

                    <!-- Localisation -->
                    <div class="filter-group">
                        <h3>Localisation</h3>
                        <select name="location" class="location-select">
                            <option value="">Toutes les régions</option>
                            <?php
                            $localisations = $pdo->query("SELECT id, nom FROM localisations")->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($localisations as $localisation): ?>
                                <option value="<?php echo $localisation['id']; ?>">
                                    <?php echo htmlspecialchars($localisation['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Race -->
                    <div class="filter-group">
                        <h3>Race</h3>
                        <select name="race" class="race-select">
                            <option value="">Toutes les races</option>
                            <?php
                            $races = $pdo->query("SELECT id, nom FROM races")->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($races as $race): ?>
                                <option value="<?php echo $race['id']; ?>" 
                                    <?php echo (!empty($_GET['race']) && $_GET['race'] == $race['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($race['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="apply-filters">Appliquer les filtres</button>
                </form>
            </aside>

            <!-- Liste des annonces -->
            <div class="annonces-content">
                <div class="annonces-header">
                    <div class="results-count">
                        <span><?php echo $totalAnnonces; ?> annonces trouvées</span>
                    </div>
                </div>

                <div class="listings-grid">
                    <?php if (!empty($annonces)): ?>
                        <?php foreach ($annonces as $annonce): ?>
                            <div class="listing-card">
                                <div class="listing-image">
                                    <?php 
                                    $imagePath = "assets/images/" . htmlspecialchars($annonce['image']);
                                    if (!file_exists($imagePath)) {
                                        $imagePath = "assets/images/default.jpg"; // Image par défaut
                                    }
                                    ?>
                                    <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($annonce['titre']); ?>">
                                    <form action="ajouter_favori.php" method="POST">
                                        <input type="hidden" name="annonce_id" value="<?php echo $annonce['id']; ?>">
                                        <button type="submit" class="favorite-btn" title="Ajouter aux favoris">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="listing-content">
                                    <h3 class="listing-title"><?php echo htmlspecialchars($annonce['titre']); ?></h3>
                                    <div class="listing-price"><?php echo number_format($annonce['prix'], 0, ',', ' '); ?> FCFA</div>
                                    <button class="details-button">
                                        <a href="detail-produit.php?id=<?php echo $annonce['id']; ?>">Détails</a>
                                    </button>
                                    <div class="listing-footer">
                                        <div class="listing-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo htmlspecialchars($annonce['localisation']); ?>
                                        </div>
                                        <div class="listing-date">
                                            <?php echo date('d/m/Y', strtotime($annonce['date_creation'])); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucune annonce trouvée.</p>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>" class="page-btn prev">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" class="page-btn <?php echo ($i === $page) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>" class="page-btn next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'components/footer.php'; ?>