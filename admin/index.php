<?php
$page_title = 'Dashboard Admin';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin(); // Vérifie que l'utilisateur est admin

$db = getDB();

// Stats
$total_products = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_users = $db->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$total_orders = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pending_orders = $db->query("SELECT COUNT(*) FROM orders WHERE status = 'En attente'")->fetchColumn();
$revenue = $db->query("SELECT SUM(total) FROM orders WHERE status = 'Livrée'")->fetchColumn() ?? 0;

// Top produits
$top_products = $db->query("SELECT p.name, SUM(oi.quantity) as total_sold 
                            FROM order_items oi 
                            JOIN products p ON oi.product_id = p.id 
                            GROUP BY oi.product_id 
                            ORDER BY total_sold DESC LIMIT 5")->fetchAll();

// Top catégories
$top_categories = $db->query("SELECT c.name, COUNT(oi.id) as total 
                              FROM order_items oi 
                              JOIN products p ON oi.product_id = p.id 
                              JOIN categories c ON p.category_id = c.id 
                              GROUP BY c.id 
                              ORDER BY total DESC LIMIT 5")->fetchAll();

require_once __DIR__ . '/admin-header.php';
?>

<div class="admin-dashboard">
    <h1>Tableau de bord</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <i class="fas fa-shoe-prints"></i>
            <h3><?= $total_products ?></h3>
            <p>Produits</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-users"></i>
            <h3><?= $total_users ?></h3>
            <p>Clients</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-shopping-cart"></i>
            <h3><?= $total_orders ?></h3>
            <p>Commandes totales</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-clock"></i>
            <h3><?= $pending_orders ?></h3>
            <p>En attente</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-euro-sign"></i>
            <h3><?= formatPrice($revenue) ?></h3>
            <p>Chiffre d'affaires</p>
        </div>
    </div>
    
    <div class="charts-row">
        <div class="chart-box">
            <h3>Top 5 produits les plus vendus</h3>
            <ul class="top-list">
                <?php foreach($top_products as $product): ?>
                    <li>
                        <span><?= htmlspecialchars($product['name']) ?></span>
                        <span class="badge"><?= $product['total_sold'] ?> vendus</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="chart-box">
            <h3>Top catégories</h3>
            <ul class="top-list">
                <?php foreach($top_categories as $cat): ?>
                    <li>
                        <span><?= htmlspecialchars($cat['name']) ?></span>
                        <span class="badge"><?= $cat['total'] ?> articles</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/admin-footer.php'; ?>