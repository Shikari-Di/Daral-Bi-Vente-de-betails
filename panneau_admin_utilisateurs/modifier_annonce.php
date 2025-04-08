<?php
// Connexion à la base de données
include '../db.php';

// Vérifie que l'ID est passé dans l'URL
if (!isset($_GET['id'])) {
    echo "ID de l'annonce manquant.";
    exit();
}

$id = intval($_GET['id']);
$message = "";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $prix = $_POST['prix'];
    $localisation = $_POST['localisation'];

    $sql = "UPDATE annonces SET titre = ?, prix = ?, localisation = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$titre, $prix, $localisation, $id])) {
        $message = "Annonce mise à jour avec succès.";
    } else {
        $message = "Erreur lors de la mise à jour.";
    }
}

// Récupérer les données actuelles de l'annonce
$sql = "SELECT * FROM annonces WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$annonce = $stmt->fetch();

if (!$annonce) {
    echo "Annonce non trouvée.";
    exit();
}
?>

<section class="modifier-annonce">
    <div class="container">
        <h1>Modifier l'annonce</h1>

        <?php if (!empty($message)): ?>
            <p style="color: green; font-weight: bold"><?= $message ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="titre" value="<?= htmlspecialchars($annonce['titre']) ?>" required><br>
            <input type="number" name="prix" value="<?= htmlspecialchars($annonce['prix']) ?>" required><br>
            <input type="text" name="localisation" value="<?= htmlspecialchars($annonce['localisation'] ?? '') ?>" required><br>
            <button type="submit">💾 Enregistrer</button>
        </form>
        <a href="admin.php" id="backbutton">⬅ Retour</a>
    </div>
</section>

<style>
    #backbutton{
        background-color: rgb(19, 194, 48);
        color: white;
        border-radius: 20px;
        cursor: pointer;
        padding: 5px;
        text-decoration: none;
    }
    .modifier-annonce {
        padding: 20px;
        background-color: #f9f9f9;
    }
    .modifier-annonce form {
        display: flex;
        flex-direction: column;
    }
    .modifier-annonce input, .modifier-annonce button {
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
    .modifier-annonce button {
        background-color: rgb(0, 255, 42);
        color: white;
        border: none;
        cursor: pointer;
    }
    .modifier-annonce button:hover {
        background-color: rgb(0, 143, 179);
    }
    .modifier-annonce a:hover {
        text-decoration: underline;
        color: rgb(0, 174, 255);
        font-size: 30px;
    }
    .modifier-annonce h1 {
        color: #333;
        text-align: center;
    }
    .modifier-annonce .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>
