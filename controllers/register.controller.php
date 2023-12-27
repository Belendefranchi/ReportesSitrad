<?php
require "sitrad/controllers/redirect.controller.php";
require "sitrad/models/register.model.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = SQLite3::escapeString($_POST["txt_username"]);
  $password = SQLite3::escapeString($_POST["txt_password"]);
  $role	= "user";

  if (isset($dbUsers)) {
    $dbUsers->close();
  }

  $dbUsers = new SQLite3('sitrad/basesDestino/dbUsers.db');

  if (!$dbUsers) {
    die("Error al abrir la base de datos: " . $dbUsers->lastErrorMsg());
  }

  createDatabaseTable($dbUsers);

  if (empty($username) && empty($password)) {
    $message = "Por favor ingrese el usuario y la contraseña";
  } elseif (empty($username)) {
    $message = "Por favor ingrese el usuario";
  } elseif (empty($password)) {
    $message = "Por favor ingrese la contraseña";
  } elseif (strlen($password) < 6) {
    $message = "La contraseña debe tener mínimo 6 caracteres";
  } elseif ($username && $password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $message = checkUser($dbUsers, $username, $hashedPassword, $role);
  }
}

?>