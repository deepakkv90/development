<?php
/*
  $Id: shipping_estimator.php,v 1.1.1.1 2004/03/04 23:41:12 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 Edwin Bekaert (edwin@ednique.com)

  Customized by: Linda McGrath osCommerce@WebMakers.com
  * This now handles Free Shipping for orders over $total as defined in the Admin
  * This now shows Free Shipping on Virtual products
  * Everything is contained in an infobox for easier placement.

  Released under the GNU General Public License

  http://forums.oscommerce.com/viewtopic.php?t=38411

  http://www.oscommerce.com/community/contributions,1094
*/
?>
<!-- shipping_estimator modules //-->
<?php
// Only do when something is in the cart
if ($cart->count_contents() > 0) {

// Could be placed in english.php
// shopping cart quotes
 
  // shipping cost
  require('includes/classes/http_client.php'); // shipping in basket
  require(DIR_WS_LANGUAGES . $language . '/modules/shipping_estimator.php');

  if($cart->get_content_type() !== 'virtual') {
    if ( isset($_SESSION['customer_id']) ) {
      // user is logged in
      if (isset($_POST['address_id'])){
        // user changed address
        $_SESSION['sendto'] = $_POST['address_id'];
      }elseif ( isset($_SESSION['cart_address_id']) ){
        // user once changed address
        $_SESSION['sendto'] = $_SESSION['cart_address_id'];
      }else{
        // first timer
        $_SESSION['sendto'] = $_SESSION['customer_default_address_id'];
      }
      // set session now
      $_SESSION['cart_address_id'] = $_SESSION['sendto'];
      // include the order class (uses the sendto !)
      require(DIR_WS_CLASSES . 'order.php');
      $order = new order;
    }else{
      // user not logged in !
      if (isset($_POST['country_id'])){
        // country is selected
        $country_info = tep_get_countries($_POST['country_id'],true);
        $order->delivery = array('postcode' => $_POST['zip_code'],
                                 'country' => array('id' => $_POST['country_id'], 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']),
                                 'country_id' => $_POST['country_id'],
                                 'format_id' => tep_get_address_format_id($_POST['country_id']));
        $_SESSION['cart_country_id'] = $_POST['country_id'];
        $_SESSION['cart_zip_code'] = $_POST['zip_code'];
      }elseif ( isset($_SESSION['cart_country_id']) ){
        // session is available
        $country_info = tep_get_countries($_SESSION['cart_country_id'], true);
        $order->delivery = array('postcode' => $_SESSION['cart_zip_code'],
                                 'country' => array('id' => $_SESSION['cart_country_id'], 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']),
                                 'country_id' => $_SESSION['cart_country_id'],
                                 'format_id' => tep_get_address_format_id($_SESSION['cart_country_id']));
      } else {
        // first timer
        $_SESSION['cart_country_id'] = STORE_COUNTRY;
// WebMakers.com Added: changes
// changed from STORE_ORIGIN_ZIP to SHIPPING_ORIGIN_ZIP
        $_SESSION['cart_zip_code'] = SHIPPING_ORIGIN_ZIP;
        $country_info = tep_get_countries(STORE_COUNTRY,true);
        $order->delivery = array('postcode' => SHIPPING_ORIGIN_ZIP,
                                 'country' => array('id' => STORE_COUNTRY, 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']),
                                 'country_id' => STORE_COUNTRY,
                                 'format_id' => (isset($_POST['country_id']) ? tep_get_address_format_id($_POST['country_id']) : 0));
      }
      // set the cost to be able to calvculate free shipping
      $order->info = array('total' => $cart->show_total()); // TAX ????
    }
    // weight and count needed for shipping !
    $total_weight = $cart->show_weight();
    $total_count = $cart->count_contents();
    require(DIR_WS_CLASSES . 'shipping.php');
    $shipping_modules = new shipping;
    $quotes = $shipping_modules->quote();
    $cheapest = $shipping_modules->cheapest();
    // set selections for displaying
    $selected_country = $order->delivery['country']['id'];
    $selected_zip = $order->delivery['postcode'];
    $selected_address = (isset($_SESSION['sendto']) ? $_SESSION['sendto']  : 0);
  }
    // eo shipping cost


// check free shipping based on order $total
  if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
    switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
      case 'national':
        if ($order->delivery['country_id'] == STORE_COUNTRY) $pass = true; break;
      case 'international':
        if ($order->delivery['country_id'] != STORE_COUNTRY) $pass = true; break;
      case 'both':
        $pass = true; break;
      default:
        $pass = false; break;
    }
    $free_shipping = false;
    if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
      $free_shipping = true;
      include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
    }
  } else {
    $free_shipping = false;
  }


//begineoutput

//  $ShipTxt.='<table border="0" width="100%" cellspacing="0" cellpadding="' . CELLPADDING_SUB . '">';
  $ShipTxt= tep_draw_form('estimator', tep_href_link(basename($PHP_SELF), '', 'NONSSL'), 'post'); //'onSubmit="return check_form();"'


  if(sizeof($quotes)) {
    if ( isset($_SESSION['customer_id']) ) {
      // logged in
      $addresses_query = tep_db_query("select address_book_id, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $_SESSION['customer_id'] . "'");
      while ($addresses = tep_db_fetch_array($addresses_query)) {
        $addresses_array[] = array('id' => $addresses['address_book_id'], 'text' => tep_address_format(tep_get_address_format_id($addresses['country_id']), $addresses, 0, ' ', ' '));
      }
      $ShipTxt.='<tr><td colspan="3" class="main">' . ($total_count == 1 ?  SHIPPING_METHOD_ITEM  :  SHIPPING_METHOD_ITEMS) . $total_count . '&nbsp;-&nbsp;' . SHIPPING_METHOD_WEIGHT . $total_weight . SHIPPING_METHOD_WEIGHT_UNIT . '</td></tr>';
      $ShipTxt.='<tr><td colspan="3" class="main" nowrap="nowrap">' .
                SHIPPING_METHOD_ADDRESS .'&nbsp;'. tep_draw_pull_down_menu('address_id', $addresses_array, $selected_address, 'onchange="document.estimator.submit();return false;"').'</td></tr>';
      $ShipTxt.='<tr valign="top"><td class="main">' . SHIPPING_METHOD_TO .'</td><td colspan="2" class="main">'. tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>') . '</td></tr>';
    } else {
      // not logged in change text output
   if (strstr($PHP_SELF,'shopping_cart.php')) {     
      $SHIPPING_OPTIONS_LOGIN = SHIPPING_OPTIONS_LOGIN_A ;
      }else{
      $SHIPPING_OPTIONS_LOGIN = SHIPPING_OPTIONS_LOGIN_B ;
      }
      
      $ShipTxt.=tep_output_warning($SHIPPING_OPTIONS_LOGIN);
      $ShipTxt.='<tr><td colspan="3" class="main">' . ($total_count == 1 ?  SHIPPING_METHOD_ITEM :  SHIPPING_METHOD_ITEMS ) . $total_count . '&nbsp;-&nbsp;' . SHIPPING_METHOD_WEIGHT  . $total_weight . SHIPPING_METHOD_WEIGHT_UNIT . '</td></tr>';
      $ShipTxt.='<tr><td colspan="3" class="main" nowrap="nowrap">' .
                ENTRY_COUNTRY .'&nbsp;'. tep_get_country_list('country_id', $selected_country,'style="width=200;"');
      if(SHIPPING_METHOD_ZIP_REQUIRED == "true"){
        $ShipTxt.='</td></tr>          <tr>
            <td colspan="3" class="main" nowrap="nowrap">' . tep_draw_separator('pixel_trans.gif', '100%', '10') . '</td>
          </tr><tr><td colspan="3" class="main" nowrap="nowrap">'.ENTRY_POST_CODE .'&nbsp;'. tep_draw_input_field('zip_code', $selected_zip, 'size="10"');
      }
      $ShipTxt.='&nbsp;<a href="_" onclick="document.estimator.submit();return false;">' . SHIPPING_METHOD_RECALCULATE.'</a></td></tr>';
    }
    if ($free_shipping==1) {
      // order $total is free
      $ShipTxt.='<tr><td colspan="3" class="main">'.tep_draw_separator().'</td></tr>';
      $ShipTxt.='<tr><td>&nbsp;</td><td class="main">' . sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . '</td><td>&nbsp;</td></tr>';
    }else{
      // shipping display
      $ShipTxt.='<tr><td></td><td class="main" align="left"><b>' . SHIPPING_METHOD_TEXT . '</b></td><td class="main" align="center"><b>' . SHIPPING_METHOD_RATES . '</b></td></tr>';
      $ShipTxt.='<tr><td colspan="3" class="main">'.tep_draw_separator().'</td></tr>';
      for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
        if(isset($quotes[$i]['methods']) && is_array($quotes[$i]['methods']) && sizeof($quotes[$i]['methods'])==1){
          // simple shipping method
          $thisquoteid = $quotes[$i]['id'].'_'.$quotes[$i]['methods'][0]['id'];
          $ShipTxt.= '<tr class="'.(isset($extra) ? $extra : '').'">';
          $ShipTxt.='<td class="main">'.(isset($quotes[$i]['icon']) ? $quotes[$i]['icon'] : '').'&nbsp;</td>';
          if(isset($quotes[$i]['error'])){
            $ShipTxt.='<td colspan="2" class="main">'.$quotes[$i]['module'].'&nbsp;';
            $ShipTxt.= '('.$quotes[$i]['error'].')</td></tr>';
          }else{
            if($cheapest['id'] == $thisquoteid){
              $ShipTxt.='<td class="main"><b>'.$quotes[$i]['module'].'&nbsp;';
              $ShipTxt.= '('.$quotes[$i]['methods'][0]['title'].')</b></td><td align="right" class="main"><b>'.$currencies->format(tep_add_tax($quotes[$i]['methods'][0]['cost'], $quotes[$i]['tax'])).'<b></td></tr>';
            }else{
              $ShipTxt.='<td class="main">'.$quotes[$i]['module'].'&nbsp;';
              $ShipTxt.= '('.$quotes[$i]['methods'][0]['title'].')</td><td align="right" class="main">'.$currencies->format(tep_add_tax($quotes[$i]['methods'][0]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))).'</td></tr>';
            }
          }
        } else {
          if (!isset($quotes[$i]['methods'])) {
            $quotes[$i]['methods'] = array();
          }
          // shipping method with sub methods (multipickup)
          for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
            $thisquoteid = $quotes[$i]['id'].'_'.$quotes[$i]['methods'][$j]['id'];
            $ShipTxt.= '<tr class="'.$extra.'">';
            $ShipTxt.='<td class="main">'.$quotes[$i]['icon'].'&nbsp;</td>';
            if($quotes[$i]['error']){
              $ShipTxt.='<td colspan="2" class="main">'.$quotes[$i]['module'].'&nbsp;';
              $ShipTxt.= '('.$quotes[$i]['error'].')</td></tr>';
            }else{
              if($cheapest['id'] == $thisquoteid){
                $ShipTxt.='<td class="main"><b>'.$quotes[$i]['module'].'&nbsp;';
                $ShipTxt.= '('.$quotes[$i]['methods'][$j]['title'].')</b></td><td align="right" class="main"><b>'.$currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])).'</b></td></tr>';
              }else{
                $ShipTxt.='<td class="main">'.$quotes[$i]['module'].'&nbsp;';
                $ShipTxt.= '('.$quotes[$i]['methods'][$j]['title'].')</td><td align="right" class="main">'.$currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])).'</td></tr>';
              }
            }
          }
        }
      }
    }
  } else {
    // virtual product/download
    $ShipTxt.='<tr><td class="main">' . SHIPPING_METHOD_FREE_TEXT . ' ' . SHIPPING_METHOD_ALL_DOWNLOADS . '</td></tr>';
  }
  $ShipTxt.= '</td></tr>';
  $ShipTxt.= '</form>';

// build box
  $info_box_contents = array();
  $info_box_contents[] = array('text' => SHIPPING_OPTIONS);
  new infoBoxHeading($info_box_contents, '');
  
  $info_box_contents = array();
  $info_box_contents[] = array('text'  =>  $ShipTxt  );

  new infoBox($info_box_contents);

 if (TEMPLATE_INCLUDE_FOOTER =='true'){
   $info_box_contents = array();
   $info_box_contents[] = array('align' => 'left',
                                 'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                               );
  new infoBoxFooter($info_box_contents);
}
} // Only do when something is in the cart
?>
<!-- shipping_estimator_eof //-->
