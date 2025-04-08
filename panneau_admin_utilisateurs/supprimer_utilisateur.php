<?php
// Activation des erreurs pour le développement
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Démarrer la session sécurisée
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => true,
    'use_strict_mode' => true
]);

// Vérification si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../admin-login.php');
    exit();
}

require_once '../db.php';

// Récupérer l'ID de l'utilisateur à supprimer
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id) {
    try {
        // Supprimer l'utilisateur
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['message'] = "Utilisateur supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur de base de données : " . $e->getMessage();
    }
}

// Rediriger vers la liste des utilisateurs
header('Location: utilisateurs.php');
exit();

// Chemin sécurisé pour l'inclusion
$db_path = __DIR__.'/db.php';
if (!file_exists($db_path) || !is_readable($db_path)) {
    exit('<div class="error">Erreur système : configuration base de données</div>');
}

require_once $db_path;

// Vérification de la connexion DB
if (!isset($conn) || $conn->connect_error) {
    exit('<div class="error">Erreur de connexion à la base de données</div>');
}

// Validation de l'ID utilisateur
$user_id = null;
if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $user_id = (int)$_GET['id'];
} else {
    exit('<div class="error">ID utilisateur invalide ou manquant</div>');
}

// Protection contre l'auto-suppression
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id) {
    exit('<div class="error">Action interdite : vous ne pouvez pas supprimer votre propre compte</div>');
}

// Initialisation du message
$message = '';
$success = false;

// Transaction sécurisée
try {
    $conn->begin_transaction();

    // 1. Suppression des commentaires (si table existe)
    if ($conn->query("SHOW TABLES LIKE 'commentaires'")->num_rows > 0) {
        $sql = "DELETE FROM commentaires WHERE utilisateur_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Erreur préparation commentaires: ".$conn->error);
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) throw new Exception("Erreur exécution commentaires: ".$stmt->error);
        $stmt->close();
    }

    // 2. Suppression des annonces
    $sql = "DELETE FROM annonces WHERE utilisateur_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception("Erreur préparation annonces: ".$conn->error);
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) throw new Exception("Erreur exécution annonces: ".$stmt->error);
    $stmt->close();

    // 3. Suppression de l'utilisateur
    $sql = "DELETE FROM utilisateurs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception("Erreur préparation utilisateur: ".$conn->error);
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) throw new Exception("Erreur exécution utilisateur: ".$stmt->error);
    $affected_rows = $stmt->affected_rows;
    $stmt->close();

    if ($affected_rows > 0) {
        $conn->commit();
        $success = true;
        $message = 'Utilisateur et données associées supprimés avec succès';
    } else {
        throw new Exception("Aucun utilisateur trouvé avec cet ID");
    }

} catch (Exception $e) {
    $conn->rollback();
    $message = 'Erreur lors de la suppression : '.htmlspecialchars($e->getMessage());
    error_log("Suppression utilisateur échouée : ".$e->getMessage());
} 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppression utilisateur</title>
    <style>
        :root {
            --success: #4CAF50;
            --error: #F44336;
            --bg: rgba(138, 207, 147, 0.54);
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: var(--bg);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: white;
        }
        
        .success { background-color: var(--success); }
        .error { background-color: var(--error); }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background-color: #0b7dda;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="alert <?= $success ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
        
        <?php if ($success): ?>
            <script>
                setTimeout(function() {
                    window.location.href = "gestion_utilisateurs.php";
                }, 2000);
            </script>
            <p>Redirection automatique...</p>
        <?php else: ?>
            <a href="gestion_utilisateurs.php" class="btn">Retour</a>
        <?php endif; ?>
    </div>
</body>
</html>