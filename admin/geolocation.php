<?php
$page_title = 'Géolocalisation IP';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/geolocation.php';

requireAdmin();

$db = getDB();

// Récupérer les statistiques
$visits_by_country = getVisitsByCountry();
$total_visits = getTotalVisits();
$unique_visitors = getUniqueVisitors();
$visits_last_7_days = getVisitsByCountryLastDays(7);
$visits_last_30_days = getVisitsByCountryLastDays(30);
$daily_stats = getVisitsByDay(7);

// Dernières visites
$recent_visits = $db->query("SELECT ip_address, country, city, page, visited_at FROM visits ORDER BY visited_at DESC LIMIT 20")->fetchAll();

require_once __DIR__ . '/admin-header.php';
?>

<div class="admin-content">
    <h1>🌍 Géolocalisation des visiteurs</h1>
    
    <!-- Cartes de statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <i class="fas fa-globe"></i>
            <h3><?= $total_visits ?></h3>
            <p>Visites totales</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-users"></i>
            <h3><?= $unique_visitors ?></h3>
            <p>Visiteurs uniques</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-calendar-week"></i>
            <h3><?= array_sum(array_column($visits_last_7_days, 'total')) ?></h3>
            <p>Visites (7 derniers jours)</p>
        </div>
    </div>
    
    <!-- Graphique par pays -->
    <div class="admin-card">
        <h2>🌎 Répartition géographique (total)</h2>
        
        <?php if(empty($visits_by_country)): ?>
            <p class="alert-info">Aucune donnée de visite pour le moment.</p>
        <?php else: ?>
            <div style="display: flex; flex-wrap: wrap; gap: 30px;">
                <div style="flex: 1; min-width: 250px;">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Pays</th>
                                <th>Visites</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($visits_by_country as $v): ?>
                                <?php 
                                $percent = round(($v['total'] / $total_visits) * 100, 1);
                                $flag = match($v['country']) {
                                    'France' => '🇫🇷',
                                    'Maroc' => '🇲🇦',
                                    'Espagne' => '🇪🇸',
                                    'Tunisie' => '🇹🇳',
                                    'Algérie' => '🇩🇿',
                                    'Sénégal' => '🇸🇳',
                                    'Côte d\'Ivoire' => '🇨🇮',
                                    'Localhost' => '💻',
                                    default => '🌍'
                                };
                                ?>
                                <tr>
                                    <td><?= $flag ?> <?= htmlspecialchars($v['country']) ?></td>
                                    <td><strong><?= $v['total'] ?></strong> visiteur<?= $v['total'] > 1 ? 's' : '' ?></td>
                                    <td>
                                        <div style="background:#eee; border-radius:10px; overflow:hidden; width:120px; display:inline-block;">
                                            <div style="background:#e67e22; width:<?= $percent ?>%; height:8px;"></div>
                                        </div>
                                        <?= $percent ?>%
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Graphique en camembert simple -->
                <div style="flex: 1; min-width: 250px; text-align: center;">
                    <h3>Répartition géographique</h3>
                    <div style="width: 200px; height: 200px; margin: 20px auto; position: relative;">
                        <canvas id="countryChart" width="200" height="200"></canvas>
                    </div>
                    <div style="font-size: 12px; margin-top: 10px;">
                        <?php foreach($visits_by_country as $v): ?>
                            <div style="display: inline-block; margin: 5px;">
                                <span style="display:inline-block; width:12px; height:12px; border-radius:50%;"></span>
                                <?= htmlspecialchars($v['country']) ?> (<?= $v['total'] ?>)
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Statistiques par période -->
    <div class="charts-row">
        <div class="chart-box">
            <h3>📊 Derniers 7 jours</h3>
            <?php if(empty($visits_last_7_days)): ?>
                <p>Aucune donnée</p>
            <?php else: ?>
                <table class="admin-table">
                    <thead><tr><th>Pays</th><th>Visites</th></tr></thead>
                    <tbody>
                        <?php foreach($visits_last_7_days as $v): ?>
                            <tr><td><?= htmlspecialchars($v['country']) ?></td><td><?= $v['total'] ?></td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <div class="chart-box">
            <h3>📅 Derniers 30 jours</h3>
            <?php if(empty($visits_last_30_days)): ?>
                <p>Aucune donnée</p>
            <?php else: ?>
                <table class="admin-table">
                    <thead><tr><th>Pays</th><th>Visites</th></tr></thead>
                    <tbody>
                        <?php foreach($visits_last_30_days as $v): ?>
                            <tr><td><?= htmlspecialchars($v['country']) ?></td><td><?= $v['total'] ?></td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Graphique journalier -->
    <div class="admin-card">
        <h2>📈 Évolution des visites (7 derniers jours)</h2>
        <?php if(empty($daily_stats)): ?>
            <p>Aucune donnée</p>
        <?php else: ?>
            <canvas id="dailyChart" height="100" style="max-width: 100%;"></canvas>
            <table class="admin-table" style="margin-top: 20px;">
                <thead><tr><th>Date</th><th>Visites</th></tr></thead>
                <tbody>
                    <?php foreach($daily_stats as $d): ?>
                        <tr><td><?= date('d/m/Y', strtotime($d['date'])) ?></td><td><?= $d['count'] ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <!-- Dernières visites -->
    <div class="admin-card">
        <h2>🕐 Dernières visites</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>IP</th>
                    <th>Pays</th>
                    <th>Ville</th>
                    <th>Page</th>
                    <th>Date/Heure</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($recent_visits as $visit): ?>
                    <tr>
                        <td><?= htmlspecialchars($visit['ip_address']) ?></td>
                        <td><?= htmlspecialchars($visit['country']) ?></td>
                        <td><?= htmlspecialchars($visit['city'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($visit['page']) ?></td>
                        <td><?= date('d/m/Y H:i:s', strtotime($visit['visited_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if(!empty($visits_by_country)): ?>
// Graphique par pays
const ctx = document.getElementById('countryChart').getContext('2d');
const countries = <?= json_encode(array_column($visits_by_country, 'country')) ?>;
const counts = <?= json_encode(array_column($visits_by_country, 'total')) ?>;

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: countries,
        datasets: [{
            data: counts,
            backgroundColor: ['#e67e22', '#3498db', '#2ecc71', '#e74c3c', '#9b59b6', '#f1c40f', '#1abc9c'],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
<?php endif; ?>

<?php if(!empty($daily_stats)): ?>
// Graphique journalier
const ctx2 = document.getElementById('dailyChart').getContext('2d');
const dates = <?= json_encode(array_reverse(array_column($daily_stats, 'date'))) ?>;
const dailyCounts = <?= json_encode(array_reverse(array_column($daily_stats, 'count'))) ?>;

new Chart(ctx2, {
    type: 'line',
    data: {
        labels: dates.map(d => new Date(d).toLocaleDateString('fr-FR')),
        datasets: [{
            label: 'Visites',
            data: dailyCounts,
            borderColor: '#e67e22',
            backgroundColor: 'rgba(230,126,34,0.1)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});
<?php endif; ?>
</script>

<style>
.alert-info {
    background: #e3f2fd;
    color: #1565c0;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
}
</style>

<?php require_once __DIR__ . '/admin-footer.php'; ?>