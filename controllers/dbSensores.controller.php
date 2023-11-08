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

  echo '<input type="button" value="Volver" onClick="window.history.go(-1)">';
  echo '<h2>Sensores Disponibles</h2>';

  $dbSensores = new SQLite3('./basesDestino/dbSensores.db');

  $tableExistsQuery = "SELECT name FROM sqlite_master WHERE type='table' AND name='sensores'";
	$tableExistsResult = $dbSensores->querySingle($tableExistsQuery);

	if (!$tableExistsResult) {

    echo '<table width="80%">';
    echo '<tr>';
    echo '<td class="encabezado">Id</td>';
    echo '<td class="encabezado">Modelo</td>';
    echo '<td class="encabezado">Sensor</td>';
    echo '<td class="encabezado">Nombre</td>';
    echo '</tr>';

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
          require ("./models/dbSenores.model.php");
        };
        $dbOrigen->close();
        $i++;
      }
    };
    echo '</table>';
  } else {

    echo '<table width="80%">';
    echo '<tr>';
    echo '<td class="encabezado">Id</td>';
    echo '<td class="encabezado">Modelo</td>';
    echo '<td class="encabezado">Tipo Sensor</td>';
    echo '<td class="encabezado">Reporte habilitado</td>';
    echo '<td class="encabezado">Nombre</td>';
    echo '</tr>';

    $query="SELECT id, modelo, tipo, nombre FROM sensores";
    $result = $dbSensores->query($query);

    while ($data=$result->fetchArray()){
      include ("./views/dbSensores.view.php");
    };
    echo '</table>';
  }

  $dbSensores->close();

  ?>
</body>
</html>