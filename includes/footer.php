</main>

<footer>
    <div class="container" id="contact">
        <div class="footer-content">
            <div class="footer-section">
                <h3>SPORTSTEP</h3>
                <p>Des chaussures de sport haute performance pour tous les athlètes.</p>
            </div>
            <div class="footer-section">
                <h4>Liens rapides</h4>
                <a href="<?= SITE_URL ?>">Accueil</a>
                <a href="<?= SITE_URL ?>?category=homme">Hommes</a>
                <a href="<?= SITE_URL ?>?category=femme">Femmes</a>
            </div>
            <div class="footer-section">
                <h4>Contact</h4>
                <p><i class="fas fa-phone"></i> 01 23 45 67 89</p>
                <p><i class="fas fa-envelope"></i> contact@sportstep.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 SPORTSTEP — Livraison rapide • Retours gratuits 30j</p>
        </div>
    </div>
</footer>
<?php
// Track visit dans le footer
if (function_exists('trackVisit') && strpos($_SERVER['SCRIPT_NAME'], 'admin') === false) {
    trackVisit();
}

// Tracking des visites (géolocalisation)
if (file_exists(__DIR__ . '/geolocation.php') && strpos($_SERVER['SCRIPT_NAME'], 'admin') === false) {
    require_once __DIR__ . '/geolocation.php';
    trackVisit();
}
?>

<script src="<?= SITE_URL ?>assets/js/main.js"></script>
</body>
</html>