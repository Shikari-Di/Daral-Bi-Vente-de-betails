<?php 
$pageTitle = "Conditions Générales";
$currentPage = 'conditions';
include 'components/header.php';
?>

<div class="terms-page">
    <div class="container">
        <h1 class="page-title">Conditions Générales d'Utilisation</h1>
        
        <div class="terms-content">
            <section class="terms-section">
                <h2>1. Introduction</h2>
                <p>Les présentes conditions générales régissent l'utilisation de la plateforme Daaral Bi, accessible via www.daaralbi.sn. En utilisant notre plateforme, vous acceptez d'être lié par ces conditions.</p>
            </section>

            <section class="terms-section">
                <h2>2. Définitions</h2>
                <ul class="terms-list">
                    <li><strong>Plateforme :</strong> Désigne le site web Daaral Bi</li>
                    <li><strong>Utilisateur :</strong> Toute personne utilisant la plateforme</li>
                    <li><strong>Vendeur :</strong> Utilisateur proposant du bétail à la vente</li>
                    <li><strong>Acheteur :</strong> Utilisateur souhaitant acquérir du bétail</li>
                </ul>
            </section>

            <section class="terms-section">
                <h2>3. Services proposés</h2>
                <p>Daaral Bi est une plateforme de mise en relation entre vendeurs et acheteurs de bétail au Sénégal. Nous proposons :</p>
                <ul class="terms-list">
                    <li>Publication d'annonces de vente de bétail</li>
                    <li>Service de mise en relation sécurisée</li>
                    <li>Solutions de paiement sécurisées</li>
                    <li>Service de livraison du bétail</li>
                </ul>
            </section>

            <section class="terms-section">
                <h2>4. Responsabilités</h2>
                <h3>4.1 Responsabilités de la plateforme</h3>
                <p>Daaral Bi s'engage à :</p>
                <ul class="terms-list">
                    <li>Assurer la disponibilité et le bon fonctionnement de la plateforme</li>
                    <li>Vérifier l'identité des vendeurs</li>
                    <li>Sécuriser les transactions</li>
                    <li>Protéger les données personnelles des utilisateurs</li>
                </ul>

                <h3>4.2 Responsabilités des utilisateurs</h3>
                <p>Les utilisateurs s'engagent à :</p>
                <ul class="terms-list">
                    <li>Fournir des informations exactes</li>
                    <li>Respecter les lois en vigueur</li>
                    <li>Ne pas utiliser la plateforme à des fins frauduleuses</li>
                </ul>
            </section>

            <section class="terms-section">
                <h2>5. Conditions de vente</h2>
                <div class="terms-subsection">
                    <h3>5.1 Prix et paiement</h3>
                    <p>Les prix sont indiqués en FCFA. Le paiement peut être effectué par :</p>
                    <ul class="terms-list">
                        <li>Carte bancaire</li>
                        <li>Mobile Money (Orange Money, Wave, Free Money)</li>
                        <li>Paiement à la livraison (avec acompte)</li>
                    </ul>
                </div>

                <div class="terms-subsection">
                    <h3>5.2 Livraison</h3>
                    <p>La livraison est assurée par nos partenaires agréés. Les délais et frais de livraison varient selon la destination.</p>
                </div>
            </section>

            <section class="terms-section">
                <h2>6. Protection des données</h2>
                <p>Nous nous engageons à protéger vos données personnelles conformément à la législation en vigueur. Pour plus d'informations, consultez notre politique de confidentialité.</p>
            </section>

            <section class="terms-section">
                <h2>7. Modification des conditions</h2>
                <p>Daaral Bi se réserve le droit de modifier les présentes conditions générales. Les utilisateurs seront informés des modifications par email.</p>
            </section>

            <div class="terms-footer">
                <p>Dernière mise à jour : <?php echo date('d/m/Y'); ?></p>
                <p>Pour toute question concernant nos conditions générales, contactez-nous à <a href="mailto:contact@daaralbi.sn">contact@daaralbi.sn</a></p>
            </div>

            <div class="terms-acceptance">
                <form action="process-accept-terms.php" method="POST" class="acceptance-form" id="acceptanceForm">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="accept-terms" required>
                        <label for="accept-terms">J'ai lu et j'accepte les conditions générales d'utilisation</label>
                    </div>
                    <input type="hidden" name="form_data" id="savedFormData">
                    <button type="submit" class="btn-primary" id="continue-button" disabled>
                        Continuer l'inscription
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.terms-acceptance {
    margin-top: 30px;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
    text-align: center;
}

.acceptance-form {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
}

.checkbox-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
}

.checkbox-wrapper input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.checkbox-wrapper label {
    font-size: 16px;
    cursor: pointer;
}

.btn-primary {
    padding: 12px 24px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-primary:disabled {
    background-color: #6c757d;
    cursor: not-allowed;
}

.btn-primary:hover:not(:disabled) {
    background-color: #218838;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('accept-terms');
    const continueButton = document.getElementById('continue-button');
    const savedFormData = document.getElementById('savedFormData');
    const form = document.getElementById('acceptanceForm');

    // Récupérer les données sauvegardées du sessionStorage
    const registerData = sessionStorage.getItem('registerFormData');
    if (registerData) {
        savedFormData.value = registerData;
    }

    checkbox.addEventListener('change', function() {
        continueButton.disabled = !this.checked;
    });

    form.addEventListener('submit', function(e) {
        if (!checkbox.checked) {
            e.preventDefault();
            return;
        }
    });
});
</script>

<?php include 'components/footer.php'; ?> 