<?php

function queryUsers($dbUsers){
  $query="SELECT username, password, role FROM data";
  $result = $dbUsers->query($query);

  while ($data=$result->fetchArray()){
    $username = $data["username"];
    $password = $data["password"];
    $role = $data["role"];

    echo '<tr>';
    echo '<td>'.$username.'</td>';
    echo '<td>'.$password.'</td>';
    echo '<td>'.$role.'</td>';
    echo '</tr>';
  };
}