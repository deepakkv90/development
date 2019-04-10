<?php
/*
  $Id: get_loaded.php,v 1.0 2008/06/06 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$hidden_fields = '';
// re-post all variables
reset($_POST);
foreach ($_POST as $key => $value) {
  if (is_array($value)) {
    $hidden_fields .= tep_draw_hidden_field($key, serialize($value));
  } else {
    $hidden_fields .= tep_draw_hidden_field($key, $value);
  }   
}
$page = (isset($_GET['page'])) ? $_GET['page'] : '';
switch ($page) {
  case 'product' :
    $this_page = $page;
    $title = defined('TEXT_POWERED_BY_CRE_NAG') ? sprintf(TEXT_POWERED_BY_CRE_NAG, $this_page) : '';
    $pID = (isset($_GET['pID'])) ? (int)$_GET['pID'] : 0;
    $cPath = (isset($_GET['cPath'])) ? $_GET['cPath'] : '';
    $filename = FILENAME_CATEGORIES;
    $params = 'cPath=' . $cPath . '&pID=' . $pID;
    break;
  case 'order' : 
  case 'edit_order' :
    $this_page = implode(' ', explode('_', $page));
    $title = defined('TEXT_POWERED_BY_CRE_NAG') ? sprintf(TEXT_POWERED_BY_CRE_NAG, $this_page) : '';
    $oID = (isset($_GET['oID'])) ? (int)$_GET['oID'] : 0; 
    $filename = ($page == 'order') ? FILENAME_ORDERS : FILENAME_EDIT_ORDERS;
    $params = 'action=update_order&oID=' . $oID;
    break;  
  case 'customer' :
    $this_page = $page;
    $title = defined('TEXT_POWERED_BY_CRE_NAG') ? sprintf(TEXT_POWERED_BY_CRE_NAG, $this_page) : '';
    $cID = (isset($_GET['cID'])) ? (int)$_GET['cID'] : 0;
    $filename = FILENAME_CUSTOMERS;
    $params = 'action=update&cID=' . $cID;
    break; 
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td>
              <?php
              $text = '<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center"><tr><td align="center" style="padding-top:120px;">' . "\n" .
                      '<table border="0" cellpadding="0" cellspacing="0" align="center" width="50%">' . "\n" . 
                      '  <tr>' . "\n" .
                      '    <td>' . "\n" .
                      tep_image(DIR_WS_IMAGES . 'popup-cre-logo.png') .
                      '    </td>' . "\n" .
                      '  </tr>' . "\n" .
                      '  <tr><td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td></tr>' . "\n" .
                      '  <tr>' . "\n" .
                      '    <td class="DATAContent">' . "\n" .
                      tep_draw_form('std_nag', $filename, $params, 'post', '', 'SSL') . ucwords($this_page) . '&nbsp;' . $title .
                      '    </td>' . "\n" .
                      '  </tr>' . "\n" .
                      '  <tr><td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td></tr>' . "\n" .
                      '  <tr>' . "\n" .
                      '    <td align="right">' . "\n" .
                      $hidden_fields . 
                      '      <input type="submit" name="button" id="button" value="Continue" />&nbsp;&nbsp;' . "\n" .
                      '    </form></td>' . "\n" .
                      '  </tr>' . "\n" .
                      '</table>' . "\n" .
                      '</td></tr></table>' . "\n";                
              echo $text;
              ?>
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
    <!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>