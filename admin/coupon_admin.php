<?php
/* 
  $Id: coupon_admin.php,v 1.2 2004/03/09 17:56:06 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
$page = (isset($_GET['page']) ? $_GET['page'] : '');
if ($_GET['action'] == 'send_email_to_user' && ($_POST['customers_email_address']) && (!$_POST['back_x'])) {
  switch ($_POST['customers_email_address']) {
    case '***':
      $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS);
      $mail_sent_to = TEXT_ALL_CUSTOMERS;
      break;
    case '**D':
      $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");
      $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
      break;
    default:
      $customers_email_address = strtolower(tep_db_prepare_input($_POST['customers_email_address']));
      $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where lower(customers_email_address) = '" . tep_db_input($customers_email_address) . "'");
      $mail_sent_to = $_POST['customers_email_address'];
      break;
  }
  $coupon_query = tep_db_query("select coupon_code from " . TABLE_COUPONS . " where coupon_id = '" . (int)$_GET['cid'] . "'");
  $coupon_result = tep_db_fetch_array($coupon_query);
  $coupon_name_query = tep_db_query("select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . (int)$_GET['cid'] . "' and language_id = '" . $languages_id . "'");
  $coupon_name = tep_db_fetch_array($coupon_name_query);
  $from = tep_db_prepare_input($_POST['from']);
  $subject = tep_db_prepare_input($_POST['subject']);
  while ($mail = tep_db_fetch_array($mail_query)) {
    if (EMAIL_USE_HTML == 'false') {
      $message = tep_db_encoder(tep_db_prepare_input($_POST['message'])) . "\n\n";
      $message .= sprintf(TEXT_TO_REDEEM_TEXT,HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '?gv_no='. $coupon_result['coupon_code']);
      $message .= TEXT_WHICH_IS . $coupon_result['coupon_code'] . TEXT_IN_CASE . "\n\n";
      $message .= TEXT_OR_VISIT . HTTP_SERVER  . DIR_WS_CATALOG  . TEXT_ENTER_CODE;
      $message .= TEXT_VOUCHER_IS . $coupon_result['coupon_code'] . "\n\n";
      $message .= TEXT_TO_REDEEM1 ;
      $message .= TEXT_REMEMBER . "\n";
    } else {
      $message = tep_db_encoder(tep_db_prepare_input($_POST['message'])) . "\n\n";
      $message .= sprintf(TEXT_TO_REDEEM_TEXT,'<a href="' . HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '?gv_no='. $coupon_result['coupon_code'] . '">' . HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '?gv_no='. $coupon_result['coupon_code'] . '</a>');
      $message .= TEXT_WHICH_IS . '<font color="red"><strong>' . $coupon_result['coupon_code'] . '</strong></font>' . TEXT_IN_CASE . "\n\n";
      $message .= TEXT_OR_VISIT . '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG  . TEXT_ENTER_CODE . '">' . STORE_NAME . '</a>';
      $message .= ' ' . TEXT_VOUCHER_IS . '<strong><u>' . $coupon_result['coupon_code'] . '</u></strong>' . "\n\n";
      $message .= TEXT_TO_REDEEM1 ;
      $message .= TEXT_REMEMBER . "\n";
    }
    // build a message object using the email class
    $mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));
    // add the message to the object
    if (EMAIL_USE_HTML == 'false') {
      $mimemessage->add_text($message);
    } else {
      $mimemessage->add_html($message);
    }
    $mimemessage->build_message();
    $mimemessage->send($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], '', $from, $subject);
    // create the coupon email entry
    $insert_query = tep_db_query("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . (int)$_GET['cid'] ."', '0', 'Admin', '" . $mail['customers_email_address'] . "', now() )");
  }
  tep_redirect(tep_href_link(FILENAME_COUPON_ADMIN, 'mail_sent_to=' . urlencode($mail_sent_to)));
}
if ( (isset($_GET['action']) && $_GET['action'] == 'preview_email') && (!$_POST['customers_email_address']) ) {
  $_GET['action'] = 'email';
  $messageStack->add('search', ERROR_NO_CUSTOMER_SELECTED, 'error');
}
if ( isset($_GET['mail_sent_to']) ) {
  $messageStack->add('search', sprintf(NOTICE_EMAIL_SENT_TO, $_GET['mail_sent_to']), 'success');
}
$coupon_id = ((isset($_GET['cid'])) ? tep_db_prepare_input($_GET['cid']) : '');
if ( isset($_GET['action']) ) {
  switch ($_GET['action']) {
    case 'setflag':
      if ( ($_GET['flag'] == 'N') || ($_GET['flag'] == 'Y') ) {
        if (isset($_GET['cid'])) {
          tep_set_coupon_status($coupon_id, $_GET['flag']);
        }
      }
      tep_redirect(tep_href_link(FILENAME_COUPON_ADMIN, '&cid=' . $_GET['cid']));
      break;
    case 'confirmdelete':
      $delete_query=tep_db_query("update " . TABLE_COUPONS . " set coupon_active = 'N' where coupon_id='".(int)$_GET['cid']."'");
      break;
     case 'update':
      // get all _POST and validate
      $_POST['coupon_code'] = trim($_POST['coupon_code']);
      $languages = tep_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $language_id = $languages[$i]['id'];
        if ($_POST['coupon_name'][$language_id]) $_POST['coupon_name'][$language_id] = trim($_POST['coupon_name'][$language_id]);
        if ($_POST['coupon_desc'][$language_id]) $_POST['coupon_desc'][$language_id] = trim($_POST['coupon_desc'][$language_id]);
      }
      $_POST['coupon_amount'] = str_replace($currencies->currencies[DEFAULT_CURRENCY]['symbol_left'], '', $_POST['coupon_amount']);
      $_POST['coupon_amount'] = str_replace($currencies->currencies[DEFAULT_CURRENCY]['symbol_right'], '', $_POST['coupon_amount']);
      $_POST['coupon_amount'] = trim($_POST['coupon_amount']);
      $update_errors = 0;
      if (!$_POST['coupon_name']) {
        $update_errors = 1;
        $messageStack->add('search', ERROR_NO_COUPON_NAME, 'error');
      }
      if ((!isset($_POST['coupon_amount'])) && (!isset($_POST['coupon_free_ship']))) {
        $update_errors = 1;
        $messageStack->add('search', ERROR_NO_COUPON_AMOUNT, 'error');
      }
      if (isset($_POST['coupon_code'])) $coupon_code = $_POST['coupon_code'];
      if ($coupon_code == '') $coupon_code = create_coupon_code();    
      $query1 = tep_db_query("select coupon_code from " . TABLE_COUPONS . " where coupon_code = '" . tep_db_input(tep_db_prepare_input($coupon_code)) . "'");
      if (tep_db_num_rows($query1) && $_POST['coupon_code'] && $_GET['oldaction'] != 'voucheredit')  {
        $update_errors = 1;
        $messageStack->add('search', ERROR_COUPON_EXISTS, 'error');
      }
      if ($update_errors != 0) {
        $_GET['action'] = 'new';
      } else {
        $_GET['action'] = 'update_preview';
      }
      break;
    case 'update_confirm':
       if(isset($_POST['Back']) ){
        $coupon_min_order = (($_POST['coupon_min_order'] == round($_POST['coupon_min_order'])) ? number_format($_POST['coupon_min_order']) : number_format($_POST['coupon_min_order'],2));
        $coupon_amount = (($_POST['coupon_amount'] == round($_POST['coupon_amount'])) ? number_format($_POST['coupon_amount']) : number_format($_POST['coupon_amount'],2));
           
            $_GET['action'] = 'new';
      } else {
      if ( isset($_POST['back_x']) || isset($_POST['back_y']) ) {
        $_GET['action'] = 'new';
      } else {
        $coupon_type = "F";
        $coupon_amount = $_POST['coupon_amount'];
        if (substr($_POST['coupon_amount'], -1) == '%') $coupon_type='P';
        if ($_POST['coupon_free_ship']) {
          $coupon_type = 'S';
          $coupon_amount = 0;
        }
        $sql_data_array = array('coupon_active' => tep_db_prepare_input($_POST['coupon_status']),
                                'coupon_code' => tep_db_prepare_input($_POST['coupon_code']),
                                /*******************/
                                    'coupon_sale_exclude' => tep_db_prepare_input($_POST['coupon_sale_exclude']),
                                /*******************/
                                'coupon_amount' => tep_db_prepare_input($coupon_amount),
                                'coupon_type' => tep_db_prepare_input($coupon_type),
                                'uses_per_coupon' => tep_db_prepare_input($_POST['coupon_uses_coupon']),
                                'uses_per_user' => tep_db_prepare_input($_POST['coupon_uses_user']),
                                'coupon_minimum_order' => tep_db_prepare_input($_POST['coupon_min_order']),
                                'restrict_to_products' => tep_db_prepare_input($_POST['coupon_products']),
                                'restrict_to_categories' => tep_db_prepare_input($_POST['coupon_categories']),
                                'coupon_start_date' => $_POST['coupon_startdate'],
                                'coupon_expire_date' => $_POST['coupon_finishdate'],
                                'date_modified' => 'now()');
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $language_id = $languages[$i]['id'];
          $sql_data_marray[$i] = array('coupon_name' => htmlspecialchars(tep_db_prepare_input($_POST['coupon_name'][$language_id])),
                                 'coupon_description' => htmlspecialchars(tep_db_prepare_input($_POST['coupon_desc'][$language_id]))
                                 );
        }
        if ($_GET['oldaction']=='voucheredit') {
          tep_db_perform(TABLE_COUPONS, $sql_data_array, 'update', "coupon_id='" . (int)$_GET['cid']."'");
          for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $language_id = $languages[$i]['id'];
            $update = tep_db_query("update " . TABLE_COUPONS_DESCRIPTION . " set coupon_name = '" . tep_db_input(tep_db_prepare_input($_POST['coupon_name'][$language_id])) . "', coupon_description = '" . tep_db_input(tep_db_prepare_input($_POST['coupon_desc'][$language_id])) . "' where coupon_id = '" . (int)$_GET['cid'] . "' and language_id = '" . $language_id . "'");
          }
        } else {
          $sql_data_array['date_created'] = ($_POST['date_created'] != '0') ? $_POST['date_created'] : 'now()';
          $query = tep_db_perform(TABLE_COUPONS, $sql_data_array);
          $insert_id = tep_db_insert_id();
          for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
            $language_id = $languages[$i]['id'];
            $sql_data_marray[$i]['coupon_id'] = $insert_id;
            $sql_data_marray[$i]['language_id'] = $language_id;
            tep_db_perform(TABLE_COUPONS_DESCRIPTION, $sql_data_marray[$i]);
          }
        }
      }
    }
  } // end switch
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
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript">
  var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
</script>
<script type="text/javascript" src="includes/javascript/tabpane/local/webfxlayout.js"></script>
<link type="text/css" rel="stylesheet" href="includes/javascript/tabpane/tab.webfx.css">
<style type="text/css">
.dynamic-tab-pane-control h2 {
  text-align: center;
  width:    auto;
}
.dynamic-tab-pane-control h2 a {
  display:  inline;
  width:    auto;
}
.dynamic-tab-pane-control a:hover {
  background: transparent;
}
</style>
<script type="text/javascript" src="includes/javascript/tabpane/tabpane.js"></script>
<?php
echo tep_load_html_editor();
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="body-table">
  <tr>
      <!-- left_navigation //-->
      <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
      <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <?php
    switch ($_GET['action']) {
      case 'report':
        ?>
        <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent"><?php echo CUSTOMER_ID; ?></td>
                    <td class="dataTableHeadingContent" align="center"><?php echo CUSTOMER_NAME; ?></td>
                    <td class="dataTableHeadingContent" align="center"><?php echo IP_ADDRESS; ?></td>
                    <td class="dataTableHeadingContent" align="center"><?php echo REDEEM_DATE; ?></td>
                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                  </tr>
                  <?php
                  $cc_query_raw = "select * from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . (int)$_GET['cid'] . "'";
                  $cc_query = tep_db_query($cc_query_raw);
                  $cc_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $cc_query_raw, $cc_query_numrows);
                  while ($cc_list = tep_db_fetch_array($cc_query)) {
                    $rows++;
                    if (strlen($rows) < 2) {
                      $rows = '0' . $rows;
                    }
                    if (((!$_GET['uid']) || (@$_GET['uid'] == $cc_list['unique_id'])) && (!$cInfo)) {
                      $cInfo = new objectInfo($cc_list);
                    }
                    if ( (is_object($cInfo)) && ($cc_list['unique_id'] == $cInfo->unique_id) ) {
                      echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link('coupon_admin.php', tep_get_all_get_params(array('cid', 'action', 'uid')) . 'cid=' . $cInfo->coupon_id . '&action=report&uid=' . $cinfo->unique_id) . '\'">' . "\n";
                    } else {
                      echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link('coupon_admin.php', tep_get_all_get_params(array('cid', 'action', 'uid')) . 'cid=' . $cc_list['coupon_id'] . '&action=report&uid=' . $cc_list['unique_id']) . '\'">' . "\n";
                    }
                    $customer_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $cc_list['customer_id'] . "'");
                    $customer = tep_db_fetch_array($customer_query);
                    ?>
                    <td class="dataTableContent"><?php echo $cc_list['customer_id']; ?></td>
                    <td class="dataTableContent" align="center"><?php echo $customer['customers_firstname'] . ' ' . $customer['customers_lastname']; ?></td>
                    <td class="dataTableContent" align="center"><?php echo $cc_list['redeem_ip']; ?></td>
                    <td class="dataTableContent" align="center"><?php echo tep_date_short($cc_list['redeem_date']); ?></td>
                    <td class="dataTableContent" align="right"><?php if ( (is_object($cInfo)) && ($cc_list['unique_id'] == $cInfo->unique_id) ) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png'); } else { echo '<a href="' . tep_href_link(FILENAME_COUPON_ADMIN, 'page=' . $_GET['page'] . '&cid=' . $cc_list['coupon_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                  </tr>
                  <?php
                  }
                  ?>
                </table></td>
              </tr>
            </table></td>
            <?php
            $heading = array();
            $contents = array();
            $coupon_description_query = tep_db_query("select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . (int)$_GET['cid'] . "' and language_id = '" . $languages_id . "'");
            $coupon_desc = tep_db_fetch_array($coupon_description_query);
            $count_customers = tep_db_query("select * from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . (int)$_GET['cid'] . "' and customer_id = '" . $cInfo->customer_id . "'");
            $heading[] = array('text' => '<b>[' . $_GET['cid'] . ']' . COUPON_NAME . ' ' . $coupon_desc['coupon_name'] . '</b>');
            $contents[] = array('text' => '<b>' . TEXT_REDEMPTIONS . '</b>');
            $contents[] = array('text' => TEXT_REDEMPTIONS_TOTAL . '=' . tep_db_num_rows($cc_query));
            $contents[] = array('text' => TEXT_REDEMPTIONS_CUSTOMER . ':' . tep_db_num_rows($count_customers));
            $contents[] = array('text' => '');
            echo '<td width="25%" valign="top">' . "\n";
            $box = new box;
            echo $box->infoBox($heading, $contents);
            echo '</td>' . "\n";
            ?>
          </tr>
        </table></td>
        <?php
        break;
                
      case 'preview_email':
        $coupon_query = tep_db_query("select coupon_code from " .TABLE_COUPONS . " where coupon_id = '" . (int)$_GET['cid'] . "'");
        $coupon_result = tep_db_fetch_array($coupon_query);
        $coupon_name_query = tep_db_query("select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . (int)$_GET['cid'] . "' and language_id = '" . $languages_id . "'");
        $coupon_name = tep_db_fetch_array($coupon_name_query);
        switch ($_POST['customers_email_address']) {
          case '***':
            $mail_sent_to = TEXT_ALL_CUSTOMERS;
            break;
          case '**D':
            $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
            break;
          default:
            $mail_sent_to = strtolower($_POST['customers_email_address']);
            break;
        }
        ?>
        <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr><?php echo tep_draw_form('mail', FILENAME_COUPON_ADMIN, 'action=send_email_to_user&cid=' . $_GET['cid']); ?>
            <td><table border="0" width="100%" cellpadding="0" cellspacing="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_CUSTOMER; ?></b><br><?php echo $mail_sent_to; ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_COUPON; ?></b><br><?php echo tep_db_encoder(tep_db_prepare_input($coupon_name['coupon_name'])); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_FROM; ?></b><br><?php echo tep_db_encoder(tep_db_prepare_input($_POST['from'])); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br><?php echo tep_db_encoder(tep_db_prepare_input($_POST['subject'])); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><?php echo tep_db_encoder(tep_db_prepare_input($_POST['message'])); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td>
                  <?php
                  /* Re-Post all POST'ed variables */
                  reset($_POST);
                  while (list($key, $value) = each($_POST)) {
                    if (!is_array($_POST[$key])) {
                      echo tep_draw_hidden_field($key, tep_db_encoder(tep_db_prepare_input($value))) . "\n";
                    }
                  }
                  ?>
                  <table border="0" width="100%" cellpadding="0" cellspacing="2">
                    <tr>
                      <td>&nbsp;</td>
                      <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_COUPON_ADMIN) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a> ' . tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?></td>
                    </tr>
                    <tr>
                      <td colspan="2" class="smallText">
                        <?php 
                        if (EMAIL_USE_HTML == 'false'){
                          echo(TEXT_EMAIL_BUTTON_HTML);
                        } else {
                          echo(TEXT_EMAIL_BUTTON_TEXT);
                        } 
                        ?>
                      </td>
                    </tr>
                  </table></td>
                </td></form>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <?php
        break;
              
      case 'email':
        $coupon_query = tep_db_query("select coupon_code from " . TABLE_COUPONS . " where coupon_id = '" . (int)$_GET['cid'] . "'");
        $coupon_result = tep_db_fetch_array($coupon_query);
        $coupon_name_query = tep_db_query("select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . (int)$_GET['cid'] . "' and language_id = '" . $languages_id . "'");
        $coupon_name = tep_db_fetch_array($coupon_name_query);
        // load editor only if html email option is true                  
        if (EMAIL_USE_HTML == 'true'){
            echo tep_insert_html_editor('message','simple','400');
        }
        echo tep_draw_separator('pixel_trans.gif', '100%', '15'); 
        ?>
        <div class="tab-pane" id="tabPane1">
          <script type="text/javascript">tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
          </script>
          <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table></td>
            </tr>
            <tr><?php echo tep_draw_form('mail', FILENAME_COUPON_ADMIN, 'action=preview_email&cid='. $_GET['cid']); ?>
              <td><table border="0" cellpadding="0" cellspacing="2">
                <tr>
                  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <?php
                $customers = array();
                $customers[] = array('id' => '', 'text' => TEXT_SELECT_CUSTOMER);
                $customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
                $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);
                $mail_query = tep_db_query("select customers_email_address, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " order by customers_lastname");
                while($customers_values = tep_db_fetch_array($mail_query)) {
                  $customers[] = array('id' => $customers_values['customers_email_address'],
                                       'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');
                }
                ?>
                <tr>
                  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="main"><?php echo TEXT_COUPON; ?>&nbsp;&nbsp;</td>
                  <td><?php echo $coupon_name['coupon_name']; ?></td>
                </tr>
                <tr>
                  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="main"><?php echo TEXT_CUSTOMER; ?>&nbsp;&nbsp;</td>
                  <td><?php echo tep_draw_pull_down_menu('customers_email_address', $customers, $_GET['customer']);?></td>
                </tr>
                <tr>
                  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="main"><?php echo TEXT_FROM; ?>&nbsp;&nbsp;</td>
                  <td><?php echo tep_draw_input_field('from', STORE_OWNER_EMAIL_ADDRESS); ?></td>
                </tr>
                <tr>
                  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="main"><?php echo TEXT_SUBJECT; ?>&nbsp;&nbsp;</td>
                  <td><?php echo tep_draw_input_field('subject'); ?></td>
                </tr>
                <tr>
                  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td>
                  <td class="main">
                    <?php echo tep_draw_textarea_field('message', 'hard', 60, 3, $message, 'style="width: 100%" '); ?>
                  </td>
                </tr>
                <tr>
                  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
              </table></td>
                <script type="text/javascript">
                  //<![CDATA[
                  setupAllTabs();
                  //]]>
                </script>
            </tr>
            <tr>
              <td  align="center">
                <?php 
                if (EMAIL_USE_HTML == 'false') { 
                    echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL, 'onClick="validate();return returnVal;"');
                } else {
                  echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); 
                }
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" class="smallText">
                <?php 
                 if (EMAIL_USE_HTML == 'false'){
                     echo(TEXT_EMAIL_BUTTON_HTML);
                 } else {
                  //   echo(TEXT_EMAIL_BUTTON_TEXT);
                 } 
                        ?>
              </td>
            </tr>
          </table></td></form>
        </div>
        <?php
        break;
        
      case 'update_preview': 
        $coupon_min_order = (($_POST['coupon_min_order'] == round($_POST['coupon_min_order'])) ? number_format($_POST['coupon_min_order']) : number_format($_POST['coupon_min_order'],2));
        /*$coupon_amount = (($_POST['coupon_amount'] == round($_POST['coupon_amount'])) ? number_format($_POST['coupon_amount']) : number_format($_POST['coupon_amount'],2));*/
        /************gsr********************/  
        $coupon_amount1 = $_POST['coupon_amount'];
        $coupon_amount = substr($coupon_amount1, -1);
        if($coupon_amount == '%') {
                $coupon_amount = $coupon_amount1;
        } else {
                 $coupon_amount = '$' .$coupon_amount1;
           }
                /****************************/       

        ?>
        <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td>
              <?php echo tep_draw_form('coupon', 'coupon_admin.php', 'action=update_confirm&oldaction=' . $_GET['oldaction'] . '&cid=' . $_GET['cid']); ?>
              <table border="0" width="100%" cellspacing="0" cellpadding="6">
                <tr>
                  <td align="left"><?php echo COUPON_STATUS; ?></td>
                  <td align="left"><?php echo (($_POST['coupon_status'] == 'Y') ? IMAGE_ICON_STATUS_GREEN : IMAGE_ICON_STATUS_RED); ?></td>
                </tr>
                <?php
                $languages = tep_get_languages();
                for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                  $language_id = $languages[$i]['id'];
                  ?>
                  <tr>
                    <td align="left"><?php echo COUPON_NAME; ?></td>
                    <td align="left"><?php echo $_POST['coupon_name'][$language_id]; ?></td>
                  </tr>
                  <?php
                }
                $languages = tep_get_languages();
                for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                  $language_id = $languages[$i]['id'];
                  ?>
                  <tr>
                    <td align="left"><?php echo COUPON_DESC; ?></td>
                    <td align="left"><?php echo $_POST['coupon_desc'][$language_id]; ?></td>
                  </tr>
                  <?php
                }
                ?>
                <tr>
                  <td align="left"><?php echo COUPON_AMOUNT; ?></td>
                  <td align="left"><?php if (!isset($_POST['coupon_free_ship'])) echo $coupon_amount; ?></td>
                </tr>
                 <tr>
                  <td align="left"><?php echo COUPON_MIN_ORDER; ?></td>
                  <td align="left"><?php echo $coupon_min_order; ?></td>
                </tr>
                <tr>
                  <td align="left"><?php echo COUPON_FREE_SHIP; ?></td>
                  <?php
                  if (isset($_POST['coupon_free_ship'])) {
                    ?>
                    <td align="left"><?php echo TEXT_FREE_SHIPPING; ?></td>
                    <?php
                  } else {
                    ?>
                    <td align="left"><?php echo TEXT_NO_FREE_SHIPPING; ?></td>
                    <?php
                  }
                  ?>
                </tr>
                <tr>
                  <td align="left"><?php echo COUPON_CODE; ?></td>
                  <?php
                  if (isset($_POST['coupon_code'])) {
                    $c_code = $_POST['coupon_code'];
                  } else {
                    $c_code = $coupon_code;
                  }
                  ?>
                  <td align="left"><?php echo $coupon_code; ?></td>
                </tr>
                <tr>
                  <td align="left"><?php echo COUPON_USES_COUPON; ?></td>
                  <td align="left"><?php echo $_POST['coupon_uses_coupon']; ?></td>
                </tr>
                <tr>
                  <td align="left"><?php echo COUPON_USES_USER; ?></td>
                  <td align="left"><?php echo $_POST['coupon_uses_user']; ?></td>
                </tr>
                <tr>
                  <td align="left"><?php echo COUPON_PRODUCTS; ?></td>
                  <td align="left"><?php echo $_POST['coupon_products']; ?></td>
                </tr>
                <tr>
                  <td align="left"><?php echo COUPON_SALE_EXCLUSION; ?></td>
                  <td align="left"><?php echo $_POST['coupon_sale_exclude']; ?></td>
                </tr>
                <tr>
                  <td align="left"><?php echo COUPON_CATEGORIES; ?></td>
                  <td align="left"><?php echo $_POST['coupon_categories']; ?></td>
                </tr>
                <tr>
                  <td align="left"><?php echo COUPON_STARTDATE; ?></td>
                  <?php
                  $start_date = date(DATE_FORMAT, mktime(0, 0, 0, $_POST['coupon_startdate_month'],$_POST['coupon_startdate_day'] ,$_POST['coupon_startdate_year'] ));
                  ?>
                  <td align="left"><?php echo $start_date; ?></td>
                </tr>
                <tr>
                  <td align="left"><?php echo COUPON_FINISHDATE; ?></td>
                  <?php
                  $finish_date = date(DATE_FORMAT, mktime(0, 0, 0, $_POST['coupon_finishdate_month'],$_POST['coupon_finishdate_day'] ,$_POST['coupon_finishdate_year'] ));
                  echo date('Y-m-d', mktime(0, 0, 0, $_POST['coupon_startdate_month'],$_POST['coupon_startdate_day'] ,$_POST['coupon_startdate_year'] ));
                  ?>
                  <td align="left"><?php echo $finish_date; ?></td>
                </tr>
                <?php
                echo tep_draw_hidden_field('coupon_status', $_POST['coupon_status']);
                $languages = tep_get_languages();
                for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                  $language_id = $languages[$i]['id'];
                  echo tep_draw_hidden_field('coupon_name[' . $languages[$i]['id'] . ']', tep_db_prepare_input($_POST['coupon_name'][$language_id]));
                  echo tep_draw_hidden_field('coupon_desc[' . $languages[$i]['id'] . ']', tep_db_prepare_input($_POST['coupon_desc'][$language_id]));
                }
                echo tep_draw_hidden_field('coupon_amount', $_POST['coupon_amount']);
                echo tep_draw_hidden_field('coupon_min_order', $_POST['coupon_min_order']);
                echo tep_draw_hidden_field('coupon_free_ship', (isset($_POST['coupon_free_ship']) ? $_POST['coupon_free_ship'] : ''));
                echo tep_draw_hidden_field('coupon_code', $c_code);
                echo tep_draw_hidden_field('coupon_uses_coupon', $_POST['coupon_uses_coupon']);

                echo tep_draw_hidden_field('coupon_uses_user', $_POST['coupon_uses_user']);
                echo tep_draw_hidden_field('coupon_sale_exclude', $_POST['coupon_sale_exclude']);

                echo tep_draw_hidden_field('coupon_products', $_POST['coupon_products']);
                echo tep_draw_hidden_field('coupon_categories', $_POST['coupon_categories']);
                echo tep_draw_hidden_field('coupon_startdate', date('Y-m-d', mktime(0, 0, 0, $_POST['coupon_startdate_month'],$_POST['coupon_startdate_day'] ,$_POST['coupon_startdate_year'] )));
                echo tep_draw_hidden_field('coupon_finishdate', date('Y-m-d', mktime(0, 0, 0, $_POST['coupon_finishdate_month'],$_POST['coupon_finishdate_day'] ,$_POST['coupon_finishdate_year'] )));
                echo tep_draw_hidden_field('date_created', date('Y-m-d'));
                ?>
                <tr>
                  <td align="left"><!-- <a href="<?php echo tep_href_link(FILENAME_COUPON_ADMIN, tep_get_all_get_params(array('action')) . 'action=new'); ?>"><?php echo tep_image_button('button_back.gif',COUPON_BUTTON_BACK, 'name=back'); ?></a> -->
                 <?php echo tep_image_submit('button_back.gif',COUPON_BUTTON_BACK, 'name=back'); ?> 
                  </td>
                  <td align="left"><?php echo tep_image_submit('button_confirm.gif',COUPON_BUTTON_CONFIRM); ?></td>
                </tr>
              </table></form>
            </td>
          </tr>
        </table></td>
        <?php
        break;
        
      case 'voucheredit':
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $language_id = $languages[$i]['id'];
          $coupon_query = tep_db_query("select coupon_name,coupon_description from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" .  (int)$_GET['cid'] . "' and language_id = '" . $language_id . "'");
          $coupon = tep_db_fetch_array($coupon_query);
          $coupon_name[$language_id] = $coupon['coupon_name'];
          $coupon_desc[$language_id] = $coupon['coupon_description'];
        }
        $coupon_query=tep_db_query("select coupon_active, coupon_code, coupon_amount, coupon_type, coupon_sale_exclude, coupon_minimum_order, coupon_start_date, coupon_expire_date, date_created, uses_per_coupon, uses_per_user, restrict_to_products, restrict_to_categories from " . TABLE_COUPONS . " where coupon_id = '" . (int)$_GET['cid'] . "'");
        $coupon=tep_db_fetch_array($coupon_query);

        $coupon_amount = (($coupon['coupon_amount'] == round($coupon['coupon_amount'])) ? number_format($coupon['coupon_amount']) : number_format($coupon['coupon_amount'],2));
        if ($coupon['coupon_type'] == 'P') {
          // not floating point value, don't display decimal info
          $coupon_amount = (($coupon_amount == round($coupon_amount)) ? number_format($coupon_amount) : number_format($coupon_amount,2)) . '%';
        }
        if ($coupon['coupon_type'] == 'S') {
          $coupon_free_ship .= true;
        }
        $coupon_min_order = (($coupon['coupon_minimum_order'] == round($coupon['coupon_minimum_order'])) ? number_format($coupon['coupon_minimum_order']) : number_format($coupon['coupon_minimum_order'],2));
        $coupon_code = $coupon['coupon_code'];
        $coupon_uses_coupon = $coupon['uses_per_coupon'];
        $coupon_uses_user = $coupon['uses_per_user'];
        $coupon_sale_exclude = $coupon['coupon_sale_exclude'];
        $coupon_products = $coupon['restrict_to_products'];
        $coupon_categories = $coupon['restrict_to_categories'];
        $date_created = $coupon['date_created'];
        $coupon_status = $coupon['coupon_active'];
        
      case 'new':
        // set default if not editing an existing coupon or showing an error
        $oldaction = (isset($_GET['oldaction']) ? $_GET['oldaction'] : '');
        $coupon_uses_user = (isset($coupon_uses_user) ? $coupon_uses_user : '');
        $date_created = (isset($date_created) ? $date_created : '');
        $cid = (isset($_GET['cid']) ? $_GET['cid'] : '');
        $coupon_startdate = (isset($_POST['coupon_startdate']) ? $_POST['coupon_startdate'] : '');
        $coupon_finishdate = (isset($_POST['coupon_finishdate']) ? $_POST['coupon_finishdate'] : '');
        if ($_GET['action'] == 'new' && !$oldaction == 'new') {
          if (!$coupon_uses_user) {
            $coupon_uses_user=1;
          }
          if (!$date_created) {
            $date_created = '0';
          }
        }
        if(!isset($coupon_status) && isset($_POST['coupon_status'])) {
          $coupon_status = $_POST['coupon_status'];
        }
        if (!isset($coupon_status)) $coupon_status = 'Y';
        switch ($coupon_status) {
          case 'N': $in_status = false; $out_status = true; break;
          case 'Y':
          default: $in_status = true; $out_status = false;
        }
        // set some defaults
        //if (!$coupon_uses_user) $coupon_uses_user = 1;
        ?>
        <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td>
              <?php
              echo tep_draw_form('coupon', 'coupon_admin.php', 'action=update&oldaction='. (($oldaction == 'voucheredit') ? $oldaction : $_GET['action']) . '&cid=' . $cid);

              if(!isset($coupon_name) && isset($_POST['coupon_name'])) {
                $coupon_name = $_POST['coupon_name'];
              }
              if(!isset($coupon_desc) && isset($_POST['coupon_desc'])) {
                $coupon_desc = $_POST['coupon_desc'];
              }
              if(!isset($coupon_amount) && isset($_POST['coupon_amount'])) {
                $coupon_amount = $_POST['coupon_amount'];
              }
              if(!isset($coupon_min_order) && isset($_POST['coupon_min_order'])) {
                $coupon_min_order = $_POST['coupon_min_order'];
              }
              if(!isset($coupon_free_ship) && isset($_POST['coupon_free_ship'])) {
                $coupon_free_ship = $_POST['coupon_free_ship'];
              }
              if(!isset($coupon_code) && isset($_POST['coupon_code'])) {
                $coupon_code = $_POST['coupon_code'];
              }
              if(!isset($coupon_uses_coupon) && isset($_POST['coupon_uses_coupon'])) {
                $coupon_uses_coupon = $_POST['coupon_uses_coupon'];
              }
              /*******************/
              if(!isset($coupon_sale_exclude) && isset($_POST['coupon_sale_exclude'])) {
                $coupon_sale_exclude = $_POST['coupon_sale_exclude'];
              }
                /*******************/
              if(!isset($coupon_uses_user) && isset($_POST['coupon_uses_user'])) {
                $coupon_uses_user = $_POST['coupon_uses_user'];
              }
              if(!isset($coupon_products) && isset($_POST['coupon_products'])) {
                $coupon_products = $_POST['coupon_products'];
              }
              if(!isset($coupon_categories) && isset($_POST['coupon_categories'])) {
                $coupon_categories = $_POST['coupon_categories'];
              }
              if(!isset($coupon_startdate) && isset($_POST['coupon_startdate'])) {
                $coupon_startdate = $_POST['coupon_startdate'];
              }
              if(!isset($coupon_finishdate) && isset($_POST['coupon_finishdate'])) {
                $coupon_finishdate = $_POST['coupon_finishdate'];
              }
              ?> 
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="form-label"><?php echo COUPON_STATUS; ?></td>
                  <td class="form-value"><?php echo tep_draw_radio_field('coupon_status', 'Y', $in_status) . '&nbsp;' . IMAGE_ICON_STATUS_GREEN . '&nbsp;' . tep_draw_radio_field('coupon_status', 'N', $out_status) . '&nbsp;' . IMAGE_ICON_STATUS_RED; ?></td>
                  <td class="main"><?php echo COUPON_STATUS_HELP; ?></td>
                </tr>
                <tr>
                  <td align="right" class="main"><?php echo COUPON_CODE; ?></td>
                  <td align="left"><?php echo tep_draw_input_field('coupon_code', (isset($coupon_code) ? $coupon_code : '')); ?></td>
                  <td align="left" class="main"><?php echo COUPON_CODE_HELP; ?></td>
                </tr>
                <?php
                $languages = tep_get_languages();
                for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                  $language_id = $languages[$i]['id'];
                  ?>
                  <tr>
                    <td class="form-label"><?php if ($i==0) echo COUPON_NAME; ?></td>
                    <td class="form-value"><?php echo tep_draw_input_field('coupon_name[' . $languages[$i]['id'] . ']', (isset($coupon_name[$language_id]) ? $coupon_name[$language_id] : '')) . '&nbsp;' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
                    <td align="left" class="main" width="40%"><?php if ($i==0) echo COUPON_NAME_HELP; ?></td>
                  </tr>
                  <?php
                }
                $languages = tep_get_languages();
                //editor
                  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                    $editor_desc_var .= 'coupon_desc[' . $languages[$i]['id'] . '],';
                  }
                  echo tep_insert_html_editor($editor_desc_var,'simple','200','100%');
                
                for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                  $language_id = $languages[$i]['id'];
                  ?>
                  <tr>
                    <td align="left" valign="top" class="main"><?php if ($i==0) echo COUPON_DESC; ?></td>
                    <td align="left" valign="top"><?php echo tep_draw_textarea_field('coupon_desc[' . $languages[$i]['id'] . ']','physical','24','3', (isset($coupon_desc[$language_id]) ? $coupon_desc[$language_id] : '')) . '&nbsp;' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
                    <td align="left" valign="top" class="main"><?php if ($i==0) echo COUPON_DESC_HELP; ?></td>
                  </tr>
                  <?php
                }
                ?>
                <tr>
                  <td align="left" class="main"><?php echo COUPON_AMOUNT; ?></td>
                  <td align="left"><?php echo tep_draw_input_field('coupon_amount', (isset($coupon_amount) ? $coupon_amount : '')); ?></td>
                  <td align="left" class="main"><?php echo COUPON_AMOUNT_HELP; ?></td>
                </tr>
                <!-- <tr>
                  <td align="left" class="main"><?php echo COUPON_MIN_ORDER; ?></td>
                  <td align="left"><?php echo tep_draw_input_field('coupon_min_order', (isset($coupon_min_order) ? $coupon_min_order : '')); ?></td>
                  <td align="left" class="main"><?php echo COUPON_MIN_ORDER_HELP; ?></td>
                </tr> -->
                <tr>
                  <td align="left" class="main"><?php echo COUPON_FREE_SHIP; ?></td>
                  <td align="left"><?php echo tep_draw_checkbox_field('coupon_free_ship', (isset($coupon_free_ship) ? $coupon_free_ship : '')); ?></td>
                  <td align="left" class="main"><?php echo COUPON_FREE_SHIP_HELP; ?></td>
                </tr>
                <!-- <tr>
                  <td align="left" class="main"><?php echo COUPON_CODE; ?></td>
                  <td align="left"><?php echo tep_draw_input_field('coupon_code', (isset($coupon_code) ? $coupon_code : '')); ?></td>
                  <td align="left" class="main"><?php echo COUPON_CODE_HELP; ?></td>
                </tr> -->
                <tr>
                  <td align="left" class="main"><?php echo COUPON_MIN_ORDER; ?></td>
                  <td align="left"><?php echo tep_draw_input_field('coupon_min_order', (isset($coupon_min_order) ? $coupon_min_order : '')); ?></td>
                  <td align="left" class="main"><?php echo COUPON_MIN_ORDER_HELP; ?></td>
                </tr>
                <tr>
                  <td align="left" class="main"><?php echo COUPON_USES_COUPON; ?></td>
                  <td align="left"><?php echo tep_draw_input_field('coupon_uses_coupon', (isset($coupon_uses_coupon) ? $coupon_uses_coupon : '')); ?></td>
                  <td align="left" class="main"><?php echo COUPON_USES_COUPON_HELP; ?></td>
                </tr>
                <tr>
                  <td align="left" class="main"><?php echo COUPON_USES_USER; ?></td>
                  <td align="left"><?php echo tep_draw_input_field('coupon_uses_user', (isset($coupon_uses_user) ? $coupon_uses_user : '')); ?></td>
                  <td align="left" class="main"><?php echo COUPON_USES_USER_HELP; ?></td>
                </tr>
<!--gsr  -->

          <?php 
              //print("coupon_sale_exclude:".$coupon_sale_exclude."<br>");
             if(isset($coupon_sale_exclude) && $coupon_sale_exclude == 1) {
             $s_coupon_sale_exclude = ' checked';
            } else {            
              $s_coupon_sale_exclude = '';
            }
            ?>
            <tr>
           <td align="left" class="main"><?php echo COUPON_SALE_EXCLUSION; ?></td>
           <td align="left">
            <input type="checkbox" name="coupon_sale_exclude" value="1" <?php echo $s_coupon_sale_exclude; ?> >
             <td align="left" class="main"><?php echo COUPON_SALE_EXCLUDE_HELP; ?></td>           
           
          </tr>
                <tr>
                  <td align="left" class="main"><?php echo COUPON_PRODUCTS; ?></td>
                  <td align="left">
                    <?php echo tep_draw_input_field('coupon_products', (isset($coupon_products) ? $coupon_products : '')); ?> 
                    <a href="javascript:void(0)" onclick="window.open('<?php echo tep_href_link('treeview.php', 'script=' . urlencode('window.opener.document.coupon.coupon_products.value = prod_value;
      window.opener.document.coupon.coupon_categories.value = cat_value;'), $request_type); ?>', 'popuppage', 'scrollbars=yes,resizable=yes,menubar=yes,width=400,height=600');"><?php echo VIEW; ?></a>
                  </td>
                  <td align="left" class="main"><?php echo COUPON_PRODUCTS_HELP; ?></td>
                </tr>
                <tr>
                  <td align="left" class="main"><?php echo COUPON_CATEGORIES; ?></td>
                  <td align="left">
                    <?php echo tep_draw_input_field('coupon_categories', isset($coupon_categories) ? $coupon_categories : '' ); ?>
                    <a href="javascript:void(0)" onclick="window.open('<?php echo tep_href_link('treeview.php', 'script=' . urlencode('window.opener.document.coupon.coupon_products.value = prod_value;window.opener.document.coupon.coupon_categories.value = cat_value;'), $request_type); ?>', 'popuppage', 'scrollbars=yes,resizable=yes,menubar=yes,width=400,height=600');"><?php echo VIEW; ?></a>
                  </td>
                  <td align="left" class="main"><?php echo COUPON_CATEGORIES_HELP; ?></td>
                </tr>
                <tr>
                  <?php
                  if ($_GET['action'] == 'new' && !$coupon_startdate && !$oldaction == 'new') {
                    $coupon_startdate = split("[-]", date('Y-m-d'));
                  } elseif (tep_not_null($coupon_startdate)) {
                    $coupon_startdate = split("[-]", $coupon_startdate);
                  } elseif (!$_GET['oldaction'] == 'new') {   // for action=voucheredit
                    $coupon_startdate = split("[-]", date('Y-m-d', strtotime($coupon['coupon_start_date'])));
                  } else {   // error is being displayed
                    $coupon_startdate = split("[-]", date('Y-m-d', mktime(0, 0, 0, $_POST['coupon_startdate_month'],$_POST['coupon_startdate_day'] ,$_POST['coupon_startdate_year'] )));
                  }
                  if ($_GET['action'] == 'new' && !$coupon_finishdate && !$oldaction == 'new') {
                    $coupon_finishdate = split("[-]", date('Y-m-d'));
                    $coupon_finishdate[0] = $coupon_finishdate[0] + 1;
                  } elseif (tep_not_null($_POST['coupon_finishdate'])) {
                    $coupon_finishdate = split("[-]", $_POST['coupon_finishdate']);
                  } elseif (!$_GET['oldaction'] == 'new') {   // for action=voucheredit
                    $coupon_finishdate = split("[-]", date('Y-m-d', strtotime($coupon['coupon_expire_date'])));
                  } else {   // error is being displayed
                    $coupon_finishdate = split("[-]", date('Y-m-d', mktime(0, 0, 0, $_POST['coupon_finishdate_month'],$_POST['coupon_finishdate_day'] ,$_POST['coupon_finishdate_year'] )));
                  }
                  ?>
                  <td align="left" class="main"><?php echo COUPON_STARTDATE; ?></td>
                  <td align="left"><?php echo tep_draw_date_selector('coupon_startdate', mktime(0,0,0, $coupon_startdate[1], $coupon_startdate[2], $coupon_startdate[0], 0)); ?></td>
                  <td align="left" class="main"><?php echo COUPON_STARTDATE_HELP; ?></td>
                </tr>
                <tr>
                  <td align="left" class="main"><?php echo COUPON_FINISHDATE; ?></td>
                  <td align="left"><?php echo tep_draw_date_selector('coupon_finishdate', mktime(0,0,0, $coupon_finishdate[1], $coupon_finishdate[2], $coupon_finishdate[0], 0)); ?></td>
                  <td align="left" class="main"><?php echo COUPON_FINISHDATE_HELP; ?></td>
                </tr>
                <?php
                echo tep_draw_hidden_field('date_created', $date_created);
                ?>
                <tr>
                  <td align="left" colspan="2"><?php echo '<a href="' . tep_href_link('coupon_admin.php', ''); ?>"><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>
                  <?php echo tep_image_submit('button_magnifier.png',COUPON_BUTTON_PREVIEW); ?></td>
                </tr>
              </table></form>
            </td>
          </tr>
        </table></td>
        <?php
        break;
        
      default:
        ?>
        <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="100%" colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                <td class="main" align="right"><?php echo tep_draw_form('status', FILENAME_COUPON_ADMIN, '', 'get'); ?>
<?php
              if (isset($_GET[tep_session_name()])) {
                echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
              }  
                  $status_array[] = array('id' => 'Y', 'text' => TEXT_COUPON_ACTIVE);
                  $status_array[] = array('id' => 'N', 'text' => TEXT_COUPON_INACTIVE);
                  $status_array[] = array('id' => 'R', 'text' => TEXT_COUPON_REDEEMED);
                  $status_array[] = array('id' => '*', 'text' => TEXT_COUPON_ALL);
                  if ( isset($_GET['status']) ) {
                    $status = tep_db_prepare_input($_GET['status']);
                  } else {
                    $status = 'Y';
                  }
                  echo HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', $status_array, $status, 'onChange="this.form.submit();"');
                  ?>
                  </form>
                </td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent"><?php echo COUPON_NAME; ?></td>
                    <td class="dataTableHeadingContent" align="center"><?php echo COUPON_AMOUNT; ?></td>
                    <td class="dataTableHeadingContent" align="center"><?php echo COUPON_CODE; ?></td>
                    <td class="dataTableHeadingContent" align="center"><?php echo TEXT_REDEMPTIONS; ?></td>
                    <td class="dataTableHeadingContent" align="center"><?php echo COUPON_STATUS; ?></td>
                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                  </tr>
                  <?php
                  if (isset($_GET['page']) && $_GET['page'] > 1) $rows = $_GET['page'] * 20 - 20;
                  if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
                    $cc_query_raw = "select c.coupon_active, c.coupon_id, c.coupon_code, c.coupon_amount, c.coupon_minimum_order, c.coupon_type, c.coupon_start_date,c.coupon_expire_date,c.uses_per_user,c.uses_per_coupon,c.restrict_to_products, c.restrict_to_categories, c.date_created,c.date_modified from " . TABLE_COUPONS . " c, " . TABLE_COUPONS_DESCRIPTION . " cd where c.coupon_id = cd.coupon_id and cd.language_id = '" . $_SESSION['languages_id'] . "' and cd.coupon_name like '%" . tep_db_input($_GET['search']) . "%'";
                  } elseif ($status == 'Y' || $status == 'N') {
                    $cc_query_raw = "select coupon_active, coupon_id, coupon_code, coupon_amount, coupon_minimum_order, coupon_type, coupon_start_date,coupon_expire_date,uses_per_user,uses_per_coupon,restrict_to_products, restrict_to_categories, date_created,date_modified from " . TABLE_COUPONS ." where coupon_active='" . tep_db_input($status) . "' and coupon_type != 'G'";
                  } else {
                    $cc_query_raw = "select coupon_active, coupon_id, coupon_code, coupon_amount, coupon_minimum_order, coupon_type, coupon_start_date,coupon_expire_date,uses_per_user,uses_per_coupon,restrict_to_products, restrict_to_categories, date_created,date_modified from " . TABLE_COUPONS . " where coupon_type != 'G'";
                  }
                  $cc_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $cc_query_raw, $cc_query_numrows);
                  $cc_query = tep_db_query($cc_query_raw);
                  while ($cc_list = tep_db_fetch_array($cc_query)) {
                    $redeem_query = tep_db_query("select redeem_date from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $cc_list['coupon_id'] . "'");
                    if ($status == 'R' && tep_db_num_rows($redeem_query) == 0) {
                      continue;
                    }
                    $rows++;
                    if (strlen($rows) < 2) {
                      $rows = '0' . $rows;
                    }
                    if (((!$_GET['cid']) || (@$_GET['cid'] == $cc_list['coupon_id'])) && (!$cInfo)) {
                      $cInfo = new objectInfo($cc_list);
                    }
                    if ( (is_object($cInfo)) && ($cc_list['coupon_id'] == $cInfo->coupon_id) ) {
                      echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link('coupon_admin.php', tep_get_all_get_params(array('cid', 'action')) . 'cid=' . $cInfo->coupon_id . '&action=edit') . '\'">' . "\n";
                    } else {
                      echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link('coupon_admin.php', tep_get_all_get_params(array('cid', 'action')) . 'cid=' . $cc_list['coupon_id']) . '\'">' . "\n";
                    }
                    $coupon_description_query = tep_db_query("select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $cc_list['coupon_id'] . "' and language_id = '" . $languages_id . "'");
                    $coupon_desc = tep_db_fetch_array($coupon_description_query);
                    ?>
                    <td class="dataTableContent"><?php echo $coupon_desc['coupon_name']; ?></td>
                    <td class="dataTableContent" align="center">
                      <?php
                      if ($cc_list['coupon_type'] == 'P') {
                        // not floating point value, don't display decimal info
                        echo (($cc_list['coupon_amount'] == round($cc_list['coupon_amount'])) ? number_format($cc_list['coupon_amount']) : number_format($cc_list['coupon_amount'],2)) . '%';
                      } elseif ($cc_list['coupon_type'] == 'S') {
                        echo TEXT_FREE_SHIPPING;
                      } else {
                        echo $currencies->format($cc_list['coupon_amount']);
                      }
                      ?>&nbsp;
                    </td>
                    <td class="dataTableContent" align="center"><?php echo $cc_list['coupon_code']; ?></td>
                    <td class="dataTableContent" align="center">   
                      <?php
                      echo tep_db_num_rows($redeem_query);   // number of redemptions
                      ?>
                    </td>
                    <td class="dataTableContent" align="center">
                      <?php
                      if ($cc_list['coupon_active'] == 'Y') {
                        echo tep_image(DIR_WS_IMAGES . 'accept.png', IMAGE_ICON_STATUS_GREEN, 16, 16) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_COUPON_ADMIN, 'action=setflag&flag=N&cid=' . $cc_list['coupon_id']) . '">' . tep_image(DIR_WS_IMAGES . 'cancel-off.png', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
                      } else {
                        echo '<a href="' . tep_href_link(FILENAME_COUPON_ADMIN, 'action=setflag&flag=Y&cid=' . $cc_list['coupon_id']) . '">' . tep_image(DIR_WS_IMAGES . 'accept-off.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'cancel.png', IMAGE_ICON_STATUS_RED, 16, 16);
                      }
                      ?>
                    </td>
                    <td class="dataTableContent" align="right"><?php if ( (is_object($cInfo)) && ($cc_list['coupon_id'] == $cInfo->coupon_id) ) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png'); } else { echo '<a href="' . tep_href_link(FILENAME_COUPON_ADMIN, 'page=' . $_GET['page'] . '&cid=' . $cc_list['coupon_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                    </tr>
                    <?php
                    $redeem_date = '';
                    while ($redeem_list = tep_db_fetch_array($redeem_query)) {   // retrieve last redeem date
                      $redeem_date = $redeem_list['redeem_date'];
                    }
                    if ( (is_object($cInfo)) && ($cc_list['coupon_id'] == $cInfo->coupon_id) ) {   // store for later
                      $rInfo = new objectInfo(array('redeem_date' => $redeem_date));
                    }
                  }
                  ?>
                  </table><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="smallText">&nbsp;<?php echo $cc_split->display_count($cc_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_COUPONS); ?>&nbsp;</td>
                        <td align="right" class="smallText">&nbsp;<?php echo $cc_split->display_links($cc_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], 'status=' . $status); ?>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . tep_href_link('coupon_admin.php', 'page=' . $_GET['page'] . '&cID=' . isset($cInfo->coupon_id) . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_NEW_COUPON) . '</a>'; ?></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
                <?php
                $heading = array();
                $contents = array();
                switch ($_GET['action']) {
                  case 'release':
                    break;
                  case 'report':
                    $heading[] = array('text' => '<b>' . TEXT_HEADING_COUPON_REPORT . '</b>');
                    $contents[] = array('text' => TEXT_NEW_INTRO);
                    break;
                  case 'neww':
                    $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_COUPON . '</b>');
                    $contents[] = array('text' => TEXT_NEW_INTRO);
                    $contents[] = array('text' => '<br>' . COUPON_NAME . '<br>' . tep_draw_input_field('name'));
                    $contents[] = array('text' => '<br>' . COUPON_AMOUNT . '<br>' . tep_draw_input_field('voucher_amount'));
                    $contents[] = array('text' => '<br>' . COUPON_CODE . '<br>' . tep_draw_input_field('voucher_code'));
                    $contents[] = array('text' => '<br>' . COUPON_USES_COUPON . '<br>' . tep_draw_input_field('voucher_number_of'));
                    break; 
                  default:
                    $heading[] = array('text'=> COUPON_CODE . ': ' . $cInfo->coupon_code);
                    $amount = $cInfo->coupon_amount;
                    if ($cInfo->coupon_type == 'P') {
                      // not floating point value, don't display decimal info
                      $amount = (($amount == round($amount)) ? number_format($amount) : number_format($amount,2)) . '%';
                    } else {
                      $amount = $currencies->format($amount);
                    }
                    $coupon_min_order = $currencies->format($cInfo->coupon_minimum_order);
                    if (isset($_GET['action']) && $_GET['action'] == 'voucherdelete') {
                      $contents[] = array('text'=> TEXT_CONFIRM_DELETE);
                      $contents[] = array('align' => 'center', 'text'=> '<br><a href="'.tep_href_link('coupon_admin.php','cid='.$cInfo->coupon_id,'NONSSL').'">'.tep_image_button('button_cancel.gif',IMAGE_CANCEL).'</a>' .
                                                   '<a href="'.tep_href_link('coupon_admin.php','action=confirmdelete&status=' . $status . (($_GET['page'] > 1) ? '&page=' . $_GET['page']: '') . '&cid='.$_GET['cid'],'NONSSL').'">'.tep_image_button('button_confirm.gif',IMAGE_CONFIRM).'</a>'
                                         );
                    } else {
                      $prod_details = NONE;
                      if ($cInfo->restrict_to_products) {
                        $prod_details = '<A HREF="listproducts.php?cid=' . $cInfo->coupon_id . '" TARGET="_blank" ONCLICK="window.open(\'listproducts.php?cid=' . $cInfo->coupon_id . '\', \'Valid_Categories\', \'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600\'); return false">' . VIEW . '</A>';
                      }
                      $cat_details = NONE;
                      if ($cInfo->restrict_to_categories) {
                        $cat_details = '<A HREF="listcategories.php?cid=' . $cInfo->coupon_id . '" TARGET="_blank" ONCLICK="window.open(\'listcategories.php?cid=' . $cInfo->coupon_id . '\', \'Valid_Categories\', \'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600\'); return false">' . VIEW . '</A>';
                      }
                      $coupon_name_query = tep_db_query("select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $cInfo->coupon_id . "' and language_id = '" . $languages_id . "'");
                      $coupon_name = tep_db_fetch_array($coupon_name_query);
                      $contents[] = array('text'=> '<br><center><a href="'.tep_href_link('coupon_admin.php','action=voucheredit&cid='.$cInfo->coupon_id,'NONSSL').'">'.tep_image_button('button_page_edit.png',COUPON_BUTTON_EDIT_VOUCHER).'</a>' .
                                                   '<a href="'.tep_href_link('coupon_admin.php','action=voucherdelete&status=' . $status . (($_GET['page'] > 1) ? '&page=' . $_GET['page']: '') . '&cid='.$cInfo->coupon_id,'NONSSL').'">'.tep_image_button('button_delete.gif',COUPON_BUTTON_DELETE_VOUCHER).'</a><br>' . 
                                                   '<a href="'.tep_href_link('coupon_admin.php','action=email&cid='.$cInfo->coupon_id,'NONSSL').'">'.tep_image_button('button_email.gif',COUPON_BUTTON_EMAIL_VOUCHER).'</a><br>' .
                                                   '<a href="'.tep_href_link('coupon_admin.php','action=report&cid='.$cInfo->coupon_id,'NONSSL').'">'.tep_image_button('button_report.gif',COUPON_BUTTON_VOUCHER_REPORT).'</a></center><br>' . 
                                                   COUPON_NAME . ':&nbsp;<b>' . $coupon_name['coupon_name'] . '</b><br>' .
                                                   COUPON_AMOUNT . ':&nbsp;<b>' . $amount . '</b><br>' .
                                                   REDEEM_DATE_LAST . ':&nbsp;<b>' . ((isset($rInfo->redeem_date)) ? tep_date_short($rInfo->redeem_date) : '') . '</b><br>' .
                                                   COUPON_MIN_ORDER . ':&nbsp;<b>' . $coupon_min_order . '</b><br>' .
                                                   COUPON_STARTDATE . ':&nbsp;<b>' . tep_date_short($cInfo->coupon_start_date) . '</b><br>' .
                                                   COUPON_FINISHDATE . ':&nbsp;<b>' . tep_date_short($cInfo->coupon_expire_date) . '</b><br>' .
                                                   COUPON_USES_COUPON . ':&nbsp;<b>' . $cInfo->uses_per_coupon . '</b><br>' .
                                                   COUPON_USES_USER . ':&nbsp;<b>' . $cInfo->uses_per_user . '</b><br>' .
                                                   COUPON_PRODUCTS . ':&nbsp;<b>' . $prod_details . '</b><br>' .
                                                   COUPON_CATEGORIES . ':&nbsp;<b>' . $cat_details . '</b><br>' .
                                                   DATE_CREATED . ':&nbsp;<b>' . tep_date_short($cInfo->date_created) . '</b><br>' .
                                                   DATE_MODIFIED . ':&nbsp;<b>' . tep_date_short($cInfo->date_modified) . '</b><br><br>'
                                          );
                    }
                    break;
                } // end switch
                ?>
              </tr>
            </table></td>
            <?php
            echo '<td width="25%" valign="top">' . "\n";
            $box = new box;
            echo $box->infoBox($heading, $contents);
            echo '</td>' . "\n";
            ?>
          </tr>
        </table></td>
        <?php
        break;
    } // end switch $_GET['action']
    ?>
    <!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>