document.addEventListener('DOMContentLoaded', function() {
    // Gestion de l'affichage des méthodes de paiement
    const paymentMethods = document.querySelectorAll('.payment-method');
    paymentMethods.forEach(method => {
        const radio = method.querySelector('input[type="radio"]');
        const content = method.querySelector('.method-content');
        
        radio.addEventListener('change', function() {
            // Cacher tous les contenus
            document.querySelectorAll('.method-content').forEach(content => {
                content.style.display = 'none';
            });
            
            // Afficher le contenu de la méthode sélectionnée
            if (this.checked) {
                content.style.display = 'block';
            }
        });
    });

    // Gestion des opérateurs de paiement mobile
    const operatorBtns = document.querySelectorAll('.operator-btn');
    operatorBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            operatorBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Validation du formulaire de carte bancaire
    const cardForm = document.getElementById('card-payment-form');
    if (cardForm) {
        cardForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validation basique
            const cardNumber = document.getElementById('card_number').value;
            const expiry = document.getElementById('expiry').value;
            const cvv = document.getElementById('cvv').value;

            if (!validateCardNumber(cardNumber)) {
                showError('Numéro de carte invalide');
                return;
            }

            if (!validateExpiry(expiry)) {
                showError('Date d\'expiration invalide');
                return;
            }

            if (!validateCVV(cvv)) {
                showError('CVV invalide');
                return;
            }

            // Simulation de traitement de paiement
            processPayment('card');
        });
    }

    // Validation du formulaire de paiement mobile
    const mobileForm = document.getElementById('mobile-payment-form');
    if (mobileForm) {
        mobileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const phone = document.getElementById('phone').value;
            if (!validatePhone(phone)) {
                showError('Numéro de téléphone invalide');
                return;
            }

            // Simulation d'envoi de code OTP
            showLoadingOverlay('Envoi du code de vérification...');
            setTimeout(() => {
                hideLoadingOverlay();
                showOTPModal();
            }, 1500);
        });
    }
});

// Fonctions de validation
function validateCardNumber(number) {
    return number.replace(/\s/g, '').length === 16;
}

function validateExpiry(expiry) {
    return /^\d{2}\/\d{2}$/.test(expiry);
}

function validateCVV(cvv) {
    return /^\d{3}$/.test(cvv);
}

function validatePhone(phone) {
    return /^7[0-9]{8}$/.test(phone.replace(/\s/g, ''));
}

// Fonctions d'interface utilisateur
function showLoadingOverlay(message = 'Traitement en cours...') {
    const overlay = document.createElement('div');
    overlay.className = 'loading-overlay';
    overlay.innerHTML = `
        <div class="spinner"></div>
        <p>${message}</p>
    `;
    document.body.appendChild(overlay);
}

function hideLoadingOverlay() {
    const overlay = document.querySelector('.loading-overlay');
    if (overlay) {
        overlay.remove();
    }
}

function showError(message) {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <i class="fas fa-exclamation-circle" style="color: #dc3545;"></i>
            <h3>Erreur</h3>
            <p>${message}</p>
            <button onclick="this.closest('.modal').remove()" class="btn-primary">OK</button>
        </div>
    `;
    document.body.appendChild(modal);
}

function showSuccess() {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <i class="fas fa-check-circle"></i>
            <h3>Paiement réussi !</h3>
            <p>Votre transaction a été effectuée avec succès.</p>
            <button onclick="window.location.href='mes-commandes.php'" class="btn-primary">
                Voir mes commandes
            </button>
        </div>
    `;
    document.body.appendChild(modal);
}

function showOTPModal() {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <h3>Vérification</h3>
            <p>Veuillez entrer le code reçu par SMS</p>
            <input type="text" maxlength="6" class="otp-input" placeholder="000000">
            <button onclick="verifyOTP()" class="btn-primary">Valider</button>
        </div>
    `;
    document.body.appendChild(modal);
}

function verifyOTP() {
    showLoadingOverlay('Vérification du code...');
    setTimeout(() => {
        hideLoadingOverlay();
        document.querySelector('.modal').remove();
        processPayment('mobile');
    }, 1500);
}

function processPayment(method) {
    showLoadingOverlay('Traitement du paiement...');
    setTimeout(() => {
        hideLoadingOverlay();
        showSuccess();
    }, 2000);
}

function confirmCashPayment() {
    const confirmation = confirm("Voulez-vous confirmer votre commande avec paiement à la livraison ?");
    if (confirmation) {
        processPayment('cash');
    }
}

// Formatage automatique des champs
document.getElementById('card_number')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '');
    value = value.replace(/(\d{4})/g, '$1 ').trim();
    e.target.value = value;
});

document.getElementById('expiry')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.slice(0,2) + '/' + value.slice(2,4);
    }
    e.target.value = value;
});

document.getElementById('phone')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.slice(0,2) + ' ' + value.slice(2);
    }
    if (value.length >= 7) {
        value = value.slice(0,7) + ' ' + value.slice(7);
    }
    e.target.value = value;
}); 