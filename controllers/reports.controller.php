<?php

$sensorId = $_GET['sensorId'];
$sensorTipo = $_GET['sensorTipo'];

echo '<input type="button" value="Volver" onClick="window.history.go(-1)">';
echo '<h2>Reporte '.$sensorId.'</h2>';
echo "Id: $sensorId, Tipo: $sensorTipo";

if ($tipo === "mt512e"){

} else {

}

?>