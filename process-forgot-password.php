<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: forgot-password.php');
    exit();
}

$email = trim($_POST['email'] ?? '');

// Validation de l'email
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['reset_errors'] = ["L'adresse email n'est pas valide"];
    $_SESSION['reset_data'] = ['email' => $email];
    header('Location: forgot-password.php');
    exit();
}

try {
    // Vérifier si l'email existe
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Générer un token unique
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Sauvegarder le token dans la base de données
        $stmt = $pdo->prepare("UPDATE utilisateurs SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->execute([$token, $expiry, $email]);

        // Simulation d'envoi d'email (en production, on enverrait un vrai email ici)
        $_SESSION['reset_success'] = "Un email a été envoyé à " . htmlspecialchars($email) . " avec les instructions pour réinitialiser votre mot de passe.";
    } else {
        // Pour des raisons de sécurité, ne pas indiquer si l'email existe ou non
        $_SESSION['reset_success'] = "Si cette adresse email est associée à un compte, vous recevrez un email avec les instructions pour réinitialiser votre mot de passe.";
    }

    header('Location: forgot-password.php');
    exit();

} catch (PDOException $e) {
    $_SESSION['reset_errors'] = ["Une erreur est survenue lors de la réinitialisation du mot de passe"];
    $_SESSION['reset_data'] = ['email' => $email];
    header('Location: forgot-password.php');
    exit();
} 