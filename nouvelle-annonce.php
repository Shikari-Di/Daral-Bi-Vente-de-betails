<?php
session_start();
require_once 'config/database.php';

$message = '';
$error = '';
$uploadDir = 'uploads/';

// Création du dossier uploads s'il n'existe pas
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Récupérer les listes pour les select
$localisations = [];
$categories = [];
$races = [];

try {
    // Récupérer les localisations
    $stmt = $pdo->query("SELECT id, nom FROM localisations");
    $localisations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les catégories
    $stmt = $pdo->query("SELECT id, nom FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les races
    $stmt = $pdo->query("SELECT id, nom FROM races");
    $races = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des données : " . $e->getMessage();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $prix = filter_var($_POST['prix'] ?? '', FILTER_VALIDATE_FLOAT);
    $poids = filter_var($_POST['poids'] ?? '', FILTER_VALIDATE_INT);
    $age = filter_var($_POST['age'] ?? '', FILTER_VALIDATE_INT);
    $localisation_id = filter_var($_POST['localisation_id'] ?? '', FILTER_VALIDATE_INT);
    $categorie_id = filter_var($_POST['categorie_id'] ?? '', FILTER_VALIDATE_INT);
    $race_id = filter_var($_POST['race_id'] ?? '', FILTER_VALIDATE_INT);
    
    // Nouveaux champs pour vendeur non connecté
    $nom_vendeur = trim($_POST['nom_vendeur'] ?? '');
    $email_vendeur = trim($_POST['email_vendeur'] ?? '');
    $telephone_vendeur = trim($_POST['telephone_vendeur'] ?? '');

    // Validation des données
    $errors = [];
    if (empty($titre)) $errors[] = "Le titre est obligatoire";
    if (empty($description)) $errors[] = "La description est obligatoire";
    if ($prix === false || $prix <= 0) $errors[] = "Le prix doit être un nombre positif";
    if ($poids === false || $poids <= 0) $errors[] = "Le poids doit être un nombre entier positif";
    if ($age === false || $age <= 0) $errors[] = "L'âge doit être un nombre entier positif";
    if (!$localisation_id) $errors[] = "La localisation est obligatoire";
    if (!$categorie_id) $errors[] = "La catégorie est obligatoire";
    if (!$race_id) $errors[] = "La race est obligatoire";

    // Validation des informations du vendeur si non connecté
    if (!isset($_SESSION['user_id'])) {
        if (empty($nom_vendeur)) $errors[] = "Le nom du vendeur est obligatoire";
        if (empty($email_vendeur)) $errors[] = "L'email du vendeur est obligatoire";
        elseif (!filter_var($email_vendeur, FILTER_VALIDATE_EMAIL)) $errors[] = "L'email n'est pas valide";
        if (empty($telephone_vendeur)) $errors[] = "Le téléphone du vendeur est obligatoire";
        elseif (!preg_match("/^[0-9]{9}$/", $telephone_vendeur)) $errors[] = "Le numéro de téléphone doit contenir 9 chiffres";
    }

    // Validation de l'image
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "L'image est obligatoire";
    } else {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5 MB

        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            $errors[] = "Le type de fichier doit être JPEG, PNG ou GIF";
        }
        if ($_FILES['image']['size'] > $maxSize) {
            $errors[] = "La taille de l'image ne doit pas dépasser 5 MB";
        }
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            // Gestion de l'upload de l'image
            $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                throw new Exception("Erreur lors de l'upload de l'image");
            }

            // Si l'utilisateur n'est pas connecté, vérifier si un utilisateur existe déjà avec cet email
            $utilisateur_id = $_SESSION['user_id'] ?? null;
            
            if (!$utilisateur_id) {
                // Vérifier si l'utilisateur existe déjà
                $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
                $stmt->execute([$email_vendeur]);
                $existingUser = $stmt->fetch();

                if ($existingUser) {
                    $utilisateur_id = $existingUser['id'];
                } else {
                    // Créer un nouvel utilisateur
                    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email) VALUES (?, ?)");
                    $stmt->execute([$nom_vendeur, $email_vendeur]);
                    $utilisateur_id = $pdo->lastInsertId();
                }

                // Ajouter ou mettre à jour le contact du vendeur
                $stmt = $pdo->prepare("INSERT INTO contacts_vendeur (utilisateur_id, telephone) 
                                     VALUES (?, ?) 
                                     ON DUPLICATE KEY UPDATE telephone = ?");
                $stmt->execute([$utilisateur_id, $telephone_vendeur, $telephone_vendeur]);
            }

            // Insertion dans la base de données
            $stmt = $pdo->prepare("INSERT INTO annonces (titre, description, prix, poids, age, image, 
                                localisation_id, categorie_id, race_id, utilisateur_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([
                $titre,
                $description,
                $prix,
                $poids,
                $age,
                $targetPath,
                $localisation_id,
                $categorie_id,
                $race_id,
                $utilisateur_id
            ]);

            $pdo->commit();
            $message = "Annonce créée avec succès !";
            if (!isset($_SESSION['user_id'])) {
                $message .= " Un email vous sera envoyé avec vos identifiants de connexion.";
            }
            
            // Réinitialiser les champs du formulaire
            $_POST = [];
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Erreur lors de la création de l'annonce : " . $e->getMessage();
            // Supprimer l'image si elle a été uploadée
            if (isset($targetPath) && file_exists($targetPath)) {
                unlink($targetPath);
            }
        }
    } else {
        $error = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Annonce - Daral Bi</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2E7D32;      /* Vert foncé */
            --secondary-color: #795548;     /* Marron */
            --accent-color: #8BC34A;        /* Vert clair */
            --hover-color: #1B5E20;         /* Vert plus foncé pour hover */
            --error-color: #d32f2f;         /* Rouge pour les erreurs */
            --text-light: #ffffff;          /* Texte clair */
            --border-color: #ddd;           /* Couleur de bordure */
        }

        .preview-image {
            max-width: 200px;
            max-height: 200px;
            display: none;
            margin-top: 10px;
            border: 2px solid var(--border-color);
        }

        .form-label {
            font-weight: 600;
            color: var(--secondary-color);
        }

        .required::after {
            content: " *";
            color: var(--error-color);
        }

        .custom-file-upload {
            border: 2px dashed var(--border-color);
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .custom-file-upload:hover {
            border-color: var(--primary-color);
            background-color: rgba(46, 125, 50, 0.05);
        }

        .custom-file-upload i {
            font-size: 2em;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: var(--primary-color) !important;
            border-bottom: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--hover-color);
            border-color: var(--hover-color);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-secondary:hover {
            background-color: #5D4037;
            border-color: #5D4037;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.25);
        }

        .input-group-text {
            background-color: var(--primary-color);
            color: var(--text-light);
            border-color: var(--primary-color);
        }

        .alert-success {
            background-color: rgba(139, 195, 74, 0.1);
            border-color: var(--accent-color);
            color: var(--primary-color);
        }

        .alert-danger {
            background-color: rgba(211, 47, 47, 0.1);
            border-color: var(--error-color);
            color: var(--error-color);
        }

        .border-primary {
            border-color: var(--primary-color) !important;
        }

        .invalid-feedback {
            color: var(--error-color);
        }
    </style>
</head>
<body>
    <?php include 'components/header.php'; ?>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="text-center mb-0">Créer une nouvelle annonce</h2>
                    </div>
                    <div class="card-body">
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

                        <form method="POST" enctype="multipart/form-data" id="annonceForm" novalidate>
                            <div class="mb-4">
                                <label for="titre" class="form-label required">Titre de l'annonce</label>
                                <input type="text" class="form-control" id="titre" name="titre" 
                                       value="<?php echo htmlspecialchars($_POST['titre'] ?? ''); ?>" 
                                       required minlength="5" maxlength="255">
                                <div class="invalid-feedback">
                                    Le titre doit contenir entre 5 et 255 caractères
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label required">Description</label>
                                <textarea class="form-control" id="description" name="description" 
                                          rows="4" required minlength="20"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                                <div class="invalid-feedback">
                                    La description doit contenir au moins 20 caractères
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="prix" class="form-label required">Prix (CFA)</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="prix" name="prix" 
                                                   value="<?php echo htmlspecialchars($_POST['prix'] ?? ''); ?>" 
                                                   required min="1" step="0.01">
                                            <span class="input-group-text">CFA</span>
                                        </div>
                                        <div class="invalid-feedback">
                                            Le prix doit être supérieur à 0
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="poids" class="form-label required">Poids (kg)</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="poids" name="poids" 
                                                   value="<?php echo htmlspecialchars($_POST['poids'] ?? ''); ?>" 
                                                   required min="1">
                                            <span class="input-group-text">kg</span>
                                        </div>
                                        <div class="invalid-feedback">
                                            Le poids doit être supérieur à 0
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="age" class="form-label required">Âge (mois)</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="age" name="age" 
                                                   value="<?php echo htmlspecialchars($_POST['age'] ?? ''); ?>" 
                                                   required min="1">
                                            <span class="input-group-text">mois</span>
                                        </div>
                                        <div class="invalid-feedback">
                                            L'âge doit être supérieur à 0
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label required">Image</label>
                                <div class="custom-file-upload" id="dropZone">
                                    <input type="file" class="form-control d-none" id="image" name="image" 
                                           accept="image/jpeg,image/png,image/gif" required>
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <div>Cliquez ou glissez une image ici</div>
                                    <div class="text-muted small">Formats acceptés : JPEG, PNG, GIF (max 5 MB)</div>
                                </div>
                                <img id="imagePreview" class="preview-image img-thumbnail" alt="Aperçu de l'image">
                                <div class="invalid-feedback">
                                    Veuillez sélectionner une image valide
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="localisation_id" class="form-label required">Localisation</label>
                                        <select class="form-select" id="localisation_id" name="localisation_id" required>
                                            <option value="">Sélectionnez une localisation</option>
                                            <?php foreach ($localisations as $localisation): ?>
                                                <option value="<?php echo $localisation['id']; ?>" 
                                                        <?php echo (isset($_POST['localisation_id']) && $_POST['localisation_id'] == $localisation['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($localisation['nom']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Veuillez sélectionner une localisation
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="categorie_id" class="form-label required">Catégorie</label>
                                        <select class="form-select" id="categorie_id" name="categorie_id" required>
                                            <option value="">Sélectionnez une catégorie</option>
                                            <?php foreach ($categories as $categorie): ?>
                                                <option value="<?php echo $categorie['id']; ?>"
                                                        <?php echo (isset($_POST['categorie_id']) && $_POST['categorie_id'] == $categorie['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($categorie['nom']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Veuillez sélectionner une catégorie
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="race_id" class="form-label required">Race</label>
                                        <select class="form-select" id="race_id" name="race_id" required>
                                            <option value="">Sélectionnez une race</option>
                                            <?php foreach ($races as $race): ?>
                                                <option value="<?php echo $race['id']; ?>"
                                                        <?php echo (isset($_POST['race_id']) && $_POST['race_id'] == $race['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($race['nom']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Veuillez sélectionner une race
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if (!isset($_SESSION['user_id'])): ?>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="nom_vendeur" class="form-label required">Nom du vendeur</label>
                                        <input type="text" class="form-control" id="nom_vendeur" name="nom_vendeur" 
                                               value="<?php echo htmlspecialchars($_POST['nom_vendeur'] ?? ''); ?>" 
                                               required minlength="2">
                                        <div class="invalid-feedback">
                                            Le nom du vendeur est obligatoire
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="email_vendeur" class="form-label required">Email</label>
                                        <input type="email" class="form-control" id="email_vendeur" name="email_vendeur" 
                                               value="<?php echo htmlspecialchars($_POST['email_vendeur'] ?? ''); ?>" 
                                               required>
                                        <div class="invalid-feedback">
                                            Veuillez entrer une adresse email valide
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="telephone_vendeur" class="form-label required">Téléphone</label>
                                        <input type="tel" class="form-control" id="telephone_vendeur" name="telephone_vendeur" 
                                               value="<?php echo htmlspecialchars($_POST['telephone_vendeur'] ?? ''); ?>" 
                                               required pattern="[0-9]{9}">
                                        <div class="invalid-feedback">
                                            Le numéro de téléphone doit contenir 9 chiffres
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-plus-circle me-2"></i>Créer l'annonce
                                </button>
                                <a href="dashboard-vendeur.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Retour au tableau de bord
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('annonceForm');
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const dropZone = document.getElementById('dropZone');

            // Validation du formulaire
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });

            // Prévisualisation de l'image
            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    if (file.size > 5 * 1024 * 1024) {
                        alert('La taille de l\'image ne doit pas dépasser 5 MB');
                        this.value = '';
                        imagePreview.style.display = 'none';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Drag & Drop
            dropZone.addEventListener('click', () => imageInput.click());
            
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropZone.classList.add('border-primary');
            }

            function unhighlight(e) {
                dropZone.classList.remove('border-primary');
            }

            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const file = dt.files[0];
                imageInput.files = dt.files;
                
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            }

            // Validation en temps réel des champs numériques
            ['prix', 'poids', 'age'].forEach(id => {
                const input = document.getElementById(id);
                input.addEventListener('input', function() {
                    if (this.value < 0) this.value = 0;
                });
            });
        });
    </script>
</body>
</html>
