<?php
include 'db_connect.php';
include 'User.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = htmlspecialchars(trim($_POST["FirstName"]));
    $lastName = htmlspecialchars(trim($_POST["LastName"]));
    $email = htmlspecialchars(trim($_POST["InputEmail"]));
    $username = htmlspecialchars(trim($_POST["username"]));
    $password = htmlspecialchars(trim($_POST["Password"]));
    $confirmPassword = htmlspecialchars(trim($_POST["ConfirmPassword"]));

    // Validate the data (add more validation as needed)
    if (empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($password) || empty($confirmPassword)) {
        // Handle validation errors (redirect back to the signup page with an error message)
        header("Location: signup.html?error=emptyfields");
        exit();
    }

    if ($password !== $confirmPassword) {
        // Handle password mismatch error
        header("Location: signup.html?error=passwordmismatch");
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Create a User instance
    $user = new User($conn, null, $firstName, $lastName, $email);

    // Use the signup method of the User class
    $result = $user->signup($firstName, $lastName, $email, $username, $hashedPassword);

    if ($result) {
        // Redirect to a success page
        header("Location: index.php");
        exit();
    } else {
        // Handle signup error
        header("Location: signuppage.php?error=signuperror");
        exit();
    }
} else {
    // If someone tries to access this file without submitting the form, redirect them to the signup page
    header("Location: signuppage.php");
    exit();
}
?>
