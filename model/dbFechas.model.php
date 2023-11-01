<?php
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
?>