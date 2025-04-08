<?php
session_start();
require_once 'config/database.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Vous devez être connecté pour ajouter au panier.";
    header('Location: login.php');
    exit();
}

// Vérifier si l'ID de l'annonce est fourni
if (!isset($_POST['annonce_id'])) {
    $_SESSION['error_message'] = "Aucune annonce sélectionnée.";
    header('Location: annonces.php');
    exit();
}

$annonce_id = (int)$_POST['annonce_id'];

try {
    // Récupérer les informations de l'annonce
    $stmt = $pdo->prepare("
        SELECT a.*, u.nom as vendeur_nom 
        FROM annonces a 
        JOIN utilisateurs u ON a.utilisateur_id = u.id 
        WHERE a.id = ?
    ");
    $stmt->execute([$annonce_id]);
    $annonce = $stmt->fetch();

    if (!$annonce) {
        $_SESSION['error_message'] = "Annonce non trouvée.";
        header('Location: annonces.php');
        exit();
    }

    // Initialiser le panier s'il n'existe pas
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    // Ajouter ou mettre à jour l'article dans le panier
    $_SESSION['panier'][$annonce_id] = [
        'titre' => $annonce['titre'],
        'prix' => $annonce['prix'],
        'image' => $annonce['image'],
        'vendeur' => $annonce['vendeur_nom'],
        'quantite' => 1
    ];

    $_SESSION['success_message'] = "L'article a été ajouté à votre panier.";
    header('Location: panier.php');
    exit();

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erreur lors de l'ajout au panier : " . $e->getMessage();
    header('Location: annonces.php');
    exit();
}
?> 