<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$product_id = $_GET['id'] ?? 0;
$product = getProductById($product_id);

if (!$product) {
    header('Location: ' . SITE_URL); // Produit inexistant → accueil
    exit();
}

$page_title = $product['name'];

// Récupération des variantes (tailles/couleurs)
$db = getDB();
$stmt = $db->prepare("SELECT * FROM product_variants WHERE product_id = ?");
$stmt->execute([$product_id]);
$variants = $stmt->fetchAll();

// Récupération des avis clients
$stmt = $db->prepare("SELECT r.*, u.full_name FROM reviews r 
                      JOIN users u ON r.user_id = u.id 
                      WHERE r.product_id = ? ORDER BY r.created_at DESC");
$stmt->execute([$product_id]);
$reviews = $stmt->fetchAll();

// Calcul de la moyenne des notes
$avg_rating = 0;
if (!empty($reviews)) {
    $total = array_sum(array_column($reviews, 'rating'));
    $avg_rating = round($total / count($reviews), 1);
}

require_once 'includes/header.php';
?>

<div class="product-detail">
    <div class="product-detail-grid">
        <div class="product-gallery">
            <img src="<?= SITE_URL . ($product['image_main'] ?? 'assets/images/placeholder.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="main-image">
            <?php if($product['image_secondary']): ?>
                <div class="thumbnails">
                    <img src="<?= SITE_URL . $product['image_secondary'] ?>" alt="Secondaire">
                </div>
            <?php endif; ?>
        </div>
        
        <div class="product-info-detail">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <div class="product-rating">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star <?= $i <= $avg_rating ? 'filled' : '' ?>"></i>
                <?php endfor; ?>
                <span>(<?= count($reviews) ?> avis)</span>
            </div>
            <div class="product-price-big"><?= formatPrice($product['price']) ?></div>
            <div class="product-description">
                <h3>Description</h3>
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            </div>
            
            <!-- Formulaire d'ajout au panier -->
            <?php if($product['stock'] > 0): ?>
                <form action="cart.php" method="POST" class="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="action" value="add">
                    
                    <?php if(!empty($variants)): ?>
                        <div class="form-group">
                            <label>Taille</label>
                            <select name="size" required>
                                <option value="">Choisir une taille</option>
                                <?php foreach($variants as $v): ?>
                                    <option value="<?= htmlspecialchars($v['size']) ?>"><?= htmlspecialchars($v['size']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Couleur</label>
                            <select name="color">
                                <option value="">Choisir une couleur</option>
                                <?php foreach($variants as $v): ?>
                                    <option value="<?= htmlspecialchars($v['color']) ?>"><?= htmlspecialchars($v['color']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Quantité</label>
                        <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>">
                    </div>
                    
                    <div class="stock-info">
                        <i class="fas fa-check-circle"></i> En stock (<?= $product['stock'] ?> disponibles)
                    </div>
                    
                    <button type="submit" class="btn-primary btn-add-cart">
                        <i class="fas fa-cart-plus"></i> Ajouter au panier
                    </button>
                </form>
            <?php else: ?>
                <div class="stock-out">Rupture de stock</div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Section avis -->
    <div class="reviews-section">
        <h3>Avis clients</h3>
        
        <?php if(isLoggedIn()): ?>
            <div class="add-review">
                <h4>Donnez votre avis</h4>
                <form action="submit-review.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <div class="form-group">
                        <label>Note</label>
                        <select name="rating" required>
                            <option value="5">★★★★★ (Excellent)</option>
                            <option value="4">★★★★☆ (Très bien)</option>
                            <option value="3">★★★☆☆ (Bien)</option>
                            <option value="2">★★☆☆☆ (Moyen)</option>
                            <option value="1">★☆☆☆☆ (Médiocre)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Votre commentaire</label>
                        <textarea name="comment" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Publier mon avis</button>
                </form>
            </div>
        <?php endif; ?>
        
        <?php foreach($reviews as $review): ?>
            <div class="review-card">
                <div class="review-header">
                    <strong><?= htmlspecialchars($review['full_name']) ?></strong>
                    <div class="review-stars">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?= $i <= $review['rating'] ? 'filled' : '' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <small><?= date('d/m/Y', strtotime($review['created_at'])) ?></small>
                </div>
                <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>