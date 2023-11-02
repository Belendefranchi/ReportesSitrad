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
		date_default_timezone_set('America/Argentina/Buenos_Aires');
    set_time_limit(300);
    ini_set('precision', 15);

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
    $dbOrigen = new SQLite3('basesOrigen/datos89.db');

    // Conexión a la base de datos de destino
    $dbDestino = new SQLite3('basesDestino/datos89.db');

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
    //$result2 = $dbDestino->query($query);


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
    $dbOrigen->close();
    $dbDestino->exec('VACUUM');
    $dbDestino->close();
    echo "</table>";
  ?>
</body>
</html>