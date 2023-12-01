<?php

function createDatabaseTable($dbUsers){
  $tableExistsQuery = "SELECT name FROM sqlite_master WHERE type='table' AND name='data'";
  $tableExistsResult = $dbUsers->querySingle($tableExistsQuery);

  if (!$tableExistsResult) {
    $createTableQuery = "
        CREATE TABLE IF NOT EXISTS data (
          username TEXT,
          password TEXT,
          role TEXT
        )
      ";

    try {
      $result = $dbUsers->exec($createTableQuery);

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
    $stmt->bindValue(":f_username", $username);
    $result = $stmt->execute();

    while ($row = $result->fetchArray()) {
      $db_username = $row["username"];
      $db_password = $row["password"];
      $db_role = $row["role"];
      if ($username == $db_username && $password == $db_password) {
        $dbUsers->close();
        return $db_role;
      }
    }
    $dbUsers->close();
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
  }
}

?>