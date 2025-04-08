<?php 
session_start();
$pageTitle = "Connexion";
$currentPage = 'login';
include 'components/header.php';

// Récupération des données en cas d'erreur
$login_data = $_SESSION['login_data'] ?? [];
$errors = $_SESSION['login_errors'] ?? [];
$success = $_SESSION['register_success'] ?? '';
unset($_SESSION['login_data'], $_SESSION['login_errors'], $_SESSION['register_success']);
?>

<div class="auth-page">
    <div class="container">
        <div class="auth-box">
            <h1>Connexion</h1>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <form class="auth-form" action="process-login.php" method="POST" id="loginForm">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-input" 
                           value="<?php echo htmlspecialchars($login_data['email'] ?? ''); ?>" required>
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

                <div class="remember-me">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" id="remember">
                        Se souvenir de moi
                    </label>
                    <a href="forgot-password.php" class="forgot-password">Mot de passe oublié ?</a>
                </div>
                
                <button type="submit" class="btn-primary btn-block">Se connecter</button>
            </form>

            <div class="auth-footer">
                <p>Vous n'avez pas de compte ? <a href="register.php">Inscrivez-vous</a></p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
    const form = document.getElementById('loginForm');
    form.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        if (!email || !password) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs');
            return;
        }

        if (!email.includes('@')) {
            e.preventDefault();
            alert('Veuillez entrer une adresse email valide');
            return;
        }
    });
});
</script>

<?php include 'components/footer.php'; ?> 