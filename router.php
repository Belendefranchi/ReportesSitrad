<?php
require_once("./controllers/users.controller.php");

$controller = $_POST['controller'];

if ($controller === "instrumentos"){
  require ("./controllers/instrumentos.controller.php");
} elseif ($controller === "mt52e") {
  require ("./controllers/mt52e.controller.php");
} elseif ($controller === "tc900") {
  require ("./controllers/tc900.controller.php");
}
?>