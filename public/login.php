<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in - Dine-in Pe</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'header.php' ?>

    <div class="container-6">
        <p class="title">Log in</p>
        <?php
        if (isset($_GET['error'])) {
            if ($_GET['error'] == "invalid_credentials") {
                echo '<p class="error-message">Incorrect email or password.</p>';
            } elseif ($_GET['error'] == "user_not_found") {
                echo '<p class="error-message">User not found.</p>';
            }
        }
        ?>
        <form action="../public/backend/login.php" method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Log in</button>
            <p class="for-pas">forgot your password?</p>
            <a href="./signup.php" class="sign-up-link">Don't have an account?, Sign up</a>
        </form>

    </div>

    <?php include 'footer.php' ?>
</body>
</html>