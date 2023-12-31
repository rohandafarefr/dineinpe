<?php
// Include the database configuration
require_once '../../env/db_config.php';
session_start(); // Start the session

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $name = $_POST['name'];
    $description = $_POST['description'];
    $merchant_id = $_SESSION['merchant_id']; // Assuming you have the merchant_id in the session

    // Handle image upload
    $uploadsDirectory = '../../uploads/'; // Your chosen directory
    $baseURL = 'http://localhost/dineinpe/uploads/'; // Your base URL

    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tempPath = $_FILES['image']['tmp_name'];
        $newFileName = uniqid() . '_' . $_FILES['image']['name']; // Generate a unique filename
        $newPath = $uploadsDirectory . $newFileName;
        $imageURL = $baseURL . $newFileName; // Full URL for image

        if (move_uploaded_file($tempPath, $newPath)) {
            // Successfully moved the uploaded image
            // Insert category details into the database
            $sql = "INSERT INTO categories (name, description, image_path, merchant_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                // Bind parameters and execute the statement
                $stmt->bind_param('sssi', $name, $description, $imageURL, $merchant_id);

                // Execute the statement
                if ($stmt->execute()) {
                    // Category added successfully
                    header("Location: add.php"); // Redirect back to category list
                    echo "<script>alert('Category added successfully');</script>";
                    exit();
                } else {
                    // Error handling for statement execution
                    echo "<script>alert('Error executing statement: " . $stmt->error . "');</script>";
                }
            } else {
                // Error handling for prepared statement
                echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
            }
        } else {
            // Failed to move the uploaded image
            echo "<script>alert('Failed to move the uploaded image.');</script>";
        }
    } else {
        // Error handling for the uploaded image
        echo "<script>alert('Error with the uploaded image.');</script>";
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
    <link rel="stylesheet" href="css/style.css">
    <!-- Include your CSS links here -->
</head>
<body>
    <div class="sidebar">
        <h2>Add Category</h2>
        <ul>
            <!-- <li><a class="actBtn" href="add.php">Add</a></li>
            <li><a href="edit.php">Edit</a></li>
            <li><a href="delete.php">Delete</a></li> -->
            <li><a href="../../merchant/" data-page="category.php">Home</a></li>
<!-- 
            <br><br><br>
            <li><a href="#" id="logout">Log out</a></li> -->
        </ul>
    </div>
    <div class="container">

        <form action="add.php" method="post" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Category Name" class="name" required>
            <textarea name="description" placeholder="Category Description" required></textarea>
            <!-- Include this in your HTML form -->
            <label class="upload-btn" for="fileInput">Add Image</label>
            <input type="file" name="image" id="fileInput" accept="image/*" class="visually-hidden" required>
            <label class="file-name" id="fileNameLabel">No file chosen</label>

            <button type="submit">Add Category</button>
        </form>
    </div>
    <script>
        document.getElementById('fileInput').addEventListener('change', function () {
            var fileName = this.files[0].name;
            document.getElementById('fileNameLabel').innerText = fileName;
        });
    </script>
</body>
</html>
