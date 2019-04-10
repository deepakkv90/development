<?php
/*
  $Id: creAttributes.php,v 1.0 2009/03/31 00:36:41 ccwjr Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded

  Released under the GNU General Public License
*/

class creAttributes {
  private $products_id = '';  // this is the product id the class was called to handle
  private $load_id = '';      // if the attributes loaded are for a different id, then the products_id is a sub product
  private $tax_class = '';    // the products tax class needed for option pricing
  private $options = array(); // hold the valid options and related information
  private $values = array();  // hold the option/value combination that are valid
  private $HTML_tags = array();  // hold the option/value as HTML input tags - not cached in the session

  // class constructor
  public function __construct() {
    if ( ! isset($_SESSION['creAttributes_data']) ) {
      $_SESSION['creAttributes_data'] = array('products_id' => '',
                                              'tax_class' => '',
                                              'load_id' => '',
                                              'options' => array(),
                                              'values' => array()
                                             );
    }
    $this->products_id =& $_SESSION['creAttributes_data']['products_id'];
    $this->tax_class =& $_SESSION['creAttributes_data']['tax_class'];
    $this->load_id =& $_SESSION['creAttributes_data']['load_id'];
    $this->options =& $_SESSION['creAttributes_data']['options'];
    $this->values =& $_SESSION['creAttributes_data']['values'];
  }
  
  public function load($id = '') {
    global $languages_id;
    
    if ( ! is_numeric($id) ) return false;
    
    if (USE_CACHE == 'true' && $id == $this->products_id) return true;
      
    // check to see if this is a valid product id
    $sql = "SELECT products_id, products_tax_class_id
            FROM " . TABLE_PRODUCTS . "
            WHERE products_id = " . $id;
    $product_check_query = tep_db_query($sql);
    if (tep_db_num_rows($product_check_query) < 1) {
      return false;  // the ID is not valid
    }
    
    // store the tax class for later use
    $product_info = tep_db_fetch_array($product_check_query);
    $this->tax_class = $product_info['products_tax_class_id'];
    
    // check to see if this is a subproduct
    if (function_exists('tep_product_sub_check')) {
      $parent_id = tep_product_sub_check($id);
      if ($parent_id != 0) {
        $product_to_load = $parent_id;
      } else {
        $product_to_load = $id;
      }
    } else {
      $product_to_load = $id;
    }
    
    // store the id being processed
    if (USE_CACHE == 'true' && $product_to_load == $this->load_id) return true; // the information is already loaded
    
    // the ID is valid, so reset our values
    $this->reset();
    $this->products_id = $id;
    $this->load_id = $product_to_load;
    
    // read in the valid option/values
    $sql = "SELECT pa.options_id, pa.options_values_id, pa.options_values_price, pa.price_prefix, 
                   po.options_type, po.options_length, pot.products_options_name, pot.products_options_instruct 
            FROM " . TABLE_PRODUCTS_ATTRIBUTES  . " AS pa,
                 " . TABLE_PRODUCTS_OPTIONS  . " AS po,
                 " . TABLE_PRODUCTS_OPTIONS_TEXT  . " AS pot
            WHERE pa.products_id = " . (int)$this->load_id . "
              and pa.options_id = po.products_options_id
              and po.products_options_id = pot.products_options_text_id
              and pot.language_id = " . (int)$languages_id . "
            ORDER BY pa.products_options_sort_order, po.products_options_sort_order";
    $products_options_query = tep_db_query($sql);
    while ($po = tep_db_fetch_array($products_options_query)) {
      // store the option and its information
      if ( ! isset($this->options[$po['options_id']])) {
        $this->options[$po['options_id']] = array('name' => $po['products_options_name'],
                                                  'type' => $po['options_type'],
                                                  'length' => $po['options_length'],
                                                  'instructions' => $po['products_options_instruct'],
                                                 );
      }
      // get the values name if the option is not a text input type
      if ( $po['options_type'] != 1  && $po['options_type'] != 4 && $po['options_type'] != 5 ) {
        $sql = "SELECT products_options_values_name
                FROM " . TABLE_PRODUCTS_OPTIONS_VALUES . "
                WHERE products_options_values_id = ". $po['options_values_id'] . "
                  and language_id = " . (int)$languages_id;
        $options_values_query = tep_db_query($sql);
        $ov = tep_db_fetch_array($options_values_query);
        $this->values[$po['options_id']][$po['options_values_id']] =  array('name' => stripslashes($ov['products_options_values_name']),
                                                                            'price' => $po['options_values_price'],
                                                                            'prefix' => $po['price_prefix'],
                                                                           );
      } else {
        $this->values[$po['options_id']]['t'] =  array('name' => '',
                                                       'price' => $po['options_values_price'],
                                                       'prefix' => $po['price_prefix'],
                                                      );
      }
    }
    return true;
  }
  
  public function get_options() {
    // return the array of options for this product
    return $this->options;
  }
  
  public function get_values() {
    // return the possible values for the options
    return $this->values;
  }
  
  public function get_HTML() {
    global $currencies;
    // the options/values are returned as HTML input tags
    
    // if this a call for a subproduct, check to see if anything is to be generated
    if ($this->products_id != $this->load_id) {
      if ( ! defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') || (PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES != 'True') ){
        return array();  // do not generate anything for the subproduct
      }
    }
    
    // the tax rate will be needed, so get it once
    $tax_rate = tep_get_tax_rate($this->tax_class);
    
    // the follow logic has been adjusted to use the PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES
    // configuration value as a switch between the older 6.2.14 naming format and the newer
    // 6.3.3 naming format that subports sub products
    foreach ($this->options as $oID => $op_data) {
      switch ($op_data['type']) {
        
		case 5: //for file upload Nov 30, 2010
          
          if (defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') && PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES == 'True' ) {
            $name = 'id[' . $this->products_id . '][' . $oID . '][t]';
          } else {
            $name = 'id[' . $oID . '][t]';
          }
          $tmp_html = '<input type="file" name="' . $name . '"' . '>';
          
          $label = $op_data['name'] . ':' . ($op_data['instructions'] != '' ? '<br><span class="smallText">' . $op_data['instructions'] . '</span>' : '' );
          $label .= $attribute_price != 0 ? '<br><span class="smallText">' . $this->values[$oID]['t']['prefix'] . ' ' . $attribute_price . '</span>' : '';
          
          $this->HTML_tags[] = array('label' => $label, 'HTML' => $tmp_html);
          break;
		  
		case 1:
          $maxlength = ( $op_data['length'] > 0 ? ' maxlength="' . $op_data['length'] . '"' : '' );
          $attribute_price = $currencies->display_price($this->values[$oID]['t']['price'], $tax_rate);
          
          if (defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') && PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES == 'True' ) {
            $name = 'id[' . $this->products_id . '][' . $oID . '][t]';
          } else {
            $name = 'id[' . $oID . '][t]';
          }
          $tmp_html = '<input type="text" name="' . $name . '"' . $maxlength . '>';
          
          $label = $op_data['name'] . ':' . ($op_data['instructions'] != '' ? '<br><span class="smallText">' . $op_data['instructions'] . '</span>' : '' );
          $label .= $attribute_price != 0 ? '<br><span class="smallText">' . $this->values[$oID]['t']['prefix'] . ' ' . $attribute_price . '</span>' : '';
          
          $this->HTML_tags[] = array('label' => $label, 'HTML' => $tmp_html);
          break;
        
        case 4:
          $text_area_array = explode(';',$op_data['length']);
          $cols = $text_area_array[0];
          if ( $cols == '' ) $cols = '100%';
          if (isset($text_area_array[1])) {
            $rows = $text_area_array[1];
          } else {
            $rows = '';
          }
          $attribute_price = $currencies->display_price($this->values[$oID]['t']['price'], $tax_rate);

          if (defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') && PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES == 'True' ) {
            $name = 'id[' . $this->products_id . '][' . $oID . '][t]';
          } else {
            $name = 'id[' . $oID . '][t]';
          }
          $tmp_html = '<textarea name="' . $name . '" wrap="virtual" style="width:auto;"></textarea>';
          
          $label = $op_data['name'] . ':' . ($op_data['instructions'] != '' ? '<br><span class="smallText">' . $op_data['instructions'] . '</span>' : '' );
          $label .= $attribute_price != 0 ? '<br><span class="smallText">' . $this->values[$oID]['t']['prefix'] . ' ' . $attribute_price . '</span>' : '';
          
          $this->HTML_tags[] = array('label' => $label, 'HTML' => $tmp_html);
          break;

        case 2:
          $tmp_html = '';
          foreach ( $this->values[$oID] as $vID => $ov_data ) {
            if ( (float)$ov_data['price'] == 0 ) {
              $price = '&nbsp;';
            } else {
              $price = '(&nbsp;' . $ov_data['prefix'] . '&nbsp;' . $currencies->display_price($ov_data['price'], $tax_rate) . '&nbsp;)';
            }
            
            if (defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') && PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES == 'True' ) {
              $name = 'id[' . $this->products_id . '][' . $oID . '][r]';
            } else {
              $name = 'id[' . $oID . ']';
            }
            $tmp_html .= '<input type="radio" name="' . $name . '" value="' . $vID . '">' . $ov_data['name'] . '&nbsp;' . $price . '<br>';
          } // End of foreach option value
          
          $label = $op_data['name'] . ':' . ($op_data['instructions'] != '' ? '<br><span class="smallText">' . $op_data['instructions'] . '</span>' : '' );
          
          $this->HTML_tags[] = array('label' => $label, 'HTML' => $tmp_html);
          break;

        case 3:
          $tmp_html = '';
          $i = 0;
          foreach ( $this->values[$oID] as $vID => $ov_data ) {
            if ( (float)$ov_data['price'] == 0 ) {
              $price = '&nbsp;';
            } else {
              $price = '(&nbsp;'.$ov_data['prefix'] . '&nbsp;' . $currencies->display_price($ov_data['price'], $tax_rate).'&nbsp;)';
            }
            
            if (defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') && PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES == 'True' ) {
              $name = 'id[' . $this->products_id . '][' . $oID . '][c][' . $i . ']';
            } else {
              $name = 'id[' . $oID . '][c][' . $i . ']';
            }
            $tmp_html .= '<input type="checkbox" name="' . $name . '" value="' . $vID . '">' . $ov_data['name'] . '&nbsp;' . $price . '<br>';
            $i++;
          }  // End of foreach option value
          
          $label = $op_data['name'] . ':' . ($op_data['instructions'] != '' ? '<br><span class="smallText">' . $op_data['instructions'] . '</span>' : '' );
          
          $this->HTML_tags[] = array('label' => $label, 'HTML' => $tmp_html);
          break;

        case 0:
          if (defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') && PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES == 'True' ) {
            $name = 'id[' . $this->products_id . '][' . $oID . '][s]';
          } else {
            $name = 'id[' . $oID . ']';
          }
          $tmp_html = '<select name="' . $name . '">';
          foreach ( $this->values[$oID] as $vID => $ov_data ) {
            if ( (float)$ov_data['price'] == 0 ) {
              $price = '&nbsp;';
            } else {
              $price = '(&nbsp; '.$ov_data['prefix'] . '&nbsp;' . $currencies->display_price($ov_data['price'], $tax_rate).'&nbsp;)';
            }
            $tmp_html .= '<option value="' . $vID . '">' . $ov_data['name'] . '&nbsp;' . $price .'</option>';
          } // End of foreach option value
          $tmp_html .= '</select>';
          
          $label = $op_data['name'] . ':' . ($op_data['instructions'] != '' ? '<br><span class="smallText">' . $op_data['instructions'] . '</span>' : '' );
          
          $this->HTML_tags[] = array('label' => $label, 'HTML' => $tmp_html);
          break;
      }  //end of switch
    } //end of foreach options
    
    return $this->HTML_tags;
  }
  
  private function reset() {
    $this->products_id = '';
    $this->load_id = '';
    $this->options = array();
    $this->values = array();
    $this->HTML_tags = array();
  }

}