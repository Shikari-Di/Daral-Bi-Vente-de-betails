<?php 
$pageTitle = "Contact";
$currentPage = 'contact';
include 'components/header.php';
?>

<section class="contact-page">
    <div class="container">
        <h1 class="page-title">Contactez-nous</h1>
        
        <div class="contact-content">
            <div class="contact-info">
                <div class="info-card">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Notre adresse</h3>
                    <p>123 Rue de la Liberté<br>Dakar, Sénégal</p>
                </div>
                
                <div class="info-card">
                    <i class="fas fa-phone"></i>
                    <h3>Téléphone</h3>
                    <p>+221 77 123 45 67<br>+221 33 123 45 67</p>
                </div>
                
                <div class="info-card">
                    <i class="fas fa-envelope"></i>
                    <h3>Email</h3>
                    <p>contact@daaralbi.sn<br>support@daaralbi.sn</p>
                </div>
            </div>
            <br>
            <form class="contact-form" action="contact.php" method="POST">
                <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" name="name" required class="form-input">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required class="form-input">
                </div>
                
                <div class="form-group">
                    <label for="subject">Sujet</label>
                    <input type="text" id="subject" name="subject" required class="form-input">
                </div>
                
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" required class="form-input" rows="5"></textarea>
                </div>
                
                <button type="submit" class="submit-button">Envoyer le message</button>
            </form>
        </div>
    </div>
</section>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    $host = 'localhost';
    $dbname = 'daral_bi';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO contacts (nom, email, sujet, message) VALUES (:name, :email, :subject, :message)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':subject' => $subject,
            ':message' => $message
        ]);

        echo "<p class='flash-message success'>Données enregistrées avec succès !</p>";


    } catch (PDOException $e) {
        echo "<p class='flash-message error'>Erreur : " . $e->getMessage() . "</p>";
    }
}
?>


<?php include 'components/footer.php'; ?> 