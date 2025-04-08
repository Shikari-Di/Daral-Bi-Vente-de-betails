<?php
session_start();
require_once 'config/database.php';

// Vérification de la connexion
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Vous devez être connecté pour supprimer une annonce.";
    header('Location: login.php');
    exit();
}

// Vérification de l'ID de l'annonce
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Annonce non trouvée.";
    header('Location: dashboard-vendeur.php');
    exit();
}

$annonce_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

try {
    // Récupération des informations de l'annonce pour vérifier le propriétaire et l'image
    $stmt = $pdo->prepare("SELECT * FROM annonces WHERE id = ? AND utilisateur_id = ?");
    $stmt->execute([$annonce_id, $user_id]);
    $annonce = $stmt->fetch();

    if (!$annonce) {
        $_SESSION['error_message'] = "Vous n'êtes pas autorisé à supprimer cette annonce.";
        header('Location: dashboard-vendeur.php');
        exit();
    }

    // Suppression de l'image associée
    if (!empty($annonce['image']) && file_exists($annonce['image'])) {
        unlink($annonce['image']);
    }

    // Suppression de l'annonce
    $stmt = $pdo->prepare("DELETE FROM annonces WHERE id = ? AND utilisateur_id = ?");
    $stmt->execute([$annonce_id, $user_id]);

    $_SESSION['success_message'] = "L'annonce a été supprimée avec succès.";
    header('Location: dashboard-vendeur.php');
    exit();

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erreur lors de la suppression de l'annonce : " . $e->getMessage();
    header('Location: dashboard-vendeur.php');
    exit();
}
?> 