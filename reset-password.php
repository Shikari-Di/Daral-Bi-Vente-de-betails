<?php 
session_start();
require_once 'config/database.php';

$token = $_GET['token'] ?? '';
$valid_token = false;
$email = '';

if ($token) {
    try {
        // Vérifier si le token est valide et non expiré
        $stmt = $pdo->prepare("SELECT email FROM utilisateurs WHERE reset_token = ? AND reset_token_expiry > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            $valid_token = true;
            $email = $user['email'];
        }
    } catch (PDOException $e) {
        $_SESSION['reset_errors'] = ["Une erreur est survenue"];
    }
}

$pageTitle = "Réinitialisation du mot de passe";
$currentPage = 'reset-password';
include 'components/header.php';
?>

<div class="auth-page">
    <div class="container">
        <div class="auth-box">
            <h1>Réinitialisation du mot de passe</h1>

            <?php if (isset($_SESSION['reset_errors'])): ?>
                <div class="alert alert-error">
                    <?php 
                    foreach ($_SESSION['reset_errors'] as $error) {
                        echo "<p>$error</p>";
                    }
                    unset($_SESSION['reset_errors']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if ($valid_token): ?>
                <p class="auth-description">
                    Choisissez un nouveau mot de passe pour votre compte : <?php echo htmlspecialchars($email); ?>
                </p>

                <form class="auth-form" action="process-reset-password.php" method="POST">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    
                    <div class="form-group">
                        <label for="password">Nouveau mot de passe</label>
                        <div class="password-input-group">
                            <input type="password" id="password" name="password" required class="form-input">
                            <button type="button" class="toggle-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirmer le mot de passe</label>
                        <div class="password-input-group">
                            <input type="password" id="confirm_password" name="confirm_password" required class="form-input">
                            <button type="button" class="toggle-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-primary btn-block">Réinitialiser le mot de passe</button>
                </form>
            <?php else: ?>
                <div class="alert alert-error">
                    <p>Le lien de réinitialisation est invalide ou a expiré.</p>
                </div>
                <div class="auth-footer">
                    <p><a href="forgot-password.php">Demander un nouveau lien</a></p>
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

<?php include 'components/footer.php'; ?> 