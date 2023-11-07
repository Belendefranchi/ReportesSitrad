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
  $sensor = $_GET['sensor'];
  echo $sensor;

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
      echo " ";
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
        echo $file;
        $query="SELECT id, modelo, descricao FROM instrumentos";
        $result = $dbOrigen->query($query);
        while ($data=$result->fetchArray()){
          $id = $data["id"];
          $modelo = $data["modelo"];
          $nombre = $data["descricao"];

          $queryInsert = "INSERT INTO data (id, modelo, nombre) VALUES (:id, :modelo, :nombre)";

          $stmt = $dbSensores->prepare($queryInsert);
          $stmt->bindValue(':id', $id, SQLITE3_TEXT);
          $stmt->bindValue(':modelo', $modelo, SQLITE3_FLOAT);
          /* $stmt->bindValue(':tipo', $tipo, SQLITE3_FLOAT); */
          $stmt->bindValue(':nombre', $nombre, SQLITE3_FLOAT);
          $stmt->execute();

          echo "<tr>";
          echo "<td>" . $data["id"] . "</td>";
          echo "<td>" . $data["modelo"] . "</td>";
          echo "<td>" . $data["descricao"] . "</td>";
          echo "</tr>";
        };
        $dbOrigen->close();



        $i++;
      }
    };
  } else {
  echo "No se encontraron archivos en el directorio.";
  };


  echo '<a href="./index.php"><button>Volver</button></a>';
  echo '<h2>Tabla mt512e</h2>';
  echo '<table width="80%">';
  echo '<tr>';
  echo '<td class="primera_fila">Id</td>';
  echo '<td class="primera_fila">Temperatura</td>';
  echo '<td class="primera_fila">Fecha y hora</td>';
  echo '<td class="primera_fila">Fecha y hora</td>';
  echo '</tr>';

  // Conexión a la base de datos de origen
/*   $dbOrigen = new SQLite3('../basesOrigen/datos89.db');

  // Conexión a la base de datos de destino
  $dbDestino = new SQLite3('../basesDestino/datos89.db');

  // Sentencia SQL para crear la tabla en la base de datos de destino si no existe
  $createTableQuery = "
      CREATE TABLE IF NOT EXISTS mt512elog (
          id INTEGER,
          Temperatura TEXT,
          data REAL
      )
  ";

  // Ejecutar la sentencia para crear la tabla
  $dbDestino->exec($createTableQuery);

  // Realizar la consulta en la base de datos de origen
  $query="SELECT id, Temperatura, data FROM mt512elog ORDER BY data DESC";
  $result = $dbOrigen->query($query);


  // Recorrer los resultados y copiarlos a la base de datos de destino
  while ($row=$result->fetchArray()){

    $queryInsert="INSERT INTO mt512elog (id, Temperatura, data) VALUES (:id, :temp, :data)";
    $stmt = $dbDestino->prepare($queryInsert);
    $stmt->bindValue(':id', $row['id'], SQLITE3_INTEGER);
    $stmt->bindValue(':temp', $row['Temperatura'], SQLITE3_TEXT);
    $stmt->bindValue(':data', $row['data'], SQLITE3_FLOAT);
    $stmt->execute();

    //Mostrar en pantalla los datos originales
    echo "<tr>";
    echo "<td>" . $row["id"] . "</td>";
    echo "<td>" . $row["Temperatura"]/10 . "</td>";
    echo "<td>" . $row["data"] . "</td>";
    echo "<td>" . date('d/m/Y H:i:s', ($row["data"]-25569) * 86400) . "</td>";
    echo "</tr>";
  };
  echo "</table>";
  $dbOrigen->close();
  $dbDestino->exec('VACUUM');
  $dbDestino->close(); */
  ?>
</body>
</html>