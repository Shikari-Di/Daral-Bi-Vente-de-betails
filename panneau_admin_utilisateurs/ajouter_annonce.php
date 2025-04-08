<?php
require_once '../db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = $_POST['titre'] ?? '';
    $prix = $_POST['prix'] ?? '';
    $description = $_POST['description'] ?? '';
    $localisation_id = $_POST['localisation_id'] ?? '';
    $image = $_FILES['image'] ?? null;

    // Gestion de l'image (sauvegarde dans un dossier "assets/images")
    if ($image && $image['error'] === UPLOAD_ERR_OK) {
        $imageName = basename($image['name']);
        $targetPath = "assets/images/" . $imageName;
        move_uploaded_file($image['tmp_name'], $targetPath);
    } else {
        $imageName = 'default.jpg'; // image par défaut
    }

    // Insertion dans la base de données
    $sql = "INSERT INTO annonces (titre, prix, description, image, localisation_id, date_creation)
            VALUES (:titre, :prix, :description, :image, :localisation_id, NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titre' => $titre,
        ':prix' => $prix,
        ':description' => $description,
        ':image' => $imageName,
        ':localisation_id' => $localisation_id
    ]);

    $message = "✅ Annonce ajoutée avec succès !";
}

// Récupération des localisations
$localisations = $pdo->query("SELECT id, nom FROM localisations")->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="admin-page">
    <div class="container">
        <h1>➕ Ajouter une nouvelle annonce</h1>
        <?php if ($message): ?>
            <p class="success-message"><?= $message ?></p>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" class="form-annonce">
            <label for="titre">Titre :</label>
            <input type="text" name="titre" required>

            <label for="prix">Prix :</label>
            <input type="number" name="prix" required>

            <label for="description">Description :</label>
            <textarea name="description" rows="4" required></textarea>

            <label for="localisation_id">Localisation :</label>
            <select name="localisation_id" required>
                <option value="">-- Choisir une localisation --</option>
                <?php foreach ($localisations as $loc): ?>
                    <option value="<?= $loc['id'] ?>"><?= htmlspecialchars($loc['nom']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="image">Image :</label>
            <input type="file" name="image" accept="image/*">

            <button type="submit" class="btn btn-primary">Ajouter l'annonce</button>
        </form>

        <a href="admin.php" class="btn-retour"><i class="fas fa-arrow-left"></i> Retour</a>
    </div>
</section>

<style>
    .admin-page {
        padding: 30px;
        background-color: #f2f9f2;
        font-family: Arial;
    }
    h1 {
        color: #1b5e20;
        margin-bottom: 20px;
    }
    .form-annonce {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        max-width: 600px;
        margin: auto;
        box-shadow: 0 0 8px rgba(0,0,0,0.1);
    }
    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
        color: #333;
    }
    input[type="text"],
    input[type="number"],
    select,
    textarea {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        margin-top: 5px;
    }
    .btn {
        display: inline-block;
        margin-top: 20px;
        text-decoration: none;
        font-weight: bold;
        padding: 10px 15px;
        border-radius: 5px;
        transition: 0.2s ease-in-out;
        cursor: pointer;
    }
    .btn-primary {
        background-color: #2ecc71;
        color: white;
        border: none;
    }
    .btn-retour {
        background-color: #1b5e20;
        color: white;
        text-decoration: none;
        padding: 10px 15px;
        border-radius: 5px;
        display: inline-block;
        margin-top: 30px;
    }
    .success-message {
        text-align: center;
        color: #2ecc71;
        font-weight: bold;
        margin-bottom: 20px;
        font-size: 18px;
    }
</style>
