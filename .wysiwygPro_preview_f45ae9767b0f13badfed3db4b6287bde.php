<?php
if ($_GET['randomId'] != "iucSsCYzAQXmz7g3JS_9Wr9CS1R590fdLbMzcgbRffINxc6895aFN6eAC0ly6Ynk") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
