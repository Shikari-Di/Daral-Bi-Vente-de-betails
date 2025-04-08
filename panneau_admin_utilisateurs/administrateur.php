<?php
require_once '../db.php'; // Connexion à la BDD

$pageTitle = "Admin - Gestion des annonces";

// Récupération des annonces
$sql = "SELECT a.id, a.titre, a.prix, a.image, l.nom AS localisation
        FROM annonces a
        INNER JOIN localisations l ON a.localisation_id = l.id
        ORDER BY a.date_creation DESC";
$annonces = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="admin-page">
    <div class="container">
        <h1>Panneau d'administration</h1>
        <p>Bienvenue, administrateur. Gérez les annonces ci-dessous.</p>

        <div class="admin-actions">
            <a href="ajouter_annonce.php" class="btn btn-primary">➕ Ajouter une annonce</a>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
            <th>Image</th>
                    <th>Titre</th>
                    <th>Prix</th>
                    <th>Localisation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($annonces as $annonce): ?>
                <tr>
                    <td><?= $annonce['id'] ?></td>
                    <td><img src="img\<?= $annonce['image'] ?>" width="60" style="border-radius: 8px;"></td>
                    <td><?= htmlspecialchars($annonce['titre']) ?></td>
                    <td><?= number_format($annonce['prix'], 0, ',', ' ') ?> FCFA</td>
                    <td><?= htmlspecialchars($annonce['localisation']) ?></td>
                    <td>
                        <a href="modifier_annonce.php?id=<?= $annonce['id'] ?>" class="btn btn-warning">✏️ Modifier</a>
                        <a href="panneau_admin_utilisateurs/supprimer_annonce.php?id=<?= $annonce['id'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">🗑️ Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="admin.php" class="btn-retour"><i class="fas fa-arrow-left"></i> Retour</a>
    </div>
</section>

<style>
    .admin-page {
        padding: 30px;
        background-color: #f2f9f2;
        font-family: 'Segoe UI', Arial, sans-serif;
    }
    h1 {
        color: #1b5e20;
        margin-bottom: 10px;
    }
    .btn {
        display: inline-block;
        text-decoration: none;
        font-weight: bold;
        padding: 8px 12px;
        border-radius: 5px;
        margin-right: 5px;
        transition: all 0.2s ease;
        font-size: 14px;
        border: none;
        cursor: pointer;
    }
    .btn-primary {
        background-color: #2ecc71;
        color: white;
    }
    .btn-warning {
        background-color: #f39c12;
        color: white;
    }
    .btn-danger {
        background-color: #e74c3c;
        color: white;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .admin-table th, .admin-table td {
        border: 1px solid #e0e0e0;
        padding: 12px;
        text-align: center;
    }
    .admin-table th {
        background-color: #dff0d8;
        font-weight: 600;
    }
    .admin-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .admin-table tr:hover {
        background-color: #f1f8e9;
    }
    .btn-retour {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 15px;
        background-color: #1b5e20;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: all 0.2s ease;
    }
    .btn-retour:hover {
        background-color: #2e7d32;
    }
</style>