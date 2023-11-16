<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
set_time_limit(300);
ini_set('precision', 15);

$path = "sitrad/basesOrigen";
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
	$dbFechas = new SQLite3('sitrad/basesDestino/dbFechas.db');

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

			require ("./model/dbFechas.model.php");

		}
	}
	// Cierra la conexión a la base de datos
	$dbFechas->close();
} else {
	echo "No se encontraron archivos en la ruta.";
};
?>
