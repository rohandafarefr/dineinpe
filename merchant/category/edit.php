<?php
// Include the database configuration
require_once '../../env/db_config.php';
session_start(); // Start the session

// Check if the category ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $categoryId = $_GET['id'];

    // Fetch the category details from the database
    $sql = "SELECT * FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameter and execute the statement
        $stmt->bind_param('i', $categoryId);
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch category details as an associative array
        $category = $result->fetch_assoc();

        // Close the statement
        $stmt->close();
    } else {
        // Error handling for prepared statement
        echo "Error preparing statement: " . $conn->error;
        exit();
    }

    // Check if the category was found
    if (!$category) {
        echo "Category not found";
        exit();
    }
} else {
    // If no category ID is provided in the URL
    echo "Invalid category ID";
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Handle image upload
    $uploadsDirectory = '../../uploads/';

    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tempPath = $_FILES['image']['tmp_name'];
        $newFileName = uniqid() . '_' . $_FILES['image']['name'];
        $newPath = $uploadsDirectory . $newFileName;

        if (move_uploaded_file($tempPath, $newPath)) {
            // Successfully moved the uploaded image
            // Update category details in the database, including the new image path
            $sql = "UPDATE categories SET name = ?, description = ?, image_path = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                // Bind parameters and execute the statement
                $stmt->bind_param('sssi', $name, $description, $newPath, $categoryId);

                if ($stmt->execute()) {
                    // Category updated successfully
                    $_SESSION['edit_success'] = true;
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
        } else {
            // Failed to move the uploaded image
            $_SESSION['edit_error'] = "Failed to move the uploaded image.";
        }
    } else {
        // No new image provided, update only text fields
        $sql = "UPDATE categories SET name = ?, description = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters and execute the statement
            $stmt->bind_param('ssi', $name, $description, $categoryId);

            if ($stmt->execute()) {
                // Category updated successfully
                $_SESSION['edit_success'] = true;
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
    }

    // Redirect back to the same page
    header("Location: edit.php?id=" . $categoryId);
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
    <title>Edit Category - Dine-in Pe</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Include your CSS links here -->
</head>
<body>
    <div class="sidebar">
        <h2>Edit Category</h2>
        <ul>
            <li><a href="add.php">Add</a></li>
            <!-- <li><a class="actBtn" href="edit.php">Edit</a></li> -->
            <!-- <li><a href="delete.php">Delete</a></li> -->
            <li><a href="../index.php">Go Back</a></li>

            <br><br><br>
            <li><a href="#" id="logout">Log out</a></li>
        </ul>
    </div>
    <div class="container">
        <?php
        // Check if $category is set and not empty before displaying the form
        if (isset($category) && !empty($category)) {
        ?>
            <form action="edit.php?id=<?php echo $categoryId; ?>" method="post" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Category Name" class="name" value="<?php echo $category['name']; ?>" required>
                <textarea name="description" placeholder="Category Description" required><?php echo $category['description']; ?></textarea>

                <!-- Include this in your HTML form for image editing -->
                <label class="upload-btn" for="fileInput">Change Image</label>
                <input type="file" name="image" id="fileInput" accept="image/*" class="visually-hidden">
                <label class="file-name" id="fileNameLabel">No file chosen</label>

                <button type="submit">Update Category</button>
            </form>
        <?php
        } else {
            echo '<p>No category found</p>';
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
