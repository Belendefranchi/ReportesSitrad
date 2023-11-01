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
		require ("./controller/dbFechas.controller.php")
	?>

	<h1>Reportes SITRAD</h1>
	<div>
		<a href="./instrumentos.php"><button>Instrumentos</button></a>
		<a href="./mt512e.php"><button>mt512e</button></a>
		<a href="./tc900.php"><button>tc900</button></a>
	</div>
	<div>
		<a href="./panel.php"><button>Panel</button></a>
	</div>
	<p>ReportesSITRAD v1.0.0</p>
</body>

</html>