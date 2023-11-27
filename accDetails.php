<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // If not logged in, redirect to the sign-in page
    header("Location: signin.html");
    exit();
}

// Assuming you have a database connection (db_connect.php) and a User class
require_once 'db_connect.php';
require_once 'User.php';

// Retrieve user details from the database based on the user_id stored in the session
$user_id = $_SESSION["user_id"];
$user = User::getUserDetailsById($conn, $user_id); // Use the User class method

?>

<!-- accountDetails.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Details - App Oasis</title>
    <!-- Include Bootstrap CSS from a CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Include your custom stylesheet if needed -->
    <link rel="stylesheet" type="text/css" href="index.css">
    <?php
    // Include the navbar
    include 'navbar.php';
    ?>
</head>
<body>

    <!-- Content Section -->
    <div class="container mt-5">
        <h1>Account Details</h1>
        <?php
        // Display user details
        if ($user) {
            echo '<p><strong>First Name:</strong> ' . $user->getFirstName() . '</p>';
            echo '<p><strong>Last Name:</strong> ' . $user->getLastName() . '</p>';
            echo '<p><strong>Email:</strong> ' . $user->getEmail() . '</p>';
            echo '<p><strong>Role:</strong> ' . $user->getRole() . '</p>'; // Display user's role
            // Add other user details as needed
            echo '<button class="btn btn-primary" onclick="window.location.href=\'changePassword.php\'">Change Password</button>';
            echo '<button class="btn btn-primary" onclick="window.location.href=\'deleteAccount.php\'">Delete Account</button>';


            if ($user->getRole() === 'Admin') {
            echo '<button class="btn btn-primary" onclick="window.location.href=\'manage_users.php\'">Manage Users</button>';
            }
        } else {
            echo '<p>User not found</p>';
        }
        ?>
    </div>

    <!-- Include Bootstrap JavaScript (bootstrap.min.js) and your custom script if needed -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="main.js"></script>
</body>
</html>
