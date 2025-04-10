<?php
session_start();

$pageTitle = "Inscription";
$currentPage = 'register';
include 'components/header.php';

// Récupérer les données sauvegardées si elles existent
$formData = $_SESSION['register_data'] ?? [];

// Supprimer les données de session après les avoir récupérées
unset($_SESSION['register_data']);

// Si le paramètre type=vendor est présent, forcer le type de compte à vendeur
if (isset($_GET['type']) && $_GET['type'] === 'vendor') {
    $formData['account_type'] = 'vendor';
}
?>

<div class="auth-page">
    <div class="container">
        <div class="auth-box">
            <h1>Créer un compte</h1>
            
            <div class="account-type-selector">
                <button type="button" class="account-type-btn <?php echo (!isset($formData['account_type']) || $formData['account_type'] === 'client') ? 'active' : ''; ?>" data-type="client">
                    <i class="fas fa-user"></i>
                    <span>Client</span>
                    <p class="type-description">Je souhaite acheter du bétail</p>
                </button>
                <button type="button" class="account-type-btn <?php echo (isset($formData['account_type']) && $formData['account_type'] === 'vendor') ? 'active' : ''; ?>" data-type="vendor">
                    <i class="fas fa-store"></i>
                    <span>Vendeur</span>
                    <p class="type-description">Je souhaite vendre du bétail</p>
                </button>
            </div>
            
            <form class="auth-form" action="process-register.php" method="POST">
                <input type="hidden" name="account_type" id="account_type" value="<?php echo htmlspecialchars($formData['account_type'] ?? 'client'); ?>">
                
                <div class="form-group">
                    <label for="fullname">Nom complet</label>
                    <input type="text" id="fullname" name="fullname" required class="form-input" 
                           value="<?php echo htmlspecialchars($formData['fullname'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required class="form-input"
                           value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone" required class="form-input"
                           value="<?php echo htmlspecialchars($formData['phone'] ?? ''); ?>">
                </div>

                <!-- Champs spécifiques aux vendeurs -->
                <div class="vendor-fields" style="display: <?php echo (isset($formData['account_type']) && $formData['account_type'] === 'vendor') ? 'block' : 'none'; ?>;">
                    <div class="form-group">
                        <label for="business_name">Nom de l'entreprise</label>
                        <input type="text" id="business_name" name="business_name" class="form-input"
                               value="<?php echo htmlspecialchars($formData['business_name'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="business_address">Adresse de l'entreprise</label>
                        <input type="text" id="business_address" name="business_address" class="form-input"
                               value="<?php echo htmlspecialchars($formData['business_address'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="tax_id">Numéro NINEA</label>
                        <input type="text" id="tax_id" name="tax_id" class="form-input"
                               value="<?php echo htmlspecialchars($formData['tax_id'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="password-input-group">
                        <input type="password" id="password" name="password" required class="form-input"
                               value="<?php echo htmlspecialchars($formData['password'] ?? ''); ?>">
                        <button type="button" class="toggle-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <div class="password-input-group">
                        <input type="password" id="confirm_password" name="confirm_password" required class="form-input"
                               value="<?php echo htmlspecialchars($formData['confirm_password'] ?? ''); ?>">
                        <button type="button" class="toggle-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group terms-checkbox">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" required checked>
                        <span>J'accepte les <a href="conditions-generales.php">conditions générales</a></span>
                    </label>
                </div>
                
                <button type="submit" class="btn-primary btn-block">Créer mon compte</button>
            </form>

            <div class="auth-footer">
                <p>Déjà inscrit ? <a href="login.php<?php echo isset($_GET['type']) && $_GET['type'] === 'vendor' ? '?from=vendor' : ''; ?>">Se connecter</a></p>
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

    // Gestion de l'affichage/masquage des mots de passe
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });

    // Si le type de compte est vendeur, afficher les champs vendeur au chargement
    if (accountTypeInput.value === 'vendor') {
        vendorFields.style.display = 'block';
        vendorFields.querySelectorAll('input').forEach(input => input.required = true);
    }
});
</script>

<?php include 'components/footer.php'; ?> 