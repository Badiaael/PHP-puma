<?php
$page_title = 'Gestion des produits';
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

requireAdmin(); // Vérifie que l'utilisateur est admin

$db = getDB();
$message = '';

// Ajouter / Modifier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $id = $_POST['id'] ?? 0;
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];
    $is_new = isset($_POST['is_new']) ? 1 : 0;
    
    if ($id) {
        // UPDATE
        $stmt = $db->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, category_id=?, is_new=? WHERE id=?");
        $stmt->execute([$name, $description, $price, $stock, $category_id, $is_new, $id]);
        $message = "Produit modifié avec succès.";
    } else {
        // INSERT
        $stmt = $db->prepare("INSERT INTO products (name, description, price, stock, category_id, is_new) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $stock, $category_id, $is_new]);
        $message = "Produit ajouté avec succès.";
    }
}

// Supprimer
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $message = "Produit supprimé.";
}

// Récupérer tous les produits
$products = $db->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC")->fetchAll();
$categories = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll();

require_once 'admin-header.php';
?>

<div class="admin-content">
    <h1>Gestion des produits</h1>
    
    <?php if($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <div class="admin-card">
        <h2>Ajouter / Modifier un produit</h2>
        <form method="POST" action="" class="admin-form">
            <input type="hidden" name="id" id="product_id">
            
            <div class="form-row">
                <div class="form-group">
                    <label>Nom du produit</label>
                    <input type="text" name="name" id="product_name" required>
                </div>
                <div class="form-group">
                    <label>Prix (€)</label>
                    <input type="number" step="0.01" name="price" id="product_price" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" id="product_stock" required>
                </div>
                <div class="form-group">
                    <label>Catégorie</label>
                    <select name="category_id" id="product_category" required>
                        <option value="">Sélectionner</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="product_desc" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_new" id="product_new"> Nouveauté
                </label>
            </div>
            
            <button type="submit" name="save" class="btn-primary">Enregistrer</button>
            <button type="button" onclick="resetForm()" class="btn-secondary">Annuler</button>
        </form>
    </div>
    
    <div class="admin-card">
        <h2>Liste des produits</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Catégorie</th>
                    <th>Nouveau</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $product): ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= formatPrice($product['price']) ?></td>
                        <td><?= $product['stock'] ?></td>
                        <td><?= htmlspecialchars($product['category_name'] ?? '-') ?></td>
                        <td><?= $product['is_new'] ? '✓' : '' ?></td>
                        <td>
                            <button onclick="editProduct(<?= htmlspecialchars(json_encode($product)) ?>)" class="btn-edit">Modifier</button>
                            <a href="?delete=<?= $product['id'] ?>" onclick="return confirm('Supprimer ce produit ?')" class="btn-delete">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function editProduct(product) {
    document.getElementById('product_id').value = product.id;
    document.getElementById('product_name').value = product.name;
    document.getElementById('product_price').value = product.price;
    document.getElementById('product_stock').value = product.stock;
    document.getElementById('product_category').value = product.category_id;
    document.getElementById('product_desc').value = product.description;
    document.getElementById('product_new').checked = product.is_new == 1;
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function resetForm() {
    document.getElementById('product_id').value = '';
    document.getElementById('product_name').value = '';
    document.getElementById('product_price').value = '';
    document.getElementById('product_stock').value = '';
    document.getElementById('product_category').value = '';
    document.getElementById('product_desc').value = '';
    document.getElementById('product_new').checked = false;
}
</script>

<?php require_once 'admin-footer.php'; ?>