<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include necessary meta tags, title, and CSS links -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>App Oasis</title>

    <!-- Link to the Bootstrap CSS from a CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Link to an external stylesheet (main.css) -->
    <link rel="stylesheet" type="text/css" href="index.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md">
        <a class="navbar-brand" href="#">
            <img src="images/logo-no-background.png" width="200" height="70" alt="" class="rounded">
        </a>
        <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main-navigation">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <?php
                // Check if the user is logged in
                if (isset($_SESSION["user_id"])) {
                    // User is logged in, display a greeting and a logout button
                    echo '<li class="nav-item"><span class="navbar-text" id="greeting">Hello, ' . $_SESSION["username"] . '!</span></li>';
                    echo '<li class="nav-item"><a class="btn btn-light" href="logout.php">Logout</a></li>';
                    echo '<li class="nav-item"><a class="btn btn-light" href="accDetails.php">Manage Account</a></li>';
                } else {
                    // User is not logged in, display sign-in and sign-up buttons
                    echo '<li class="nav-item"><button type="button" class="btn btn-light" id="signin-button" onclick="window.location.href=\'signin.html\';">Sign In</button></li>';
                    echo '<li class="nav-item"><button type="button" class="btn btn-light" id="signup-button" onclick="window.location.href=\'signup.html\';">Sign Up</button></li>';
                }
                ?>
            </ul>
        </div>
    </nav>

    <!-- Header Section -->

    <!-- Search Card Section -->
    <div class="container">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="card" id="app-search-card">
                    <div class="card-body">
                        <h1 class="card-title" id="searchAppsTitle">Search for Apps</h1>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search..." aria-label="Search for Apps">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include your JavaScript file (main.js) -->
    <script src="main.js"></script>

    <!-- Include Bootstrap JavaScript (bootstrap.min.js) -->
    <script src="bootstrap-5.3.2-dist/js/bootstrap.min.js"></script>
</body>
</html>
