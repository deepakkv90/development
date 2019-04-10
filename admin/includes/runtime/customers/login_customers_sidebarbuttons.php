<?php
/*
  $Id: login_customers_sidebarbuttons.php, v 1.0 2009/11/25 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
global $cInfo;
$rci = '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . 'login.php?email=' . $cInfo->customers_email_address . '&act=in&a='.$_SESSION['login_id'].'&ag='.$_SESSION['login_groups_id'] . '" target="_blank">' . tep_image_button('button_login_as_customer.png', IMAGE_LOGIN_AS_CUSTOMER) . '</a><br />'; 
?>