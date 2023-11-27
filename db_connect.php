<?php

$servername = "cit-mysql.regionals.miamioh.edu";
$username = "beerelbs";
$password = "9bpappY3";
$dbname = "beerelbs_AppOasisUser";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
  echo "connection successful";
}

?>
