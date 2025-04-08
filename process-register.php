<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['register_errors'] = ["Méthode non autorisée"];
    header('Location: register.php');
    exit();
}

// Récupération des données du formulaire
$nom = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$numero = trim($_POST['phone'] ?? '');
$mot_de_passe = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$type_compte = ($_POST['account_type'] ?? '') === 'vendor' ? 'vendeur' : 'client';
$nom_entreprise = isset($_POST['business_name']) ? trim($_POST['business_name']) : null;
$adresse_entreprise = isset($_POST['business_address']) ? trim($_POST['business_address']) : null;
$numero_ninea = isset($_POST['tax_id']) ? trim($_POST['tax_id']) : null;

// Validation des champs
$errors = [];

if (empty($nom)) {
    $errors[] = "Le nom complet est requis";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "L'email n'est pas valide";
}

if (empty($numero) || !preg_match('/^[0-9]{9}$/', $numero)) {
    $errors[] = "Le numéro de téléphone doit contenir 9 chiffres";
}

if (strlen($mot_de_passe) < 8) {
    $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
}

if ($mot_de_passe !== $confirm_password) {
    $errors[] = "Les mots de passe ne correspondent pas";
}

if ($type_compte === 'vendeur') {
    if (empty($nom_entreprise)) {
        $errors[] = "Le nom de l'entreprise est requis pour les vendeurs";
    }
    if (empty($adresse_entreprise)) {
        $errors[] = "L'adresse de l'entreprise est requise pour les vendeurs";
    }
    if (empty($numero_ninea)) {
        $errors[] = "Le numéro NINEA est requis pour les vendeurs";
    }
}

if (empty($errors)) {
    try {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Cet email est déjà utilisé";
        } else {
            // Hachage du mot de passe
            $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            // Insertion de l'utilisateur
            $stmt = $pdo->prepare("
                INSERT INTO utilisateurs (
                    nom, email, numero, mot_de_passe, type_compte,
                    nom_entreprise, adresse_entreprise, numero_ninea
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $nom, $email, $numero, $mot_de_passe_hash, $type_compte,
                $nom_entreprise, $adresse_entreprise, $numero_ninea
            ]);

            // Récupération de l'ID de l'utilisateur
            $user_id = $pdo->lastInsertId();

            // Création de la session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $nom;
            $_SESSION['user_type'] = $type_compte;

            // Redirection selon le type de compte
            if ($type_compte === 'vendeur') {
                header('Location: dashboard-vendeur.php');
            } else {
                header('Location: index.php');
            }
            exit();
        }
    } catch (PDOException $e) {
        $errors[] = "Une erreur est survenue lors de l'inscription";
    }
}

if (!empty($errors)) {
    $_SESSION['register_errors'] = $errors;
    $_SESSION['register_data'] = [
        'name' => $nom,
        'email' => $email,
        'phone' => $numero,
        'account_type' => $type_compte,
        'business_name' => $nom_entreprise,
        'business_address' => $adresse_entreprise,
        'tax_id' => $numero_ninea
    ];
    header('Location: register.php');
    exit();
}