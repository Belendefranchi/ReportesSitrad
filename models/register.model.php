<?php

function registerUser($dbUsers, $username, $password){
  try {
    $query = "INSERT INTO data (username, password, role) VALUES (:f_username, :f_password, :role)";
    $stmt = $dbUsers->prepare($query);
    $stmt->bindValue(":f_username", $username);
    $stmt->bindValue(":f_password", $password);

    $result = $stmt->execute();
    $dbUsers->close();
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
  }
}

?>