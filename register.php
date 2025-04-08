<?php 
$pageTitle = "Inscription";
$currentPage = 'register';
include 'components/header.php';
?>

<div class="auth-page">
    <div class="container">
        <div class="auth-box">
            <h1>Inscription</h1>
            
            <div class="account-type-selector">
                <button type="button" class="account-type-btn active" data-type="client">
                    <i class="fas fa-user"></i>
                    <span>Client</span>
                    <p class="type-description">Je souhaite acheter du bétail</p>
                </button>
                <button type="button" class="account-type-btn" data-type="vendor">
                    <i class="fas fa-store"></i>
                    <span>Vendeur</span>
                    <p class="type-description">Je souhaite vendre du bétail</p>
                </button>
            </div>
            
            <form class="auth-form" action="process-register.php" method="POST">
                <input type="hidden" name="account_type" id="account_type" value="client">
                
                <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" name="name" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone" required class="form-input">
                </div>

                <!-- Champs spécifiques aux vendeurs -->
                <div class="vendor-fields" style="display: none;">
                    <div class="form-group">
                        <label for="business_name">Nom de l'entreprise</label>
                        <input type="text" id="business_name" name="business_name" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="business_address">Adresse de l'entreprise</label>
                        <input type="text" id="business_address" name="business_address" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="tax_id">Numéro NINEA</label>
                        <input type="text" id="tax_id" name="tax_id" class="form-input">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="password-input-group">
                        <input type="password" id="password" name="password" class="form-input" required>
                        <button type="button" class="toggle-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <div class="password-input-group">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
                        <button type="button" class="toggle-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="terms-checkbox">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" required>
                        J'accepte les <a href="terms.php">conditions générales</a>
                    </label>
                </div>
                
                <button type="submit" class="btn-primary btn-block">S'inscrire</button>
            </form>

            <div class="auth-footer">
                <p>Vous avez déjà un compte ? <a href="login.php">Connectez-vous</a></p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const accountTypeBtns = document.querySelectorAll('.account-type-btn');
    const accountTypeInput = document.getElementById('account_type');
    const vendorFields = document.querySelector('.vendor-fields');

    accountTypeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Retirer la classe active de tous les boutons
            accountTypeBtns.forEach(b => b.classList.remove('active'));
            // Ajouter la classe active au bouton cliqué
            this.classList.add('active');
            
            const accountType = this.dataset.type;
            accountTypeInput.value = accountType;

            // Afficher/masquer les champs vendeur
            if (accountType === 'vendor') {
                vendorFields.style.display = 'block';
                vendorFields.querySelectorAll('input').forEach(input => input.required = true);
            } else {
                vendorFields.style.display = 'none';
                vendorFields.querySelectorAll('input').forEach(input => input.required = false);
            }
        });
    });
});
</script>

<?php include 'components/footer.php'; ?> 