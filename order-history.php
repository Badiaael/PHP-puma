<?php
$page_title = 'Mes commandes';
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

requireLogin();

$db = getDB();
$stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<div class="orders-container">
    <h1>Mes commandes</h1>
    
    <?php if (empty($orders)): ?>
        <div class="empty-orders">
            <p>Vous n'avez pas encore passé de commande.</p>
            <a href="<?= SITE_URL ?>" class="btn-primary">Découvrir nos produits</a>
        </div>
    <?php else: ?>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>N° commande</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_number']) ?></td>
                        <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                        <td><?= formatPrice($order['total']) ?></td>
                        <td>
                            <span class="status status-<?= strtolower(str_replace(' ', '-', $order['status'])) ?>">
                                <?= htmlspecialchars($order['status']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="order-tracking.php?id=<?= $order['id'] ?>" class="btn-small">Suivre</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>