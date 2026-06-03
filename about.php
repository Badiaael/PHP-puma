<?php
$page_title = 'À propos';
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

require_once 'includes/header.php';
?>

<div class="about-container">
    <div class="about-hero">
        <h1>À propos de SPORTSTEP</h1>
        <p>Votre partenaire performance depuis 2010</p>
    </div>
    
    <div class="about-content">
        <div class="about-section">
            <h2>Notre histoire</h2>
            <p>Fondée en 2010 à Paris, SPORTSTEP est née d'une passion commune pour le sport et l'innovation. Notre mission : proposer des chaussures de sport alliant performance, confort et style, accessibles à tous les athlètes, du débutant au professionnel.</p>
            <p>En 15 ans, nous avons équipé plus de 500 000 sportifs à travers la France et l'Europe, et nous continuons d'innover chaque jour pour repousser les limites de la performance.</p>
        </div>
        
        <div class="about-section">
            <h2>Nos valeurs</h2>
            <div class="values-grid">
                <div class="value-card">
                    <i class="fas fa-star"></i>
                    <h3>Qualité</h3>
                    <p>Des matériaux premium et un contrôle rigoureux pour des produits durables.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-leaf"></i>
                    <h3>Innovation</h3>
                    <p>Des technologies de pointe pour améliorer vos performances.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-heart"></i>
                    <h3>Passion</h3>
                    <p>Une équipe de sportifs qui comprend vos besoins.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-globe"></i>
                    <h3>Engagement</h3>
                    <p>Une production éco-responsable et des emballages recyclés.</p>
                </div>
            </div>
        </div>
        
        <div class="about-section">
            <h2>Notre équipe</h2>
            <div class="team-grid">
                <div class="team-card">
                    <img src="assets/images/team1.jpg" alt="Fondateur" onerror="this.src='https://placehold.co/200x200/1e293b/white?text=JD'">
                    <h3>Jean Dupont</h3>
                    <p>Fondateur & PDG</p>
                </div>
                <div class="team-card">
                    <img src="assets/images/team2.jpg" alt="Designer" onerror="this.src='https://placehold.co/200x200/1e293b/white?text=SL'">
                    <h3>Sophie Laurent</h3>
                    <p>Directrice Design</p>
                </div>
                <div class="team-card">
                    <img src="assets/images/team3.jpg" alt="Tech" onerror="this.src='https://placehold.co/200x200/1e293b/white?text=MR'">
                    <h3>Marc Roux</h3>
                    <p>Ingénieur R&D</p>
                </div>
            </div>
        </div>
        
        <div class="about-section stats">
            <h2>Chiffres clés</h2>
            <div class="stats-grid-about">
                <div class="stat-about">
                    <span class="stat-number">15+</span>
                    <span class="stat-label">Années d'expertise</span>
                </div>
                <div class="stat-about">
                    <span class="stat-number">500k+</span>
                    <span class="stat-label">Clients satisfaits</span>
                </div>
                <div class="stat-about">
                    <span class="stat-number">50+</span>
                    <span class="stat-label">Pays desservis</span>
                </div>
                <div class="stat-about">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Support client</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.about-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.about-hero {
    text-align: center;
    background: linear-gradient(135deg, #1e293b, #2c3e66);
    color: white;
    padding: 60px 40px;
    border-radius: 24px;
    margin-bottom: 48px;
}

.about-hero h1 {
    font-size: 3rem;
    margin-bottom: 16px;
}

.about-section {
    margin-bottom: 48px;
}

.about-section h2 {
    font-size: 2rem;
    margin-bottom: 24px;
    position: relative;
    padding-bottom: 12px;
}

.about-section h2:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 4px;
    background: #e67e22;
}

.about-section p {
    line-height: 1.8;
    color: #444;
    margin-bottom: 16px;
}

.values-grid, .team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
    margin-top: 24px;
}

.value-card, .team-card {
    background: white;
    padding: 24px;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    transition: 0.2s;
}

.value-card:hover, .team-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.1);
}

.value-card i {
    font-size: 40px;
    color: #e67e22;
    margin-bottom: 16px;
}

.value-card h3, .team-card h3 {
    margin-bottom: 8px;
}

.team-card img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 16px;
}

.stats-grid-about {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 24px;
    background: white;
    padding: 32px;
    border-radius: 16px;
    text-align: center;
}

.stat-about {
    padding: 20px;
}

.stat-number {
    display: block;
    font-size: 2.5rem;
    font-weight: 800;
    color: #e67e22;
}

.stat-label {
    font-size: 0.9rem;
    color: #666;
}

@media (max-width: 768px) {
    .about-hero h1 { font-size: 2rem; }
    .about-section h2 { font-size: 1.5rem; }
}
</style>

<?php require_once 'includes/footer.php'; ?>