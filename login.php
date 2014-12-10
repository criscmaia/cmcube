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
    <title>Log In</title>
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <script type="text/JavaScript" src="js/sha512.js"></script>
    <script type="text/JavaScript" src="js/forms.js"></script>
</head>
<body>
    <?php if (login_check($mysqli) == true) : ?>
    <p>You are currently logged <b><?php echo $logged ?></b>.</p>
    <p>Please use the menu above to navigate :) </p>
    <?php else : ?>

    <?php
              if (isset($_GET['error'])) {
                  echo '<p class="error">Error Logging In!</p>';
              }
    ?>
    <form action="includes/process_login.php" method="post" name="login_form" id="login">
        <fieldset name="login" style="max-width: 600px;">
            <legend>Login</legend>

            <label for="email">Email:</label>
            <input type="email" name="email" placeholder="email" autocomplete="on">

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="password">

            <input type="submit" value="Login" onclick="formhash(this.form, this.form.password);" />
        </fieldset>
    </form>

    <p>If you don't have a login, please <a href="register.php">register</a></p>
    <p>You are not logged in so you should not be able to access the <a href="protected_page.php">protected page</a>.</p>
    <?php endif; ?>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
        $(document).foundation();
    </script>
</body>
</html>
