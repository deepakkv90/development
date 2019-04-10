<?php
/*
  $Id: cc_purge.php,v 1.0.0.0 2009/05/20 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
require('includes/application_top.php');

$version = (file_exists(DIR_WS_CLASSES . 'rci.php')) ? 'CRE' : 'OSC';
$action = (isset($_GET['action'])) ? $_GET['action'] : '';
$cc_number = '';
if ($action == 'process') {
  $method = (isset($_POST['method'])) ? $_POST['method'] : '';
  $order_query = tep_db_query("SELECT cc_number, orders_id, cc_expires from " . TABLE_ORDERS);
  while ($order = tep_db_fetch_array($order_query)) {
    $oID = $order['orders_id'];
    switch ($method) {
      case '1':
        $cc_number = '';
        break;
      case '2':
        $cc_number = 'XXXXXXXXXXXX' . substr($order['cc_number'], -4);  
        break;
      case '3':
        $cc_number = 'XXXXXX' . substr($order['cc_number'], 6, 6) . 'XXXX' . substr($order['cc_number'], -4);
        break;                
    }   
    tep_db_query("UPDATE " . TABLE_ORDERS . " SET cc_expires = '', cc_number = '" . $cc_number . "' WHERE orders_id = '" . $oID . "'");
  }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Credit Card Purge Utility Tool</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="body-table">
  <tr>
    <?php
    if ($version == 'OSC') {
      ?>
      <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
        <!-- left_navigation //-->
        <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
        <!-- left_navigation_eof //-->
      </table></td>      
      <?php  
    } else {
      ?>
      <!-- left_navigation //-->
      <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
      <!-- left_navigation_eof //-->
      <?php
    }
    ?>
    <!-- body_text //-->
    <td class="page-container" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr> 
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Credit Card Purge Utility Tool</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>      
      </tr>
      <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>      
      <tr>
        <td width="100%" valign="top" class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="menuBoxHeading">
        <p>To achieve PCI Compliance you will need to mask all credit card number information stored in the database. You must now make a decision about the method used to mask the credit card information. If you do not do this <u>YOU WILL NOT BE ABLE TO ACHIEVE PCI COMPLIANCE</u>.</p>  
        <p><b>WARNING: </b>Once this process is complete, you will no longer be able to see the entire credit card number.</p>
        </table></td>
      </tr>
      <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
      <?php
      if ($action == 'process') {
        ?>
        <tr>
          <td width="100%" valign="top"><table border="0" width="60%" cellspacing="0" cellpadding="2">
            <tr><td class="main">... Process Complete</td></tr>
            <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
          </table></td>
        </tr>         
        <?php
      } else {
        ?>
        <tr><?php echo tep_draw_form('purge', 'cc_purge.php', 'action=process', 'post'); ?>
          <td width="100%" valign="top"><table border="0" width="60%" cellspacing="0" cellpadding="2">
            <?php
            echo '<tr><td class="main">' . tep_draw_radio_field('method', '1', 'SELECTED') . ' Remove All Credit Card Info</td></tr>';
            echo '<tr><td class="main">' . tep_draw_radio_field('method', '2') . ' Mask All Except Last 4 Digits of Credit Card Info (XXXXXXXXXXXX1234)</td></tr>'; 
            echo '<tr><td class="main">' . tep_draw_radio_field('method', '3') . ' Mask Middle 6 of Credit Card Info (123456XXXXXX1234)</td></tr>';         
            ?>
            <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
            <tr>
              <td align="right" class="main" style="padding-right:70px;"><input type="submit" value="Submit"></td>
            </tr>
          </table></td></form>
        </tr>      
        <?php
      }
      ?>
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