<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchant Dashboard - Dine-in Pe</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Add any other stylesheets or resources here -->
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Merchant Dashboard</h2>
        <ul>
            <li><a href="vier_orders.php" data-page="view_orders.php">View Orders</a></li>
            <li><a href="products.php" data-page="products.php">Products</a></li>
            <li><a href="category.php" data-page="category.php">Category</a></li>
            <li><a href="coupons.php" data-page="coupons.php">Coupons</a></li>
            <li><a href="edit_profile.php" data-page="edit_profile.php">Edit Profile</a></li>
            <li><a href="settings.php" data-page="settings.php">Settings</a></li>
            <br><br><br>
            <li><a href="#" id="logout">Log out</a></li>
        </ul>
    </div>

    <!-- Page Content -->
    <div class="content">
        <!-- Your content goes here -->
     
    </div>

    <!-- Add any footer or scripts here -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle sidebar link clicks
            $(".sidebar a").on("click", function(e) {
                e.preventDefault();

                // Remove active class from all links and add it to the clicked link
                $(".sidebar a").removeClass("active");
                $(this).addClass("active");

                // Get the data-page attribute value
                var page = $(this).data("page");

                // Load content using AJAX
                $(".content").load(page);
            });
        });
    </script>
    <script>
    $("#logout").on("click", function(e) {
        e.preventDefault();

        // Perform logout via AJAX or redirect directly
        $.ajax({
            url: "../public/backend/logout.php",
            type: "POST",
            success: function(response) {
                // Redirect to the login page
                window.location.href = "../public/login.php";
            },
            error: function(xhr, status, error) {
                // Handle error if needed
            }
        });
    });

    </script>


</body>
</html>
