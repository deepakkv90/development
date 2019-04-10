<?php
/*
  $Id: cdspages_pages_sidebarcatbuttons.php, v 1.0.0.0 2008/02/12 maestro

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  Author: Michael Hogan, modified: maestro
*/

  global $cInfo;
  $cPath = (isset($_GET['cPath'] ) && tep_not_null($_GET['cPath'])) ? $_GET['cPath'] : 0;

  $rci = '<a target="_blank" href="' . HTTP_CATALOG_SERVER . DIR_WS_CATALOG . 'pages.php?CDpath=' . $cInfo->ID . '">' . tep_image_button('button_view_in_catalog.gif', 'View in Catalog') . '</a>';

?>