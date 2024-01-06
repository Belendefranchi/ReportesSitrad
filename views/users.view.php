<?php
session_start();

if (!isset($_SESSION['role'])) {
  header('Location: /sitrad');
  exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de control</title>
  <link rel="stylesheet" href="/sitrad/node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="icon" href="/sitrad/public/favicon.ico" type="image/x-icon">
</head>
<body class="text-center" style="background-color:#D7E1D6">
  <header class="d-flex m-3">
    <div class="col-sm-6 text-start">
      <p><?php echo "Rol: " . $_SESSION['role']?></p>
      <p><?php echo "Nombre de usuario: " . $_SESSION['username']?></p>
    </div>
    <div class="d-flex flex-column col-sm-6 text-end">
      <a href="/sitrad/panel">Administrar sensores</a>
      <a href="/sitrad/logout">Cerrar sesión</a>
    </div>
  </header>
  <main class="d-flex flex-column align-items-center m-3">

    <h2>Administración de Usuarios</h2>
    <p>Tabla editable, haga click en el valor a editar y luego de modificarlo haga click en un lugar vacío.</p>

  </main>
  <footer>

  </footer>
  <script src="/sitrad/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>