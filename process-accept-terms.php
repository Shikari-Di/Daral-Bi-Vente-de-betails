<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Marquer les conditions comme acceptées dans la session
    $_SESSION['terms_accepted'] = true;
    
    // Marquer que l'utilisateur vient d'accepter les conditions
    $_SESSION['just_accepted_terms'] = true;

    // Récupérer les données sauvegardées du sessionStorage
    if (isset($_POST['form_data'])) {
        $_SESSION['register_data'] = json_decode($_POST['form_data'], true);
    }
    
    // Rediriger vers le formulaire d'inscription avec les données
    header('Location: register.php');
    exit();
} else {
    header('Location: conditions-generales.php');
    exit();
} 