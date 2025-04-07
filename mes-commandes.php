<?php 
$pageTitle = "Mes Commandes";
$currentPage = 'mes-commandes';
include 'components/header.php';

// Simulation des données de commandes (normalement viendraient de la base de données)
$orders = [
    [
        'id' => '12345',
        'product_name' => 'Mouton Ladoum',
        'seller' => 'Éleveur Exemple',
        'price' => 250000,
        'status' => 'Livraison en cours'
    ],
    
];
?>

<div class="orders-page">
    <div class="container">
        <h1 class="page-title">Mes Commandes</h1>
        
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <h2><?php echo $order['product_name']; ?></h2>
                    <p><strong>Vendeur:</strong> <?php echo $order['seller']; ?></p>
                    <p><strong>Prix:</strong> <?php echo number_format($order['price'], 0, ',', ' '); ?> FCFA</p>
                    <p><strong>Statut:</strong> <?php echo $order['status']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include 'components/footer.php'; ?>

<style>
.orders-page {
    background-color: #f9f9f9;
    padding: 20px;
}

.page-title {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
}

.orders-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.order-card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    width: 300px;
    transition: transform 0.3s;
}

.order-card:hover {
    transform: translateY(-5px);
}

.order-card h2 {
    color: #007bff;
    margin-bottom: 10px;
}

.order-card p {
    color: #555;
    margin: 5px 0;
}
</style> 