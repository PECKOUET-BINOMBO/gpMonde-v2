<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/src/config/titreTopBar.php';

// Routes
$routes = [
    '/' => 'src/pages/accueil.html.php',
    '/dashboard' => 'src/pages/dashboard.html.php',
    '/cargaisons' => 'src/pages/cargaisons.html.php',
    '/enregistrement' => 'src/pages/enregistrement.html.php',
    '/recherche' => 'src/pages/recherche.html.php',
    '/403' => 'src/pages/errors/403.html.php',
    '/404' => 'src/pages/errors/404.html.php',
    '/500' => 'src/pages/errors/500.html.php',
    '/connexion' => 'src/pages/auth/login.html.php',
];

$request = $_SERVER['REQUEST_URI'];



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

