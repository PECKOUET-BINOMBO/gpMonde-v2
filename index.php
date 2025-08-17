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

$request = strtok($_SERVER['REQUEST_URI'], '?');



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
        "poids_actuel" => 0,           // Ajouté
        "etat" => "ouvert",            // Ajouté
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

if ($request === '/api/cargaisons' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $dbPath = __DIR__ . '/db.json';
    $db = json_decode(file_get_contents($dbPath), true);
    
    $status = $_GET['status'] ?? null;
    $cargaisons = $db['cargaisons'];
    
    // Filtrer par statut si demandé
    if ($status === 'ouvert') {
        $cargaisons = array_filter($cargaisons, function($c) {
            return $c['etat'] === 'ouvert';
        });
    }
    
    header('Content-Type: application/json');
    echo json_encode(array_values($cargaisons));
    exit;
}

if ($request === '/api/colis' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $dbPath = __DIR__ . '/db.json';
    $db = json_decode(file_get_contents($dbPath), true);

    // Validation
    if (empty($data['cargaison_id']) || empty($data['poids'])) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Données manquantes"]);
        exit;
    }

    // Vérifier que la cargaison existe et est ouverte
    $cargaison = null;
    foreach ($db['cargaisons'] as &$c) {
        if ($c['id'] == $data['cargaison_id']) {
            $cargaison = &$c;
            break;
        }
    }

    if (!$cargaison || $cargaison['etat'] !== 'ouvert') {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Cargaison non disponible"]);
        exit;
    }

    // Vérifier la capacité
    $poids_actuel = $cargaison['poids_actuel'] ?? 0;
    if (($poids_actuel + $data['poids']) > $cargaison['poids_max']) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Capacité maximale dépassée"]);
        exit;
    }

    // Créer le colis avec toutes les informations nécessaires
    $newId = 1;
    if (!empty($db['colis'])) {
        $ids = array_column($db['colis'], 'id');
        $newId = max($ids) + 1;
    }

    // Générer la route basée sur la cargaison
    $route = [
        [
            "lat" => $cargaison['latitude_depart'] ?? 0,
            "lng" => $cargaison['longitude_depart'] ?? 0,
            "name" => $cargaison['lieu_depart'] . " (Départ)",
            "time" => date('d/m/Y H:i', strtotime($cargaison['date_depart']))
        ],
        [
            "lat" => $cargaison['latitude_arrivee'] ?? 0,
            "lng" => $cargaison['longitude_arrivee'] ?? 0,
            "name" => $cargaison['lieu_arrive'] . " (Destination)",
            "time" => date('d/m/Y H:i', strtotime($cargaison['date_arrivee']))
        ]
    ];

    // Si c'est un transport longue distance, ajouter des points de transit
    if ($cargaison['type_transport'] === 'maritime' || 
        (isset($cargaison['distance']) && $cargaison['distance'] > 500)) {
        
        // Point de transit au milieu (exemple générique)
        $lat_transit = ($route[0]['lat'] + $route[1]['lat']) / 2;
        $lng_transit = ($route[0]['lng'] + $route[1]['lng']) / 2;
        
        $transit_point = [
            "lat" => $lat_transit,
            "lng" => $lng_transit,
            "name" => "Point de Transit",
            "time" => date('d/m/Y H:i', strtotime($cargaison['date_depart'] . ' +2 days'))
        ];
        
        // Insérer le point de transit
        array_splice($route, 1, 0, [$transit_point]);
    }

    $newColis = [
        "id" => $newId,
        "numero_colis" => $data['numero_colis'],
        "cargaison_id" => (int)$data['cargaison_id'],
        "nbr_colis" => (int)$data['nbr_colis'],
        "poids" => (float)$data['poids'],
        "type_produit" => $data['type_produit'],
        "type_transport" => $data['type_transport'],
        "prix" => (int)$data['prix'],
        "description" => $data['description'] ?? '',
        "etat" => "En attente",
        "lieu_actuel" => $cargaison['lieu_depart'], // Position actuelle = lieu de départ
        "route" => $route,
        "currentIndex" => 0, // Index de la position actuelle dans la route (départ)
        "info_expediteur" => [
            "nom" => $data['info_expediteur']['nom'],
            "prenom" => $data['info_expediteur']['prenom'],
            "adresse" => $data['info_expediteur']['adresse'],
            "tel" => $data['info_expediteur']['tel'],
            "email" => $data['info_expediteur']['email'] ?? ''
        ],
        "info_destinataire" => [
            "nom" => $data['info_destinataire']['nom'],
            "prenom" => $data['info_destinataire']['prenom'],
            "adresse" => $data['info_destinataire']['adresse'],
            "tel" => $data['info_destinataire']['tel'],
            "email" => $data['info_destinataire']['email'] ?? ''
        ],
        "created_at" => date('Y-m-d H:i:s')
    ];

    $db['colis'][] = $newColis;
    
    // Mettre à jour le poids actuel de la cargaison
    $cargaison['poids_actuel'] = $poids_actuel + $data['poids'];

    if (file_put_contents($dbPath, json_encode($db, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        header('Content-Type: application/json');
        echo json_encode([
            "success" => true, 
            "colis" => $newColis,
            "message" => "Colis enregistré avec succès"
        ]);
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