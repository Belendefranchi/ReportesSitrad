<?php

session_start();

require "sitrad/models/login.model.php";

function redirectToDashboard($role){
  switch ($role) {
    case "admin":
      header("Location: /sitrad/panel/");
      exit();
    case "user":
      header("Location: /sitrad/reportes/");
      exit();
    default:
      die("Rol no reconocido");
  }
}

if (isset($_SESSION["admin_login"]) || isset($_SESSION["user_login"])) {
  redirectToDashboard($_SESSION["role"]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST["txt_username"];
  $password = $_POST["txt_password"];

  if (isset($dbUsers)) {
    $dbUsers->close();
  }

  $dbUsers = new SQLite3('sitrad/basesDestino/dbUsers.db');

  createDatabaseTable($dbUsers);

  if (empty($username) && empty($password)) {
    $errorMsg = "Por favor ingrese el usuario y la contraseña";
  } elseif (empty($username)) {
    $errorMsg = "Por favor ingrese el usuario";
  } elseif (empty($password)) {
    $errorMsg = "Por favor ingrese la contraseña";
  } elseif ($username && $password) {
    $role = loginUser($dbUsers, $username, $password);

    if ($role) {
      switch ($role) {
        case "admin":
          $_SESSION["admin_login"] = $username;
          $_SESSION["role"] = $role;
          redirectToDashboard($role);
          break;

        case "user":
          $_SESSION["user_login"] = $username;
          $_SESSION["role"] = $role;
          redirectToDashboard($role);
          break;

        default:
          $errorMsg = "1 (no hay rol): Usuario o contraseña incorrectos";
          echo $role;
      }
    } else {
      $errorMsg = "2: Usuario o contraseña incorrectos";
    }
  }
}
?>