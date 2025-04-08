<?php
session_start();
$pageTitle = "Mes Commandes";
$currentPage = 'mes-commandes';
include 'components/header.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Vous devez être connecté pour voir vos commandes.";
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

try {
    // Récupérer les commandes de l'utilisateur
    $stmt = $pdo->prepare("
        SELECT c.*, a.titre, a.image, u.nom as vendeur_nom 
        FROM commandes c
        JOIN annonces a ON c.annonce_id = a.id
        JOIN utilisateurs u ON a.utilisateur_id = u.id
        WHERE c.acheteur_id = ?
        ORDER BY c.date_commande DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $commandes = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des commandes : " . $e->getMessage();
}
?>

<div class="commandes-page">
    <div class="container">
        <h1 class="page-title">Mes Commandes</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($commandes)): ?>
            <div class="empty-orders">
                <i class="fas fa-shopping-bag fa-3x"></i>
                <h2>Vous n'avez pas encore de commandes</h2>
                <p>Découvrez nos magnifiques bétails et faites votre première commande.</p>
                <a href="annonces.php" class="btn btn-primary">Voir les annonces</a>
            </div>
        <?php else: ?>
            <div class="orders-list">
                <?php foreach ($commandes as $commande): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <h3>Commande #<?php echo $commande['id']; ?></h3>
                                <p class="order-date">
                                    <i class="far fa-calendar-alt"></i>
                                    <?php echo date('d/m/Y à H:i', strtotime($commande['date_commande'])); ?>
                                </p>
                            </div>
                            <div class="order-status <?php echo strtolower($commande['statut']); ?>">
                                <?php echo $commande['statut']; ?>
                            </div>
                        </div>

                        <div class="order-content">
                            <div class="product-image">
                                <img src="<?php echo htmlspecialchars($commande['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($commande['titre']); ?>">
                            </div>
                            <div class="product-details">
                                <h4><?php echo htmlspecialchars($commande['titre']); ?></h4>
                                <p class="seller">Vendeur: <?php echo htmlspecialchars($commande['vendeur_nom']); ?></p>
                                <p class="price"><?php echo number_format($commande['montant_total'], 0, ',', ' '); ?> FCFA</p>
                            </div>
                            <div class="order-actions">
                                <?php if ($commande['statut'] === 'En attente'): ?>
                                    <form method="POST" action="annuler-commande.php">
                                        <input type="hidden" name="commande_id" value="<?php echo $commande['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                            <i class="fas fa-times"></i> Annuler
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($commande['statut'] === 'Livré'): ?>
                                    <a href="evaluer-commande.php?id=<?php echo $commande['id']; ?>" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-star"></i> Évaluer
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="order-timeline">
                            <div class="timeline-item <?php echo $commande['statut'] === 'En attente' ? 'active' : ''; ?>">
                                <i class="fas fa-clock"></i>
                                <span>En attente</span>
                            </div>
                            <div class="timeline-item <?php echo $commande['statut'] === 'Confirmé' ? 'active' : ''; ?>">
                                <i class="fas fa-check"></i>
                                <span>Confirmé</span>
                            </div>
                            <div class="timeline-item <?php echo $commande['statut'] === 'En livraison' ? 'active' : ''; ?>">
                                <i class="fas fa-truck"></i>
                                <span>En livraison</span>
                            </div>
                            <div class="timeline-item <?php echo $commande['statut'] === 'Livré' ? 'active' : ''; ?>">
                                <i class="fas fa-box"></i>
                                <span>Livré</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.commandes-page {
    padding: 40px 0;
    background-color: #f8f9fa;
}

.page-title {
    margin-bottom: 30px;
    color: #2c3e50;
    text-align: center;
}

.empty-orders {
    text-align: center;
    padding: 50px 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.empty-orders i {
    color: #95a5a6;
    margin-bottom: 20px;
}

.empty-orders h2 {
    color: #2c3e50;
    margin-bottom: 15px;
}

.empty-orders p {
    color: #7f8c8d;
    margin-bottom: 25px;
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.order-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #eee;
}

.order-info h3 {
    color: #2c3e50;
    margin-bottom: 5px;
}

.order-date {
    color: #7f8c8d;
    font-size: 0.9em;
}

.order-date i {
    margin-right: 5px;
}

.order-status {
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.9em;
    font-weight: 600;
}

.order-status.en-attente {
    background-color: #f1c40f;
    color: #fff;
}

.order-status.confirmé {
    background-color: #3498db;
    color: #fff;
}

.order-status.en-livraison {
    background-color: #e67e22;
    color: #fff;
}

.order-status.livré {
    background-color: #2ecc71;
    color: #fff;
}

.order-content {
    display: grid;
    grid-template-columns: 100px 1fr auto;
    gap: 20px;
    align-items: center;
    margin-bottom: 20px;
}

.product-image img {
    width: 100%;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
}

.product-details h4 {
    color: #2c3e50;
    margin-bottom: 5px;
}

.product-details .seller {
    color: #7f8c8d;
    font-size: 0.9em;
    margin-bottom: 5px;
}

.product-details .price {
    color: #e67e22;
    font-weight: bold;
}

.order-timeline {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid #eee;
}

.timeline-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    color: #95a5a6;
    position: relative;
    flex: 1;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 15px;
    right: -50%;
    width: 100%;
    height: 2px;
    background-color: #eee;
    z-index: 1;
}

.timeline-item i {
    background-color: #fff;
    padding: 10px;
    border-radius: 50%;
    border: 2px solid #eee;
    margin-bottom: 5px;
    z-index: 2;
}

.timeline-item span {
    font-size: 0.8em;
}

.timeline-item.active {
    color: #2ecc71;
}

.timeline-item.active i {
    border-color: #2ecc71;
    color: #2ecc71;
}

.btn-sm {
    padding: 5px 15px;
    font-size: 0.9em;
}

.btn-danger {
    background-color: #e74c3c;
    border-color: #e74c3c;
    color: #fff;
}

.btn-danger:hover {
    background-color: #c0392b;
    border-color: #c0392b;
}
</style>

<?php include 'components/footer.php'; ?> 