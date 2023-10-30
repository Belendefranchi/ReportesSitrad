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
    echo '<h2>Tabla tc900</h2>';
    echo '<table width="80%">';
    echo '<tr>';
    echo '<td class="primera_fila">Id</td>';
    echo '<td class="primera_fila">Temperatura 1</td>';
    echo '<td class="primera_fila">Temperatura 2</td>';
    echo '<td class="primera_fila">Fecha y hora</td>';
    echo '</tr>';

    $db = new DataBase();
    $query="SELECT id, Temp1, Temp2, data FROM tc900log ORDER BY data DESC";
    $result = $db->query($query);
    while ($row=$result->fetchArray()){
      echo "<tr>";
      echo "<td>" . $row["id"] . "</td>";
      echo "<td>" . $row["Temp1"]/10 . "</td>";
      echo "<td>" . $row["Temp2"]/10 . "</td>";
      echo "<td>" . date('d/m/Y H:i:s', ($row["data"]-25569) * 86400) . "</td>";
      echo "</tr>";
    };
    $db->close();
    echo "</table>";
  ?>
</body>
</html>