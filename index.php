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
		require ("./controllers/dbFechas.controller.php");
	?>

	<h1>Reportes SITRAD</h1>
	<div>
		<form method="POST" action="router.php">
        <input type="hidden" name="controller" value="instrumentos">
        <input type="submit" value="Instrumentos">
    </form>
		<form method="POST" action="router.php">
        <input type="hidden" name="controller" value="mt512e">
        <input type="submit" value="mt512e">
    </form>
		<form method="POST" action="router.php">
        <input type="hidden" name="controller" value="tc900">
        <input type="submit" value="tc900">
    </form>
	</div>
	<div>
		<a href="./panel.php"><button>Panel</button></a>
	</div>
	<p>ReportesSITRAD v1.0.0</p>
</body>

</html>