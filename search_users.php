<?php
// Assuming you have a database connection (db_connect.php) and a User class
require_once 'db_connect.php';
require_once 'User.php';

// Check if a search term is provided
if (isset($_GET['searchTerm'])) {
    $searchTerm = $_GET['searchTerm'];

    // Create an instance of the User class
    $user = new User($conn);

    // Retrieve user details based on the search term
    $searchResults = $user->searchUsers($searchTerm);

    if ($searchResults) {
        // Display search results
        foreach ($searchResults as $result) {
            // Add a class to make the result clickable
            echo '<div class="userResult">';
            echo '  <p>' . $result['username'] . '</p>';
            echo '  <button class="btn btn-primary selectUserBtn" data-username="' . $result['username'] . '">Select</button>';
            echo '</div>';
        }
    } else {
        echo 'No matching users found.';
    }
} else {
    echo 'No search term provided.';
}
?>
