<?php 
$pageTitle = "Tableau de bord vendeur";
$currentPage = 'dashboard';
include 'components/header.php';

// TODO: Ajouter la vérification de l'authentification
?>

<div class="dashboard">
    <div class="container">
        <div class="dashboard-grid">
            <!-- Sidebar -->
            <aside class="dashboard-sidebar">
                <div class="user-info">
                    <img src="path/to/avatar.jpg" alt="Photo de profil" class="user-avatar">
                    <h3 class="user-name">John Doe</h3>
                    <p class="user-type">Vendeur</p>
                </div>

                <nav class="dashboard-nav">
                    <a href="#" class="dash-link active">
                        <i class="fas fa-home"></i>
                        Tableau de bord
                    </a>
                    <a href="#" class="dash-link">
                        <i class="fas fa-store"></i>
                        Mes annonces
                    </a>
                    <a href="#" class="dash-link">
                        <i class="fas fa-shopping-cart"></i>
                        Commandes
                    </a>
                    <a href="#" class="dash-link">
                        <i class="fas fa-comments"></i>
                        Messages
                    </a>
                    <a href="#" class="dash-link">
                        <i class="fas fa-chart-line"></i>
                        Statistiques
                    </a>
                    <a href="#" class="dash-link">
                        <i class="fas fa-cog"></i>
                        Paramètres
                    </a>
                </nav>
            </aside>

            <!-- Contenu principal -->
            <main class="dashboard-content">
                <div class="dashboard-header">
                    <h1>Tableau de bord</h1>
                    <a href="nouvelle-annonce.php" class="btn-primary">
                        <i class="fas fa-plus"></i>
                        Nouvelle annonce
                    </a>
                </div>

                <!-- Statistiques rapides -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Ventes totales</h3>
                            <p class="stat-value">12</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Annonces actives</h3>
                            <p class="stat-value">8</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Vues totales</h3>
                            <p class="stat-value">245</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Note moyenne</h3>
                            <p class="stat-value">4.8/5</p>
                        </div>
                    </div>
                </div>

                <!-- Dernières annonces -->
                <section class="dashboard-section">
                    <div class="section-header">
                        <h2>Dernières annonces</h2>
                        <a href="#" class="view-all">Voir tout</a>
                    </div>

                    <div class="annonces-grid">
                        <?php for($i = 0; $i < 3; $i++): ?>
                        <div class="annonce-card">
                            <div class="annonce-image">
                                <img src="path/to/animal<?= $i ?>.jpg" alt="Animal">
                                <span class="status-badge active">Disponible</span>
                            </div>
                            <div class="annonce-content">
                                <h3>Mouton Ladoum</h3>
                                <p class="annonce-price">250.000 FCFA</p>
                                <div class="annonce-stats">
                                    <span><i class="fas fa-eye"></i> 45 vues</span>
                                    <span><i class="fas fa-heart"></i> 12 favoris</span>
                                </div>
                                <div class="annonce-actions">
                                    <button class="btn-edit" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-delete" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button class="btn-view" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </section>
            </main>
        </div>
    </div>
</div>

<?php include 'components/footer.php'; ?> 