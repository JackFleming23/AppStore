<?php
// Assuming you have a database connection (db_connect.php) and a User class
require_once 'db_connect.php';
require_once 'User.php';

// Check if a username is provided
if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Create an instance of the User class
    $user = new User($conn);

    // Retrieve user details based on the provided username
    $userData = $user->getUserByUsername($username);

    if ($userData) {
        // Output the user details as JSON
        echo json_encode($userData);
    } else {
        echo 'User not found.';
    }
} else {
    echo 'No username provided.';
}
?>
