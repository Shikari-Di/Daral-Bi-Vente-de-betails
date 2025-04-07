<?php 
$pageTitle = "Devenir Vendeur";
$currentPage = 'devenir-vendeur';
include 'components/header.php';
?>

<section class="vendor-registration">
    <div class="container">
        <h1 class="section-title">Devenir Vendeur sur Daaral Bi</h1>
        <p class="section-description">Rejoignez notre communauté de vendeurs et développez votre activité d'élevage en ligne.</p>

        <div class="registration-container">
            <form action="process-vendor-registration.php" method="POST" class="vendor-form" enctype="multipart/form-data">
                <!-- Informations personnelles -->
                <div class="form-section">
                    <h2>Informations Personnelles</h2>
                    <div class="form-group">
                        <label for="nom">Nom complet *</label>
                        <input type="text" id="nom" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="telephone">Téléphone *</label>
                        <input type="tel" id="telephone" name="telephone" required>
                    </div>
                    <div class="form-group">
                        <label for="adresse">Adresse *</label>
                        <input type="text" id="adresse" name="adresse" required>
                    </div>
                </div>

                <!-- Informations professionnelles -->
                <div class="form-section">
                    <h2>Informations Professionnelles</h2>
                    <div class="form-group">
                        <label for="nom_entreprise">Nom de l'entreprise (optionnel)</label>
                        <input type="text" id="nom_entreprise" name="nom_entreprise">
                    </div>
                    <div class="form-group">
                        <label for="type_elevage">Type d'élevage *</label>
                        <select id="type_elevage" name="type_elevage" required>
                            <option value="">Sélectionnez un type</option>
                            <option value="bovins">Bovins</option>
                            <option value="ovins">Ovins</option>
                            <option value="caprins">Caprins</option>
                            <option value="mixte">Mixte</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="experience">Années d'expérience *</label>
                        <input type="number" id="experience" name="experience" min="0" required>
                    </div>
                </div>

                <!-- Documents -->
                <div class="form-section">
                    <h2>Documents Requis</h2>
                    <div class="form-group">
                        <label for="cni">Copie de la CNI *</label>
                        <input type="file" id="cni" name="cni" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                    <div class="form-group">
                        <label for="certificat">Certificat vétérinaire (si disponible)</label>
                        <input type="file" id="certificat" name="certificat" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>

                <!-- Conditions -->
                <div class="form-section">
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="conditions" name="conditions" required>
                        <label for="conditions">J'accepte les conditions d'utilisation et la politique de confidentialité *</label>
                    </div>
                </div>

                <button type="submit" class="button button-primary">Soumettre ma candidature</button>
            </form>
        </div>
    </div>
</section>

<style>
.vendor-registration {
    padding: 4rem 0;
    background-color: #f8f9fa;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 1rem;
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    color: var(--text-color);
    margin-bottom: 1rem;
}

.section-description {
    text-align: center;
    color: #666;
    margin-bottom: 3rem;
}

.registration-container {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #eee;
}

.form-section:last-child {
    border-bottom: none;
}

.form-section h2 {
    font-size: 1.5rem;
    color: var(--text-color);
    margin-bottom: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-color);
    font-weight: 500;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="tel"],
.form-group input[type="number"],
.form-group select {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
}

.form-group input[type="file"] {
    width: 100%;
    padding: 0.5rem 0;
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.checkbox-group input[type="checkbox"] {
    width: 20px;
    height: 20px;
}

.checkbox-group label {
    margin-bottom: 0;
    font-size: 0.9rem;
}

button[type="submit"] {
    width: 100%;
    padding: 1rem;
    font-size: 1.1rem;
    margin-top: 1rem;
}

@media (max-width: 768px) {
    .section-title {
        font-size: 2rem;
    }

    .registration-container {
        padding: 1.5rem;
    }
}
</style>

<?php include 'components/footer.php'; ?> 