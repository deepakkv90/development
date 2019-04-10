<?php
/*
  $Id: shopping_cart.php,v 1.1.1.1 2004/03/04 23:40:47 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class shoppingCart {
    var $contents, $total, $weight, $cartID, $content_type,$weight_virtual, $myfiles;

    function shoppingCart() {
      if ( ! isset($_SESSION['shoppingCart_data']) ) {
        $this->reset();
        $_SESSION['shoppingCart_data'] = array('contents' => array(),
                                               'total' => 0,
                                               'weight' => 0,
                                               'cartID' => 0,
                                               'content_type' => '',
											   'myfiles' => ''
                                               );
      }
      $this->contents =& $_SESSION['shoppingCart_data']['contents'];
      $this->total =& $_SESSION['shoppingCart_data']['total'];
      $this->weight =& $_SESSION['shoppingCart_data']['weight'];
      $this->cartID =& $_SESSION['shoppingCart_data']['cartID'];
      $this->content_type =& $_SESSION['shoppingCart_data']['content_type'];
	  $this->myfiles =& $_SESSION['shoppingCart_data']['myfiles'];
    }

    function restore_contents() {
      global $languages_id, $REMOTE_ADDR;

      if ( ! isset($_SESSION['customer_id']) ) return false;

// insert current cart contents in database
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id_string, ) = each($this->contents)) {
          $qty = $this->contents[$products_id_string]['qty'];
          $product_query = tep_db_query("select products_id from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . tep_db_input($products_id_string) . "'");
          if (!tep_db_num_rows($product_query)) {
            tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) values (" . (int)$_SESSION['customer_id'] . ", '" . tep_db_input($products_id_string) . "', " . (int)$qty . ", '" . date('Ymd') . "')");
            if (isset($this->contents[$products_id_string]['attributes'])) {
              reset($this->contents[$products_id_string]['attributes']);
              while (list($option, $value) = each($this->contents[$products_id_string]['attributes'])) {
                if ( is_array($value) ) {
                  $new_value = 0;
                  $attr_value = serialize($value);
                } else {
                  $new_value = $value;
                  $attr_value = NULL;
                }
                tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id, products_options_value_text) values (" . (int)$_SESSION['customer_id'] . ", '" . tep_db_input($products_id_string) . "', " . (int)$option . ", " . (int)$new_value . ", '" . tep_db_input($attr_value) . "')");
              }
            }
          } else {
            tep_db_query("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . $qty . "' where customers_id = " . (int)$_SESSION['customer_id'] . " and products_id = '" . tep_db_input($products_id_string) . "'");
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

      $products_query = tep_db_query("select products_id, customers_basket_quantity from " . TABLE_CUSTOMERS_BASKET . " where customers_id = " . (int)$_SESSION['customer_id']);
      while ($products = tep_db_fetch_array($products_query)) {
        
        $products_id_string = $products['products_id'];
        $products_id = tep_get_prid($products['products_id']);
        
        // add reality check, is this product valid and active?
        // it is possible the product counld have been disabled since being added to the cart
        // if it's sub product, do not check products_status
        $product_check_query = tep_db_query("SELECT p.products_id, p.products_status, p.products_parent_id
                                    FROM " . TABLE_PRODUCTS . " p,
                                          " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                    WHERE p.products_id = " . (int)$products_id . "
                                       and pd.products_id = " . (int)$products_id . "
                                       and pd.language_id = " . (int)$languages_id);
        if (tep_db_num_rows($product_check_query) < 1) { // nothing here for us to use
          continue;
        }
        
        $product_check = tep_db_fetch_array($product_check_query);
        if ( $product_check['products_parent_id'] == 0 ) {
          if ( $product_check['products_status'] == 0 ) {
            continue;
          }
        } else {
          $parent_check_query = tep_db_query("SELECT p.products_id, p.products_status
                                              FROM " . TABLE_PRODUCTS . " p,
                                                   " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                              WHERE p.products_id = " . (int)$product_check['products_parent_id'] . "
                                                and p.products_status = 1
                                                and pd.products_id = " . (int)$products_id . "
                                                and pd.language_id = " . (int)$languages_id);
          if (tep_db_num_rows($parent_check_query) < 1) { // nothing here for us to use
            continue;
          }
        }
        
        $this->contents[$products_id_string] = array('qty' => $products['customers_basket_quantity'],
                                                     'products_id' => $product_check['products_id'],
                                                     'parent' => $product_check['products_parent_id']
                                                    );
// attributes
        // the query was changed for tracker issue 997 to provide a order to the attributes
        // $attributes_query = tep_db_query("select products_options_id, products_options_value_id, products_options_value_text from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . tep_db_input($products['products_id']) . "'");
        $attributes_query = tep_db_query("SELECT a.products_options_id, a.products_options_value_id, a.products_options_value_text
                                          FROM " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " a,
                                               " . TABLE_PRODUCTS_OPTIONS_TEXT . " ot
                                          WHERE a.customers_id = " . (int)$_SESSION['customer_id'] . "
                                            AND a.products_id = '" . tep_db_input($products_id_string) . "'
                                            AND ot.products_options_text_id = a.products_options_id
                                            AND ot.language_id = " . (int)$languages_id . "
                                          ORDER BY ot.products_options_name, a.products_options_value_text
                                            ");
        while ($attributes = tep_db_fetch_array($attributes_query)) {
          if ( ($attributes['products_options_value_id'] == 0)  &&  ! is_null($attributes['products_options_value_text']) ) {
            $this->contents[$products_id_string]['attributes'][$attributes['products_options_id']] = unserialize($attributes['products_options_value_text']);
          } else {
            $this->contents[$products_id_string]['attributes'][$attributes['products_options_id']] = $attributes['products_options_value_id'];
          }
        }
      }

      $this->cleanup();
    }

    function reset($reset_database = false) {
      
      $this->contents = array();
      $this->total = 0;
      $this->weight = 0;
      $this->content_type = false;
	  //$this->myfiles = "";

      if (isset($_SESSION['customer_id']) && ($reset_database == true)) {
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
      }

      // unset($this->cartID);  this was changed to better support the new calss session handling
      $this->cartID = '';
    }
		
		
	//Get product files
	function get_product_files($product_id, $option_id, $file_name, $path, $comment) {
		
		$this->myfiles[$product_id][$option_id][$file_name] = array('path' => $path,
                                                     	'comment' => $comment
											  		);	
		
	}
	//for file field in pro info page
	function update_product_files() {
				
		if (isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']) ) { //insert myfile
			
			while (list($option, $data) = each($this->myfiles)) {
				
				while (list($option_id, $fdata) = each($data)) {
				
					while (list($fname, $otherdata) = each($fdata)) {
					
						$fileInfo = pathinfo($fname);
						
						$ext = strtolower($fileInfo['extension']);
						
						$randamNumber=md5(microtime().rand(0,999999));	
						
						$myfiles_dir = 'users_files/';
						
						$path = DIR_FS_CATALOG . DIR_WS_IMAGES . $myfiles_dir . $randamNumber.".".$fileInfo['extension']; 
						
						if($ext=="jpeg" || $ext=="jpg" || $ext=="png" || $ext=="gif" || $ext=="txt" || $ext=="xls" || $ext=="csv" || $ext=="xlsx" || $ext=="pdf" || $ext=="doc" || $ext=="ai" || $ext=="cdr" || $ext=="eps" ) {
						
							if (copy($otherdata['path'], $path)) {  
								unlink($otherdata['path']);
								$sqlInsFiles = "INSERT INTO ".TABLE_MYFILES." set file_name = '".$fname."', 
												comment='".$otherdata['comment']."', 
												file_path='".$path."', 
												customers_id='".$_SESSION['customer_id']."', 
												date_uploaded = now()";		  	
								$rst_files = tep_db_query($sqlInsFiles);			
							} else {
								echo "Error";
							}
						} else {
							unlink($otherdata['path']);
						}
					}	
					
				}
			}
			$this->myfiles = "";
		
		}
		
	}
	

    function add_cart($products_id, $qty = '1', $attributes = '', $notify = true) {
require_once(DIR_WS_CLASSES."upload.php");
/*
print(" <xmp> ");
print_r( $attributes );
print_r( $_POST );
print(" </xmp> ");
*/
      global $languages_id, $purifier;
      
      // add reality check, is this product valid and active?
      // if it's sub product, do not check products_status
      $product_check_query = tep_db_query("SELECT p.products_id, p.products_status, p.products_parent_id
                                           FROM " . TABLE_PRODUCTS . " p,
                                                " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                           WHERE p.products_id = " . (int)$products_id . "
                                             and pd.products_id = " . (int)$products_id . "
                                             and pd.language_id = " . (int)$languages_id);
      if (tep_db_num_rows($product_check_query) < 1) { // nothing here for us to use
        return false;
      }
      $product_check = tep_db_fetch_array($product_check_query);
      
      if ( $product_check['products_parent_id'] == 0 ) {
        if ( $product_check['products_status'] == 0 ) {
          return false;
        }
      } else {
        $parent_check_query = tep_db_query("SELECT p.products_id, p.products_status
                                            FROM " . TABLE_PRODUCTS . " p,
                                                 " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                            WHERE p.products_id = " . (int)$product_check['products_parent_id'] . "
                                              and p.products_status = 1
                                              and pd.products_id = " . (int)$products_id . "
                                              and pd.language_id = " . (int)$languages_id);
        if (tep_db_num_rows($parent_check_query) < 1) { // nothing here for us to use
          return false;
        }
      }
      
      $products_id_string = tep_get_uprid($products_id, $attributes);
      $products_id = tep_get_prid($products_id_string);

      if ($notify == true) {
        $_SESSION['new_products_id_in_cart'] = $products_id;
      }

      if ($this->in_cart($products_id_string)) {
        $this->update_quantity($products_id_string, $qty, $attributes);
      } else {
        $this->contents[$products_id_string] = array('qty' => $qty,
                                                     'products_id' => $product_check['products_id'],
                                                     'parent' => $product_check['products_parent_id']
                                                    );

        if ( isset($_SESSION['customer_id']) ) {
          tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) values ('" . (int)$_SESSION['customer_id'] . "', '" . tep_db_input($products_id_string) . "', '" . $qty . "', '" . date('Ymd') . "')");
        }


/*
        $products_attrib_image = new upload('uplod');

 
        $products_attrib_image->set_destination(PRODUCT_ATTRIB_FILE_UPLOAD_LOCATION);
        $products_attrib_image->set_extensions($allowd_ext);

        if ($products_attrib_image->parse() && $products_attrib_image->save()) {

          $products_attrib_image_name = $products_attrib_image->filename;
        }
*/
/*
print(" <xmp> ");
print_r( $attributes );
print(" </xmp> ");
*/
        if (is_array($attributes)) {
          // the attribute values being input needs to be validated
          $creattributes = new creAttributes();
          $creattributes->load($products_id);
          $valid_options = $creattributes->get_options();
          $valid_values = $creattributes->get_values();
          reset($attributes);
          // the PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES seeting now controls the format
          // of the attributes name field
          if (defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') && PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES == 'True' ) {
            // newer style to support sub products
            while (list($option, $data) = each($attributes)) {
              if ( ! isset($valid_options[$option]) ) continue;  // the option supplied is not valid
              // the newer option types are passed as more complex arrays
              // check for this and handle the text options differently
              while (list($type, $value) = each($data)) {
                if ($type != 't' && $type != 'c') {
                  if ( ! isset($valid_values[$option][$value]) ) continue;  // the value is not valid
                  $this->contents[$products_id_string]['attributes'][$option] = $value;
                  if (tep_session_is_registered('customer_id')) tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id) values ('" . (int)$_SESSION['customer_id'] . "', '" . tep_db_input($products_id_string) . "', '" . (int)$option . "', '" . (int)$value . "')");
                } elseif ($type == 't') {
                  if  ( ! empty($value)) {  // there is not value for a text input, but it must not be empty
                    $clean_value = $purifier->purify($value);
                    $this->contents[$products_id_string]['attributes'][$option][$type] = $clean_value;
                    $attr_array = array();
                    $attr_array['t'] = $clean_value;
                    $attr_value = serialize($attr_array);
                    if (tep_session_is_registered('customer_id')) tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id, products_options_value_text) values ('" . (int)$_SESSION['customer_id'] . "', '" . tep_db_input($products_id_string) . "', '" . (int)$option . "', '0', '" . tep_db_input($attr_value) . "')");
                  }
                } elseif ($type == 'c') {
                  if ( is_array($value) ) {
                    $attr_array = array();
                    while (list($idx, $checked_value) = each($value)) {
                      if ( ! isset($valid_values[$option][$checked_value]) ) continue;  // the value is not valid 
                      $this->contents[$products_id_string]['attributes'][$option]['c'][$idx] = $checked_value;
                      $attr_array['c'][] = $checked_value;
                    }
                    $attr_value = serialize($attr_array);
                    if (tep_session_is_registered('customer_id')) tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id, products_options_value_text) values ('" . (int)$_SESSION['customer_id'] . "', '" . tep_db_input($products_id_string) . "', '" . (int)$option . "', '0', '" . tep_db_input($attr_value) . "')");
                  }
                }
              }
            }
          } else {
            // older style that did not support sub products
            while (list($option, $value) = each($attributes)) {
              if ( ! isset($valid_options[$option]) ) continue;  // the option supplied is not valid
              if ( ! is_array($value) ) {
                if ( ! isset($valid_values[$option][$value]) ) continue;  // the value is not valid
                $this->contents[$products_id_string]['attributes'][$option] = $value;
                if (tep_session_is_registered('customer_id')) tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id) values ('" . (int)$_SESSION['customer_id'] . "', '" . tep_db_input($products_id_string) . "', '" . (int)$option . "', '" . (int)$value . "')");
              } elseif (isset($value['t'])) {
                if ( ! empty($value['t'])) {
                  $clean_value = $purifier->purify($value['t']);
                  $this->contents[$products_id_string]['attributes'][$option]['t'] = $clean_value;
                  $attr_array = array();
                  $attr_array['t'] = $clean_value;
                  $attr_value = serialize($attr_array);
                  if (tep_session_is_registered('customer_id')) tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id, products_options_value_text) values ('" . (int)$_SESSION['customer_id'] . "', '" . tep_db_input($products_id_string) . "', '" . (int)$option . "', '0', '" . tep_db_input($attr_value) . "')");
                }
              } elseif (isset($value['c'])) {
                if ( is_array($value) ) {
                  $attr_array = array();
                  while (list($idx, $checked_value) = each($value['c'])) {
                    if ( ! isset($valid_values[$option][$checked_value]) ) continue;  // the value is not valid 
                    $this->contents[$products_id_string]['attributes'][$option]['c'][$idx] = $checked_value;
                    $attr_array['c'][] = $checked_value;
                  }
                  $attr_value = serialize($attr_array);
                  if (tep_session_is_registered('customer_id')) tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id, products_options_value_text) values ('" . (int)$_SESSION['customer_id'] . "', '" . tep_db_input($products_id_string) . "', '" . (int)$option . "', '0', '" . tep_db_input($attr_value) . "')");
                }
              }
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
      
      $products_id_string = tep_get_uprid($products_id, $attributes);
      $products_id = tep_get_prid($products_id_string);
      
      $this->contents[$products_id_string]['qty'] = $quantity;
      
      if ( isset($_SESSION['customer_id']) ) tep_db_query("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . $quantity . "' where customers_id = '" . (int)$_SESSION['customer_id'] . "' and products_id = '" . tep_db_input($products_id_string) . "'");

      if (is_array($attributes)) {
        reset($attributes);
        while (list($option, $data) = each($attributes)) {
          // the newer option types are passed as more complex arrays
          // check for this and handle the text options differently
          while (list($type, $value) = each($data)) {
            if ($type != 't'  && $type != 'c') {
              $this->contents[$products_id_string]['attributes'][$option] = $value;
              if ( isset($_SESSION['customer_id']) ) tep_db_query("update " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " set products_options_value_id = " . (int)$value . ", products_options_value_text = '' where customers_id = " . (int)$_SESSION['customer_id'] . " and products_id = '" . tep_db_input($products_id_string) . "' and products_options_id = " . (int)$option);
            } elseif ( ! empty($value)) {
              $this->contents[$products_id_string]['attributes'][$option][$type] = $value;
              $attr_value = serialize($data);
              if ( isset($_SESSION['customer_id']) ) tep_db_query("update " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " set products_options_value_id = 0, products_options_value_text = '" . tep_db_input($attr_value) . "' where customers_id = " . (int)$_SESSION['customer_id'] . " and products_id = '" . tep_db_input($products_id_string) . "' and products_options_id = " . (int)$option);
            }
          }
        }
      }
    }

    function cleanup() {
      reset($this->contents);
      while (list($key,) = each($this->contents)) {
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


	// Added new function for shopping cart total calculation - Aug 26, 2010
	function shopping_cart_calculation() {	
      global $pf, $languages_id;
      $this->total_virtual = 0; // ICW Gift Voucher System
      $this->total = 0;
      $this->weight = 0;
      if (!is_array($this->contents)) return 0;

      reset($this->contents);
      $cart_contents = $this->contents;
      while (list($products_id_string, ) = each($this->contents)) {
        $qty = $this->contents[$products_id_string]['qty'];
        $products_id = (int)$this->contents[$products_id_string]['products_id'];
        
        $qty_new = 0;
        reset($cart_contents);
        foreach ($cart_contents as $key => $value) {
          if ($cart_contents[$key]['products_id'] == $products_id) {
            $qty_new += $cart_contents[$key]['qty'];
          }
        }
        
        // Eversun mod for sppc and qty price breaks
        // global variable (session) $sppc_customer_group_id -> class variable cg_id
        if (isset($_SESSION['customer_id']) && $_SESSION['customer_id']=="") {
          $customer_group_id = "G";
        } else {
          $getcustomer_GroupID_query = tep_db_query("select customers_group_id   from " . TABLE_CUSTOMERS . " where  customers_id = '" . (isset($_SESSION['customer_id']) ? (int)$_SESSION['customer_id'] : 0) . "'");
          $getcustomer_GroupID = tep_db_fetch_array($getcustomer_GroupID_query);
          $customer_group_id = $getcustomer_GroupID['customers_group_id'];
        }
        $this->cg_id = $customer_group_id; #$sppc_customer_group_id;

        $product = $pf->loadProduct((int)$this->contents[$products_id_string]['products_id'], $languages_id);
        $no_count = 1;
        $gv_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$this->contents[$products_id_string]['products_id'] . "'");
        $gv_result = tep_db_fetch_array($gv_query);
        if (ereg('^GIFT', $gv_result['products_model'])) {
          $no_count = 0;
        }
        
        $products_tax = tep_get_tax_rate($product['products_tax_class_id']);

        // Eversun mod end for sppc and qty price breaks
        if ($pf->hasCustomerGroupPrice() == 'true') {
          $products_price = $pf->hasCustomerGroupcomputePrice($qty_new);
        } else {
          $products_price = $pf->computePrice($qty_new);  // get priced based on the total in the cart
        }
        // Eversun mod for sppc and qty price breaks
        
        $products_weight = $product['products_weight'];

        //Eversun mod end for sppc and qty price breaks

        //$this->total_virtual += tep_add_tax($products_price, $products_tax) * $qty * $no_count;// ICW CREDIT CLASS;
		$this->total_virtual += $products_price * $qty * $no_count;// ICW CREDIT CLASS;
		
        $this->weight_virtual += ($qty * $products_weight) * $no_count;// ICW CREDIT CLASS;
        //$this->total += tep_add_tax($products_price, $products_tax) * $qty;
		$this->total += $products_price * $qty;
        $this->weight += ($qty * $products_weight);

// attributes price
        if (isset($this->contents[$products_id_string]['attributes'])) {
          $attribute_product_id = (int)$this->contents[$products_id_string]['products_id'];
          if ((int)$this->contents[$products_id_string]['parent'] != 0) {
            $attribute_product_id = (int)$this->contents[$products_id_string]['parent'];
          }
          reset($this->contents[$products_id_string]['attributes']);
          while (list($option, $value) = each($this->contents[$products_id_string]['attributes'])) {
            if ( ! is_array($value) ) {
              $attribute_price_query = tep_db_query("select options_values_price as price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = " . (int)$attribute_product_id . " and options_id = " . (int)$option . " and options_values_id = " . (int)$value);
              $attribute_price = tep_db_fetch_array($attribute_price_query);
              if ($attribute_price['price_prefix'] == '+') {
                //$this->total += $qty * tep_add_tax($attribute_price['price'], $products_tax);
				$this->total += $qty * $attribute_price['price'];
              } else {
                //$this->total -= $qty * tep_add_tax($attribute_price['price'], $products_tax);
				$this->total -= $qty * $attribute_price['price'];

              }
            } elseif ( isset($value['t']) ) {
              $attribute_price_query = tep_db_query("select options_values_price as price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = " . (int)$attribute_product_id . " and options_id = " . (int)$option . " and options_values_id = 0");
              $attribute_price = tep_db_fetch_array($attribute_price_query);
              if ($attribute_price['price_prefix'] == '+') {
                //$this->total += $qty * tep_add_tax($attribute_price['price'], $products_tax);
				$this->total += $qty * $attribute_price['price'];
              } else {
                //$this->total -= $qty * tep_add_tax($attribute_price['price'], $products_tax);
				$this->total -= $qty * $attribute_price['price'];
              }
            } elseif ( isset($value['c']) ) {
              foreach ( $value['c'] as $v ) {
                $attribute_price_query = tep_db_query("select options_values_price as price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = " . (int)$attribute_product_id . " and options_id = " . (int)$option . " and options_values_id = " . (int)$v);
                $attribute_price = tep_db_fetch_array($attribute_price_query);
                if ($attribute_price['price_prefix'] == '+') {
                  //$this->total += $qty * tep_add_tax($attribute_price['price'], $products_tax);
				  $this->total += $qty * $attribute_price['price'];
                } else {
                  //$this->total -= $qty * tep_add_tax($attribute_price['price'], $products_tax);
				  $this->total -= $qty * $attribute_price['price'];
                }
              }
            }
          }
        }
      }
    
	}
    
	
	
    function calculate() {
      global $pf, $languages_id;
      $this->total_virtual = 0; // ICW Gift Voucher System
      $this->total = 0;
      $this->weight = 0;
      if (!is_array($this->contents)) return 0;

      reset($this->contents);
      $cart_contents = $this->contents;
      while (list($products_id_string, ) = each($this->contents)) {
        $qty = $this->contents[$products_id_string]['qty'];
        $products_id = (int)$this->contents[$products_id_string]['products_id'];
        
        $qty_new = 0;
        reset($cart_contents);
        foreach ($cart_contents as $key => $value) {
          if ($cart_contents[$key]['products_id'] == $products_id) {
            $qty_new += $cart_contents[$key]['qty'];
          }
        }
        
        // Eversun mod for sppc and qty price breaks
        // global variable (session) $sppc_customer_group_id -> class variable cg_id
        if (isset($_SESSION['customer_id']) && $_SESSION['customer_id']=="") {
          $customer_group_id = "G";
        } else {
          $getcustomer_GroupID_query = tep_db_query("select customers_group_id   from " . TABLE_CUSTOMERS . " where  customers_id = '" . (isset($_SESSION['customer_id']) ? (int)$_SESSION['customer_id'] : 0) . "'");
          $getcustomer_GroupID = tep_db_fetch_array($getcustomer_GroupID_query);
          $customer_group_id = $getcustomer_GroupID['customers_group_id'];
        }
        $this->cg_id = $customer_group_id; #$sppc_customer_group_id;

        $product = $pf->loadProduct((int)$this->contents[$products_id_string]['products_id'], $languages_id);
        $no_count = 1;
        $gv_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$this->contents[$products_id_string]['products_id'] . "'");
        $gv_result = tep_db_fetch_array($gv_query);
        if (ereg('^GIFT', $gv_result['products_model'])) {
          $no_count = 0;
        }
        
        $products_tax = tep_get_tax_rate($product['products_tax_class_id']);

        // Eversun mod end for sppc and qty price breaks
        if ($pf->hasCustomerGroupPrice() == 'true') {
          $products_price = $pf->hasCustomerGroupcomputePrice($qty_new);
        } else {
          $products_price = $pf->computePrice($qty_new);  // get priced based on the total in the cart
        }
        // Eversun mod for sppc and qty price breaks
        
        $products_weight = $product['products_weight'];

        //Eversun mod end for sppc and qty price breaks

        $this->total_virtual += tep_add_tax($products_price, $products_tax) * $qty * $no_count;// ICW CREDIT CLASS;
        $this->weight_virtual += ($qty * $products_weight) * $no_count;// ICW CREDIT CLASS;
        $this->total += tep_add_tax($products_price, $products_tax) * $qty;
        $this->weight += ($qty * $products_weight);

// attributes price
        if (isset($this->contents[$products_id_string]['attributes'])) {
          $attribute_product_id = (int)$this->contents[$products_id_string]['products_id'];
          if ((int)$this->contents[$products_id_string]['parent'] != 0) {
            $attribute_product_id = (int)$this->contents[$products_id_string]['parent'];
          }
          reset($this->contents[$products_id_string]['attributes']);
          while (list($option, $value) = each($this->contents[$products_id_string]['attributes'])) {
            if ( ! is_array($value) ) {
              $attribute_price_query = tep_db_query("select options_values_price as price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = " . (int)$attribute_product_id . " and options_id = " . (int)$option . " and options_values_id = " . (int)$value);
              $attribute_price = tep_db_fetch_array($attribute_price_query);
              if ($attribute_price['price_prefix'] == '+') {
                $this->total += $qty * tep_add_tax($attribute_price['price'], $products_tax);
              } else {
                $this->total -= $qty * tep_add_tax($attribute_price['price'], $products_tax);
              }
            } elseif ( isset($value['t']) ) {
              $attribute_price_query = tep_db_query("select options_values_price as price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = " . (int)$attribute_product_id . " and options_id = " . (int)$option . " and options_values_id = 0");
              $attribute_price = tep_db_fetch_array($attribute_price_query);
              if ($attribute_price['price_prefix'] == '+') {
                $this->total += $qty * tep_add_tax($attribute_price['price'], $products_tax);
              } else {
                $this->total -= $qty * tep_add_tax($attribute_price['price'], $products_tax);
              }
            } elseif ( isset($value['c']) ) {
              foreach ( $value['c'] as $v ) {
                $attribute_price_query = tep_db_query("select options_values_price as price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = " . (int)$attribute_product_id . " and options_id = " . (int)$option . " and options_values_id = " . (int)$v);
                $attribute_price = tep_db_fetch_array($attribute_price_query);
                if ($attribute_price['price_prefix'] == '+') {
                  $this->total += $qty * tep_add_tax($attribute_price['price'], $products_tax);
                } else {
                  $this->total -= $qty * tep_add_tax($attribute_price['price'], $products_tax);
                }
              }
            }
          }
        }
      }
    }

    function vendor_shipping() {

      if (!is_array($this->contents)) return 0;  //Cart is empty

      $this->vendor_shipping = array();  //Initialize the output array
      reset($this->contents);            //  and reset the input array
      foreach ($this->contents as $products_id => $value) {  //$value is never used
        $quantity = $this->contents[$products_id]['qty'];

        $products_query = tep_db_query("select products_id,
                                               products_price,
                                               products_tax_class_id,
                                               products_weight,
                                               vendors_id
                                        from " . TABLE_PRODUCTS . "
                                        where products_id = '" . (int)$products_id . "'"
                                      );
        if ($products = tep_db_fetch_array($products_query)) {
          $products_price = $products['products_price'];
          $products_weight = $products['products_weight'];
          $vendors_id = ($products['vendors_id'] <= 0) ? 1 : $products['vendors_id'];
          $products_tax = tep_get_tax_rate($products['products_tax_class_id']);

          //Find special prices (if any)
          $specials_query = tep_db_query("select specials_new_products_price
                                          from " . TABLE_SPECIALS . "
                                          where products_id = '" . (int)$products_id . "'
                                            and status = '1'"
                                        );
          if (tep_db_num_rows ($specials_query)) {
            $specials = tep_db_fetch_array($specials_query);
            $products_price = $specials['specials_new_products_price'];
          }

          //Add values to the output array
          $this->vendor_shipping[$vendors_id]['weight'] += ($quantity * $products_weight);
          $this->vendor_shipping[$vendors_id]['cost'] += tep_add_tax($products_price, $products_tax) * $quantity;
          $this->vendor_shipping[$vendors_id]['qty'] += $quantity;
          $this->vendor_shipping[$vendors_id]['products_id'][] = $products_id; //There can be more than one product
        }

        // Add/subtract attributes prices (if any)
        if (isset($this->contents[$products_id]['attributes'])) {
          reset($this->contents[$products_id]['attributes']);
          foreach ($this->contents[$products_id]['attributes'] as $option => $value) {
            $attribute_price_query = tep_db_query("select options_values_price,
                                                          price_prefix
                                                   from " . TABLE_PRODUCTS_ATTRIBUTES . "
                                                   where products_id = '" . (int)$products_id . "'
                                                     and options_id = '" . (int)$option . "'
                                                     and options_values_id = '" . (int)$value . "'"
                                                 );
            $attribute_price = tep_db_fetch_array($attribute_price_query);
            if ($attribute_price['price_prefix'] == '+') {
              $this->vendor_shipping[$vendors_id]['cost'] += $quantity * tep_add_tax($attribute_price['options_values_price'], $products_tax);
            } else {
              $this->vendor_shipping[$vendors_id]['cost'] -= $quantity * tep_add_tax($attribute_price['options_values_price'], $products_tax);
          }
        }
      }
    }

      return $this->vendor_shipping;
    }

    function attributes_price($products_id_string) {
      $attributes_price = 0;
      
      if (isset($this->contents[$products_id_string]['attributes'])) {
        $attribute_product_id = (int)$this->contents[$products_id_string]['products_id'];
        if ((int)$this->contents[$products_id_string]['parent'] != 0) {
          $attribute_product_id = (int)$this->contents[$products_id_string]['parent'];
        }
        
        reset($this->contents[$products_id_string]['attributes']);
        while (list($option, $value) = each($this->contents[$products_id_string]['attributes'])) {
          if ( ! is_array($value) ) {
            $attribute_price_query = tep_db_query("select options_values_price as price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = " . (int)$attribute_product_id . " and options_id = " . (int)$option . " and options_values_id = " . (int)$value );
            $attribute_price = tep_db_fetch_array($attribute_price_query);
            if ($attribute_price['price_prefix'] == '+') {
              $attributes_price += $attribute_price['price'];
            } else {
              $attributes_price -= $attribute_price['price'];
            }
          } elseif ( isset($value['t']) ) {
            $attribute_price_query = tep_db_query("select options_values_price as price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = " . (int)$attribute_product_id . " and options_id = " . (int)$option . " and options_values_id = 0");
            $attribute_price = tep_db_fetch_array($attribute_price_query);
            if ($attribute_price['price_prefix'] == '+') {
              $attributes_price += $attribute_price['price'];
            } else {
              $attributes_price -= $attribute_price['price'];
            }
          } elseif ( isset($value['c']) ) {
            foreach ( $value['c'] as $v ) {
              $attribute_price_query = tep_db_query("select options_values_price as price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = " . (int)$attribute_product_id . " and options_id = " . (int)$option . " and options_values_id = " . (int)$v);
              $attribute_price = tep_db_fetch_array($attribute_price_query);
              if ($attribute_price['price_prefix'] == '+') {
                $attributes_price += $attribute_price['price'];
              } else {
                $attributes_price -= $attribute_price['price'];
              }
            }
          }
        }
      }
      
      return $attributes_price;
    }

    function get_products() {
      global $languages_id;
      
      if (!is_array($this->contents)) return false;
      
      $pf = new PriceFormatter;
      
      // Eversun mod for sppc and qty price breaks
      if ( ! isset($_SESSION['sppc_customer_group_id']) ) {
        $this->cg_id = 'G';
      } else {
        $this->cg_id = $_SESSION['sppc_customer_group_id'];
      }
      
      $products_array = array();
      reset($this->contents);
      $cart_contents = $this->contents;
      while (list($products_id_string, ) = each($this->contents)) {
        $products_id = (int)$this->contents[$products_id_string]['products_id'];
        
        $qty_new = 0;
        reset($cart_contents);
        foreach ($cart_contents as $key => $value) {
          if ($cart_contents[$key]['products_id'] == $products_id) {
            $qty_new += $cart_contents[$key]['qty'];
          }
        }
        
        if ($products = $pf->loadProduct($products_id, $languages_id)) {
          if ($pf->hasCustomerGroupPrice() == 'true') {
            $products_price = $pf->hasCustomerGroupcomputePrice($qty_new);
          } else {
            $products_price = $pf->computePrice($qty_new);
          }
          //Eversun mod end for sppc and qty price breaks
          $products_array[] = array('id' => $this->contents[$products_id_string]['products_id'],
                                    'id_string' => $products_id_string,
                                    'name' => (isset($products['products_name']) ? $products['products_name'] : '') ,
                                    'model' => (isset($products['products_model']) ? $products['products_model'] : ''),
                                    'image' => (isset($products['products_image']) ? $products['products_image'] : ''),
                                    'price' => (isset($products_price) ? $products_price : 0),
                                                  'product_original_price' => (isset($products['products_price']) ? $products['products_price'] : 0),
                                    'product_original_final_price' => ($products['products_price'] + $this->attributes_price($products_id_string)),
                                    'quantity' => $this->contents[$products_id_string]['qty'],
                                    'weight' => (isset($products['products_weight']) ? $products['products_weight'] : 0),
                                    // Dimensional UPS begin
                                    'length' => (isset($products['products_length']) ? $products['products_length'] : 0),
                                    'width' => (isset($products['products_width']) ? $products['products_width'] : 0),
                                    'height' => (isset($products['products_height']) ? $products['products_height'] : 0),
                                    'ready_to_ship' => (isset($products['products_ready_to_ship']) ? $products['products_ready_to_ship'] : 0),
                                    // Dimensional UPS end
                                    'final_price' => ($products_price + $this->attributes_price($products_id_string)),
                                    'tax_class_id' => (isset($products['products_tax_class_id']) ? $products['products_tax_class_id'] : 0),
                                    //MVS start
                                    'vendors_id' => $products['vendors_id'],
                                    'vendors_name' => $products['vendors_name'],
                                    //MVS end
                                    'attributes' => (isset($this->contents[$products_id_string]['attributes']) ? $this->contents[$products_id_string]['attributes'] : ''));
        }
      }

      return $products_array;
    }

	//Added new function for show shopping cart total calculation - Aug 26, 2010	
	function show_shopping_cart_total() {
      $this->shopping_cart_calculation();

      return $this->total;
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
        while (list($products_id_string, ) = each($this->contents)) {
          if (isset($this->contents[$products_id_string]['attributes'])) {
            $attribute_product_id = (int)$this->contents[$products_id_string]['products_id'];
            if ((int)$this->contents[$products_id_string]['parent'] != 0) {
              $attribute_product_id = (int)$this->contents[$products_id_string]['parent'];
            }
            reset($this->contents[$products_id_string]['attributes']);
            while (list(, $value) = each($this->contents[$products_id_string]['attributes'])) {
              
              $virtual_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad where pa.products_id = '" . (int)$attribute_product_id . "' and pa.options_values_id = '" . (int)$value . "' and pa.products_attributes_id = pad.products_attributes_id");
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
            while (list($products_id_string, ) = each($this->contents)) {
              $products_id = (int)$this->contents[$products_id_string]['products_id'];
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
        while (list($products_id_string, ) = each($this->contents)) {
          $products_id = (int)$this->contents[$products_id_string]['products_id'];
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