<?php
// Include the database configuration
require_once '../../env/db_config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email exists in any of the user tables (users, merchants, admins)
    $sql = "SELECT id, password, 'user' AS type FROM users WHERE email = '$email'
            UNION SELECT id, password, 'merchant' AS type FROM merchants WHERE email = '$email'
            UNION SELECT id, password, 'admin' AS type FROM admins WHERE email = '$email'";

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];

        // Verify the password
        if ($password == $storedPassword) {
            // Password is correct, set session based on user type and redirect
            session_start();
            if ($row['type'] == 'user') {
                $_SESSION['user_id'] = $row['id'];
                header("Location: ../../user/index.php");
                exit();
            } elseif ($row['type'] == 'merchant') {
                $_SESSION['merchant_id'] = $row['id'];
                header("Location: ../../merchant/index.php");
                exit();
            } elseif ($row['type'] == 'admin') {
                $_SESSION['admin_id'] = $row['id'];
                header("Location: ../../admin/index.php");
                exit();
            }
        } else {
            // Password is incorrect, redirect with error message
            echo "Invalid Password";
            header("Location: ../login.php?error=invalid_credentials");
            exit();
        }
    } else {
        // User not found, redirect with error message
        echo "User Not Found";
        header("Location: ../login.php?error=user_not_found");
        exit();
    }
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
