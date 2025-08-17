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
    if ($status === 'open') {
        $cargaisons = array_filter($cargaisons, function($c) {
            return $c['etat'] === 'ouvert';
        });
    }
    
    header('Content-Type: application/json');
    echo json_encode(array_values($cargaisons));
    exit;
}

// Ajoutez cette route dans index.php
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

    // Créer le colis
    $newId = 1;
    if (!empty($db['colis'])) {
        $ids = array_column($db['colis'], 'id');
        $newId = max($ids) + 1;
    }

    $newColis = [
        "id" => $newId,
        "numero_colis" => $data['numero_colis'],
        "cargaison_id" => $data['cargaison_id'],
        "nbr_colis" => $data['nbr_colis'],
        "poids" => $data['poids'],
        "type_produit" => $data['type_produit'],
        "type_transport" => $data['type_transport'],
        "prix" => $data['prix'],
        "description" => $data['description'],
        "etat" => "En attente",
        "info_expediteur" => $data['info_expediteur'],
        "info_destinataire" => $data['info_destinataire'],
        "created_at" => date('Y-m-d H:i:s')
    ];

    $db['colis'][] = $newColis;
    
    // Mettre à jour le poids actuel de la cargaison
    $cargaison['poids_actuel'] = $poids_actuel + $data['poids'];

    if (file_put_contents($dbPath, json_encode($db, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        header('Content-Type: application/json');
        echo json_encode(["success" => true, "colis" => $newColis]);
    } else {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Erreur lors de l'écriture dans la base de données"]);
    }
    exit;
}

if ($request === '/api/colis' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $code = $_GET['code'] ?? '';
    $dbPath = __DIR__ . '/db.json';
    
    try {
        $db = json_decode(file_get_contents($dbPath), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Erreur de lecture du fichier JSON');
        }

        $found = null;
        foreach ($db['colis'] as $colis) {
            if (strtoupper($colis['numero_colis']) === strtoupper($code)) {
                $found = $colis;
                break;
            }
        }

        header('Content-Type: application/json');
        if ($found) {
            // Assurez-vous que toutes les clés nécessaires sont présentes
            if (!isset($found['currentIndex'])) {
                $found['currentIndex'] = 0; // Valeur par défaut
            }
            echo json_encode($found);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Colis non trouvé']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
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