<?php 
$pageTitle = "Réinitialisation du mot de passe";
$currentPage = 'forgot-password';
include 'components/header.php';
?>

<div class="auth-page">
    <div class="container">
        <div class="auth-box">
            <h1>Mot de passe oublié ?</h1>
            <p class="auth-description">
                Entrez votre adresse email ci-dessous. Nous vous enverrons un lien pour réinitialiser votre mot de passe.
            </p>
            
            <form class="auth-form" action="process-reset-password.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required class="form-input" 
                           placeholder="Entrez votre adresse email">
                </div>
                
                <button type="submit" class="btn-primary btn-block">
                    Envoyer le lien de réinitialisation
                </button>
            </form>

            <div class="auth-footer">
                <p>
                    <a href="login.php" class="back-to-login">
                        <i class="fas fa-arrow-left"></i> 
                        Retour à la connexion
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include 'components/footer.php'; ?> 