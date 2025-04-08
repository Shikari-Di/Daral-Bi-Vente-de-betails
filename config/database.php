<?php
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=daral_bi;charset=utf8mb4',
        'root',  // Nom d'utilisateur par défaut de XAMPP
        ''       // Mot de passe par défaut de XAMPP (vide)
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
} 
