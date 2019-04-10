<?php
/*
  $Id: popup_image.php,v 1.1.1.1 2004/03/04 23:38:01 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_POPUP_IMAGE);

  $navigation->remove_current_page();

  $products_query = tep_db_query("select p.products_image, p.products_image_lrg, p.products_image_xl_1, p.products_image_xl_2, p.products_image_xl_3, p.products_image_xl_4, p.products_image_xl_5, p.products_image_xl_6, pd.products_name from " . TABLE_PRODUCTS . " p,  " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$_GET['pID'] . "' and p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $products = tep_db_fetch_array($products_query);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo $products['products_name']; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<script type="text/javascript"><!--
var i=0;
var s=0;
function resize() {
   if (navigator.appName == 'Netscape') i=40;
     if (window.navigator.userAgent.indexOf("SV1") != -1) s=20; //This browser is Internet Explorer in SP2.
      if (document.images[0]) window.resizeTo(document.images[0].width +30, document.images[0].height+60-i+s);
      self.focus();
    if (document.images[0]) {
    imgHeight = document.images[0].height+120-i;
    imgWidth = document.images[0].width+30;
    var height = screen.height;
    var width = screen.width;
    var leftpos = width / 2 - imgWidth / 2;
    var toppos = height / 2 - imgHeight / 2; 
    window.moveTo(leftpos, toppos);  
    window.resizeTo(imgWidth, imgHeight);
  }
}
//--></script>
</head>
<body onload="resize();">
<a href="javascript:;" onclick="javascript:top.window.close();"> 
<?php
     if (isset($_GET['image']) &&  ($_GET['image'] ==0) && ($products['products_image_lrg'] != '')) {
     echo tep_image(DIR_WS_IMAGES . $products['products_image_lrg'], $products['products_name']);
     } elseif (isset($_GET['image']) &&  $_GET['image'] ==1) {
     echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_1'], $products['products_name']);
     } elseif (isset($_GET['image']) &&  $_GET['image'] ==2) {
     echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_2'], $products['products_name']);
     } elseif (isset($_GET['image']) &&  $_GET['image'] ==3) {
     echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_3'], $products['products_name']);
     } elseif (isset($_GET['image']) &&  $_GET['image'] ==4) {
     echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_4'], $products['products_name']);
     } elseif (isset($_GET['image']) &&  $_GET['image'] ==5) {
     echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_5'], $products['products_name']);
     } elseif (isset($_GET['image']) &&  $_GET['image'] ==6) {
     echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_6'], $products['products_name']);
     } else
     echo tep_image(DIR_WS_IMAGES . $products['products_image'], $products['products_name']);
     ?>
     <?php echo  TEXT_CLOSE_WINDOW ;?>
</body>
</html>
<?php //require('includes/application_bottom.php'); ?>