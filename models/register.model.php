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
      $dbUsers->close();
      if ($result === false) {
        throw new Exception("Error al crear la tabla: " . $dbUsers->lastErrorMsg());
      }
    } catch (Exception $e) {
      die("Error: " . $e->getMessage());
    }
  }
}

function checkUser($dbUsers, $username, $hashedPassword, $role){
  try {
    $checkExistsQuery = "SELECT COUNT(username) as count FROM data WHERE username= :f_username";
    $stmt = $dbUsers->prepare($checkExistsQuery);
    $stmt->bindValue(":f_username", $username, SQLITE3_TEXT);
		$result = $stmt->execute();
		$row = $result->fetchArray();
    if ($row['count'] == 0) {
			// El usuario no existe en la base de datos, realiza inserción
      registerUser($dbUsers, $username, $hashedPassword, $role);
      $dbUsers->close();
      header("refresh:4; /sitrad");
      return "Usuario creado correctamente, por favor aguarde unos instantes e inicie sesión";
		}
    $dbUsers->close();
    return "El usuario ya existe en la base de datos, por favor elija un nombre nuevo";
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
  }
}

function registerUser($dbUsers, $username, $hashedPassword, $role){
  try {
    $query = "INSERT INTO data (username, password, role) VALUES (:f_username, :f_password, :f_role)";
    $stmt = $dbUsers->prepare($query);
    $stmt->bindValue(":f_username", $username, SQLITE3_TEXT);
    $stmt->bindValue(":f_password", $hashedPassword, SQLITE3_TEXT);
    $stmt->bindValue(":f_role", $role, SQLITE3_TEXT);
    $stmt->execute();
    $dbUsers->close();
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
  }
}

?>