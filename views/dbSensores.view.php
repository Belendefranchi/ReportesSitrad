<?php
  echo '<tr>';
  echo '<td>' . $data["id"] . '</td>';
  echo '<td>' . $data["modelo"] . '</td>';
  echo '<td>' . $data["tipo"] . '</td>';
  echo '<td><input type="checkbox"></td>';
  echo '<td><a href="./controllers/reportes.controller.php?sensorId='.$data["id"].'&sensorTipo='.$data["tipo"].'">'.$data["nombre"].'</a></td>';
  echo '</tr>';
?>