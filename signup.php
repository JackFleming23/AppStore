<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Log received POST data to a file
  $logFile = fopen("post_data_log.txt", "a");
  fwrite($logFile, date("Y-m-d H:i:s") . "\n");
  fwrite($logFile, print_r($_POST, true) . "\n");
  fclose($logFile);
    // Retrieve form data
    $firstName = $_POST["FirstName"];
    $lastName = $_POST["LastName"];
    $email = $_POST["InputEmail"];
    $username = $_POST["username"];
    $password = password_hash($_POST["Password"], PASSWORD_BCRYPT); // Use BCRYPT for password hashing

    // Validate the data (add more validation as needed)
    if (empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($_POST["Password"]) || empty($_POST["ConfirmPassword"])) {
        // Handle validation errors (redirect back to the signup page with an error message)
        header("Location: signup.html?error=emptyfields");
        echo "Empty Fields";
        exit();
    }

    if ($_POST["Password"] !== $_POST["ConfirmPassword"]) {
        // Handle password mismatch error
        header("Location: signup.html?error=passwordmismatch");
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

    // Insert user data into the database using prepared statements
    $sql = "INSERT INTO users (first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $username, $password);

    // Execute the statement
if ($stmt->execute()) {
    // Redirect to a success page
    header("Location: index.html");
    exit();
}


    // Close the statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // If someone tries to access this file without submitting the form, redirect them to the signup page
    header("Location: signup.html");
    exit();
}
?>
