<?php
$page_title = 'Inscription';
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';  // ← AJOUTE CETTE LIGNE

// Rediriger si déjà connecté
if (isLoggedIn()) {
    header('Location: ' . SITE_URL);
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    
    if (empty($email) || empty($password) || empty($full_name)) {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } elseif ($password !== $confirm_password) {
        $error = 'Les mots de passe ne correspondent pas.';
    } elseif (strlen($password) < 4) {
        $error = 'Le mot de passe doit contenir au moins 4 caractères.';
    } else {
        $db = getDB();
        
        // Vérifier si l'email existe déjà
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Cet email est déjà utilisé.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (email, password, full_name, phone, role) VALUES (?, ?, ?, ?, 'user')");
            
            if ($stmt->execute([$email, $hashed_password, $full_name, $phone])) {
                $success = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
                // Redirection automatique après 2 secondes
                header("refresh:2;url=login.php");
            } else {
                $error = 'Une erreur est survenue. Veuillez réessayer.';
            }
        }
    }
}

require_once 'includes/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <h2>Inscription</h2>
        
        <?php if($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" class="auth-form">
            <div class="form-group">
                <label for="full_name">Nom complet *</label>
                <input type="text" id="full_name" name="full_name" required value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="phone">Téléphone</label>
                <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe * (min. 4 caractères)</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe *</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn-primary btn-full">S'inscrire</button>
        </form>
        
        <p class="auth-link">Déjà inscrit ? <a href="login.php">Se connecter</a></p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>