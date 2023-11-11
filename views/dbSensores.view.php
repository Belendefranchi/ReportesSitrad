<?php
  echo '<tr>';
  echo '<td><input type="text" value=' . $data["id"] . '></td>';
  echo '<td><input type="text" value=' . $data["modelo"] . '></td>';
  echo '<td><input type="text" value=' . $data["tipo"] . '></td>';
  echo '<td><input type="checkbox"></td>';
  echo '<td><a href="./controllers/reportes.controller.php?sensorId=' . $data["id"] . '&sensorTipo=' . $data["tipo"] . '">' . $data["nombre"] . '</a></td>';
  echo '</tr>';
?>