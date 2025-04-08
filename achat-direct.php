<?php
session_start();
require_once 'config/database.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Vous devez être connecté pour effectuer un achat.";
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

    // Stocker les informations de l'annonce en session pour le paiement
    $_SESSION['achat_direct'] = [
        'annonce_id' => $annonce['id'],
        'titre' => $annonce['titre'],
        'prix' => $annonce['prix'],
        'vendeur' => $annonce['vendeur_nom'],
        'image' => $annonce['image']
    ];

    // Rediriger vers la page de paiement
    header('Location: paiement.php');
    exit();

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erreur lors de la récupération des données : " . $e->getMessage();
    header('Location: annonces.php');
    exit();
}
?> 