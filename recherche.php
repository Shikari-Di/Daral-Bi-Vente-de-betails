<?php
include 'config\database.php';

try {
    $query = $pdo->query("SELECT * FROM annonces ORDER BY date_creation DESC");
    $annonces = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$host = 'localhost';
$dbname = 'daral_bi';
$username = 'root';
$password = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$search = $_GET['q'] ?? '';

$sql = "SELECT * FROM annonces WHERE titre LIKE :search OR description LIKE :search";
$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => '%' . $search . '%']);
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche - Daaral Bi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .search-bar {
            padding: 30px;
            text-align: center;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .search-input {
            width: 60%;
            padding: 12px 20px;
            border: 2px solid #ccc;
            border-radius: 30px;
            font-size: 16px;
        }

        .search-button {
            background-color: #20c997;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            cursor: pointer;
            margin-left: -62px;
            position: relative;
            z-index: 2;
        }

        h2 {
            text-align: center;
            color: #20c997;
            margin-top: 30px;
        }

        .annonce-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 40px 20px;
            gap: 20px;
        }

        .annonce-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            padding: 20px;
            width: 300px;
            transition: transform 0.2s ease;
        }

        .annonce-card:hover {
            transform: translateY(-5px);
        }

        .annonce-card h3 {
            color: #007bff;
            margin-bottom: 10px;
        }

        .annonce-card p {
            margin: 6px 0;
            color: #444;
        }

        .statut {
            font-weight: bold;
        }

    </style>
</head>
<body>

    <div class="search-bar">
        <form method="GET" action="recherche.php">
            <input type="text" name="q" placeholder="Rechercher un animal..." class="search-input" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <h2>Résultats de recherche</h2>

    <div class="annonce-container">
        <?php if ($annonces): ?>
            <?php foreach ($annonces as $annonce): ?>
                <div class="annonce-card">
                    <h3>
                    <a href="details.php?id=<?= $annonce['id'] ?>" style="display:inline-block;margin-top:10px;padding:8px 16px;background:#20c997;color:white;border-radius:20px;text-decoration:none;">Voir détails</a>
                            <?= htmlspecialchars($annonce['titre']) ?>
                      </a>
                    </h3>
                    <p><strong>Prix:</strong> <?= number_format($annonce['prix'], 0, ',', ' ') ?> FCFA</p>
                    <p><strong>Poids:</strong> <?= htmlspecialchars($annonce['poids']) ?> kg</p>
                    <p class="statut"><strong>Statut:</strong> Disponible</p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune annonce trouvée pour cette recherche.</p>
        <?php endif; ?>
    </div>
    <section class="search-results">
    <div class="container">
        <h2 class="section-title">Résultats de recherche</h2>
        <div class="products-grid">
            <?php foreach ($annonces as $annonce): ?>
                <a href="detail-produit.php?id=<?php echo $annonce['id']; ?>" class="product-card">
                    <img src="assets/images/<?php echo htmlspecialchars($annonce['image']); ?>" alt="<?php echo htmlspecialchars($annonce['titre']); ?>" class="product-image">
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($annonce['titre']); ?></h3>
                        <p class="description"><?php echo htmlspecialchars($annonce['age']); ?> mois, <?php echo htmlspecialchars($annonce['poids']); ?> kg</p>
                        <p class="price">FCFA <?php echo number_format($annonce['prix'], 0, ',', ' '); ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

</body>
</html>
