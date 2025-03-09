<?php 
$pageTitle = "Témoignages";
$currentPage = 'temoignages';
include 'components/header.php';
?>

<main class="testimonials-page">
    <div class="testimonials-hero">
        <div class="container">
            <h1 class="page-title">Témoignages de nos clients</h1>
        </div>
    </div>

    <div class="container">
        <div class="testimonials-grid">
            <?php for($i = 0; $i < 9; $i++): ?>
            <div class="testimonial-card">
                <div class="testimonial-header">
                    <img src="assets/images/testimonials/client<?= $i+1 ?>.jpg" alt="Client <?= $i+1 ?>" class="client-image">
                    <div class="client-info">
                        <h3 class="client-name"><?php 
                            $names = [
                                "Mamadou Diallo", "Fatou Sow", "Ousmane Fall",
                                "Aïssatou Ndiaye", "Ibrahima Diop", "Aminata Ba",
                                "Cheikh Gueye", "Rama Seck", "Abdoulaye Kane"
                            ];
                            echo $names[$i];
                        ?></h3>
                        <p class="client-location"><?php 
                            $locations = [
                                "Dakar", "Saint-Louis", "Thiès",
                                "Kaolack", "Ziguinchor", "Diourbel",
                                "Louga", "Tambacounda", "Kolda"
                            ];
                            echo $locations[$i];
                        ?></p>
                    </div>
                </div>
                <div class="testimonial-content">
                    <div class="quote-icon">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <p class="testimonial-text"><?php 
                        $testimonials = [
                            "Excellent service ! J'ai trouvé exactement ce que je cherchais. Le processus d'achat était simple et transparent.",
                            "Plateforme très fiable et professionnelle. Les vendeurs sont sérieux et les prix sont compétitifs.",
                            "Je recommande vivement Daaral Bi ! La qualité du bétail est exceptionnelle et le service client est remarquable.",
                            "Transactions sécurisées et service client réactif. Je suis très satisfait de mon expérience d'achat.",
                            "La meilleure plateforme pour acheter du bétail en ligne. Tout est bien organisé et professionnel.",
                            "Des vendeurs sérieux et des produits de qualité. La livraison a été rapide et bien gérée.",
                            "Interface facile à utiliser et grande variété de choix. Je reviendrai certainement pour mes prochains achats.",
                            "Service de livraison rapide et efficace. Le bétail correspond parfaitement aux descriptions.",
                            "Très satisfait de mes achats sur Daaral Bi. Une expérience client exceptionnelle du début à la fin."
                        ];
                        echo $testimonials[$i];
                    ?></p>
                    <div class="rating">
                        <?php for($j = 0; $j < 5; $j++): ?>
                            <i class="fas fa-star"></i>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</main>

<?php include 'components/footer.php'; ?> 