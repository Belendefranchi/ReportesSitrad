<?php

function createDatabaseTable($dbSensores){

  $tableExistsQuery = "SELECT name FROM sqlite_master WHERE type='table' AND name='sensores'";
  $tableExistsResult = $dbSensores->querySingle($tableExistsQuery);

  if (!$tableExistsResult) {
    $createTableQuery = "
      CREATE TABLE IF NOT EXISTS sensores (
        idx INTEGER PRIMARY KEY NOT NULL,
        id INTEGER NOT NULL,
        modelo TEXT,
        tipo TEXT,
        nombre TEXT,
        checked TEXT,
        visible TEXT
      )
    ";

    try {
      $result = $dbSensores->exec($createTableQuery);
      if ($result === false) {
        throw new Exception("Error al crear la tabla" . $dbSensores->lastErrorMsg());
      }
    } catch (Exception $e) {
      die("Error: " . $e->getMessage());
    }
  }
}

function checkSensores($dbOrigen, $dbSensores){

  $query="SELECT id, modelo, descricao FROM instrumentos";
  $result = $dbOrigen->query($query);

  while ($data=$result->fetchArray()){
    $id = $data["id"];
    $modelo = $data["modelo"];
    $nombre = $data["descricao"];
    if($modelo === 73){
      $tipo = "mt512e";
    } else {
      $tipo = "tc900";
    };
    $checked = "true";
    $visible = "false";

    $checkExistsQuery = "SELECT COUNT(id) as count FROM sensores WHERE id= :id";
    $stmt = $dbSensores->prepare($checkExistsQuery);
    $stmt->bindValue(":id", $id, SQLITE3_TEXT);

    try {
      $resultExists = $stmt->execute();
      $row = $resultExists->fetchArray();
      if ($row['count'] == 0) {
        // El sensor no existe en la base de datos, realiza inserción
        insertSensor($dbSensores, $id, $modelo, $nombre, $tipo, $checked, $visible);
      }
    } catch (Exception $e) {
      die("Error: " . $e->getMessage());
    }

  }
}

function insertSensor($dbSensores, $id, $modelo, $nombre, $tipo, $checked, $visible){

  $queryInsert = "INSERT INTO sensores (id, modelo, tipo, nombre, checked, visible) VALUES (:id, :modelo, :tipo, :nombre, :checked, :visible)";

  $stmt = $dbSensores->prepare($queryInsert);
  $stmt->bindValue(':id', $id, SQLITE3_TEXT);
  $stmt->bindValue(':modelo', $modelo, SQLITE3_FLOAT);
  $stmt->bindValue(':tipo', $tipo, SQLITE3_TEXT);
  $stmt->bindValue(':nombre', $nombre, SQLITE3_TEXT);
  $stmt->bindValue(':checked', $checked, SQLITE3_TEXT);
  $stmt->bindValue(':visible', $visible, SQLITE3_TEXT);
  $stmt->execute();
}

function querySensores($dbSensores){

  $query = "SELECT idx, id, modelo, tipo, nombre, checked, visible FROM sensores";
  $result = $dbSensores->query($query);
  
  echo '<tr class="table-dark text-light">
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>
            <select class="form-select form-select-sm bg-transparent fw-bold text-light" id="nro">
              <option class="text-dark text-center" selected>Nombre</option>';
              while ($data=$result->fetchArray()){
                $nombre = preg_replace('/[\º]+/', 'º ',$data["nombre"]);
                echo '<option class="text-dark">'.$nombre.'</option>';
              }
  echo      '</select>
          </td>
          <td></td>
          <td></td>
        </tr>';
  
    while ($data=$result->fetchArray()){
    $idx = $data["idx"];
    $id = $data["id"];
    $modelo = $data["modelo"];
    $tipo = $data["tipo"];
    $nombre = preg_replace('/[\º]+/', 'º ',$data["nombre"]);
    $checked = $data["checked"];
    $visible = $data["visible"];

    echo '<tr>';
    echo '<td class="fw-bold">'.$idx.'</td>';
    echo '<td>'.$id.'</td>';
    echo '<td>'.$modelo.'</td>';
    echo '<td>'.$tipo.'</td>';
    echo '<td><a href="./reportes.controller.php?sensorId='.$id.'">'.$nombre.'</td>';
    if($checked === "true"){
      echo '<td><input class="form-check-input" type="checkbox" value="" id="c'.$id.'" checked></td>';
    }else{
      echo '<td><input class="form-check-input" type="checkbox" value="" id="c'.$id.'"></td>';
    };
    if($visible === "true"){
      echo '<td><input class="form-check-input" type="checkbox" value="" id="v'.$id.'" checked></td>';
    }else{
      echo '<td><input class="form-check-input" type="checkbox" value="" id="v'.$id.'"></td>';
    };
    echo '</tr>';
  }
}

?>