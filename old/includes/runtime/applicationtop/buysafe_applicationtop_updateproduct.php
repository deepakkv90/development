<?php
/*
  $Id: buysafe_applicationtop_updateproduct.php,v 1.0.0.0 2007/08/16 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
global $WantsBond;

if (defined('MODULE_ADDONS_BUYSAFE_STATUS') &&  MODULE_ADDONS_BUYSAFE_STATUS == 'True') { 
  $session_WantsBond = (isset($_SESSION['WantsBond'])) ? $_SESSION['WantsBond'] : '';
  $WantsBond = (isset($_POST['WantsBond'])) ? $_POST['WantsBond'] : $session_WantsBond;
  $_SESSION['WantsBond'] = $WantsBond;
}
?>