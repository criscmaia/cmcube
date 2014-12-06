<?php
include_once './includes/psl-config.php';
include_once './includes/db_connect.php';
include_once './includes/functions.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES['profileToUpload']['error']) ||
        is_array($_FILES['profileToUpload']['error'])
    ) {
        throw new RuntimeException('You haven\'t selected any file to upload.');
    }

    // Check $_FILES['profileToUpload']['error'] value.
    switch ($_FILES['profileToUpload']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }
    
    // You should also check filesize here. 
    if ($_FILES['profileToUpload']['size'] > 1000000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }
    
    // DO NOT TRUST $_FILES['profileToUpload']['mime'] VALUE !!
    // Check MIME Type by yourself.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['profileToUpload']['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ),
        true
    )) {
        throw new RuntimeException('Invalid file format.');
    }
    
    
    // add user and file name to the database
    if (isset($_SESSION['user_id'],                                                     // Check if all session variables are set 
              $_SESSION['username'], 
              $_SESSION['login_string'])) {
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
        $user_browser = $_SERVER['HTTP_USER_AGENT'];                                    // Get the user-agent string of the user.
        
        echo '$user_id: ' . $user_id;
        echo '$login_string: ' . $login_string;
        echo '$username: ' . $username;
        echo '$user_browser: ' . $user_browser;        
        
    } else {
        echo '--- session is not set.';
    }
    
    
    $username = htmlentities($_SESSION['username']);
    echo '|| Username: ' . $username;
    
    $filename = sprintf('%s.%s', sha1_file($_FILES['profileToUpload']['tmp_name']),$ext);       // filename with extension
    
    // Insert the new user into the database 
    /*
    if ($insert_stmt = $mysqli->prepare("INSERT INTO image_access (username, filename) VALUES (?, ?)")) {
        $insert_stmt->bind_param('ss', $username, $filename);
        
        if (! $insert_stmt->execute()) {
            //header('location: ../error.php?err=registration failure: insert');
        }
    }
    //header('Location: ./register_success.php');
    */
    
    
    
    
    // You should name it uniquely.
    // DO NOT USE $_FILES['profileToUpload']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
    if (!move_uploaded_file(
            $_FILES['profileToUpload']['tmp_name'],
            sprintf('./uploadedPictures/%s.%s', sha1_file($_FILES['profileToUpload']['tmp_name']),
            $ext
        )
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    }

    echo 'File is uploaded successfully.';
    
}
catch (RuntimeException $e) {
    
    echo $e->getMessage();

}
?>