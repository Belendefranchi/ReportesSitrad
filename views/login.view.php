<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="/sitrad/node_modules/bootstrap/dist/css/bootstrap.min.css">
</head>

<body class="text-center" style="background-color:#D7E1D6">

  <?php

  session_start();

  require ("../controllers/login.controller.php");
  /* ########################################## FUNCIONES ########################################## */

/*   function redirectToDashboard($role)
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
  } */


  /* ############################################################################################ */

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

  <header style="background-color:white">
    <img class="img-fluid" src="public/portada.jpg" alt="portada">
  </header>
  <main class="d-flex flex-column align-items-center m-3">
    <div class="col-sm-6">
      <?php
      if (isset($errorMsg)) {
      ?>
        <div class="alert alert-success p-2">
          <strong><?php echo $errorMsg; ?></strong>
        </div>
      <?php
      }
      ?>
    </div>
    <div class="col-sm-6">
      <h2 class="m-3">Iniciar sesión</h2>
      <form method="post" action="../controllers/login.controller.php">
        <div class="form-group">
          <input type="text" name="txt_username" class="form-control m-1" placeholder="Ingrese usuario">
        </div>
        <div class="form-group">
          <input type="password" name="txt_password" class="form-control m-1" placeholder="Ingrese contraseña">
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-success m-3" value="Ingresar">
        </div>
      </form>
    </div>
  </main>
  <footer>
    <div class="col-sm-12">
      <h5>¿No tienes una cuenta?</h5>
      <a href="/sitrad/register">
        <p class="text-success fw-bold">Regístrate</p>
      </a>
    </div>
  </footer>
  <script src="/sitrad/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>