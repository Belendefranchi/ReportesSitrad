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

require "sitrad/models/login.model.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = SQLite3::escapeString($_POST["txt_username"]);
  $password = SQLite3::escapeString($_POST["txt_password"]);

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  if (isset($dbUsers)) {
    $dbUsers->close();
  }

  $dbUsers = new SQLite3('sitrad/basesDestino/dbUsers.db');

  if (!$dbUsers) {
    die("Error al abrir la base de datos: " . $dbUsers->lastErrorMsg());
  }

  if (empty($username) && empty($password)) {
    $message = "Por favor ingrese el usuario y la contraseña";
  } elseif (empty($username)) {
    $message = "Por favor ingrese el usuario";
  } elseif (empty($password)) {
    $message = "Por favor ingrese la contraseña";
  } elseif ($username && $password) {
    $role = loginUser($dbUsers, $username, $password);

    if ($role) {
      if ($role === "admin"){
        echo "admin: " . $role;
        header("Location: /sitrad/panel");
        exit();
      }else{
        echo "else: " . $role;
        header("Location: /sitrad/reports");
        exit();
      }
    } else {
      $message = "Usuario o contraseña incorrectos";
    }
  }
}
?>