<?php
/*
  $Id: affiliate_banners_build.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

//initilize variable is empty

   if (isset($_GET['individual_banner_id'])) {
    $individual_banner_id = $_GET['individual_banner_id'] ;
    }else if (isset($_POST['individual_banner_id'])){
    $individual_banner_id = $_POST['individual_banner_id'] ;
    } else {
    $individual_banner_id = 0 ;
   }

    $affiliate_pbanners_values = tep_db_query("select p.products_image, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$individual_banner_id . "' and pd.products_id = '" . (int)$individual_banner_id . "' and p.products_status = '1' and pd.language_id = '" . (int)$languages_id . "'");
    $affiliate_pbanners = tep_db_fetch_array($affiliate_pbanners_values) ;

  if (!isset($_SESSION['affiliate_id'])) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_BANNERS_BUILD);

  $affiliate_banners_values = tep_db_query("select * from " . TABLE_AFFILIATE_BANNERS . " order by affiliate_banners_title");
  $breadcrumb->add(NAVBAR_TITLE_AFFILIATE_CENTRAL, tep_href_link(FILENAME_AFFILIATE_CENTRAL));
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD));

  $content = CONTENT_AFFILIATE_BANNERS_BUILD;
  $javascript = 'affiliate_popup.js.php';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
