<?php
include 'db.php'; // Assurez-vous que db.php contient la connexion à la base de données

// Récupérer tous les utilisateurs
$sql = "SELECT * FROM utilisateurs";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs - Admin</title>
    <link rel="stylesheet" href="assets/css/admin.css">
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
            margin-top: 20px;
            display: inline-block;
            padding: 10px 15px;
            background:rgb(0, 255, 128);
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-ajouter:hover {
            background:rgba(42, 179, 0, 0.42);
            

        }
    </style>
</head>
<body>

    <h1>Gestion des utilisateurs</h1>

    <a href="ajouter_utilisateur.php" class="btn-ajouter">Ajouter un utilisateur</a>

    <?php if (mysqli_num_rows($result) > 0): ?>
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
                <?php while ($user = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['type_compte']) ?></td>
                        <td>
                            <a class="btn" href="modifier_utilisateur.php?id=<?= $user['id'] ?>">Modifier</a>
                            <a class="btn" href="supprimer_utilisateur.php?id=<?= $user['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun utilisateur trouvé.</p>
    <?php endif; ?>

    <a href="admin.php" class="btn-retour">← Retour au panneau admin</a>

</body>
</html>

<?php mysqli_close($conn); ?>
