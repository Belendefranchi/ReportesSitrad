<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Reportes SITRAD</title>
<!-- <link rel="stylesheet" type="text/css" href="hoja2.css"> -->
<link rel="icon" href="favicon.ico">
</head>

<body>
  <?php

  echo '<a href="./index.php"><button>Volver</button></a>';
  echo '<h2>Tabla mt512e</h2>';

  $dbSensores = new SQLite3('./basesDestino/dbSensores.db');

  $tableExistsQuery = "SELECT name FROM sqlite_master WHERE type='table' AND name='sensores'";
	$tableExistsResult = $dbSensores->querySingle($tableExistsQuery);

	if (!$tableExistsResult) {

    echo '<table width="80%">';
    echo '<tr>';
    echo '<td class="primera_fila">Id</td>';
    echo '<td class="primera_fila">Modelo</td>';
    echo '<td class="primera_fila">Sensor</td>';
    echo '<td class="primera_fila">Nombre</td>';
    echo '</tr>';
    // Conecta a la base de datos sensores
    $dbSensores = new SQLite3('./basesDestino/dbSensores.db');

    $createTableQuery = "
      CREATE TABLE IF NOT EXISTS sensores (
          id INTEGER,
          modelo TEXT,
          tipo TEXT,
          nombre TEXT
        )
      ";

    // Ejecutar la sentencia para crear la tabla
    $dbSensores->exec($createTableQuery);

    /* ############################### OBTENER EL ULTIMO ARCHIVO MODIFICADO ############################### */
    $path = "./basesOrigen";
    $dir = opendir($path);

    while ($file = readdir($dir)) {
      if ($file == "." || $file == "..") {
        echo ' ';
      } else {
        $files[$file] = filemtime($path . "/" . $file);
      }
    }

    if (!empty($files)) {
      //Ordena desde el más reciente.
      arsort($files);

      $i = 0;
      foreach ($files as $file => $timestamp) {
        if ($i < 1) {

          $dbOrigen = new SQLite3($path . "/" . $file);
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
            echo '<td>' . $data["id"] . '</td>';
            echo '<td>' . $data["modelo"] . '</td>';
            echo '<td>' . $tipo . '</td>';
            echo '<td>' . $data["descricao"] . '</td>';
            echo '</tr>';
          };
          $dbOrigen->close();
          $i++;
        }
      };
      echo '</table>';
    } else {
    echo 'No se encontraron archivos en el directorio.';
    };

  } else {

    echo '<table width="80%">';
    echo '<tr>';
    echo '<td class="primera_fila">Id</td>';
    echo '<td class="primera_fila">Modelo</td>';
    echo '<td class="primera_fila">Sensor</td>';
    echo '<td class="primera_fila">Nombre</td>';
    echo '</tr>';

    $query="SELECT id, modelo, tipo, nombre FROM sensores";
    $result = $dbSensores->query($query);

    while ($data=$result->fetchArray()){
      echo '<tr>';
      echo '<td>' . $data["id"] . '</td>';
      echo '<td>' . $data["modelo"] . '</td>';
      echo '<td>' . $data["tipo"] . '</td>';
      echo '<td>' . $data["nombre"] . '</td>';
      echo '</tr>';
    };
    echo '</table>';
  }

  $dbSensores->close();

  ?>
</body>
</html>