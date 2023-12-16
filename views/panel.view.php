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
  <table width="80%">
    <tr>
    <select class="form-select" aria-label="Default select example">
      <option selected>Id</option>
      
      <option value="1">One</option>
      <option value="2">Two</option>
      <option value="3">Three</option>
    </select>
      <td class="encabezado"><input type="text">Id</td>
      <td class="encabezado"><input type="text">Modelo</td>
      <td class="encabezado"><input type="text">Sensor</td>
      <td class="encabezado"><input type="text">Nombre</td>
    </tr>
    <?php
    require "sitrad/controllers/panel.controller.php";
    ?>
  </table>

  <script src="/sitrad/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>