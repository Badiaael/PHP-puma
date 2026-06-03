<?php
$page_title = 'Accueil';
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';  // ← Charger auth APRÈS config

// Récupération des produits
$db = getDB();

// Construction de la requête avec filtres
$sql = "SELECT p.*, c.name as category_name FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";
$params = [];

if (!empty($_GET['search'])) {
    $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%{$_GET['search']}%";
    $params[] = "%{$_GET['search']}%";
}

if (!empty($_GET['category']) && $_GET['category'] != '') {
    $sql .= " AND c.slug = ?";
    $params[] = $_GET['category'];
}

if (!empty($_GET['min_price'])) {
    $sql .= " AND p.price >= ?";
    $params[] = $_GET['min_price'];
}

if (!empty($_GET['max_price'])) {
    $sql .= " AND p.price <= ?";
    $params[] = $_GET['max_price'];
}

if (!empty($_GET['filter']) && $_GET['filter'] == 'new') {
    $sql .= " AND p.is_new = 1";
}

$sql .= " ORDER BY p.created_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Récupérer les catégories
$categories = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll();

require_once 'includes/header.php';
?>

<div class="hero">
    <div class="hero-content">
        <h2>Démarre ta <br>meilleure course</h2>
        <p>Des chaussures de sport haute performance, amorti dynamique et design audacieux. Livraison offerte dès 100€.</p>
        <a href="#products" class="btn-primary">Explorer la collection →</a>
    </div>
    <div class="hero-img">
        <img src="assets/images/hero-shoe.png" alt="Chaussure hero" onerror="this.src='https://placehold.co/400x300/1e2a3e/white?text=SPORTSTEP'">
    </div>
</div>

<!-- Barre de recherche -->
<div class="search-filters">
    <form method="GET" action="" class="filter-form">
        <input type="text" name="search" placeholder="Rechercher un produit..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        
        <select name="category">
            <option value="">Toutes catégories</option>
            <?php foreach($categories as $cat): ?>
                <option value="<?= $cat['slug'] ?>" <?= ($_GET['category'] ?? '') == $cat['slug'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <input type="number" name="min_price" placeholder="Prix min" value="<?= $_GET['min_price'] ?? '' ?>">
        <input type="number" name="max_price" placeholder="Prix max" value="<?= $_GET['max_price'] ?? '' ?>">
        
        <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Filtrer</button>
        <a href="<?= SITE_URL ?>" class="btn-reset">Réinitialiser</a>
    </form>
</div>

<!-- Section produits -->
<div id="products">
    <h2 class="section-title">Nos meilleures ventes</h2>
    
    <?php if (empty($products)): ?>
        <div class="empty-products">
            <i class="fas fa-search fa-3x"></i>
            <p>Aucun produit ne correspond à votre recherche.</p>
            <p><small>Vérifiez qu'il y a des produits dans la base de données.</small></p>
        </div>
    <?php else: ?>
        <div class="products-grid">
            <?php foreach($products as $product): ?>
                <div class="product-card">
                    <div class="product-img">
                        <img src="<?= $product['image_main'] ? SITE_URL . $product['image_main'] : 'https://placehold.co/200x200/f2f4f8/2c3e66?text=' . urlencode($product['name']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php if($product['is_new']): ?>
                            <span class="badge-new">Nouveau</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <div class="product-category"><?= htmlspecialchars($product['category_name'] ?? 'Sport') ?></div>
                        <div class="product-title"><?= htmlspecialchars($product['name']) ?></div>
                        <div class="product-price"><?= formatPrice($product['price']) ?></div>
                        <a href="product.php?id=<?= $product['id'] ?>" class="btn-add">
                            <i class="fas fa-eye"></i> Voir détails
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>