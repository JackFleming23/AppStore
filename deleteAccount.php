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
    $confirmation = htmlspecialchars(trim($_POST["confirmation"]));

    if ($confirmation === "DELETE") {
        // Attempt to delete the account
        $success = $user->deleteAccount($user_id);

        if ($success) {
            // Redirect to the home page or a goodbye page after successful deletion
            header("Location: index.php?accountdeleted=true");
            exit();
        } else {
            // Handle account deletion failure
            header("Location: deleteAccount.php?error=deletefailed");
            exit();
        }
    } else {
        // Handle incorrect confirmation value
        header("Location: deleteAccount.php?error=confirmationfailed");
        exit();
    }
}
?>

<!-- deleteAccount.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include necessary meta tags, title, and CSS links -->
    <title>Delete Account - App Oasis</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Link to Bootstrap CSS from a CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Link to an external stylesheet (index.css) -->
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <!-- Content Section -->
    <div class="container mt-5">
        <h1>Delete Account</h1>

        <?php
        // Display error messages if any
        if (isset($_GET["error"]) && $_GET["error"] === "deletefailed") {
            echo '<p class="text-danger">Failed to delete the account. Please try again.</p>';
        } elseif (isset($_GET["error"]) && $_GET["error"] === "confirmationfailed") {
            echo '<p class="text-danger">Confirmation value is incorrect.</p>';
        }
        ?>

        <p>Are you sure you want to delete your account? This action cannot be undone.</p>
        <p>To confirm, type "DELETE" in the box below:</p>

        <!-- Delete Account Form -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <input type="text" class="form-control" name="confirmation" required>
            </div>
            <button type="submit" class="btn btn-danger">Delete Account</button>
        </form>
    </div>

    <!-- Include Bootstrap JavaScript (bootstrap.min.js) after the body content -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
