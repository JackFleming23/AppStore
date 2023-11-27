<?php
// Assuming you have a database connection (db_connect.php) and the User class
require_once 'db_connect.php';
require_once 'User.php';

// Check if an action and username are provided
if (isset($_POST['action']) && isset($_POST['username'])) {
    $action = $_POST['action'];
    $username = $_POST['username'];

    // Create an instance of the User class
    $user = new User($conn);

    // Perform the requested action based on the action parameter
    switch ($action) {
        case 'deleteUser':
            $success = $user->deleteAccount($user->getUserIdByUsername($username));
            break;

        case 'updatePassword':
            if (isset($_POST['newPassword'])) {
                $newPassword = $_POST['newPassword'];
                $user_id = $user->getUserIdByUsername($username);
                $success = $user->updatePassword($user_id, $newPassword);
            } else {
                $success = false;
            }
            break;

        case 'promoteUser':
            // Assuming you have a form field for selecting the new role
            if (isset($_POST['newRole'])) {
                $newRole = $_POST['newRole'];
                $user_id = $user->getUserIdByUsername($username);
                $success = $user->promoteToRole($user_id, $newRole);
            } else {
                $success = false;
            }
            break;

        default:
            $success = false;
            break;
    }

    // Return a response (you can customize the response based on your needs)
    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);
} else {
    // Invalid request
    header('HTTP/1.1 400 Bad Request');
    echo 'Invalid request.';
}
?>
