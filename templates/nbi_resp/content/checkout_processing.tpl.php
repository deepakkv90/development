<?php
/*
  $Id: checkout_processing.tpl.php,v 1.0.0.0 2008/01/16 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/ 

if (isset($$payment->form_action_url)) {
		$form_action_url = $$payment->form_action_url;
} else {
		$form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
}
echo "\n".tep_draw_form('checkout_processing', $form_action_url, 'post');
?>
<table cellpadding="0" width="100%" height="100%" cellspacing="0">
  <tr>
    <td style="height:100%; vertical-align:middle;">
      <div style="color:#003366" align="center"><h1><?php echo TEXT_ORDER_CHECKOUT_PROCESSING; ?></h1></div>
      <div style="margin:10px;padding:10px;" align="center"><?php echo TEXT_ORDER_CHECKOUT_DESCRIPTION_PROCESSING; ?></div>
      <div style="margin:10px;padding:10px;" align="right"><?php echo tep_template_image_submit('button_checkout.gif', IMAGE_BUTTON_CHECKOUT); ?></div>
    </td>
  </tr>
</table>
<?php
if (is_array($payment_modules->modules)) {
  $payment_modules->confirmation();     
  echo $payment_modules->process_button();
}
echo tep_draw_hidden_field('comments', $comments);
if (isset($_POST['cot_gv']) && $_POST['cot_gv'] == '1') {
  echo tep_draw_hidden_field('cot_gv', $_POST['cot_gv']);
}
?>
</form>