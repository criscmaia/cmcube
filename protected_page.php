<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Secure Login: Protected Page</title>
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>
    <?php if (login_check($mysqli) == true) : ?>
        <p>Welcome <b><?php echo htmlentities($_SESSION['username']); ?>!</b></p>
        <p>You are logged in so you have permission to upload an image. This image will only be avaiable to you, unless you give permission to another to access it.</p>
        <p>Return to <a href="login.php">login page</a></p>
        <p>Return to the <a href="index.php">home page</a>.</p>
        <hr />
        <?php include "./upload_front.php"; ?>
        <hr />
        <img src="./uploadedPictures/<?php list_images($mysqli) ?>" />
        <hr />
        <p><b>List of visitors:</b></p>
        <?php include "./visitors.php"; ?>
    <?php else : ?>
        <p><span class="error">You are not authorized to access this page.</span> Please <a href="login.php">login</a>.</p>
    <?php endif; ?>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
        $(document).foundation();
    </script>
</body>
</html>
