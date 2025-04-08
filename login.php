<?php 
$pageTitle = "Connexion";
$currentPage = 'login';
include 'components/header.php';
?>

<div class="auth-page">
    <div class="auth-box">
        <h1>Connexion</h1>
        
        <form class="auth-form" action="process-login.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input" required>
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
                    <input type="checkbox" name="remember">
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

<?php include 'components/footer.php'; ?> 