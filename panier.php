<?php 
$pageTitle = "Mon Panier";
$currentPage = 'panier';
include 'components/header.php';

// Simulation des données de panier (normalement viendraient de la base de données)
$cartItems = [
    [
        'id' => '12345',
        'product_name' => 'Mouton Ladoum',
        'seller' => 'Éleveur Exemple',
        'price' => 250000,
        'quantity' => 1
    ],
    
];

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<div class="cart-page">
    <div class="container">
        <h1 class="page-title">Mon Panier</h1>
        
        <div class="cart-list">
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item">
                    <h2><?php echo $item['product_name']; ?></h2>
                    <p><strong>Vendeur:</strong> <?php echo $item['seller']; ?></p>
                    <p><strong>Prix:</strong> <?php echo number_format($item['price'], 0, ',', ' '); ?> FCFA</p>
                    <p><strong>Quantité:</strong> <?php echo $item['quantity']; ?></p>
                    <p><strong>Sous-total:</strong> <?php echo number_format($item['price'] * $item['quantity'], 0, ',', ' '); ?> FCFA</p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="cart-total">
            <h3>Total: <?php echo number_format($total, 0, ',', ' '); ?> FCFA</h3>
            <form action="paiement.php" method="post">
                <button type="submit" class="btn-primary">Valider le Panier</button>
            </form>
        </div>
    </div>
</div>

<?php include 'components/footer.php'; ?>

<style>
.cart-page {
    background-color: #f9f9f9;
    padding: 20px;
}

.page-title {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
}

.cart-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 30px;
}

.cart-item {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    transition: transform 0.3s;
}

.cart-item:hover {
    transform: translateY(-5px);
}

.cart-item h2 {
    color: #007bff;
    margin-bottom: 10px;
}

.cart-item p {
    color: #555;
    margin: 5px 0;
}

.cart-total {
    text-align: center;
    font-size: 1.2em;
    color: #333;
}

.btn-primary {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-primary:hover {
    background-color: #0056b3;
}
</style> 