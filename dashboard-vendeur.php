<?php
session_start();
require_once 'config/database.php';

// Vérification de la connexion
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Vous devez être connecté pour accéder au tableau de bord.";
    header('Location: login.php');
    exit();
}

// Récupérer les informations de l'utilisateur
$user_info = [];
$annonces = [];
$message = '';
$error = '';

try {
    // Récupérer les informations de l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_info) {
        header('Location: login.php');
        exit();
    }

    // Récupérer les annonces de l'utilisateur
    $stmt = $pdo->prepare("
        SELECT a.*, 
               l.nom as localisation_nom,
               c.nom as categorie_nom,
               r.nom as race_nom,
               (SELECT COUNT(*) FROM vues_annonces WHERE annonce_id = a.id) as nombre_vues
        FROM annonces a
        LEFT JOIN localisations l ON a.localisation_id = l.id
        LEFT JOIN categories c ON a.categorie_id = c.id
        LEFT JOIN races r ON a.race_id = r.id
        WHERE a.utilisateur_id = ?
        ORDER BY a.date_creation DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les statistiques
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total_annonces,
            SUM(CASE WHEN date_creation >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as annonces_recentes
        FROM annonces 
        WHERE utilisateur_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des données : " . $e->getMessage();
}

// Traitement de la suppression d'une annonce
if (isset($_POST['delete_annonce']) && isset($_POST['annonce_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT image FROM annonces WHERE id = ? AND utilisateur_id = ?");
        $stmt->execute([$_POST['annonce_id'], $_SESSION['user_id']]);
        $annonce = $stmt->fetch();

        if ($annonce) {
            // Supprimer l'image
            if (file_exists($annonce['image'])) {
                unlink($annonce['image']);
            }

            // Supprimer l'annonce
            $stmt = $pdo->prepare("DELETE FROM annonces WHERE id = ? AND utilisateur_id = ?");
            $stmt->execute([$_POST['annonce_id'], $_SESSION['user_id']]);

            $message = "Annonce supprimée avec succès";
            header("Location: dashboard-vendeur.php");
            exit();
        }
    } catch (PDOException $e) {
        $error = "Erreur lors de la suppression de l'annonce : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Vendeur - Daral Bi</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* ... existing styles ... */
        .user-welcome {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .user-welcome h2 {
            margin: 0;
            font-size: 24px;
        }
        .user-welcome p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <?php include 'components/header.php'; ?>

    <div class="container mt-5 mb-5">
        <!-- Message de bienvenue personnalisé -->
        <div class="user-welcome">
            <h2>Bienvenue, <?php echo htmlspecialchars($user_info['nom']); ?> !</h2>
            <p>Gérez vos annonces et suivez vos performances</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-12 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h1>Mes annonces</h1>
                    <a href="nouvelle-annonce.php" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Créer une nouvelle annonce
                    </a>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <h5><i class="fas fa-list me-2"></i>Total des annonces</h5>
                    <div class="stats-number"><?php echo $stats['total_annonces']; ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <h5><i class="fas fa-clock me-2"></i>Annonces ce mois</h5>
                    <div class="stats-number"><?php echo $stats['annonces_recentes']; ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <h5><i class="fas fa-eye me-2"></i>Vues totales</h5>
                    <div class="stats-number">
                        <?php 
                        $total_vues = array_sum(array_column($annonces, 'nombre_vues'));
                        echo $total_vues;
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0"><i class="fas fa-list me-2"></i>Liste de mes annonces</h3>
            </div>
            <div class="card-body">
                <?php if (empty($annonces)): ?>
                    <div class="empty-state">
                        <i class="fas fa-clipboard"></i>
                        <h3>Aucune annonce</h3>
                        <p>Vous n'avez pas encore créé d'annonces.</p>
                        <a href="nouvelle-annonce.php" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i>Créer ma première annonce
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Titre</th>
                                    <th>Prix</th>
                                    <th>Localisation</th>
                                    <th>Catégorie</th>
                                    <th>Race</th>
                                    <th>Vues</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($annonces as $annonce): ?>
                                    <tr>
                                        <td>
                                            <img src="<?php echo htmlspecialchars($annonce['image']); ?>" 
                                                 alt="<?php echo htmlspecialchars($annonce['titre']); ?>" 
                                                 class="annonce-image">
                                        </td>
                                        <td><?php echo htmlspecialchars($annonce['titre']); ?></td>
                                        <td><?php echo number_format($annonce['prix'], 0, ',', ' '); ?> CFA</td>
                                        <td><?php echo htmlspecialchars($annonce['localisation_nom']); ?></td>
                                        <td><?php echo htmlspecialchars($annonce['categorie_nom']); ?></td>
                                        <td><?php echo htmlspecialchars($annonce['race_nom']); ?></td>
                                        <td><?php echo $annonce['nombre_vues'] ?? 0; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($annonce['date_creation'])); ?></td>
                                        <td class="action-buttons">
                                            <a href="modifier-annonce.php?id=<?php echo $annonce['id']; ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
                                                <input type="hidden" name="annonce_id" value="<?php echo $annonce['id']; ?>">
                                                <button type="submit" name="delete_annonce" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 