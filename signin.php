<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the data (add more validation as needed)
    if (empty($username) || empty($password)) {
        // Handle validation errors (redirect back to the sign-in page with an error message)
        header("Location: signin.html?error=emptyfields");
        exit();
    }

    // Connect to the database (replace with your actual database credentials)
    $servername = "cit-mysql.regionals.miamioh.edu";
    $db_username = "beerelbs";
    $db_password = "9bpappY3";
    $dbname = "beerelbs_AppOasisUser";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve user data from the database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("s", $username);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if the username exists
    if ($result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();
        $db_password_hash = $row["password"];

        // Check if the entered password matches the stored hash
        if (password_verify($password, $db_password_hash)) {
            // Password is correct, store user information in the session
            session_start();
            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION["username"] = $row["username"];

            // Redirect to the home page or another authenticated page
            header("Location: index.html");
            exit();
        } else {
            // Password is incorrect, redirect back to the sign-in page with an error message
            header("Location: signin.html?error=incorrectpassword");
            exit();
        }
    } else {
        // Username doesn't exist, redirect back to the sign-in page with an error message
        header("Location: signin.html?error=usernotfound");
        exit();
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // If someone tries to access this file without submitting the form, redirect them to the sign-in page
    header("Location: signin.html");
    exit();
}
?>
