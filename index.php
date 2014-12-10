<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home page</title>
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>
    <?php if (login_check($mysqli) == true) : ?>
    <p>
        [ <a href="index.php">home page</a> |
             <a href="protected_page.php">upload image</a> |
             <a href="visitors_front.php">visitors log</a> |
             <a href="includes/logout.php">log out</a>
        ]
    </p>
    <h4>Welcome <b><?php echo htmlentities($_SESSION['username']); ?>!</b></h4>
    <?php else : ?>
    <p>Hi there</p>
    <?php endif; ?>

    <div id="loginDiv"></div>
    <script>
        $(document).ready(function () {
            $("#loginDiv").load("./login.php");
        });
    </script>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
        $(document).foundation();
    </script>
</body>
</html>
