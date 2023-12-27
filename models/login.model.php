<?php

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
    }
    if($db_role){
      if ($username == $db_username && $password == password_verify($password, $db_password_hash)) {
        $_SESSION["username"] = $db_username;
        $_SESSION["role"] = $db_role;
        $dbUsers->close();
      }
    }
    return $db_role;
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
  }
}

?>