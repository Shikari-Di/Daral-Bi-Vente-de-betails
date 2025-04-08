<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;
    $from_vendor = isset($_GET['from']) && $_GET['from'] === 'vendor';

    $errors = [];

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide";
    }

    if (empty($mot_de_passe)) {
        $errors[] = "Le mot de passe est requis";
    }

    if (empty($errors)) {
        try {
            // Recherche de l'utilisateur
            $stmt = $pdo->prepare("SELECT id, email, mot_de_passe, type_compte, nom FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
                // Si l'utilisateur vient du bouton "Devenir vendeur" et n'est pas un vendeur
                if ($from_vendor && $user['type_compte'] !== 'vendeur') {
                    $_SESSION['login_errors'] = ["Vous devez avoir un compte vendeur pour accéder à cette section. <a href='register.php?type=vendor'>Créer un compte vendeur</a>"];
                    $_SESSION['login_data'] = ['email' => $email];
                    header('Location: login.php?from=vendor');
                    exit();
                }

                // Connexion réussie
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['nom'];
                $_SESSION['user_type'] = $user['type_compte'];

                // Gestion du "Se souvenir de moi"
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));
                    
                    $stmt = $pdo->prepare("UPDATE utilisateurs SET remember_token = ?, token_expiry = ? WHERE id = ?");
                    $stmt->execute([$token, $expiry, $user['id']]);
                    
                    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
                }

                // Redirection selon le type de compte
                if ($user['type_compte'] === 'vendeur') {
                    header('Location: dashboard-vendeur.php');
                } else {
                    header('Location: index.php');
                }
                exit();
            } else {
                $errors[] = "Email ou mot de passe incorrect";
            }
        } catch (PDOException $e) {
            $errors[] = "Une erreur est survenue lors de la connexion";
        }
    }

    if (!empty($errors)) {
        $_SESSION['login_errors'] = $errors;
        $_SESSION['login_data'] = ['email' => $email];
        header('Location: login.php' . ($from_vendor ? '?from=vendor' : ''));
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}