<?php
// Include the database configuration
require_once '../../env/db_config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Handle image upload
    $uploadsDirectory = '../../uploads/'; // Your chosen directory

    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tempPath = $_FILES['image']['tmp_name'];
        $newFileName = uniqid() . '_' . $_FILES['image']['name']; // Generate a unique filename
        $newPath = $uploadsDirectory . $newFileName;

        if (move_uploaded_file($tempPath, $newPath)) {
            // Successfully moved the uploaded image
            // Insert category details into the database
            $sql = "INSERT INTO categories (name, description, image_path) VALUES ('$name', '$description', '$newPath')";
            if ($conn->query($sql) === TRUE) {
                // Category added successfully
                header("Location: index.php"); // Redirect back to category list
                exit();
            } else {
                // Error handling for database insertion
            }
        } else {
            // Failed to move the uploaded image
        }
    } else {
        // Error handling for the uploaded image
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category - Dine-in Pe</title>
    <link rel="stylesheet" href="../css/style.css">
    <!-- Include your CSS links here -->
</head>
<body>
    <div class="sidebar">
        <h2>Add Category</h2>
        <ul>
            <li><a class="actBtn" data-page="./category/add.php">Add</a></li>
            <li><a data-page="./category/edit.php">Edit</a></li>
            <li><a data-page="./category/delete.php">Delete</a></li>
            <li><a data-page="../category.php">Go Back</a></li>

            <br><br><br>
            <li><a href="#" id="logout">Log out</a></li>
        </ul>
    </div>
    <div class="container">

        <form action="add.php" method="post" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Category Name" required>
            <textarea name="description" placeholder="Category Description" required></textarea>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit">Add Category</button>
        </form>
    </div>
</body>
</html>
