<?php
/*
  $Id: mail.php,v 1.1.1.1 2004/03/04 23:38:43 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if ( ($action == 'send_email_to_user') && isset($_POST['customers_email_address']) && !isset($_POST['back_x']) ) {
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
        $mail_sent_to = strtolower($_POST['customers_email_address']);
        break;
    }

    $from = tep_db_prepare_input($_POST['from']);

// Added by maestro for Admin>Tools>Send Email Fix
    $subject = tep_db_prepare_input($_POST['email_subject']);
// Added by maestro for Admin>Tools>Send Email Fix

// Commented out by maestro for Admin>Tools>Send Email Fix
/*
    $subject_id = tep_db_prepare_input($_POST['email_subject']);
     $subjects_query = tep_db_query("SELECT email_subjects_name
                                     from " . TABLE_EMAIL_SUBJECTS . "
                                     where email_subjects_id = '" . $subject_id . "'");

    while($email_subjects = tep_db_fetch_array($subjects_query)) {
   $subject = $email_subjects['email_subjects_name'];
 }
*/
// Commented out by maestro for Admin>Tools>Send Email Fix

// changed from subject to email_subject for Contact US Email : DMG
    $message = tep_db_prepare_input($_POST['message']);

    //Let's build a message object using the email class
    $mimemessage = new email(array('X-Mailer: osCommerce'));
    // add the message to the object

// MaxiDVD Added Line For WYSIWYG HTML Area: BOF (Send TEXT Email when WYSIWYG Disabled)
    if (HTML_WYSIWYG_DISABLE_EMAIL == 'Disable') {
    $mimemessage->add_text($message);
    } else {
    $mimemessage->add_html($message);
    }
// MaxiDVD Added Line For WYSIWYG HTML Area: EOF (Send HTML Email when WYSIWYG Enabled)

    $mimemessage->build_message();
    while ($mail = tep_db_fetch_array($mail_query)) {
//      $mimemessage->send($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], '', $from, $subject);
//    }
// Changed for Contact US Email Subject : DMG
      $mimemessage->send($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], '', $from, EMAIL_SUBJECT.$subject);
    }


    tep_redirect(tep_href_link(FILENAME_MAIL, 'mail_sent_to=' . urlencode($mail_sent_to)));
  }

  if ( ($action == 'preview') && !isset($_POST['customers_email_address']) ) {
    $messageStack->add('search', ERROR_NO_CUSTOMER_SELECTED, 'error');
  }

  if (isset($_GET['mail_sent_to'])) {
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
<?php
  // Load Editor
  echo tep_load_html_editor();
  echo tep_insert_html_editor('message','advanced','500');
?>
       <script language="JavaScript">
<!-- Begin
  function init() {
    define('customers_email_address', 'string', 'Customer or Newsletter Group');
  }
//  End -->
</script>
</head>
<body OnLoad="init()" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- header //-->
<?php 
  require(DIR_WS_INCLUDES . 'header.php'); 
?>
<!-- header_eof //-->

<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
      <!-- left_navigation //-->
      <?php 
        require(DIR_WS_INCLUDES . 'column_left.php');
      ?>
      <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top" class="page-container">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%">
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <?php
              if ( ($action == 'preview') && isset($_POST['customers_email_address']) ) {
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
            <tr>
            <?php echo tep_draw_form('mail', FILENAME_MAIL, 'action=send_email_to_user'); ?>
            <td>
              <table border="0" width="100%" cellpadding="0" cellspacing="2">
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
                  <td class="smallText"><b><?php echo TEXT_FROM; ?></b><br><?php echo htmlspecialchars(stripslashes($_POST['from'])); ?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br><?php echo htmlspecialchars(stripslashes($_POST['email_subject'])); ?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b><?php echo TEXT_MESSAGE; ?></b><br><?php if (HTML_WYSIWYG_DISABLE_EMAIL == 'Enable') { echo (stripslashes($_POST['message'])); } else { echo htmlspecialchars(stripslashes($_POST['message'])); } ?></td>
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
                        echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
                      }
                    }
                  ?>
                  </td>
                </tr>
                <tr>
                  <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_MAIL) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a> ' . tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?></td>
                </tr>
                <tr>
                  <td class="smallText">
                  <?php 
                    if (HTML_WYSIWYG_DISABLE_EMAIL == 'Disable') {
                      echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="back"');
                    } 
                    if (HTML_WYSIWYG_DISABLE_EMAIL == 'Disable') {
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
        </table>
      </td>
    </form>
  </tr>
  <?php
    } else {
  ?>
  <tr>
  <?php 
    echo tep_draw_form('mail', FILENAME_MAIL, 'action=preview');
  ?>
    <td>
      <table border="0" cellpadding="0" cellspacing="2">
        <tr>
          <td colspan="2">
          <?php 
            echo tep_draw_separator('pixel_trans.gif', '1', '10'); 
          ?>
          </td>
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

        // Commented out by maestro for Admin>Tools>Send Email Fix
        /*
          $subjects = array();
          $subjects[] = array('id' => '', 'text' => TEXT_EMAIL_SUBJECTS);
          $subjects_query = tep_db_query("SELECT * from " . TABLE_EMAIL_SUBJECTS . "
                                                       ORDER BY email_subjects_name");
            while($email_subjects = tep_db_fetch_array($subjects_query)) {
              $subjects[] = array('id' => $email_subjects['email_subjects_id'],
                                'text' => $email_subjects['email_subjects_name']);
            }
        */
        // Commented out by maestro for Admin>Tools>Send Email Fix
        ?>
        <tr>
          <td class="main"><?php echo TEXT_CUSTOMER; ?></td>
          <td><?php echo tep_draw_pull_down_menu('customers_email_address', $customers, (isset($_GET['customer']) ? $_GET['customer'] : ''));?></td>
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
          <td><?php echo tep_draw_input_field('email_subject'); ?></td>
        </tr>
        <tr>
          <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td>
          <td><?php echo tep_draw_textarea_field('message', 'soft', '60', '25'); ?></td>
        </tr>
          <tr>
          <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td colspan="2" align="right">
          <?php 
            if (HTML_WYSIWYG_DISABLE_EMAIL == 'Enable') { 
              echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL, 'onClick="validate();return returnVal;"');
            } else {
              echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); 
            }
          ?>
          </td>
        </tr>
      </table>
    </td>
  </form>
</tr>
<?php
  }
?>
<!-- body_text_eof //-->
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php 
  require(DIR_WS_INCLUDES . 'footer.php'); 
?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php 
  require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>