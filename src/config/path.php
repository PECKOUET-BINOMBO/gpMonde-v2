<?php
// Configuration des paths pour Render
$isRender = getenv('RENDER') !== false;

if ($isRender) {
    define('BASE_URL', 'https://your-app-name.onrender.com');
    define('ASSETS_PATH', '/src/assets');
} else {
    define('BASE_URL', 'http://localhost:10000');
    define('ASSETS_PATH', '/src/assets');
}

$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


