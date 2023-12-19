<?php
// Start a session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../public/login.php");
    exit();
}

// Get user's name from the database based on the user_id stored in session
require_once '../env/db_config.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT name FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $user_name = $row['name'];
} else {
    $user_name = "User";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Dine-in Pe</title>
</head>
<body>
    <?php include 'header.php' ?>

    <div class="container-11">
        <p>Hello <?php echo $user_name; ?></p>
    </div>

    <!-- <?php include 'footer.php' ?> -->
</body>
</html>
