<?php
session_start();
require_once 'config/database.php';

// Vérification de la connexion
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Vous devez être connecté pour modifier une annonce.";
    header('Location: login.php');
    exit();
}

// Vérification de l'ID de l'annonce
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Annonce non trouvée.";
    header('Location: dashboard-vendeur.php');
    exit();
}

$annonce_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

try {
    // Récupération des données de l'annonce
    $stmt = $pdo->prepare("
        SELECT a.*, l.nom as localisation_nom, c.nom as categorie_nom, r.nom as race_nom
        FROM annonces a
        LEFT JOIN localisations l ON a.localisation_id = l.id
        LEFT JOIN categories c ON a.categorie_id = c.id
        LEFT JOIN races r ON a.race_id = r.id
        WHERE a.id = ? AND a.utilisateur_id = ?
    ");
    $stmt->execute([$annonce_id, $user_id]);
    $annonce = $stmt->fetch();

    if (!$annonce) {
        $_SESSION['error_message'] = "Vous n'êtes pas autorisé à modifier cette annonce.";
        header('Location: dashboard-vendeur.php');
        exit();
    }

    // Récupération des listes pour les select
    $stmt = $pdo->query("SELECT * FROM localisations ORDER BY nom");
    $localisations = $stmt->fetchAll();

    $stmt = $pdo->query("SELECT * FROM categories ORDER BY nom");
    $categories = $stmt->fetchAll();

    $stmt = $pdo->query("SELECT * FROM races ORDER BY nom");
    $races = $stmt->fetchAll();

} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des données : " . $e->getMessage();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $prix = (float)$_POST['prix'];
    $poids = (int)$_POST['poids'];
    $age = (int)$_POST['age'];
    $localisation_id = (int)$_POST['localisation_id'];
    $categorie_id = (int)$_POST['categorie_id'];
    $race_id = (int)$_POST['race_id'];

    $errors = [];

    // Validation des champs
    if (empty($titre)) $errors[] = "Le titre est requis";
    if (empty($description)) $errors[] = "La description est requise";
    if ($prix <= 0) $errors[] = "Le prix doit être supérieur à 0";
    if ($poids <= 0) $errors[] = "Le poids doit être supérieur à 0";
    if ($age <= 0) $errors[] = "L'âge doit être supérieur à 0";

    // Traitement de la nouvelle image si fournie
    $image = $annonce['image']; // Garde l'ancienne image par défaut
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed)) {
            $errors[] = "Format d'image non autorisé. Formats acceptés : " . implode(', ', $allowed);
        } else {
            $new_image = 'uploads/' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $new_image)) {
                // Suppression de l'ancienne image
                if (file_exists($annonce['image'])) {
                    unlink($annonce['image']);
                }
                $image = $new_image;
            } else {
                $errors[] = "Erreur lors du téléchargement de l'image";
            }
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE annonces 
                SET titre = ?, description = ?, prix = ?, poids = ?, age = ?,
                    localisation_id = ?, categorie_id = ?, race_id = ?, image = ?
                WHERE id = ? AND utilisateur_id = ?
            ");
            
            $stmt->execute([
                $titre, $description, $prix, $poids, $age,
                $localisation_id, $categorie_id, $race_id, $image,
                $annonce_id, $user_id
            ]);

            $_SESSION['success_message'] = "L'annonce a été modifiée avec succès.";
            header('Location: dashboard-vendeur.php');
            exit();
        } catch (PDOException $e) {
            $error = "Erreur lors de la modification de l'annonce : " . $e->getMessage();
        }
    } else {
        $error = implode('<br>', $errors);
    }
}

$pageTitle = "Modifier l'annonce";
include 'components/header.php';
?>

<div class="nouvelle-annonce-page">
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h1>Modifier l'annonce</h1>
                <p class="subtitle">Modifiez les informations de votre annonce</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($message): ?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data" class="annonce-form">
                <div class="form-section">
                    <h3>Informations générales</h3>
                    <div class="form-group">
                        <label for="titre">Titre de l'annonce</label>
                        <input type="text" class="form-control" id="titre" name="titre" 
                               value="<?php echo htmlspecialchars($annonce['titre']); ?>" required>
                        <small class="form-text text-muted">Donnez un titre clair et descriptif à votre annonce</small>
                    </div>

                    <div class="form-group">
                        <label for="description">Description détaillée</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($annonce['description']); ?></textarea>
                        <small class="form-text text-muted">Décrivez les caractéristiques importantes de votre bétail</small>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Caractéristiques</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="prix">Prix (CFA)</label>
                                <input type="number" class="form-control" id="prix" name="prix" 
                                       value="<?php echo $annonce['prix']; ?>" required>
                                <small class="form-text text-muted">Prix en francs CFA</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="poids">Poids (kg)</label>
                                <input type="number" class="form-control" id="poids" name="poids" 
                                       value="<?php echo $annonce['poids']; ?>" required>
                                <small class="form-text text-muted">Poids en kilogrammes</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="age">Âge (mois)</label>
                                <input type="number" class="form-control" id="age" name="age" 
                                       value="<?php echo $annonce['age']; ?>" required>
                                <small class="form-text text-muted">Âge en mois</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Catégorisation</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="localisation_id">Localisation</label>
                                <select class="form-control" id="localisation_id" name="localisation_id" required>
                                    <?php foreach ($localisations as $loc): ?>
                                        <option value="<?php echo $loc['id']; ?>" 
                                            <?php echo $loc['id'] == $annonce['localisation_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($loc['nom']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="categorie_id">Catégorie</label>
                                <select class="form-control" id="categorie_id" name="categorie_id" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>"
                                            <?php echo $cat['id'] == $annonce['categorie_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['nom']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="race_id">Race</label>
                                <select class="form-control" id="race_id" name="race_id" required>
                                    <?php foreach ($races as $race): ?>
                                        <option value="<?php echo $race['id']; ?>"
                                            <?php echo $race['id'] == $annonce['race_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($race['nom']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Photo du bétail</h3>
                    <div class="form-group">
                        <label>Image actuelle</label>
                        <div class="current-image mb-3">
                            <img src="<?php echo htmlspecialchars($annonce['image']); ?>" 
                                 alt="Image actuelle" class="img-thumbnail" style="max-height: 200px;">
                        </div>
                        <label for="image">Nouvelle image (optionnel)</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <small class="form-text text-muted">
                            Formats acceptés : JPG, JPEG, PNG, GIF. Laissez vide pour garder l'image actuelle.
                        </small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                    <a href="dashboard-vendeur.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.nouvelle-annonce-page {
    padding: 40px 0;
    background-color: #f8f9fa;
}

.form-container {
    background-color: #fff;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.form-header {
    text-align: center;
    margin-bottom: 30px;
}

.form-header h1 {
    color: #2c3e50;
    font-size: 2.5em;
    margin-bottom: 10px;
}

.subtitle {
    color: #7f8c8d;
    font-size: 1.1em;
}

.form-section {
    margin-bottom: 40px;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
}

.form-section h3 {
    color: #2c3e50;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-weight: 600;
    color: #34495e;
    margin-bottom: 8px;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 6px;
    padding: 12px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52,152,219,0.25);
}

.form-text {
    color: #95a5a6;
    font-size: 0.9em;
    margin-top: 5px;
}

.current-image {
    text-align: center;
    padding: 10px;
    background-color: #fff;
    border-radius: 6px;
    border: 2px solid #e9ecef;
}

.form-actions {
    text-align: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #e9ecef;
}

.btn {
    padding: 12px 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #3498db;
    border-color: #3498db;
}

.btn-primary:hover {
    background-color: #2980b9;
    border-color: #2980b9;
}

.btn-secondary {
    background-color: #95a5a6;
    border-color: #95a5a6;
    margin-left: 10px;
}

.btn-secondary:hover {
    background-color: #7f8c8d;
    border-color: #7f8c8d;
}

.alert {
    border-radius: 6px;
    margin-bottom: 20px;
    padding: 15px 20px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prévisualisation de l'image
    const imageInput = document.getElementById('image');
    const previewImage = document.querySelector('.img-thumbnail');
    
    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Validation du formulaire
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const prix = document.getElementById('prix').value;
        const poids = document.getElementById('poids').value;
        const age = document.getElementById('age').value;

        if (prix <= 0) {
            e.preventDefault();
            alert('Le prix doit être supérieur à 0');
            return;
        }

        if (poids <= 0) {
            e.preventDefault();
            alert('Le poids doit être supérieur à 0');
            return;
        }

        if (age <= 0) {
            e.preventDefault();
            alert("L'âge doit être supérieur à 0");
            return;
        }
    });
});
</script>

<?php include 'components/footer.php'; ?> 