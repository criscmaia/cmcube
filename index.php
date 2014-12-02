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
    <title>Secure Login: Protected Page</title>
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>
    <?php if (login_check($mysqli) == true) : ?>
        <p>Hi <b><?php echo htmlentities($_SESSION['username']); ?></b>.</p>
    <?php else : ?>
        <p>Hi there</p>
    <?php endif; ?>
    
    <div id="loginDiv"></div>
    <script>
        $(document).ready(function () {
            $("#loginDiv").load("./login.php");
        });
    </script>
</body>
</html>
