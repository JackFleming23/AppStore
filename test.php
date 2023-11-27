<?php
$servername = "cit-mysql.regionals.miamioh.edu";
$username = "beerelbs";
$password = "9bpappY3";
$dbname = "beerelbs_AppOasisUser";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";

// Close the connection
$conn->close();
?>
