CREATE DATABASE IF NOT EXISTS sportshop;
USE sportshop;

-- Table users
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(50),
    postal_code VARCHAR(10),
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table categories
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) UNIQUE NOT NULL,
    gender ENUM('homme', 'femme', 'mixte') DEFAULT 'mixte'
);

-- Table products
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    category_id INT,
    image_main VARCHAR(255),
    image_secondary VARCHAR(255),
    is_new BOOLEAN DEFAULT FALSE,
    is_promo BOOLEAN DEFAULT FALSE,
    promo_price DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Table product_variants (tailles, couleurs)
CREATE TABLE product_variants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    size VARCHAR(10),
    color VARCHAR(50),
    stock INT,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Table orders
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    order_number VARCHAR(20) UNIQUE,
    total DECIMAL(10,2),
    status ENUM('En attente', 'Confirmée', 'Expédiée', 'Livrée') DEFAULT 'En attente',
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(50),
    postal_code VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Table order_items
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- Table reviews
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    user_id INT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des visites géolocalisées (pour les statistiques IP)
CREATE TABLE IF NOT EXISTS visits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ip_address VARCHAR(45) NOT NULL,
    country VARCHAR(100),
    country_code VARCHAR(5),
    city VARCHAR(100),
    page VARCHAR(255),
    visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ip (ip_address),
    INDEX idx_country (country),
    INDEX idx_date (visited_at)
);

-- Insertion catégories
INSERT INTO categories (name, slug, gender) VALUES
('Running', 'running', 'mixte'),
('Trail', 'trail', 'mixte'),
('Tennis', 'tennis', 'mixte'),
('Training', 'training', 'mixte'),
('Basketball', 'basketball', 'homme'),
('Fitness', 'fitness', 'femme');

-- Insertion produits exemple
INSERT INTO products (name, description, price, stock, category_id, image_main, is_new) VALUES
('NitroRush X1', 'Chaussure running ultra dynamique avec amorti réactif', 119.99, 25, 1, 'assets/images/nitrorush.jpg', 1),
('AeroSwift Pro', 'Légèreté et respirabilité pour vos entraînements', 99.99, 30, 4, 'assets/images/aeroswift.jpg', 0),
('TrailBlazer 5', 'Accroche parfaite sur terrain accidenté', 139.99, 15, 2, 'assets/images/trailblazer.jpg', 1),
('HyperCourt 2.0', 'Stabilité et pivotement pour le tennis', 109.99, 20, 3, 'assets/images/hypercourt.jpg', 0);

-- Insertion admin (mot de passe: admin123)
INSERT INTO users (email, password, full_name, role) VALUES
('admin@sportshop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin Super', 'admin');

-- Insertion utilisateur test (mot de passe: user123)
INSERT INTO users (email, password, full_name, role) VALUES
('user@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jean Dupont', 'user');