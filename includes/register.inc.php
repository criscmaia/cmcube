<?php
include_once 'db_connect.php';
include_once 'psl-config.php';

$error_msg = "";

if (isset($_POST['username'], $_POST['email'], $_POST['p'])) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);                               // Sanitize and validate the data passed in
    $email    = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email    = filter_var  ($email, FILTER_VALIDATE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg .= '<p class="error">The email address you entered is not valid</p>';                    // Not a valid email
    }
    
    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($password) != 128) {                                                                         // The hashed pwd should be 128 characters long.
        $error_msg .= '<p class="error">Invalid password configuration.</p>';                               // If it's not, something really odd has happened
    }
    
    // Username validity and password validity have been checked client side.
    
    $prep_stmt = "SELECT id FROM members WHERE email = ? LIMIT 1";                                          
    $stmt = $mysqli->prepare($prep_stmt);
    
    if ($stmt) {                                                                                            // check existing email  
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
            $error_msg .= '<p class="error">A user with this email address already exists.</p>';            // A user with this email address already exists
            $stmt->close();
        } else {
            $stmt->close();
        }
    } else {
        $error_msg .= '<p class="error">Database error.</p>';
        $stmt->close();
    }
    
    $prep_stmt = "SELECT id FROM members WHERE username = ? LIMIT 1";                                       // check existing username
    $stmt = $mysqli->prepare($prep_stmt);
    
    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {                                                                         // A user with this username already exists
            $error_msg .= '<p class="error">A user with this username already exists</p>';
            $stmt->close();
        } else {
            $stmt->close();
        }
    } else {
        $error_msg .= '<p class="error">Database error.</p>';
        $stmt->close();
    }
    
    if (empty($error_msg)) {
        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));                           // Create a random salt
        $password = hash('sha512', $password . $random_salt);                                               // Create salted password 
        
        // Insert the new user into the database 
        if ($insert_stmt = $mysqli->prepare("INSERT INTO members (username, email, password, salt) VALUES (?, ?, ?, ?)")) {
            $insert_stmt->bind_param('ssss', $username, $email, $password, $random_salt);
            
            if (! $insert_stmt->execute()) {                                                                // Execute the prepared query.
                header('Location: ../error.php?err=Registration failure: INSERT');
            }
        }
        header('Location: ./register_success.php');
    }
}