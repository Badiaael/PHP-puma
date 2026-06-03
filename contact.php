<?php
$page_title = 'Contact';
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Veuillez remplir tous les champs.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Veuillez entrer un email valide.';
    } else {
        // Envoi d'email (à configurer avec votre serveur)
        $to = 'contact@sportstep.com';
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
        $full_message = "Nom: $name\nEmail: $email\n\nMessage:\n$message";
        
        // En production, décommentez la ligne ci-dessous
        // mail($to, $subject, $full_message, $headers);
        
        // Pour le développement, on simule l'envoi
        $success = 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.';
        
        // Optionnel : sauvegarder le message en base de données
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $subject, $message]);
    }
}

require_once 'includes/header.php';
?>

<div class="contact-container">
    <div class="contact-header">
        <h1>Contactez-nous</h1>
        <p>Une question ? Un conseil ? Notre équipe est à votre écoute.</p>
    </div>
    
    <div class="contact-grid">
        <div class="contact-form">
            <h2>Envoyez-nous un message</h2>
            
            <?php if($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" class="contact-form-fields">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nom complet *</label>
                        <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="subject">Sujet *</label>
                    <input type="text" id="subject" name="subject" required value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" rows="6" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                </div>
                
                <button type="submit" class="btn-primary">Envoyer le message</button>
            </form>
        </div>
        
        <div class="contact-info">
            <h2>Nos coordonnées</h2>
            
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <h3>Adresse</h3>
                    <p>123 Avenue des Sports<br>75001 Paris, France</p>
                </div>
            </div>
            
            <div class="info-item">
                <i class="fas fa-phone"></i>
                <div>
                    <h3>Téléphone</h3>
                    <p>+33 1 23 45 67 89<br>Lun-Ven : 9h-18h</p>
                </div>
            </div>
            
            <div class="info-item">
                <i class="fas fa-envelope"></i>
                <div>
                    <h3>Email</h3>
                    <p>contact@sportstep.com<br>sav@sportstep.com</p>
                </div>
            </div>
            
            <div class="social-links">
                <h3>Suivez-nous</h3>
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>
</div>

<style>
.contact-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.contact-header {
    text-align: center;
    margin-bottom: 40px;
}

.contact-header h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
}

.contact-form, .contact-info {
    background: white;
    padding: 32px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}

.contact-form h2, .contact-info h2 {
    margin-bottom: 24px;
    color: #1e293b;
}

.contact-form-fields .form-group {
    margin-bottom: 20px;
}

.contact-form-fields label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}

.contact-form-fields input,
.contact-form-fields textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}

.info-item {
    display: flex;
    gap: 16px;
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.info-item i {
    font-size: 24px;
    color: #e67e22;
    width: 40px;
}

.info-item h3 {
    margin-bottom: 5px;
    font-size: 16px;
}

.info-item p {
    color: #666;
    font-size: 14px;
}

.social-links {
    margin-top: 24px;
    text-align: center;
}

.social-links a {
    display: inline-block;
    margin: 0 10px;
    color: #1e293b;
    font-size: 24px;
    transition: 0.2s;
}

.social-links a:hover {
    color: #e67e22;
}

@media (max-width: 768px) {
    .contact-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>