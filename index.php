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


		/* ############################### OBTENER EL ULTIMO ARCHIVO MODIFICADO ############################### */
		$path= "./basesOrigen";
		$dir=opendir($path);

		while ($file = readdir($dir)) {
			if ($file == "." || $file == ".."){
				echo " ";
			}else{
				$files[$file] = filemtime($path."/".$file);
			}
		}

		if (!empty($files)) {
			//Ordena desde el más reciente.
			arsort ($files);


			$i=0;
			foreach ($files as $file => $timestamp) {
				if ( $i < 1 ) {

					preg_match('/(\d+)/', $file, $number);
					$fileNumberOrigen = $number[0];

					echo "RUTA: <a href=\"$path/$file\">$path/$file</a>";
					echo "<p>file: $file</p>";
					echo "<p>fileNumber: $fileNumberOrigen</p>";

					$dbOrigen = new SQLite3($path . "/" . $file);

					/* ########################## OBTENER EL ULTIMO ARCHIVO INSERTADO EN DBFECHAS ######################### */

					$dbFechas = new SQLite3('basesDestino/dbFechas.db');

					// Consulta para verificar la existencia de la tabla 'data'
					$tableExistsQuery = "SELECT name FROM sqlite_master WHERE type='table' AND name='data'";
					$tableExistsResult = $dbFechas->querySingle($tableExistsQuery);

					if ($tableExistsResult) {

							$query = "SELECT file FROM data ORDER BY ROWID DESC LIMIT 1;";
							$result = $dbFechas->query($query);

							if ($result) {
									while ($row = $result->fetchArray()) {
											$lastFileInserted = $row["file"];
											echo "<p>Último archivo insertado: " . $row["file"] . "</p>";
									}
									preg_match('/(\d+)/', $lastFileInserted, $number);
									$fileNumberInserted = $number[0];
									echo "<p>lastFileInserted: $lastFileInserted</p>";
									echo "<p>fileNumberInserted: $fileNumberInserted</p>";
							} else {
								echo "<p>No se pudieron recuperar datos de la tabla.</p>";
							}
					} else {
						$fileNumberInserted = 0;
						echo "<p>La tabla no existe en la base de datos.</p>";
					}

					/* ########################## COMPARAR ARCHIVO ORIGEN CON ARCHIVO INSERTADO ########################## */

					if ( $fileNumberInserted < $fileNumberOrigen) {

						/* ################################### OBTENER RANGO PARA MT512E ################################### */

						$mt512eDataInicial = null;
						$mt512eDataFinal = null;

						$query="SELECT data FROM mt512elog ORDER BY data LIMIT 1";
						$result = $dbOrigen->query($query);
						while ($row = $result->fetchArray()){
							$mt512eDataInicial = $row["data"];
							echo "<p>Primero mt512elog: " . $row["data"] . "</p>";
						};

						$query = "SELECT data FROM mt512elog ORDER BY data DESC LIMIT 1";
						$result = $dbOrigen->query($query);
						while ($row = $result->fetchArray()){
							$mt512eDataFinal = $row["data"];
							echo "<p>Último mt512elog: " . $row["data"] . "</p>";
						};

						/* ################################################################################################# */

						/* ################################### OBTENER RANGO PARA TC900 ################################### */

						$tc900DataInicial = null;
						$tc900DataFinal = null;

						$query = "SELECT data FROM tc900log ORDER BY data LIMIT 1";
						$result = $dbOrigen->query($query);
						while ($row = $result->fetchArray()){
							$tc900DataInicial = $row["data"];
							echo "<p>Primero tc900log: " . $row["data"] . "</p>";
						};

						$query = "SELECT data FROM tc900log ORDER BY data DESC LIMIT 1";
						$result = $dbOrigen->query($query);
						while ($row = $result->fetchArray()){
							$tc900DataFinal = $row["data"];
							echo "<p>Último tc900log: " . $row["data"] . "</p>";
						};

						$dbOrigen->close();

						/* ################################################################################################# */

						/* ############################## INSERTAR LOS RANGOS EN TABLA FECHAS ############################## */

						$createTableQuery = "
								CREATE TABLE IF NOT EXISTS data (
										file TEXT,
										mt512eDataInicial REAL,
										mt512eDataFinal REAL,
										tc900DataInicial REAL,
										tc900DataFinal REAL
								)
						";
						$dbFechas->exec($createTableQuery);

						$queryInsert="INSERT INTO data (file, mt512eDataInicial, mt512eDataFinal, tc900DataInicial, tc900DataFinal) VALUES (:file, :mt512eDataInicial, :mt512eDataFinal, :tc900DataInicial, :tc900DataFinal)";

						$stmt = $dbFechas->prepare($queryInsert);
						$stmt->bindValue(':file', $file, SQLITE3_TEXT);
						$stmt->bindValue(':mt512eDataInicial', $mt512eDataInicial, SQLITE3_FLOAT);
						$stmt->bindValue(':mt512eDataFinal', $mt512eDataFinal, SQLITE3_FLOAT);
						$stmt->bindValue(':tc900DataInicial', $tc900DataInicial, SQLITE3_FLOAT);
						$stmt->bindValue(':tc900DataFinal', $tc900DataFinal, SQLITE3_FLOAT);
						$stmt->execute();

						/* ################################################################################################# */

					};
					$i++;
				}
			};
		} else {
			echo "No se encontraron archivos en el directorio.";
		}
	?>

	<h1>Reportes SITRAD v1.0.0</h1>
	<div>
		<a href="./instrumentos.php"><button>Instrumentos</button></a>
		<a href="./mt512e.php"><button>mt512e</button></a>
		<a href="./tc900.php"><button>tc900</button></a>
	</div>
	<div>
		<a href="./panel.php"><button>Panel</button></a>
	</div>
	<!-- <p>ReportesSITRAD v1.0.0</p> -->
</body>

</html>