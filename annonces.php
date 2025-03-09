<?php 
$pageTitle = "Annonces";
$currentPage = 'annonces';
include 'components/header.php';
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

                <form class="filters-form">
                    <!-- Catégorie -->
                    <div class="filter-group">
                        <h3>Catégorie</h3>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="checkbox" name="category" value="mouton">
                                <span class="checkbox-custom"></span>
                                Moutons
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="category" value="vache">
                                <span class="checkbox-custom"></span>
                                Vaches
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="category" value="chevre">
                                <span class="checkbox-custom"></span>
                                Chèvres
                            </label>
                        </div>
                    </div>

                    <!-- Prix -->
                    <div class="filter-group">
                        <h3>Prix</h3>
                        <div class="price-range">
                            <div class="price-inputs">
                                <input type="number" placeholder="Min" class="price-input">
                                <span>-</span>
                                <input type="number" placeholder="Max" class="price-input">
                            </div>
                            <button type="button" class="apply-price">Appliquer</button>
                        </div>
                    </div>

                    <!-- Localisation -->
                    <div class="filter-group">
                        <h3>Localisation</h3>
                        <select class="location-select">
                            <option value="">Toutes les régions</option>
                            <option value="dakar">Dakar</option>
                            <option value="thies">Thiès</option>
                            <option value="saint-louis">Saint-Louis</option>
                            <option value="kolda">Kolda</option>
                        </select>
                    </div>

                    <!-- Race -->
                    <div class="filter-group">
                        <h3>Race</h3>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="checkbox" name="race" value="ladoum">
                                <span class="checkbox-custom"></span>
                                Ladoum
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="race" value="bali-bali">
                                <span class="checkbox-custom"></span>
                                Bali-Bali
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="race" value="touabir">
                                <span class="checkbox-custom"></span>
                                Touabir
                            </label>
                        </div>
                    </div>
                </form>
            </aside>

            <!-- Liste des annonces -->
            <div class="annonces-content">
                <div class="annonces-header">
                    <div class="results-count">
                        <span>450 annonces trouvées</span>
                    </div>
                    <div class="sort-options">
                        <select class="sort-select">
                            <option value="recent">Plus récentes</option>
                            <option value="price-asc">Prix croissant</option>
                            <option value="price-desc">Prix décroissant</option>
                        </select>
                    </div>
                </div>

                <div class="listings-grid">
                    <?php for($i = 1; $i <= 9; $i++): ?>
                    <div class="listing-card">
                        <div class="listing-image">
                            <img src="assets/images/animal<?= $i ?>.jpg" alt="Animal <?= $i ?>">
                            <button class="favorite-btn" title="Ajouter aux favoris">
                                <i class="far fa-heart"></i>
                            </button>
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
                                <div class="listing-date">Il y a 2 jours</div>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <button class="page-btn prev" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <span class="page-dots">...</span>
                    <button class="page-btn">12</button>
                    <button class="page-btn next">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'components/footer.php'; ?> 