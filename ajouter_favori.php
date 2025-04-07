<?php
include 'db.php'; // Inclure la connexion à la base de données
session_start(); // Si vous utilisez des sessions pour gérer les utilisateurs

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: connexion.php");
    exit();
}

// Vérifier si l'ID de l'annonce est fourni
if (!empty($_POST['annonce_id'])) {
    $annonce_id = (int)$_POST['annonce_id'];
    $utilisateur_id = $_SESSION['utilisateur_id'];

    // Vérifier si l'annonce est déjà dans les favoris
    $stmt = $pdo->prepare("SELECT * FROM favoris WHERE utilisateur_id = ? AND annonce_id = ?");
    $stmt->execute([$utilisateur_id, $annonce_id]);
    $favori = $stmt->fetch();

    if ($favori) {
        echo "Cette annonce est déjà dans vos favoris.";
    } else {
        // Ajouter l'annonce aux favoris
        $stmt = $pdo->prepare("INSERT INTO favoris (utilisateur_id, annonce_id) VALUES (?, ?)");
        if ($stmt->execute([$utilisateur_id, $annonce_id])) {
            echo "Annonce ajoutée aux favoris avec succès.";
        } else {
            echo "Erreur lors de l'ajout aux favoris.";
        }
    }
} else {
    echo "Aucune annonce spécifiée.";
}
?>