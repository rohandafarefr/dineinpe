<?php
// Include the database configuration
require_once 'env/db_config.php';

// Check if merchant ID is provided in the URL
if (isset($_GET['merchant_id']) && is_numeric($_GET['merchant_id'])) {
    $merchantId = $_GET['merchant_id'];

    // Fetch merchant details from the database
    $merchantSql = "SELECT * FROM merchants WHERE id = ?";
    $merchantStmt = $conn->prepare($merchantSql);

    if ($merchantStmt) {
        // Bind parameter and execute the statement
        $merchantStmt->bind_param('i', $merchantId);
        $merchantStmt->execute();

        // Get the result
        $merchantResult = $merchantStmt->get_result();

        // Fetch merchant details as an associative array
        $merchant = $merchantResult->fetch_assoc();

        // Close the statement
        $merchantStmt->close();
    } else {
        // Error handling for prepared statement
        echo "Error preparing merchant statement: " . $conn->error;
        exit();
    }

    // Check if the merchant was found
    if (!$merchant) {
        echo "Merchant not found";
        exit();
    }
} else {
    // If no merchant ID is provided in the URL
    echo "Invalid merchant ID";
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $merchant['outlet_name']; ?></title>
    <!-- <link rel="stylesheet" href="path/to/your/style.css"> -->
    <!-- Include your CSS links here -->
    <style>
        /* Add this CSS to your existing stylesheet or create a new one */

body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

header {
    background-color: #000;
    color: #fff;
    text-align: center;
    padding: 20px;
}

h1 {
    margin: 0;
}

button {
    background-color: #000;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin: 20px;
}

button:hover {
    background-color: #005;
}

    </style>
</head>
<body>
    <header>
        <h1><?php echo $merchant['outlet_name']; ?></h1>
        <!-- Add more details as needed -->
    </header>

    <button onclick="window.location.href='menu.php?merchant_id=<?php echo $merchantId; ?>'">View Menu</button>
</body>
</html>
