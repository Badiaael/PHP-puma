<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?> - <?= $page_title ?? 'Boutique de chaussures de sport' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>assets/css/style.css">
</head>
<body>
<header>
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <a href="<?= SITE_URL ?>">
                    <h1>SPORTSTEP <span>⚡ performance & style</span></h1>
                </a>
            </div>
            <div class="nav-links">
                <a href="<?= SITE_URL ?>">Accueil</a>
                <a href="<?= SITE_URL ?>?category=homme">Hommes</a>
                <a href="<?= SITE_URL ?>?category=femme">Femmes</a>
                <a href="<?= SITE_URL ?>?filter=new">Nouveautés</a>
                <a href="<?= SITE_URL ?>about.php">À propos</a>      
                <a href="<?= SITE_URL ?>faq.php">FAQ</a>             
                <a href="<?= SITE_URL ?>contact.php">Contact</a>
                
                <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
                    <a href="<?= SITE_URL ?>profile.php"><i class="fas fa-user"></i> Mon compte</a>
                    <a href="<?= SITE_URL ?>order-history.php"><i class="fas fa-history"></i> Mes commandes</a>
                    <?php if (function_exists('isAdmin') && isAdmin()): ?>
                        <a href="<?= SITE_URL ?>admin/" style="color: #e67e22;">Admin</a>
                    <?php endif; ?>
                    <a href="<?= SITE_URL ?>logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                <?php else: ?>
                    <a href="<?= SITE_URL ?>login.php"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                    <a href="<?= SITE_URL ?>register.php"><i class="fas fa-user-plus"></i> Inscription</a>
                <?php endif; ?>
                
                <div class="cart-icon">
                    <a href="<?= SITE_URL ?>cart.php">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="cart-count"><?= function_exists('getCartCount') ? getCartCount() : 0 ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="container">
