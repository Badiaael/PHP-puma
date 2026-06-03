<?php
// Configuration de l'application
define('DB_HOST', 'localhost');
define('DB_NAME', 'sportshop');
define('DB_USER', 'root');
define('DB_PASS', '');
define('SITE_URL', 'http://localhost/chaussures-sport/');
define('SITE_NAME', 'SPORTSTEP');

// Démarrage de la session AVANT tout affichage
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuration du fuseau horaire
date_default_timezone_set('Europe/Paris');

// PAS de fonctions ici ! Elles sont dans auth.php
?>