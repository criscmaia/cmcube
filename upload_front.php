<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Upload Page</title>
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>
    <?php if (login_check($mysqli) == true) : ?>
    <div class="row">
        <div class="large-12 columns">
            <p>Upload a new image below:</p>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <div class="panel">
                <form action="upload.php" method="post" enctype="multipart/form-data">
                    <fieldset name="picture">
                        <div id="upload">
                            <output id="list"></output>
                            <input type="file" id="profileToUpload" name="profileToUpload" accept="image/*" class="small secondary button" />
                            <input type="submit" value="Upload Image" name="submit" class="small button" />
                            <script>
                                function handleFileSelect(evt) {
                                    var files = evt.target.files;                           // FileList object
                                    for (var i = 0, f; f = files[i]; i++) {                 // Loop through the FileList and render image files as thumbnails.

                                        if (!f.type.match('image.*')) {                     // Only process image files.
                                            continue;
                                        }

                                        var reader = new FileReader();
                                        reader.onload = (function (theFile) {               // Closure to capture the file information.
                                            return function (e) {

                                                var span = document.createElement('span');  // Render thumbnail.
                                                span.innerHTML = ['<img class="th" src="', e.target.result,
                                                                  '" title="', escape(theFile.name), '"/><br />'].join('');
                                                document.getElementById('list').insertBefore(span, null);
                                                document.getElementById('uploadTxt').firstChild.nodeValue = "";

                                            };
                                        })(f);

                                        reader.readAsDataURL(f);                            // Read in the image file as a data URL.
                                    }
                                }

                                document.getElementById('profileToUpload').addEventListener('change', handleFileSelect, false);
                            </script>
                        </div>
                    </fieldset>
                </form>
                <p>Oh! The layout adapts to whatever device you are using :)</p>
            </div>
        </div>
    </div>

    <?php else : ?>
    <p>
        <span class="error">You are not authorized to access this page.</span> Please <a href="login.php">login</a>.
    </p>
    <?php endif; ?>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
        $(document).foundation();
    </script>
</body>
</html>
