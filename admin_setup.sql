-- Création de la table des administrateurs
CREATE TABLE IF NOT EXISTS administrateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    security_code VARCHAR(50) NOT NULL
);

-- Insertion d'un administrateur par défaut
-- Mot de passe : admin123
-- Code de sécurité : 123456
INSERT INTO administrateurs (username, password, security_code)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123456'); 