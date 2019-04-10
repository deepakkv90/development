<?php
/*
  $Id: PP_english_lang.php,v 1.0. 2009/04/06 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
//begin PayPal_Shopping_Cart_IPN
define('BOX_CUSTOMERS_PAYPAL', 'PayPal IPN');
define('TEXT_DISPLAY_NUMBER_OF_PAYPALIPN_TRANSACTIONS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> transactions)'); // PAYPALIPN
define('BOX_HEADING_PAYPALIPN_ADMIN', 'PayPal IPN'); // PAYPALIPN
define('BOX_PAYPALIPN_ADMIN_TRANSACTIONS', 'Transactions'); // PAYPALIPN
define('BOX_PAYPALIPN_ADMIN_TESTS', 'Send Test IPN'); // PAYPALIPN
define("PAYPAL_HELP_CONFIGURATION_GUIDE","Configuration Guide");
define("PAYPAL_HELP_FREQUENTLY_ASKED_QUESTIONS","Frequently Asked Questions");
define("PAYPAL_CONTENTS","Contents");
define("PAYPAL_HELP_CONTENTS1","The following is a guide towards configuring your store's PayPal payment module settings:");

define("PAYPAL_HELP_CONTENTS2",'<ul style="margin: 10px 0px 0px 0px; padding: 0px 0px 0px 5px; list-style-type: none;">
      <li><b class="ppem380">Enable PayPal Module</b><br>Since you have just done the installation, by default it will say yes.</li><br><br class="h6" />
      <li><b class="ppem380">E-Mail Address</b><br>Enter your Primary PayPal email address here, if you have more than one email address registered with PayPal then you must login your PayPal account to determine which one is your <b>Primary</b> email address.</li><br><br class="h6" />
      <li><b class="ppem380">Business ID</b><br>If you have configured a secondary email address to be your Business ID (email address) in your PayPal profile account, then enter this here, otherwise enter the one used above.</li><br><br class="h6" />
      <li><b class="ppem380">Transaction Currency</b><br>Choose which currencies you want to accept PayPal payments with.</li><br><br class="h6" />
      <li><b class="ppem380">Payment Zone</b><br>If a zone is selected, enable this payment method for that zone only.</li><br><br class="h6" />
      <li><b class="ppem380">Set <b>Pending Notification</b> Order Status</b><br>Set the Pending Notification status of orders made with this payment module to this value, \'<b style="color:blue">Pending</b>\' is suggested or alternatively the optional order status created in step 4 above.</li><br><br class="h6" />
      <li><b class="ppem380">Set <b>Order</b> Status</b><br>Set the status of orders made with this payment module to this value <b style="color:red">Do not set it to \'default\' but specifically choose the status required</b>, \'<b style="color:blue">Processing</b>\' is suggested.</li><br><br class="h6" />
      <li><b class="ppem380">Set <b>On Hold Order</b> Status</b><br>This order status is used when the Cart Test has failed, eg. the expected payment amount did not match.</li><br><br class="h6" />
      <li><b class="ppem380">Set <b>Canceled Order</b> Status</b><br>Refunded, Reversed, Failed or Denied payments will be set to this order status.</li><br><br class="h6" />
      <li><b class="ppem380">Synchronize Invoice</b><br>Specify whether the osCommerce order number should be used as a <b>unique</b> PayPal invoice number, similiar to PayPal\'s Send Money feature, this will enable customers to resume an order via their osCommerce account history info page for that order for pending transactions which were not previously completed.</li><br><br class="h6" />
      <li><b class="ppem380">Sort order of display</b><br>Sort order of display. Lowest is displayed first.</li><br><br class="h6" />
      <li><b class="ppem380">Background Color</b><br>Select the background color of PayPal\'s payment pages, either black or white.</li><br><br class="h6" />
      <li><b class="ppem380">Processing logo</b><br>Specify the image file name to be used in the store\'s checkout process splash page, must be located in <code>catalog/images</code> directory.</li><br><br class="h6" />
      <li><b class="ppem380">Store logo</b><br>Specify the image file name for PayPal to display on their payment pages pages, must be located in <code>catalog/images</code> directory. If you do not have SSL then leave this field blank.</li><br><br class="h6" />
      <li><b class="ppem380">PayPal Page Style Name</b><br>Specify the name of the page style you have configured in your PayPal account profile.</li><br><br class="h6" />
      <li><b class="ppem380">Include a note with payment</b><br>When a customer arrives at the PayPal payment page you can choose whether or not to show them another note field which will have seperate contents from that of the one provided in your store\'s checkout process but will be included in PayPal\'s invoice and receipt.<br>To change the title appearing above this field on the PayPal payment page look in the corresponding catalog langauge file (<code>catalog/includes/langauges/english/modules/payment/paypal.php</code>).</li><br><br class="h6" />
      <li><b class="ppem380">Shopping Cart Method</b><br>Choose which type of PayPal Shopping Cart you want to use, Aggregate simply means the total amount is passed (with a list of the products shown in a single item field), whereas an Itemized Cart will list indivudally all the items the customer has selected in their PayPal invoice.</li><br><br class="h6" />
      <li><b class="ppem380">Enable PayPal Shipping Address</b><br>Allow the customer to choose their own PayPal shipping address. (See <a href="'.tep_href_link(FILENAME_PAYPAL,'action=help-faqs','NONSSL') .'">FAQ\'s</a> for more info)</li><br><br class="h6" />
      <li><b class="ppem380">Digest Key</b><br>Specify a unique digest key, this will then be used similiar to a password while testing via the IPN Test Panel <b>and</b> also the store\'s PayPal transaction signature digest key.</li><br><br class="h6" />
      <li><b class="ppem380">Test Mode</b><br>This should be off for live environments.<br>You can simulate your own IPNs via the <a href="'. tep_href_link(FILENAME_PAYPAL,'action=itp') .'">IPN Test Panel</a>.</li><br><br class="h6" />
      <li><b class="ppem380">Cart Test</b><br>This test verifies that the amount received via PayPal matches to what is expected (See <a href="'. tep_href_link(FILENAME_PAYPAL,'action=help-faqs','NONSSL').'">FAQ\'s</a> for more info).</li><br><br class="h6" />
      <li><b class="ppem380">Debug Email Notifications</b><br>Choose whether you want to receive debug emails.</li><br><br class="h6" />
      <li><b class="ppem380">Debug Email Notification Address</b><br>Enter a seperate email address where you wish to receive <b>debug</b> emails.</li><br><br class="h6" />
      <li><b class="ppem380">PayPal Domain</b><br>This must be set to <b>paypal.com</b> for live production environments, this setting is to facilate PayPal\'s sandbox and is not related to the IPN Test Panel or the above Test Mode option.</li><br><br class="h6" />
      <li><b class="ppem380">Return URL behavior</b><br>This determines what method PayPal should use when returning the customer back to the store, leave this set to 1 (GET).</li><br><br class="h6" />
    </ul>');



define("PAYPAL_FAQ_CONTENTS1","The following are some frequently asked questions about this payment module:");

define("PAYPAL_FAQ_CONTENTS2",'<ul style="margin: 10px 0px 0px 0px; padding: 0px 0px 0px 5px; list-style-type: none;">
      <li><b class="ppem380">My shipping costs are different once the customer arrives at the PayPal site.</b></legend><div>In your PayPal account profile in the shipping section, check the box that allows overriding the shipping costs.</li><br><br class="h6" />
      <li><b class="ppem380">Do I need to enable IPN in my PayPal account profile.</b><br>No.</li><br><br class="h6" />
      <li><b class="ppem380">What url should I specify for the IPN feature to be used.</b><br>You don\'t need to, it will automatically be specified.</li><br><br class="h6" />
      <li><b class="ppem380">Why doesn\'t the Auto-Return feature work.</b><br>You must enable it first in your PayPal account profile.</li><br><br class="h6" />
      <li><b class="ppem380">What url should I specify for the Auto-Return feature.</b><br>It doesn\'t matter, this contribution will automatically specify the correct script url location, which is now <code>catalog/checkout_success.php</code></li><br><br class="h6" />
      <li><b class="ppem380">Does this contribution support PayPal Credit Card payments.</b><br>Yes.</li><br><br class="h6" />
      <li><b class="ppem380">Why am I getting so many emails per transaction.</b><br>You get <b>one</b> from PayPal and <b>one</b> osCommerce order confirmation email, and another which is the <b>debug email</b>, either disable <b>debug</b> email notification or specify a sperate email address for <b>debug</b> emails.</li><br><br class="h6" />
      <li><b class="ppem380">Why am I not receiving any IPNs.</b><br>You must ensure that your <b>Primary Email Address</b> and <b>Business ID</b> are configured correctly.<br>Login to your PayPal Account and see what and how many email addresses you are using and which one is the Primary address.<br>Also check your debug emails as it will also specify the Primary Email Address of the intended receiver.</li><br><br class="h6" />
      <li><b class="ppem380">I\'m having problems with Multi-Currencies.</b><br>This problem should now be resolved, previously the <b>Cart Test</b> was comparing the currency and amounts of the actual order to what PayPal received as opposed to actually comparing the currency and amounts prior to transfering the customer to PayPal.<br><br><b>However</b>, if you\'re using the Itemized Shopping Cart then discrepancies may arise due to PayPal\'s two decimal place calculations, in such an event the order will be placed on hold. Although this type of discrepancy may not affect all orders it does however exist and now adds a future requirement to enable you to finalize the transaction via your store\'s admin.</li><br><br class="h6" />
      <li><b class="ppem380">I\'m having problems verifying the Cart Totals.</b><br>See above.</li><br><br class="h6" />
      <li><b class="ppem380">Why is there no PayPal shipping address details in the IPN info?</b><br>Set Enable PayPal Shipping Address to \'No\' if you require a PayPal verified shipping address or set to \'Yes\' if you do not.</li><br><br class="h6" />
      <li><b class="ppem380">How can I customize the Checkout redirect page.</b><br>The splash template page shown while transfering the customer to PayPal is:<br><code>catalog/includes/modules/payment/paypal/catalog/checkout_splash.inc.php</code></li><br><br class="h6" />
      <li><b class="ppem380">Where can I find more PayPal logo buttons.</b><br>Login to your PayPal account and click Merchant Tools or Auction Tools.</li><br><br class="h6" />
      <li><b class="ppem380">Where can I find PayPal documentation.</b><br><ul style="margin-left: 15px; margin-top: 5px; padding-left: 5px;"><li><a href="https://www.paypal.com/en_US/pdf/integration_guide.pdf">PayPal Integration Manual</a></li></ul></li><br><br class="h6" />

      <li><b class="ppem380">How do I use PayPal\'s Sandbox.</b>
        <ul style="margin-left: 15px; margin-top: 5px; padding-left: 5px;">
          <li>Register with <a href="https://developer.paypal.com/" target="PPDC">PayPal Developer Central</a></li><br class="h6" />
          <li>Then create <b>two virtual accounts</b> types:<ul style="margin-top: 5px; margin-left: 10px; padding-left: 10px;"><li>Personal Account</li><li>Premier or Business Account</li></ul></li><br class="h6" />
          <li>Confirm both accounts (each is just a single click option).</li><br class="h6" />
          <li>Transfer some virtual money into your personal account (Click the add funds link).</li><br class="h6" />
          <li>Emails to both accounts will appear in the section where you initially login into the PayPal Developer Central, no real emails are sent so the email addresses for your virtual accounts should be fictitious.</li><br class="h6" />
          <li>Now in your <a href="'. tep_href_link(FILENAME_MODULES,'set=payment&module=paypal&action=edit').'" target="_blank">osCommerce::Admin::Modules::Payment::PayPal</a> configuration section<ul style="margin-top: 5px; margin-left: 10px; padding-left: 10px;"><li>Enter the virtual business account email address into both the Primary Email Address and Business ID fields</li><li>Set the domain to www.sandbox.paypal.com</li></ul></li><br class="h6" />
          <li>Now checkout as a customer via the store, this account should have a real email address (so that you can receive the osC customer order confirmation email), and when you arrive at the PayPal Sandbox site (look for their logo) then login using the virtual personal account details and finalize the payment process.</li>
        </ul>
       <br><p class="ppsmallerrorbold">Note that you must first login via the PayPal Developer Central prior to testing the Sandbox/Checkout process.</p>
      </li><br><br class="h6" />

      <li><b class="ppem380">What\'s the difference between the IPN Test Panel and using PayPal\'s Sandbox.</b><br>The <a href="'. tep_href_link(FILENAME_PAYPAL,'action=itp').'" target="itp">IPN Test Panel</a> is an independent osCommerce Contribution feature, for testing and development purposes, which allows you to simmulate your own IPNs without having to interact with PayPal.<br><br><p class="ppsmallerrorbold">All Test IPNs via the IPN Test Panel will be invalid according to PayPal since they\'re not authentic transactions.</p></li><br><br class="h6" />
      <li><b class="ppem380">Is the IPN Test Panel related to PayPal\'s Sandbox.</b><br>No, see above.</li><br><br class="h6" />
      <li><b class="ppem380">What\'s the purpose of the digest key.</b><br>Currently the digest key serves two purposes, it authenticates and allows you to use the <a href="'. tep_href_link(FILENAME_PAYPAL,'action=itp').'" target="itp">IPN Test Panel</a>, secondly, untill such time that PayPal provides a secure method of associating an IPN specifically to a particular order, it serves as a unique transaction signature identifier.</li><br><br class="h6" />
      <li><b class="ppem380">Should I change the digest key default value.</b><br>Yes, but remember what it is if uninstalling and reinstalling this contrib payment module in the admin since there might be existing pending orders which will require it in order to associate with to their corresponding IPN(s).</li><br><br class="h6" />
      <li><b class="ppem380">How are IPNs deleted.</b><br>Currently, an IPN appearing the admin section is deleted automatically when deleting it\'s corresponding order.</li><br><br class="h6" />
      <li><b class="ppem380">How can a customer resume their order.</b><br>Currently, if the customer abandons the checkout process at the PayPal site, e.g for some reason they did not actually pay at that time, then they can log into their osCommerce account and click the confirm order button when viewing the details of that particular order in their account_history_info.<br><br>This feature is only available when <b>synchronizing invoices</b> with PayPal.<br><br>This means that if you ever reset the osCommerce <code>orders</code> database table then you must also update it\'s auto-increment value to be greater than the last known <code>order_id</code>.</li><br><br class="h6" />
      <li><b class="ppem380">I\'m getting errors after installing the contribution.</b><br>First, go through the installation edits and remove any blank spaces at the beginning of the line(s), some text editors will insert hidden characters which is a side effect of copying and pasting from HTML documentation.<br>Make sure that the alterations have been performed as well as running the paypal_ipn.sql script.</li><br><br class="h6" />

      <li><b class="ppem380">What type of PayPal account do I need.</b><br>You will need either a Premier or Merchant Business Account. <a href="https://www.paypal.com/refer/pal=PS2X9Q773CKG4" target="_blank">Click&nbsp;here&nbsp;to&nbsp;register&nbsp;for&nbsp;an&nbsp;account</a> if you do not already have one.</li><br><br class="h6" />
    </ul>');



define('PAYPAL_IPN_TEST_MODE1','Test Mode must be enabled!');
define('PAYPAL_IPN_TEST_MODE_ADVANCED','Advanced');
define('PAYPAL_IPN_TEST_MODE_HELP_WITH_THIS_PAGE','Help with this page');
define('PAYPAL_IPN_TEST_MODE_PRIMARY_PAYPAL_EMAIL_ADDRESS','Primary PayPal Email Address');
define('PAYPAL_IPN_TEST_MODE_BUSINESS_ID','Business ID');
define('PAYPAL_IPN_TEST_MODE_DEBUG_EMAIL_ADDRESS','Debug Email Address');

define('PAYPAL_IPN_TEST_MODE_FIRST_NAME','First Name');
define('PAYPAL_IPN_TEST_MODE_LAST_NAME','Last Name');
define('PAYPAL_IPN_TEST_MODE_BUSINESS_NAME','Business Name');
define('PAYPAL_IPN_TEST_MODE_EMAIL_ADDRESS','Email Address');
define('PAYPAL_IPN_TEST_MODE_PAYER_ID','Payer ID');
define('PAYPAL_IPN_TEST_MODE_PAYER_STATUS','Payer Status');
define('PAYPAL_IPN_TEST_MODE_VERIFIED','verified');
define('PAYPAL_IPN_TEST_MODE_UNVERIFIED','unverified');
define('PAYPAL_IPN_TEST_MODE_INVOICE','Invoice');

define('PAYPAL_IPN_TEST_MODE_ADDRESS_NAME','Address Name');
define('PAYPAL_IPN_TEST_MODE_ADDRESS_STREET','Address Street');
define('PAYPAL_IPN_TEST_MODE_ADDRESS_CITY','Address City');
define('PAYPAL_IPN_TEST_MODE_ADDRESS_STATE','Address State');
define('PAYPAL_IPN_TEST_MODE_ADDRESS_ZIP','Address Zip');
define('PAYPAL_IPN_TEST_MODE_ADDRESS_COUNTRY','Address Country');
define('PAYPAL_IPN_TEST_MODE_ADDRESS_STATUS','Address Status');
define('PAYPAL_IPN_TEST_MODE_CONFIRMED','confirmed');
define('PAYPAL_IPN_TEST_MODE_UNCONFIRMED','unconfirmed');

define('PAYPAL_IPN_TEST_MODE_PAYMENT_TYPE','Payment Type');
define('PAYPAL_IPN_TEST_MODE_INSTANT','instant');
define('PAYPAL_IPN_TEST_MODE_ECHECK','echeck');
define('PAYPAL_IPN_TEST_MODE_TRANSACTION_TYPE','Transaction Type');
define('PAYPAL_IPN_TEST_MODE_SELECT','--select--');
define('PAYPAL_IPN_TEST_MODE_CART','cart');
define('PAYPAL_IPN_TEST_MODE_WEB_ACCEPT','web_accept');
define('PAYPAL_IPN_TEST_MODE_SEND_MONEY','send_money');
define('PAYPAL_IPN_TEST_MODE_CUSTOM','Custom');
define('PAYPAL_IPN_TEST_MODE_TRANSACTION_ID','Transaction ID');
define('PAYPAL_IPN_TEST_MODE_PARENT_TRANSACTION_ID','Parent Transaction ID');
define('PAYPAL_IPN_TEST_MODE_NO_CART_ITEMS','No. Cart Items');
define('PAYPAL_IPN_TEST_MODE_NOTIFY_VERSION','Notify Version');
define('PAYPAL_IPN_TEST_MODE_NOTIFY_VERSION_VALUE','1.6');
define('PAYPAL_IPN_TEST_MODE_MEMO','Memo');

define('PAYPAL_IPN_TEST_MODE_MC_CURRENCY','MC Currency');
define('PAYPAL_IPN_TEST_MODE_USD','USD');
define('PAYPAL_IPN_TEST_MODE_GBP','GBP');
define('PAYPAL_IPN_TEST_MODE_EUR','EUR');
define('PAYPAL_IPN_TEST_MODE_CAD','CAD');
define('PAYPAL_IPN_TEST_MODE_JPY','JPY');
define('PAYPAL_IPN_TEST_MODE_MC_GROSS','MC Gross');
define('PAYPAL_IPN_TEST_MODE_MC_FEE','MC Fee');

define('PAYPAL_IPN_TEST_MODE_SETTLE_AMOUNT','Settle Amount');
define('PAYPAL_IPN_TEST_MODE_SETTLE_CURRENCY','Settle Currency');
define('PAYPAL_IPN_TEST_MODE_EXCHANGE_RATE','Exchange Rate');

define('PAYPAL_IPN_TEST_MODE_PAYMENT_STATUS','Payment Status');
define('PAYPAL_IPN_TEST_MODE_PAYMENT_STATUS_COMPLETED','Completed');
define('PAYPAL_IPN_TEST_MODE_PAYMENT_STATUS_PENDING','Pending');
define('PAYPAL_IPN_TEST_MODE_PAYMENT_STATUS_FAILED','Failed');
define('PAYPAL_IPN_TEST_MODE_PAYMENT_STATUS_DENIED','Denied');
define('PAYPAL_IPN_TEST_MODE_PAYMENT_STATUS_REFUNDED','Refunded');
define('PAYPAL_IPN_TEST_MODE_PAYMENT_STATUS_REVERSED','Reversed');
define('PAYPAL_IPN_TEST_MODE_PAYMENT_STATUS_CANCELED_REVERSAL','Canceled_Reversal');

define('PAYPAL_IPN_TEST_MODE_PENDING_REASON','Pending Reason');
define('PAYPAL_IPN_TEST_MODE_MULTI_CURRENCY','multi_currency');
define('PAYPAL_IPN_TEST_MODE_INTL','intl');
define('PAYPAL_IPN_TEST_MODE_VERIFY','verify');
define('PAYPAL_IPN_TEST_MODE_ADDRESS','address');
define('PAYPAL_IPN_TEST_MODE_UPGRADE','upgrade');
define('PAYPAL_IPN_TEST_MODE_UNILATERAL','unilateral');
define('PAYPAL_IPN_TEST_MODE_OTHER','other');

define('PAYPAL_IPN_TEST_MODE_REASON_CODE','Reason Code');
define('PAYPAL_IPN_TEST_MODE_CHARGEBACK','chargeback');
define('PAYPAL_IPN_TEST_MODE_GUARANTEE','guarantee');
define('PAYPAL_IPN_TEST_MODE_BUYER_COMPLAINT','buyer_complaint');
define('PAYPAL_IPN_TEST_MODE_REFUND','refund');

define('PAYPAL_IPN_TEST_MODE_TAX','Tax');
define('PAYPAL_IPN_TEST_MODE_FOR_AUCTION','For Auction');
define('PAYPAL_IPN_TEST_MODE_NO','No');
define('PAYPAL_IPN_TEST_MODE_YES','Yes');
define('PAYPAL_IPN_TEST_MODE_AUCTION_BUYER_ID','Auction Buyer ID');
define('PAYPAL_IPN_TEST_MODE_AUCTION_CLOSING_DATE','Auction Closing Date');
define('PAYPAL_IPN_TEST_MODE_AUCTION_MULTI_ITEM','Auction Multi-Item');

define('PAYPAL_IPN_TEST_MODE_ITEM_NAME','Item Name');
define('PAYPAL_IPN_TEST_MODE_ITEM_NUMBER','Item Number');
define('PAYPAL_IPN_TEST_MODE_QUANTITY','Quantity');


define('PAYPAL_IPN_TEST_MODE_HELP1','The following is a quick guide towards simmulating your own IPNs:');


define('PAYPAL_IPN_TEST_MODE_HELP2',' <br><ol>
      <li>Begin to check out as a customer via the store, stop when you get to the PayPal site</li><br><br class="h6">
      <li>Go into the store admin orders section and find the last order (just created)</li><br><br class="h6">
      <li>Select a <b>Transaction Type</b>, usually cart or web_accept, but nothing for refunds, reversals, or canceled_reversals payments</li><br><br class="h6">
      <li>Copy and paste the <b>Transaction Signature</b> into the <b>Custom</b> field and into the <b>Transaction ID</b> field</li><br><br class="h6">
      <li>If the <b>Cart Test</b> is on, then make sure that the above <b>MC Gross</b> amount is the same as the order total and that the <b>MC&nbsp;Currency</b> field is set to the same currency as the order.</li><br><br class="h6">
      <li>Submit the Test IPN</li><br><br class="h6">
      <li>Now check the admin order status</li><br><br class="h6">
    </ol>');

define('PAYPAL_IPN_TEST_MODE_HELP3','When testing Pending payments etc, remember to use the same Transction ID');

?>