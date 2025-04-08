<?php 
session_start();

// Nettoyer les données de session au chargement de la page
unset($_SESSION['login_data']);
unset($_SESSION['login_errors']);

$pageTitle = "Connexion";
$currentPage = 'login';
include 'components/header.php';

// Vérifier si l'utilisateur vient du bouton "Devenir vendeur"
$from_vendor = isset($_GET['from']) && $_GET['from'] === 'vendor';

// Afficher le message de succès s'il existe
if (isset($_SESSION['register_success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['register_success'] . '</div>';
    unset($_SESSION['register_success']);
}

// Afficher les erreurs de connexion s'il y en a
if (isset($_SESSION['login_errors'])) {
    echo '<div class="alert alert-error">';
    foreach ($_SESSION['login_errors'] as $error) {
        echo "<p>$error</p>";
    }
    echo '</div>';
    unset($_SESSION['login_errors']);
}
?>

<div class="auth-page">
    <div class="container">
        <div class="auth-box">
            <h1><?php echo $from_vendor ? 'Connexion Vendeur' : 'Connexion'; ?></h1>
            
            <?php if ($from_vendor): ?>
            <p class="auth-description">Connectez-vous à votre compte vendeur ou créez-en un nouveau pour commencer à vendre.</p>
            <?php endif; ?>
            
            <form class="auth-form" action="process-login.php" method="POST" autocomplete="off">
                <?php if ($from_vendor): ?>
                <input type="hidden" name="from_vendor" value="1">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required class="form-input" autocomplete="new-email" value="">
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="password-input-group">
                        <input type="password" id="password" name="password" required class="form-input" autocomplete="new-password" value="">
                        <button type="button" class="toggle-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group remember-me">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span>Se souvenir de moi</span>
                    </label>
                    <a href="forgot-password.php" class="forgot-password">Mot de passe oublié ?</a>
                </div>
                
                <button type="submit" class="btn-primary btn-block">Se connecter</button>
            </form>

            <div class="auth-footer">
                <p>Pas encore de compte ? 
                    <?php if ($from_vendor): ?>
                    <a href="register.php?type=vendor">Créer un compte vendeur</a>
                    <?php else: ?>
                    <a href="register.php">Créer un compte</a>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert p {
    margin: 0;
    padding: 0;
}

.alert p + p {
    margin-top: 5px;
}

.auth-description {
    text-align: center;
    color: #666;
    margin-bottom: 20px;
}

.alert-error a {
    color: #721c24;
    text-decoration: underline;
}

.alert-error a:hover {
    text-decoration: none;
}
</style>

<script>
// Fonction pour vider le formulaire
function clearForm() {
    const form = document.querySelector('.auth-form');
    const inputs = form.querySelectorAll('input:not([type="checkbox"]):not([type="hidden"])');
    inputs.forEach(input => {
        input.value = '';
    });
    form.querySelector('input[type="checkbox"]').checked = false;
}

// Vider le formulaire au chargement de la page
window.onload = clearForm;

// Vider le formulaire quand la page est actualisée
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        clearForm();
    }
});

// Vider le formulaire quand l'utilisateur navigue vers la page
if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
    clearForm();
}

document.addEventListener('DOMContentLoaded', function() {
    clearForm();
    
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
});
</script>

<?php include 'components/footer.php'; ?> 