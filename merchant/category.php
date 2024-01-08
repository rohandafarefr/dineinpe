<?php
// Include the database configuration
require_once '../env/db_config.php';

// Fetch the list of existing categories from the database
$sql = "SELECT * FROM categories"; // Change this query according to your database structure
$result = $conn->query($sql);

// Create an array to store categories
$categories = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
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
    <title>Category Management - Dine-in Pe</title>
    <link rel="stylesheet" href="./category//css/style.css">
</head>
<body>
    <div class="sidebar">
        <h2>Category</h2>
        <ul>
            <li><a href="./category/add.php">Add New</a></li>
            <!-- <li><a href="./category/edit.php">Edit Category</a></li>
            <li><a href="./category/delete.php">Delete Category</a></li> -->
            <li><a href="./index.php">Go Back</a></li>
            <br><br><br>
            <!-- <li><a href="backend/logout.php" id="logout">Log out</a></li> -->
        </ul>
    </div>
    <div class="container">
        <?php
        // Check if $categories is an array and not empty before looping through it
        if (is_array($categories) && !empty($categories)) {
            // Fetch and display categories from the database
            foreach ($categories as $category) {
                echo '<div class="category">';
                echo '<h3>' . $category['name'] . '</h3>';
                echo '<p>' . $category['description'] . '</p>';

                // Check if 'image_url' exists in the $category array before accessing it
                if (isset($category['image_path'])) {
                    echo '<img src="' . $category['image_path'] . '" alt="' . $category['name'] . '">';
                } else {
                    echo '<p>No image available</p>';
                }

                echo '<a href="./category/edit.php?id=' . $category['id'] . '">Edit</a>';
                echo '<a href="./category/delete.php?id=' . $category['id'] . '">Delete</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No categories found</p>';
        }
        ?>
    </div>
</body>
</html>


