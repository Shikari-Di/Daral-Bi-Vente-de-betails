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
$user = null;

// Récupérer l'ID de l'utilisateur à modifier
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header('Location: utilisateurs.php');
    exit();
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $type_compte = $_POST['type_compte'] ?? 'client';
    $numero = trim($_POST['numero'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');

    // Validation
    if (empty($nom) || empty($email)) {
        $error = "Le nom et l'email sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email n'est pas valide.";
    } else {
        try {
            // Vérifier si l'email existe déjà pour un autre utilisateur
            $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? AND id != ?");
            $stmt->execute([$email, $id]);
            if ($stmt->fetch()) {
                $error = "Cette adresse email est déjà utilisée par un autre utilisateur.";
            } else {
                // Préparer la requête de base
                $sql = "UPDATE utilisateurs SET nom = ?, email = ?, type_compte = ?, numero = ?";
                $params = [$nom, $email, $type_compte, $numero];

                // Ajouter le mot de passe à la requête si un nouveau est fourni
                if (!empty($new_password)) {
                    $sql .= ", mot_de_passe = ?";
                    $params[] = password_hash($new_password, PASSWORD_DEFAULT);
                }

                $sql .= " WHERE id = ?";
                $params[] = $id;

                // Exécuter la mise à jour
                $stmt = $pdo->prepare($sql);
                if ($stmt->execute($params)) {
                    $message = "Utilisateur modifié avec succès.";
                    // Rediriger après 2 secondes
                    header("refresh:2;url=utilisateurs.php");
                } else {
                    $error = "Erreur lors de la modification de l'utilisateur.";
                }
            }
        } catch (PDOException $e) {
            $error = "Erreur de base de données : " . $e->getMessage();
        }
    }
}

// Récupérer les données de l'utilisateur
try {
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header('Location: utilisateurs.php');
        exit();
    }
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un utilisateur - Admin</title>
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
        <h1>Modifier un utilisateur</h1>

        <?php if ($message): ?>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($user): ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Nom <span class="required">*</span></label>
                    <input type="text" name="nom" required value="<?= htmlspecialchars($user['nom']) ?>">
                </div>

                <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">
                </div>

                <div class="form-group">
                    <label>Nouveau mot de passe (laisser vide pour ne pas modifier)</label>
                    <input type="password" name="new_password">
                </div>

                <div class="form-group">
                    <label>Numéro de téléphone</label>
                    <input type="text" name="numero" value="<?= htmlspecialchars($user['numero'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Type de compte</label>
                    <select name="type_compte">
                        <option value="client" <?= $user['type_compte'] === 'client' ? 'selected' : '' ?>>Client</option>
                        <option value="vendeur" <?= $user['type_compte'] === 'vendeur' ? 'selected' : '' ?>>Vendeur</option>
                    </select>
                </div>

                <div class="form-group">
                    <a href="utilisateurs.php" class="btn-retour">← Retour</a>
                    <button type="submit" class="btn-submit">Enregistrer les modifications</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>