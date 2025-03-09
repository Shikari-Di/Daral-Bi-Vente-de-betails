<?php 
$pageTitle = "Blog";
$currentPage = 'blog';
include 'components/header.php';
?>

<section class="blog-page">
    <div class="container">
        <h1>Actualités et conseils</h1>
        
        <div class="blog-grid">
            <article class="blog-post">
                <img src="path/to/image.jpg" alt="Titre de l'article">
                <div class="post-content">
                    <h2>Titre de l'article</h2>
                    <p class="post-meta">Par <span>Auteur</span> le <time>Date</time></p>
                    <p class="post-excerpt">Résumé de l'article...</p>
                    <a href="#" class="read-more">Lire la suite</a>
                </div>
            </article>
        </div>
    </div>
</section> 