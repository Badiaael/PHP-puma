<?php
$page_title = 'Validation de commande';
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

requireLogin();

if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

$error = '';
$success = '';

// Récupérer les infos utilisateur
$user = getCurrentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $postal_code = $_POST['postal_code'] ?? '';
    
    if (empty($first_name) || empty($last_name) || empty($address) || empty($city) || empty($postal_code)) {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } else {
        $db = getDB();
        $order_number = 'CMD-' . strtoupper(uniqid());
        $total = getCartTotal();
        
        try {
            $db->beginTransaction();
            
            // Créer la commande
            $stmt = $db->prepare("INSERT INTO orders (user_id, order_number, total, first_name, last_name, phone, address, city, postal_code) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_SESSION['user_id'],
                $order_number,
                $total,
                $first_name,
                $last_name,
                $phone,
                $address,
                $city,
                $postal_code
            ]);
            
            $order_id = $db->lastInsertId();
            
            // Ajouter les articles
            foreach ($_SESSION['cart'] as $item) {
                $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
            }
            
            $db->commit();
            
            // Vider le panier
            $_SESSION['cart'] = [];
            
            $success = "Commande validée ! Numéro : $order_number";
            header("refresh:3;url=order-history.php");
            
        } catch(Exception $e) {
            $db->rollBack();
            $error = "Une erreur est survenue. Veuillez réessayer.";
        }
    }
}

require_once 'includes/header.php';
?>

<div class="checkout-container">
    <h1>Validation de la commande</h1>
    
    <?php if($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <div class="checkout-grid">
        <div class="checkout-form">
            <h3>Informations de livraison</h3>
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label>Prénom *</label>
                        <input type="text" name="first_name" required value="<?= htmlspecialchars($_POST['first_name'] ?? $user['full_name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Nom *</label>
                        <input type="text" name="last_name" required value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? $user['phone'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label>Adresse *</label>
                    <textarea name="address" rows="2" required><?= htmlspecialchars($_POST['address'] ?? $user['address'] ?? '') ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Ville *</label>
                        <input type="text" name="city" required value="<?= htmlspecialchars($_POST['city'] ?? $user['city'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Code postal *</label>
                        <input type="text" name="postal_code" required value="<?= htmlspecialchars($_POST['postal_code'] ?? $user['postal_code'] ?? '') ?>">
                    </div>
                </div>
                
                <button type="submit" class="btn-primary btn-full">Confirmer la commande</button>
            </form>
        </div>
        
        <div class="checkout-summary">
            <h3>Récapitulatif</h3>
            <?php foreach($_SESSION['cart'] as $item): ?>
                <div class="summary-item">
                    <span><?= htmlspecialchars($item['name']) ?> x<?= $item['quantity'] ?></span>
                    <span><?= formatPrice($item['price'] * $item['quantity']) ?></span>
                </div>
            <?php endforeach; ?>
            <div class="summary-total">
                <strong>Total</strong>
                <strong><?= formatPrice(getCartTotal()) ?></strong>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>