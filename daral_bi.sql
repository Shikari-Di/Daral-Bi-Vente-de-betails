-- Création de la base de données
CREATE DATABASE IF NOT EXISTS daral_bi;
USE daral_bi;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    numero VARCHAR(9),
    mot_de_passe VARCHAR(255) NOT NULL,
    type_compte ENUM('client', 'vendeur') NOT NULL DEFAULT 'client',
    nom_entreprise VARCHAR(100),
    adresse_entreprise VARCHAR(255),
    numero_ninea VARCHAR(50),
    remember_token VARCHAR(100),
    token_expiry DATETIME,
    reset_token VARCHAR(100),
    reset_token_expiry DATETIME,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des catégories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL
);

-- Table des races
CREATE TABLE IF NOT EXISTS races (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL
);

-- Table des localisations
CREATE TABLE IF NOT EXISTS localisations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

-- Table des annonces
CREATE TABLE IF NOT EXISTS annonces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    poids INT NOT NULL,
    age INT NOT NULL, -- âge en mois
    image VARCHAR(255) NOT NULL,
    localisation_id INT NOT NULL,
    categorie_id INT NOT NULL,
    race_id INT NOT NULL,
    utilisateur_id INT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (localisation_id) REFERENCES localisations(id),
    FOREIGN KEY (categorie_id) REFERENCES categories(id),
    FOREIGN KEY (race_id) REFERENCES races(id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
);

-- Table des favoris
CREATE TABLE IF NOT EXISTS favoris (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    annonce_id INT NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (annonce_id) REFERENCES annonces(id)
);

-- Table des témoignages
CREATE TABLE IF NOT EXISTS temoignages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    contenu TEXT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
);

-- Table des contacts
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    sujet VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    date_envoi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des blogs
CREATE TABLE IF NOT EXISTS blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    image VARCHAR(255),
    auteur_id INT NOT NULL,
    date_publication TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id)
);

-- Table des FAQ
CREATE TABLE IF NOT EXISTS faq (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    reponse TEXT NOT NULL
);

-- Insertion des données initiales

-- Utilisateur
INSERT INTO utilisateurs (nom, email, numero, mot_de_passe) 
VALUES ('Admin', 'admin@example.com', '770000000', 'password123');

-- Catégories
INSERT INTO categories (nom) 
VALUES ('Moutons'), ('Vaches'), ('Chèvres');

-- Races
INSERT INTO races (nom) 
VALUES ('Ladoum'), ('Bali-Bali'), ('Touabir');

-- Localisations
INSERT INTO localisations (nom) 
VALUES ('Dakar'), ('Thiès'), ('Saint-Louis'), ('Kolda');

-- Annonces
INSERT INTO annonces (titre, description, prix, poids, age, image, localisation_id, categorie_id, race_id, utilisateur_id)
VALUES 
('Mouton Ladoum', 'Mouton de race pure, idéal pour Tabaski.', 250000, 45, 18, 'mouton1.jpg', 1, 1, 1, 1),
('Vache Bali-Bali', 'Vache robuste et en bonne santé.', 500000, 300, 36, 'vache1.jpg', 2, 2, 2, 1),
('Chèvre Touabir', 'Chèvre de race Touabir, parfaite pour l\'élevage.', 150000, 30, 12, 'chevre1.jpg', 3, 3, 3, 1),
('Mouton Bali-Bali', 'Mouton robuste et bien nourri.', 200000, 50, 24, 'mouton2.jpg', 1, 1, 2, 1),
('Vache Ladoum', 'Vache de grande taille, idéale pour la reproduction.', 600000, 350, 48, 'vache2.jpg', 2, 2, 1, 1),
('Chèvre Bali-Bali', 'Chèvre en bonne santé, parfaite pour la production de lait.', 120000, 25, 10, 'chevre2.jpg', 3, 3, 2, 1),
('Mouton Touabir', 'Mouton de race Touabir, prêt pour Tabaski.', 180000, 40, 20, 'mouton3.jpg', 4, 1, 3, 1),
('Vache Touabir', 'Vache robuste et productive.', 550000, 320, 42, 'vache3.jpg', 4, 2, 3, 1),
('Chèvre Ladoum', 'Chèvre de race Ladoum, idéale pour l\'élevage.', 130000, 28, 14, 'chevre3.jpg', 1, 3, 1, 1),
('Mouton Mixte', 'Mouton croisé, robuste et bien nourri.', 220000, 48, 22, 'mouton4.jpg', 2, 1, 2, 1),
('Vache Mixte', 'Vache croisée, idéale pour la production de lait.', 480000, 310, 38, 'vache4.jpg', 3, 2, 2, 1),
('Chèvre Mixte', 'Chèvre croisée, parfaite pour l\'élevage.', 110000, 22, 8, 'chevre4.jpg', 4, 3, 2, 1),
('Mouton Premium', 'Mouton de qualité supérieure, prêt pour Tabaski.', 300000, 55, 26, 'mouton5.jpg', 1, 1, 1, 1),
('Vache Premium', 'Vache de qualité supérieure, idéale pour la reproduction.', 700000, 400, 50, 'vache5.jpg', 2, 2, 1, 1),
('Chèvre Premium', 'Chèvre de qualité supérieure, parfaite pour l\'élevage.', 160000, 35, 16, 'chevre5.jpg', 3, 3, 1, 1);