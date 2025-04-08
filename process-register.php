<?php
session_start();
require_once 'config/database.php';

// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifier si la requête est bien POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['register_errors'] = ["Méthode non autorisée"];
    header('Location: register.php');
    exit();
}

// Récupération des données du formulaire
$nom = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$numero = trim($_POST['phone'] ?? '');
$mot_de_passe = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$type_compte = ($_POST['account_type'] ?? '') === 'vendor' ? 'vendeur' : 'client';
$nom_entreprise = isset($_POST['business_name']) ? trim($_POST['business_name']) : null;
$adresse_entreprise = isset($_POST['business_address']) ? trim($_POST['business_address']) : null;
$numero_ninea = isset($_POST['tax_id']) ? trim($_POST['tax_id']) : null;

// Debug
echo "Données reçues :<br>";
echo "Nom : $nom<br>";
echo "Email : $email<br>";
echo "Type compte : $type_compte<br>";

// Validation des champs
$errors = [];

if (empty($nom)) {
    $errors[] = "Le nom complet est requis";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "L'email n'est pas valide";
}

if (empty($numero)) {
    $errors[] = "Le numéro de téléphone est requis";
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

// Vérification de l'unicité de l'email
$stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->rowCount() > 0) {
    $errors[] = "Cet email est déjà utilisé";
}

if (empty($errors)) {
    try {
        // Hachage du mot de passe
        $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        // Debug
        echo "Tentative d'insertion dans la base de données...<br>";

        // Insertion dans la base de données
        $stmt = $pdo->prepare("
            INSERT INTO utilisateurs (nom, email, numero, mot_de_passe, type_compte, nom_entreprise, adresse_entreprise, numero_ninea, date_inscription)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmt->execute([
            $nom,
            $email,
            $numero,
            $hashed_password,
            $type_compte,
            $nom_entreprise,
            $adresse_entreprise,
            $numero_ninea
        ]);

        // Debug
        echo "Insertion réussie !<br>";

        // Récupération de l'ID de l'utilisateur
        $user_id = $pdo->lastInsertId();
        echo "ID utilisateur : $user_id<br>";

        // Création de la session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['email'] = $email;
        $_SESSION['type_compte'] = $type_compte;

        // Debug
        echo "Session créée :<br>";
        print_r($_SESSION);

        // Message de succès pour la page de connexion
        $_SESSION['register_success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        
        // Redirection vers la page de connexion
        header("Location: login.php");
        exit();

    } catch (PDOException $e) {
        echo "Erreur PDO : " . $e->getMessage() . "<br>";
        $errors[] = "Une erreur est survenue lors de l'inscription";
    }
}

// Si des erreurs sont présentes, les stocker en session et rediriger
if (!empty($errors)) {
    echo "Erreurs :<br>";
    print_r($errors);
    $_SESSION['register_errors'] = $errors;
    $_SESSION['register_data'] = $_POST;
    header('Location: register.php');
    exit();
} 