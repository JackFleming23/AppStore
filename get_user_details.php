<?php
// Assuming you have a database connection (db_connect.php) and the User class
require_once 'db_connect.php';
require_once 'User.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Create an instance of the User class
    $user = new User($conn);

    // Retrieve user details based on the provided username
    $userDetails = $user->getUserByUsername($username);

    // Output the user details as JSON
    header('Content-Type: application/json');
    echo json_encode($userDetails);
} else {
    echo 'No username provided.';
}
?>
