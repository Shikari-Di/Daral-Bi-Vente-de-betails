<?php
session_start();

// Récupération des erreurs et des données
$login_data = $_SESSION['admin_login_data'] ?? [];
$errors = $_SESSION['admin_login_errors'] ?? [];
$timeout = isset($_GET['timeout']) ? true : false;

// Nettoyage des variables de session
unset($_SESSION['admin_login_data'], $_SESSION['admin_login_errors']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administration - Daral_bi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2ecc71;
            --secondary: #3498db;
            --danger: #e74c3c;
            --dark: #2c3e50;
            --light: #f8f9fa;
            --border: #dee2e6;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            margin: 20px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: var(--dark);
            margin-bottom: 10px;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark);
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--secondary);
        }

        .password-input-group {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: var(--secondary);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-login:hover {
            background: var(--primary);
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            color: white;
        }

        .alert-danger {
            background: var(--danger);
        }

        .alert-warning {
            background: #f1c40f;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Connexion Administration</h1>
            <p>Veuillez vous connecter pour accéder au panneau d'administration</p>
        </div>

        <?php if ($timeout): ?>
            <div class="alert alert-warning">
                Votre session a expiré. Veuillez vous reconnecter.
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="process-admin-login.php" method="POST" id="adminLoginForm">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" class="form-input" 
                       value="<?php echo htmlspecialchars($login_data['username'] ?? ''); ?>" required>
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
                <label for="security_code">Code de sécurité</label>
                <input type="text" id="security_code" name="security_code" class="form-input" required>
            </div>

            <button type="submit" class="btn-login">Se connecter</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const toggleButton = document.querySelector('.toggle-password');
            const passwordInput = document.querySelector('#password');

            toggleButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            // Form validation
            const form = document.getElementById('adminLoginForm');
            form.addEventListener('submit', function(e) {
                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;
                const securityCode = document.getElementById('security_code').value;

                if (!username || !password || !securityCode) {
                    e.preventDefault();
                    alert('Veuillez remplir tous les champs');
                }
            });
        });
    </script>
</body>
</html> 