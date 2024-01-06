<?php

require "sitrad/models/panel.model.php";

$dbSensores = new SQLite3('sitrad/basesDestino/dbSensores.db');

if (!$dbSensores) {
  die("Error al abrir la base de datos: " . $dbSensores->lastErrorMsg());
}

createDatabaseTable($dbSensores);

/* ############################### OBTENER EL ULTIMO ARCHIVO MODIFICADO ############################### */
$path = "sitrad/basesOrigen";
$dir = opendir($path);

while ($file = readdir($dir)) {
  if ($file == "." || $file == "..") {
    echo ' ';
  } else {
    $files[$file] = filemtime($path . "/" . $file);
  }
}

if (!empty($files)) {
  //Ordena desde el más reciente.
  arsort($files);

  $i = 0;
  foreach ($files as $file => $timestamp) {
    if ($i < 1) {
      $dbOrigen = new SQLite3($path . "/" . $file);
      checkSensores($dbOrigen, $dbSensores);
      querySensores($dbSensores);
    };
    $dbOrigen->close();
    $dbSensores->close();
    $i++;
  }
};

?>