<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');
set_time_limit(300);
ini_set('precision', 15);

//include("./controllers/users.controller.php");

// Obtiene la ruta desde la URL
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


$rutas = [
  '/login' => ['Login', 'login.php'],
  '/panel' => ['Panel de control', './controllers/panel.controller.php'],
  '/sensores' => ['Sensores', './controllers/dbSensores.controller.php'],
  '/reportes' => ['Reportes', './controllers/reportes.controller.php'],
];


