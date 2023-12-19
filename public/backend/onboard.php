<?php
// Include the database configuration
require_once '../../env/db_config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize merchant input
    $name = $_POST['name'];
    $outletName = $_POST['outlet-name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $outletType = $_POST['outType'];
    $password = $_POST['password'];
    $cPassword = $_POST['c_password'];

    // Check if passwords match
    if ($password != $cPassword) {
        // Passwords don't match, redirect with error message
        header("Location: ../onboard.php?error=password_mismatch");
        exit();
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert merchant data into the database
    $sql = "INSERT INTO merchants (name, outlet_name, number, email, address, outlet_type, password)
            VALUES ('$name', '$outletName', '$number', '$email', '$address', '$outletType', '$hashedPassword')";

    if ($conn->query($sql) === TRUE) {
        // Registration successful
        header("Location: ../login.php?success=registration_successful");
        exit();
    } else {
        // Registration failed, redirect with error message
        header("Location: ../onboard.php?error=registration_failed");
        exit();
    }
}
?>
