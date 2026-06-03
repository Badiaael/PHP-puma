<?php
// Fonctions utilitaires pour le site

// Formate un prix : 119.99 → "119,99 €"
function formatPrice($price) {
    return number_format($price, 2, ',', ' ') . ' €';
}

// Compte le nombre d'articles dans le panier (session)
function getCartCount() {
    if (!isset($_SESSION['cart'])) return 0;
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['quantity'];
    }
    return $count;
}

// Calcule le total du panier
function getCartTotal() {
    if (!isset($_SESSION['cart'])) return 0;
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Récupère un produit par son ID
function getProductById($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]); // Requête préparée = protection SQL injection
    return $stmt->fetch();
}

// Récupère toutes les catégories
function getCategories() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM categories ORDER BY name");
    return $stmt->fetchAll();
}

// Récupère les produits avec filtres (recherche, prix, catégorie)
function getProducts($filters = []) {
    $db = getDB();
    $sql = "SELECT p.*, c.name as category_name FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";
    $params = [];
    
    if (!empty($filters['search'])) {
        $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
        $params[] = "%{$filters['search']}%";
        $params[] = "%{$filters['search']}%";
    }
    
    if (!empty($filters['category'])) {
        $sql .= " AND c.slug = ?";
        $params[] = $filters['category'];
    }
    
    if (!empty($filters['min_price'])) {
        $sql .= " AND p.price >= ?";
        $params[] = $filters['min_price'];
    }
    
    if (!empty($filters['max_price'])) {
        $sql .= " AND p.price <= ?";
        $params[] = $filters['max_price'];
    }
    
    $sql .= " ORDER BY p.created_at DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
?>