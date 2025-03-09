<?php 
$pageTitle = "À propos";
$currentPage = 'about';
include 'components/header.php';
?>

<section class="about-page">
    <div class="container">
        <div class="about-content">
            <div class="about-text">
                <h1>À propos de Daaral Bi</h1>
                <p class="mission">Notre mission est de révolutionner le commerce de bétail au Sénégal en connectant éleveurs et acheteurs sur une plateforme moderne et sécurisée.</p>
                
                <div class="about-features">
                    <div class="feature">
                        <i class="fas fa-shield-alt"></i>
                        <h3>Sécurité garantie</h3>
                        <p>Transactions sécurisées et vérification des vendeurs</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-handshake"></i>
                        <h3>Confiance</h3>
                        <p>Système d'évaluation transparent des vendeurs</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-truck"></i>
                        <h3>Livraison</h3>
                        <p>Service de livraison disponible dans tout le pays</p>
                    </div>
                </div>
            </div>
            
            <div class="team-section">
                <h2>Notre équipe</h2>
                <div class="team-grid">
                    <!-- Répéter pour chaque membre -->
                    <div class="team-member">
                        <img src="path/to/member.jpg" alt="Nom du membre">
                        <h3>Nom Prénom</h3>
                        <p>Poste occupé</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'components/footer.php'; ?> 