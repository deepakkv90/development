<?php
/*
  $Id: download.php,v 1.0.0.0 2006/08/24 13:40:44 eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

class download {
  var $file_content, $download_flag;

  function process($current_file_id) {
    global $languages_id;
    
    $this->download_flag = false;
    $sql=("SELECT library_id, purchase_required, products_id 
                from " . TABLE_LIBRARY_PRODUCTS . " 
             WHERE library_id = '" . (int)$current_file_id . "' 
               and library_type = 'f' 
               and purchase_required = '1'");

    $pur_check = tep_db_query($sql);
    $purchase_products = "";
    while ($purchase = tep_db_fetch_array($pur_check)) {
      $purchase_products .= "'" . $purchase['products_id'] . "', ";
    }
    $purchase_products = substr($purchase_products, 0, strlen($purchase_products) - 2);
    $sql_file=("SELECT files_name, files_icon, file_date_created, files_download, file_availability 
                     from " . TABLE_LIBRARY_FILES . " 
                   WHERE files_status = '1' 
                     and files_id = '" . (int)$current_file_id . "'");

    $result = tep_db_query($sql_file);
    $file_list = tep_db_fetch_array($result);
    $file_desc = tep_db_fetch_array(tep_db_query("SELECT files_descriptive_name 
                                                                          from " . TABLE_LIBRARY_FILES_DESCRIPTION . " 
                                                                        WHERE files_id = '" . (int)$current_file_id . "'"));
    $pur_query_flag = false;
    if (isset($_SESSION['customer_id'])) {
      $current_customer_id = $_SESSION['customer_id'];
      $order_status_array = split(',', LIBRARY_DOWNLOAD_ORDER_STATUS_CONTROL);
      $orders_status = '';
      foreach ($order_status_array as $value) {
        $orders_status .= "'" . trim($value) . "', ";
      }
      $orders_status = substr($orders_status, 0, strlen($orders_status) - 2);
      if (FDM_REQ_PRODUCT_OPERATORS == 'or') {
        if (!tep_not_null($purchase_products)) {
          $purchase_products = "''";
        }
        $pur_query = tep_db_query("SELECT o.orders_id 
                                                    from " . TABLE_ORDERS . " o, 
                                                           " . TABLE_ORDERS_PRODUCTS . " op, 
                                                           " . TABLE_ORDERS_STATUS . " os 
                                                  WHERE o.orders_id = op.orders_id 
                                                    and o.orders_status = os.orders_status_id 
                                                    and os.language_id = '" . $languages_id . "' 
                                                    and os.orders_status_id in (" . $orders_status . ") 
                                                    and op.products_id in (" . $purchase_products . ") 
                                                    and o.customers_id = '" . $current_customer_id . "'");

        if (tep_db_num_rows($pur_query) > 0) {
          $pur_query_flag = true;
        } else {
          $pur_query_flag = false;
        }
      } else {
        $pur_query_flag = true;
         if (tep_not_null($purchase_products)) {
          $products_array = explode(', ', $purchase_products);      
          foreach ($products_array as $products_id) {
            $pur_query = tep_db_query("SELECT o.orders_id 
                                                        from " . TABLE_ORDERS . " o, 
                                                               " . TABLE_ORDERS_PRODUCTS . " op, 
                                                               " . TABLE_ORDERS_STATUS . " os 
                                                      WHERE o.orders_id = op.orders_id 
                                                        and o.orders_status = os.orders_status_id 
                                                        and os.language_id = '" . $languages_id . "' 
                                                        and os.orders_status_id in (" . $orders_status . ") 
                                                        and op.products_id = " . $products_id . " 
                                                        and o.customers_id = '" . $current_customer_id . "'");

            if (tep_db_num_rows($pur_query) == 0) {
              $pur_query_flag = false;
              break;
            }
          }
        }
      }
    }
    if ( (($file_list['file_availability'] == '1') && (!isset($_SESSION['customer_id']))) || ($file_list['file_availability'] == '2') )  {
      $download_str = '<a href="' . tep_href_link(FILENAME_DOWNLOAD_FILE, 'fileid=' . (int)$current_file_id, 'SSL') . '">' . tep_image('images/icon-dnload.gif', TEXT_BUTTON_DOWNLOAD) . '&nbsp;<span class="authText">' . TEXT_DOWNLOAD . '</span></a>';
    } else {
      $download_str = '<a href="' . tep_href_link(FILENAME_DOWNLOAD_FILE, 'fileid=' . (int)$current_file_id, 'NONSSL') . '">' . tep_image('images/icon-dnload.gif', TEXT_BUTTON_DOWNLOAD) . '&nbsp;<span class="authText">' . TEXT_DOWNLOAD . '</span></a>';
    }        
    $login_str = '<a href="javascript:document.forms.folder_files.submit();"><span class="errorText">' . TEXT_REQ_LOGIN . '</span></a>';
    $purchase_str = '<a href="' . tep_href_link(FILENAME_FILE_DETAIL,'file_id='.(int)$current_file_id) . '"><span class="errorText">' . TEXT_REQ_PURCHASE . '</span></a>';
    $unavailable_str = '<span class="errorText">' . TEXT_FILE_UNAVAILABLE . '</span>';
    if (tep_db_num_rows($result) > 0 && file_exists(DIR_FS_CATALOG . LIBRARY_DIRECTORY . $file_list['files_name'])) {
      if (tep_db_num_rows($pur_check) > 0 && !$pur_query_flag) {
        $this->file_content = $purchase_str;
      } else if (tep_db_num_rows($pur_check) > 0 && $pur_query_flag) {
          $this->file_content = $download_str;
          $this->download_flag = true;
      } else {
        if ( ($file_list['file_availability'] == '1') && (!isset($_SESSION['customer_id'])) ) {
          $this->file_content = $login_str;
        } else if ($file_list['file_availability'] == '2')  {
          $this->file_content = $purchase_str;
           $this->download_flag = false;
        } else {
           $this->file_content = $download_str;          
           $this->download_flag = true;
        }
      }
    } else {
      $this->file_content = $unavailable_str;
    }
  }
} 
?>