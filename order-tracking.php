<?php
$page_title = 'Suivi de commande';
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

requireLogin();

$order_id = $_GET['id'] ?? 0;

$db = getDB();
$stmt = $db->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: order-history.php');
    exit();
}

$statuses = ['En attente', 'Confirmée', 'Expédiée', 'Livrée'];
$current_index = array_search($order['status'], $statuses);
$progress_percent = (($current_index + 1) / count($statuses)) * 100;

require_once 'includes/header.php';
?>

<div class="tracking-container">
    <h1>Suivi de commande</h1>
    <p class="order-number">Commande n° : <?= htmlspecialchars($order['order_number']) ?></p>
    
    <div class="tracking-progress">
        <div class="progress-bar">
            <div class="progress-fill" style="width: <?= $progress_percent ?>%"></div>
        </div>
        
        <div class="status-steps">
            <?php foreach($statuses as $index => $status): ?>
                <div class="step <?= $index <= $current_index ? 'completed' : '' ?>">
                    <div class="step-marker"><?= $index + 1 ?></div>
                    <div class="step-label"><?= $status ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="order-details">
        <h3>Détails de la commande</h3>
        <p><strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
        <p><strong>Total :</strong> <?= formatPrice($order['total']) ?></p>
        <p><strong>Adresse de livraison :</strong><br>
        <?= htmlspecialchars($order['first_name']) ?> <?= htmlspecialchars($order['last_name']) ?><br>
        <?= nl2br(htmlspecialchars($order['address'])) ?><br>
        <?= htmlspecialchars($order['postal_code']) ?> <?= htmlspecialchars($order['city']) ?>
        </p>
        
        <h4>Articles commandés</h4>
        <?php
        $stmt = $db->prepare("SELECT oi.*, p.name FROM order_items oi 
                              JOIN products p ON oi.product_id = p.id 
                              WHERE oi.order_id = ?");
        $stmt->execute([$order_id]);
        $items = $stmt->fetchAll();
        ?>
        <ul class="tracking-items">
            <?php foreach($items as $item): ?>
                <li><?= htmlspecialchars($item['name']) ?> - x<?= $item['quantity'] ?> - <?= formatPrice($item['price']) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>