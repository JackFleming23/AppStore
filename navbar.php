<?php
// Start the session
session_start();
?>

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
