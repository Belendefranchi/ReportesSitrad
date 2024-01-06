<?php

session_start();

if (isset($_SESSION['role'])) {
  if ($role == "admin"){
    header("Location: /sitrad/panel/");
  }else{
    header("Location: /sitrad/reports/");
  }
  exit();
}

require "sitrad/models/register.model.php";
createDatabaseTable($dbUsers);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = SQLite3::escapeString($_POST["txt_username"]);
  $password = SQLite3::escapeString($_POST["txt_password"]);
  $role	= "user";

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