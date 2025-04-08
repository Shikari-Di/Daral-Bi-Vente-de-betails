<?php
// Connexion à la base de données
require_once '../db.php'; 

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $stmt = $pdo->prepare("DELETE FROM annonces WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirection après suppression
        header("Location: ../admin.php?message=suppression_ok");
        exit();
    } catch (PDOException $e) {
        echo "<p>Erreur lors de la suppression : " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>ID d'annonce non spécifié.</p>";
}
?>


<!-- Bouton Retour -->
<a href="administrateur.php" class="btn-retour"><i class="fas fa-arrow-left"></i> Retour</a>

<!-- Un peu de style -->
<style>
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
        background-color: rgba(138, 207, 147, 0.01);
    }
    p {
        font-size: 25px;
        color: #333;
        text-align: center;
        margin-top: 20px;
        font-weight: bold;
        color: rgb(0, 255, 179);



    }
    .btn-retour {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 15px;
        background-color: #28a745;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-weight: bold;
        text-align: center;
    }
    .btn-retour:hover {
        background-color:rgb(16, 207, 89);
        text-decoration: black;
        font-size: 40px;
        font-weight: bold;
        color: rgba(214, 67, 99, 0.61);    


    }
    .fas {
        margin-right: 5px;
    }


</style>
