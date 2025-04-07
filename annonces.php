<?php 
$pageTitle = "Annonces";
$currentPage = 'annonces';
include 'components/header.php';

// Exemple de données d'annonces
$annonces = [
    ['id' => 1, 'title' => 'Mouton Ladoum de race pure', 'price' => 250000, 'category' => 'mouton', 'location' => 'dakar', 'race' => 'ladoum'],
    ['id' => 2, 'title' => 'Vache Bali-Bali', 'price' => 300000, 'category' => 'vache', 'location' => 'thies', 'race' => 'bali-bali'],
    ['id' => 3, 'title' => 'Chevre Touabir', 'price' => 150000, 'category' => 'chevre', 'location' => 'saint-louis', 'race' => 'touabir'],
];

// Récupérer les filtres
$categoryFilter = isset($_GET['category']) ? (array)$_GET['category'] : [];
$priceMin = isset($_GET['price_min']) && is_numeric($_GET['price_min']) ? (int)$_GET['price_min'] : null;
$priceMax = isset($_GET['price_max']) && is_numeric($_GET['price_max']) ? (int)$_GET['price_max'] : null;
$locationFilter = isset($_GET['location']) ? (array)$_GET['location'] : [];
$raceFilter = isset($_GET['race']) ? (array)$_GET['race'] : [];

// Filtrer les annonces
$filteredAnnonces = array_filter($annonces, function($annonce) use ($categoryFilter, $priceMin, $priceMax, $locationFilter, $raceFilter) {
    if (!empty($categoryFilter) && !in_array($annonce['category'], $categoryFilter)) return false;
    if ($priceMin !== null && $annonce['price'] < $priceMin) return false;
    if ($priceMax !== null && $annonce['price'] > $priceMax) return false;
    if (!empty($locationFilter) && !in_array($annonce['location'], $locationFilter)) return false;
    if (!empty($raceFilter) && !in_array($annonce['race'], $raceFilter)) return false;
    return true;
});

// Options pour les filtres
$locations = [
    'dakar' => 'Dakar',
    'thies' => 'Thiès',
    'saint-louis' => 'Saint-Louis',
    'kolda' => 'Kolda'
];
?>

<style>
/* Style pour les cases à cocher personnalisées */
.filter-option {
    display: block;
    position: relative;
    padding-left: 25px;
    margin-bottom: 10px;
    cursor: pointer;
}

.filter-option input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 18px;
    width: 18px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 3px;
}

.filter-option:hover input ~ .checkmark {
    background-color: #f1f1f1;
}

.filter-option input:checked ~ .checkmark {
    background-color: #4CAF50;
    border-color: #4CAF50;
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.filter-option input:checked ~ .checkmark:after {
    display: block;
}

.filter-option .checkmark:after {
    left: 6px;
    top: 2px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}
</style>

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
                    <?php if(!empty($_GET)): ?>
                        <a href="annonces.php" class="reset-filters">Réinitialiser</a>
                    <?php endif; ?>
                </div>
                <form method="get">
                    <!-- Catégorie -->
                    <div class="filter-group">
                        <h3>Catégorie</h3>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="checkbox" name="category[]" value="mouton" <?= in_array('mouton', $categoryFilter) ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                                Moutons
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="category[]" value="vache" <?= in_array('vache', $categoryFilter) ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                                Vaches
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="category[]" value="chevre" <?= in_array('chevre', $categoryFilter) ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                                Chèvres
                            </label>
                        </div>
                    </div>

                    <!-- Localisation -->
                    <div class="filter-group">
                        <h3>Localisation</h3>
                        <div class="filter-options">
                            <?php foreach ($locations as $value => $label): ?>
                                <label class="filter-option">
                                    <input type="checkbox" name="location[]" value="<?= $value ?>" <?= in_array($value, $locationFilter) ? 'checked' : '' ?>>
                                    <span class="checkmark"></span>
                                    <?= $label ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Race -->
                    <div class="filter-group">
                        <h3>Race</h3>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="checkbox" name="race[]" value="ladoum" <?= in_array('ladoum', $raceFilter) ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                                Ladoum
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="race[]" value="bali-bali" <?= in_array('bali-bali', $raceFilter) ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                                Bali-Bali
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="race[]" value="touabir" <?= in_array('touabir', $raceFilter) ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                                Touabir
                            </label>
                        </div>
                    </div>

                    <!-- Prix -->
                    <div class="filter-group">
                        <h3>Prix (FCFA)</h3>
                        <div class="price-range">
                            <input type="number" name="price_min" value="<?= $priceMin ?>" placeholder="Min" min="0">
                            <span>-</span>
                            <input type="number" name="price_max" value="<?= $priceMax ?>" placeholder="Max" min="0">
                        </div>
                    </div>

                    <button type="submit" class="btn-apply">Appliquer</button>
                </form>
            </aside>

            <!-- Liste des annonces -->
            <div class="annonces-content">
                <div class="results-count">
                    <span><?= count($filteredAnnonces) ?> annonce<?= count($filteredAnnonces) > 1 ? 's' : '' ?> trouvée<?= count($filteredAnnonces) > 1 ? 's' : '' ?></span>
                </div>
                
                <?php if(empty($filteredAnnonces)): ?>
                    <div class="no-results">
                        <p>Aucune annonce ne correspond à vos critères.</p>
                    </div>
                <?php else: ?>
                    <div class="listings-grid">
                        <?php foreach ($filteredAnnonces as $annonce): ?>
                        <div class="listing-card">
                            <div class="listing-image">
                                <img src="assets/images/animal<?= $annonce['id'] ?>.jpg" alt="<?= htmlspecialchars($annonce['title']) ?>">
                            </div>
                            <div class="listing-content">
                                <h3 class="listing-title"><?= htmlspecialchars($annonce['title']) ?></h3>
                                <div class="listing-price"><?= number_format($annonce['price'], 0, ',', ' ') ?> FCFA</div>
                                <div class="listing-location"><?= $locations[$annonce['location']] ?? $annonce['location'] ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include 'components/footer.php'; ?>

