<?php
// Fichier de géolocalisation par IP

function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function getCountryFromIP($ip) {
    // Exclure les IP locales
    if ($ip == '127.0.0.1' || $ip == '::1' || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0) {
        return ['country' => 'Localhost', 'countryCode' => 'LOCAL', 'city' => 'Local'];
    }
    
    // Appel à l'API ip-api.com (gratuit, pas besoin de clé)
    $url = "http://ip-api.com/json/" . $ip . "?fields=status,country,countryCode,city";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200 && $response) {
        $data = json_decode($response, true);
        if ($data && $data['status'] == 'success') {
            return [
                'country' => $data['country'],
                'countryCode' => $data['countryCode'],
                'city' => $data['city']
            ];
        }
    }
    
    return ['country' => 'Inconnu', 'countryCode' => 'UNKNOWN', 'city' => 'Inconnu'];
}

function trackVisit($page = '') {
    $db = getDB();
    $ip = getUserIP();
    $geo = getCountryFromIP($ip);
    
    $pageName = $page ?: basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
    
    $stmt = $db->prepare("INSERT INTO visits (ip_address, country, country_code, city, page) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$ip, $geo['country'], $geo['countryCode'], $geo['city'], $pageName]);
    
    return $geo;
}

function getVisitsByCountry() {
    $db = getDB();
    $stmt = $db->query("SELECT country, COUNT(*) as total FROM visits GROUP BY country ORDER BY total DESC");
    return $stmt->fetchAll();
}

function getVisitsByCountryLastDays($days = 30) {
    $db = getDB();
    $stmt = $db->prepare("SELECT country, COUNT(*) as total FROM visits WHERE visited_at >= DATE_SUB(NOW(), INTERVAL ? DAY) GROUP BY country ORDER BY total DESC");
    $stmt->execute([$days]);
    return $stmt->fetchAll();
}

function getTotalVisits() {
    $db = getDB();
    return $db->query("SELECT COUNT(*) FROM visits")->fetchColumn();
}

function getUniqueVisitors() {
    $db = getDB();
    return $db->query("SELECT COUNT(DISTINCT ip_address) FROM visits")->fetchColumn();
}

function getVisitsByDay($days = 7) {
    $db = getDB();
    $stmt = $db->prepare("SELECT DATE(visited_at) as date, COUNT(*) as count FROM visits WHERE visited_at >= DATE_SUB(NOW(), INTERVAL ? DAY) GROUP BY DATE(visited_at) ORDER BY date DESC");
    $stmt->execute([$days]);
    return $stmt->fetchAll();
}
?>