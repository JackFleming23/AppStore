<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class User
{
    private $user_id;
    private $firstName;
    private $lastName;
    private $email;
    private $role; // New attribute
    private $conn;

    public function __construct($conn, $user_id = null, $firstName = null, $lastName = null, $email = null, $role = null)
    {
        $this->conn = $conn;
        $this->user_id = $user_id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->role = $role;
        // Add other user details as needed
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function logout()
    {
        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();
    }

    public function searchUsers($searchTerm) {
        // Use prepared statements to prevent SQL injection
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username LIKE ?");
        $searchTerm = '%' . $searchTerm . '%';
        $stmt->bind_param('s', $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        // Fetch the results as an associative array
        $searchResults = $result->fetch_all(MYSQLI_ASSOC);

        return $searchResults;
    }

    // Inside your User class (User.php)
    public function getUserByUsername($username) {
            // Sanitize the input to prevent SQL injection
            $username = mysqli_real_escape_string($this->conn, $username);

            // Query to fetch user details by username
            $query = "SELECT * FROM users WHERE username = '$username'";

            // Execute the query
            $result = mysqli_query($this->conn, $query);

            // Check if the query was successful
            if ($result) {
                // Fetch user details as an associative array
                $user = mysqli_fetch_assoc($result);

                // Free the result set
                mysqli_free_result($result);

                return $user;
            } else {
                // Handle the error (you might want to log it or handle it differently)
                echo "Error: " . mysqli_error($this->conn);
                return false;
            }
        }


    // Delete user account
    public function deleteAccount($user_id)
    {
        // Ensure user_id is valid and exists
        if ($user_id) {
            // Delete user_role records for the user
            $stmtUserRole = $this->conn->prepare("DELETE FROM user_role WHERE user_id = ?");
            $stmtUserRole->bind_param("i", $user_id);

            // Execute the statement
            $stmtUserRole->execute();

            // Delete the user from the database
            $stmtUser = $this->conn->prepare("DELETE FROM users WHERE user_id = ?");
            $stmtUser->bind_param("i", $user_id);

            // Execute the statement
            if ($stmtUser->execute()) {
                // Logout the user after deleting the account
                $this->logout();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getPasswordById($user_id) {
       // Assuming you have a database connection ($this->conn)
       $stmt = $this->conn->prepare("SELECT password FROM users WHERE user_id = ?");
       $stmt->bind_param("i", $user_id);
       $stmt->execute();
       $result = $stmt->get_result();
       $row = $result->fetch_assoc();
       $stmt->close();

       return $row ? $row['password'] : null;
   }

    public function updatePassword($user_id, $new_password) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the database
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }


    public function getUserIdByUsername($username)
    {
        $sql = "SELECT user_id FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['user_id'];
        } else {
            return null; // Username not found
        }
    }

    public static function getUserDetailsById($conn, $user_id)
    {
        // Validate input
        if (!is_numeric($user_id) || $user_id <= 0) {
            return null; // Invalid user ID
        }

        // Retrieve user data from the database, including the role
        $sql = "SELECT users.*, roles.role_name
                FROM users
                JOIN user_role ON users.user_id = user_role.user_id
                JOIN roles ON user_role.role_id = roles.role_id
                WHERE users.user_id = ?";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("i", $user_id);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if the user ID exists
        if ($result->num_rows > 0) {
            // Fetch user data
            $row = $result->fetch_assoc();

            // Create and return a User object with the fetched data
            return new User(
                $conn,
                $row["user_id"],
                $row["first_name"],
                $row["last_name"],
                $row["email"],
                $row["role_name"]
            );
        } else {
            return null; // User not found
        }
    }

    public function signup($firstName, $lastName, $email, $username, $password)
    {
        // Check if the username already exists
        $check_username_sql = "SELECT * FROM users WHERE username = ?";
        $check_stmt = $this->conn->prepare($check_username_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $existing_user = $check_stmt->fetch();
        $check_stmt->close();

        if ($existing_user) {
            // Username already exists, handle accordingly (e.g., redirect with an error message)
            header("Location: signup.html?error=usernameexists");
            exit();
        }

        // Insert user data into the database using prepared statements
        $sql = "INSERT INTO users (first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("sssss", $firstName, $lastName, $email, $username, $password);

        // Use a transaction to ensure atomicity
        $this->conn->begin_transaction();

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;

            // Role assignment
            $role_sql = "INSERT INTO user_role (user_id, role_id) VALUES (?, (SELECT role_id FROM roles WHERE role_name = 'user'))";
            $role_stmt = $this->conn->prepare($role_sql);
            $role_stmt->bind_param("i", $user_id);

            if (!$role_stmt->execute()) {
                // Roll back the transaction on role assignment failure
                $this->conn->rollback();
                $role_stmt->close();

                // Redirect with an error message
                header("Location: signup.html?error=registrationfailed");
                exit();
            }

            // Commit the transaction
            $this->conn->commit();
            $role_stmt->close();

            // Registration successful
            // You might want to redirect to a success page or log in the user automatically
            header("Location: index.php");
            exit();
        }

        // Roll back the transaction on user insertion failure
        $this->conn->rollback();

        // Redirect with an error message
        header("Location: signup.html?error=registrationfailed");
        exit();
    }

    public function signin($username, $password)
    {
        // Validate the data (add more validation as needed)
        if (empty($username) || empty($password)) {
            // Handle validation errors (redirect back to the sign-in page with an error message)
            header("Location: signin.html?error=emptyfields");
            exit();
        }

        // Retrieve user data from the database, including the role
        $sql = "SELECT users.*, roles.role_name
                FROM users
                JOIN user_role ON users.user_id = user_role.user_id
                JOIN roles ON user_role.role_id = roles.role_id
                WHERE users.username = ?";
        $stmt = $this->conn->prepare($sql);

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

            // Update user attributes
            $this->user_id = $row["user_id"];
            $this->firstName = $row["first_name"];
            $this->lastName = $row["last_name"];
            $this->email = $row["email"];
            $this->role = $row["role_name"];

            $db_password_hash = $row["password"];

            // Check if the entered password matches the stored hash
            if (password_verify($password, $db_password_hash)) {
                // Password is correct, store user information in the session
                session_start();
                $_SESSION["user_id"] = $this->user_id;
                $_SESSION["username"] = $username;
                $_SESSION["role"] = $this->role; // Store role in the session

                // Redirect to the home page or another authenticated page
                header("Location: index.php");
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

        // Close the statement
        $stmt->close();
    }

    public function promoteToRole($userId, $newRole)
    {
        // Validate the new role to ensure it's a valid role (you may fetch roles from your database)
        $validRoles = ['User', 'Moderator', 'Admin'];

        if (!in_array($newRole, $validRoles)) {
            // Invalid role, handle accordingly (throw an exception, return an error code, etc.)
            // For simplicity, this example assumes 'user', 'moderator', and 'admin' as valid roles
            return false;
        }

        // Update the user's role in the user_role table
        $updateRoleSql = "UPDATE user_role SET role_id = (SELECT role_id FROM roles WHERE role_name = ?) WHERE user_id = ?";
        $stmt = $this->conn->prepare($updateRoleSql);

        // Bind parameters
        $stmt->bind_param("si", $newRole, $userId);

        // Execute the statement
        $result = $stmt->execute();

        // Close the statement
        $stmt->close();

        return $result;
    }
}

?>
