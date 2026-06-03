<?php
$page_title = 'FAQ';
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

require_once 'includes/header.php';
?>

<div class="faq-container">
    <div class="faq-header">
        <h1>Foire aux questions</h1>
        <p>Toutes les réponses à vos questions</p>
    </div>
    
    <div class="faq-categories">
        <button class="faq-cat active" data-cat="all">Toutes</button>
        <button class="faq-cat" data-cat="commandes">Commandes</button>
        <button class="faq-cat" data-cat="livraison">Livraison</button>
        <button class="faq-cat" data-cat="retours">Retours & SAV</button>
        <button class="faq-cat" data-cat="produits">Produits</button>
    </div>
    
    <div class="faq-list">
        <!-- Catégorie Commandes -->
        <div class="faq-item" data-cat="commandes">
            <div class="faq-question">
                <span>Comment passer une commande ?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Pour passer commande, rien de plus simple :</p>
                <ol>
                    <li>Parcourez notre catalogue et ajoutez les produits souhaités au panier</li>
                    <li>Cliquez sur l'icône panier en haut à droite</li>
                    <li>Validez votre panier et renseignez vos coordonnées de livraison</li>
                    <li>Choisissez votre mode de paiement et finalisez la commande</li>
                </ol>
                <p>Vous recevrez un email de confirmation immédiatement après.</p>
            </div>
        </div>
        
        <div class="faq-item" data-cat="commandes">
            <div class="faq-question">
                <span>Puis-je modifier ou annuler ma commande ?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Vous pouvez modifier ou annuler votre commande dans l'heure suivant sa validation. Contactez notre service client au 01 23 45 67 89 ou par email à sav@sportstep.com.</p>
            </div>
        </div>
        
        <!-- Catégorie Livraison -->
        <div class="faq-item" data-cat="livraison">
            <div class="faq-question">
                <span>Quels sont les délais de livraison ?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Nos délais de livraison sont :</p>
                <ul>
                    <li><strong>Livraison standard</strong> : 3 à 5 jours ouvrés (offerte dès 100€ d'achat)</li>
                    <li><strong>Livraison express</strong> : 24h à 48h (8,90€)</li>
                    <li><strong>Point relais</strong> : 3 à 5 jours ouvrés (3,90€)</li>
                </ul>
            </div>
        </div>
        
        <div class="faq-item" data-cat="livraison">
            <div class="faq-question">
                <span>Livrez-vous à l'international ?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Oui, nous livrons dans toute l'Europe (France, Belgique, Suisse, Espagne, Allemagne, Italie, Luxembourg) ainsi qu'au Maroc et en Tunisie. Les frais de livraison varient selon la destination.</p>
            </div>
        </div>
        
        <!-- Catégorie Retours -->
        <div class="faq-item" data-cat="retours">
            <div class="faq-question">
                <span>Quelle est votre politique de retour ?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Vous disposez de 30 jours à compter de la réception de votre commande pour retourner un produit qui ne vous convient pas. Les articles doivent être neufs, non portés, dans leur emballage d'origine.</p>
                <p>Pour effectuer un retour, connectez-vous à votre compte, allez dans "Mes commandes" et cliquez sur "Retourner cet article".</p>
            </div>
        </div>
        
        <div class="faq-item" data-cat="retours">
            <div class="faq-question">
                <span>Comment suivre mon remboursement ?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Une fois votre retour reçu et vérifié (2-3 jours ouvrés), le remboursement est effectué sous 7 à 10 jours ouvrés sur le moyen de paiement utilisé. Vous recevrez un email de confirmation.</p>
            </div>
        </div>
        
        <!-- Catégorie Produits -->
        <div class="faq-item" data-cat="produits">
            <div class="faq-question">
                <span>Comment choisir ma taille ?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Nous vous conseillons de prendre votre taille habituelle. Pour plus de précision, consultez notre <a href="#">guide des tailles</a>. Nos chaussures taillent normalement, mais si vous êtes entre deux tailles, prenez la taille supérieure.</p>
            </div>
        </div>
        
        <div class="faq-item" data-cat="produits">
            <div class="faq-question">
                <span>Les produits sont-ils authentiques ?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Absolument ! SPORTSTEP est un revendeur officiel. Tous nos produits sont 100% authentiques et garantis par les fabricants.</p>
            </div>
        </div>
        
        <div class="faq-item" data-cat="produits">
            <div class="faq-question">
                <span>Comment entretenir mes chaussures ?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Pour prolonger la durée de vie de vos chaussures :</p>
                <ul>
                    <li>Lavez-les à la main avec une éponge humide</li>
                    <li>Ne les mettez pas en machine à laver</li>
                    <li>Laissez-les sécher à l'air libre, loin des sources de chaleur</li>
                    <li>Utilisez un spray imperméabilisant pour les protéger</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="faq-contact">
        <h3>Vous n'avez pas trouvé votre réponse ?</h3>
        <p>Contactez notre équipe, nous vous répondrons sous 24h</p>
        <a href="contact.php" class="btn-primary">Nous contacter</a>
    </div>
</div>

<style>
.faq-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px;
}

.faq-header {
    text-align: center;
    margin-bottom: 40px;
}

.faq-header h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.faq-categories {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 12px;
    margin-bottom: 32px;
}

.faq-cat {
    padding: 8px 20px;
    background: #f1f5f9;
    border: none;
    border-radius: 40px;
    cursor: pointer;
    font-size: 14px;
    transition: 0.2s;
}

.faq-cat.active {
    background: #e67e22;
    color: white;
}

.faq-item {
    background: white;
    border-radius: 12px;
    margin-bottom: 16px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.faq-question {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    cursor: pointer;
    font-weight: 600;
    background: white;
    transition: 0.2s;
}

.faq-question:hover {
    background: #f8fafc;
}

.faq-question i {
    transition: transform 0.2s;
    color: #e67e22;
}

.faq-item.active .faq-question i {
    transform: rotate(180deg);
}

.faq-answer {
    max-height: 0;
    padding: 0 20px;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background: #fafbfc;
    border-top: 1px solid #eee;
    line-height: 1.6;
    color: #444;
}

.faq-item.active .faq-answer {
    max-height: 500px;
    padding: 20px;
}

.faq-answer ul, .faq-answer ol {
    margin: 10px 0 10px 20px;
}

.faq-answer li {
    margin: 5px 0;
}

.faq-contact {
    text-align: center;
    background: #f1f5f9;
    padding: 40px;
    border-radius: 16px;
    margin-top: 48px;
}

.faq-contact h3 {
    margin-bottom: 12px;
}

.faq-contact .btn-primary {
    display: inline-block;
    margin-top: 16px;
}

@media (max-width: 768px) {
    .faq-categories {
        gap: 8px;
    }
    .faq-cat {
        padding: 6px 14px;
        font-size: 12px;
    }
}
</style>

<script>
document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', () => {
        const item = question.parentElement;
        item.classList.toggle('active');
    });
});

document.querySelectorAll('.faq-cat').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.faq-cat').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        
        const category = btn.dataset.cat;
        
        document.querySelectorAll('.faq-item').forEach(item => {
            if (category === 'all' || item.dataset.cat === category) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>