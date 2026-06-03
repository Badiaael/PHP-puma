<?php
$page_title = 'Statistiques avancées';
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

requireAdmin();

$db = getDB();

// Statistiques globales
$total_users = $db->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$total_orders = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_revenue = $db->query("SELECT SUM(total) FROM orders WHERE status = 'Livrée'")->fetchColumn() ?? 0;

// Statistiques par mois
$monthly_stats = $db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as orders, SUM(total) as revenue 
                              FROM orders 
                              WHERE status = 'Livrée'
                              GROUP BY month 
                              ORDER BY month DESC LIMIT 12")->fetchAll();

// Panier moyen
$avg_cart = $total_orders > 0 ? $total_revenue / $total_orders : 0;

require_once 'admin-header.php';
?>

<div class="admin-content">
    <h1>Statistiques avancées</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3><?= $total_users ?></h3>
            <p>Clients inscrits</p>
        </div>
        <div class="stat-card">
            <h3><?= $total_orders ?></h3>
            <p>Commandes totales</p>
        </div>
        <div class="stat-card">
            <h3><?= formatPrice($total_revenue) ?></h3>
            <p>Chiffre d'affaires</p>
        </div>
        <div class="stat-card">
            <h3><?= formatPrice($avg_cart) ?></h3>
            <p>Panier moyen</p>
        </div>
    </div>
    
    <div class="admin-card">
        <h2>Évolution mensuelle</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mois</th>
                    <th>Nombre de commandes</th>
                    <th>Chiffre d'affaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($monthly_stats as $stat): ?>
                    <tr>
                        <td><?= date('F Y', strtotime($stat['month'] . '-01')) ?></td>
                        <td><?= $stat['orders'] ?></td>
                        <td><?= formatPrice($stat['revenue']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'admin-footer.php'; ?>