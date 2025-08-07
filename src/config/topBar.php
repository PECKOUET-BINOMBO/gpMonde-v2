<?php
require_once __DIR__ . '/path.php';

$titre = [
    '/dashboard' => 'Tableau de Bord',
    '/cargaisons' => 'Cargaisons',
    '/enregistrement' => 'Enregistrement',
    '/recherche' => 'Recherche un colis',
];



$pageTitle = $titre[$currentPath] ?? 'Titre indisponible';