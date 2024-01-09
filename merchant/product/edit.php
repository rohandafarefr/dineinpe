<?php
// Include the database configuration
require_once '../../env/db_config.php';
session_start(); // Start the session

// Check if the product ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = $_GET['id'];

    // Fetch the product details from the database
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameter and execute the statement
        $stmt->bind_param('i', $productId);
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch product details as an associative array
        $product = $result->fetch_assoc();

        // Close the statement
        $stmt->close();
    } else {
        // Error handling for prepared statement
        echo "Error preparing statement: " . $conn->error;
        exit();
    }

    // Check if the product was found
    if (!$product) {
        echo "Product not found";
        exit();
    }
} else {
    // If no product ID is provided in the URL
    echo "Invalid product ID";
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    // Update product details in the database
    $sql = "UPDATE products SET name = ?, description = ?, price = ?, category_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters and execute the statement
        $stmt->bind_param('ssdii', $name, $description, $price, $category_id, $productId);

        if ($stmt->execute()) {
            // Product updated successfully

            // Check if a new image is provided
            if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadsDirectory = '../../uploads/'; // Your chosen directory
                $baseURL = 'http://localhost/dineinpe/uploads/'; // Your base URL

                $tempPath = $_FILES['image']['tmp_name'];
                $newFileName = uniqid() . '_' . $_FILES['image']['name']; // Generate a unique filename
                $newPath = $uploadsDirectory . $newFileName;
                $imageURL = $baseURL . $newFileName; // Full URL for image

                if (move_uploaded_file($tempPath, $newPath)) {
                    // Successfully moved the uploaded image
                    // Update the image path in the database
                    $updateImageSQL = "UPDATE products SET image_path = ? WHERE id = ?";
                    $stmtImage = $conn->prepare($updateImageSQL);

                    if ($stmtImage) {
                        $stmtImage->bind_param('si', $imageURL, $productId);
                        
                        if ($stmtImage->execute()) {
                            // Image path updated successfully
                            $_SESSION['edit_success'] = true;
                        } else {
                            // Error handling for statement execution
                            $_SESSION['edit_error'] = "Error updating image path: " . $stmtImage->error;
                        }

                        // Close the statement
                        $stmtImage->close();
                    } else {
                        // Error handling for prepared statement
                        $_SESSION['edit_error'] = "Error preparing image update statement: " . $conn->error;
                    }
                } else {
                    // Failed to move the uploaded image
                    $_SESSION['edit_error'] = "Failed to move the uploaded image.";
                }
            } else {
                // No new image provided
                $_SESSION['edit_success'] = true;
            }
        } else {
            // Error handling for statement execution
            $_SESSION['edit_error'] = "Error executing statement: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error handling for prepared statement
        $_SESSION['edit_error'] = "Error preparing statement: " . $conn->error;
    }

    // Redirect back to the same page
    header("Location: edit.php?id=" . $productId);
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
    <title>Edit Product - Dine-in Pe</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Include your CSS links here -->
    <style>
        /* Add this CSS to your existing stylesheet or create a new one */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
}

form {
    margin-top: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input,
textarea,
select {
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}

select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background: url('path/to/arrow-icon.png') no-repeat right;
    background-size: 20px;
}

.upload-btn {
    display: inline-block;
    padding: 8px 12px;
    background-color: #007BFF;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    cursor: pointer;
}

#fileInput {
    display: none;
}

.file-name {
    margin-top: 5px;
    color: #666;
}

button {
    background-color: #007BFF;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

/* Add more styles as needed */

    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Edit Product</h2>
        <ul>
            <li><a href="add.php">Add Product</a></li>
            <li><a href="../products.php">View Products</a></li>
            <li><a href="../index.php">Go Back</a></li>
            <br><br><br>
            <li><a href="#" id="logout">Log out</a></li>
        </ul>
    </div>
    <div class="container">
        <?php
        // Check if $product is set and not empty before displaying the form
        if (isset($product) && !empty($product)) {
        ?>
            <form action="edit.php?id=<?php echo $productId; ?>" method="post" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Product Name" class="name" value="<?php echo $product['name']; ?>" required>
                <textarea name="description" placeholder="Product Description" required><?php echo $product['description']; ?></textarea>
                <input type="number" name="price" placeholder="Product Price" value="<?php echo $product['price']; ?>" step="0.01" required>
                <!-- Include this in your HTML form -->
                <label class="upload-btn" for="fileInput">Change Image</label>
                <input type="file" name="image" id="fileInput" accept="image/*" class="visually-hidden">
                <label class="file-name" id="fileNameLabel">No file chosen</label>


                <button type="submit">Update Product</button>
            </form>
        <?php
        } else {
            echo '<p>No product found</p>';
        }
        ?>
    </div>
    <script>
        document.getElementById('fileInput').addEventListener('change', function () {
            var fileName = this.files[0].name;
            document.getElementById('fileNameLabel').innerText = fileName;
        });
    </script>
</body>
</html>
