<?php
session_start();
require_once 'db.php';

// Initialisation des tableaux pour les erreurs et les données
$errors = [];
$login_data = [
    'username' => $_POST['username'] ?? '',
];

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $security_code = trim($_POST['security_code'] ?? '');

    // Validation des champs
    if (empty($username)) {
        $errors[] = "Le nom d'utilisateur est requis";
    }
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis";
    }
    if (empty($security_code)) {
        $errors[] = "Le code de sécurité est requis";
    }

    // Si aucune erreur, procéder à la vérification
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérification directe du mot de passe et du code de sécurité
            if ($admin && $password === $admin['password'] && $security_code === $admin['security_code']) {
                // Connexion réussie
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['is_admin'] = true;
                $_SESSION['last_activity'] = time();

                // Redirection vers admin.php
                header("Location: admin.php");
                exit();
            } else {
                $errors[] = "Identifiants invalides ou code de sécurité incorrect";
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur de connexion à la base de données";
        }
    }
}

// Si des erreurs sont présentes, rediriger vers la page de connexion avec les erreurs
if (!empty($errors)) {
    $_SESSION['admin_login_errors'] = $errors;
    $_SESSION['admin_login_data'] = $login_data;
    header("Location: admin-login.php");
    exit();
} 