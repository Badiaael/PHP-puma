<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

$db = getDB();

// Nouveau mot de passe
$new_password = 'admin123';
$hashed = password_hash($new_password, PASSWORD_DEFAULT);

// Mettre à jour l'admin
$stmt = $db->prepare("UPDATE users SET password = ? WHERE email = 'admin@sportshop.com'");
$result = $stmt->execute([$hashed]);

if ($result && $stmt->rowCount() > 0) {
    echo "✅ Mot de passe admin mis à jour avec succès !<br>";
    echo "📧 Email: admin@sportshop.com<br>";
    echo "🔑 Nouveau mot de passe: admin123<br>";
    echo "<br>🔒 Hash: " . $hashed . "<br>";
    
    // Tester immédiatement
    if (password_verify('admin123', $hashed)) {
        echo "<br>✅ Test de vérification: OK - Vous pouvez maintenant vous connecter !";
    } else {
        echo "<br>❌ Erreur de vérification";
    }
} else {
    echo "❌ Échec de la mise à jour ou admin non trouvé<br>";
    
    // Afficher les admins existants
    $stmt = $db->query("SELECT id, email, role FROM users WHERE role = 'admin'");
    $admins = $stmt->fetchAll();
    
    if (empty($admins)) {
        echo "<br>Aucun admin trouvé. Création d'un nouvel admin...<br>";
        $stmt = $db->prepare("INSERT INTO users (email, password, full_name, role) VALUES (?, ?, ?, 'admin')");
        $stmt->execute(['admin@sportshop.com', $hashed, 'Administrateur']);
        echo "✅ Nouvel admin créé avec email: admin@sportshop.com / mot de passe: admin123";
    } else {
        echo "<br>Admins existants :<br>";
        foreach ($admins as $admin) {
            echo "- " . $admin['email'] . " (ID: " . $admin['id'] . ")<br>";
        }
    }
}
?>