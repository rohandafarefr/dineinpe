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
    <link rel="stylesheet" href="../css/style.css">
    <!-- Add more stylesheets or scripts as needed -->
</h
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Dine-in Pe</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Other necessary stylesheets and resources -->
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Category</h2>
        <ul>
            <li><a href="./category/add.php" data-page="./category/add.php">Add</a></li>
            <li><a data-page="./category/edit.php">Edit</a></li>
            <li><a data-page="./category/delete.php">Delete</a></li>
            <li><a data-page="./index.php">Go Back</a></li>

            <br><br><br>
            <li><a href="#" id="logout">Log out</a></li>
        </ul>
    </div>

    <div class="container">        
        <!-- Display list of existing categories -->
        <div class="category-list">
    <?php
// Fetch and display categories from the database
        foreach ($categories as $category) {
            echo <<<HTML
            <div class="category">
                <h3>{$category['name']}</h3>
                <p>{$category['description']}</p>
                <img src="{$category['image_url']}" alt="{$category['name']}">
                <a href="./category/edit.php?id={$category['id']}">Edit</a>
                <a href="./category/delete.php?id={$category['id']}">Delete</a>
            </div>
        HTML;
        }
    ?>

        </div>
    </div>
</body>
</html>
