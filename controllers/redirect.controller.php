<?php

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

?>