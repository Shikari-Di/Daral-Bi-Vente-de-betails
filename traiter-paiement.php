<?php
session_start();
require_once 'config/database.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Vous devez être connecté pour effectuer un paiement.";
    header('Location: login.php');
    exit();
}

// Vérifier si les données de paiement sont présentes
if (!isset($_POST['mode_paiement'])) {
    $_SESSION['error_message'] = "Veuillez sélectionner un mode de paiement.";
    header('Location: paiement.php');
    exit();
}

try {
    // Démarrer une transaction
    $pdo->beginTransaction();

    // Si c'est un achat direct
    if (isset($_SESSION['achat_direct'])) {
        $annonce = $_SESSION['achat_direct'];
        
        // Créer la commande
        $stmt = $pdo->prepare("
            INSERT INTO commandes (annonce_id, acheteur_id, montant_total, statut, mode_paiement)
            VALUES (?, ?, ?, 'En attente', ?)
        ");
        $stmt->execute([
            $annonce['annonce_id'],
            $_SESSION['user_id'],
            $annonce['prix'],
            $_POST['mode_paiement']
        ]);

        unset($_SESSION['achat_direct']);
    }
    // Si c'est un achat depuis le panier
    elseif (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
        foreach ($_SESSION['panier'] as $annonce_id => $item) {
            // Créer une commande pour chaque article du panier
            $stmt = $pdo->prepare("
                INSERT INTO commandes (annonce_id, acheteur_id, montant_total, statut, mode_paiement)
                VALUES (?, ?, ?, 'En attente', ?)
            ");
            $stmt->execute([
                $annonce_id,
                $_SESSION['user_id'],
                $item['prix'] * $item['quantite'],
                $_POST['mode_paiement']
            ]);
        }

        // Vider le panier
        unset($_SESSION['panier']);
    } else {
        throw new Exception("Aucun article à payer.");
    }

    // Valider la transaction
    $pdo->commit();

    $_SESSION['success_message'] = "Votre commande a été enregistrée avec succès.";
    header('Location: mes-commandes.php');
    exit();

} catch (Exception $e) {
    // Annuler la transaction en cas d'erreur
    $pdo->rollBack();
    $_SESSION['error_message'] = "Erreur lors du traitement du paiement : " . $e->getMessage();
    header('Location: paiement.php');
    exit();
}
?> 