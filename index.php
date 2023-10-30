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

//##//
		$path= "./basesOrigen";
		$dir=opendir($path);

		while ($file = readdir($dir)) {
			if ($file == "." || $file == ".."){
				echo " ";
			}else{
				$files[$file] = filemtime($path."/".$file);
			}
		}
		//Ordena desde el más reciente.
		arsort ($files);
		print_r($files);

		$i=0;
		foreach ($files as $file => $timestamp) {
			if ( $i < 1 ) {

				preg_match('/(\d+)/', $file, $number);
				$fileNumber = $number[0];

				echo "<p>" . date("d-m-y H:i:s", $timestamp) . "</p>";
				echo "<p>$timestamp</p>";
				echo "<a href=\"$path/$file\">$path/$file</a>";
				echo "<p>file: $file</p>";
				echo "<p>fileNumber: $fileNumber</p>";
				echo "<p>$path/$file</p>";
			};
			$i++;
		};

		// Conexión a la base de datos de origen
		$dbOrigen = new SQLite3($path . "/" . $file);






		$queryTc900Inicial = "SELECT data FROM tc900log ORDER BY data LIMIT 1";
		$resultTc900Inicial = $dbOrigen->query($queryTc900Inicial);

		while ($row=$resultTc900Inicial->fetchArray()){
			$tc900DataInicial = $row["data"];
      echo "<p>Primero tc900log: " . $row["data"] . "</p>";
    };

		$queryTc900Final = "SELECT data FROM tc900log ORDER BY data DESC LIMIT 1";
		$resultTc900Final = $dbOrigen->query($queryTc900Final);

		while ($row = $resultTc900Final->fetchArray()){
			$tc900DataFinal = $row["data"];
      echo "<p>Último tc900log: " . $row["data"] . "</p>";
    };





		$queryMt512eInicial="SELECT data FROM mt512elog ORDER BY data LIMIT 1";
		$resultMt512etInicial = $dbOrigen->query($queryMt512eInicial);

		while ($row=$resultMt512etInicial->fetchArray()){
			$mt512eDataInicial = $row["data"];
      echo "<p>Primero mt512elog: " . $row["data"] . "</p>";
    };

		$queryMt512eFinal = "SELECT data FROM mt512elog ORDER BY data DESC LIMIT 1";
		$resultMt512eFinal = $dbOrigen->query($queryMt512eFinal);

		while ($row = $resultMt512eFinal->fetchArray()){
			$mt512eDataFinal = $row["data"];
      echo "<p>Último mt512elog: " . $row["data"] . "</p>";
    };


    $dbFechas = new SQLite3('basesDestino/dbFechas.db');
    $createTableQuery = "
        CREATE TABLE IF NOT EXISTS data (
            file TEXT,
            mt512eDataInicial REAL,
						mt512eDataFinal REAL,
						tc900DataInicial REAL,
						tc900DataFinal REAL
        )
    ";
		// Ejecutar la sentencia para crear la tabla
		$dbFechas->exec($createTableQuery);


		$queryInsert="INSERT INTO data (file, mt512eDataInicial, mt512eDataFinal, tc900DataInicial, tc900DataFinal) VALUES (:file, :mt512eDataInicial, :mt512eDataFinal, :tc900DataInicial, :tc900DataFinal)";

		$stmt = $dbFechas->prepare($queryInsert);
		$stmt->bindValue(':file', "datos$fileNumber.db", SQLITE3_TEXT);
		$stmt->bindValue(':mt512eDataInicial', $mt512eDataInicial, SQLITE3_FLOAT);
		$stmt->bindValue(':mt512eDataFinal', $mt512eDataFinal, SQLITE3_FLOAT);
		$stmt->bindValue(':tc900DataInicial', $tc900DataInicial, SQLITE3_FLOAT);
		$stmt->bindValue(':tc900DataFinal', $tc900DataFinal, SQLITE3_FLOAT);
		$stmt->execute();

		// Realizar la consulta en la base de datos de origen
/* 		$query2="SELECT * data FROM data ORDER BY data DESC";
		$result2 = $dbFechas->query($query);

		while ($row=$result2->fetchArray()){

			//Mostrar en pantalla los datos originales
			echo "<tr>";
			echo "<td>" . $row["file"] . "</td>";
			echo "<td>" . $row["dataInicial"] . "</td>";
			echo "<td>" . $row["dataFinal"] . "</td>";
			echo "</tr>";
		}; */
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