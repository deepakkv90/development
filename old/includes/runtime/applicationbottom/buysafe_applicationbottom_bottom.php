<?php
/*
  $Id: buysafe_applicationbottom_bottom.php,v 1.0.0.0 2007/08/16 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
global $buysafe_module;

if (defined('MODULE_ADDONS_BUYSAFE_STATUS') &&  MODULE_ADDONS_BUYSAFE_STATUS == 'True') {    
  if (is_object($buysafe_module)) {
    $rci = $buysafe_module->get_debug_info();  
    return $rci;
  }
}
?>