<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Mot de passe oublié";
$currentPage = 'forgot-password';

include 'components/header.php';
?>

<div class="auth-page">
    <div class="container">
        <div class="auth-box">
            <h1>Mot de passe oublié</h1>
            
            <?php if (isset($_SESSION['reset_success'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo $_SESSION['reset_success'];
                    unset($_SESSION['reset_success']);
                    ?>
                </div>

                <div class="auth-footer">
                    <p>Retour à la <a href="login.php">connexion</a></p>
                </div>
            <?php else: ?>
                <?php if (isset($_SESSION['reset_errors'])): ?>
                    <div class="alert alert-error">
                        <?php 
                        foreach ($_SESSION['reset_errors'] as $error) {
                            echo "<p>" . htmlspecialchars($error) . "</p>";
                        }
                        unset($_SESSION['reset_errors']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <p class="auth-description">
                    Entrez votre adresse email ci-dessous. Nous vous enverrons un lien pour réinitialiser votre mot de passe.
                </p>

                <form class="auth-form" action="process-forgot-password.php" method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required class="form-input" 
                               value="<?php echo isset($_SESSION['reset_data']['email']) ? htmlspecialchars($_SESSION['reset_data']['email']) : ''; ?>">
                    </div>
                    
                    <button type="submit" class="btn-primary btn-block">Envoyer le lien</button>
                </form>

                <div class="auth-footer">
                    <p>Retour à la <a href="login.php">connexion</a></p>
                </div>
            <?php endif; ?>
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
</style>

<?php 
unset($_SESSION['reset_data']);
include 'components/footer.php'; 
?> 