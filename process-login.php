<?php
session_start();
require_once 'config/database.php';

// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;
    $from_vendor = isset($_POST['from_vendor']) ? true : false;

    // Debug
    echo "Tentative de connexion pour l'email : $email<br>";

    $errors = [];

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide";
    }

    if (empty($mot_de_passe)) {
        $errors[] = "Le mot de passe est requis";
    }

    if (empty($errors)) {
        try {
            // Debug
            echo "Recherche de l'utilisateur dans la base de données...<br>";

            // Recherche de l'utilisateur
            $stmt = $pdo->prepare("SELECT id, email, mot_de_passe, type_compte FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                echo "Utilisateur trouvé :<br>";
                print_r($user);
            } else {
                echo "Aucun utilisateur trouvé avec cet email<br>";
            }

            if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
                // Si l'utilisateur vient du bouton "Devenir vendeur" et n'est pas un vendeur
                if ($from_vendor && $user['type_compte'] !== 'vendeur') {
                    $_SESSION['login_errors'] = ["Vous devez avoir un compte vendeur pour accéder à cette section. <a href='register.php?type=vendor'>Créer un compte vendeur</a>"];
                    $_SESSION['login_data'] = ['email' => $email];
                    header('Location: login.php?from=vendor');
                    exit();
                }

                echo "Mot de passe vérifié avec succès<br>";

                // Création de la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['type_compte'] = $user['type_compte'];

                echo "Session créée :<br>";
                print_r($_SESSION);

                // Si "Se souvenir de moi" est coché, créer un cookie
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    $expiry = time() + (30 * 24 * 60 * 60); // 30 jours

                    // Stocker le token dans la base de données
                    $stmt = $pdo->prepare("UPDATE utilisateurs SET remember_token = ?, token_expiry = ? WHERE id = ?");
                    $stmt->execute([$token, date('Y-m-d H:i:s', $expiry), $user['id']]);

                    // Créer le cookie
                    setcookie('remember_token', $token, $expiry, '/', '', true, true);
                }

                // Redirection selon le type de compte
                if ($user['type_compte'] === 'vendeur') {
                    echo "Redirection vers le dashboard vendeur...<br>";
                    header('Location: dashboard-vendeur.php');
                } else {
                    echo "Redirection vers la page d'accueil...<br>";
                    header('Location: index.php');
                }
                exit();
            } else {
                $errors[] = "Email ou mot de passe incorrect";
            }
        } catch (PDOException $e) {
            echo "Erreur PDO : " . $e->getMessage() . "<br>";
            $errors[] = "Une erreur est survenue lors de la connexion";
        }
    }

    // Si des erreurs sont présentes, les stocker en session et rediriger
    if (!empty($errors)) {
        echo "Erreurs :<br>";
        print_r($errors);
        $_SESSION['login_errors'] = $errors;
        $_SESSION['login_data'] = ['email' => $email];
        header('Location: login.php' . ($from_vendor ? '?from=vendor' : ''));
        exit();
    }
} else {
    header('Location: login.php');
    exit();
} 