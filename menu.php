<?php
require_once 'env/db_config.php';

// Check if a merchant ID is provided in the URL
if (!isset($_GET['merchant_id']) || !is_numeric($_GET['merchant_id'])) {
    echo "Invalid merchant ID";
    exit();
}

$merchantId = $_GET['merchant_id'];

// Fetch merchant details from the database
$merchantSql = "SELECT * FROM merchants WHERE id = ?";
$merchantStmt = $conn->prepare($merchantSql);

if ($merchantStmt) {
    $merchantStmt->bind_param('i', $merchantId);
    $merchantStmt->execute();
    $merchantResult = $merchantStmt->get_result();
    $merchant = $merchantResult->fetch_assoc();
    $merchantStmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

// Fetch categories for the selected merchant
$categorySql = "SELECT * FROM categories WHERE merchant_id = ?";
$categoryStmt = $conn->prepare($categorySql);

if ($categoryStmt) {
    $categoryStmt->bind_param('i', $merchantId);
    $categoryStmt->execute();
    $categoryResult = $categoryStmt->get_result();
    $categories = $categoryResult->fetch_all(MYSQLI_ASSOC);
    $categoryStmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

// Check if a category ID is provided in the URL
if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
    $categoryId = $_GET['category_id'];

    // Fetch products for the selected category
    $productSql = "SELECT * FROM products WHERE category_id = ?";
    $productStmt = $conn->prepare($productSql);

    if ($productStmt) {
        $productStmt->bind_param('i', $categoryId);
        $productStmt->execute();
        $productResult = $productStmt->get_result();
        $products = $productResult->fetch_all(MYSQLI_ASSOC);
        $productStmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
        exit();
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
    <title><?php echo $merchant['name']; ?> - Menu</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Adjust the path as needed -->
    <!-- Include your CSS links here -->
    <style>
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

        .categories {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .category {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            position: relative;
        }

        .category img {
            max-width: 200px;
            height: auto;
            border-radius: 5px;
        }

        .category-overlay {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            justify-content: center;
            align-items: center;
        }

        .category-overlay img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .product {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
        }

        .product img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo $merchant['outlet_name']; ?> - Menu</h2>

        <?php if (!empty($categories)) : ?>
            <div class="categories">
                <?php foreach ($categories as $category) : ?>
                    <a href="menu.php?merchant_id=<?php echo $merchantId; ?>&category_id=<?php echo $category['id']; ?>" class="category">
                        <img src="<?php echo $category['image_path']; ?>" alt="<?php echo $category['name']; ?>">
                        <?php echo $category['name']; ?>
                        <div class="category-overlay">
                            <img src="<?php echo $category['image_path']; ?>" alt="<?php echo $category['name']; ?>">
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p>No categories found for this merchant.</p>
        <?php endif; ?>

        <?php if (isset($products) && !empty($products)) : ?>
            <div class="products">
                <?php foreach ($products as $product) : ?>
                    <div class="product">
                        <img src="<?php echo $product['image_path']; ?>" alt="<?php echo $product['name']; ?>">
                        <p><?php echo $product['name']; ?></p>
                        <p><?php echo $product['description']; ?></p>
                        <p>₹<?php echo number_format($product['price'], 2); ?> /-</p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif (isset($categoryId)) : ?>
            <p>No products found for this category.</p>
        <?php endif; ?>
    </div>
</body>
</html>
