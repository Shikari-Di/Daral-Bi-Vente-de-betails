<?php
session_start();
require_once 'config/database.php';

if (!isset($_POST['annonce_id'])) {
    die('ID de l\'annonce manquant');
}

$annonce_id = (int)$_POST['annonce_id'];

try {
    // Vérifier si l'annonce existe
    $stmt = $pdo->prepare("SELECT id FROM annonces WHERE id = ?");
    $stmt->execute([$annonce_id]);
    
    if ($stmt->fetch()) {
        // Enregistrer la vue
        $stmt = $pdo->prepare("INSERT INTO vues_annonces (annonce_id) VALUES (?)");
        $stmt->execute([$annonce_id]);
        echo 'Vue enregistrée';
    } else {
        echo 'Annonce non trouvée';
    }
} catch (PDOException $e) {
    echo 'Erreur lors de l\'enregistrement de la vue';
} 