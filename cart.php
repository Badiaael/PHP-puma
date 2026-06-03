<?php
$page_title = 'Mon panier';
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Initialisation du panier
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Ajout au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)($_POST['quantity'] ?? 1);
    
    $product = getProductById($product_id);
    
    if ($product) {
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image' => $product['image_main']
            ];
        }
    }
    
    // Redirection pour éviter resoumission
    header('Location: cart.php');
    exit();
}

// Suppression d'un article
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($remove_id) {
        return $item['id'] != $remove_id;
    });
    header('Location: cart.php');
    exit();
}

// Mise à jour des quantités
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    foreach ($_POST['quantities'] as $id => $qty) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $id) {
                $item['quantity'] = max(1, (int)$qty);
                break;
            }
        }
    }
    header('Location: cart.php');
    exit();
}

require_once 'includes/header.php';
?>

<div class="cart-container">
    <h1>Mon panier</h1>
    
    <?php if (empty($_SESSION['cart'])): ?>
        <div class="empty-cart">
            <i class="fas fa-shopping-bag fa-3x"></i>
            <p>Votre panier est vide.</p>
            <a href="<?= SITE_URL ?>" class="btn-primary">Continuer mes achats</a>
        </div>
    <?php else: ?>
        <form method="POST" action="">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($_SESSION['cart'] as $item): ?>
                        <tr>
                            <td>
                                <div class="cart-product">
                                    <img src="<?= SITE_URL . ($item['image'] ?? 'assets/images/placeholder.jpg') ?>" width="60" alt="">
                                    <span><?= htmlspecialchars($item['name']) ?></span>
                                </div>
                            </td>
                            <td><?= formatPrice($item['price']) ?></td>
                            <td>
                                <input type="number" name="quantities[<?= $item['id'] ?>]" value="<?= $item['quantity'] ?>" min="1" class="qty-input">
                            </td>
                            <td><?= formatPrice($item['price'] * $item['quantity']) ?></td>
                            <td>
                                <a href="?remove=<?= $item['id'] ?>" class="remove-btn" onclick="return confirm('Supprimer cet article ?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                             </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="cart-actions">
                <button type="submit" name="update" class="btn-secondary">Mettre à jour</button>
                <a href="checkout.php" class="btn-primary">Valider la commande</a>
            </div>
        </form>
        
        <div class="cart-summary">
            <h3>Total : <?= formatPrice(getCartTotal()) ?></h3>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>