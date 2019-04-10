<?php
/*
  $Id: create_order.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

*/

  require('includes/application_top.php');

// #### Get Available Customers

  $query = tep_db_query("select customers_id, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " ORDER BY customers_lastname DESC");
  $result = $query;

  if (tep_db_num_rows($result) > 0) {
    // Query Successful
    $SelectCustomerBox = "<select name='Customer'><option value=''>". BUTTON_TEXT_CHOOSE_CUST . "</option>\n";
    while($db_Row = tep_db_fetch_array($result)){
      $SelectCustomerBox .= "<option value='" . $db_Row["customers_id"] . "'";
      if(isset($_GET['Customer']) and $db_Row["customers_id"]==$_GET['Customer']) $SelectCustomerBox .= ' selected="selected" ';
      $SelectCustomerBox .= ">" . $db_Row["customers_lastname"] . " , " . $db_Row["customers_firstname"] . "</option>\n";
    }
    $SelectCustomerBox .= "</select>\n";
  }
//newcode below
  $query = tep_db_query("select code, value from " . TABLE_CURRENCIES . " ORDER BY code");
  $result = $query;
  
  if (tep_db_num_rows($result) > 0) {
    // Query Successful
    $SelectCurrencyBox = '<select name="Currency"><option value="" selected="selected">' . TEXT_SELECT_CURRENCY . "</option>\n";
    while($db_Row = tep_db_fetch_array($result)) { 
      $SelectCurrencyBox .= "<option value='" . $db_Row["code"] . " , " . $db_Row["value"] . "'";
      $SelectCurrencyBox .= ">" . $db_Row["code"] . "</option>\n";
    }
    $SelectCurrencyBox .= "</select>\n";
  }
  if(isset($_GET['Customer'])) {
    //$account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $_GET['Customer'] . "'");
    $account_query = tep_db_query("select c.*,ab.entry_telephone as customers_telephone from " . TABLE_CUSTOMERS . " c, ".TABLE_ADDRESS_BOOK." ab where c.customers_id = '" . $_GET['Customer'] . "' and c.customers_id = ab.customers_id");

    $account = tep_db_fetch_array($account_query);
    $customer = $account['customers_id'];
    $address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $_GET['Customer'] . "'");
    $address = tep_db_fetch_array($address_query);
  }

// #### Generate Page
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<title><?php echo TITLE_1;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
<?php require('includes/form_check.js.php'); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
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
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td><table bgcolor="#7c6bce" border="0" width="100%" cellspacing="1" cellpadding="1">
              <tr>
                <td height="30" class="main"><font color="#ffffff"><b><?php echo HEADING_STEP1 ?></b></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php
                echo tep_draw_form('select_customer', FILENAME_CREATE_ORDER, tep_get_all_get_params(array('action','select_customer')) . '', 'get', '', 'SSL') ;
                if (isset($_GET[tep_session_name()])) {
                  echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
                }
                echo  '<table border="0"><tr>' . "\n";
                echo  tep_draw_separator('pixel_trans.gif', '100%', '10');
                echo  '<td><font class="main"><b>' . TEXT_SELECT_CUST . '</b></font><br>' . $SelectCustomerBox . '</td>' . "\n";
                echo  '<td valign="bottom">' . tep_image_submit('submit.png', BUTTON_TEXT_SELECT_CUST) . '</td>' . "\n";
                echo  '</tr></table></form>' . "\n";
              ?>
                  <?php
                echo tep_draw_form('select_customer', FILENAME_CREATE_ORDER, tep_get_all_get_params(array('action','select_customer')) . '', 'get', '', 'SSL') ;
                //echo  '<form action="' . FILENAME_CREATE_ORDER . '" method="GET">' . "\n";
                if (isset($_GET[tep_session_name()])) {
                  echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
                }
                echo  '<table border="0"><tr>' . "\n";
                echo  '<td><font class="main"><b>' . TEXT_OR_BY . '</b></font><br><input type="text" name="Customer"></td>' . "\n";
                echo  '<td valign="bottom">' . tep_image_submit('submit.png', BUTTON_TEXT_CHOOSE_CUST) . '</td>' . "\n";
                echo  '</tr></table></form>' . "\n";
              ?>
                </td>
              </tr>
              <tr>
                <td width="100%" valign="top"><?php 
              echo tep_draw_form('account_edit', FILENAME_CREATE_ORDER_PROCESS, tep_get_all_get_params(array('action','create_order')) . '', 'post', 'onSubmit="return check_form(account_edit);"', 'SSL');
              echo (isset($account['customers_id']) ? tep_draw_hidden_field('customers_id', $account['customers_id']) : ''); 
              ?>
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                    </tr>
                    <tr>
                      <td class="pageHeading"><?php echo HEADING_CREATE; ?></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                </tr>
                <tr>
                  <td><?php
                    //onSubmit="return check_form();"
                    require(DIR_WS_MODULES . 'create_order_details.php');
                  ?>
                  </td>
                </tr>
              </table>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
              <tr>
                <td>
                <?php
                  if(isset($_REQUEST['Customer']) && $_REQUEST['Customer']!='') {
                ?>
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="main"><?php echo '<a href="javascript: history.go(-1)">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                      <td class="main" align="right"><?php echo tep_image_submit('button_confirm.gif', IMAGE_BUTTON_CONFIRM); ?></td>
                    </tr>
                  </table>
                  <?php
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
            </table></td>
        </tr>
      </table>
      </form></td>
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
<?php
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
