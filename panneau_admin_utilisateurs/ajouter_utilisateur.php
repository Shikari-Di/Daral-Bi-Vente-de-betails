<?php
session_start();

// Vérification si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../admin-login.php');
    exit();
}

require_once '../db.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $type_compte = $_POST['type_compte'] ?? 'client';
    $numero = trim($_POST['numero'] ?? '');

    // Validation
    if (empty($nom) || empty($email) || empty($password)) {
        $error = "Tous les champs marqués * sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email n'est pas valide.";
    } else {
        try {
            // Vérifier si l'email existe déjà
            $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Cette adresse email est déjà utilisée.";
            } else {
                // Hasher le mot de passe
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insérer le nouvel utilisateur
                $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, type_compte, numero) VALUES (?, ?, ?, ?, ?)");
                if ($stmt->execute([$nom, $email, $hashed_password, $type_compte, $numero])) {
                    $message = "Utilisateur ajouté avec succès.";
                    // Rediriger après 2 secondes
                    header("refresh:2;url=utilisateurs.php");
                } else {
                    $error = "Erreur lors de l'ajout de l'utilisateur.";
                }
            }
        } catch (PDOException $e) {
            $error = "Erreur de base de données : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un utilisateur - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background: #f9f9f9;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .required {
            color: red;
        }

        .btn-submit {
            background: rgb(11, 150, 64);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background: rgb(9, 121, 52);
        }

        .btn-retour {
            display: inline-block;
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
        }

        .btn-retour:hover {
            background: #5a6268;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajouter un utilisateur</h1>

        <?php if ($message): ?>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Nom <span class="required">*</span></label>
                <input type="text" name="nom" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Mot de passe <span class="required">*</span></label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Numéro de téléphone</label>
                <input type="text" name="numero" value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Type de compte</label>
                <select name="type_compte">
                    <option value="client" <?= (isset($_POST['type_compte']) && $_POST['type_compte'] === 'client') ? 'selected' : '' ?>>Client</option>
                    <option value="vendeur" <?= (isset($_POST['type_compte']) && $_POST['type_compte'] === 'vendeur') ? 'selected' : '' ?>>Vendeur</option>
                </select>
            </div>

            <div class="form-group">
                <a href="utilisateurs.php" class="btn-retour">← Retour</a>
                <button type="submit" class="btn-submit">Ajouter l'utilisateur</button>
            </div>
        </form>
    </div>
</body>
</html>