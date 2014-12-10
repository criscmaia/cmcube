<?php
include_once 'includes/register.inc.php';
include_once 'includes/functions.php';

sec_session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registration Form</title>
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/foundation.css" />
    <script type="text/JavaScript" src="js/sha512.js"></script>
    <script type="text/JavaScript" src="js/forms.js"></script>
    <script src="js/vendor/modernizr.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body style="max-width: 500px;">
    <?php if (login_check($mysqli) == true) : ?>
    <p>
        [ <a href="index.php">home page</a> |
             <a href="protected_page.php">upload image</a> |
             <a href="visitors_front.php">visitors log</a> |
             <a href="includes/logout.php">log out</a>
        ]
    </p>
    <h4>You are already logged in, <?php echo htmlentities($_SESSION['username']); ?>.</h4>
    <p>Please use the menu above to navigate :) </p>
    <?php else : ?>
    <p>
        [ <a href="index.php">home page</a> |
             <a href="login.php">login</a>
        ]
    </p>
    <h4>Register</h4>
    <ul>
        <li>Usernames may contain only digits, upper and lower case letters and underscores</li>
        <li>Emails must have a valid email format</li>
        <li>Passwords must be at least 6 characters long</li>
        <li>Passwords must contain
                        <ul>
                            <li>At least one upper case letter (A..Z)</li>
                            <li>At least one lower case letter (a..z)</li>
                            <li>At least one number (0..9)</li>
                        </ul>
        </li>
        <li>Your password and confirmation must match exactly</li>
    </ul>
    <?php
              if (!empty($error_msg)) {
                  echo $error_msg;
              }
    ?>
    <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>"  method="post" name="registration_form">
        <fieldset name="login">
            <legend>Register</legend>
            <label for="username">Username:</label>
            <input type='text' name='username' id='username' placeholder="username" /><br />

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="email" autocomplete="on"><br />

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="password"><br />

            <label for="confirmpwd">Confirm password:</label>
            <input type="password" name="confirmpwd" id="confirmpwd" placeholder="confirm password" /><br>

            <br />
            <input type="button" value="Register"
                onclick="return regformhash(this.form,
                                                   this.form.username,
                                                   this.form.email,
                                                   this.form.password,
                                                   this.form.confirmpwd);" />
        </fieldset>
    </form>
    <?php endif; ?>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
        $(document).foundation();
    </script>
</body>
</html>
