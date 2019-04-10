<?php
/*
  $Id: Help_Config.inc.php,v 2.8 2004/09/11 devosc Exp $

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
    <td>
  <H3><b> PayPal Setup </h3> </b><br>
   Please use your browsers back button to return.    
    </td>
  </tr>
  <tr>
    <td class="pptext">The following is a guide towards configuring your store's PayPal payment module settings:</td>
  </tr>
  <tr>
    <td class="pptext" valign="top">
    <ul style="margin: 10px 0px 0px 0px; padding: 0px 0px 0px 5px; list-style-type: none;">
      <li><b class="ppem380">Enable PayPal Module</b><br>Since you have just done the installation, by default it will say yes.</li><br>
      <li><b class="ppem380">E-Mail Address</b><br>Enter your Primary PayPal email address here, if you have more than one email address registered with PayPal then you must login your PayPal account to determine which one is your <b>Primary</b> email address.</li><br>
      <li><b class="ppem380">Business ID</b><br>If you have configured a secondary email address to be your Business ID (email address) in your PayPal profile account, then enter this here, otherwise enter the one used above.</li><br>
      <li><b class="ppem380">Transaction Currency</b><br>Choose which currencies you want to accept PayPal payments with.</li><br>
      <li><b class="ppem380">Payment Zone</b><br>If a zone is selected, enable this payment method for that zone only.</li><br>
      <li><b class="ppem380">Set <b>Pending Notification</b> Order Status</b><br>Set the Pending Notification status of orders made with this payment module to this value, '<b style="color:blue">Pending</b>' is suggested or alternatively the optional order status created in step 4 above.</li><br>
      <li><b class="ppem380">Set <b>Order</b> Status</b><br>Set the status of orders made with this payment module to this value <b style="color:red">Do not set it to 'default' but specifically choose the status required</b>, '<b style="color:blue">Processing</b>' is suggested.</li><br>
      <li><b class="ppem380">Set <b>On Hold Order</b> Status</b><br>This order status is used when the Cart Test has failed, eg. the expected payment amount did not match.</li><br>
      <li><b class="ppem380">Set <b>Canceled Order</b> Status</b><br>Refunded, Reversed, Failed or Denied payments will be set to this order status.</li><br>
      <li><b class="ppem380">Synchronize Invoice</b><br>Specify whether the osCommerce order number should be used as a <b>unique</b> PayPal invoice number, similiar to PayPal's Send Money feature, this will enable customers to resume an order via their osCommerce account history info page for that order for pending transactions which were not previously completed.</li><br>
      <li><b class="ppem380">Sort order of display</b><br>Sort order of display. Lowest is displayed first.</li><br>
      <li><b class="ppem380">Background Color</b><br>Select the background color of PayPal's payment pages, either black or white.</li><br>
      <li><b class="ppem380">Processing logo</b><br>Specify the image file name to be used in the store's checkout process splash page, must be located in <code>catalog/images</code> directory.</li><br>
      <li><b class="ppem380">Store logo</b><br>Specify the image file name for PayPal to display on their payment pages pages, must be located in <code>catalog/images</code> directory. If you do not have SSL then leave this field blank.</li><br>
      <li><b class="ppem380">PayPal Page Style Name</b><br>Specify the name of the page style you have configured in your PayPal account profile.</li><br>
      <li><b class="ppem380">Include a note with payment</b><br>When a customer arrives at the PayPal payment page you can choose whether or not to show them another note field which will have seperate contents from that of the one provided in your store's checkout process but will be included in PayPal's invoice and receipt.<br>To change the title appearing above this field on the PayPal payment page look in the corresponding catalog langauge file (<code>catalog/includes/langauges/english/modules/payment/paypal.php</code>).</li><br>
      <li><b class="ppem380">Shopping Cart Method</b><br>Choose which type of PayPal Shopping Cart you want to use, Aggregate simply means the total amount is passed (with a list of the products shown in a single item field), whereas an Itemized Cart will list indivudally all the items the customer has selected in their PayPal invoice.</li><br>
      <li><b class="ppem380">Enable PayPal Shipping Address</b><br>Allow the customer to choose their own PayPal shipping address. 
      (See FAQ's for more info)</li><br>
      <li><b class="ppem380">Digest Key</b><br>Specify a unique digest key, this will then be used similiar to a password while testing via the IPN Test Panel <b>and</b> also the store's PayPal transaction signature digest key.</li><br>
      <li><b class="ppem380">Test Mode</b><br>This should be off for live environments.<br>You can simulate your own IPNs via the
      <a href="<?php 
      //echo tep_href_link(FILENAME_PAYPAL,'action=itp');
      ?>">
      IPN Test Panel</a>.</li><br>
      <li><b class="ppem380">Cart Test</b><br>This test verifies that the amount received via PayPal matches to what is expected 
      (See FAQ's for more info).</li><br>
      <li><b class="ppem380">Debug Email Notifications</b><br>Choose whether you want to receive debug emails.</li><br>
      <li><b class="ppem380">Debug Email Notification Address</b><br>Enter a seperate email address where you wish to receive <b>debug</b> emails.</li><br>
      <li><b class="ppem380">PayPal Domain</b><br>This must be set to <b>paypal.com</b> for live production environments, this setting is to facilate PayPal's sandbox and is not related to the IPN Test Panel or the above Test Mode option.</li><br>
      <li><b class="ppem380">Return URL behavior</b><br>This determines what method PayPal should use when returning the customer back to the store, leave this set to 1 (GET).</li><br>
    </ul>
    </td>
  </tr>
  <tr>
    <tr><td><hr class="solid"></td></tr>
  </tr>
  <tr>
    <td class="buttontd">
    </td>
  </tr>
</table>
