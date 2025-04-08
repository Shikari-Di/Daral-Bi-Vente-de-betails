<?php
// Connexion à la base de données
require_once('db.php'); // Utilisation de require_once au lieu de include pour s'assurer que le fichier est présent

// Initialisation des variables pour conserver les valeurs en cas d'erreur
$formData = [
    'nom' => '',
    'email' => '',
    'numero' => '',
    'type_compte' => 'client',
    'nom_entreprise' => '',
    'adresse_entreprise' => '',
    'numero_ninea' => ''
];

$error = '';
$success = '';

// Traitement du formulaire d'ajout d'utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nettoyage des données
    $formData = [
        'nom' => htmlspecialchars(trim($_POST['nom'])),
        'email' => filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL),
        'numero' => htmlspecialchars(trim($_POST['numero'])),
        'type_compte' => $_POST['type_compte'],
        'nom_entreprise' => htmlspecialchars(trim($_POST['nom_entreprise'])),
        'adresse_entreprise' => htmlspecialchars(trim($_POST['adresse_entreprise'])),
        'numero_ninea' => htmlspecialchars(trim($_POST['numero_ninea']))
    ];
    
    // Validation des données
    if (empty($formData['nom'])) {
        $error = "Le nom est obligatoire";
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "L'email n'est pas valide";
    } elseif (empty($_POST['mot_de_passe'])) {
        $error = "Le mot de passe est obligatoire";
    } elseif (strlen($_POST['mot_de_passe']) < 8) {
        $error = "Le mot de passe doit contenir au moins 8 caractères";
    } else {
        // Hashage du mot de passe
        $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT);
        
        // Préparation de la requête avec des paramètres pour éviter les injections SQL
        $sql = "INSERT INTO utilisateurs (nom, email, numero, mot_de_passe, type_compte, nom_entreprise, adresse_entreprise, numero_ninea)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssssss", 
                $formData['nom'], 
                $formData['email'], 
                $formData['numero'], 
                $mot_de_passe, 
                $formData['type_compte'], 
                $formData['nom_entreprise'], 
                $formData['adresse_entreprise'], 
                $formData['numero_ninea']);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = "Utilisateur créé avec succès!";
                // Réinitialisation des données du formulaire après succès
                $formData = [
                    'nom' => '',
                    'email' => '',
                    'numero' => '',
                    'type_compte' => 'client',
                    'nom_entreprise' => '',
                    'adresse_entreprise' => '',
                    'numero_ninea' => ''
                ];
            } else {
                $error = "Erreur lors de la création de l'utilisateur: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "Erreur de préparation de la requête: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création d'utilisateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .form-container {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], 
        input[type="email"], 
        input[type="password"], 
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="text"]:focus, 
        input[type="email"]:focus, 
        input[type="password"]:focus, 
        select:focus {
            border-color: #4CAF50;
            outline: none;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .alert-error {
            background-color: #f2dede;
            color: #a94442;
        }
        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #4CAF50;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .required:after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Création d'utilisateur</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label for="nom" class="required">Nom:</label>
                    <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($formData['nom']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="required">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($formData['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="numero">Numéro de téléphone:</label>
                    <input type="text" name="numero" id="numero" value="<?php echo htmlspecialchars($formData['numero']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="mot_de_passe" class="required">Mot de passe:</label>
                    <input type="password" name="mot_de_passe" id="mot_de_passe" required minlength="8">
                    <small>Minimum 8 caractères</small>
                </div>
                
                <div class="form-group">
                    <label for="type_compte" class="required">Type de compte:</label>
                    <select name="type_compte" id="type_compte" required>
                        <option value="client" <?php echo $formData['type_compte'] === 'client' ? 'selected' : ''; ?>>Client</option>
                        <option value="vendeur" <?php echo $formData['type_compte'] === 'vendeur' ? 'selected' : ''; ?>>Vendeur</option>
                    </select>
                </div>
                
                <div class="form-group" id="entreprise-fields">
                    <label for="nom_entreprise">Nom de l'entreprise:</label>
                    <input type="text" name="nom_entreprise" id="nom_entreprise" value="<?php echo htmlspecialchars($formData['nom_entreprise']); ?>">
                    
                    <label for="adresse_entreprise">Adresse de l'entreprise:</label>
                    <input type="text" name="adresse_entreprise" id="adresse_entreprise" value="<?php echo htmlspecialchars($formData['adresse_entreprise']); ?>">
                    
                    <label for="numero_ninea">Numéro NINEA:</label>
                    <input type="text" name="numero_ninea" id="numero_ninea" value="<?php echo htmlspecialchars($formData['numero_ninea']); ?>">
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn">Créer l'utilisateur</button>
                </div>
            </form>
            
            <a href="utilisateurs.php" class="back-link">⬅ Retour à la liste des utilisateurs</a>
        </div>
    </div>

    <script>
        // Afficher/masquer les champs entreprise selon le type de compte
        document.getElementById('type_compte').addEventListener('change', function() {
            const entrepriseFields = document.getElementById('entreprise-fields');
            if (this.value === 'vendeur') {
                entrepriseFields.style.display = 'block';
            } else {
                entrepriseFields.style.display = 'none';
            }
        });

        // Initialiser l'affichage au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            const typeCompte = document.getElementById('type_compte').value;
            const entrepriseFields = document.getElementById('entreprise-fields');
            entrepriseFields.style.display = typeCompte === 'vendeur' ? 'block' : 'none';
        });
    </script>
</body>
</html>