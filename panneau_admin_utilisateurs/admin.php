<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Daral_bi</title>
    <style>
        :root {
            --primary: #2ecc71;
            --secondary: #3498db;
            --dark: #2c3e50;
            --light: #f8f9fa;
            --border: #dee2e6;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light);
            margin: 0;
            padding: 0;
            color: #333;
        }
        
        .admin-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .admin-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border);
        }
        
        .admin-header h1 {
            color: var(--dark);
            margin-bottom: 10px;
        }
        
        .admin-menu {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        @media (max-width: 600px) {
            .admin-menu {
                grid-template-columns: 1fr;
            }
        }
        
        .admin-card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .admin-card i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .admin-card h2 {
            color: var(--dark);
            margin-bottom: 15px;
        }
        
        .admin-card p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .admin-btn {
            display: inline-block;
            background: var(--secondary);
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            transition: background 0.3s;
        }
        
        .admin-btn:hover {
            background: var(--primary);
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <h1>Panneau d'Administration</h1>
            <p>Gestion des annonces et utilisateurs</p>
        </header>
        
        <div class="admin-menu">
            <div class="admin-card">
                <i class="fas fa-users"></i>
                <h2>Utilisateurs</h2>
                <p>Gérer les comptes utilisateurs et leurs permissions</p>
                <a href="utilisateurs.php" class="admin-btn">Accéder</a>
            </div>
            
            <div class="admin-card">
                <i class="fas fa-list"></i>
                <h2>Annonces</h2>
                <p>Modérer et gérer toutes les annonces publiées</p>
                <a href="administrateur.php" class="admin-btn">Accéder</a>
            </div>
        </div>
    </div>
</body>
</html>