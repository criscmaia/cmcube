<?php
include_once 'psl-config.php';

function sec_session_start() {
    $session_name = 'sec_session_id';   // Set a custom session name
    $secure = SECURE;
    
    $httponly = true;                                                                   // This stops JavaScript being able to access the session id.    
    if (ini_set('session.use_only_cookies', 1) === FALSE) {                             // Forces sessions to only use cookies.
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    $cookieParams = session_get_cookie_params();                                        // Gets current cookies params.
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
    
    session_name($session_name);                                                        // Sets the session name to the one set above.
    session_start();                                                                    // Start the PHP session 
    session_regenerate_id();                                                            // regenerated the session, delete the old one. 
}

function login($email, $password, $mysqli) {
    // Using prepared statements means that SQL injection is not possible. 
    if ($stmt = $mysqli->prepare("SELECT id, username, password, salt FROM members WHERE email = ? LIMIT 1")) {
        $stmt->bind_param('s', $email);                                                 // Bind "$email" to parameter.
        $stmt->execute();                                                               // Execute the prepared query.
        $stmt->store_result();
        
        $stmt->bind_result($user_id, $username, $db_password, $salt);                   // get variables from result.
        $stmt->fetch();
        
        $password = hash('sha512', $password . $salt);                                  // hash the password with the unique salt.
        if ($stmt->num_rows == 1) {
            if (checkbrute($user_id, $mysqli) == true) {                                // If the user exists we check if the account is locked from too many login attempts 
                // Account is locked.  
                // Email user
                return false;
            } else {
                // Check if the password in the database matches
                // the password the user submitted.
                if ($db_password == $password) {
                    // Password is correct!
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];                        // Get the user-agent string of the user.
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);                 // XSS protection as we might print this value
                    $_SESSION['user_id'] = $user_id;
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/",  "",  $username);    // XSS protection as we might print this value
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512',  $password . $user_browser);
                    // Login successful.
                    return true;
                } else {
                    // Password is not correct
                    // We record this attempt in the database
                    $now = time();
                    $mysqli->query("INSERT INTO login_attempts(user_id, time) VALUES ('$user_id', '$now')");
                    return false;
                }
            }
        } else {
            return false;       // No user exists.
        }
    }
}

function checkbrute($user_id, $mysqli) {
    $now = time();                                                                      // Get timestamp of current time 
    $valid_attempts = $now - (2 * 60 * 60);                                             // All login attempts are counted from the past 2 hours. 
    
    if ($stmt = $mysqli->prepare("SELECT time  FROM login_attempts  WHERE user_id = ? AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();                                                               // Execute the prepared query. 
        $stmt->store_result();
        
        if ($stmt->num_rows > 10) {                                                     // If there have been more than 10 failed logins 
            return true;
        } else {
            return false;
        }
    }
}

function login_check($mysqli) {
    
    if (isset($_SESSION['user_id'],                                                     // Check if all session variables are set 
              $_SESSION['username'], 
              $_SESSION['login_string'])) {
        
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
        $user_browser = $_SERVER['HTTP_USER_AGENT'];                                    // Get the user-agent string of the user.
        
        if ($stmt = $mysqli->prepare("SELECT password  FROM members  WHERE id = ? LIMIT 1")) {
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $user_id);
            $stmt->execute();                                                           // Execute the prepared query.
            $stmt->store_result();
            
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($password);                                          // If the user exists get variables from result.
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);
                
                if ($login_check == $login_string) {
                    return true;                                                        // Logged In ! :)
                } else {
                    return false;                                                       // Not logged in :(
                }
            } else {
                return false;                                                           // Not logged in :(
            }
        } else {
            return false;                                                               // Not logged in 
        }
    } else {
        return false;                                                                   // Not logged in 
    }
}

function esc_url($url) {
    
    if ('' == $url) {
        return $url;
    }
    
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
    
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
    
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
    
    $url = str_replace(';//', '://', $url);
    
    $url = htmlentities($url);
    
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
    
    if ($url[0] !== '/') {
        return '';                                                                      // We're only interested in relative links from $_SERVER['PHP_SELF']
    } else {
        return $url;
    }
}

function list_images($mysqli) {
    $username = $_SESSION['username'];
    
    if ($stmt = $mysqli->prepare("SELECT filename FROM image_access WHERE username = ? LIMIT 1")) {
        $stmt->bind_param('i', $username);
        $stmt->execute();                                                           // Execute the prepared query.
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($filename);
            $stmt->fetch();
            echo $filename;
            //$login_check = hash('sha512', $password . $user_browser);
            
            //if ($login_check == $login_string) {
            //    return true;                                                        // Logged In ! :)
            //} else {
            //    return false;                                                       // Not logged in :(
            //}
        } else {
            //echo '<h1>No rows returned </h1>';
            echo 'none/no_image.jpg';                                                           // Not logged in :(
        }
    }

}