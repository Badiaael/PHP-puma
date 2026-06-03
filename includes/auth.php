<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

// Vérifie si l'utilisateur est connecté (session)
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Vérifie si l'utilisateur est administrateur
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Redirige vers login si non connecté
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . SITE_URL . 'login.php');
        exit();
    }
}

// Redirige si pas admin (protection des pages admin)
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . SITE_URL . 'index.php');
        exit();
    }
}

// Récupère les infos de l'utilisateur connecté
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

?>