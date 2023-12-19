<?php
    $uploadsDirectory = '../../uploads/'; // Your chosen directory

    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tempPath = $_FILES['image']['tmp_name'];
        $newFileName = uniqid() . '_' . $_FILES['image']['name']; // Generate a unique filename
        $newPath = $uploadsDirectory . $newFileName;

        if (move_uploaded_file($tempPath, $newPath)) {
            // Successfully moved the uploaded image
            // Store $newPath in the database for later use
        } else {
            // Failed to move the uploaded image
        }
    } else {
        // Error handling for the uploaded image
    }
?>