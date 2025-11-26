<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/routes.php';

// session_start();

// $routes = [
//     '/' => 'home.php',
//     '/doneer' => 'donatieFormulier.php',
//     '/aanvraag' => 'aanvraagFormulier.php',
// ];

// $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// foreach ($routes as $route => $file) {
//     if ($uri === $route) {
//         include __DIR__ . "/$file";
//         exit();
//     }
// }

// http_response_code(404);
// echo "'" . explode('/', $uri)[1] . "' niet gevonden";
// exit();
