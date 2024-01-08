<?php
// Include the database configuration
require_once '../../env/db_config.php';
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['merchant_id'])) {
    header("Location: ../../index.php"); // Redirect to the login page if not logged in
    exit();
}

// Check if the product ID is provided in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ./"); // Redirect back to the product list if no or invalid ID
    exit();
}

// Get product ID from the URL
$product_id = $_GET['id'];

// Query to check if the product exists and belongs to the logged-in merchant
$sql = "SELECT * FROM products WHERE id = ? AND merchant_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Bind parameters and execute the statement
    $stmt->bind_param('ii', $product_id, $_SESSION['merchant_id']);

    // Execute the statement
    $stmt->execute();

    // Fetch the result
    $result = $stmt->get_result();

    // Check if the product exists and belongs to the merchant
    if ($result->num_rows === 0) {
        header("Location: ./"); // Redirect back to the product list
        exit();
    }
} else {
    // Error handling for prepared statement
    echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
    exit();
}

// Close the statement
$stmt->close();

// Check if the form is submitted for product deletion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Query to delete the product
    $sql = "DELETE FROM products WHERE id = ? AND merchant_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters and execute the statement
        $stmt->bind_param('ii', $product_id, $_SESSION['merchant_id']);

        // Execute the statement
        if ($stmt->execute()) {
            // Product deleted successfully
            header("Location: ../index.php"); // Redirect back to the product list
            echo "<script>alert('Product deleted successfully');</script>";
            exit();
        } else {
            // Error handling for statement execution
            echo "<script>alert('Error executing statement: " . $stmt->error . "');</script>";
        }
    } else {
        // Error handling for prepared statement
        echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product - Dine-in Pe</title>
    <link rel="stylesheet" href="../css/style.css">
    <!-- Include your CSS links here -->

    <style>
        /* Add this CSS to your existing stylesheet or create a new one */

        body {
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f5f5f5;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        form {
            text-align: center;
            margin-top: 20px;
        }

        button {
            margin-top: 10px; /* Adjust as needed */
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Delete Product</h2>
        <form action="delete.php?id=<?php echo $product_id; ?>" method="post">
            <p>Are you sure you want to delete this product?</p>
            
            <!-- You can display product details here if needed -->
            
            <button type="submit">Delete Product</button>
        </form>
    </div>
</body>
</html>


