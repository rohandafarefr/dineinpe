<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Dine-in Pe</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'header.php' ?>
        <div class="container-10">
            <p class="title">Contact us</p>
            <form action="" method="post">
                <input type="email" name="email" placeholder="Email*" require>
                <input type="text" name="subject" placeholder="Subject*" require>
                <input type="text" name="msg-body" placeholder="Message - upto 250 words" require>
                <button type="submit">Send</button>
            </form>
        </div>
    <?php include 'footer.php' ?>
</body>
</html>