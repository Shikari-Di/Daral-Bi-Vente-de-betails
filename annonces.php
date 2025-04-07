<?php 
$pageTitle = "Annonces";
$currentPage = 'annonces';
include 'components/header.php';

// Données simulées
$annonces = [
    ['id' => 1, 'categorie' => 'mouton', 'race' => 'ladoum', 'prix' => 250000, 'region' => 'dakar', 'poids' => 45, 'age' => 18, 'image' => 'animal1.jpg'],
    ['id' => 2, 'categorie' => 'vache', 'race' => 'bali-bali', 'prix' => 400000, 'region' => 'kolda', 'poids' => 300, 'age' => 36, 'image' => 'animal2.jpg'],
    ['id' => 3, 'categorie' => 'chevre', 'race' => 'touabir', 'prix' => 150000, 'region' => 'thies', 'poids' => 35, 'age' => 12, 'image' => 'animal3.jpg'],
    ['id' => 4, 'categorie' => 'mouton', 'race' => 'ladoum', 'prix' => 300000, 'region' => 'dakar', 'poids' => 50, 'age' => 24, 'image' => 'animal4.jpg'],
    // ... ajoute d'autres annonces si tu veux
];

// Récupération des filtres
$filtreCategorie = $_GET['category'] ?? [];
$filtreRace = $_GET['race'] ?? [];
$filtreMinPrix = $_GET['min'] ?? null;
$filtreMaxPrix = $_GET['max'] ?? null;
$filtreRegion = $_GET['region'] ?? '';

// Filtrage des annonces
$annoncesFiltrees = array_filter($annonces, function ($annonce) use (
    $filtreCategorie, $filtreRace, $filtreMinPrix, $filtreMaxPrix, $filtreRegion
) {
    if ($filtreCategorie && !in_array($annonce['categorie'], $filtreCategorie)) return false;
    if ($filtreRace && !in_array($annonce['race'], $filtreRace)) return false;
    if ($filtreMinPrix && $annonce['prix'] < $filtreMinPrix) return false;
    if ($filtreMaxPrix && $annonce['prix'] > $filtreMaxPrix) return false;
    if ($filtreRegion && $annonce['region'] !== $filtreRegion) return false;
    return true;
});
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
                </div>

                <form class="filters-form" method="GET" action="annonces.php">
                    <!-- Catégorie -->
                    <div class="filter-group">
                        <h3>Catégorie</h3>
                        <label><input type="checkbox" name="category[]" value="mouton" <?= in_array('mouton', $filtreCategorie) ? 'checked' : '' ?>> Mouton</label>
                        <label><input type="checkbox" name="category[]" value="vache" <?= in_array('vache', $filtreCategorie) ? 'checked' : '' ?>> Vache</label>
                        <label><input type="checkbox" name="category[]" value="chevre" <?= in_array('chevre', $filtreCategorie) ? 'checked' : '' ?>> Chèvre</label>
                    </div>

                    <!-- Prix -->
                    <div class="filter-group">
                        <h3>Prix</h3>
                        <input type="number" name="min" placeholder="Min" value="<?= htmlspecialchars($filtreMinPrix) ?>">
                        <input type="number" name="max" placeholder="Max" value="<?= htmlspecialchars($filtreMaxPrix) ?>">
                    </div>

                    <!-- Localisation -->
                    <div class="filter-group">
                        <h3>Localisation</h3>
                        <select name="region">
                            <option value="">Toutes</option>
                            <option value="dakar" <?= $filtreRegion == 'dakar' ? 'selected' : '' ?>>Dakar</option>
                            <option value="thies" <?= $filtreRegion == 'thies' ? 'selected' : '' ?>>Thiès</option>
                            <option value="saint-louis" <?= $filtreRegion == 'saint-louis' ? 'selected' : '' ?>>Saint-Louis</option>
                            <option value="kolda" <?= $filtreRegion == 'kolda' ? 'selected' : '' ?>>Kolda</option>
                        </select>
                    </div>

                    <!-- Race -->
                    <div class="filter-group">
                        <h3>Race</h3>
                        <label><input type="checkbox" name="race[]" value="ladoum" <?= in_array('ladoum', $filtreRace) ? 'checked' : '' ?>> Ladoum</label>
                        <label><input type="checkbox" name="race[]" value="bali-bali" <?= in_array('bali-bali', $filtreRace) ? 'checked' : '' ?>> Bali-Bali</label>
                        <label><input type="checkbox" name="race[]" value="touabir" <?= in_array('touabir', $filtreRace) ? 'checked' : '' ?>> Touabir</label>
                    </div>

                    <button type="submit">Filtrer</button>
                </form>
            </aside>

            <!-- Liste des annonces -->
            <div class="annonces-content">
                <div class="annonces-header">
                    <span><?= count($annoncesFiltrees) ?> annonces trouvées</span>
                </div>

                <div class="listings-grid">
                    <?php if (empty($annoncesFiltrees)): ?>
                        <p>Aucune annonce ne correspond à vos critères.</p>
                    <?php else: ?>
                        <?php foreach ($annoncesFiltrees as $annonce): ?>
                            <div class="listing-card">
                                <div class="listing-image">
                                    <img src="assets/images/<?= $annonce['image'] ?>" alt="Animal <?= $annonce['id'] ?>">
                                </div>
                                <div class="listing-content">
                                    <h3>Mouton <?= ucfirst($annonce['race']) ?></h3>
                                    <div class="listing-price"><?= number_format($annonce['prix'], 0, ',', ' ') ?> FCFA</div>
                                    <div class="listing-details">
                                        <span><?= $annonce['poids'] ?> kg</span> | 
                                        <span><?= $annonce['age'] ?> mois</span>
                                    </div>
                                    <div class="listing-footer">
                                        <span><?= ucfirst($annonce['region']) ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'components/footer.php'; ?>
