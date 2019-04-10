<?php
/*
  $Id: onepagecheckout_checkoutconfirmation_logic.php,v 1.0.0 2008/05/22 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('MODULE_ADDONS_ONEPAGECHECKOUT_STATUS') && MODULE_ADDONS_ONEPAGECHECKOUT_STATUS == 'True') {	
 	tep_redirect(tep_href_link(FILENAME_ORDER_CHECKOUT, tep_get_all_get_params(array()) . (isset($_GET['error']) ? 'error=' . $_GET['error'] : '')));
}
?>