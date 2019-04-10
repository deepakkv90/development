<?php
if ($_GET['randomId'] != "lfyPKXjVzsFjN3gDYDZrz46JWMbCg2lIGNmFkhMyoOy84u4n9sJBIiPYnjBGPUHf") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
