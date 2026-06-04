<?php
require_once __DIR__ . '/config.php';  //__DIR__ donne le chemin absolu du dossier courant

class Database {
    private $pdo; // Stocke la connexion PDO
    
    public function __construct() {
        try {
             // Crée la connexion à MySQL
            $this->pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Affiche les erreurs SQL
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Résultats sous forme de tableau associatif
                ]
            );
        } catch(PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->pdo;
    }
}

function getDB() {
    static $db = null; // Singleton : évite de multiplier les connexions
    if ($db === null) {
        $database = new Database();
        $db = $database->getConnection();
    }
    return $db;
}
?>
