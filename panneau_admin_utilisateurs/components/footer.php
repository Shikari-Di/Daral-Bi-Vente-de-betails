    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>footer</title>
    </head>
    <body>
        <link rel="stylesheet" href="css/style.css">
    </body>
    </html>
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <img src="assets/images/logo-white.png" alt="Daaral Bi" class="footer-logo">
                    <p class="footer-description">
                        Daaral Bi est votre marketplace de confiance pour l'achat et la vente de bétail au Sénégal. 
                        Nous connectons éleveurs et acheteurs dans un environnement sécurisé et professionnel.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="footer-column">
                    <h4>Liens rapides</h4>
                    <ul>
                        <li><a href="about.php"><i class="fas fa-chevron-right"></i>À propos</a></li>
                        <li><a href="annonces.php"><i class="fas fa-chevron-right"></i>Annonces</a></li>
                        <li><a href="blog.php"><i class="fas fa-chevron-right"></i>Blog</a></li>
                        <li><a href="contact.php"><i class="fas fa-chevron-right"></i>Contact</a></li>
                        <li><a href="faq.php"><i class="fas fa-chevron-right"></i>FAQ</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h4>Contact</h4>
                    <div class="footer-contact">
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 Rue Exemple, Dakar, Sénégal</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>+221 77 123 45 67</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>contact@daaralbi.sn</span>
                        </div>
                    </div>
                </div>

                <div class="footer-column">
                    <h4>Newsletter</h4>
                    <p class="footer-description">Inscrivez-vous pour recevoir nos dernières actualités et offres.</p>
                    <form class="newsletter-form">
                        <div class="newsletter-input">
                            <input type="email" placeholder="Votre email">
                            <button type="submit"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="footer-bottom">
                <p>
                    © <?php echo date('Y'); ?> Daaral Bi. Tous droits réservés. <br>
                    Développé par 
                    <span class="developer">Mohamed Rassoul Diagne</span>, 
                    <span class="developer">Ousmane Faye</span>, 
                    <span class="developer">Amar Wade</span>, 
                    <span class="developer">Adjaratou Ngoné Diaba Fall</span>, 
                    <span class="developer">Coumba Dieng Diallo</span> et 
                    <span class="developer">Maïmouna Diallo</span>
                </p>
            </div>
        </div>
    </footer>
</body>
</html> 