<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?= $page_title ?? 'Dashboard' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>assets/css/admin.css">
</head>
<body>
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <h2>SportStep Admin</h2>
        <nav>
            <a href="index.php"><i class="fas fa-dashboard"></i> Dashboard</a>
            <a href="products.php"><i class="fas fa-shoe-prints"></i> Produits</a>
            <a href="users.php"><i class="fas fa-users"></i> Utilisateurs</a>
            <a href="orders.php"><i class="fas fa-truck"></i> Commandes</a>
            <a href="stats.php"><i class="fas fa-chart-line"></i> Statistiques</a>
            <a href="geolocation.php"><i class="fas fa-map-marker-alt"></i> 🌍 Géolocalisation</a>
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </aside>
    <main class="admin-main">