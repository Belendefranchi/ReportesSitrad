<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
	<title>Iniciar Sesión</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
	<!-- <script src="js/jquery-1.12.4-jquery.min.js"></script> -->
</head>
<body class="text-center" style="background-color:#D7E1D6">

  <?php

  session_start();

/* ########################################## FUNCIONES ########################################## */

  function redirectToDashboard($role) {
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

  function createDatabaseTable($dbUsers) {
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

  function loginUser($dbUsers, $username, $password) {
    try {
      $query = "SELECT username, password, role FROM data WHERE username=:f_username";
      $stmt = $dbUsers->prepare($query);
      $stmt->bindValue(":f_username", $username);
      $result = $stmt->execute();

      while ($row = $result->fetchArray()) {
        $db_username = $row["username"];
        $db_password = $row["password"];
        $db_role = $row["role"];
      }

      if($db_username !== null && $db_password !== null){
        if($username == $db_username){
          if (password_verify($password, $db_password)) {
            echo $db_role;
          } else {
            echo "no coinciden";
          }
        } else {
          echo "El usuario no existe";
        }
      }
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
    }


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
      $errorMsg = "Por favor ingrese el nombre de usuario y la contraseña";
    } elseif (empty($username)) {
      $errorMsg = "Por favor ingrese el usuario";
    } elseif (empty($password)) {
      $errorMsg = "Por favor ingrese la contraseña";
    } elseif ($username && $password) {
      $role = loginUser($dbUsers, $username, $password);

      if ($role !== false) {
        switch ($role) {
          case "admin":
            $_SESSION["admin_login"] = $username;
            $loginMsg = "Admin: Inició sesión con éxito";
            redirectToDashboard($role);
            break;

          case "user":
            $_SESSION["user_login"] = $username;
            $loginMsg = "¡Inició sesión con éxito!";
            redirectToDashboard($role);
            break;

          default:
            echo $role;
            $errorMsg = "1: Usuario o contraseña incorrectos";
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
  <main class="d-flex flex-column align-items-center m-5">
    <div class="col-sm-8">
      <?php
      if (isset($errorMsg)) {
      ?>
      <div class="alert alert-danger">
        <strong><?php echo $errorMsg; ?></strong>
      </div>
      <?php
      }
      if (isset($loginMsg)) {
      ?>
      <div class="alert alert-success">
        <strong>ÉXITO ! <?php echo $loginMsg; ?></strong>
      </div>
      <?php
      }
      ?>
    </div>
    <div class="col-sm-8">
      <h2>Iniciar sesión</h2>
      <form method="post" action="">
        <div class="form-group">
          <div class="col-sm-12">
            <input type="text" name="txt_username" class="form-control m-1" placeholder="Ingrese usuario">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-12">
            <input type="password" name="txt_password" class="form-control m-1" placeholder="Ingrese contraseña">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-12">
            <input type="submit" class="btn btn-success m-3" value="Ingresar"></input>
          </div>
        </div>
      </form>
    </div>
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
</body>

</html>