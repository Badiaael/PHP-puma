<?php
$page_title = 'Mon compte';
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

requireLogin();

$db = getDB();
$user = getCurrentUser();
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $postal_code = $_POST['postal_code'] ?? '';
    
    $stmt = $db->prepare("UPDATE users SET full_name = ?, phone = ?, address = ?, city = ?, postal_code = ? WHERE id = ?");
    if ($stmt->execute([$full_name, $phone, $address, $city, $postal_code, $_SESSION['user_id']])) {
        $success = "Informations mises à jour !";
        // Recharger les données
        $_SESSION['user_name'] = $full_name;
        $user = getCurrentUser();
    }
}

require_once 'includes/header.php';
?>

<div class="profile-container">
    <h1>Mon compte</h1>
    
    <?php if($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <div class="profile-grid">
        <div class="profile-info">
            <h3>Mes informations</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Nom complet</label>
                    <input type="text" name="full_name" required value="<?= htmlspecialchars($user['full_name']) ?>">
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                    <small>L'email ne peut pas être modifié</small>
                </div>
                
                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label>Adresse</label>
                    <textarea name="address" rows="2"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Ville</label>
                        <input type="text" name="city" value="<?= htmlspecialchars($user['city'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Code postal</label>
                        <input type="text" name="postal_code" value="<?= htmlspecialchars($user['postal_code'] ?? '') ?>">
                    </div>
                </div>
                
                <button type="submit" class="btn-primary">Mettre à jour</button>
            </form>
        </div>
        
        <div class="profile-stats">
            <div class="stat-card">
                <i class="fas fa-shopping-bag"></i>
                <?php
                $stmt = $db->prepare("SELECT COUNT(*) as total FROM orders WHERE user_id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $order_count = $stmt->fetch()['total'];
                ?>
                <h3><?= $order_count ?></h3>
                <p>Commandes passées</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-euro-sign"></i>
                <?php
                $stmt = $db->prepare("SELECT SUM(total) as total FROM orders WHERE user_id = ? AND status = 'Livrée'");
                $stmt->execute([$_SESSION['user_id']]);
                $total_spent = $stmt->fetch()['total'] ?? 0;
                ?>
                <h3><?= formatPrice($total_spent) ?></h3>
                <p>Dépensé total</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>