<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
	<title>Iniciar Sesión</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
	<script src="js/jquery-1.12.4-jquery.min.js"></script>
</head>
<body class="text-center" style="background-color:#D7E1D6">
	<?php
	session_start();
	if (isset($_SESSION["admin_login"]))
	{
		header("location: /sitrad/panel");
	}
	if (isset($_SESSION["user_login"]))
	{
		header("location: /sitrad/reportes");
	}

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//if (isset($_REQUEST['btn_login'])) {
		$username	= $_POST["txt_username"];
		$password	= $_POST["txt_password"];

    // Conecta a la base de datos de destino
    $dbUsers = new SQLite3('sitrad/basesDestino/dbUsers.db');

    // Consulta para verificar la existencia de la tabla 'data'
    $tableExistsQuery = "SELECT name FROM sqlite_master WHERE type='table' AND name='data'";
    $tableExistsResult = $dbUsers->querySingle($tableExistsQuery);

    if (!$tableExistsResult) {
      // Si la tabla 'data' no existe, créala
      $createTableQuery = "
                  CREATE TABLE IF NOT EXISTS data (
                      username TEXT,
                      password TEXT,
                      role TEXT
                  )
              ";
      $dbUsers->exec($createTableQuery);
    }

		if (empty($username)) {
			$errorMsg[] = "Por favor ingrese el nombre de usuario";
		} else if (empty($password)) {
			$errorMsg[] = "Por favor ingrese la contraseña";
		} else if ($username and $password) {
			try {
        $query = "SELECT username, password, role FROM login WHERE username=:f_username AND password=:f_password";
				$stmt = $dbUsers->prepare($query);
				$stmt->bindValue(":f_username", $username);
				$stmt->bindValue(":f_password", $password);

        $result = $stmt->execute();
        while ($row = $result->fetchArray()) {
					$db_username = $row["username"];
					$db_password = $row["password"];
					$db_role = $row["role"];
				}
				if ($username != null and $password != null and $role = !null) {
					if ($row['count'] > 0) {
						if ($username == $db_username and $password == $db_password and $role == $db_role) {
							switch ($db_role) {
								case "admin":
									$_SESSION["admin_login"] = $username;
									$loginMsg = "Admin: Inició sesión con éxito";
									header("refresh:1; views/panel.view.php");
									break;

								case "user":
									$_SESSION["user_login"] = $username;
									$loginMsg = "¡Inició sesión con éxito!";
									header("refresh:1; views/reportes.view.php");
									break;

								default:
									$errorMsg[] = "Rol incorrecto";
							}
						} else {
							$errorMsg[] = "Correo electrónico o contraseña incorrectos";
						}
					} else {
						$errorMsg[] = "Usuario no encontrado";
					}
				} else {
					$errorMsg[] = "Correo electrónico o contraseña no ingresados";
				}
			} catch (PDOException $e) {
				$e->getMessage();
			}
    } else {
      $errorMsg[] = "correo electrónico o contraseña o rol incorrectos";
    }
  }
  ?>
  <header style="background-color:white">
    <img class="img-fluid" src="public/portada.jpg" alt="portada">
  </header>
  <main class="d-flex justify-content-center m-5">
    <div class="col-sm-6" id="map_section">
      <?php
      if (isset($errorMsg)) {
        foreach ($errorMsg as $error) {
      ?>
          <div class="alert alert-danger">
            <strong><?php echo $error; ?></strong>
          </div>
        <?php
        }
      }
      if (isset($loginMsg)) {
        ?>
        <div class="alert alert-success">
          <strong>ÉXITO ! <?php echo $loginMsg; ?></strong>
        </div>
      <?php
      }
      ?>

      <div class="d-flex flex-column justify-content-center align-content-center">
        <h2>Iniciar sesión</h2>
        <form class="form-row" method="post" action="">
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
              <input type="submit" class="btn btn-success m-3" name="btn_login" value="Iniciar Sesión"></input>
            </div>
          </div>
        </form>
      </div>
    </div>
  </main>
  <footer>
    <div class="col-sm-12">
      <h5>¿No tenés una cuenta?</h5>
      <a href="registro.php">
        <p class="text-info">Registrate</p>
      </a>
    </div>
  </footer>
</body>

</html>