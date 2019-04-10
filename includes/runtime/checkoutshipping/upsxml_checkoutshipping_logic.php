<?php

/*
  $Id: upsxml_checkoutshipping_logic.php,v 1.0.0.0 2008/06/17 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
global $packing, $dimensions_support;
// changes for adding class packing
if (defined('MODULE_SHIPPING_UPSXML_RATES_STATUS') && MODULE_SHIPPING_UPSXML_RATES_STATUS == 'True') {
  if (defined('MODULE_SHIPPING_UPSXML_DIMENSIONS_SUPPORTED') && MODULE_SHIPPING_UPSXML_DIMENSIONS_SUPPORTED == 'Ready-to-ship only') {
    $dimensions_support = 1;
  } elseif (defined('MODULE_SHIPPING_UPSXML_DIMENSIONS_SUPPORTED') && MODULE_SHIPPING_UPSXML_DIMENSIONS_SUPPORTED == 'With product dimensions') {
    $dimensions_support = 2;
  } else {
    $dimensions_support = 0;
  }
  if ($dimensions_support > 0) {
    require_once(DIR_WS_CLASSES . 'packing.php');
    $packing = new packing;
  }
}
?>