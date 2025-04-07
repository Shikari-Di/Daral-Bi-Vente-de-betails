<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daraal Bi - Marketplace de bétail</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <a href="index.php" class="logo">
                <img src="assets/images/logo.png" alt="Daraal Bi Logo" class="logo-img">
            </a>

            <div class="search-bar">
                <form action="recherche.php" method="GET">
                    <input type="text" name="query" placeholder="Rechercher un animal..." class="search-input">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <button class="menu-toggle">
                <i class="fas fa-bars"></i>
            </button>

            <nav class="nav-menu">
                <ul class="nav-list">
                    <li><a href="index.php" class="nav-link <?php echo ($currentPage === 'home') ? 'active' : ''; ?>">Accueil</a></li>
                    <li class="dropdown">
                        <a href="#" class="nav-link dropdown-toggle <?php echo (in_array($currentPage, ['about', 'blog', 'faq'])) ? 'active' : ''; ?>">
                            Découvrir <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="about.php" class="nav-link <?php echo ($currentPage === 'about') ? 'active' : ''; ?>">À propos</a></li>
                            <li><a href="blog.php" class="nav-link <?php echo ($currentPage === 'blog') ? 'active' : ''; ?>">Blog</a></li>
                            <li><a href="faq.php" class="nav-link <?php echo ($currentPage === 'faq') ? 'active' : ''; ?>">FAQ</a></li>
                        </ul>
                    </li>
                    <li><a href="annonces.php" class="nav-link <?php echo ($currentPage === 'annonces') ? 'active' : ''; ?>">Annonces</a></li>
                    <li><a href="temoignages.php" class="nav-link <?php echo ($currentPage === 'temoignages') ? 'active' : ''; ?>">Témoignages</a></li>
                    <li><a href="contact.php" class="nav-link <?php echo ($currentPage === 'contact') ? 'active' : ''; ?>">Contact</a></li>
                    <li><a href="login.php" class="nav-link connect-button">Connexion</a></li>
                </ul>

            </nav>
        </div>
    </header>

</body>
</html>