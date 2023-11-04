<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');
set_time_limit(300);
ini_set('precision', 15);

//require_once("./controllers/users.controller.php");

$controller = $_POST['controller'];

if ($controller === "instrumentos"){
  require_once ("./controllers/instrumentos.controller.php");
} elseif ($controller === "mt512e") {
  require_once ("./controllers/dbSensores.controller.php");
} elseif ($controller === "tc900") {
  require_once ("./controllers/tc900.controller.php");
}
?>