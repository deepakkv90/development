<?php
/*
  $Id: Help.inc.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/
?>
<table class="popupmain" cellspacing="0" cellpadding="0" border="0" align="center">
  <tr>
    <td class="pptext" colspan="2">The following is a quick guide towards simmulating your own IPNs:</td>
  </tr>
  <tr>
    <td class="ppsmalltext" valign="top">
    <br><ol>
      <li>Begin to check out as a customer via the store, stop when you get to the PayPal site</li><br><br class="h6">
      <li>Go into the store admin orders section and find the last order (just created)</li><br><br class="h6">
      <li>Select a <b>Transaction Type</b>, usually cart or web_accept, but nothing for refunds, reversals, or canceled_reversals payments</li><br><br class="h6">
      <li>Copy and paste the <b>Transaction Signature</b> into the <b>Custom</b> field and into the <b>Transaction ID</b> field</li><br><br class="h6">
      <li>If the <b>Cart Test</b> is on, then make sure that the above <b>MC Gross</b> amount is the same as the order total and that the <b>MC&nbsp;Currency</b> field is set to the same currency as the order.</li><br><br class="h6">
      <li>Submit the Test IPN</li><br><br class="h6">
      <li>Now check the admin order status</li><br><br class="h6">
    </ol></td>
  </tr>
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0" style="width: 100%; padding: 4px; border:1px solid #aaaaaa; background: #ffffcc;">
        <tr>
          <td><?php echo $page->image('icon_error_40x40.gif',IMAGE_ERROR); ?></td>
          <td><br class="text_spacer">&nbsp;</td>
          <td class="pperrorbold" nowrap="nowrap">When testing Pending payments etc, remember to use the same Transction ID</td>
        </tr></table></td>
  </tr>
</table>
