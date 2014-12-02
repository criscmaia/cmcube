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
</head>
<body>
    <h1>Welcome :)</h1>
    <div id="loginDiv"></div>

    <script>
        $(document).ready(function () {
            $("#loginDiv").load("./login.php");
        });
    </script>
</body>
</html>
