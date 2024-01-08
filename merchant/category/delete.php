<?php
// Include the database configuration
require_once '../../env/db_config.php';
session_start(); // Start the session

// Check if the category ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $categoryId = $_GET['id'];

    // Delete the category from the database
    $sql = "DELETE FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameter and execute the statement
        $stmt->bind_param('i', $categoryId);
        if ($stmt->execute()) {
            // Category deleted successfully
            $_SESSION['delete_success'] = true; // Set a session variable to indicate success
            echo "<script>alert('Category Deleted Successfully');</script>";
        } else {
            // Error handling for statement execution
            $_SESSION['delete_error'] = "Error executing statement: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error handling for prepared statement
        $_SESSION['delete_error'] = "Error preparing statement: " . $conn->error;
    }
} else {
    // If no category ID is provided in the URL
    $_SESSION['delete_error'] = "Invalid category ID";
}

// Close the database connection
$conn->close();

// Redirect back to the previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>
