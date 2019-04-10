<?php
/*
 // wishlist.php,v 2.0  2003/11/22 Jesse Labrocca

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ( !isset($_SESSION['customer_id']) ) {
    $_SESSION['SESSION_WISHLIST'] = (int)$_POST['products_id'];
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  /* atrifact code left from the old processing, this code is now handled in the application top
  // being removed to reduce any security exposure
  
  //ADDED FOR WHEN USERS ARE NOT LOGGED IN TO KEEP THE PRODUCT_ID
  if(!isset($products_price)) {
  $products_price = 0;
  }
  if(!isset($products_model)) {
    $products_model = '';
  }
  if(!isset($products_name)) {
    $products_name = '';
  }
  if(isset($_SESSION['SESSION_WISHLIST'])) {

   // Queries below replace old product instead of adding to quantity.
   tep_db_query("delete from " . TABLE_WISHLIST . " where products_id = '" . $_SESSION['SESSION_WISHLIST'] . "' and customers_id = '" . (int)$_SESSION['customer_id'] . "'");
   tep_db_query("insert into " . TABLE_WISHLIST . " (customers_id, products_id, products_model, products_name, products_price) values ('" . (int)$_SESSION['customer_id'] . "', '" . $_SESSION['SESSION_WISHLIST'] . "', '" . tep_db_prepare_input($products_model) . "', '" . tep_db_prepare_input($products_name) . "', '" . tep_db_prepare_input($products_price) . "' )");
   tep_db_query("delete from " . TABLE_WISHLIST_ATTRIBUTES . " where products_id = '" . $_SESSION['SESSION_WISHLIST'] . "' and customers_id = '" . (int)$_SESSION['customer_id'] . "'");

   // Read array of options and values for attributes in id[]
    if (isset ($id)) {
    foreach($id as $att_option=>$att_value) {
    // Add to customers_wishlist_attributes table
    tep_db_query("insert into " . TABLE_WISHLIST_ATTRIBUTES . " (customers_id, products_id, products_options_id , products_options_value_id) values ('" . (int)$_SESSION['customer_id'] . "', '" . $_SESSION['SESSION_WISHLIST'] . "', '" . (int)$att_option . "', '" . (int)$att_value . "' )");
    }
   }
  unset($_SESSION['SESSION_WISHLIST']);
  }

  //END OF ADDITION
  */
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_WISHLIST);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_WISHLIST, '', 'NONSSL'));

  $content = CONTENT_WISHLIST;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
