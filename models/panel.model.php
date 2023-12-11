<?php

function createDatabaseTable($dbSensores){

  $tableExistsQuery = "SELECT name FROM sqlite_master WHERE type='table' AND name='sensores'";
  $tableExistsResult = $dbSensores->querySingle($tableExistsQuery);

  if (!$tableExistsResult) {
    $createTableQuery = "
    CREATE TABLE IF NOT EXISTS sensores (
        id INTEGER,
        modelo TEXT,
        tipo TEXT,
        nombre TEXT
      )
    ";

    try {
      $result = $dbSensores->exec($createTableQuery);
      $dbSensores->close();
      if ($result === false) {
        throw new Exception("Error al crear la tabla: " . $dbSensores->lastErrorMsg());
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

    $queryInsert = "INSERT INTO sensores (id, modelo, tipo, nombre) VALUES (:id, :modelo, :tipo, :nombre)";

    $stmt = $dbSensores->prepare($queryInsert);
    $stmt->bindValue(':id', $id, SQLITE3_TEXT);
    $stmt->bindValue(':modelo', $modelo, SQLITE3_FLOAT);
    $stmt->bindValue(':tipo', $tipo, SQLITE3_TEXT);
    $stmt->bindValue(':nombre', $nombre, SQLITE3_TEXT);
    $stmt->execute();

    echo '<tr>';
    echo '<td>'.$data["id"].'</td>';
    echo '<td>'.$data["modelo"].'</td>';
    echo '<td>'.$tipo.'</td>';
    echo '<td><a href="./reportes.controller.php?sensorId='.$data["id"].'">'.$data["descricao"].'</td>';
    echo '</tr>';
  };
}

?>