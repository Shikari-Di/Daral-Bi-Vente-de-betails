<?php 
session_start();
require_once 'config/database.php';

$pageTitle = "Inscription";
$currentPage = 'register';

// Récupération des données en cas d'erreur
$register_data = $_SESSION['register_data'] ?? [];
$errors = $_SESSION['register_errors'] ?? [];

// Récupérer le type de compte depuis l'URL
$account_type = isset($_GET['type']) && $_GET['type'] === 'vendor' ? 'vendor' : 'client';

// Nettoyer les données de session après les avoir récupérées
unset($_SESSION['register_data'], $_SESSION['register_errors']);

include 'components/header.php';
?>

<div class="auth-page">
    <div class="container">
        <div class="auth-box">
            <h1>Inscription</h1>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="account-type-selector">
                <button type="button" class="account-type-btn <?php echo $account_type === 'client' ? 'active' : ''; ?>" data-type="client">
                    <i class="fas fa-user"></i>
                    <span>Client</span>
                    <p class="type-description">Je souhaite acheter du bétail</p>
                </button>
                <button type="button" class="account-type-btn <?php echo $account_type === 'vendor' ? 'active' : ''; ?>" data-type="vendor">
                    <i class="fas fa-store"></i>
                    <span>Vendeur</span>
                    <p class="type-description">Je souhaite vendre du bétail</p>
                </button>
            </div>
            
            <form class="auth-form" action="process-register.php" method="POST" id="registerForm">
                <input type="hidden" name="account_type" id="account_type" value="<?php echo $account_type; ?>">
                
                <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" name="name" class="form-input" 
                           value="<?php echo htmlspecialchars($register_data['name'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-input" 
                           value="<?php echo htmlspecialchars($register_data['email'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone" class="form-input" 
                           value="<?php echo htmlspecialchars($register_data['phone'] ?? ''); ?>" 
                           pattern="[0-9]{9}" maxlength="9" required>
                    <small class="form-text text-muted">Format: 9 chiffres</small>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="password-input-group">
                        <input type="password" id="password" name="password" class="form-input" required>
                        <button type="button" class="toggle-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <small class="form-text text-muted">Minimum 8 caractères</small>
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

                <!-- Champs spécifiques aux vendeurs -->
                <div class="vendor-fields" style="display: <?php echo $account_type === 'vendor' ? 'block' : 'none'; ?>;">
                    <div class="form-group">
                        <label for="business_name">Nom de l'entreprise</label>
                        <input type="text" id="business_name" name="business_name" class="form-input" 
                               value="<?php echo htmlspecialchars($register_data['business_name'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="business_address">Adresse de l'entreprise</label>
                        <input type="text" id="business_address" name="business_address" class="form-input" 
                               value="<?php echo htmlspecialchars($register_data['business_address'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="tax_id">Numéro NINEA</label>
                        <input type="text" id="tax_id" name="tax_id" class="form-input" 
                               value="<?php echo htmlspecialchars($register_data['tax_id'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" required>
                        J'accepte les <a href="conditions-generales.php">conditions générales</a>
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
    // Gestion du type de compte
    const accountTypeBtns = document.querySelectorAll('.account-type-btn');
    const vendorFields = document.querySelector('.vendor-fields');
    const accountTypeInput = document.getElementById('account_type');
    const vendorInputs = vendorFields.querySelectorAll('input');

    accountTypeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            accountTypeBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const type = this.dataset.type;
            accountTypeInput.value = type;
            vendorFields.style.display = type === 'vendor' ? 'block' : 'none';
            
            // Gérer les champs requis pour les vendeurs
            if (type === 'vendor') {
                vendorInputs.forEach(input => input.required = true);
            } else {
                vendorInputs.forEach(input => {
                    input.required = false;
                    input.value = ''; // Vider les champs
                });
            }
        });
    });

    // Toggle password visibility
    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });

    // Validation du formulaire
    const form = document.getElementById('registerForm');
    form.addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const type = accountTypeInput.value;

        if (password.length < 8) {
            e.preventDefault();
            alert('Le mot de passe doit contenir au moins 8 caractères');
            return;
        }

        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas');
            return;
        }

        if (type === 'vendor') {
            const businessName = document.getElementById('business_name').value;
            const businessAddress = document.getElementById('business_address').value;
            const taxId = document.getElementById('tax_id').value;

            if (!businessName || !businessAddress || !taxId) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs requis pour les vendeurs');
                return;
            }
        }
    });
});
</script>

<?php include 'components/footer.php'; ?>