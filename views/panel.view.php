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

  <a href="/sitrad/logout">Salir</a>
  <h2>Sensores Disponibles</h2>

  <?php

  require "sitrad/controllers/panel.controller.php";

  echo '<tr>';
  echo '<td><input type="text" value=' . $data["id"] . '></td>';
  echo '<td><input type="text" value=' . $data["modelo"] . '></td>';
  echo '<td><input type="text" value=' . $data["tipo"] . '></td>';
  echo '<td><input type="checkbox"></td>';
  echo '<td><a href="./controllers/reportes.controller.php?sensorId=' . $data["id"] . '&sensorTipo=' . $data["tipo"] . '">' . $data["nombre"] . '</a></td>';
  echo '</tr>';

  ?>

  <script src="/sitrad/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>