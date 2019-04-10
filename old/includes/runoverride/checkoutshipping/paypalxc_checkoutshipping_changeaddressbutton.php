<?php
/*
  $Id: paypalxc_checkoutshipping_changeaddressbutton.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
if (!isset($_SESSION['skip_payment']) || $_SESSION['skip_payment'] != '1') {
  echo '<td class="main" width="50%" valign="top">' . TEXT_CHOOSE_SHIPPING_DESTINATION . '<br><br><a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '">Change</a></td>';
}
?>