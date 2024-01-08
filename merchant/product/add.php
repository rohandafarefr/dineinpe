<?php
// Include the database configuration
require_once '../../env/db_config.php';
session_start(); // Start the session

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id']; // Assuming you have the category_id in the form
    $merchant_id = $_SESSION['merchant_id']; // Assuming you have the merchant_id in the session

    // Set base URL for uploads
    $uploadsDirectory = '../../uploads/'; // Your chosen directory
    $baseURL = 'http://localhost/dineinpe/uploads/'; // Your base URL

    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tempPath = $_FILES['image']['tmp_name'];
        $newFileName = uniqid() . '_' . $_FILES['image']['name']; // Generate a unique filename
        $newPath = $uploadsDirectory . $newFileName;
        $imageURL = $baseURL . $newFileName; // Full URL for image

        if (move_uploaded_file($tempPath, $newPath)) {
            // Successfully moved the uploaded image
            // Insert product details into the database
            $sql = "INSERT INTO products (name, description, price, image_path, category_id, merchant_id) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                // Create a variable for price before calling bind_param
                $priceVar = floatval($price);

                // Bind parameters and execute the statement
                $stmt->bind_param('ssdsii', $name, $description, $priceVar, $imageURL, $category_id, $merchant_id);

                // Execute the statement
                if ($stmt->execute()) {
                    // Product added successfully
                    echo "<script>alert('Product added successfully');</script>";
                    header("Location: add.php"); // Redirect back to product list
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
    <title>Add Product - Dine-in Pe</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Include your CSS links here -->
</head>
<body>
    <div class="sidebar">
        <h2>Add Product</h2>
        <ul>
            <!-- <li><a class="actBtn" href="add.php">Add</a></li>
            <li><a href="edit.php">Edit</a></li>
            <li><a href="delete.php">Delete</a></li> -->
            <li><a href="../../merchant/" data-page="product.php">Home</a></li>
            <!-- 
            <br><br><br>
            <li><a href="#" id="logout">Log out</a></li> -->
        </ul>
    </div>
    <div class="container">

        <form action="add.php" method="post" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Product Name" class="name" required>
            <textarea name="description" placeholder="Product Description" required></textarea>
            <input type="number" name="price" placeholder="Product Price" step="0.01" required>
            <!-- Include this in your HTML form -->
            <label class="upload-btn" for="fileInput">Add Image</label>
            <input type="file" name="image" id="fileInput" accept="image/*" class="visually-hidden" required>
            <label class="file-name" id="fileNameLabel">No file chosen</label>

            <!-- Add a dropdown for category selection -->
            <label for="category">Select Category:</label>
            <select name="category_id" id="category" required>
                <!-- Fetch and display categories dynamically -->
                <?php
                $categoryQuery = "SELECT id, name FROM categories";
                $categoryResult = $conn->query($categoryQuery);

                if ($categoryResult->num_rows > 0) {
                    while ($row = $categoryResult->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                    }
                }
                ?>
            </select>

            <button type="submit">Add Product</button>
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
