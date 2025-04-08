<?php 
$pageTitle = "Paiement";
$currentPage = 'paiement';
include 'components/header.php';

// Simulation des données de commande (normalement viendraient de la base de données)
$order = [
    'id' => '12345',
    'product_name' => 'Mouton Ladoum',
    'seller' => 'Éleveur Example',
    'price' => 250000,
    'delivery_fee' => 15000,
    'total' => 265000,
    'buyer' => 'John Doe',
    'delivery_address' => 'Dakar, Sénégal'
];
?>

<div class="payment-page">
    <div class="container">
        <h1 class="page-title">Paiement sécurisé</h1>
        
        <!-- Résumé de la commande -->
        <div class="order-summary">
            <h2>Résumé de votre commande</h2>
            <div class="summary-details">
                <div class="summary-item">
                    <span>Produit:</span>
                    <span><?php echo $order['product_name']; ?></span>
                </div>
                <div class="summary-item">
                    <span>Vendeur:</span>
                    <span><?php echo $order['seller']; ?></span>
                </div>
                <div class="summary-item">
                    <span>Prix du produit:</span>
                    <span><?php echo number_format($order['price'], 0, ',', ' '); ?> FCFA</span>
                </div>
                <div class="summary-item">
                    <span>Frais de livraison:</span>
                    <span><?php echo number_format($order['delivery_fee'], 0, ',', ' '); ?> FCFA</span>
                </div>
                <div class="summary-item total">
                    <span>Total à payer:</span>
                    <span><?php echo number_format($order['total'], 0, ',', ' '); ?> FCFA</span>
                </div>
            </div>
        </div>

        <!-- Options de paiement -->
        <div class="payment-options">
            <h2>Choisissez votre mode de paiement</h2>
            
            <div class="payment-methods">
                <!-- Paiement par carte -->
                <div class="payment-method" data-method="card">
                    <div class="method-header">
                        <input type="radio" name="payment_method" id="card" value="card">
                        <label for="card">
                            <i class="fas fa-credit-card"></i>
                            Carte bancaire
                        </label>
                    </div>
                    <div class="method-content">
                        <form id="card-payment-form" class="payment-form">
                            <div class="form-group">
                                <label for="card_number">Numéro de carte</label>
                                <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="expiry">Date d'expiration</label>
                                    <input type="text" id="expiry" name="expiry" placeholder="MM/AA" required>
                                </div>
                                <div class="form-group">
                                    <label for="cvv">CVV</label>
                                    <input type="text" id="cvv" name="cvv" placeholder="123" required>
                                </div>
                            </div>
                            <button type="submit" class="btn-primary">Payer maintenant</button>
                        </form>
                    </div>
                </div>

                <!-- Paiement mobile -->
                <div class="payment-method" data-method="mobile">
                    <div class="method-header">
                        <input type="radio" name="payment_method" id="mobile" value="mobile">
                        <label for="mobile">
                            <i class="fas fa-mobile-alt"></i>
                            Orange Money / Wave / Free Money
                        </label>
                    </div>
                    <div class="method-content">
                        <div class="mobile-operators">
                            <button class="operator-btn active" data-operator="orange">
                                <img src="img/orange_money.jpeg" alt="Orange Money">
                                Orange Money
                            </button>
                            <button class="operator-btn" data-operator="wave">
                                <img src="img/wave.png" alt="Wave">
                                Wave
                            </button>
                            <button class="operator-btn" data-operator="free">
                                <img src="img/free_money.png" alt="Free Money">
                                Free Money
                            </button>
                        </div>
                        <form id="mobile-payment-form" class="payment-form">
                            <div class="form-group">
                                <label for="phone">Numéro de téléphone</label>
                                <input type="tel" id="phone" name="phone" placeholder="77 123 45 67" required>
                            </div>
                            <button type="submit" class="btn-primary">Continuer</button>
                        </form>
                    </div>
                </div>

                <!-- Paiement à la livraison -->
                <div class="payment-method" data-method="cash">
                    <div class="method-header">
                        <input type="radio" name="payment_method" id="cash" value="cash">
                        <label for="cash">
                            <i class="fas fa-money-bill-wave"></i>
                            Paiement à la livraison
                        </label>
                    </div>
                    <div class="method-content">
                        <div class="cash-payment-info">
                            <div class="info-box warning">
                                <i class="fas fa-info-circle"></i>
                                <p>Un acompte de 30% (<?php echo number_format($order['total'] * 0.3, 0, ',', ' '); ?> FCFA) est requis pour confirmer la commande.</p>
                            </div>
                            <div class="payment-details">
                                <p><strong>Acompte à payer:</strong> <?php echo number_format($order['total'] * 0.3, 0, ',', ' '); ?> FCFA</p>
                                <p><strong>Reste à payer à la livraison:</strong> <?php echo number_format($order['total'] * 0.7, 0, ',', ' '); ?> FCFA</p>
                            </div>
                        </div>
                        <button type="button" class="btn-primary" onclick="confirmCashPayment()">Confirmer la commande</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations de sécurité -->
        <div class="security-info">
            <div class="security-badge">
                <i class="fas fa-shield-alt"></i>
                <p>Paiement 100% sécurisé</p>
            </div>
            <div class="security-badge">
                <i class="fas fa-lock"></i>
                <p>Données cryptées</p>
            </div>
            <div class="security-badge">
                <i class="fas fa-check-circle"></i>
                <p>Transaction garantie</p>
            </div>
        </div>
    </div>
</div>

<script src="js/payment.js"></script>

<?php include 'components/footer.php'; ?> 