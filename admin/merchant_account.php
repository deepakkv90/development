<?php
/*
  $Id: merchant_account.php,v 1.0.0.0 2007/10/24 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
require('includes/application_top.php');

// clean html, extract only text
function cre_html2txt($string){
$search = array('@<script[^>]*?>.*?</script>@si', 
               '@<[\/\!]*?[^<>]*?>@si',
               '@<style[^>]*?>.*?</style>@siU',
               '@<![\s\S]*?--[ \t\n\r]*>@'
);
$text = preg_replace($search, '', $string);
return $text;
}

$my_account_query = tep_db_query ("select a.admin_id, a.admin_firstname, a.admin_lastname, a.admin_email_address, g.admin_groups_name from " . TABLE_ADMIN . " a, " . TABLE_ADMIN_GROUPS . " g where a.admin_id= " . $_SESSION['login_id'] . " and g.admin_groups_id= " . $_SESSION['login_groups_id'] . "");
$myAccount = tep_db_fetch_array($my_account_query);

$action = (isset($_GET['action']) ? $_GET['action'] : '');
  
$error = false;
if (isset($action) && ($action == 'send')) {

$company = tep_db_prepare_input($_POST['company']);
$full_name = tep_db_prepare_input($_POST['full_name']);
$telephone = tep_db_prepare_input($_POST['telephone']);
$nightphone = tep_db_prepare_input($_POST['nightphone']);
$country_id = tep_db_prepare_input($_POST['country_id']);
$country = tep_get_country_name($country_id);
$email_address = tep_db_prepare_input($_POST['email_address']);
$businessyears = tep_db_prepare_input($_POST['businessyears']);
$website = tep_db_prepare_input($_POST['website_url']);
$processing = tep_db_prepare_input($_POST['processing']);
$start_processing = tep_db_prepare_input($_POST['start_processing']);
$comments = tep_db_prepare_input($_POST['comments']);

if($company == '' || $full_name == '' || $telephone == '' || $website == '' || !tep_validate_email($email_address) || $full_name == 'Salvatore Iozzia' || $company == 'CRE Loaded Store' || $email_address == 'noreply@creforge.com') {
$error = true;
}

if(!$error) {
//all good send
$message = '';
$message = "\n\n" . sprintf(TEXT_EMAIL_BODY_TITLE,$full_name) . "\n\n" .
TEXT_COMPANY_NAME . ' ' . $company . "\n" .
TEXT_FULL_NAME . ' ' . $full_name . "\n" .
TEXT_TELEPHONE . ' ' . $telephone . "\n" .
TEXT_NIGHT_PHONE . ' ' . $nightphone . "\n" .
TEXT_COUNTRY . ' ' . $country . "\n" .
TEXT_EMAIL_ADDRESS . ' ' . $email_address . "\n" .
TEXT_YEARS_IN_BUSINESS . ' ' . $businessyears . "\n" . 
TEXT_WEBSITE . ' ' . $website . "\n" . 
TEXT_PROCESSING . ' ' . $processing . "\n" .
TEXT_START_PROCESSING . ' ' . $start_processing . "\n" .
TEXT_COMMENTS . "\n" . cre_html2txt($comments) . "\n" . 
CREM_EMAIL_SEPERATOR . "\n\n" . 
TEXT_CUSTOMER_IP . $_SERVER['REMOTE_ADDR'] . "\n" . 
TEXT_CUSTOMER_ISP . gethostbyaddr($_SERVER['REMOTE_ADDR']) . "\n";

@tep_mail(TEXT_SEND_TO_NAME, 'sales@creloaded.com', sprintf(TEXT_EMAIL_SUBJECT,$full_name), $message, $full_name, $email_address);
@tep_mail(TEXT_SEND_TO_NAME, 'application@cremerchant.com', sprintf(TEXT_EMAIL_SUBJECT,$full_name), $message, $full_name, $email_address);

}

} // end action

  if($error) $messageStack->add('merchant', TEXT_ALL_FIELDS_REQUIRED, 'warning');
  
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
<style type="text/css">
#frmCrem input {
  width: 250px;
}
#frmCrem input.noclass {
  width: auto;
}
span.inputRequirement {
  color:#f00;
}
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>  <!-- left_navigation //-->
  <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
  <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td><table border="0" width="100%" cellspacing="2" cellpadding="2">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <?php
if (isset($action) && ($action != 'send')) {
?>
              <tr>
                <td><!--Banner Script Start-->
                  <iframe src="messages.php?s=crem" frameborder="0" width="98%" height="300" scrolling="auto" allowtransparency="true"></iframe>
                  <!--Banner Script End--></td>
              </tr>
              <?php } else if(!$error){?>
              <tr>
                <td><?php echo TEXT_DESCRIPTION_CREM;?></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <?php } ?>
            </table></td>
        </tr>
        <tr>
          <td class="main" valign="top" align="center"><?php 
if ($messageStack->size('merchant') > 0) { echo $messageStack->output('merchant'); }

if (($error == true) || isset($action) && ($action != 'send')) {
echo tep_draw_form('frmCrem',FILENAME_MERCHANT_ACCOUNT,'action=send','post','id="frmCrem"');?>
            <table border="0" cellpadding="3" cellspacing="3">
              <tr>
                <td class="main"><?php echo TEXT_COMPANY_NAME;?> </td>
                <td class="main"><?php echo tep_draw_input_field('company',(isset($company) ? $company : STORE_NAME));?> <span class="inputRequirement">*</span></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_FULL_NAME;?> </td>
                <td class="main"><?php echo tep_draw_input_field('full_name',(isset($full_name) ? $full_name : $myAccount['admin_firstname'] . ' ' . $myAccount['admin_lastname']));?> <span class="inputRequirement">*</span></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_TELEPHONE;?> </td>
                <td class="main"><?php echo tep_draw_input_field('telephone', (isset($telephone) ? $telephone : ''));?> <span class="inputRequirement">*</span></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_NIGHT_PHONE;?> </td>
                <td class="main"><?php echo tep_draw_input_field('nightphone', (isset($nightphone) ? $nightphone : ''));?> </td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_COUNTRY;?> </td>
                <td class="main"><?php echo tep_draw_pull_down_menu('country_id', tep_get_countries(), (isset($country) ? $country_id : STORE_COUNTRY));?> <span class="inputRequirement">*</span></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_EMAIL_ADDRESS;?> </td>
                <td class="main"><?php echo tep_draw_input_field('email_address',(isset($email_address) ? $email_address : $myAccount['admin_email_address']));?> <span class="inputRequirement">*</span></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_YEARS_IN_BUSINESS;?> </td>
                <td class="main"><?php echo tep_draw_input_field('businessyears',(isset($businessyears) ? $businessyears : ''));?> <span class="inputRequirement">*</span></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_WEBSITE;?> </td>
                <td class="main"><?php echo tep_draw_input_field('website_url',(isset($website) ? $website : 'http://www.'))?> <span class="inputRequirement">*</span></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_PROCESSING;?> </td>
                <td class="main"><input class="noclass" name="processing" value="I am currently accepting credit cards" type="radio" />
                  I am currently accepting credit cards <br>
                  <input class="noclass" name="processing" value="I have not processed credit cards before." type="radio" />
                  I have not processed credit cards before. <br>
                  <span class="inputRequirement">*</span></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_START_PROCESSING;?> </td>
                <td class="main"><input class="noclass" name="start_processing" value="Now" type="radio" />
                  Now
                  <input class="noclass" name="start_processing" value="2 Weeks" type="radio" />
                  2 Weeks
                  <input class="noclass" name="start_processing" value="1 Month" type="radio" />
                  1 Month
                  <input class="noclass" name="start_processing" value="Longer" type="radio" />
                  Longer <span class="inputRequirement">*</span></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_COMMENTS;?> </td>
                <td class="main"><?php echo tep_draw_textarea_field('comments','soft','50','15',(isset($comments) ? $comments : ''));?></td>
              </tr>
              <tr>
                <td class="main" valign="top"></td>
                <td><a href="javascript:document.frmCrem.submit();"><?php echo tep_image_button('button_send.gif',IMAGE_SEND);?></a></td>
              </tr>
            </table>
            <?php
}            
            ?>
            </form></td>
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