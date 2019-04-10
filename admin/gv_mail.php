<?php
/*
  $Id: gv_mail.php,v 1.1.1.1 2004/03/04 23:38:35 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
$currency_value = $currencies->currencies[$currency]['value'];
function tep_get_current_language($language_id) {
  $languages_query = tep_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " where languages_id = '" . $language_id . "' order by sort_order");
  while ($languages = tep_db_fetch_array($languages_query)) {
    $languages_array1 = array('id' => $languages['languages_id'],
                              'name' => $languages['name'],
                              'code' => $languages['code'],
                              'image' => $languages['image'],
                              'directory' => $languages['directory']);
  }
  return $languages_array1;
}
$languages = tep_get_languages();
$cur_language = tep_get_current_language($languages_id);
$languages_name = $cur_language['name'];
$languages_image = $cur_language['image'];
$languages_directory = $cur_language['directory'];
if ( (isset($_GET['action']) && $_GET['action'] == 'send_email_to_user') && ($_POST['customers_email_address'] || $_POST['email_to']) && (!$_POST['back_x']) ) {
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
      $mail_info = tep_db_fetch_array($mail_query);
      $mail_to_name = $mail_info['customers_firstname'] . ' ' .  $mail_info['customers_lastname'];
      $mail_sent_to = strtolower($_POST['customers_email_address']);
      if (isset($_POST['email_to']) && $_POST['email_to'] != '') {
        $mail_sent_to = strtolower($_POST['email_to']);
      }
      break;
  }
  $from = tep_db_prepare_input($_POST['from']);
  $subject = tep_db_prepare_input($_POST['subject']);
  $_POST['amount'] = str_replace($currencies->currencies[DEFAULT_CURRENCY]['symbol_left'], '', $_POST['amount']);
  $_POST['amount'] = str_replace($currencies->currencies[DEFAULT_CURRENCY]['symbol_right'], '', $_POST['amount']);
  $_POST['amount'] = trim($_POST['amount']);
  if (isset($mail_sent_to) && $mail_sent_to != '') {
    if (EMAIL_USE_HTML == 'false') {
      $id1 = create_coupon_code($mail_sent_to);
      $message = tep_db_encoder(tep_db_prepare_input($_POST['message']));
      $message .= "\n\n" . TEXT_GV_WORTH  . $currencies->format($_POST['amount'],true,DEFAULT_CURRENCY,$currency_value) . "\n\n";
      $message .= TEXT_TO_REDEEM_TEXT;
      $message .= TEXT_WHICH_IS . $id1 . TEXT_IN_CASE . "\n\n";
      $message .= TEXT_OR_VISIT . '<a href="' .  HTTP_SERVER  . DIR_WS_CATALOG . '">' . HTTP_SERVER  . DIR_WS_CATALOG  . '</a>' . TEXT_ENTER_CODE;
      $message .= TEXT_TO_REDEEM1 ;
      $message .= TEXT_REMEMBER . "\n";
    } else {
      $id1 = create_coupon_code($mail_sent_to);
      $message = tep_db_encoder(tep_db_prepare_input($_POST['message']));
      $message .= "\n\n" . TEXT_GV_WORTH  . $currencies->format($_POST['amount'],true,DEFAULT_CURRENCY,$currency_value) . "\n\n";
      $message .= TEXT_TO_REDEEM;
      $message .= TEXT_WHICH_IS . $id1 . TEXT_IN_CASE . "\n\n";
      $message .= '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '?gv_no='.$id1 .'">' .  HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '?gv_no='.$id1 . '</a>' . "\n\n";
      $message .= TEXT_OR_VISIT . '<a href="' .  HTTP_SERVER  . DIR_WS_CATALOG . '">' . HTTP_SERVER  . DIR_WS_CATALOG  . '</a>' . TEXT_ENTER_CODE;
      $message .= TEXT_TO_REDEEM1 ;
      $message .= TEXT_REMEMBER . "\n";
    }
    //Let's build a message object using the email class
    $mimemessage = new email(array('X-Mailer: ' . PROJECT_VERSION . ' mailer'));
    // add the message to the object
    if (EMAIL_USE_HTML == 'false') {
      $mimemessage->add_text($message);
    } else {
      $mimemessage->add_html($message);
    }
    if($mail_to_name == '') { $mail_to_name = $mail_sent_to;}
    $sender_query = tep_db_query ("select admin_id, admin_firstname, admin_lastname from " . TABLE_ADMIN . "  where admin_id= " . $_SESSION['login_id']);
    $sender = tep_db_fetch_array($sender_query);
    $sender_name = $sender['admin_firstname'] . ' ' . $sender['admin_lastname'];
    $sender_id = $sender['admin_id'];
    $mimemessage->build_message();
    if ($_POST['customers_email_address'] == '***') {
      while($tmp_db = tep_db_fetch_array($mail_query)) {
        $mail_to_name = $tmp_db['customers_firstname']. " ".$tmp_db['customers_lastname'];
        $mail_sent_to = $tmp_db['customers_email_address'];
        $mimemessage->send($mail_to_name, $mail_sent_to, '', $from, $subject);
      }  
    } else if ($_POST['customers_email_address'] == '**D') {
      while($tmp_db = tep_db_fetch_array($mail_query)) {
        $mail_to_name = $tmp_db['customers_firstname']. " ".$tmp_db['customers_lastname'];
        $mail_sent_to = $tmp_db['customers_email_address'];
        $mimemessage->send($mail_to_name, $mail_sent_to, '', $from, $subject);
      }  
    } else {
      $mimemessage->send($mail_to_name, $mail_sent_to, '', $from, $subject);
    }
    // Now create the coupon email entry
    $insert_query = tep_db_query("insert into " . TABLE_COUPONS . " (coupon_code, coupon_type, coupon_amount, date_created) values ('" . $id1 . "', 'G', '" . $_POST['amount'] . "', now())");
    $insert_id = tep_db_insert_id();
    $insert_query = tep_db_query("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $insert_id ."', '" . $sender_id . "', '" . $sender_name . "', '" . $mail_sent_to . "', now() )");
  }
  tep_redirect(tep_href_link(FILENAME_GV_MAIL, 'mail_sent_to=' . urlencode($mail_sent_to)));
}
if ( (isset($_GET['action']) && $_GET['action'] == 'preview') && (!$_POST['customers_email_address']) && (!$_POST['email_to']) ) {
  $messageStack->add('search', ERROR_NO_CUSTOMER_SELECTED, 'error');
}
if ( (isset($_GET['action']) && $_GET['action'] == 'preview') && (!$_POST['amount']) ) {
  $messageStack->add('search', ERROR_NO_AMOUNT_SELECTED, 'error');
}
if (isset($_GET['mail_sent_to']) && $_GET['mail_sent_to']) {
  $messageStack->add('search', sprintf(NOTICE_EMAIL_SENT_TO, $_GET['mail_sent_to']), 'success');
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
<script language="javascript"><!--
function disableButton(obj) {
  for (z=1; z < obj.length; z++) {
    if (obj[z].type == 'submit') {
      obj[z].disabled = true;
      break;
    }
  }
}
--></script>
<!-- Tabs code -->
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
<!-- End Tabs -->
<?php 
if (EMAIL_USE_HTML == 'true'){
  include('includes/javascript/editor.php');
  echo tep_load_html_editor();
}
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <?php
          if ( (isset($_GET['action']) && $_GET['action'] == 'preview') && ($_POST['customers_email_address'] || $_POST['email_to']) ) {
            switch ($_POST['customers_email_address']) {
              case '***':
                $mail_sent_to = TEXT_ALL_CUSTOMERS;
                break;
              case '**D':
                $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
                break;
              default:
                $mail_sent_to = strtolower($_POST['customers_email_address']);
                if (tep_not_null($_POST['email_to'])) {
                  $mail_sent_to = strtolower($_POST['email_to']);
                }
                break;
            }
            $_POST['amount'] = str_replace($currencies->currencies[DEFAULT_CURRENCY]['symbol_left'], '', $_POST['amount']);
            $_POST['amount'] = str_replace($currencies->currencies[DEFAULT_CURRENCY]['symbol_right'], '', $_POST['amount']);
            $_POST['amount'] = trim($_POST['amount']);
            ?>
            <tr><?php echo tep_draw_form('mail', FILENAME_GV_MAIL, 'action=send_email_to_user', 'post', 'onsubmit="disableButton(this);"'); ?>
              <td><table border="0" width="100%" cellpadding="2" cellspacing="2">
                <tr>
                  <td class="smallText"><b><?php echo TEXT_CUSTOMER; ?></b><br><?php echo $mail_sent_to; ?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b><?php echo TEXT_FROM; ?></b><br><?php echo tep_db_prepare_input($_POST['from']); ?></td>
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
                  <td class="smallText"><b><?php echo TEXT_AMOUNT; ?></b><br><?php echo $currencies->format(nl2br(tep_db_prepare_input($_POST['amount'])),true,DEFAULT_CURRENCY,$currency_value); ?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b>
                    <?php echo tep_db_encoder(tep_db_prepare_input($_POST['message'])); ?>
                  </td>
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
                    <table border="0" width="100%" cellpadding="2" cellspacing="2">
                      <tr>
                        <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_GV_MAIL) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a> ' . tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?></td>
                      </tr>
                      <tr>
                        <td class="smallText">
                          <?php 
                          if (EMAIL_USE_HTML == 'false') {
                            echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="back"');
                          } 
                          if (EMAIL_USE_HTML == 'false') {
                            echo(TEXT_EMAIL_BUTTON_HTML);
                          } else {
                            echo(TEXT_EMAIL_BUTTON_TEXT);
                          } 
                          ?>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table></td>
              </form>
            </tr>
            <?php
          } else {
            if (EMAIL_USE_HTML == 'true'){
              echo tep_insert_html_editor('message','simple','400');
            }
            echo tep_draw_separator('pixel_trans.gif', '100%', '15');
            ?>
            <div class="tab-pane" id="tabPane1">
              <script type="text/javascript">tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );</script>
              <?php echo tep_draw_form('mail', FILENAME_GV_MAIL, 'action=preview'); ?>
              <table width="100%" border="0" cellspacing="0" cellpadding="0" summary="tab table">
                <tr>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
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
                      <td class="main"><?php echo TEXT_CUSTOMER; ?></td>
                      <td><?php echo tep_draw_pull_down_menu('customers_email_address', $customers, (isset($_GET['customer']) ? $_GET['customer'] : ''));?></td>
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                      <td class="main"><?php echo TEXT_TO; ?></td>
                      <td><?php echo tep_draw_input_field('email_to'); ?><?php echo '&nbsp;&nbsp;' . TEXT_SINGLE_EMAIL; ?></td>
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                      <td class="main"><?php echo TEXT_FROM; ?></td>
                      <td><?php echo tep_draw_input_field('from', STORE_OWNER_EMAIL_ADDRESS); ?></td>
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                      <td class="main"><?php echo TEXT_SUBJECT; ?></td>
                      <td><?php echo tep_draw_input_field('subject'); ?></td>
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                      <td valign="top" class="main"><?php echo TEXT_AMOUNT; ?></td>
                      <td><?php echo tep_draw_input_field('amount'); ?></td>
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                      <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td> 
                      <td class="main"><?php echo tep_draw_textarea_field('message', 'soft', '60', '3', $message, ''); ?></td>              
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                  </table></td>
                  <?php 
                  if (EMAIL_USE_HTML == 'true') {
                    ?>
                    <script type="text/javascript">
                      //<![CDATA[
                      setupAllTabs();
                      //]]>
                    </script>
                    <?php
                  }
                  ?>
                </tr>
                <tr>
                  <td align="center">
                    <?php 
                    if (EMAIL_USE_HTML == 'false') { 
                      echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL, 'onClick="validate();return returnVal;"');
                    } else {
                      echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); 
                    }
                    ?>
                  </td>
                </tr>
              </table>
              </form>
            </div>
            <?php
          }
          ?>
          <!-- body_text_eof //-->
        </table></td>
      </tr>
    </table></td>
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