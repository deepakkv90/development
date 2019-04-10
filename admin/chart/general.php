<?php
/*
  $Id: general.php,v 1.1.1.1 2004/03/04 23:39:53 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Chain Reaction Works, Inc
  Copyright &copy; 2005 - 2006 Chain Reaction Works, Inc.

  Last Modified by $Author$
  Last Modifed on : $Date$
  Latest Revision : $Revision: 3455 $

  Released under the GNU General Public License
*/

// This function is used to a JIT load of classes
function creClassLoader($className) {
  if (file_exists(DIR_FS_CLASSES . $className . '.php')) {
    include DIR_FS_CLASSES . $className . '.php';
  }
}
spl_autoload_register('creClassLoader');  // register it

// this function will record all PHP messages to prevent
// potential exposure of seecure details
function _exception_handler($severity, $message, $filepath, $line) {
  // We don't bother with "strict" notices since they will fill up
  // the log file with information that isn't normally very
  // helpful.  For example, if you are running PHP 5 and you
  // use version 4 style class functions (without prefixes
  // like "public", "private", etc.) you'll get notices telling
  // you that these have been deprecated.
  if ($severity == E_STRICT) return;
  
  $msg = '['.date("D M j G:i:s Y").'] [IP '.$_SERVER['REMOTE_ADDR'].'] Severity: '.$severity.'  --> '.$message. ' '.$filepath.' '.$line;
  
  $fp = fopen(ERROR_MESSAGE_LOG, 'a');
  flock($fp, LOCK_EX);    
  fwrite($fp, $msg . "\r\n");
  fwrite($fp, "\r\n");
  flock($fp, LOCK_UN);
  fclose($fp);
}

// this function is used to log as part of the PHP error log
// any additional messages that should not be presented on the 
// web page, but are needed for debugging.
function _error_handler($message) {
  // capture the track back report
  // the actual passed message is ignored since
  // it will be included as part of the track back
  ob_start();
  debug_print_backtrace();
  $traceback = ob_get_clean();
  
  $fp = @fopen(ERROR_MESSAGE_LOG, 'a');
  flock($fp, LOCK_EX);    
  fwrite($fp, $traceback . "\r\n");
  fwrite($fp, "\r\n");
  flock($fp, LOCK_UN);
  fclose($fp);
}

// Stop from parsing any further PHP code
  function tep_exit() {
   tep_session_close();
   exit();
  }


// Redirect to another page or site
  function tep_redirect($url) {
    if ( (strstr($url, "\n") != false) || (strstr($url, "\r") != false) ) { 
      tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
    }

    if ( (ENABLE_SSL == 'true') && (getenv('HTTPS') == 'on') ) { // We are loading an SSL page
      if (substr($url, 0, strlen(HTTP_SERVER . DIR_WS_HTTP_CATALOG)) == HTTP_SERVER . DIR_WS_HTTP_CATALOG) { // NONSSL url
        $url = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG . substr($url, strlen(HTTP_SERVER . DIR_WS_HTTP_CATALOG)); // Change it to SSL
      }
    }
    $url =  str_replace("&amp;", "&", $url);
    header('Location: ' . $url);

   tep_exit();
  }

//Admin begin
////
//Check login and file access
function tep_admin_check_login() {
  global $PHP_SELF ,$navigation;
  if ( ! isset($_SESSION['login_id']) && basename($PHP_SELF) != FILENAME_LOGOFF ) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  } else {
    // if they are in group 1, let them do whatever they need to do
    if ( $_SESSION['login_groups_id'] != 1 ) {
      $filename = basename( $PHP_SELF );
      if ($filename != FILENAME_DEFAULT && $filename != FILENAME_FORBIDEN && $filename != FILENAME_LOGOFF && $filename != FILENAME_ADMIN_ACCOUNT && $filename != FILENAME_POPUP_IMAGE && $filename != 'packingslip.php' && $filename != 'invoice.php') {
        $db_file_query = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $_SESSION['login_groups_id'] . "', admin_groups_id) and admin_files_name = '" . $filename . "'");
        if (!tep_db_num_rows($db_file_query)) {
          tep_redirect(tep_href_link(FILENAME_FORBIDEN));
        }
      }
    }
  }
}

////
//Return 'true' or 'false' value to display boxes and files in index.php and column_left.php
function tep_admin_check_boxes($filename, $boxes='') {
  $is_boxes = 1;
  if ($boxes == 'sub_boxes') {
    $is_boxes = 0;
  }
  // by pass the check for top level admins
  if ( $_SESSION['login_groups_id'] != 1 ) {
    $dbquery = tep_db_query("select admin_files_id from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $_SESSION['login_groups_id'] . "', admin_groups_id) and admin_files_is_boxes = '" . $is_boxes . "' and admin_files_name = '" . $filename . "'");

    $return_value = false;
    if (tep_db_num_rows($dbquery)) {
      $return_value = true;
    }
  } else {
    $return_value = true;
  }
  return $return_value;
}

////
//Return files stored in box that can be accessed by user
function tep_admin_files_boxes ($filename = '', $sub_box_name = '', $connection = 'NONSSL', $parameters = '', $space = '') {
  global $request_type;
  $sub_boxes = '';

if ($filename != '' ){
  // by pass the check for top level admins
    if ( $_SESSION['login_groups_id'] != 1 ) {
      $dbquery = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $_SESSION['login_groups_id'] . "', admin_groups_id) and admin_files_is_boxes = '0' and admin_files_name = '" . $filename . "'");
      if (tep_db_num_rows($dbquery)) {
        $sub_boxes = '<a href="' . tep_href_link($filename, $parameters, $connection) . '" class="menuBoxContentLink"><nobr>' . $sub_box_name . '</nobr></a><br>';
      } else if ( (tep_db_num_rows($dbquery)) || ($connection == '') ) {
        $sub_boxes = '<a href="' . tep_href_link($filename, $parameters, $connection) . '" class="menuBoxContentLink"><nobr>' . $sub_box_name . '</nobr></a><br>';
      }
    } else {
      $sub_boxes = '<a href="' . tep_href_link($filename, $parameters, $connection) . '" class="menuBoxContentLink"><nobr>' . $sub_box_name . '</nobr></a><br>';
    }
  } else  if ($filename == ''){
    $sub_boxes = '<div class="menuBoxSubhead"><nobr>' . $sub_box_name . '</nobr></div>';
  } else {
    $sub_boxes = '<div class="menuBoxSubhead"><nobr>' . $sub_box_name . '</nobr></div>';
  }
  return $sub_boxes;
}

////
//Get selected file for index.php
function tep_selected_file($filename) {
  $randomize = FILENAME_ADMIN_ACCOUNT;

  $dbquery = tep_db_query("select admin_files_id as boxes_id from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $_SESSION['login_groups_id'] . "', admin_groups_id) and admin_files_is_boxes = '1' and admin_files_name = '" . $filename . "'");
  if (tep_db_num_rows($dbquery)) {
    $boxes_id = tep_db_fetch_array($dbquery);
    $randomize_query = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $_SESSION['login_groups_id'] . "', admin_groups_id) and admin_files_is_boxes = '0' and admin_files_to_boxes = '" . $boxes_id['boxes_id'] . "'");
    if (tep_db_num_rows($randomize_query)) {
      $file_selected = tep_db_fetch_array($randomize_query);
      $randomize = $file_selected['admin_files_name'];
    }
  }
  return $randomize;
}
//Admin end


////
// Parse the data used in the html tags to ensure the tags will not break
  function tep_parse_input_field_data($data, $parse) {
    return strtr(trim($data), $parse);
  }

  function tep_output_string($string, $translate = false, $protected = false) {
    $string = str_replace('\\', '', $string);
    if ($protected == true) {
      $new_string = str_replace('&amp;', '&', $string);  // so corruption will not occur
      return htmlspecialchars($new_string);
    } else {
      if ($translate == false) {
        return tep_parse_input_field_data($string, array('"' => '&quot;'));
      } else {
        return tep_parse_input_field_data($string, $translate);
      }
    }
  }

  function tep_output_string_protected($string) {
    return tep_output_string($string, false, true);
  }

  function tep_sanitize_string($string) {
    $string = ereg_replace(' +', ' ', $string);

    return preg_replace("/[<>]/", '_', $string);
  }

  function tep_customers_name($customers_id) {
    $customers = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
    $customers_values = tep_db_fetch_array($customers);

    return $customers_values['customers_firstname'] . ' ' . $customers_values['customers_lastname'];
  }

//check if product is really a subproduct
//returns parent_id if product is a sub, or false if it is not
function tep_subproducts_parent($products_id) {
  $product_sub_product_query = tep_db_query("select products_parent_id from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$products_id . "'");
  $product_sub_product = tep_db_fetch_array($product_sub_product_query);

 if ($product_sub_product['products_parent_id'] > 0){
      return $product_sub_product['products_parent_id'];
    } else {
      return false;
    }
}

  function tep_get_path($current_category_id = '') {
    global $cPath_array;

    if ($current_category_id == '') {
      $cPath_new = implode('_', $cPath_array);
    } else {
      if (sizeof($cPath_array) == 0) {
        $cPath_new = $current_category_id;
      } else {
        $cPath_new = '';
        $last_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cPath_array[(sizeof($cPath_array)-1)] . "'");
        $last_category = tep_db_fetch_array($last_category_query);

        $current_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
        $current_category = tep_db_fetch_array($current_category_query);

        if ($last_category['parent_id'] == $current_category['parent_id']) {
          for ($i = 0, $n = sizeof($cPath_array) - 1; $i < $n; $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        } else {
          for ($i = 0, $n = sizeof($cPath_array); $i < $n; $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        }

        $cPath_new .= '_' . $current_category_id;

        if (substr($cPath_new, 0, 1) == '_') {
          $cPath_new = substr($cPath_new, 1);
        }
      }
    }

    return 'cPath=' . $cPath_new;
  }

  function tep_get_all_get_params($exclude_array = '') {
    if ($exclude_array == '') $exclude_array = array();

    $get_url = '';

    reset($_GET);
    while (list($key, $value) = each($_GET)) {
      if (($key != tep_session_name()) && ($key != 'error') && (!in_array($key, $exclude_array))) $get_url .= $key . '=' . $value . '&';
    }

    return $get_url;
  }

  function tep_date_long($raw_date) {
    if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

    $year = (int)substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);

    return strftime(DATE_FORMAT_LONG, mktime($hour, $minute, $second, $month, $day, $year));
  }

////
// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
// NOTE: Includes a workaround for dates before 01/01/1970 that fail on windows servers
  function tep_date_short($raw_date) {
    if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

    $year = substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);

    if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
      return date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
    } else {
      return ereg_replace('2037' . '$', $year, date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, 2037)));
    }

  }

  function tep_datetime_short($raw_datetime) {
    if ( ($raw_datetime == '0000-00-00 00:00:00') || ($raw_datetime == '') ) return false;

    $year = (int)substr($raw_datetime, 0, 4);
    $month = (int)substr($raw_datetime, 5, 2);
    $day = (int)substr($raw_datetime, 8, 2);
    $hour = (int)substr($raw_datetime, 11, 2);
    $minute = (int)substr($raw_datetime, 14, 2);
    $second = (int)substr($raw_datetime, 17, 2);

    return strftime(DATE_TIME_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
  }

  function tep_get_category_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false, $customer_group_id = NULL) {
    global $languages_id;

    if (!is_array($category_tree_array)) $category_tree_array = array();
    if ( (sizeof($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);

    if ($include_itself) {
      $category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.language_id = '" . (int)$languages_id . "' and cd.categories_id = '" . (int)$parent_id . "'");
      $category = tep_db_fetch_array($category_query);
      $category_tree_array[] = array('id' => $parent_id, 'text' => $category['categories_name']);
    }

if ($customer_group_id == NULL){
    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.parent_id = '" . (int)$parent_id . "' order by c.sort_order, cd.categories_name");
   }else{
    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.parent_id = '" . (int)$parent_id . "' and (c.products_group_access like \"%,".$customer_group_id.",%\" or c.products_group_access like \"".$customer_group_id.",%\" or c.products_group_access like \"%,".$customer_group_id."\" or c.products_group_access='".$customer_group_id."') order by c.sort_order, cd.categories_name");
   }

   while ($categories = tep_db_fetch_array($categories_query)) {
      if ($exclude != $categories['categories_id']) $category_tree_array[] = array('id' => $categories['categories_id'], 'text' => $spacing . $categories['categories_name']);
      $category_tree_array = tep_get_category_tree($categories['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);
    }

    return $category_tree_array;
  }

  function tep_draw_products_pull_down($name, $parameters = '', $exclude = '') {
    global $currencies, $languages_id;

    if ($exclude == '') {
      $exclude = array();
    }

    $select_string = '<select name="' . $name . '"';

    if ($parameters) {
      $select_string .= ' ' . $parameters;
    }

    $select_string .= ' onChange="this.form.txtpflag.value=1;this.form.submit();">';

// Eversun mod for sppc and qty price breaks
    $all_groups=array();
    $customers_groups_query = tep_db_query("select customers_group_name, customers_group_id from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
    while ($existing_groups =  tep_db_fetch_array($customers_groups_query)) {
      $all_groups[$existing_groups['customers_group_id']]=$existing_groups['customers_group_name'];
    }
// Eversun mod end for sppc and qty price breaks

    $products_query = tep_db_query("SELECT p.products_id, pd.products_name, p.products_price
                                    FROM " . TABLE_PRODUCTS . " p,
                                         " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                    WHERE p.products_id = pd.products_id
                                      and pd.language_id = " . (int)$languages_id . "
                                      and (p.products_status = 1
                                          or (p.products_status <> 1 and p.products_parent_id <> 0))
                                      and p.products_model <> 'GIFT'
                                    ORDER BY products_name");
    while ($products = tep_db_fetch_array($products_query)) {

// Eversun mod for sppc and qty price breaks
/*      if (!in_array($products['products_id'], $exclude)) {
        $select_string .= '<option value="' . $products['products_id'] . '">' . $products['products_name'] . ' (' . $currencies->format($products['products_price']) . ')</option>'; */
      if (!in_array($products['products_id'], $exclude)) {
        $price_query=tep_db_query("select customers_group_price, customers_group_id from " . TABLE_PRODUCTS_GROUPS . " where products_id = " . $products['products_id']);
        $product_prices=array();
        while($prices_array=tep_db_fetch_array($price_query)){
          $product_prices[$prices_array['customers_group_id']]=$prices_array['customers_group_price'];
        }
        reset($all_groups);
        $price_string="";
        $sde=0;
        while(list($sdek,$sdev)=each($all_groups)){
          if (!in_array((int)$products['products_id'].":".(int)$sdek, $exclude)) {
            if($sde) $price_string.=", ";
            $price_string.=$sdev.": ".$currencies->format(isset($product_prices[$sdek]) ? $product_prices[$sdek]:$products['products_price']);
            $sde=1;
          }
        }
        if (isset($_POST[$name]) && (int)$_POST[$name] > 0  && $products['products_id'] == (int)$_POST[$name] )  {
          $strtmp =  ' selected';
        } else {
          $strtmp = '';
        }
        $select_string .= '<option value="' . $products['products_id'] . '" '.$strtmp.'>' . $products['products_name'] . ' (' . $price_string . ')</option>\n';
      }
// Eversun mod end for sppc and qty price breaks
    }

    $select_string .= '</select><input type ="hidden" name = "txtpflag" value = "0">';

    return $select_string;
  }

  function tep_options_name($options_id) {
    global $languages_id;

    $options = tep_db_query("select products_options_name from " . TABLE_PRODUCTS_OPTIONS_TEXT . " where products_options_text_id = '" . (int)$options_id . "' and language_id = '" . (int)$languages_id . "'");
    $options_values = tep_db_fetch_array($options);

    return $options_values['products_options_name'];
  }

  function tep_values_name($values_id) {
    global $languages_id;

    $values = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$values_id . "' and language_id = '" . (int)$languages_id . "'");
    $values_values = tep_db_fetch_array($values);

    return $values_values['products_options_values_name'];
  }

  function tep_info_image($image, $alt, $width = '', $height = '') {
      global $request_type;
    if (tep_not_null($image) && (file_exists(DIR_FS_CATALOG_IMAGES . $image)) ) {
      $image = tep_image((($request_type == SSL) ? HTTPS_CATALOG_SERVER : HTTP_SERVER ) . DIR_WS_CATALOG_IMAGES . $image, $alt, $width, $height);
    } else {
      $image = VISUAL_IMAGE_NONEXISTENT;
    }

    return $image;
  }

  function tep_break_string($string, $len, $break_char = '-') {
    $l = 0;
    $output = '';
    for ($i=0, $n=strlen($string); $i<$n; $i++) {
      $char = substr($string, $i, 1);
      if ($char != ' ') {
        $l++;
      } else {
        $l = 0;
      }
      if ($l > $len) {
        $l = 1;
        $output .= $break_char;
      }
      $output .= $char;
    }

    return $output;
  }

  function tep_get_country_name($country_id) {
    $country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$country_id . "'");

    if (!tep_db_num_rows($country_query)) {
      return $country_id;
    } else {
      $country = tep_db_fetch_array($country_query);
      return $country['countries_name'];
    }
  }

  function tep_get_zone_name($country_id, $zone_id, $default_zone) {
    $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
    if (tep_db_num_rows($zone_query)) {
      $zone = tep_db_fetch_array($zone_query);
      return $zone['zone_name'];
    } else {
      return $default_zone;
    }
  }

function tep_array_to_string($array, $exclude = '', $equals = '=', $separator = '&') {
    if (!is_array($exclude)) $exclude = array();

    $get_string = '';
    if (sizeof($array) > 0) {
      while (list($key, $value) = each($array)) {
        if ( (!in_array($key, $exclude)) && ($key != 'x') && ($key != 'y') ) {
          $get_string .= $key . $equals . $value . $separator;
        }
      }
      $remove_chars = strlen($separator);
      $get_string = substr($get_string, 0, -$remove_chars);
    }

    return $get_string;
  }

  function tep_not_null($value) {
    if (is_array($value)) {
      if (sizeof($value) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      if ( (is_string($value) || is_int($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0)) {
        return true;
      } else {
        return false;
      }
    }
  }

  function tep_browser_detect($component) {
    global $HTTP_USER_AGENT;

    return stristr($HTTP_USER_AGENT, $component);
  }

  function tep_tax_classes_pull_down($parameters, $selected = '') {
    $select_string = '<select ' . $parameters . '>';
    $classes_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while ($classes = tep_db_fetch_array($classes_query)) {
      $select_string .= '<option value="' . $classes['tax_class_id'] . '"';
      if ($selected == $classes['tax_class_id']) $select_string .= ' selected="selected"';
      $select_string .= '>' . $classes['tax_class_title'] . '</option>';
    }
    $select_string .= '</select>';

    return $select_string;
  }

  function tep_geo_zones_pull_down($parameters, $selected = '') {
    $select_string = '<select ' . $parameters . '>';
    $zones_query = tep_db_query("select geo_zone_id, geo_zone_name from " . TABLE_GEO_ZONES . " order by geo_zone_name");
    while ($zones = tep_db_fetch_array($zones_query)) {
      $select_string .= '<option value="' . $zones['geo_zone_id'] . '"';
      if ($selected == $zones['geo_zone_id']) $select_string .= ' selected="selected"';
      $select_string .= '>' . $zones['geo_zone_name'] . '</option>';
    }
    $select_string .= '</select>';

    return $select_string;
  }

  function tep_get_geo_zone_name($geo_zone_id) {
    $zones_query = tep_db_query("select geo_zone_name from " . TABLE_GEO_ZONES . " where geo_zone_id = '" . (int)$geo_zone_id . "'");

    if (!tep_db_num_rows($zones_query)) {
      $geo_zone_name = $geo_zone_id;
    } else {
      $zones = tep_db_fetch_array($zones_query);
      $geo_zone_name = $zones['geo_zone_name'];
    }

    return $geo_zone_name;
  }

  function tep_address_format($address_format_id, $address, $html, $boln, $eoln) {
    $address_format_query = tep_db_query("select address_format as format from " . TABLE_ADDRESS_FORMAT . " where address_format_id = '" . (int)$address_format_id . "'");
    $address_format = tep_db_fetch_array($address_format_query);

    $company = (isset($address['company']) ? tep_output_string_protected($address['company']) : '');
    if (isset($address['firstname']) && tep_not_null($address['firstname'])) {
      $firstname = tep_output_string_protected($address['firstname']);
      $lastname = tep_output_string_protected($address['lastname']);
    } elseif (isset($address['name']) && tep_not_null($address['name'])) {
      $firstname = tep_output_string_protected($address['name']);
      $lastname = '';
    } else {
      $firstname = '';
      $lastname = '';
    }
    $street = tep_output_string_protected($address['street_address']);
    $suburb = tep_output_string_protected($address['suburb']);
    $city = tep_output_string_protected($address['city']);
    $state = tep_output_string_protected($address['state']);
    if (isset($address['country_id']) && tep_not_null($address['country_id'])) {
      $country = tep_get_country_name($address['country_id']);

      if (isset($address['zone_id']) && tep_not_null($address['zone_id'])) {
        $state = tep_get_zone_code($address['country_id'], $address['zone_id'], $state);
      }
    } elseif (isset($address['country']) && tep_not_null($address['country'])) {
      $country = tep_output_string_protected($address['country']);
    } else {
      $country = '';
    }
    $postcode = tep_output_string_protected($address['postcode']);
    $zip = $postcode;
    $telephone = isset($address['telephone']) ? tep_output_string_protected($address['telephone']) : '';

    if ($html) {
// HTML Mode
      $HR = '<hr>';
      $hr = '<hr>';
      if ( ($boln == '') && ($eoln == "\n") ) { // Values not specified, use rational defaults
        $CR = '<br>';
        $cr = '<br>';
        $eoln = $cr;
      } else { // Use values supplied
        $CR = $eoln . $boln;
        $cr = $CR;
      }
    } else {
// Text Mode
      $CR = $eoln;
      $cr = $CR;
      $HR = '----------------------------------------';
      $hr = '----------------------------------------';
    }

    $statecomma = '';
    $streets = $street;
    if ($suburb != '') $streets = $street . $cr . $suburb;
    if ($country == '') $country = tep_output_string_protected($address['country']);
    if ($state != '') $statecomma = $state . ', ';

    $fmt = $address_format['format'];
    eval("\$address = \"$fmt\";");

    if ( (ACCOUNT_COMPANY == 'true') && (tep_not_null($company)) ) {
      $address = $company . $cr . $address;
    }
    
    if (tep_not_null($telephone)) {
      $address .= $cr . $telephone;
    }

    return $address;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_get_zone_code
  //
  // Arguments   : country           country code string
  //               zone              state/province zone_id
  //               def_state         default string if zone==0
  //
  // Return      : state_prov_code   state/province code
  //
  // Description : Function to retrieve the state/province code (as in FL for Florida etc)
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_get_zone_code($country, $zone, $def_state) {

    $state_prov_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and zone_id = '" . (int)$zone . "'");

    if (!tep_db_num_rows($state_prov_query)) {
      $state_prov_code = $def_state;
    }
    else {
      $state_prov_values = tep_db_fetch_array($state_prov_query);
      $state_prov_code = $state_prov_values['zone_code'];
    }

    return $state_prov_code;
  }

  function tep_get_uprid($prid, $params) {
    $uprid = $prid;
    if ( (is_array($params)) && (!strstr($prid, '{')) ) {
      while (list($option, $value) = each($params)) {
        $uprid = $uprid . '{' . $option . '}' . $value;
      }
    }

    return $uprid;
  }

  function tep_get_prid($uprid) {
    $pieces = explode('{', $uprid);

    return $pieces[0];
  }

  function tep_get_languages() {
    $languages_query = tep_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " order by sort_order");
    while ($languages = tep_db_fetch_array($languages_query)) {
      $languages_array[] = array('id' => $languages['languages_id'],
                                 'name' => $languages['name'],
                                 'code' => $languages['code'],
                                 'image' => $languages['image'],
                                 'directory' => $languages['directory']);
    }

    return $languages_array;
  }

  function tep_get_orders_status_name($orders_status_id, $language_id = '') {
    global $languages_id;

    if (!$language_id) $language_id = $languages_id;
    $orders_status_query = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . (int)$orders_status_id . "' and language_id = '" . (int)$language_id . "'");
    $orders_status = tep_db_fetch_array($orders_status_query);

    return $orders_status['orders_status_name'];
  }

  function tep_get_orders_status() {
    global $languages_id;

    $orders_status_array = array();
    $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "' order by orders_status_id");
    while ($orders_status = tep_db_fetch_array($orders_status_query)) {
      $orders_status_array[] = array('id' => $orders_status['orders_status_id'],
                                     'text' => $orders_status['orders_status_name']);
    }

    return $orders_status_array;
  }

  function tep_get_products_name($product_id, $language_id = 0) {
    global $languages_id;

    if ($language_id == 0) $language_id = $languages_id;
    $product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
    $product = tep_db_fetch_array($product_query);
    $product['products_name'] = tep_db_decoder($product['products_name']);
    $product['products_name'] = tep_db_output($product['products_name']);
    return $product['products_name'];
  }

  function tep_get_infobox_file_name($infobox_id, $language_id = 0) {
    global $languages_id;

    if ($language_id == 0) $language_id = $languages_id;
    $infobox_query = tep_db_query("select infobox_file_name from " . TABLE_INFOBOX_CONFIGURATION . " where infobox_id = '" . (int)$infobox_id . "' and language_id = '" . (int)$language_id . "'");
    $infobox = tep_db_fetch_array($infobox_query);

    return $infobox['infobox_file_name'];
  }

  function tep_get_products_description($product_id, $language_id) {
    $product_query = tep_db_query("select products_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_description'];
  }
  function tep_get_products_blurb($product_id, $language_id) {
    $product_query = tep_db_query("select products_blurb from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_blurb'];
  }

  function tep_get_products_url($product_id, $language_id) {
    $product_query = tep_db_query("select products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_url'];
  }

  function tep_get_category_name($category_id, $language_id) {
    $category_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
    $category = tep_db_fetch_array($category_query);

    return $category['categories_name'];
  }

    function tep_get_category_htc_title($category_id, $language_id) {
    $category_query = tep_db_query("select categories_head_title_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
    $category = tep_db_fetch_array($category_query);

    return $category['categories_head_title_tag'];
  }
    
  function tep_get_category_htc_desc($category_id, $language_id) {
    $category_query = tep_db_query("select categories_head_desc_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
    $category = tep_db_fetch_array($category_query);

    return $category['categories_head_desc_tag'];
  }
   
  function tep_get_category_htc_keywords($category_id, $language_id) {
    $category_query = tep_db_query("select categories_head_keywords_tag  from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
    $category = tep_db_fetch_array($category_query);

    return $category['categories_head_keywords_tag '];
  }
  
  function tep_get_products_head_title_tag($product_id, $language_id) {
    $product_query = tep_db_query("select products_head_title_tag from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_head_title_tag'];
  }

  function tep_get_products_head_desc_tag($product_id, $language_id) {
    $product_query = tep_db_query("select products_head_desc_tag from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_head_desc_tag'];
  }

  function tep_get_products_head_keywords_tag($product_id, $language_id) {
    $product_query = tep_db_query("select products_head_keywords_tag from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_head_keywords_tag'];
  }

  function tep_get_manufacturer_htc_title($manufacturer_id, $language_id) {
    $manufacturer_query = tep_db_query("select manufacturers_htc_title_tag from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$manufacturer_id . "' and languages_id = '" . (int)$language_id . "'");
    $manufacturer = tep_db_fetch_array($manufacturer_query);

    return $manufacturer['manufacturers_htc_title_tag'];
  }
    
  function tep_get_manufacturer_htc_desc($manufacturer_id, $language_id) {
    $manufacturer_query = tep_db_query("select manufacturers_htc_desc_tag from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$manufacturer_id . "' and languages_id = '" . (int)$language_id . "'");
    $manufacturer = tep_db_fetch_array($manufacturer_query);

    return $manufacturer['manufacturers_htc_desc_tag'];
  }
   
  function tep_get_manufacturer_htc_keywords($manufacturer_id, $language_id) {
    $manufacturer_query = tep_db_query("select manufacturers_htc_keywords_tag from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$manufacturer_id . "' and languages_id = '" . (int)$language_id . "'");
    $manufacturer = tep_db_fetch_array($manufacturer_query);

    return $manufacturer['manufacturers_htc_keywords_tag'];
  }

////
// Return the manufacturers URL in the needed language
// TABLES: manufacturers_info
  function tep_get_manufacturer_url($manufacturer_id, $language_id) {
    $manufacturer_query = tep_db_query("select manufacturers_url from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$manufacturer_id . "' and languages_id = '" . (int)$language_id . "'");
    $manufacturer = tep_db_fetch_array($manufacturer_query);

    return $manufacturer['manufacturers_url'];
  }

////
// Wrapper for class_exists() function
// This function is not available in all PHP versions so we test it before using it.
  function tep_class_exists($class_name) {
    if (function_exists('class_exists')) {
      return class_exists($class_name, false);
    } else {
      return true;
    }
  }

////
// Count how many products exist in a category
// TABLES: products, products_to_categories, categories
  function tep_products_in_category_count($categories_id, $include_deactivated = false) {
    $products_count = 0;

    if ($include_deactivated) {
      $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$categories_id . "' AND (p.default_product_id is NULL OR p.default_product_id=0)");
    } else {
      $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . (int)$categories_id . "' AND (p.default_product_id is NULL OR p.default_product_id=0)");
    }

    $products = tep_db_fetch_array($products_query);

    $products_count += $products['total'];

    $childs_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$categories_id . "'");
    if (tep_db_num_rows($childs_query)) {
      while ($childs = tep_db_fetch_array($childs_query)) {
        $products_count += tep_products_in_category_count($childs['categories_id'], $include_deactivated);
      }
    }

    return $products_count;
  }

////
// Count how many subcategories exist in a category
// TABLES: categories
  function tep_childs_in_category_count($categories_id) {
    $categories_count = 0;

    $categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$categories_id . "'");
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $categories_count += tep_childs_in_category_count($categories['categories_id']);
    }

    return $categories_count;
  }

////
// Returns an array with countries
// TABLES: countries
  function tep_get_countries($default = '') {
    $countries_array = array();
    if ($default) {
      $countries_array[] = array('id' => '',
                                 'text' => $default);
    }
    $countries_query = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
    while ($countries = tep_db_fetch_array($countries_query)) {
      $countries_array[] = array('id' => $countries['countries_id'],
                                 'text' => $countries['countries_name']);
    }

    return $countries_array;
  }

////
// return an array with country zones
  function tep_get_country_zones($country_id) {
    $zones_array = array();
    $zones_query = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' order by zone_name");
    while ($zones = tep_db_fetch_array($zones_query)) {
      $zones_array[] = array('id' => $zones['zone_id'],
                             'text' => $zones['zone_name']);
    }

    return $zones_array;
  }

  function tep_prepare_country_zones_pull_down($country_id = '') {
// preset the width of the drop-down for Netscape
    $pre = '';
    if ( (!tep_browser_detect('MSIE')) && (tep_browser_detect('Mozilla/4')) ) {
      for ($i=0; $i<45; $i++) $pre .= '&nbsp;';
    }

    $zones = tep_get_country_zones($country_id);

    if (sizeof($zones) > 0) {
      $zones_select = array(array('id' => '', 'text' => PLEASE_SELECT));
      $zones = array_merge($zones_select, $zones);
    } else {
      $zones = array(array('id' => '', 'text' => TYPE_BELOW));
// create dummy options for Netscape to preset the height of the drop-down
      if ( (!tep_browser_detect('MSIE')) && (tep_browser_detect('Mozilla/4')) ) {
        for ($i=0; $i<9; $i++) {
          $zones[] = array('id' => '', 'text' => $pre);
        }
      }
    }

    return $zones;
  }

////
// Get list of address_format_id's
  function tep_get_address_formats() {
    $address_format_query = tep_db_query("select address_format_id from " . TABLE_ADDRESS_FORMAT . " order by address_format_id");
    $address_format_array = array();
    while ($address_format_values = tep_db_fetch_array($address_format_query)) {
      $address_format_array[] = array('id' => $address_format_values['address_format_id'],
                                      'text' => $address_format_values['address_format_id']);
    }
    return $address_format_array;
  }

////
// Alias function for Store configuration values in the Administration Tool
  function tep_cfg_pull_down_country_list($country_id) {
    return tep_draw_pull_down_menu('configuration_value', tep_get_countries(), $country_id);
  }

  function tep_cfg_pull_down_zone_list($zone_id) {
    return tep_draw_pull_down_menu('configuration_value', tep_get_country_zones(STORE_COUNTRY), $zone_id);
  }

  function tep_cfg_pull_down_timezone_list($timezone_id) {
    $timezones_array = array();
    $timezone_identifiers = DateTimeZone::listIdentifiers();
    foreach ($timezone_identifiers as $tz) {
      $timezones_array[$tz] = 0;
    }
    ksort($timezones_array);
    unset($timezone_identifiers);
    
    $timezone_abbreviations = DateTimeZone::listAbbreviations();
    foreach ($timezone_abbreviations as $abr => $tzs) {
      foreach ($tzs as $tz) {
        if ($tz['timezone_id'] == '') continue;
        if (isset($timezones_array[$tz['timezone_id']])) {
          $timezones_array[$tz['timezone_id']] = $tz['offset'];
        }
      }
    }
    unset($timezone_abbreviations);
    
    $timezones = array();
    foreach($timezones_array as $tz => $offset) {
      if ($offset == 0) {
        $hours = 0;
        $mins = 0;
      } else {
        $hours = floor($offset / 3600);
        $mins = abs($offset) - abs($hours * 3600);
      }
      $timezones[] = array('id' => $tz,
                           'text' => $tz . ' GMT ' . sprintf('%+02d:%02d', $hours, $mins));
    }
    
    return tep_draw_pull_down_menu('configuration_value', $timezones, $timezone_id);
  }

  function tep_cfg_pull_down_tax_classes($tax_class_id, $key = '') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while ($tax_class = tep_db_fetch_array($tax_class_query)) {
      $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 'text' => $tax_class['tax_class_title']);
    }

    return tep_draw_pull_down_menu($name, $tax_class_array, $tax_class_id);
  }
//++++ QT Pro: Begin Changed code
////
// Function to build menu of available class files given a file prefix
// Used for configuring plug-ins for product information attributes
  function tep_cfg_pull_down_class_files($prefix, $current_file) {
    $d=DIR_FS_CATALOG . DIR_WS_CLASSES;
    $function_directory = dir ($d);

    while (false !== ($function = $function_directory->read())) {
      if (preg_match('/^'.$prefix.'(.+)\.php$/',$function,$function_name)) {
          $file_list[]=array('id'=>$function_name[1], 'text'=>$function_name[1]);
      }
    }
    $function_directory->close();

    return tep_draw_pull_down_menu('configuration_value', $file_list, $current_file);
  }

//++++ QT Pro: End Changed Code
////
// Function to read in text area in admin
 function tep_cfg_textarea($text) {
    return tep_draw_textarea_field('configuration_value', false, 35, 5, $text);
  }

  function tep_cfg_get_zone_name($zone_id) {
    $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_id = '" . (int)$zone_id . "'");

    if (!tep_db_num_rows($zone_query)) {
      return $zone_id;
    } else {
      $zone = tep_db_fetch_array($zone_query);
      return $zone['zone_name'];
    }
  }

// Function use: to create a color selector for html colors
  function tep_cfg_select_color_html($feild) {
$output  = '<tr> ';
$output .=    '<td bgcolor="#ffffff" valign="top">';
$output .=      '<input type="Text" name="configuration_value" value="' . $feild . '"> ' ;;
$output .=      '<a href="javascript:TCP.popup(document.forms[\'configuration\'].elements[\'configuration_value\'], 0)">' . tep_image_button('button_insert.gif', TEXT_HEADING_CHANGE_COLOR);
$output .=      '<br><br>'. TEXT_HEADING_INPUT_COLOR . '&nbsp;<span style= "{ border-width: medium ; border-style:solid ; border-color: #000000 ;  padding: .5em ; background-color: rgb(' . $feild . '); border-color: #000000 ; border-width: thin; padding: .5em ;} ">&nbsp;&nbsp;&nbsp;</span>';
$output .=     '</td>';
$output .=  '</tr>';

    return $output ;
  }

// Function use: to create a color selector for RGB colors
  function tep_cfg_select_color_rgb($feild) {
$output  = '<tr> ';
$output .=    '<td bgcolor="#ffffff" valign="top">';
$output .=      '<input type="Text" name="configuration_value" value="' . $feild . '"> ' ;
$output .=      '<a href="javascript:TCP_DEC.popup(document.forms[\'configuration\'].elements[\'configuration_value\'], 0)">' . tep_image_button('button_insert.gif', TEXT_HEADING_CHANGE_COLOR);
$output .=      '<br><br>'. TEXT_HEADING_INPUT_COLOR . '&nbsp;<span style= "{ border-width: medium ; border-style:solid ; border-color: #000000 ;  padding: .5em ; background-color: rgb(' . $feild . '); border-color: #000000 ; border-width: thin; padding: .5em ;} ">&nbsp;&nbsp;&nbsp;</span>';
$output .=     '</td>';
$output .=  '</tr>';

    return $output ;
  }

////
// Function to build menu of available font files
// Used for configuring plug-ins for product information attributes
  function tep_cfg_pull_down_font_files() {
     $d= DIR_FS_CATALOG . 'includes/fonts/' ;
     $name_select = VVC_FONTS;
  if ($handle1 = opendir($d)) {
      while (false !== ($file1 = readdir($handle1))) {
         if( (is_file($d . $file1)) && ($file1 != '..' || $file1 != '.' ) ){
         $file1 = str_replace(".ttf", "", $file1);
         $font_list1[] = array('id'=>$file1, 'text'=>$file1);
         }
     }
    closedir($handle1);
    }
return tep_draw_pull_down_menu('configuration_value', $font_list1, $name_select);
  }

////
// Sets the status of a banner
  function tep_set_banner_status($banners_id, $status) {
    if ($status == '1') {
      return tep_db_query("update " . TABLE_BANNERS . " set status = '1', date_status_change = now() where banners_id = '" . $banners_id . "'");
    } elseif ($status == '0') {
      return tep_db_query("update " . TABLE_BANNERS . " set status = '0', date_status_change = now() where banners_id = '" . $banners_id . "'");
    } else {
      return -1;
    }
  }

////
// Sets the status of a product
  function tep_set_product_status($products_id, $status) {
    if ($status == '1') {
      return tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '1', products_last_modified = now() where products_id = '" . (int)$products_id . "'");
    } elseif ($status == '0') {
      return tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0', products_last_modified = now() where products_id = '" . (int)$products_id . "'");
    } else {
      return -1;
    }
  }

////
// Sets the status of a product on special
  function tep_set_specials_status($specials_id, $status) {
    if ($status == '1') {
      return tep_db_query("update " . TABLE_SPECIALS . " set status = '1', expires_date = NULL, date_status_change = NULL where specials_id = '" . (int)$specials_id . "'");
    } elseif ($status == '0') {
      return tep_db_query("update " . TABLE_SPECIALS . " set status = '0', date_status_change = now() where specials_id = '" . (int)$specials_id . "'");
    } else {
      return -1;
    }
  }

////
// Sets timeout for the current script.
// Cant be used in safe mode.
  function tep_set_time_limit($limit) {
    if (!get_cfg_var('safe_mode')) {
      set_time_limit($limit);
    }
  }

////
// Alias function for Store configuration values in the Administration Tool
  function tep_cfg_select_option($select_array, $key_value, $key = '') {
    $string = '';

    for ($i=0, $n=sizeof($select_array); $i<$n; $i++) {
      $name = ((tep_not_null($key)) ? 'configuration[' . $key . ']' : 'configuration_value');

      $string .= '<br><input type="radio" name="' . $name . '" value="' . $select_array[$i] . '"';

      if ($key_value == $select_array[$i]) $string .= ' CHECKED';

      $string .= '> ' . $select_array[$i];
    }

    return $string;
  }

////
// Alias function for module configuration keys
  function tep_mod_select_option($select_array, $key_name, $key_value) {
    reset($select_array);
    while (list($key, $value) = each($select_array)) {
      if (is_int($key)) $key = $value;
      $string .= '<br><input type="radio" name="configuration[' . $key_name . ']" value="' . $key . '"';
      if ($key_value == $key) $string .= ' CHECKED';
      $string .= '> ' . $value;
    }

    return $string;
  }

////
// Retreive server information
  function tep_get_system_information() {
    $db_query = tep_db_query("select now() as datetime");
    $db = tep_db_fetch_array($db_query);

// windows os does not have uname utility

$server_information = @exec('uname -a') ;

if (!empty($server_information)){
    list($system, $host, $kernel) = preg_split('/[\s,]+/', $server_information, 5);
} else{
$system = '';
$host = '';
$kernel = '';
}

    return array('date' => tep_datetime_short(date('Y-m-d H:i:s')),
                 'system' => $system,
                 'kernel' => $kernel,
                 'host' => $host,
                 'ip' => gethostbyname($host),
                 'uptime' => @exec('uptime'),
                 'http_server' => $_SERVER['SERVER_SOFTWARE'],
                 'php' => PHP_VERSION,
                 'zend' => (function_exists('zend_version') ? zend_version() : ''),
                 'db_server' => DB_SERVER,
                 'db_ip' => gethostbyname(DB_SERVER),
                 'db_version' => 'MySQL ' . (function_exists('mysql_get_server_info') ? mysql_get_server_info() : ''),
                 'db_date' => tep_datetime_short($db['datetime']));
  }

  function tep_generate_category_path($id, $from = 'category', $categories_array = '', $index = 0) {
    global $languages_id;

    if (!is_array($categories_array)) $categories_array = array();

    if ($from == 'product') {
      $categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$id . "'");
      while ($categories = tep_db_fetch_array($categories_query)) {
        if ($categories['categories_id'] == '0') {
          $categories_array[$index][] = array('id' => '0', 'text' => TEXT_TOP);
        } else {
          $category_query = tep_db_query("select cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$categories['categories_id'] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
          $category = tep_db_fetch_array($category_query);
          $categories_array[$index][] = array('id' => $categories['categories_id'], 'text' => $category['categories_name']);
          if ( (tep_not_null($category['parent_id'])) && ($category['parent_id'] != '0') ) $categories_array = tep_generate_category_path($category['parent_id'], 'category', $categories_array, $index);
          $categories_array[$index] = array_reverse($categories_array[$index]);
        }
        $index++;
      }
    } elseif ($from == 'category') {
      $category_query = tep_db_query("select cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
      $category = tep_db_fetch_array($category_query);
      $categories_array[$index][] = array('id' => $id, 'text' => $category['categories_name']);
      if ( (tep_not_null($category['parent_id'])) && ($category['parent_id'] != '0') ) $categories_array = tep_generate_category_path($category['parent_id'], 'category', $categories_array, $index);
    }

    return $categories_array;
  }

  function tep_output_generated_category_path($id, $from = 'category') {
    $calculated_category_path_string = '';
    $calculated_category_path = tep_generate_category_path($id, $from);
    for ($i=0, $n=sizeof($calculated_category_path); $i<$n; $i++) {
      for ($j=0, $k=sizeof($calculated_category_path[$i]); $j<$k; $j++) {
        $calculated_category_path_string .= $calculated_category_path[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
      }
      $calculated_category_path_string = substr($calculated_category_path_string, 0, -16) . '<br>';
    }
    $calculated_category_path_string = substr($calculated_category_path_string, 0, -4);
    if (strlen($calculated_category_path_string) < 1) $calculated_category_path_string = TEXT_TOP;

    return $calculated_category_path_string;
  }

  function tep_get_generated_category_path_ids($id, $from = 'category') {
    $calculated_category_path_string = '';
    $calculated_category_path = tep_generate_category_path($id, $from);
    for ($i=0, $n=sizeof($calculated_category_path); $i<$n; $i++) {
      for ($j=0, $k=sizeof($calculated_category_path[$i]); $j<$k; $j++) {
        $calculated_category_path_string .= $calculated_category_path[$i][$j]['id'] . '_';
      }
      $calculated_category_path_string = substr($calculated_category_path_string, 0, -1) . '<br>';
    }
    $calculated_category_path_string = substr($calculated_category_path_string, 0, -4);
    if (strlen($calculated_category_path_string) < 1) $calculated_category_path_string = TEXT_TOP;
    // reverse the order
    $new_calculated_category_path_string = '';
    $path_array = explode('&nbsp;&gt;&nbsp;', $calculated_category_path_string);
    rsort($path_array);
    while (list(, $value) = each($path_array)) {
      $new_calculated_category_path_string .= $value . '&nbsp;&gt;&nbsp;';
    }
    $new_calculated_category_path_string = substr($new_calculated_category_path_string, 0, -16);

    return $new_calculated_category_path_string;
  }

  function tep_remove_category($category_id) {
    $category_image_query = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
    $category_image = tep_db_fetch_array($category_image_query);

    $duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where categories_image = '" . tep_db_input($category_image['categories_image']) . "'");
    $duplicate_image = tep_db_fetch_array($duplicate_image_query);

    if ($duplicate_image['total'] < 2) {
      if (file_exists(DIR_FS_CATALOG_IMAGES . $category_image['categories_image'])) {
        @unlink(DIR_FS_CATALOG_IMAGES . $category_image['categories_image']);
      }
    }

    tep_db_query("delete from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
    tep_db_query("delete from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "'");
    tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");

    if (USE_CACHE == 'true') {
      tep_reset_cache_block('categories');
      tep_reset_cache_block('also_purchased');
    }
  }

  function tep_remove_product($product_id) {
    $product_image_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
    $product_image = tep_db_fetch_array($product_image_query);

    $duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image = '" . tep_db_input($product_image['products_image']) . "'");
    $duplicate_image = tep_db_fetch_array($duplicate_image_query);

    if ($duplicate_image['total'] < 2) {
      if (file_exists(DIR_FS_CATALOG_IMAGES . $product_image['products_image'])) {
        @unlink(DIR_FS_CATALOG_IMAGES . $product_image['products_image']);
      }
    }

    tep_db_query("delete from " . TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where products_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where products_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " where products_id = " . (int)$product_id);
    $product_reviews_query = tep_db_query("select reviews_id from " . TABLE_REVIEWS . " where products_id = '" . (int)$product_id . "'");
    while ($product_reviews = tep_db_fetch_array($product_reviews_query)) {
      tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . (int)$product_reviews['reviews_id'] . "'");
    }
    tep_db_query("delete from " . TABLE_REVIEWS . " where products_id = '" . (int)$product_id . "'");
    //addition 12.15.08
    tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_FEATURED . " where products_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_ARTICLES_XSELL . " where xsell_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_PRODUCTS_XSELL . " where products_id = '" . (int)$product_id . "'"); //if product has xsell 
    tep_db_query("delete from " . TABLE_PRODUCTS_XSELL . " where xsell_id = '" . (int)$product_id . "'");   //if is xsell for other products


    if (USE_CACHE == 'true') {
      tep_reset_cache_block('categories');
      tep_reset_cache_block('also_purchased');
    }
  }

  function tep_remove_order($order_id, $restock) {
    if ($restock == 'on') {
      $order_query = tep_db_query("select products_id, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
      while ($order = tep_db_fetch_array($order_query)) {
        tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $order['products_quantity'] . ", products_ordered = products_ordered - " . $order['products_quantity'] . " where products_id = '" . (int)$order['products_id'] . "'");
      }
    }

    //begin PayPal_Shopping_Cart_IPN 2.8 DMG
    // the file may no longer exist, so check for it
    if (file_exists(DIR_FS_CATALOG_MODULES . 'payment/paypal/functions/general.func.php')) {
      include_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/functions/general.func.php');
      paypal_remove_order($order_id);
    }
    //end PayPal_Shopping_Cart_IPN
	
	//Start update customers orders count - Sep 12 2011
	$customer_query = tep_db_query("SELECT customers_id from ".TABLE_ORDERS." WHERE orders_id = '" . (int)$order_id . "'");
	$customer = tep_db_fetch_array($customer_query);	
	$customer_orders = tep_db_query("SELECT customers_orders_count from customers WHERE customers_id = '" . (int)$customer['customers_id'] . "'");
	$order_count = tep_db_fetch_array($customer_orders);
	if($order_count['customers_orders_count']>0) {
		$current_count = (int)($order_count['customers_orders_count']-1);
		tep_db_query("update customers set customers_orders_count='".$current_count."' WHERE customers_id='".(int)$customer['customers_id']."'");
	}
	//End update customers orders count - Sep 12 2011
	
    tep_db_query("delete from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "'");
	
	
	
  }

  function tep_reset_cache_block($cache_block) {
    global $cache_blocks;
$dir_cache = DIR_FS_CATALOG . DIR_FS_CACHE ;
   // get default template
   if (tep_not_null($cptemplate1['template_selected'])) {
  define('TEMPLATE_NAME', $cptemplate['template_selected']);
    }else if  (tep_not_null(DEFAULT_TEMPLATE)){
  define('TEMPLATE_NAME', DEFAULT_TEMPLATE);  
    } else {
    define('TEMPLATE_NAME', 'default');
    }
     $template_query = tep_db_query("select template_name from " . TABLE_TEMPLATE . "  order by template_name");
      while ($template = tep_db_fetch_array($template_query)) {
        $template_array=array(template=>template_name);
        }

    
    for ($i=0, $n=sizeof($cache_blocks); $i<$n; $i++) {
      if ($cache_blocks[$i]['code'] == $cache_block) {
        if ($cache_blocks[$i]['multiple']) {
          if ($dir = @opendir($dir_cache)) {
            while ($cache_file = readdir($dir)) {
              $cached_file = $cache_blocks[$i]['file'];
              $languages = tep_get_languages();
              for ($j=0, $k=sizeof($languages); $j<$k; $j++) {
                $cached_file_unlink = ereg_replace('-language', '-' . $languages[$j]['directory'], $cached_file);
                
                              for ($j=0, $k=sizeof($template_array); $j<$k; $j++) {
                    $cached_file_unlink = ereg_replace('-TEMPLATE_NAME', '-' . $template_array[$j]['template_name'] , $cached_file);
              
                
                if (ereg('^' . $cached_file_unlink, $cache_file)) {
                  @unlink($dir_cache . $cache_file);
                  }
                }
              }
            } 
            closedir($dir);
          }
        } else {
          $cached_file = $cache_blocks[$i]['file'];
          $languages = tep_get_languages();
          for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
            $cached_file = ereg_replace('-language', '-' . $languages[$i]['directory'], $cached_file);
            @unlink(DIR_FS_CACHE . $cached_file);
          }
        }
        break;
      }
    }
  }

  function tep_get_file_permissions($mode) {
// determine type
    if ( ($mode & 0xC000) == 0xC000) { // unix domain socket
      $type = 's';
    } elseif ( ($mode & 0x4000) == 0x4000) { // directory
      $type = 'd';
    } elseif ( ($mode & 0xA000) == 0xA000) { // symbolic link
      $type = 'l';
    } elseif ( ($mode & 0x8000) == 0x8000) { // regular file
      $type = '-';
    } elseif ( ($mode & 0x6000) == 0x6000) { //bBlock special file
      $type = 'b';
    } elseif ( ($mode & 0x2000) == 0x2000) { // character special file
      $type = 'c';
    } elseif ( ($mode & 0x1000) == 0x1000) { // named pipe
      $type = 'p';
    } else { // unknown
      $type = '?';
    }

// determine permissions
    $owner['read']    = ($mode & 00400) ? 'r' : '-';
    $owner['write']   = ($mode & 00200) ? 'w' : '-';
    $owner['execute'] = ($mode & 00100) ? 'x' : '-';
    $group['read']    = ($mode & 00040) ? 'r' : '-';
    $group['write']   = ($mode & 00020) ? 'w' : '-';
    $group['execute'] = ($mode & 00010) ? 'x' : '-';
    $world['read']    = ($mode & 00004) ? 'r' : '-';
    $world['write']   = ($mode & 00002) ? 'w' : '-';
    $world['execute'] = ($mode & 00001) ? 'x' : '-';

// adjust for SUID, SGID and sticky bit
    if ($mode & 0x800 ) $owner['execute'] = ($owner['execute'] == 'x') ? 's' : 'S';
    if ($mode & 0x400 ) $group['execute'] = ($group['execute'] == 'x') ? 's' : 'S';
    if ($mode & 0x200 ) $world['execute'] = ($world['execute'] == 'x') ? 't' : 'T';

    return $type .
           $owner['read'] . $owner['write'] . $owner['execute'] .
           $group['read'] . $group['write'] . $group['execute'] .
           $world['read'] . $world['write'] . $world['execute'];
  }

  function tep_remove($source) {
    global $messageStack, $tep_remove_error;

    if (isset($tep_remove_error)) $tep_remove_error = false;

    if (is_dir($source)) {
      $dir = dir($source);
      while ($file = $dir->read()) {
        if ( ($file != '.') && ($file != '..') ) {
          if (is_writeable($source . '/' . $file)) {
            tep_remove($source . '/' . $file);
          } else {
            $messageStack->add('search', sprintf(ERROR_FILE_NOT_REMOVEABLE, $source . '/' . $file), 'error');
            $tep_remove_error = true;
          }
        }
      }
      $dir->close();

      if (is_writeable($source)) {
        rmdir($source);
      } else {
        $messageStack->add('search', sprintf(ERROR_DIRECTORY_NOT_REMOVEABLE, $source), 'error');
        $tep_remove_error = true;
      }
    } else {
      if (is_writeable($source)) {
        unlink($source);
      } else {
        $messageStack->add('search', sprintf(ERROR_FILE_NOT_REMOVEABLE, $source), 'error');
        $tep_remove_error = true;
      }
    }
  }

////
// Output the tax percentage with optional padded decimals
  function tep_display_tax_value($value, $padding = TAX_DECIMAL_PLACES) {
    if (strpos($value, '.')) {
      $loop = true;
      while ($loop) {
        if (substr($value, -1) == '0') {
          $value = substr($value, 0, -1);
        } else {
          $loop = false;
          if (substr($value, -1) == '.') {
            $value = substr($value, 0, -1);
          }
        }
      }
    }

    if ($padding > 0) {
      if ($decimal_pos = strpos($value, '.')) {
        $decimals = strlen(substr($value, ($decimal_pos+1)));
        for ($i=$decimals; $i<$padding; $i++) {
          $value .= '0';
        }
      } else {
        $value .= '.';
        for ($i=0; $i<$padding; $i++) {
          $value .= '0';
        }
      }
    }

    return $value;
  }

  function tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address) {
    if (SEND_EMAILS != 'true') return false;

    // Instantiate a new mail object
    $message = new email(array('X-Mailer: osCommerce'));

    // Build the text version
    $text = strip_tags($email_text);
    if (EMAIL_USE_HTML == 'true') {
      $message->add_html($email_text, $text);
    } else {
      $message->add_text($text);
    }

    // Send message
    $message->build_message();
    $message->send($to_name, $to_email_address, $from_email_name, $from_email_address, $email_subject);
  }
  
   function tep_mail_with_attach($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, $attach = null) {
    if (SEND_EMAILS != 'true') return false;

    // Instantiate a new mail object
    $message = new email(array('X-Mailer: osCommerce Mailer'));

    // Build the text version
    $text = strip_tags($email_text);
    if (EMAIL_USE_HTML == 'true') {
      $message->add_html($email_text, $text);
    } else {
      $message->add_text($text);
    }
    
    if($attach){
      if(is_array($attach)){
        foreach ($attach as $att){
          if(substr($att['file'], 0 , -3) == 'png'){                         
            $message->add_attachment($att['file'], $att['name'], 'image/png');           
          } else {                         ;
              $message->add_attachment($att['file'], $att['name']);              
          }
        }
      } else {
          $message->add_attachment($attach);
      }
    }
      
    
    // Send message
    $message->build_message();
    $message->send($to_name, $to_email_address, $from_email_name, $from_email_address, $email_subject);
  }

// count the number of images present for a product
function tep_product_has_images($products_id) {
$product_has_images = 0 ;
$product_has_images_query = tep_db_query("select p.products_image, p.products_image_med, p.products_image_med from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$products_id . "'");
  while ($product_has_images_results = tep_db_fetch_array($product_has_images_query)){

  if ($product_has_images_results['products_image'] > '' ){
    $product_has_images = '1';
   }else{
    $product_has_images = '0';
   }
  }
return $product_has_images;
}

  function tep_get_tax_class_title($tax_class_id) {
    if ($tax_class_id == '0') {
      return TEXT_NONE;
    } else {
      $classes_query = tep_db_query("select tax_class_title from " . TABLE_TAX_CLASS . " where tax_class_id = '" . (int)$tax_class_id . "'");
      $classes = tep_db_fetch_array($classes_query);

      return $classes['tax_class_title'];
    }
  }

  function tep_banner_image_extension() {
    if (function_exists('imagetypes')) {
      if (imagetypes() & IMG_PNG) {
        return 'png';
      } elseif (imagetypes() & IMG_JPG) {
        return 'jpg';
      } elseif (imagetypes() & IMG_GIF) {
        return 'gif';
      }
    } elseif (function_exists('imagecreatefrompng') && function_exists('imagepng')) {
      return 'png';
    } elseif (function_exists('imagecreatefromjpeg') && function_exists('imagejpeg')) {
      return 'jpg';
    } elseif (function_exists('imagecreatefromgif') && function_exists('imagegif')) {
      return 'gif';
    }

    return false;
  }

////
// Wrapper function for round() for php3 compatibility
  function tep_round($value, $precision) {
    return round($value, $precision);
  }

////
// Add tax to a products price
  function tep_add_tax($price, $tax) {
    global $currencies;

    if (DISPLAY_PRICE_WITH_TAX == 'true') {
      return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']) + tep_calculate_tax($price, $tax);
    } else {
      return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
    }
  }

// Calculates Tax rounding the result
  function tep_calculate_tax($price, $tax) {
    global $currencies;

    return tep_round($price * $tax / 100, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
  }

////
// Returns the tax rate for a zone / class
// TABLES: tax_rates, zones_to_geo_zones
  function tep_get_tax_rate($class_id, $country_id = -1, $zone_id = -1) {

    if ( ($country_id == -1) && ($zone_id == -1) ) {
      if ( ! isset($_SESSION['customer_id']) ) {
        $country_id = STORE_COUNTRY;
        $zone_id = STORE_ZONE;
      } else {
        $country_id = $_SESSION['customer_country_id'];
        $zone_id = $_SESSION['customer_zone_id'];
      }
    }

    $tax_query = tep_db_query("select sum(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za ON tr.tax_zone_id = za.geo_zone_id left join " . TABLE_GEO_ZONES . " tz ON tz.geo_zone_id = tr.tax_zone_id WHERE (za.zone_country_id IS NULL OR za.zone_country_id = '0' OR za.zone_country_id = '" . (int)$country_id . "') AND (za.zone_id IS NULL OR za.zone_id = '0' OR za.zone_id = '" . (int)$zone_id . "') AND tr.tax_class_id = '" . (int)$class_id . "' GROUP BY tr.tax_priority");
    if (tep_db_num_rows($tax_query)) {
      $tax_multiplier = 0;
      while ($tax = tep_db_fetch_array($tax_query)) {
        $tax_multiplier += $tax['tax_rate'];
      }
      return $tax_multiplier;
    } else {
      return 0;
    }
  }

////
// Returns the tax rate for a tax class
// TABLES: tax_rates
  function tep_get_tax_rate_value($class_id) {
    $tax_query = tep_db_query("select sum(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " where tax_class_id = '" . (int)$class_id . "' group by tax_priority");
    if (tep_db_num_rows($tax_query)) {
      $tax_multiplier = 0;
      while ($tax = tep_db_fetch_array($tax_query)) {
        $tax_multiplier += $tax['tax_rate'];
      }
      return $tax_multiplier;
    } else {
      return 0;
    }
  }

  function tep_call_function($function, $parameter, $object = '') {
    if (!is_object($object)) {
      return call_user_func($function, $parameter);
    } else {
      return call_user_func(array($object, $function), $parameter);
    }
  }

  function tep_get_zone_class_title($zone_class_id) {
    if ($zone_class_id == '0') {
      return TEXT_NONE;
    } else {
      $classes_query = tep_db_query("select geo_zone_name from " . TABLE_GEO_ZONES . " where geo_zone_id = '" . (int)$zone_class_id . "'");
      $classes = tep_db_fetch_array($classes_query);

      return $classes['geo_zone_name'];
    }
  }

  function tep_cfg_pull_down_zone_classes($zone_class_id, $key = '') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $zone_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $zone_class_query = tep_db_query("select geo_zone_id, geo_zone_name from " . TABLE_GEO_ZONES . " order by geo_zone_name");
    while ($zone_class = tep_db_fetch_array($zone_class_query)) {
      $zone_class_array[] = array('id' => $zone_class['geo_zone_id'],
                                  'text' => $zone_class['geo_zone_name']);
    }

    return tep_draw_pull_down_menu($name, $zone_class_array, $zone_class_id);
  }

  function tep_cfg_pull_down_order_statuses($order_status_id, $key = '') {
    global $languages_id;

    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $statuses_array = array(array('id' => '0', 'text' => TEXT_DEFAULT));
    $statuses_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "' order by orders_status_name");
    while ($statuses = tep_db_fetch_array($statuses_query)) {
      $statuses_array[] = array('id' => $statuses['orders_status_id'],
                                'text' => $statuses['orders_status_name']);
    }

    return tep_draw_pull_down_menu($name, $statuses_array, $order_status_id);
  }

  function tep_get_order_status_name($order_status_id, $language_id = '') {
    global $languages_id;

    if ($order_status_id < 1) return TEXT_DEFAULT;

    if (!is_numeric($language_id)) $language_id = $languages_id;

    $status_query = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . (int)$order_status_id . "' and language_id = '" . (int)$language_id . "'");
    $status = tep_db_fetch_array($status_query);

    return $status['orders_status_name'];
  }

////
// Return a random value
  function tep_rand($min = null, $max = null) {
    static $seeded;

    if (!$seeded) {
      mt_srand((double)microtime()*1000000);
      $seeded = true;
    }

    if (isset($min) && isset($max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }

// nl2br() prior PHP 4.2.0 did not convert linefeeds on all OSs (it only converted \n)
  function tep_convert_linefeeds($from, $to, $string) {
    return str_replace($from, $to, $string);
  }

  function tep_string_to_int($string) {
    return (int)$string;
  }

////
// Parse and secure the cPath parameter values
  function tep_parse_category_path($cPath) {
// make sure the category IDs are integers
    $cPath_array = array_map('tep_string_to_int', explode('_', $cPath));

// make sure no duplicate category IDs exist which could lock the server in a loop
    $tmp_array = array();
    $n = sizeof($cPath_array);
    for ($i=0; $i<$n; $i++) {
      if (!in_array($cPath_array[$i], $tmp_array)) {
        $tmp_array[] = $cPath_array[$i];
      }
    }

    return $tmp_array;
  }
// Alias function for array of configuration values in the Administration Tool
  function tep_cfg_select_multioption($select_array, $key_value, $key = '') {
    $string = '';
    for ($i=0; $i<sizeof($select_array); $i++) {
      $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
      $string .= '<br><input type="checkbox" name="' . $name . '" value="' . $select_array[$i] . '"';
      $key_values = explode( ", ", $key_value);
      if ( in_array($select_array[$i], $key_values) ) $string .= 'CHECKED';
      $string .= '> ' . $select_array[$i];
    }
    return $string;
  }

//create a select list to display list of themes available for selection
  function tep_cfg_pull_down_template_list($template_id, $key = '') {
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $template_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . " order by template_name");
    while ($template = tep_db_fetch_array($template_query)) {
      $template_array[] = array('id' => $template['template_name'],
                                 'text' => $template['template_name']);
    }

    return tep_draw_pull_down_menu($name, $template_array, $template_id);
  }


// BOF: WebMakers.com Added: Downloads Controller
require(DIR_WS_FUNCTIONS . 'downloads_controller.php');
// EOF: WebMakers.com Added: Downloads Controller

// Contact US Email Subjects : DMG
// PassionSeed Contact Us Email Subject begin
  function tep_get_email_subjects_list($subjects_array = '') {
    if (!is_array($subjects_array)) $subjects_array = array();

    $subjects_query = tep_db_query("select email_subjects_id, email_subjects_name from " . TABLE_EMAIL_SUBJECTS . " order by email_subjects_name");
    while ($subjects = tep_db_fetch_array($subjects_query)) {
      $subjects_array[] = array('id' => $subjects['email_subjects_name'], 'text' => $subjects['email_subjects_name']);
    }

    return $subjects_array;
  }
// PassionSeed Contact Us Email Subject end
function tep_get_pay_method($pay_methods_id, $language_id = '') {
    global $languages_id;

    if (!$language_id) $language_id = $languages_id;
    $pay_method_query1 = tep_db_query("select pay_method from " . TABLE_ORDERS_PAY_METHODS . " where pay_methods_id = '" . (int)$pay_methods_id . "' and pay_method_language = '" . (int)$language_id . "'");
    $pay_method1 = tep_db_fetch_array($pay_method_query1);

    return $pay_method1['pay_method'];
  }
function tep_get_ship_method($ship_methods_id, $language_id = '') {
    global $languages_id;

    if (!$language_id) $language_id = $languages_id;
    $ship_method_query1 = tep_db_query("select ship_method from " . TABLE_ORDERS_SHIP_METHODS . " where ship_methods_id = '" . (int)$ship_methods_id . "' and ship_method_language = '" . (int)$language_id . "'");
    $ship_method1 = tep_db_fetch_array($ship_method_query1);

    return $ship_method1['ship_method'];
  }

  function tep_get_ip_address() {
    if (isset($_SERVER)) {
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
      } else {
        $ip = $_SERVER['REMOTE_ADDR'];
      }
    } else {
      if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
      } elseif (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
      } else {
        $ip = getenv('REMOTE_ADDR');
      }
    }

    return $ip;
  }

  
function tep_get_box_heading($infobox_id, $languages_id) {
    $configuration_query12 = tep_db_query("select box_heading from " . TABLE_INFOBOX_HEADING . " where infobox_id = '" . (int)$infobox_id . "' and languages_id = '" . (int)$languages_id . "'");
    $configuration12 = tep_db_fetch_array($configuration_query12);

    return $configuration12['box_heading'];
  }
  
function checkParentStatus($pcatid,$grouptype) {
  global $groupcnt;
  if($pcatid!="")
  {
    $listing1_sql =' and (products_group_access like "%,'.$grouptype.',%" or products_group_access like "'.$grouptype.',%" or products_group_access like "%,'.$grouptype.'" or products_group_access="'.$grouptype.'")';
    $categories_query1 = tep_db_query("select count(categories_id) as cntcat from " . TABLE_CATEGORIES."  where categories_id=".$pcatid.$listing1_sql);
    while($category = tep_db_fetch_array($categories_query1))
    {
      if($category['cntcat']=="0")
      {
        $groupcnt=$groupcnt+ 1;
      }
    }

     $category_query = tep_db_query("select parent_id from ".TABLE_CATEGORIES." where categories_id=".$pcatid);
     while($category = tep_db_fetch_array($category_query))
     {
      //echo $category['parent_id'];
      if($category['parent_id']!=0)
      {
        checkParentStatus($category['parent_id'],$grouptype);
      }
    }
  }
}

function readChild($pcatid,$pcustomergroupid,$categoryorproduct) {
   $category_query = tep_db_query("select categories_id from ".TABLE_CATEGORIES." where parent_id=".$pcatid);
   while($category = tep_db_fetch_array($category_query)) {
     $sql_data_array1=array('products_group_access' => $pcustomergroupid);
     $update_sql_data = array('last_modified' => 'now()');
     $sql_data_array1 = array_merge($sql_data_array1, $update_sql_data);
     tep_db_perform(TABLE_CATEGORIES, $sql_data_array1, 'update', "categories_id = '" . (int)$category['categories_id'] . "'");
     if($categoryorproduct=="CP") {
       tep_db_query("update " . TABLE_PRODUCTS . " a,".TABLE_PRODUCTS_TO_CATEGORIES." b set products_group_access = '" . $pcustomergroupid . "'  where a.products_id=b.products_id and b.categories_id= '" . (int)$category['categories_id'] . "'");
     }
   }
   if ($categoryorproduct=="CP") {
     tep_db_query("update " . TABLE_PRODUCTS . " a,".TABLE_PRODUCTS_TO_CATEGORIES." b set products_group_access = '" . $pcustomergroupid . "'  where a.products_id=b.products_id and b.categories_id= '" . (int)$pcatid . "'");
   }
}

  function tep_html_noquote($string) {
  $string=str_replace('&#39;', '', $string);
  $string=str_replace("'", "", $string);
  $string=str_replace('"', '', $string);
  $string=preg_replace("/\\r\\n|\\n|\\r/", "<br>", $string); 
  return $string;

}

  //template swithing,
  // usage echo cre_template_switch('filed_name','selected_template')
  function cre_template_switch($var_name,$selected = '') {
  $templates_query = tep_db_query("SELECT template_name from " . TABLE_TEMPLATE . " WHERE active = 1");
  $dirs_array[] = array('id' => '', 'text' => 'Select / Remove');//this is needed, otherwise every affiliate / group, etc must have a template selected
    while ($templates = tep_db_fetch_array($templates_query)) {
      $dirs_array[] = array('id' => $templates['template_name'], 'text' => $templates['template_name']);
    }
  if(isset($selected) ? $selected : '');
  return tep_draw_pull_down_menu($var_name, $dirs_array, $selected );
}

/************   GSR - Group Access Logic - Start   ************/
function func_category_group_access($group_access,$current_category_id) {
  ?>
  <table width="100%" border = "0" cellspacing="0" cellpadding="0" align="center" >
      <tr>
        <td>
          <fieldset>
    <legend><?php echo TEXT_PRODUCTS_GROUP_ACCESS; ?></legend>
    <table width="100%" border="0" cellspacing="3" cellpadding="3" align="center" summary="category Image and sort order">
      <tr valign="top">
        <td width="50%" class="main">
     <table>
             <?php
   
            //category groups For Guest Group as its not a Default Group
            $customergroupidcnt=0;
            $foundid="No";
            if($group_access!="")
            {
              foreach ((explode(",",$group_access)) as $cgroupid)
              {
                if($cgroupid=='G')
                $foundid="Yes";
              }
            }

            $groupcnt=0;
            $parentstate=checkParentStatus($current_category_id,"G");
            if($groupcnt=="1")
            {
                   echo "<tr><td>".CAT_GUEST."</td><td></td><td> "
                .tep_draw_radio_field('customergroupid'.$customergroupidcnt, 'N',true)."</td><td>".CAT_HIDE."</td></tr>";
            }
            else
            {

              /*if($action=="new_category")
              {$foundid="Yes";}*/

                if($foundid=="Yes")
              {
                echo "<tr><td>".CAT_GUEST."</td><td>".
                tep_draw_radio_field('customergroupid'.$customergroupidcnt, 'Y',true)."</td><td>".CAT_SHOW."</td><td> ".
                tep_draw_radio_field('customergroupid'.$customergroupidcnt, 'N')."</td><td>".CAT_HIDE."</td></tr>";
              }
              else
              {
                echo "<tr><td>".CAT_GUEST."</td><td>"
                .tep_draw_radio_field('customergroupid'.$customergroupidcnt, 'Y')."</td><td>".CAT_SHOW."</td><td> "
                .tep_draw_radio_field('customergroupid'.$customergroupidcnt, 'N',true)."</td><td>".CAT_HIDE."</td></tr>";
              }
              }

             $customergroup_query = tep_db_query("select customers_group_name,customers_group_id from ".TABLE_CUSTOMERS_GROUPS);
             while($customergroup = tep_db_fetch_array($customergroup_query))
             {
               $customergroupidcnt=$customergroupidcnt+1;
               $foundid="No";
               foreach ((explode(",",$group_access)) as $cgroupid)
               {
                if($cgroupid==$customergroup["customers_group_id"])
                $foundid="Yes";
            }

              $groupcnt=0;
              $parentstate=checkParentStatus($current_category_id,$customergroup["customers_group_id"]);
              if($groupcnt=="1")
              {
                  echo "<tr><td>".$customergroup["customers_group_name"]."</td><td></td><td>".
                  tep_draw_radio_field('customergroupid'.$customergroupidcnt,"N",true)."</td><td>".CAT_HIDE."</td></tr>";
              }
              else
              {
               /* if($action=="new_category")
                {$foundid="Yes";}*/

                   if($foundid=="Yes")
                 {
                   echo "<tr><td>".$customergroup["customers_group_name"]."</td><td>".
                   tep_draw_radio_field('customergroupid'.$customergroupidcnt, $customergroup["customers_group_id"],true)."</td><td>".CAT_SHOW."</td><td>".
                   tep_draw_radio_field('customergroupid'.$customergroupidcnt, "N")."</td><td>".CAT_HIDE."</td></tr>";
                 }
                 else
                 {
                  echo "<tr><td>".$customergroup["customers_group_name"]."</td><td>".
                  tep_draw_radio_field('customergroupid'.$customergroupidcnt, $customergroup["customers_group_id"])."</td><td>".CAT_SHOW."</td><td>".
                  tep_draw_radio_field('customergroupid'.$customergroupidcnt,"N",true)."</td><td>".CAT_HIDE."</td></tr>";
                 }
              }
            }

             ?>
             <input type="hidden" name="customergroupidcnt" value="<?php echo $customergroupidcnt; ?>">
             </table>

    </td>
    <td>  <fieldset><legend><?php echo TEXT_PUSH_SAVE_OPTION;?></legend>
    <table width = "100%" border = "0" cellpadding = "2" cellspacing = "1">
      <tr>
        <td width = "5" >
          <?php echo tep_draw_radio_field('Push', '1');?>
        </td>
        <td>
          <?php echo TEXT_DO_NOT_PUSH;?>
        </td>
      </tr>

      <tr>
        <td>
          <?php echo tep_draw_radio_field('Push', '2');?>
        </td>
        <td>
          <?php echo TEXT_PUSH_SUB_CATEGORIES;?>
        </td>
      </tr>

      <tr>
        <td>
          <?php echo  tep_draw_radio_field('Push', '3');?>
        </td>
        <td>
          <?php echo TEXT_PUSH_SUB_CATEGORIES_PRODUCTS;?>
        </td>
      </tr>
      <tr>
        <td colspan = "3">  
          <?php echo  tep_image('images/icons/warning.gif',TEXT_PUSH_WARNING) . '&nbsp;<font size="1">'.TEXT_PUSH_WARNING.'</font>'; ?>
        </td>
      </tr>
    </table>
                </fieldset></td>
    </tr>
   </table>
 </fieldset>
        </td>
      </tr>
    </table>
<?php
}

function func_product_group_access($group_access) {
?>
<table cellpadding = "2" cellspacing = "2" border = "0" width = "100%">
  <tr bgcolor="#ebebff">
     <td class="main">
     <?php echo TEXT_PRODUCTS_GROUP_ACCESS; ?></td>
     <td class="main">

   <table cellpadding = "2" cellspacing = "2" border = "0" width = "100%">
   <?php

  //product groups For Guest Group as its not a Default Group
  $customergroupidcnt=0;
  $foundid="No";
  if($group_access!="")
  {
    foreach ((explode(",",$group_access)) as $cgroupid)
    {
      if($cgroupid=='G')
      $foundid="Yes";
    }
  }

  /*if (!(isset($pID)))
  {$foundid="Yes";}*/

    if($foundid=="Yes")
  {
    echo "<tr><td>".CAT_GUEST."</td><td>".
    tep_draw_radio_field('customergroupid'.$customergroupidcnt, 'Y',true)." </td><td>".CAT_SHOW."</td><td> ".
    tep_draw_radio_field('customergroupid'.$customergroupidcnt, 'N')."</td><td>".CAT_HIDE."</td></tr>";
  }
  else
  {
    echo "<tr><td>".CAT_GUEST."</td><td>"
    .tep_draw_radio_field('customergroupid'.$customergroupidcnt, 'Y')." </td><td>".CAT_SHOW."</td><td> "
    .tep_draw_radio_field('customergroupid'.$customergroupidcnt, 'N',true)."</td><td>".CAT_HIDE."</td></tr>";
  }


   $customergroup_query = tep_db_query("select customers_group_name,customers_group_id from ".TABLE_CUSTOMERS_GROUPS);
   while($customergroup = tep_db_fetch_array($customergroup_query))
   {
     $customergroupidcnt=$customergroupidcnt+1;
     $foundid="No";
     foreach ((explode(",",$group_access)) as $cgroupid)
     {
      if($cgroupid==$customergroup["customers_group_id"])
      $foundid="Yes";
     }
  /* if (!(isset($pID)))
    {$foundid ="Yes" ;}*/

       if($foundid=="Yes")
     {
       echo "<tr><td>".$customergroup["customers_group_name"] . "</td><td>".
       tep_draw_radio_field('customergroupid'.$customergroupidcnt, $customergroup["customers_group_id"],true)." </td><td>".CAT_SHOW."</td><td>".
       tep_draw_radio_field('customergroupid'.$customergroupidcnt, "N")."</td><td>".CAT_HIDE."</td></tr>";
     }
     else
     {
      echo "<tr><td>".$customergroup["customers_group_name"] . "</td><td>".
      tep_draw_radio_field('customergroupid'.$customergroupidcnt, $customergroup["customers_group_id"])." </td><td>".CAT_SHOW."</td><td>".
      tep_draw_radio_field('customergroupid'.$customergroupidcnt,"N",true)."</td><td>".CAT_HIDE."</td></tr>";
     }
  }

   ?>
   <input type="hidden" name="customergroupidcnt" value="<?php echo $customergroupidcnt; ?>">
   </table>
   </td>
  </tr>
</table>
<?php
} 
function func_group_access_array($sql_data_array,$action='') {
  global $_POST,$customergroupid;
    $customergroupidcnt=$_POST['customergroupidcnt'];
    $customergroupid="";
    for ($i=0; $i <= $customergroupidcnt; $i++)
    {
      if($_POST['customergroupid'.$i]!='N')
      {
        if($_POST['customergroupid'.$i]=='Y')
        {$customergroupid.="G,";}
        else{$customergroupid.=$_POST['customergroupid'.$i].",";}
      }
    }
    $customergroupid=substr($customergroupid,0,(strlen($customergroupid)-1));

    if($action == 'new' && $customergroupid=="")
    {
      $customergroupid="G,0";
    }

    $arrcustomergroupid=array('products_group_access' => $customergroupid);
    $sql_data_array = array_merge($sql_data_array, $arrcustomergroupid);
    return $sql_data_array;
}

  //function to get order status for configurations.
  function tep_get_orders_status_selection($key_value, $key = '') {
    $string = '';
    $key_values = explode(',', $key_value);
    $orders_status_array = array();
    $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$_SESSION['languages_id'] . "' order by orders_status_id");
    while ($orders_status = tep_db_fetch_array($orders_status_query)) {
      $orders_status_array[] = array ('id' => $orders_status['orders_status_id'],
                                      'name' => $orders_status['orders_status_name']);
    }
    for ($i=0; $i<(sizeof($orders_status_array)); $i++) {
      $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value[]');
      $string .= '<br><input type="checkbox" name="' . $name . '" value="' . $orders_status_array[$i]['id'] . '"';
      if (in_array($orders_status_array[$i]['id'], $key_values)) $string .= ' checked="checked"';
      $string .= '> ' . $orders_status_array[$i]['name'];
    }
    return $string;
  }
  
  
  //function to show title and tooltip of index boxes.
  function cre_index_block_title($title, $url='', $tip='') {
    $content = '';
    $content .= $title;
    if(isset($url) && $url !=''){
      $content .= ' (<a href="' . $url . '">' . TEXT_MANAGE . '</a>) ';
    }
    if(isset($tip) && $tip !='' && defined('ADMIN_BLOCKS_SHOW_TIP') && ADMIN_BLOCKS_SHOW_TIP == 'true'){
      $content .= ' <a class="helpLink" href="?" onMouseover="showhint(\'' . $tip . '\', this, event, \'250px\'); return false">[?]</a>';
    }
    echo $content;
  }

  //generate password 
  function randomize() {
    $salt = "ABCDEFGHIJKLMNOPQRSTUVWXWZabchefghjkmnpqrstuvwxyz0123456789";
    srand((double)microtime()*1000000);
    $i = 0;
    $pass = '';
    while ($i <= ENTRY_PASSWORD_MIN_LENGTH) {
      $num = rand() % strlen($salt);
      $tmp = substr($salt, $num, 1);
      $pass .= $tmp;
      $i++;
    }
    return $pass;
  }
  
// multi vendor shiping
// Sets the Vendor Send Order Emails
function tep_set_vendor_email($vendors_id, $vendors_send_email) {
  if ($vendors_send_email == '1') {
    return tep_db_query("update " . TABLE_VENDORS . " set vendors_send_email = '1' where vendors_id = '" . (int)$vendors_id . "'");
  } elseif ($vendors_send_email == '0') {
    return tep_db_query("update " . TABLE_VENDORS . " set vendors_send_email = '0' where vendors_id = '" . (int)$vendors_id . "'");
  } else {
    return -1;
  }
}

function tep_get_vendors_info($product_id, $vendors_id, $language_id = 0) {
  global $languages_id;
  if ($language_id == 0) $language_id = $languages_id;
  $product_query = tep_db_query("select * from " . TABLE_VENDORS . " where " . TABLE_VENDORS . " .vendors_id = " . TABLE_PRODUCTS . " .vendors_id and products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
  $product = tep_db_fetch_array($product_query);
  return $product['vendors_name'];
}

function tep_get_vendors_prod_comments($product_id) {
  $product_query = tep_db_query("select vendors_prod_comments from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
  $product = tep_db_fetch_array($product_query);
  return $product['vendors_prod_comments'];
}

function tep_get_vendor_url($vendor_id, $language_id) {
  $vendor_query = tep_db_query("select vendors_url from " . TABLE_VENDORS_INFO . " where vendors_id = '" . (int)$vendor_id . "' and languages_id = '" . (int)$language_id . "'");
  $vendor = tep_db_fetch_array($vendor_query);
  return $vendor['vendors_url'];
}

function tep_get_country_list($name, $selected = '', $parameters = '') {
  $countries = tep_get_countries();
  return tep_draw_pull_down_menu($name, $countries, $selected, $parameters);
}

//Mar 30 2011

//functions used in artwork and design start

function tep_get_all_admin() {
	$sqlSelAdmin = tep_db_query("SELECT * FROM ".TABLE_ADMIN);
	$admin_arr = array();
	while($admin = tep_db_fetch_array($sqlSelAdmin)) {
		$admin_arr[] = $admin; 
	}
	return $admin_arr;
}

function tep_get_order_products($op_id) {
	$sqlSelOrdPro = tep_db_query("SELECT * FROM ".TABLE_ORDERS_PRODUCTS." WHERE orders_products_id='".$op_id."'");	
	$op = tep_db_fetch_array($sqlSelOrdPro);	
	return $op;
}

function tep_get_artwork_option($artwork_id) {
	$sel_option= tep_db_query("SELECT * FROM artwork_option WHERE artwork_id='".$artwork_id."'");
	while($rst_arr_opt = tep_db_fetch_array($sel_option)) {
		$opt_arr[] = $rst_arr_opt;
	}
	return $opt_arr;
}

//get option infor by option id
function tep_get_option_details($option_id) {
	$sel_option= tep_db_query("SELECT * FROM artwork_option WHERE artwork_option_id='".$option_id."'");
	$option = tep_db_fetch_array($sel_option);	
	return $option;
}

//option revision count
function tep_get_revision_count($option_id) {
	$sel_revision= tep_db_query("SELECT COUNT(*) as rev_count FROM artwork_option WHERE revision_id='".$option_id."'");
	$rev_count = tep_db_fetch_array($sel_revision);	
	return $rev_count['rev_count'];
}

//artwork revision count
function tep_get_artwork_revision_count($art_id) {
	
	$sel_revision= tep_db_query("SELECT COUNT(*) as rev_count FROM artwork_feedback WHERE status='revision' and artwork_id='".$art_id."'");
	$rev_count = tep_db_fetch_array($sel_revision);		
	return $rev_count['rev_count'];
}

function tep_get_artwork_option_count($artwork_id) {
	$sel_option= tep_db_query("SELECT COUNT(*) as opt_count FROM artwork_option WHERE artwork_id='".$artwork_id."'");
	$opt_count = tep_db_fetch_array($sel_option);		
	return $opt_count['opt_count'];
}

function tep_get_admin_details($admin_id) {
	$sqlSelOrdPro = tep_db_query("SELECT * FROM ".TABLE_ADMIN." WHERE admin_id='".$admin_id."'");	
	$op = tep_db_fetch_array($sqlSelOrdPro);	
	return $op;
}

function tep_date_aus_format($date,$type) {
	if($date=="") { return false; } if(is_null($date)) { return false; }
	if($date == "0000-00-00 00:00:00" ) { return false; }
	
	if($type=="short") {
		return date("d/m/Y", strtotime($date));
	} else {		
		return date("d/m/Y H:i:g", strtotime($date));
	}
}

//functions used in artwork and design end

// Products costs functions 
//get products cost value
function tep_get_products_costs($id) {
	$sel_products_costs = tep_db_query("SELECT labour_cost, overhead_cost, material_cost FROM products WHERE products_id='".$id."'");
	$rst_arr_opt = tep_db_fetch_array($sel_products_costs);	
	return $rst_arr_opt;
}

function tep_get_orders_products_costs($order_id) {
	$sel_products_costs = tep_db_query("SELECT * FROM orders_products_costs WHERE orders_id='".$order_id."'");
	if(tep_db_num_rows($sel_products_costs)==0) {
		return false;
	}
	
	$total_costs = 0;
	
	while($rst_arr_opt = tep_db_fetch_array($sel_products_costs)) {
		$material_cost = ($rst_arr_opt['material_cost'] * $rst_arr_opt['products_quantity']);
		$labour_cost = ($rst_arr_opt['labour_cost'] * $rst_arr_opt['products_quantity']);
		$overhead_cost = ($rst_arr_opt['overhead_cost'] * $rst_arr_opt['products_quantity']);
		$total_costs += ($material_cost + $labour_cost + $overhead_cost);
						
		$opt_arr[] = $rst_arr_opt;
	}
	
	$opt_arr['total_costs'] = $total_costs;
	
	return $opt_arr;
}

function tep_get_orders_products_name($orders_products_id, $orders_id) {
	$product_query = tep_db_query("select products_name from orders_products where orders_products_id = '" .(int)$orders_products_id . "' and orders_id = '" . (int)$orders_id ."'");
    $product = tep_db_fetch_array($product_query);        
	return $product['products_name'];
}


function tep_get_product_category($products_id) {

    $cID = '';
	
    $cat_count_sql = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $products_id . "'");

    $cat_count_data = tep_db_fetch_array($cat_count_sql);

    if ($cat_count_data['count'] == 1) {

      $categories = array();	  
      $cat_id_sql = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $products_id . "'");
      $cat_id_data = tep_db_fetch_array($cat_id_sql);
      $catID = $cat_id_data['categories_id'];
      $cat_id_desc = tep_db_query("select c.parent_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and c.categories_id = '" . $catID . "'");
      $cat_id_data = tep_db_fetch_array($cat_id_desc);     	  
	  $cat_info['name'] = $cat_id_data['categories_name'];	  
	  $cat_info['id'] = $catID;	  
	  $cat_info['parent_id'] = $cat_id_data['parent_id'];	  
	  $parent_qry = tep_db_query("SELECT categories_name FROM ". TABLE_CATEGORIES_DESCRIPTION . " WHERE categories_id = '" . $cat_info['parent_id'] . "'");	  
	  $parent_data = tep_db_fetch_array($parent_qry); 
	  $cat_info['parent_name'] = $parent_data['categories_name'];    

    }
	
    return $cat_info;

  }
 

////
// Return the manufacturers name 
// TABLES: manufacturers
  function tep_get_manufacturer_name($manufacturer_id) {  	
    $manufacturer_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturer_id . "'");
    $manufacturer = tep_db_fetch_array($manufacturer_query);	
    return $manufacturer['manufacturers_name'];
  }
  
 //get product manufacturer
   function tep_get_product_manufacturer($products_id) {  	
    $manufacturer_query = tep_db_query("select manufacturers_id from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
    $manufacturer = tep_db_fetch_array($manufacturer_query);		
	return $manufacturer['manufacturers_id'];
  }
   
  //get product model
  
  function tep_get_product_model($products_id) {  	
    $models_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
    $models = tep_db_fetch_array($models_query);		
	return $models['products_model'];
  }	
  
///// 

// Products costs functions eof

//Artwork resolution
//get artwork option high resolution image by artwork option id
function tep_get_resolution($option_id) {
	$sel_option= tep_db_query("SELECT * FROM artwork_option_resolution WHERE artwork_option_id='".$option_id."'");
	$option = tep_db_fetch_array($sel_option);	
	return $option;
}

//get artwork feedback by option
function tep_get_option_feedback($option_id) {
	$sel_option= tep_db_query("SELECT * FROM artwork_feedback WHERE artwork_option_id='".$option_id."'");
	$option = tep_db_fetch_array($sel_option);	
	return $option;
}


//Chart information

function get_customers_state_filter($zone_id) {
	switch($zone_id) {
		case 182:
			//Queensland
			$query = " and ( (a.entry_postcode <= 4999 and a.entry_postcode >= 4000) OR (a.entry_postcode <= 9999 and a.entry_postcode >= 9000) OR (a.entry_zone_id=182) )  ";		
			break;
		case 184:
			//New south wales
			$query = " and ( (a.entry_postcode <= 1999 and a.entry_postcode >=1000) OR (a.entry_postcode <= 2599 and a.entry_postcode >= 2000) OR (a.entry_postcode <= 2898 and a.entry_postcode >= 2619) OR (a.entry_postcode <= 2999 and a.entry_postcode >= 2921) OR (a.entry_zone_id=184) )  ";	
			break;
		case 185:
			//Victoria
			$query = " and ( (a.entry_postcode <= 3999 and a.entry_postcode >= 3000) OR (a.entry_postcode <= 8999 and a.entry_postcode >= 8000) OR (a.entry_zone_id=185) )  ";		
			break;
		case 186:
			//South australia
			$query = " and ( (a.entry_postcode <= 5799 and a.entry_postcode >= 5000) OR (a.entry_postcode <= 5999 and a.entry_postcode >= 5800) OR (a.entry_zone_id=186) )  ";		
			break;
		case 187:
			//Western austrlia
			$query = " and ( (a.entry_postcode <= 6797 and a.entry_postcode >= 6000) OR (a.entry_postcode <= 6999 and a.entry_postcode >= 6800) OR (a.entry_zone_id=187) )  ";		
			break;
		case 188:
			//ACT
			$query = " and ( (a.entry_postcode <= 299 and a.entry_postcode >= 200) OR (a.entry_postcode <= 2618 and a.entry_postcode >= 2600) OR (a.entry_postcode <= 2920 and a.entry_postcode >= 2900) OR (a.entry_zone_id=188) )  ";	
			break;
		case 189:
			//Northern Territory
			$query = " and ( (a.entry_postcode <= 899 and a.entry_postcode >= 800) OR (a.entry_postcode <= 999 and a.entry_postcode >= 900) OR (a.entry_zone_id=189) )  ";		
			break;
		case 190:
			//Tasmania
			$query = " and ( (a.entry_postcode <= 7799 and a.entry_postcode >= 7000) OR (a.entry_postcode <= 7999 and a.entry_postcode >= 7800) OR (a.entry_zone_id=190) )  ";		
			break;
		default:
			$query = " ";		
			break;
	}
	
	return $query;
}

//get in active customers by state
function tep_get_inactive_customers_by_state($zone_id) {
	$query_string = get_customers_state_filter($zone_id);	
		
	$customer_query = tep_db_query("SELECT distinct c.customers_id FROM customers c LEFT JOIN address_book a ON (c.customers_id = a.customers_id) WHERE c.customers_default_address_id = a.address_book_id AND c.customers_orders_count = 0 AND a.entry_country_id='13' ".$query_string . "GROUP BY c.customers_id");		
	return tep_db_num_rows($customer_query);
}

//get active customers by state
function tep_get_active_customers_by_state($zone_id) {
	$query_string = get_customers_state_filter($zone_id);	
	$customer_query = tep_db_query("SELECT distinct c.customers_id FROM customers c LEFT JOIN address_book a ON (c.customers_id = a.customers_id ) WHERE  c.customers_default_address_id = a.address_book_id AND c.customers_orders_count > 0 AND a.entry_country_id='13' ".$query_string . "GROUP BY c.customers_id");
	return tep_db_num_rows($customer_query);
}

/**************** Other city with in australia state *******************/
//get in active customers by state
function tep_get_inactive_customers_aus_other() {
	$customer_query = tep_db_query("SELECT distinct c.customers_id from customers c LEFT JOIN address_book a ON (c.customers_id = a.customers_id AND c.customers_default_address_id = a.address_book_id) WHERE c.customers_orders_count = 0 AND a.entry_country_id='13' AND a.entry_zone_id NOT IN(182,184,185,186,187,188,189,190) ");
	return tep_db_num_rows($customer_query);
}

//get active customers other city within australia
function tep_get_active_customers_aus_other() {
	$customer_query = tep_db_query("SELECT distinct c.customers_id from customers c LEFT JOIN address_book a ON (c.customers_id = a.customers_id AND c.customers_default_address_id = a.address_book_id) WHERE c.customers_orders_count > 0 AND a.entry_country_id='13' AND a.entry_zone_id NOT IN(182,184,185,186,187,188,189,190) ");
	return tep_db_num_rows($customer_query);
}

/**************** Other city with in australia statr *******************/

//get in active customers outside australia
function tep_get_inactive_customers_outside_aus() {
	$customer_query = tep_db_query("SELECT distinct c.customers_id from customers c LEFT JOIN address_book a ON (c.customers_id=a.customers_id AND c.customers_default_address_id = a.address_book_id) WHERE c.customers_orders_count = 0 AND a.entry_country_id!='13' ");	
	return tep_db_num_rows($customer_query);
}

//get active customers outside australia
function tep_get_active_customers_outside_aus() {
	$customer_query = tep_db_query("SELECT distinct c.customers_id from customers c LEFT JOIN address_book a ON (c.customers_id=a.customers_id AND c.customers_default_address_id = a.address_book_id) WHERE c.customers_orders_count > 0 AND a.entry_country_id!='13' ");	
	return tep_db_num_rows($customer_query);
}

//get all active customers
function tep_get_active_customers() {
	$customer_query = tep_db_query("SELECT distinct c.customers_id FROM customers c WHERE c.customers_orders_count > 0 ");
	return tep_db_num_rows($customer_query);
}

//get all in active customers
function tep_get_inactive_customers() {
	$customer_query = tep_db_query("SELECT distinct c.customers_id FROM customers c WHERE c.customers_orders_count = 0 ");
	return tep_db_num_rows($customer_query);
}

//get active customers this week
function tep_get_active_customers_this_week() {
	$timestamp = time();
	$last_sunday = date("Y-m-d H:i:s", strtotime("last sunday", $timestamp));
	$next_sunday = date("Y-m-d H:i:s", strtotime("next sunday", $timestamp));  
	$customer_query = tep_db_query("SELECT DISTINCT c.customers_id FROM customers c LEFT JOIN customers_info ci ON c.customers_id = ci.customers_info_id WHERE ci.customers_info_date_account_created > '".$last_sunday."' AND ci.customers_info_date_account_created < '".$next_sunday."' AND c.customers_orders_count > 0");
	return tep_db_num_rows($customer_query);
}

//get in active customers this week
function tep_get_inactive_customers_this_week() {
	$timestamp = time();
	$last_sunday = date("Y-m-d H:i:s", strtotime("last sunday", $timestamp));
	$next_sunday = date("Y-m-d H:i:s", strtotime("next sunday", $timestamp)); 	
	$customer_query = tep_db_query("SELECT DISTINCT c.customers_id FROM customers c LEFT JOIN customers_info ci ON c.customers_id = ci.customers_info_id WHERE ci.customers_info_date_account_created > '".$last_sunday."' AND ci.customers_info_date_account_created < '".$next_sunday."' AND c.customers_orders_count = 0");
	return tep_db_num_rows($customer_query);
}

//get customers zone id
function tep_get_customers_zone($customers_id) {	
	$customer_query = tep_db_query("SELECT a.entry_zone_id FROM customers c LEFT JOIN address_book a ON (c.customers_id = a.customers_id) WHERE c.customers_default_address_id = a.address_book_id AND c.customers_id = '".$customers_id."'");		
	$customers_arr = tep_db_fetch_array($customer_query);
	return $customers_arr['entry_zone_id'];
}

//get zone name by zone id
function tep_get_zonename($zone) {	
	$zone_query = tep_db_query("SELECT zone_name FROM zones WHERE zone_id = '".$zone."'");		
	$zone_arr = tep_db_fetch_array($zone_query);
	return $zone_arr['zone_name'];
}


//get customers revenue by state and duration
function tep_get_all_products_model() {
	
	$products_query = tep_db_query("SELECT DISTINCT products_model FROM `products` group by products_model");
	
	if(tep_db_num_rows($products_query)>0) {
		
		while($products_arr = tep_db_fetch_array($products_query)) {
						
			$products_code = explode("-",$products_arr["products_model"]);				
			if($products_code[0]=="" || $products_code[0]<1) { $pmodel = '000'; }
			else { $pmodel = $products_code[0]; }	
			
			$model_arr[$pmodel] = $pmodel; 		
		}		
	}
	
	return $model_arr;
	
}


  include(DIR_WS_FUNCTIONS . 'wysiwyg_editor.php'); 
  
  // Begin RMA Returns System
  include(DIR_WS_FUNCTIONS . 'refund_functions.php');
  // End RMA Returns System 
  

?>