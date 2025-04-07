<?php
if (isset($_GET['query'])) {
    $query = htmlspecialchars($_GET['query']); // Sécuriser l'entrée utilisateur
    echo "Résultats pour : " . $query;

    // Ajoutez ici la logique pour rechercher dans votre base de données
    // Exemple :
    // $results = $db->query("SELECT * FROM animaux WHERE nom LIKE '%$query%'");
    // foreach ($results as $result) {
    //     echo "<p>" . $result['nom'] . "</p>";
    // }
} else {
    echo "Aucun terme de recherche fourni.";
}
?>