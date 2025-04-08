<?php
session_start();

// Vérification de la session administrateur
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

// Vérification de l'inactivité (30 minutes)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header('Location: admin-login.php?timeout=1');
    exit();
}

// Mise à jour du temps d'activité
$_SESSION['last_activity'] = time(); 