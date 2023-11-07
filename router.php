<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');
set_time_limit(300);
ini_set('precision', 15);

//include("./controllers/users.controller.php");

$controller = $_POST['controller'];

if ($controller === "instrumentos"){
  require ("./controllers/instrumentos.controller.php");
} else {
  require ("./controllers/dbSensores.controller.php");
}
?>