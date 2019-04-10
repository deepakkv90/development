<?php
/*
  $Id: shopping_cart.php,v 1.1.1.1 2004/03/04 23:39:49 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class shoppingCart {
    var $contents, $total, $weight, $cartID, $content_type,$weight_virtual;

    function shoppingCart() {
      if ( ! isset($_SESSION['shoppingCart_data']) ) {
        $this->reset();
        $_SESSION['shoppingCart_data'] = array('contents' => array(),
                                               'total' => 0,
                                               'weight' => 0,
                                               'cartID' => 0,
                                               'content_type' => ''
                                               );
      }
      $this->contents =& $_SESSION['shoppingCart_data']['contents'];
      $this->total =& $_SESSION['shoppingCart_data']['total'];
      $this->weight =& $_SESSION['shoppingCart_data']['weight'];
      $this->cartID =& $_SESSION['shoppingCart_data']['cartID'];
      $this->content_type =& $_SESSION['shoppingCart_data']['content_type'];
    }

    function restore_contents() {
//ICW replace line
      global $customer_id, $languages_id, $REMOTE_ADDR;

      if ( !isset($_SESSION['customer_id']) ) return false;

// insert current cart contents in database
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $qty = $this->contents[$products_id]['qty'];
          $product_query = tep_db_query("select products_id from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . tep_db_input($products_id) . "'");
          if (!tep_db_num_rows($product_query)) {
            tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) values ('" . (int)$_SESSION['customer_id'] . "', '" . tep_db_input($products_id) . "', '" . $qty . "', '" . date('Ymd') . "')");
            if (isset($this->contents[$products_id]['attributes'])) {
              reset($this->contents[$products_id]['attributes']);
              while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
                // BOM - Options Catagories
                if ( is_array($value) ) {
                  $new_value = 0;
                  $attr_value = serialize($value);
                } else {
                  $new_value = $value;
                  $attr_value = NULL;
                }
                tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id, products_options_value_text) values ('" . (int)$_SESSION['customer_id'] . "', '" . tep_db_input($products_id) . "', '" . (int)$option . "', '" . (int)$new_value . "', '" . tep_db_input($attr_value) . "')");
                // EOM - Options Catagories
              }
            }
          } else {
            tep_db_query("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . $qty . "' where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . tep_db_input($products_id) . "'");
          }
        }
//ICW ADDDED FOR CREDIT CLASS GV - START
        if ( isset($_SESSION['gv_id']) ) {
          $gv_query = tep_db_query("insert into  " . TABLE_COUPON_REDEEM_TRACK . " (coupon_id, customer_id, redeem_date, redeem_ip) values ('" . (int)$_SESSION['gv_id'] . "', '" . (int)$_SESSION['customer_id'] . "', now(),'" . $REMOTE_ADDR . "')");
          $gv_update = tep_db_query("update " . TABLE_COUPONS . " set coupon_active = 'N' where coupon_id = '" . (int)$_SESSION['gv_id'] . "'");
          tep_gv_account_update($_SESSION['customer_id'], $_SESSION['gv_id']);
          unset($_SESSION['gv_id']);
        }
//ICW ADDDED FOR CREDIT CLASS GV - END
      }

// reset per-session cart contents, but not the database contents
      $this->reset(false);

      $products_query = tep_db_query("select products_id, customers_basket_quantity from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
      while ($products = tep_db_fetch_array($products_query)) {
        $this->contents[$products['products_id']] = array('qty' => $products['customers_basket_quantity']);
// attributes
        // BOM - Options Catagories
        // the query was changed for tracker issue 997 to provide a order to the attributes
        // $attributes_query = tep_db_query("select products_options_id, products_options_value_id, products_options_value_text from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products['products_id']) . "'");
        $attributes_query = tep_db_query("SELECT a.products_options_id, a.products_options_value_id, a.products_options_value_text
                                          FROM " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " a,
                                               " . TABLE_PRODUCTS_OPTIONS_TEXT . " ot
                                          WHERE a.customers_id = " . (int)$customer_id . "
                                            AND a.products_id = '" . tep_db_input($products['products_id']) . "'
                                            AND ot.products_options_text_id = a.products_options_id
                                            AND ot.language_id = " . (int)$languages_id . "
                                          ORDER BY ot.products_options_name, a.products_options_value_text
                                            ");
        while ($attributes = tep_db_fetch_array($attributes_query)) {
          if ( ($attributes['products_options_value_id'] == 0)  &&  ! is_null($attributes['products_options_value_text']) ) {
            $this->contents[$products['products_id']]['attributes'][$attributes['products_options_id']] = unserialize($attributes['products_options_value_text']);
          } else {
            $this->contents[$products['products_id']]['attributes'][$attributes['products_options_id']] = $attributes['products_options_value_id'];
          }
        }
        // EOM - Options Catagories
      }

      $this->cleanup();
    }

    function reset($reset_database = false) {

      $this->contents = array();
      $this->total = 0;
      $this->weight = 0;
      $this->content_type = false;

      if ( isset($_SESSION['customer_id']) && ($reset_database == true)) {
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
      }

      // unset($this->cartID);  this was changed to better support the new calss session handling
      $this->cartID = '';
    }

    function add_cart($products_id, $qty = '1', $attributes = '', $notify = true) {

      global $languages_id;
      // add reality check, is this product valid and active?
      $product_check_query = tep_db_query("SELECT p.products_id
                                           FROM " . TABLE_PRODUCTS . " p,
                                                " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                           WHERE (p.products_status = 1 or (p.products_status <> 1 and p.products_parent_id <> 0))
                                             and p.products_id = " . (int)$products_id . "
                                             and pd.products_id = " . (int)$products_id . "
                                             and pd.language_id = " . (int)$languages_id);
      if (tep_db_num_rows($product_check_query) < 1) { // nothing here for use to use
        return false;
      }

      $products_id = tep_get_uprid($products_id, $attributes);
      if ($notify == true) {
        $_SESSION['new_products_id_in_cart'] = $products_id;
      }

      if ($this->in_cart($products_id)) {
        $this->update_quantity($products_id, $qty, $attributes);
      } else {
        $this->contents[] = array($products_id);
        $this->contents[$products_id] = array('qty' => $qty);

        if ( isset($_SESSION['customer_id']) ){
          tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) values ('" . (int)$_SESSION['customer_id'] . "', '" . tep_db_input($products_id) . "', '" . $qty . "', '" . date('Ymd') . "')");
        }

        if (is_array($attributes)) {
          reset($attributes);
          while (list($option, $value) = each($attributes)) {
            if ( !is_array($value) ) {
              $this->contents[$products_id]['attributes'][$option] = $value;
              $attr_value = NULL;
              if ( isset($_SESSION['customer_id']) ) tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id, products_options_value_text) values ('" . (int)$_SESSION['customer_id'] . "', '" . tep_db_input($products_id) . "', '" . (int)$option . "', '" . (int)$value . "', '" . tep_db_input($attr_value) . "')");
            } else {
              $this->contents[$products_id]['attributes'][$option] = $value;
              $attr_value = serialize($value);
              if ( isset($_SESSION['customer_id']) ) tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id, products_options_value_text) values ('" . (int)$_SESSION['customer_id'] . "', '" . tep_db_input($products_id) . "', '" . (int)$option . "', '0', '" . tep_db_input($attr_value) . "')");
            }
          }
        }
      }

      $this->cleanup();

// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
      $this->cartID = $this->generate_cart_id();
    }

    function update_quantity($products_id, $quantity = '', $attributes = '') {

      if (empty($quantity)) return true; // nothing needs to be updated if theres no quantity, so we return true..

      $this->contents[$products_id]['qty'] = $quantity;
// update database
      if ( isset($_SESSION['customer_id']) ) tep_db_query("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . $quantity . "' where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . tep_db_input($products_id) . "'");

      if (is_array($attributes)) {
        reset($attributes);
        while (list($option, $value) = each($attributes)) {
          // BOM - Options Catagories
          $attr_value = NULL;
          $this->contents[$products_id]['attributes'][$option] = $value;
          if ( !is_array($value) ) {
            $attr_value = $value;
            if ( isset($_SESSION['customer_id']) ) tep_db_query("update " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " set products_options_value_id = '" . (int)$value . "', products_options_value_text = '" . tep_db_input($attr_value) . "' where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . tep_db_input($products_id) . "' and products_options_id = '" . (int)$option . "'");
          } elseif ( isset($attributes[$option]['t']) ) {
            $attr_value = htmlspecialchars(stripslashes($attributes[$option]['t']), ENT_QUOTES);
            if ( isset($_SESSION['customer_id']) ) tep_db_query("update " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " set products_options_value_id = '" . (int)$value . "', products_options_value_text = '" . tep_db_input($attr_value) . "' where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . tep_db_input($products_id) . "' and products_options_id = '" . (int)$option . "'");
          } elseif ( isset($attributes[$option]['c']) ) {
            foreach ($value as $v) {
              $attr_value = $v;
              if ( isset($_SESSION['customer_id']) ) tep_db_query("update " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " set products_options_value_id = '" . (int)$value . "', products_options_value_text = '" . tep_db_input($attr_value) . "' where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . tep_db_input($products_id) . "' and products_options_id = '" . (int)$option . "'");
            }
          }
          // EOM - Options Catagories
        }
      }
    }

    function cleanup() {


      reset($this->contents);
      while (list($key,) = each($this->contents)) {
      if (!isset($this->contents[$key]['qty'])){
      $this->contents[$key]['qty'] = 0  ;
      }
        if ($this->contents[$key]['qty'] < 1) {

          unset($this->contents[$key]);
// remove from database
          if ( isset($_SESSION['customer_id']) ) {
            tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . tep_db_input($key) . "'");
            tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . tep_db_input($key) . "'");
          }
        }
      }
    }

    function count_contents() {  // get total number of items in cart
      $total_items = 0;
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $total_items += $this->get_quantity($products_id);
        }
      }

      return $total_items;
    }

    function get_quantity($products_id) {
      if (isset($this->contents[$products_id])) {
        return $this->contents[$products_id]['qty'];
      } else {
        return 0;
      }
    }

    function in_cart($products_id) {
      if (isset($this->contents[$products_id])) {
        return true;
      } else {
        return false;
      }
    }

    function remove($products_id) {

      // BOM - Options Catagories
//      $products_id = tep_get_uprid($products_id, $attributes);
      $products_id = tep_get_uprid($products_id, '');
      // EOM - Options Catagories
      unset($this->contents[$products_id]);
// remove from database
      if ( isset($_SESSION['customer_id']) ) {
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . tep_db_input($products_id) . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . tep_db_input($products_id) . "'");
      }

// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
      $this->cartID = $this->generate_cart_id();

    }

    function remove_all() {
      $this->reset();
    }

    function get_product_id_list() {
      $product_id_list = '';
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $product_id_list .= ', ' . $products_id;
        }
      }

      return substr($product_id_list, 2);
    }

    function calculate() {
      global $languages_id;// Eversun mod for SPPP Qty Price Break Enhancement
      $this->total_virtual = 0; // ICW Gift Voucher System
      $this->total = 0;
      $this->weight = 0;
      if (!is_array($this->contents)) return 0;

      reset($this->contents);
      $cart_contents = $this->contents;
      while (list($products_id, ) = each($this->contents)) {
        $qty = $this->contents[$products_id]['qty'];
        $qty_new = 0;
        reset($cart_contents);
        foreach ($cart_contents as $key => $value) {
          if ((int)$key == (int)$products_id) {
            $qty_new += $cart_contents[$key]['qty'];
          }
        }
// Eversun mod for sppc and qty price breaks
// global variable (session) $sppc_customer_group_id -> class variable cg_id


          if(isset($_SESSION['customer_id']) && $_SESSION['customer_id']=="") {
            $customer_group_id="G";
          } else {
            $getcustomer_GroupID_query = tep_db_query("select customers_group_id   from " . TABLE_CUSTOMERS . " where  customers_id = '" . (isset($_SESSION['customer_id']) ? (int)$_SESSION['customer_id'] : 0) . "'");
            $getcustomer_GroupID = tep_db_fetch_array($getcustomer_GroupID_query);
            $customer_group_id=$getcustomer_GroupID['customers_group_id'];
          }
          $this->cg_id = $customer_group_id; #$sppc_customer_group_id;

          $pf = new PriceFormatter;
// Eversun mod end for sppc and qty price breaks
// products price
          $product_query = tep_db_query("select products_id, products_price, products_tax_class_id, products_weight from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");

      // Eversun mod  for sppc and qty price breaks
    // if ($product = tep_db_fetch_array($product_query)) {
          if ($product = $pf->loadProduct($products_id, $languages_id)) {
    // Eversun mod end for sppc and qty price breaks
// ICW ORDER TOTAL CREDIT CLASS Start Amendment
            $no_count = 1;
            $gv_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
            $gv_result = tep_db_fetch_array($gv_query);
            if (ereg('^GIFT', $gv_result['products_model'])) {
              $no_count = 0;
            }
// ICW ORDER TOTAL  CREDIT CLASS End Amendment
            $prid = $product['products_id'];
            $products_tax = tep_get_tax_rate($product['products_tax_class_id']);


      // Eversun mod end for sppc and qty price breaks
          //$products_price = $product['products_price'];
            $products_price = $pf->computePrice($qty_new);
            if($pf->hasCustomerGroupPrice()=='true') {
              $products_price= $pf->hasCustomerGroupcomputePrice($qty);
            }
// Eversun mod for sppc and qty price breaks
            $products_weight = $product['products_weight'];

//Eversun mod end for sppc and qty price breaks
            $this->total_virtual += tep_add_tax($products_price, $products_tax) * $qty * $no_count;// ICW CREDIT CLASS;
            $this->weight_virtual += ($qty * $products_weight) * $no_count;// ICW CREDIT CLASS;
            $this->total += tep_add_tax($products_price, $products_tax) * $qty;
            $this->weight += ($qty * $products_weight);
          }

// attributes price
          if (isset($this->contents[$products_id]['attributes'])) {
          $products_id_tmp = $products_id;
            reset($this->contents[$products_id]['attributes']);
            while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {

                  if(tep_subproducts_parent($products_id_tmp)){
                   $products_id_query = tep_subproducts_parent($products_id_tmp);
                   }else{
                   $products_id_query = $products_id_tmp;
                   }

            if ( !is_array($value) ) {
              $attribute_price_query = tep_db_query("select options_values_price as price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id_query . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "'");
              $attribute_price = tep_db_fetch_array($attribute_price_query);
              if ($attribute_price['price_prefix'] == '+') {
                $this->total += $qty * tep_add_tax($attribute_price['price'], $products_tax);
              } else {
                $this->total -= $qty * tep_add_tax($attribute_price['price'], $products_tax);
              }
            } elseif ( isset($value['t']) ) {
              $attribute_price_query = tep_db_query("select options_values_price as price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id_query . "' and options_id = '" . (int)$option . "'");
              $attribute_price = tep_db_fetch_array($attribute_price_query);
              if ($attribute_price['price_prefix'] == '+') {
                $this->total += $qty * tep_add_tax($attribute_price['price'], $products_tax);
              } else {
                $this->total -= $qty * tep_add_tax($attribute_price['price'], $products_tax);
              }
            } elseif ( isset($value['c']) ) {
              foreach ( $value['c'] as $v ) {
                $attribute_price_query = tep_db_query("select options_values_price as price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id_query . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$v . "'");
                $attribute_price = tep_db_fetch_array($attribute_price_query);
                if ($attribute_price['price_prefix'] == '+') {
                  $this->total += $qty * tep_add_tax($attribute_price['price'], $products_tax);
                } else {
                  $this->total -= $qty * tep_add_tax($attribute_price['price'], $products_tax);
                }
              }
            }
            // EOM - Options Catagories
          }
        }
      }
    }

    function attributes_price($products_id) {
      $attributes_price = 0;


      if (isset($this->contents[$products_id]['attributes'])) {
        reset($this->contents[$products_id]['attributes']);
        while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
          // BOM - Options Catagories
                $products_id_tmp = '';
                $products_id_query = '';
                 $products_id_tmp = tep_subproducts_parent(tep_get_prid($products_id));

                if($products_id_tmp == false){
                   $products_id_query = tep_get_prid($products_id);
                   }else{
                   $products_id_query = $products_id_tmp;
                   }
          if ( !is_array($value) ) {
            $attribute_price_query = tep_db_query("select options_values_price as price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id_query . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "'");
            $attribute_price = tep_db_fetch_array($attribute_price_query);
            if ($attribute_price['price_prefix'] == '+') {
              $attributes_price += $attribute_price['price'];
            } else {
              $attributes_price -= $attribute_price['price'];
            }
          } elseif ( isset($value['t']) ) {
            $attribute_price_query = tep_db_query("select options_values_price as price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id_query . "' and options_id = '" . (int)$option . "'");
            $attribute_price = tep_db_fetch_array($attribute_price_query);
            if ($attribute_price['price_prefix'] == '+') {
              $attributes_price += $attribute_price['price'];
            } else {
              $attributes_price -= $attribute_price['price'];
            }
          } elseif ( isset($value['c']) ) {
            foreach ( $value['c'] as $v ) {
              $attribute_price_query = tep_db_query("select options_values_price as price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id_query . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$v . "'");
              $attribute_price = tep_db_fetch_array($attribute_price_query);
              if ($attribute_price['price_prefix'] == '+') {
                $attributes_price += $attribute_price['price'];
              } else {
                $attributes_price -= $attribute_price['price'];
              }
            }
          }
          // EOM - Options Catagories
        }
      }

      return $attributes_price;
    }

    function get_products() {
      global $languages_id;
      // Eversun mod for sppc and qty price breaks
      if ( ! isset($_SESSION['sppc_customer_group_id']) ) {
        $this->cg_id = 'G';
      } else {
        $this->cg_id = $_SESSION['sppc_customer_group_id'];
      }
      $pf = new PriceFormatter;
      // Eversun mod end for sppc and qty price breaks
      if (!is_array($this->contents)) return false;

      $products_array = array();
      reset($this->contents);
      while (list($products_id, ) = each($this->contents)) {
        $cart_contents = $this->contents;
        $qty_new = 0;
        reset($cart_contents);
        foreach ($cart_contents as $key => $value) {
          if ((int)$key == (int)$products_id) {
            $qty_new += $cart_contents[$key]['qty'];
          }
        }
        if ($products = $pf->loadProduct($products_id, $languages_id)) {
//          $products_price = $pf->computePrice($this->contents[$products_id]['qty']);
          $products_price = $pf->computePrice($qty_new);
          if($pf->hasCustomerGroupPrice()=='true') {
            $products_price= $pf->hasCustomerGroupcomputePrice($this->contents[$products_id]['qty']);
          }
          //Eversun mod end for sppc and qty price breaks
          $products_array[] = array('id' => $products_id,
                                    'name' => (isset($products['products_name']) ? $products['products_name'] : '') ,
                                    'model' => (isset($products['products_model']) ? $products['products_model'] : ''),
                                    'image' => (isset($products['products_image']) ? $products['products_image'] : ''),
                                    'price' => (isset($products_price) ? $products_price : 0),
                                    'quantity' => $this->contents[$products_id]['qty'],
                                    'weight' => (isset($products['products_weight']) ? $products['products_weight'] : 0),
                                    // Dimensional UPS begin
                                    'length' => $products['products_length'],
                                    'width' => $products['products_width'],
                                    'height' => $products['products_height'],
                                    'ready_to_ship' => $products['products_ready_to_ship'],
                                    // Dimensional UPS end
                                    'final_price' => ($products_price + $this->attributes_price($products_id)),
                                    'tax_class_id' => (isset($products['products_tax_class_id']) ? $products['products_tax_class_id'] : 0),
                                    'attributes' => (isset($this->contents[$products_id]['attributes']) ? $this->contents[$products_id]['attributes'] : ''));
        }
      }

      return $products_array;
    }

    function show_total() {
      $this->calculate();

      return $this->total;
    }

    function show_weight() {
      $this->calculate();

      return $this->weight;
    }
// CREDIT CLASS Start Amendment
    function show_total_virtual() {
      $this->calculate();

      return $this->total_virtual;
    }

    function show_weight_virtual() {
      $this->calculate();

      return $this->weight_virtual;
    }
// CREDIT CLASS End Amendment

    function generate_cart_id($length = 5) {
      return tep_create_random_value($length, 'digits');
    }

    function get_content_type() {
      $this->content_type = false;

      if ( (DOWNLOAD_ENABLED == 'true') && ($this->count_contents() > 0) ) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          if (isset($this->contents[$products_id]['attributes'])) {
            reset($this->contents[$products_id]['attributes']);
            while (list(, $value) = each($this->contents[$products_id]['attributes'])) {
              $virtual_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad where pa.products_id = '" . (int)$products_id . "' and pa.options_values_id = '" . (int)$value . "' and pa.products_attributes_id = pad.products_attributes_id");
              $virtual_check = tep_db_fetch_array($virtual_check_query);

              if ($virtual_check['total'] > 0) {
                switch ($this->content_type) {
                  case 'physical':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'virtual';
                    break;
                }
              } else {
                switch ($this->content_type) {
                  case 'virtual':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'physical';
                    break;
                }
              }
            }
// ICW ADDED CREDIT CLASS - Begin
          } elseif ($this->show_weight() == 0) {
            reset($this->contents);
            while (list($products_id, ) = each($this->contents)) {
              $virtual_check_query = tep_db_query("select products_weight from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'");
              $virtual_check = tep_db_fetch_array($virtual_check_query);
              if ($virtual_check['products_weight'] == 0) {
                switch ($this->content_type) {
                  case 'physical':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'virtual_weight';
                    break;
                }
              } else {
                switch ($this->content_type) {
                  case 'virtual':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'physical';
                    break;
                }
              }
            }
// ICW ADDED CREDIT CLASS - End
          } else {
            switch ($this->content_type) {
              case 'virtual':
                $this->content_type = 'mixed';

                return $this->content_type;
                break;
              default:
                $this->content_type = 'physical';
                break;
            }
          }
        }
      } else {
        $this->content_type = 'physical';
      }

      return $this->content_type;
    }

    function unserialize($broken) {
      for(reset($broken);$kv=each($broken);) {
        $key=$kv['key'];
        if (gettype($this->$key)!="user function")
        $this->$key=$kv['value'];
      }
    }
   // ------------------------ ICWILSON CREDIT CLASS Gift Voucher Addittion-------------------------------Start
   // amend count_contents to show nil contents for shipping
   // as we don't want to quote for 'virtual' item
   // GLOBAL CONSTANTS if NO_COUNT_ZERO_WEIGHT is true then we don't count any product with a weight
   // which is less than or equal to MINIMUM_WEIGHT
   // otherwise we just don't count gift certificates

    function count_contents_virtual() {  // get total number of items in cart disregard gift vouchers
      $total_items = 0;
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $no_count = false;
          $gv_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'");
          $gv_result = tep_db_fetch_array($gv_query);
          if (ereg('^GIFT', $gv_result['products_model'])) {
            $no_count=true;
          }
           if(!defined('NO_COUNT_ZERO_WEIGHT')){
            define('NO_COUNT_ZERO_WEIGHT', '0');
           }
          if (NO_COUNT_ZERO_WEIGHT == 1) {
            $gv_query = tep_db_query("select products_weight from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($products_id) . "'");
            $gv_result=tep_db_fetch_array($gv_query);
            if ($gv_result['products_weight']<=MINIMUM_WEIGHT) {
              $no_count=true;
            }
          }
          if (!$no_count) $total_items += $this->get_quantity($products_id);
        }
      }
      return $total_items;
    }
// ------------------------ ICWILSON CREDIT CLASS Gift Voucher Addittion-------------------------------End
  }
?>
