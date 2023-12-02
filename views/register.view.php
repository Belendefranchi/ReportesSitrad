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

	require "sitrad/controllers/register.controller.php";

	?>

  <header style="background-color:white">
    <img class="img-fluid" src="public/portada.jpg" alt="portada">
  </header>
  <main class="d-flex flex-column align-items-center m-3">
    <div class="col-sm-6">

      <?php
      if (isset($message)) {
      ?>

      <div class="alert alert-success p-2">
        <strong><?php echo $message; ?></strong>
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
		<div class="col-sm-12">
      <a href="/sitrad">
        <p class="text-success fw-bold">Volver</p>
      </a>
    </div>
	</footer>
	<script src="/sitrad/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>