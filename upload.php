<?php
include_once './includes/psl-config.php';
include_once './includes/db_connect.php';
include_once './includes/functions.php';

sec_session_start();

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
    $username = htmlentities($_SESSION['username']);                                                        // get username
    $filename = sprintf('%s.%s', sha1_file($_FILES['profileToUpload']['tmp_name']),$ext);                   // filename with extension
    
    if ($insert_stmt = $mysqli->prepare("INSERT INTO image_access (username, filename) VALUES (?, ?)")) {   // insert into the database
        $insert_stmt->bind_param('ss', $username, $filename);
        $insert_stmt->execute;    
    }
    header('Location: ./register_success.php');  
    
    
    
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