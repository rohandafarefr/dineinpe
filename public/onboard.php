<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboard - Dine-in Pe</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'header.php' ?>
    <div class="container-8">
        <p class="title">Onboard</p>
        <?php
        // Display error message if present
        if (isset($_GET['error'])) {
            if ($_GET['error'] == "password_mismatch") {
                echo '<p class="error-message">Passwords do not match.</p>';
            } elseif ($_GET['error'] == "registration_failed") {
                echo '<p class="error-message">Registration failed. Please try again later.</p>';
            }
        }
        ?>
        <form action="backend/onboard.php" method="post">
            <div class="input-column">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="text" name="outlet-name" placeholder="Outlet Name" required>
            </div>
            <div class="input-column">
                <input type="tel" name="number" placeholder="Number" required>
                <input type="email" name="email" placeholder="Business Email" required>
            </div>
            <div class="input-column">
                <input type="text" name="address" placeholder="Outlet Address" required>
                <select name="outType" class="outType" value="Outlet-Type" required>
                    <option value="" disabled selected hidden>Outlet Type</option>
                    <option value="cafe">Cafe</option>
                    <option value="restaurant">Restaurant</option>
                    <option value="food-chain">Food Chain</option>
                </select>
            </div>
            <div class="input-column">
                <input type="password" name="password" placeholder="Create Password" required>
                <input type="password" name="c_password" placeholder="Confirm Password" required>
            </div>
            <div class="check-tnc">
                <input type="checkbox" name="tnc" class="tnc" required>
                <p class="agree-tnc">i agree to the terms & conditions & privacy policy</p>
            </div>
            <button type="submit">Lets get Onboard</button>
        </form>
    </div>
    <?php include 'footer.php' ?>
</body>
</html>