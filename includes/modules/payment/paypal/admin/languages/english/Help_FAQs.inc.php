<?php
/*
  $Id: Help_FAQs.inc.php,v 2.8 2004/09/11 devosc Exp $

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
    <td style="padding-bottom: 5px; text-align: right;"><?php echo tep_image($page->baseURL . 'images/contents.gif','Contents','','','align=absmiddle'); ?>&nbsp;<a href="<?php echo tep_href_link(FILENAME_PAYPAL,'action=help','NONSSL'); ?>">Contents</a>&nbsp;</td>
  </tr>
  <tr>
    <td class="pptext">The following are some frequently asked questions about this payment module:</td>
  </tr>
  <tr>
    <td class="pptext" valign="top">
    <ul style="margin: 10px 0px 0px 0px; padding: 0px 0px 0px 5px; list-style-type: none;">
      <li><b class="ppem380">My shipping costs are different once the customer arrives at the PayPal site.</b></legend><div>In your PayPal account profile in the shipping section, check the box that allows overriding the shipping costs.</li><br><br class="h6" />
      <li><b class="ppem380">Do I need to enable IPN in my PayPal account profile.</b><br>No.</li><br><br class="h6" />
      <li><b class="ppem380">What url should I specify for the IPN feature to be used.</b><br>You don't need to, it will automatically be specified.</li><br><br class="h6" />
      <li><b class="ppem380">Why doesn't the Auto-Return feature work.</b><br>You must enable it first in your PayPal account profile.</li><br><br class="h6" />
      <li><b class="ppem380">What url should I specify for the Auto-Return feature.</b><br>It doesn't matter, this contribution will automatically specify the correct script url location, which is now <code>catalog/checkout_success.php</code></li><br><br class="h6" />
      <li><b class="ppem380">Does this contribution support PayPal Credit Card payments.</b><br>Yes.</li><br><br class="h6" />
      <li><b class="ppem380">Why am I getting so many emails per transaction.</b><br>You get <b>one</b> from PayPal and <b>one</b> osCommerce order confirmation email, and another which is the <b>debug email</b>, either disable <b>debug</b> email notification or specify a sperate email address for <b>debug</b> emails.</li><br><br class="h6" />
      <li><b class="ppem380">Why am I not receiving any IPNs.</b><br>You must ensure that your <b>Primary Email Address</b> and <b>Business ID</b> are configured correctly.<br>Login to your PayPal Account and see what and how many email addresses you are using and which one is the Primary address.<br>Also check your debug emails as it will also specify the Primary Email Address of the intended receiver.</li><br><br class="h6" />
      <li><b class="ppem380">I'm having problems with Multi-Currencies.</b><br>This problem should now be resolved, previously the <b>Cart Test</b> was comparing the currency and amounts of the actual order to what PayPal received as opposed to actually comparing the currency and amounts prior to transfering the customer to PayPal.<br><br><b>However</b>, if you're using the Itemized Shopping Cart then discrepancies may arise due to PayPal's two decimal place calculations, in such an event the order will be placed on hold. Although this type of discrepancy may not affect all orders it does however exist and now adds a future requirement to enable you to finalize the transaction via your store's admin.</li><br><br class="h6" />
      <li><b class="ppem380">I'm having problems verifying the Cart Totals.</b><br>See above.</li><br><br class="h6" />
      <li><b class="ppem380">Why is there no PayPal shipping address details in the IPN info?</b><br>Set Enable PayPal Shipping Address to 'No' if you require a PayPal verified shipping address or set to 'Yes' if you do not.</li><br><br class="h6" />
      <li><b class="ppem380">How can I customize the Checkout redirect page.</b><br>The splash template page shown while transfering the customer to PayPal is:<br><code>catalog/includes/modules/payment/paypal/catalog/checkout_splash.inc.php</code></li><br><br class="h6" />
      <li><b class="ppem380">Where can I find more PayPal logo buttons.</b><br>Login to your PayPal account and click Merchant Tools or Auction Tools.</li><br><br class="h6" />
      <li><b class="ppem380">Where can I find PayPal documentation.</b><br><ul style="margin-left: 15px; margin-top: 5px; padding-left: 5px;"><li><a href="https://www.paypal.com/en_US/pdf/integration_guide.pdf">PayPal Integration Manual</a></li></ul></li><br><br class="h6" />

      <li><b class="ppem380">How do I use PayPal's Sandbox.</b>
        <ul style="margin-left: 15px; margin-top: 5px; padding-left: 5px;">
          <li>Register with <a href="https://developer.paypal.com/" target="PPDC">PayPal Developer Central</a></li><br class="h6" />
          <li>Then create <b>two virtual accounts</b> types:<ul style="margin-top: 5px; margin-left: 10px; padding-left: 10px;"><li>Personal Account</li><li>Premier or Business Account</li></ul></li><br class="h6" />
          <li>Confirm both accounts (each is just a single click option).</li><br class="h6" />
          <li>Transfer some virtual money into your personal account (Click the add funds link).</li><br class="h6" />
          <li>Emails to both accounts will appear in the section where you initially login into the PayPal Developer Central, no real emails are sent so the email addresses for your virtual accounts should be fictitious.</li><br class="h6" />
          <li>Now in your <a href="<?php echo tep_href_link(FILENAME_MODULES,'set=payment&module=paypal&action=edit'); ?>" target="_blank">osCommerce::Admin::Modules::Payment::PayPal</a> configuration section<ul style="margin-top: 5px; margin-left: 10px; padding-left: 10px;"><li>Enter the virtual business account email address into both the Primary Email Address and Business ID fields</li><li>Set the domain to www.sandbox.paypal.com</li></ul></li><br class="h6" />
          <li>Now checkout as a customer via the store, this account should have a real email address (so that you can receive the osC customer order confirmation email), and when you arrive at the PayPal Sandbox site (look for their logo) then login using the virtual personal account details and finalize the payment process.</li>
        </ul>
       <br><p class="ppsmallerrorbold">Note that you must first login via the PayPal Developer Central prior to testing the Sandbox/Checkout process.</p>
      </li><br><br class="h6" />

      <li><b class="ppem380">What's the difference between the IPN Test Panel and using PayPal's Sandbox.</b><br>The <a href="<?php echo tep_href_link(FILENAME_PAYPAL,'action=itp'); ?>" target="itp">IPN Test Panel</a> is an independent osCommerce Contribution feature, for testing and development purposes, which allows you to simmulate your own IPNs without having to interact with PayPal.<br><br><p class="ppsmallerrorbold">All Test IPNs via the IPN Test Panel will be invalid according to PayPal since they're not authentic transactions.</p></li><br><br class="h6" />
      <li><b class="ppem380">Is the IPN Test Panel related to PayPal's Sandbox.</b><br>No, see above.</li><br><br class="h6" />
      <li><b class="ppem380">What's the purpose of the digest key.</b><br>Currently the digest key serves two purposes, it authenticates and allows you to use the <a href="<?php echo tep_href_link(FILENAME_PAYPAL,'action=itp'); ?>" target="itp">IPN Test Panel</a>, secondly, untill such time that PayPal provides a secure method of associating an IPN specifically to a particular order, it serves as a unique transaction signature identifier.</li><br><br class="h6" />
      <li><b class="ppem380">Should I change the digest key default value.</b><br>Yes, but remember what it is if uninstalling and reinstalling this contrib payment module in the admin since there might be existing pending orders which will require it in order to associate with to their corresponding IPN(s).</li><br><br class="h6" />
      <li><b class="ppem380">How are IPNs deleted.</b><br>Currently, an IPN appearing the admin section is deleted automatically when deleting it's corresponding order.</li><br><br class="h6" />
      <li><b class="ppem380">How can a customer resume their order.</b><br>Currently, if the customer abandons the checkout process at the PayPal site, e.g for some reason they did not actually pay at that time, then they can log into their osCommerce account and click the confirm order button when viewing the details of that particular order in their account_history_info.<br><br>This feature is only available when <b>synchronizing invoices</b> with PayPal.<br><br>This means that if you ever reset the osCommerce <code>orders</code> database table then you must also update it's auto-increment value to be greater than the last known <code>order_id</code>.</li><br><br class="h6" />
      <li><b class="ppem380">I'm getting errors after installing the contribution.</b><br>First, go through the installation edits and remove any blank spaces at the beginning of the line(s), some text editors will insert hidden characters which is a side effect of copying and pasting from HTML documentation.<br>Make sure that the alterations have been performed as well as running the paypal_ipn.sql script.</li><br><br class="h6" />

      <li><b class="ppem380">What type of PayPal account do I need.</b><br>You will need either a Premier or Merchant Business Account. <a href="https://www.paypal.com/refer/pal=PS2X9Q773CKG4" target="_blank">Click&nbsp;here&nbsp;to&nbsp;register&nbsp;for&nbsp;an&nbsp;account</a> if you do not already have one.</li><br><br class="h6" />
    </ul>
    </td>
  </tr>
  <tr>
    <tr><td><hr class="solid"></td></tr>
  </tr>
  <tr>
    <td class="buttontd"><form name="contents" action="<?php echo tep_href_link(FILENAME_PAYPAL,'','NONSSL'); ?>"><input type="hidden" name="action" value="help" /><input type="submit" value="Contents" class="ppbuttonsmall" /></form></td>
  </tr>
</table>
