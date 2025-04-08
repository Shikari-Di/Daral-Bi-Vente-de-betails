<?php
session_start();

// Vérification si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../admin-login.php');
    exit();
}

require_once '../db.php'; // Chemin correct vers db.php

// Récupérer tous les utilisateurs
try {
    $stmt = $pdo->query("SELECT * FROM utilisateurs");
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background: #f9f9f9;
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background:rgb(11, 150, 64);
            color: white;
        }

        a.btn {
            padding: 6px 10px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 5px;
            display: inline-block;
        }

        a.btn:hover {
            background:rgb(35, 87, 200);
        }

        .btn-retour {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 15px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-retour:hover {
            background: #218838;
        }

        .btn-ajouter {
            margin-bottom: 20px;
            display: inline-block;
            padding: 10px 15px;
            background:rgb(11, 150, 64);
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-ajouter:hover {
            background:rgb(9, 121, 52);
        }
    </style>
</head>
<body>
    <h1>Gestion des utilisateurs</h1>

    <a href="ajouter_utilisateur.php" class="btn-ajouter">Ajouter un utilisateur</a>

    <?php if (!empty($utilisateurs)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Type de compte</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['type_compte']) ?></td>
                        <td>
                            <a class="btn" href="modifier_utilisateur.php?id=<?= htmlspecialchars($user['id']) ?>">Modifier</a>
                            <a class="btn" href="supprimer_utilisateur.php?id=<?= htmlspecialchars($user['id']) ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun utilisateur trouvé.</p>
    <?php endif; ?>

    <a href="../admin.php" class="btn-retour">← Retour au panneau admin</a>
</body>
</html>
