<?php
/*
  $Id: product_reviews_info.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
 // Eversun mod for sppc and qty price breaks
// global variable (session) $sppc_customer_group_id -> local variable customer_group_id

  if(!isset($_SESSION['sppc_customer_group_id'])) {
  $customer_group_id = 'G';
  } else {
   $customer_group_id = $_SESSION['sppc_customer_group_id'];
  }
// Eversun mod end for sppc and qty price breaks

  if (isset($_GET['reviews_id']) && tep_not_null($_GET['reviews_id']) && isset($_GET['products_id']) && tep_not_null($_GET['products_id'])) {
    $review_check_query = tep_db_query("select count(*) as total from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . (int)$_GET['reviews_id'] . "' and r.products_id = '" . (int)$_GET['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "'");
    $review_check = tep_db_fetch_array($review_check_query);

    if ($review_check['total'] < 1) {
      tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id'))));
    }
  } else {
    tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id'))));
  }

  tep_db_query("update " . TABLE_REVIEWS . " set reviews_read = reviews_read+1 where reviews_id = '" . (int)$_GET['reviews_id'] . "'");

  $review_query = tep_db_query("SELECT rd.reviews_text, r.reviews_rating, r.reviews_id, r.customers_name, r.date_added, r.reviews_read, p.products_id, p.products_model, p.products_image, if (isnull(pg.customers_group_price), p.products_price, pg.customers_group_price) as final_price,  p.products_tax_class_id,  pd.products_name
                                                  from " . TABLE_REVIEWS . " r,
                                                         " . TABLE_REVIEWS_DESCRIPTION . "  rd,
                                                         " . TABLE_PRODUCTS . " p
                                                LEFT JOIN " . TABLE_PRODUCTS_GROUPS . " pg
                                                  on p.products_id = pg.products_id
                                                  and pg.customers_group_id = '" . $customer_group_id . "',
                                                        " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                               WHERE r.reviews_id = '" . (int)$_GET['reviews_id'] . "'
                                                 and r.reviews_id = rd.reviews_id
                                                 and rd.languages_id = '" . (int)$languages_id . "'
                                                 and r.products_id = p.products_id
                                                 and p.products_status = '1'
                                                 and p.products_id = pd.products_id
                                                 and pd.language_id = '". (int)$languages_id . "'");

  $reviews_info = tep_db_fetch_array($review_query);

     if ($customer_group_id !='0') {
  $customer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . (isset($review['products_id']) ? $review['products_id'] : 0) . "' and customers_group_id =  '" . $customer_group_id . "'");
    if ($customer_group_price = tep_db_fetch_array($customer_group_price_query)) {
      $review['products_price'] = $customer_group_price['customers_group_price'];
    }
     }
// Eversun end mod for sppc and qty price breaks
    $pf->loadProduct($reviews_info['products_id'],$languages_id);
    $products_price = $pf->getPriceStringShort();

  if (tep_not_null($reviews_info['products_model'])) {
    $products_name = $reviews_info['products_name'] . '<br><span class="smallText">[' . $reviews_info['products_model'] . ']</span>';
  } else {
    $products_name = $reviews_info['products_name'];
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_INFO);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));

  if (tep_customer_access_product($_SESSION['customer_id'],         $reviews_info['products_id'])) {
    $content = CONTENT_PRODUCT_REVIEWS_INFO;
  } else {
    $content = CONTENT_INDEX_RESTRICTED;
  } 
  $javascript = 'popup_window.js';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
