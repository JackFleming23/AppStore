<?php
include 'db_connect.php';
include 'User.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create a new User instance and pass the database connection
$user = new User($conn);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $password = $_POST["password"];

    echo "Username: $username<br>";
    echo "Password: $password<br>";


    // Use the signin method of the User class
    $user->signin($username, $password);

} else {
    // If someone tries to access this file without submitting the form, redirect them to the sign-in page
    header("Location: signin.html");
    exit();
}
?>
