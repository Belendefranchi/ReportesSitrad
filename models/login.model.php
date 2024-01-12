<?php

require "sitrad/configs/config.php";

$dbUsers = new SQLite3($pathDestino.'/dbUsers.db');
if (!$dbUsers) {
  die("Error al abrir la base de datos: " . $dbUsers->lastErrorMsg());
}

function createDatabaseTable($dbUsers){

  $tableExistsQuery = "SELECT name FROM sqlite_master WHERE type='table' AND name='data'";
  $tableExistsResult = $dbUsers->querySingle($tableExistsQuery);

  if (!$tableExistsResult) {
    $createTableQuery = "
        CREATE TABLE IF NOT EXISTS data (
          idx INTEGER PRIMARY KEY NOT NULL,
          username TEXT,
          password TEXT,
          role TEXT
        )
      ";

    try {
      $result = $dbUsers->exec($createTableQuery);
      $dbUsers->close();
      if ($result === false) {
        throw new Exception("Error al crear la tabla: " . $dbUsers->lastErrorMsg());
      }
    } catch (Exception $e) {
      die("Error: " . $e->getMessage());
    }
  }
}

function loginUser($dbUsers, $username, $password){
  try {
    $query = "SELECT username, password, role FROM data WHERE username=:f_username";
    $stmt = $dbUsers->prepare($query);
    $stmt->bindValue(":f_username", $username, SQLITE3_TEXT);
    $result = $stmt->execute();

    while ($row = $result->fetchArray()) {
      $db_username = $row["username"];
      $db_password_hash = $row["password"];
      $db_role = $row["role"];
      if ($username == $db_username && $password == password_verify($password, $db_password_hash)) {
        $_SESSION["username"] = $db_username;
        $_SESSION["role"] = $db_role;
        $dbUsers->close();
      }
      return $db_role;
    }
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
  }
}

?>


