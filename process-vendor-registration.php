<?php
session_start();
require_once 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = htmlspecialchars($_POST['nom']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $telephone = htmlspecialchars($_POST['telephone']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $nom_entreprise = isset($_POST['nom_entreprise']) ? htmlspecialchars($_POST['nom_entreprise']) : null;
    $type_elevage = htmlspecialchars($_POST['type_elevage']);
    $experience = intval($_POST['experience']);

    try {
        // Vérification si l'email existe déjà
        $stmt = $conn->prepare("SELECT id FROM vendeurs WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Cette adresse email est déjà utilisée.";
            header("Location: devenir-vendeur.php");
            exit();
        }

        // Traitement des fichiers
        $upload_dir = "uploads/documents/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Traitement de la CNI
        $cni_path = null;
        if (isset($_FILES['cni']) && $_FILES['cni']['error'] == 0) {
            $cni_name = uniqid() . '_' . basename($_FILES['cni']['name']);
            $cni_path = $upload_dir . $cni_name;
            move_uploaded_file($_FILES['cni']['tmp_name'], $cni_path);
        }

        // Traitement du certificat vétérinaire
        $certificat_path = null;
        if (isset($_FILES['certificat']) && $_FILES['certificat']['error'] == 0) {
            $certificat_name = uniqid() . '_' . basename($_FILES['certificat']['name']);
            $certificat_path = $upload_dir . $certificat_name;
            move_uploaded_file($_FILES['certificat']['tmp_name'], $certificat_path);
        }

        // Insertion dans la base de données
        $stmt = $conn->prepare("
            INSERT INTO vendeurs (nom, email, telephone, adresse, nom_entreprise, type_elevage, experience, cni_path, certificat_path, status, date_inscription)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'en_attente', NOW())
        ");

        $stmt->execute([
            $nom,
            $email,
            $telephone,
            $adresse,
            $nom_entreprise,
            $type_elevage,
            $experience,
            $cni_path,
            $certificat_path
        ]);

        $_SESSION['success'] = "Votre demande d'inscription a été enregistrée avec succès. Nous l'examinerons dans les plus brefs délais.";
        header("Location: devenir-vendeur.php");
        exit();

    } catch(PDOException $e) {
        $_SESSION['error'] = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
        error_log("Erreur d'inscription vendeur : " . $e->getMessage());
        header("Location: devenir-vendeur.php");
        exit();
    }
} else {
    header("Location: devenir-vendeur.php");
    exit();
}
?> 