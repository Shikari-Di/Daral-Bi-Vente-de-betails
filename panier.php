<?php
session_start();
$pageTitle = "Mon Panier";
$currentPage = 'panier';
include 'components/header.php';

// Initialiser le panier s'il n'existe pas
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Traitement des actions (ajouter/supprimer/modifier quantité)
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'supprimer':
            if (isset($_POST['id'])) {
                unset($_SESSION['panier'][$_POST['id']]);
            }
            break;
        case 'modifier_quantite':
            if (isset($_POST['id']) && isset($_POST['quantite'])) {
                $_SESSION['panier'][$_POST['id']]['quantite'] = max(1, (int)$_POST['quantite']);
            }
            break;
    }
}

// Calculer le total
$total = 0;
foreach ($_SESSION['panier'] as $item) {
    $total += $item['prix'] * $item['quantite'];
}
?>

<div class="panier-page">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-shopping-cart"></i>
                Mon Panier
            </h1>
            <p class="items-count">
                <?php echo count($_SESSION['panier']); ?> article(s)
            </p>
        </div>

        <?php if (empty($_SESSION['panier'])): ?>
            <div class="empty-cart">
                <div class="empty-cart-content">
                    <i class="fas fa-shopping-cart fa-4x"></i>
                    <h2>Votre panier est vide</h2>
                    <p>Découvrez nos magnifiques bétails et commencez vos achats.</p>
                    <a href="annonces.php" class="btn-discover">
                        <i class="fas fa-search"></i>
                        Voir les annonces
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="cart-content">
                <div class="cart-items">
                    <?php foreach ($_SESSION['panier'] as $id => $item): ?>
                        <div class="cart-item">
                            <div class="item-image">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['titre']); ?>">
                            </div>
                            <div class="item-details">
                                <h3><?php echo htmlspecialchars($item['titre']); ?></h3>
                                <p class="seller">
                                    <i class="fas fa-store"></i>
                                    Vendeur: <?php echo htmlspecialchars($item['vendeur']); ?>
                                </p>
                                <p class="price">
                                    <i class="fas fa-tag"></i>
                                    <?php echo number_format($item['prix'], 0, ',', ' '); ?> FCFA
                                </p>
                            </div>
                            <div class="item-quantity">
                                <form method="POST" class="quantity-form">
                                    <input type="hidden" name="action" value="modifier_quantite">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <div class="quantity-controls">
                                        <button type="button" class="quantity-btn minus" onclick="updateQuantity(this.form, -1)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" name="quantite" value="<?php echo $item['quantite']; ?>" 
                                               min="1" max="10" onchange="this.form.submit()" class="quantity-input">
                                        <button type="button" class="quantity-btn plus" onclick="updateQuantity(this.form, 1)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="item-total">
                                <?php echo number_format($item['prix'] * $item['quantite'], 0, ',', ' '); ?> FCFA
                            </div>
                            <div class="item-actions">
                                <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir retirer cet article ?');">
                                    <input type="hidden" name="action" value="supprimer">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <button type="submit" class="remove-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <h3>Résumé de la commande</h3>
                    <div class="summary-details">
                        <div class="summary-item">
                            <span>Sous-total</span>
                            <span><?php echo number_format($total, 0, ',', ' '); ?> FCFA</span>
                        </div>
                        <div class="summary-item">
                            <span>Frais de livraison</span>
                            <span>À calculer</span>
                        </div>
                        <div class="summary-item total">
                            <span>Total</span>
                            <span><?php echo number_format($total, 0, ',', ' '); ?> FCFA</span>
                        </div>
                    </div>
                    <div class="summary-actions">
                        <a href="paiement.php" class="checkout-btn">
                            <i class="fas fa-lock"></i>
                            Procéder au paiement
                        </a>
                        <a href="annonces.php" class="continue-shopping">
                            <i class="fas fa-arrow-left"></i>
                            Continuer mes achats
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.panier-page {
    padding: 40px 0;
    background-color: #f8f9fa;
    min-height: calc(100vh - 200px);
}

.page-header {
    text-align: center;
    margin-bottom: 30px;
}

.page-title {
    color: #2c3e50;
    font-size: 2.5em;
    margin-bottom: 10px;
}

.page-title i {
    margin-right: 10px;
    color: #3498db;
}

.items-count {
    color: #7f8c8d;
    font-size: 1.1em;
}

.empty-cart {
    background: #fff;
    border-radius: 15px;
    padding: 50px 20px;
    text-align: center;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.empty-cart-content {
    max-width: 400px;
    margin: 0 auto;
}

.empty-cart i {
    color: #95a5a6;
    margin-bottom: 20px;
}

.empty-cart h2 {
    color: #2c3e50;
    margin-bottom: 15px;
    font-size: 1.8em;
}

.empty-cart p {
    color: #7f8c8d;
    margin-bottom: 25px;
    font-size: 1.1em;
}

.btn-discover {
    display: inline-block;
    padding: 15px 30px;
    background-color: #3498db;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-discover:hover {
    background-color: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(52,152,219,0.2);
}

.btn-discover i {
    margin-right: 10px;
}

.cart-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
}

.cart-items {
    background: #fff;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.cart-item {
    display: grid;
    grid-template-columns: 100px 2fr 120px 120px 50px;
    gap: 20px;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #eee;
}

.cart-item:last-child {
    border-bottom: none;
}

.item-image img {
    width: 100%;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

.item-details h3 {
    color: #2c3e50;
    margin-bottom: 5px;
    font-size: 1.2em;
}

.item-details .seller,
.item-details .price {
    color: #7f8c8d;
    font-size: 0.9em;
    margin-bottom: 5px;
}

.item-details .price {
    color: #e67e22;
    font-weight: bold;
}

.item-details i {
    margin-right: 5px;
    width: 16px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    border: 2px solid #eee;
    border-radius: 8px;
    overflow: hidden;
}

.quantity-btn {
    background: #f8f9fa;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.quantity-btn:hover {
    background: #e9ecef;
}

.quantity-input {
    width: 40px;
    border: none;
    text-align: center;
    font-size: 1em;
    padding: 8px 0;
    -moz-appearance: textfield;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.item-total {
    font-weight: bold;
    color: #2c3e50;
}

.remove-btn {
    background: none;
    border: none;
    color: #e74c3c;
    cursor: pointer;
    padding: 5px;
    transition: all 0.3s ease;
}

.remove-btn:hover {
    color: #c0392b;
    transform: scale(1.1);
}

.cart-summary {
    background: #fff;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    position: sticky;
    top: 20px;
}

.cart-summary h3 {
    color: #2c3e50;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #eee;
}

.summary-details {
    margin-bottom: 25px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    color: #2c3e50;
}

.summary-item.total {
    font-size: 1.2em;
    font-weight: bold;
    border-top: 2px solid #eee;
    padding-top: 15px;
    margin-top: 15px;
}

.summary-actions {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.checkout-btn {
    display: block;
    padding: 15px 25px;
    background-color: #2ecc71;
    color: #fff;
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.checkout-btn:hover {
    background-color: #27ae60;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(46,204,113,0.2);
}

.continue-shopping {
    display: block;
    padding: 15px 25px;
    background-color: #fff;
    color: #3498db;
    border: 2px solid #3498db;
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.continue-shopping:hover {
    background-color: #3498db;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(52,152,219,0.2);
}

.checkout-btn i,
.continue-shopping i {
    margin-right: 10px;
}

@media (max-width: 992px) {
    .cart-content {
        grid-template-columns: 1fr;
    }
    
    .cart-summary {
        position: static;
    }
}

@media (max-width: 768px) {
    .cart-item {
        grid-template-columns: 80px 1fr;
        grid-template-areas: 
            "image details"
            "quantity total"
            "actions actions";
        gap: 15px;
    }

    .item-image {
        grid-area: image;
    }

    .item-details {
        grid-area: details;
    }

    .item-quantity {
        grid-area: quantity;
    }

    .item-total {
        grid-area: total;
        text-align: right;
    }

    .item-actions {
        grid-area: actions;
        text-align: right;
    }
}
</style>

<script>
function updateQuantity(form, change) {
    const input = form.querySelector('input[name="quantite"]');
    const currentValue = parseInt(input.value);
    const newValue = Math.max(1, Math.min(10, currentValue + change));
    
    if (newValue !== currentValue) {
        input.value = newValue;
        form.submit();
    }
}
</script>

<?php include 'components/footer.php'; ?> 