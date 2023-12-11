<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="/sitrad/node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="icon" href="/sitrad/public/favicon.ico" type="image/x-icon">
</head>

<body class="text-center" style="background-color:#D7E1D6">

  <?php

  require "sitrad/controllers/login.controller.php";

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
      <h2 class="m-3">Iniciar sesión</h2>
      <form method="post">
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