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
    class DataBase extends SQLite3
    {
        function __construct()
        {
            $this->open('basesOrigen/datos89.db');
        }
    }
    echo '<a href="./index.php"><button>Volver</button></a>';
    echo '<h2>Tabla instrumentos</h2>';
    echo '<table width="80%">';
    echo '<tr>';
    echo '<td class="primera_fila">Id</td>';
    echo '<td class="primera_fila">Modelo</td>';
    echo '<td class="primera_fila">Descripción</td>';
    echo '<td class="primera_fila">Fecha y hora</td>';
    echo '<td class="primera_fila">Alarma 1 High</td>';
    echo '<td class="primera_fila">Alarma 1 Low</td>';
    echo '</tr>';

    $db = new DataBase();
    $query="SELECT * FROM instrumentos";
    $result = $db->query($query);
    while ($data=$result->fetchArray()){
        echo "<tr>";
        echo "<td>" . $data["id"] . "</td>";
        echo "<td>" . $data["modelo"] . "</td>";
        echo "<td>" . $data["descricao"] . "</td>";
        echo "<td>" . date('d/m/Y H:i:s', ($data["datacad"]-25569) * 86400) . "</td>";
        echo "<td>" . $data["alarme1H"] . "</td>";
        echo "<td>" . $data["alarme1L"] . "</td>";
        echo "</tr>";
    };
    $db->close();
    echo "</table>";
  ?>
</body>
</html>