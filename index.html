<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Upload page</title>
    
    <style>
        .thumb {
            height: 95px;
            border: 1px solid #000;
            margin: 10px 5px 0 0;
        }
    </style>
</head>
<body>

    <div class="row">
        <div class="large-12 columns">
            <h1>Upload page</h1>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <div class="panel">
                <h3>Use the form below to upload any images to the server </h3>

                <form action="upload.php" method="post" enctype="multipart/form-data">
                    <fieldset name="picture">
                        <div id="upload">
                            <div id="uploadTxt">Select the picture you want to upload</div>
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

                                                var span = document.createElement('span');                                      // Render thumbnail.
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


    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
        $(document).foundation();
    </script>
</body>
</html>
