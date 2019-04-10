<?php
/*
  $Id: cresecure_shoppingcart_bottom.php,v 1.0.0.0 2007/08/16 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
// check order history
global $languages_id, $language;

$rci = ''; 
$cartID = (isset($_SESSION['cart_cresecure_ID'])) ? substr($_SESSION['cart_cresecure_ID'], 0, strpos($_SESSION['cart_cresecure_ID'], '-')) : '';
if (defined('MODULE_PAYMENT_CRESECURE_STATUS') && MODULE_PAYMENT_CRESECURE_STATUS == 'True' && isset($_SESSION['customer_id'])) {
  if (defined('MODULE_PAYMENT_CRESECURE_SHOW_INCOMPLETE') && MODULE_PAYMENT_CRESECURE_SHOW_INCOMPLETE == 'True') {
    $history_query = tep_db_query("SELECT o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, o.payment_info, ot.text as order_total, s.orders_status_name 
                                     from " . TABLE_ORDERS . " o, 
                                          " . TABLE_ORDERS_TOTAL . " ot, 
                                          " . TABLE_ORDERS_STATUS . " s 
                                   WHERE o.customers_id = '" . (int)$_SESSION['customer_id'] . "' 
                                     and o.orders_id = ot.orders_id 
                                     and ot.class = 'ot_total' 
                                     and o.orders_status = s.orders_status_id 
                                     and s.language_id = '" . (int)$languages_id . "' 
                                     and s.orders_status_name = 'Preparing [CRE Secure]' 
                                     and o.payment_info != '" . $cartID . "'  
                                   ORDER BY orders_id DESC");
    if (tep_db_num_rows($history_query) > 0) {
// do not show orders if same cartID
      include_once(DIR_WS_LANGUAGES . '/' . $language . '/account_history.php');
      $rci .= '<table border="0" width="100%" cellspacing="1" cellpadding="2">' . "\n";
      $rci .= '  <tr>' . "\n";
      $rci .= '    <td><table STYLE="border-color: #ff0000; border-width: 1px; border-style: solid;" border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n";
      $rci .= '      <tr><td><div align="center"><b>You have placed orders without paying for them. Your orders will not be shipped until payment is made. You can complete these orders and pay for them by selecting them below:</b></center></div></td></tr>' . "\n";    
      $rci .= '    </table></td>' . "\n";            
      $rci .= '  </tr>' . "\n";
      $rci .= '</table>' . "\n";
      
      //$rci .= '      <tr><td>' . tep_draw_separator('pixel_trans.gif', '1', '5') . '</td></tr>' . "\n";      
      while ($history = tep_db_fetch_array($history_query)) {
        $products_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$history['orders_id'] . "'");
        $products = tep_db_fetch_array($products_query);
        if (tep_not_null($history['delivery_name'])) {
          $order_type = TEXT_ORDER_SHIPPED_TO;
          $order_name = $history['delivery_name'];
        } else {
          $order_type = TEXT_ORDER_BILLED_TO;
          $order_name = $history['billing_name'];
        }
        $rci .= '      <table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n";
        $rci .= '        <tr>' . "\n";
        $rci .= '          <td class="main"><b>' . TEXT_ORDER_NUMBER . '</b> ' . $history['orders_id'] . '</td>' . "\n";
        $rci .= '          <td class="main" align="right"><b>' . TEXT_ORDER_STATUS . '</b> ' . $history['orders_status_name'] . '</td>' . "\n";
        $rci .= '        </tr>' . "\n";
        $rci .= '      </table>' . "\n";
        $rci .= '      <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">' . "\n";
        $rci .= '        <tr class="infoBoxContents">' . "\n";
        $rci .= '          <td><table border="0" width="100%" cellspacing="2" cellpadding="4">' . "\n";
        $rci .= '            <tr>' . "\n";
        $rci .= '              <td class="main" width="50%" valign="top">' . TEXT_ORDER_DATE . '</b> ' . tep_date_long($history['date_purchased']) . '<br><b>' . $order_type . '</b> ' . tep_output_string_protected($order_name) . '</td>' . "\n";
        $rci .= '              <td class="main" width="30%" valign="top"><b>' . TEXT_ORDER_PRODUCTS . '</b> ' . $products['count'] . '<br><b>' . TEXT_ORDER_COST . '</b> ' . strip_tags($history['order_total']) . '</td>' . "\n";
        $rci .= '              <td class="main" width="20%"><a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&amp;' : '') . 'order_id=' . $history['orders_id'], 'SSL') . '">' . tep_template_image_button('small_view.gif', SMALL_IMAGE_BUTTON_VIEW) . '</a></td>' . "\n";
        $rci .= '            </tr>' . "\n";
        $rci .= '          </table></td>' . "\n";
        $rci .= '        </tr>' . "\n"; 
        $rci .= '        <tr><td width="100%">' . tep_draw_separator('pixel_black.gif', '100%', '1') . '</td></tr>' . "\n"; 
        $rci .= '      </table>' . "\n";
      }
    }
  }
}
return $rci; 
?> 