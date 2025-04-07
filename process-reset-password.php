<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: forgot-password.php');
    exit();
}

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validation
$errors = [];

if (empty($token)) {
    $errors[] = "Token invalide";
}

if (strlen($password) < 8) {
    $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
}

if ($password !== $confirm_password) {
    $errors[] = "Les mots de passe ne correspondent pas";
}

if (!empty($errors)) {
    $_SESSION['reset_errors'] = $errors;
    header('Location: reset-password.php?token=' . urlencode($token));
    exit();
}

try {
    // Vérifier si le token est valide et non expiré
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // Mettre à jour le mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Mettre à jour le mot de passe et supprimer le token
        $stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
        $stmt->execute([$hashed_password, $user['id']]);

        // Message de succès et redirection
        $_SESSION['login_success'] = "Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.";
        header('Location: login.php');
        exit();
    } else {
        $_SESSION['reset_errors'] = ["Le lien de réinitialisation est invalide ou a expiré"];
        header('Location: forgot-password.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['reset_errors'] = ["Une erreur est survenue lors de la réinitialisation du mot de passe"];
    header('Location: reset-password.php?token=' . urlencode($token));
    exit();
} 