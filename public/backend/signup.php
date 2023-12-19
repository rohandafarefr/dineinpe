<?php
// Include the database configuration
require_once '../../env/db_config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $name = $_POST['name'];
    $number = $_POST['tel'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $sql = "INSERT INTO users (name, number, email, password)
            VALUES ('$name', '$number', '$email', '$hashedPassword')";

    if ($conn->query($sql) === TRUE) {
        // Registration successful
        header("Location: ../login.php?success=registration_successful");
        exit();
    } else {
        // Registration failed
        header("Location: signup.php?error=registration_failed");
        exit();
    }
}
?>
