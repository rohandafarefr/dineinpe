<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up - Dine-in Pe</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'header.php' ?>
    <div class="container-7">
        <p class="title">Sign up</p>
        <?php
        // Display error message if present
        if (isset($_GET['error']) && $_GET['error'] == "registration_failed") {
            echo '<p class="error-message">Registration failed. Please try again later.</p>';
        }
        ?>
        <form action="../public/backend/signup.php" method="post">
            <input type="name" name="name" placeholder="Name" required>
            <input type="tel" name="tel" placeholder="Number" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <div class="check-tnc">
                <input type="checkbox" name="tnc" class="tnc" required>
                <p class="agree-tnc">i agree to the terms & conditions & privacy policy</p>
            </div>
            <button type="submit">Sign up</button>
            <a href="./login.php" class="log-in-link"><p>Already have an account? Log in</p></a>
        </form>
    </div>
    <?php include 'footer.php' ?>
</body>
</html>