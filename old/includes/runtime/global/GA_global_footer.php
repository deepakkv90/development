<?php
/*
  $Id: GA_global_footer.php,v 1.0.0.0 2008/02/14 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
global $request_type, $languages_id, $content;

$rci = '<!-- Google Analytics RCI start -->' . "\n";
if (defined('GOOGLEANALYTICS_UA_NUMBER') && (GOOGLEANALYTICS_UA_NUMBER != 'UA-' && GOOGLEANALYTICS_UA_NUMBER != '')) {
  $rci .= '<script type="text/javascript">' . "\n";
  $rci .= 'var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");' . "\n";
  $rci .= 'document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));' . "\n";
  $rci .= '</script>' . "\n";
  $rci .= '<script type="text/javascript">' . "\n";
  $rci .= 'console.log("test"); var pageTracker = _gat._getTracker("' . GOOGLEANALYTICS_UA_NUMBER . '");' . "\n";
  $rci .= 'pageTracker._initData();' . "\n";
  $rci .= 'pageTracker._trackPageview();' . "\n";
  if ($content == CONTENT_CHECKOUT_SUCCESS) {
    $analytics_affiliation = (isset($_SESSION['affiliate_ref'])) ? $_SESSION['affiliate_ref'] : '';
    $customer_id = (isset($_SESSION['customer_id'])) ? $_SESSION['customer_id'] : '';;
    $order_id = (isset($_GET['order_id'])) ? $_GET['order_id'] : 0;
    // Get info for "city", "state", "country"
    $orders_query = tep_db_query("SELECT customers_city, customers_state, customers_country 
                                    from " . TABLE_ORDERS . " 
                                  WHERE orders_id = '" . (int)$order_id . "' 
                                    and customers_id = '" . (int)$customer_id . "'");
    $orders = tep_db_fetch_array($orders_query);
    $totals_query = tep_db_query("SELECT value, class 
                                    from " . TABLE_ORDERS_TOTAL . " 
                                  WHERE orders_id = '" . (int)$order_id . "' 
                                  ORDER BY sort_order");
    // Set values for "total", "tax" and "shipping"
    $analytics_total = 0;
    $analytics_tax = 0;
    $analytics_shipping = 0;
    $transaction_string = '';
    while ($totals = tep_db_fetch_array($totals_query)) {
      if ($totals['class'] == 'ot_total') {
        $analytics_total = number_format($totals['value'], 2);
        $total_flag = 'true';
      } else if ($totals['class'] == 'ot_tax') {
        $analytics_tax = number_format($totals['value'], 2);
        $tax_flag = 'true';
      } else if ($totals['class'] == 'ot_shipping') {
        $analytics_shipping = number_format($totals['value'], 2);
        $shipping_flag = 'true';
      }
    }
    // Prepare the Analytics "Transaction" string
    $transaction_string .=  'pageTracker._addTrans( ' . "\n";
    $transaction_string .=  '   "' . $order_id . '",' . "\n";// Order ID
    $transaction_string .=  '   "' . $analytics_affiliation . '",' . "\n";// Affiliation
    $transaction_string .=  '   "' . $analytics_total . '",' . "\n";// Total
    $transaction_string .=  '   "' . $analytics_tax . '",' . "\n";//Tax
    $transaction_string .=  '   "' . $analytics_shipping . '",' . "\n";//Shipping
    $transaction_string .=  '   "' . tep_db_output($orders['customers_city']) . '",' . "\n";//Customer City
    $transaction_string .=  '   "' . tep_db_output($orders['customers_state']) . '",' . "\n";//Customer State
    $transaction_string .=  '   "' . tep_db_output($orders['customers_country']) . '"' . "\n";//Customer Country
    $transaction_string .=  ');' . "\n";
    // Get products info for Analytics "Products"
    $item_string = '';
    $items_query = tep_db_query("SELECT DISTINCT o.products_id, o.products_model, o.products_name, o.final_price, o.products_quantity, p2c.categories_id, cd.categories_name 
                                   from " . TABLE_ORDERS_PRODUCTS . " o, 
                                        " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, 
                                        " . TABLE_CATEGORIES_DESCRIPTION . " cd  
                                 WHERE o.orders_id = '" . $order_id . "' 
                                   and p2c.products_id = o.products_id 
                                   and cd.categories_id = p2c.categories_id 
                                   and cd.language_id = '" . $languages_id . "' 
                                 ORDER BY products_name");
    while ($items = tep_db_fetch_array($items_query)) {
      $item_string .=  'pageTracker._addItem(' . "\n";
      $item_string .=  '  "' . $order_id . '",' . "\n";//Order ID
      $item_string .=  '  "' . $items['products_id'] . '",' . "\n";// SKU
      $item_string .=  '  "' . tep_db_output($items['products_name']) . '",' . "\n";//Product Name
      $item_string .=  '  "' . tep_db_output($items['categories_name']) . '", ' . "\n";// Category Name
      $item_string .=  '  "' . number_format($items['final_price'], 2) . '",' . "\n";// Price
      $item_string .=  '  "' . $items['products_quantity'] . '"' . "\n";//Qty Ordered
      $item_string .=  ');' . "\n";
    }
    $rci .= "\n";
    $rci .= $transaction_string .  $item_string . "\n";
    $rci .= '  pageTracker._trackTrans();' . "\n";
  } // if page == checkout_success eof
  $rci .= '</script>' . "\n";
} else { //show anyletics code not updated only on view source
  $rci .= '<!-- Google Analytics Not Active! -->' . "\n"; 
}
$rci .= '<!-- Google Analytics RCI end -->' . "\n"; 

return $rci;
?>