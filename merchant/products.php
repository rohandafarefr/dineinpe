<?php
// Include the database configuration
require_once '../env/db_config.php';

// Fetch the list of existing products from the database
$sql = "SELECT p.*, c.name as category_name FROM products p
        LEFT JOIN categories c ON p.category_id = c.id"; // Adjust the JOIN condition based on your actual table structure
$result = $conn->query($sql);

// Create an array to store products
$products = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - Dine-in Pe</title>
    <link rel="stylesheet" href="../css/style.css">
    <!-- Add more stylesheets or scripts as needed -->
    <style>
        /* Add this CSS to your existing stylesheet or create a new one */
/* Add this CSS to your existing stylesheet or create a new one */
.container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.product {
    border: 1px solid #ddd;
    padding: 15px;
    text-align: left; /* Align text to the left */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background-color: #fff; /* Set background color */
    transition: box-shadow 0.3s; /* Smooth transition for box-shadow */
}

.product:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Hover effect for box-shadow */
}

.product h3 {
    font-size: 1.5em;
    margin-bottom: 10px;
}

.product p {
    font-size: 1em;
    color: #333;
    margin-bottom: 10px;
}

.product img {
    max-width: 200px;
    height: 200px;
    border-radius: 5px;
    margin-bottom: 10px;
}

.product a {
    display: inline-block;
    padding: 8px 16px;
    margin: 10px;
    text-decoration: none;
    background-color: #007BFF;
    color: #fff;
    border-radius: 3px;
    transition: background-color 0.3s;
}

.product a:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Products</h2>
        <ul>
            <li><a href="./product/add.php">Add Product</a></li>
            <!-- <li><a href="./product/edit.php">Edit Product</a></li>
            <li><a href="./product/delete.php">Delete Product</a></li> -->
            <li><a href="../index.php">Go Back</a></li>
            <br><br><br>
            <li><a href="backend/logout.php" id="logout">Log out</a></li>
        </ul>
    </div>
    <div class="container">
        <?php
        // Check if $products is an array and not empty before looping through it
        if (is_array($products) && !empty($products)) {
            // Fetch and display products from the database
            foreach ($products as $product) {
                echo '<div class="product">';
                echo '<h3>' . $product['name'] . '</h3>';
                echo '<p>' . $product['description'] . '</p>';
                echo '<p>Price: ₹' . $product['price'] . '</p>';
                echo '<p>Category: ' . $product['category_name'] . '</p>';

                // Check if 'image_path' exists in the $product array before accessing it
                if (isset($product['image_path'])) {
                    echo '<img src="' . $product['image_path'] . '" alt="' . $product['name'] . '"> <br>';
                } else {
                    echo '<p>No image available</p>';
                }

                echo '<a href="./product/edit.php?id=' . $product['id'] . '">Edit</a>';
                echo '<a href="./product/delete.php?id=' . $product['id'] . '">Delete</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No products found</p>';
        }
        ?>
    </div>
</body>
</html>
