<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/src/config/titreTopBar.php';

// Routes
$routes = [
    //route invite
    '/accueil' => 'src/pages/accueil.html.php',
    '/' => 'src/pages/auth/login.html.php',

    //routes gestionnaire
    '/dashboard' => 'src/pages/dashboard.html.php',
    '/cargaisons' => 'src/pages/cargaisons.html.php',
    '/enregistrement' => 'src/pages/enregistrement.html.php',
    '/recherche' => 'src/pages/recherche.html.php',
    //routes erreurs
    '/403' => 'src/pages/errors/403.html.php',
    '/404' => 'src/pages/errors/404.html.php',
    '/500' => 'src/pages/errors/500.html.php',

    
];

$request = $_SERVER['REQUEST_URI'];



// Gestion de l'API d'abord !
if ($request === '/api/cargaison' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $dbPath = __DIR__ . '/db.json';
    $db = json_decode(file_get_contents($dbPath), true);

    // Validation des données
    if (empty($data['type_transport']) || empty($data['lieu_depart']) || empty($data['lieu_arrive'])) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Tous les champs obligatoires doivent être remplis"]);
        exit;
    }

    // Générer un nouvel ID
    $newId = 1;
    if (!empty($db['cargaisons'])) {
        $ids = array_column($db['cargaisons'], 'id');
        $newId = max($ids) + 1;
    }

    $newCargo = [
        "id" => $newId,
        "numero_cargaison" => $data['numero_cargaison'] ?? 'CARG' . rand(100000, 999999),
        "type_transport" => $data['type_transport'],
        "lieu_depart" => $data['lieu_depart'],
        "lieu_arrive" => $data['lieu_arrive'],
        "distance" => $data['distance'] ?? null,
        "latitude_depart" => $data['latitude_depart'] ?? null,
        "longitude_depart" => $data['longitude_depart'] ?? null,
        "latitude_arrivee" => $data['latitude_arrivee'] ?? null,
        "longitude_arrivee" => $data['longitude_arrivee'] ?? null,
        "poids_max" => $data['poids_max'],
        "date_depart" => $data['date_depart'],
        "date_arrivee" => $data['date_arrivee'],
        "description" => $data['description'] ?? ""
    ];

    $db['cargaisons'][] = $newCargo;
    
    if (file_put_contents($dbPath, json_encode($db, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        header('Content-Type: application/json');
        echo json_encode(["success" => true, "cargaison" => $newCargo]);
    } else {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Erreur lors de l'écriture dans la base de données"]);
    }
    exit;
}

// Ensuite, routes classiques
if (array_key_exists($request, $routes)) {
    require __DIR__.'/'.$routes[$request];
} 
elseif ($request === '/500') {
    http_response_code(500);
    require __DIR__.'/src/pages/errors/500.html.php';
}
elseif ($request === '/403') {
    http_response_code(403);
    require __DIR__.'/src/pages/errors/403.html.php';
}
else {
    http_response_code(404);
    require __DIR__.'/src/pages/errors/404.html.php';
}