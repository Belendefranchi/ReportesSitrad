<?php

require "sitrad/models/users.model.php";

$dbUsers = new SQLite3('sitrad/basesDestino/dbUsers.db');

if (!$dbUsers) {
  die("Error al abrir la base de datos: " . $dbUsers->lastErrorMsg());
}

queryUsers($dbUsers);

?>