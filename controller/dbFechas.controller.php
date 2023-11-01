<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
set_time_limit(300);
ini_set('precision', 15);

$path = "./basesOrigen";
$dir = opendir($path);

// Obtén todos los archivos en la ruta
$files = array();
while ($file = readdir($dir)) {
	if ($file != "." && $file != "..") {
		$files[] = $file;
	}
}
closedir($dir);

if (!empty($files)) {
	// Conecta a la base de datos de destino
	$dbFechas = new SQLite3('basesDestino/dbFechas.db');

	// Consulta para verificar la existencia de la tabla 'data'
	$tableExistsQuery = "SELECT name FROM sqlite_master WHERE type='table' AND name='data'";
	$tableExistsResult = $dbFechas->querySingle($tableExistsQuery);

	if (!$tableExistsResult) {
		// Si la tabla 'data' no existe, créala
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
	}

	// Recorre todos los archivos en la ruta
	foreach ($files as $file) {
		preg_match('/(\d+)/', $file, $number);
		$fileNumberOrigen = $number[0];

		// Verifica si el archivo ya está en la tabla 'data'
		$checkExistsQuery = "SELECT COUNT(*) as count FROM data WHERE file = :file";
		$stmt = $dbFechas->prepare($checkExistsQuery);
		$stmt->bindValue(':file', $file, SQLITE3_TEXT);
		$result = $stmt->execute();
		$row = $result->fetchArray();

		if ($row['count'] == 0) {
			// El archivo no existe en la tabla 'data', realiza inserción

			$dbOrigen = new SQLite3($path . "/" . $file);

			/* ################################ OBTENER RANGO PARA MT512E ################################ */

			$mt512eDataInicial = null;
			$mt512eDataFinal = null;

			$query = "SELECT data FROM mt512elog ORDER BY data LIMIT 1";
			$stmt = $dbOrigen->prepare($query);
			$result = $stmt->execute();
			while ($row = $result->fetchArray()) {
				$mt512eDataInicial = $row["data"];
				echo "<p>Primero mt512elog: " . $row["data"] . "</p>";
			};

			$query = "SELECT data FROM mt512elog ORDER BY data DESC LIMIT 1";
			$stmt = $dbOrigen->prepare($query);
			$result = $stmt->execute();
			while ($row = $result->fetchArray()) {
				$mt512eDataFinal = $row["data"];
				echo "<p>Último mt512elog: " . $row["data"] . "</p>";
			};

			/* ########################################################################################## */

			/* ################################ OBTENER RANGO PARA TC900 ################################ */

			$tc900DataInicial = null;
			$tc900DataFinal = null;

			$query = "SELECT data FROM tc900log ORDER BY data LIMIT 1";
			$stmt = $dbOrigen->prepare($query);
			$result = $stmt->execute();
			while ($row = $result->fetchArray()) {
				$tc900DataInicial = $row["data"];
				echo "<p>Primero tc900log: " . $row["data"] . "</p>";
			};

			$query = "SELECT data FROM tc900log ORDER BY data DESC LIMIT 1";
			$stmt = $dbOrigen->prepare($query);
			$result = $stmt->execute();
			while ($row = $result->fetchArray()) {
				$tc900DataFinal = $row["data"];
				echo "<p>Último tc900log: " . $row["data"] . "</p>";
			};

			$dbOrigen->close();

			/* ########################################################################################## */

			$queryInsert = "INSERT INTO data (file, mt512eDataInicial, mt512eDataFinal, tc900DataInicial, tc900DataFinal) VALUES (:file, :mt512eDataInicial, :mt512eDataFinal, :tc900DataInicial, :tc900DataFinal)";

			$stmt = $dbFechas->prepare($queryInsert);
			$stmt->bindValue(':file', $file, SQLITE3_TEXT);
			$stmt->bindValue(':mt512eDataInicial', $mt512eDataInicial, SQLITE3_FLOAT);
			$stmt->bindValue(':mt512eDataFinal', $mt512eDataFinal, SQLITE3_FLOAT);
			$stmt->bindValue(':tc900DataInicial', $tc900DataInicial, SQLITE3_FLOAT);
			$stmt->bindValue(':tc900DataFinal', $tc900DataFinal, SQLITE3_FLOAT);
			$stmt->execute();
		}
	}

	// Cierra la conexión a la base de datos
	$dbFechas->close();
} else {
	echo "No se encontraron archivos en la ruta.";
}
