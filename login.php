<?php 
$pageTitle = "Connexion";
$currentPage = 'login';
include 'components/header.php';
?>

<div class="auth-page">
    <div class="container">
        <div class="auth-box">
            <h1>Connexion</h1>
            
            <form class="auth-form" action="process-login.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required class="form-input">
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="password-input-group">
                        <input type="password" id="password" name="password" required class="form-input">
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

            <div class="auth-separator">
                <span>ou</span>
            </div>

            <div class="social-auth">
                <button class="btn-social btn-facebook">
                    <i class="fab fa-facebook-f"></i>
                    Continuer avec Facebook
                </button>
                <button class="btn-social btn-google">
                    <i class="fab fa-google"></i>
                    Continuer avec Google
                </button>
            </div>

            <div class="auth-footer">
                <p>Pas encore de compte ? <a href="register.php">Créer un compte</a></p>
            </div>
        </div>
    </div>
</div>

<?php include 'components/footer.php'; ?> 