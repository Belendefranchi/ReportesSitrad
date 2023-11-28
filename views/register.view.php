<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
	<title>Registrarse</title>
	<link rel="stylesheet" href="/sitrad/node_modules/bootstrap/dist/css/bootstrap.min.css">
</head>

<body class="text-center" style="background-color:#D7E1D6">

	<?php

	session_start();

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

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		/* if (isset($_REQUEST['btn_register'])) { */
		$username	= $_POST['txt_username'];
		$email		= $_POST['txt_email'];
		$password	= $_POST['txt_password'];
		$role		= "user";

		if (empty($username)) {
			$errorMsg[] = "Ingrese nombre de usuario";
		} else if (empty($email)) {
			$errorMsg[] = "Ingrese email";
		} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errorMsg[] = "Ingrese email valido";
		} else if (empty($password)) {
			$errorMsg[] = "Ingrese password";
		} else if (strlen($password) < 6) {
			$errorMsg = "Password mínimo 6 caracteres";
		} else {
			try {
				$stmt = $db->prepare("SELECT username, email FROM mainlogin
										WHERE username=:uname OR email=:uemail");
				$stmt->bindParam(":uname", $username);
				$stmt->bindParam(":uemail", $email);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				if ($row["username"] == $username) {
					$errorMsg[] = "Usuario ya existe";
				} else if ($row["email"] == $email) {
					$errorMsg[] = "Email ya existe";
				} else if (!isset($errorMsg)) {
					$stmt = $db->prepare("INSERT INTO mainlogin(username,email,password,role) VALUES(:uname,:uemail,:upassword,:urole)");
					$stmt->bindParam(":uname", $username);
					$stmt->bindParam(":uemail", $email);
					$stmt->bindParam(":upassword", $password);
					$stmt->bindParam(":urole", $role);

					if ($stmt->execute()) {
						$registerMsg = "Registro exitoso: Esperar página de inicio de sesión"; //Ejecuta consultas 
						header("refresh:2;index.php"); //Actualizar despues de 2 segundo a la portada
					}
				}
			} catch (PDOException $e) {
				echo $e->getMessage();
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
      <h2 class="m-3">Registrarse</h2>
			<form method="post" action="">
        <div class="form-group">
          <input type="text" name="txt_username" class="form-control m-1" placeholder="Ingrese usuario">
        </div>
        <div class="form-group">
          <input type="password" name="txt_password" class="form-control m-1" placeholder="Ingrese contraseña">
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-success m-3" value="Crear cuenta">
        </div>
      </form>
    </div>
  </main>
	<footer class="text-center">
		<img class="img-fluid" src="/img/2.png" style="width:290px !important; height:50px !important" alt="">
	</footer>
	<script src="/sitrad/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>