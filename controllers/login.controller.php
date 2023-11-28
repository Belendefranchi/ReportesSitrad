<?php

function redirectToDashboard($role)
{
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

function createDatabaseTable($dbUsers)
{
  $tableExistsQuery = "SELECT name FROM sqlite_master WHERE type='table' AND name='data'";
  $tableExistsResult = $dbUsers->querySingle($tableExistsQuery);

  if (!$tableExistsResult) {
    $createTableQuery = "
        CREATE TABLE IF NOT EXISTS data (
          username TEXT,
          password TEXT,
          role TEXT
        )
      ";

    try {
      $result = $dbUsers->exec($createTableQuery);

      if ($result === false) {
        throw new Exception("Error al crear la tabla: " . $dbUsers->lastErrorMsg());
      }
    } catch (Exception $e) {
      die("Error: " . $e->getMessage());
    }
  }
}

function loginUser($dbUsers, $username, $password)
{
  try {
    $query = "SELECT username, password, role FROM data WHERE username=:f_username";
    $stmt = $dbUsers->prepare($query);
    $stmt->bindValue(":f_username", $username);
    $result = $stmt->execute();

    while ($row = $result->fetchArray()) {
      $db_username = $row["username"];
      $db_password = $row["password"];
      $db_role = $row["role"];
      if ($username == $db_username && $password == $db_password) {
        return $db_role;
      }
    }
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
  }
}

if (isset($_SESSION['user'])) {
  if ($_SESSION["role"] == "admin" || $_SESSION["role"] == "user") {
    redirectToDashboard($_SESSION["role"]);
  }
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
          redirectToDashboard($role);
          break;

        case "user":
          $_SESSION["user_login"] = $username;
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