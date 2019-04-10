<?php
/*
  $Id: checkout_splash.inc.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License

*/
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<title><?php echo STORE_NAME; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<style type="text/css">
body {background-color:#FFFFFF;}
body, td, div {font-family: verdana, arial, sans-serif;}
</style>
</head>
<body onload="return document.paypal_payment_info.submit();">
<?php echo "\n".tep_draw_form('paypal_payment_info', $this->form_paypal_url, 'post'); ?>
<table cellpadding="0" width="100%" height="100%" cellspacing="0" style="border:1px solid #003366;">
  <tr><td align="middle" style="height:100%; vertical-align:middle;">
    <div><?php if (tep_not_null(MODULE_PAYMENT_PAYPAL_PROCESSING_LOGO)) echo tep_image(DIR_WS_IMAGES . MODULE_PAYMENT_PAYPAL_PROCESSING_LOGO); ?></div>
    <div style="color:#003366"><h1><?php echo MODULE_PAYMENT_PAYPAL_TEXT_TITLE_PROCESSING . tep_image(DIR_WS_MODULES .'payment/paypal/images/period_ani.gif'); ?></h1></div>
    <div style="margin:10px;padding:10px;"><?php echo MODULE_PAYMENT_PAYPAL_TEXT_DESCRIPTION_PROCESSING?></div>
    <div style="margin:10px;padding:10px;"><input type="image" src="<?php echo tep_output_string(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/buttons/' . $language);?>/button_ppcheckout.gif" alt="<?php echo MODULE_PAYMENT_PAYPAL_IMAGE_BUTTON_CHECKOUT;?>" style="border:0;" title=" <?php echo MODULE_PAYMENT_PAYPAL_IMAGE_BUTTON_CHECKOUT;?> " /></div>
  </td></tr>
</table>
<?php echo $this->formFields()."\n"; ?>
</body></html>
<?php require(DIR_WS_MODULES . 'payment/paypal/application_bottom.inc.php'); ?>
