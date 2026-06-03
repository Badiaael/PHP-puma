<?php
$page_title = 'Gestion des commandes';
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

requireAdmin();

$db = getDB();
$message = '';

// Changer le statut d'une commande
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);
    $message = "Statut mis à jour.";
}

// Affichage des commandes avec le nom du client
$orders = $db->query("SELECT o.*, u.full_name as user_name 
                      FROM orders o 
                      LEFT JOIN users u ON o.user_id = u.id 
                      ORDER BY o.created_at DESC")->fetchAll();

$statuses = ['En attente', 'Confirmée', 'Expédiée', 'Livrée'];

require_once 'admin-header.php';
?>

<div class="admin-content">
    <h1>Gestion des commandes</h1>
    
    <?php if($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>N° commande</th>
                    <th>Client</th>
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
                        <td><?= htmlspecialchars($order['user_name'] ?? $order['first_name'] . ' ' . $order['last_name']) ?></td>
                        <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                        <td><?= formatPrice($order['total']) ?></td>
                        <td>
                            <span class="status status-<?= strtolower(str_replace(' ', '-', $order['status'])) ?>">
                                <?= htmlspecialchars($order['status']) ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" style="display: flex; gap: 5px;">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <select name="status">
                                    <?php foreach($statuses as $s): ?>
                                        <option value="<?= $s ?>" <?= $s == $order['status'] ? 'selected' : '' ?>><?= $s ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" name="update_status" class="btn-small">Mettre à jour</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'admin-footer.php'; ?>