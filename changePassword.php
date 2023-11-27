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
// Assuming you have an instance of the User class
$user = new User($conn);
$user_id = $_SESSION["user_id"];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize the input data
    $currentPassword = htmlspecialchars(trim($_POST["currentPassword"]));
    $newPassword = htmlspecialchars(trim($_POST["newPassword"]));
    $confirmPassword = htmlspecialchars(trim($_POST["confirmPassword"]));

    // Retrieve the hashed password from the database
    $hashedPassword = $user->getPasswordById($user_id);

    // Check if the current password matches the stored password
    if (password_verify($currentPassword, $hashedPassword)) {
        // Change the password (you need to implement this part)
        // For example, you can use a method like $user->changePassword($user_id, $newPassword);
        $success = $user->updatePassword($user_id, $newPassword);

        if ($success) {
            // Redirect to the account details page after changing the password
            header("Location: accDetails.php?success=passwordchanged");
            exit();
        } else {
            // Handle password update failure
            header("Location: changePassword.php?error=passwordupdatefailed");
            exit();
        }
    } else {
        // Handle incorrect current password (redirect back to the change password page with an error message)
        header("Location: changePassword.php?error=currentpasswordincorrect");
        exit();
    }
}
?>

<!-- changePassword.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Password - App Oasis</title>
    <!-- Include Bootstrap CSS from a CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Include your custom stylesheet if needed -->
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <!-- Content Section -->
    <div class="container mt-5">
        <h1>Change Password</h1>

        <?php
        // Display error messages if any
        if (isset($_GET["error"]) && $_GET["error"] === "currentpasswordincorrect") {
            echo '<p class="text-danger">Current password is incorrect.</p>';
        } elseif (isset($_GET["error"]) && $_GET["error"] === "passwordmismatch") {
            echo '<p class="text-danger">New password and confirm password do not match.</p>';
        }

        // Display success message if password change was successful
        if (isset($_GET["passwordChangeSuccess"]) && $_GET["passwordChangeSuccess"] === "true") {
            echo '<p class="text-success">Password changed successfully!</p>';
        }
        ?>

        <!-- Change Password Form -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <label for="currentPassword" class="form-label">Current Password</label>
                <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
            </div>
            <div class="mb-3">
                <label for="newPassword" class="form-label">New Password</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" required>
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
        </form>
    </div>

    <!-- Include Bootstrap JavaScript (bootstrap.min.js) and your custom script if needed -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="main.js"></script>
</body>
</html>
