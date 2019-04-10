<?php
/*
  $Id: customers_groups.php,v 1.1.1.1 2008/06/08 23:38:02 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  function tep_set_customers_groups($group_id, $flag, $type) {
    $sql_data = array();
    switch ($type) {
      case 'access':
        $sql_data['group_access'] = $flag;
        break;
      case 'price':
        $sql_data['group_price'] = $flag;
        break;
      case 'status':
        $sql_data['group_status'] = $flag;
        break;
    }
    if (tep_not_null($sql_data)) {
      tep_db_perform(TABLE_CUSTOMERS_GROUPS, $sql_data, 'update', "customers_group_id = '" . $group_id . "'");
    }
  }
  
  require('includes/application_top.php');
  
    $cg_show_tax_array = array(array('id' => '1', 'text' => ENTRY_GROUP_SHOW_TAX_YES),
                              array('id' => '0', 'text' => ENTRY_GROUP_SHOW_TAX_NO));
    $cg_tax_exempt_array = array(array('id' => '1', 'text' => ENTRY_GROUP_TAX_EXEMPT_YES),
                              array('id' => '0', 'text' => ENTRY_GROUP_TAX_EXEMPT_NO));
  
    $action = (isset($_GET['action']) ? $_GET['action'] : '');
    if (tep_not_null($action)) {
      switch ($action) {
        case 'setflag':
          tep_set_customers_groups($_GET['cID'], $_GET['flag'], $_GET['type']);
          tep_redirect(tep_href_link(FILENAME_CUSTOMERS_GROUPS, 'cID=' . $_GET['cID'], 'NONSSL'));
          break;
        case 'update':
          $error = false;
          $customers_group_id = tep_db_prepare_input($_GET['cID']);
          $customers_group_name = tep_db_prepare_input($_POST['customers_group_name']);
          $customers_group_show_tax = tep_db_prepare_input($_POST['customers_group_show_tax']);
          $customers_group_tax_exempt = tep_db_prepare_input($_POST['customers_group_tax_exempt']);
          $group_template = tep_db_prepare_input($_POST['group_template']);
          $group_discount = tep_db_prepare_input($_POST['group_discount']);
          $group_discount_QTY_Breaks = tep_db_prepare_input($_POST['group_discount_QTY_Breaks']);
          $group_hide_show_prices = tep_db_prepare_input($_POST['group_hide_show_prices']);
          
          $group_payment_allowed = '';
          if (isset($_POST['payment_allowed']) && $_POST['group_payment_settings'] == '1') {
            while(list($key, $val) = each($_POST['payment_allowed'])) {
              if ($val == true) { 
                $group_payment_allowed .= tep_db_prepare_input($val).';'; 
              }
            } // end while
            $group_payment_allowed = substr($group_payment_allowed,0,strlen($group_payment_allowed)-1);
          } // end if ($_POST['payment_allowed'])
          
          $group_shipment_allowed = '';
          if (isset($_POST['shipping_allowed']) && $_POST['group_shipment_settings'] == '1') {
            while(list($key, $val) = each($_POST['shipping_allowed'])) {
              if ($val == true) { 
                $group_shipment_allowed .= tep_db_prepare_input($val).';'; 
              }
            } // end while
            $group_shipment_allowed = substr($group_shipment_allowed,0,strlen($group_shipment_allowed)-1);
          } // end if ($_POST['shipment_allowed'])
          
          tep_db_query("update " . TABLE_CUSTOMERS_GROUPS . " set customers_group_name='" . $customers_group_name . "', customers_group_show_tax = '" . $customers_group_show_tax . "', customers_group_tax_exempt = '" . $customers_group_tax_exempt . "', group_payment_allowed = '". $group_payment_allowed ."', group_shipment_allowed = '". $group_shipment_allowed ."',group_discount = '".$group_discount."',        group_discount_QTY_Breaks = '".$group_discount_QTY_Breaks."',group_hide_show_prices = '".$group_hide_show_prices."', group_template = '" . $group_template . "' where customers_group_id = " . tep_db_input($customers_group_id) );
          $languages = tep_get_languages();
          for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
            $language_id = $languages[$i]['id'];
            $public_description = isset($_POST['public_description'][$language_id]) ? tep_db_prepare_input($_POST['public_description'][$language_id]) : '';
            $approval_message = isset($_POST['approval_message'][$language_id]) ? tep_db_prepare_input($_POST['approval_message'][$language_id]) : '';
            $comments = isset($_POST['comments']) ? tep_db_prepare_input($_POST['comments']) : '';

            tep_db_query("update " . TABLE_CUSTOMER_GROUPS_DESCRIPTION . " set public_description = '" . $public_description . "', approval_message = '" . $approval_message . "', comments = '" . $comments . "' where customers_group_id = " . tep_db_input($customers_group_id) ." and language_id = '" . (int)$language_id . "'" );
          }
          tep_redirect(tep_href_link('customers_groups.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers_group_id));
          break;
        case 'deleteconfirm':
          $group_id = tep_db_prepare_input($_GET['cID']);
          tep_db_query("delete from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id= " . $group_id); 
          tep_db_query("delete from " . TABLE_CUSTOMER_GROUPS_DESCRIPTION . " where customers_group_id= " . $group_id);

          tep_db_query("delete from " . TABLE_PRODUCTS_GROUPS . " where customers_group_id= " . $group_id); 

          $customers_id_query = tep_db_query("select customers_id from " . TABLE_CUSTOMERS . " where customers_group_id=" . $group_id);
          while($customers_id = tep_db_fetch_array($customers_id_query)) {
              tep_db_query("UPDATE " . TABLE_CUSTOMERS . " set customers_group_id = '0' where customers_id=" . $customers_id['customers_id']);
          }     
          tep_redirect(tep_href_link('customers_groups.php', tep_get_all_get_params(array('cID', 'action')))); 
          break;
        case 'newconfirm' :
          $customers_group_name = tep_db_prepare_input($_POST['customers_group_name']);
          $customers_group_tax_exempt = tep_db_prepare_input($_POST['customers_group_tax_exempt']);
          $group_template = tep_db_prepare_input($_POST['group_template']);
          $group_payment_allowed = '';
          $customers_group_show_tax = tep_db_prepare_input($_POST['customers_group_show_tax']);
          $group_discount = tep_db_prepare_input($_POST['group_discount']);
          $group_discount_QTY_Breaks = tep_db_prepare_input($_POST['group_discount_QTY_Breaks']);
          $group_hide_show_prices = tep_db_prepare_input($_POST['group_hide_show_prices']);
          
          $group_payment_allowed = '';
          if (isset($_POST['payment_allowed'])) {
            while(list($key, $val) = each($_POST['payment_allowed'])) {
              if ($val == true) { 
                $group_payment_allowed .= tep_db_prepare_input($val).';'; 
              }
            } // end while
            $group_payment_allowed = substr($group_payment_allowed,0,strlen($group_payment_allowed)-1);
          } // end if ($_POST['payment_allowed'])

          $group_shipment_allowed = '';
          if (isset($_POST['shipping_allowed']) && isset($_POST['group_shipment_settings']) && $_POST['group_shipment_settings'] == '1') {
            while(list($key, $val) = each($_POST['shipping_allowed'])) {
              if ($val == true) { 
                $group_shipment_allowed .= tep_db_prepare_input($val).';'; 
              }
            } // end while
            $group_shipment_allowed = substr($group_shipment_allowed,0,strlen($group_shipment_allowed)-1);
          } // end if ($_POST['shipment_allowed'])

          $last_id_query = tep_db_query("select MAX(customers_group_id) as last_cg_id from " . TABLE_CUSTOMERS_GROUPS . "");
          $last_cg_id_inserted = tep_db_fetch_array($last_id_query);
          $new_cg_id = $last_cg_id_inserted['last_cg_id'] +1;
          tep_db_query("insert into " . TABLE_CUSTOMERS_GROUPS . " set customers_group_id = " . $new_cg_id . ", customers_group_name = '" . $customers_group_name . "', customers_group_show_tax = '" . $customers_group_show_tax . "', customers_group_tax_exempt = '" . $customers_group_tax_exempt . "', group_payment_allowed = '". $group_payment_allowed ."', group_shipment_allowed = '". $group_shipment_allowed ."',        group_discount = '".$group_discount."',        group_discount_QTY_Breaks = '".$group_discount_QTY_Breaks."',group_hide_show_prices = '".$group_hide_show_prices."', group_template = '" . $group_template . "'");
          
          $languages = tep_get_languages();
          for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
            $language_id = $languages[$i]['id'];
            $public_description = isset($_POST['public_description'][$language_id]) ? tep_db_prepare_input($_POST['public_description'][$language_id]) : '';
            $approval_message = isset($_POST['approval_message'][$language_id]) ? tep_db_prepare_input($_POST['approval_message'][$language_id]) : '';
            $comments = isset($_POST['comments']) ? tep_db_prepare_input($_POST['comments']) : '';

            tep_db_query("insert into " . TABLE_CUSTOMER_GROUPS_DESCRIPTION . " set customers_group_id = " . $new_cg_id . ", public_description = '" . $public_description . "', approval_message = '" . $approval_message . "', comments = '" . $comments . "', language_id = '" . (int)$language_id . "'");
          }

          // Populate the products_groups table
         
          $qry_product = "select products_id,products_price,products_tax_class_id from ".TABLE_PRODUCTS." p order by products_id";
          $res_product = tep_db_query($qry_product);
          while($data_product = tep_db_fetch_array($res_product)) {
            
            $class_id = $data_product["products_tax_class_id"];
            $tax_rate = tep_get_tax_rate_value($class_id);            
            $tmp_products_price = $data_product["products_price"];
            $tmp_customers_group_show_tax = $_POST['customers_group_show_tax'];
            $tmp_customers_group_tax_exempt = $_POST['customers_group_tax_exempt'];

            if($tmp_customers_group_show_tax == 1) {  //with tax
              if($tmp_customers_group_tax_exempt == 0) {
                $products_price = $tmp_products_price +($tmp_products_price * $tax_rate/100);
              } else if($tmp_customers_group_tax_exempt == 1) {
                $products_price = $tmp_products_price;
              }
            } else if($tmp_customers_group_show_tax == 0) { //without tax
              if($tmp_customers_group_tax_exempt == 0) {
                $products_price = $tmp_products_price +($tmp_products_price * $tax_rate/100);
              } else if($tmp_customers_group_tax_exempt == 1) {
                $products_price = $tmp_products_price;
              }
            }

            if($group_discount_QTY_Breaks == 0) {
              $products_price = $products_price - ($products_price * ($group_discount/100));
              $products_price1 = $products_price;
              $products_price2 = $products_price;
              $products_price3 = $products_price;
              $products_price4 = $products_price;
              $products_price5 = $products_price;
              $products_price6 = $products_price;
              $products_price7 = $products_price;
              $products_price8 = $products_price;
              $products_price9 = $products_price;
              $products_price10 = $products_price;
              $products_price11 = $products_price;
              $sql_data_array = array('customers_group_id' => $new_cg_id,
                                      'customers_group_price' => $products_price,
                                      'customers_group_price1' => $products_price1,
                                      'customers_group_price2' => $products_price2,
                                      'customers_group_price3' => $products_price3,
                                      'customers_group_price4' => $products_price4,
                                      'customers_group_price5' => $products_price5,
                                      'customers_group_price6' => $products_price6,
                                      'customers_group_price7' => $products_price7,
                                      'customers_group_price8' => $products_price8,
                                      'customers_group_price9' => $products_price9,
                                      'customers_group_price10' => $products_price10,
                                      'customers_group_price11' => $products_price11,
                                      'products_id' => $data_product["products_id"]);
            } else if($group_discount_QTY_Breaks == 1) {
              $str = tep_db_query("select * from ".TABLE_PRODUCTS." where products_id = '".$data_product["products_id"]."'"); 
              $res = tep_db_fetch_array($str);

              $products_price0 = $res['products_price'] - ($res['products_price']*($group_discount/100));
              $products_price1 = $res['products_price1'] - ($res['products_price1']*($group_discount/100));
              $products_price2 = $res['products_price2'] - ($res['products_price2']*($group_discount/100));
              $products_price3 = $res['products_price3'] - ($res['products_price3']*($group_discount/100));
              $products_price4 = $res['products_price4'] - ($res['products_price4']*($group_discount/100));
              $products_price5 = $res['products_price5'] - ($res['products_price5']*($group_discount/100));
              $products_price6 = $res['products_price6'] - ($res['products_price6']*($group_discount/100));
              $products_price7 = $res['products_price7'] - ($res['products_price7']*($group_discount/100));
              $products_price8 = $res['products_price8'] - ($res['products_price8']*($group_discount/100));
              $products_price9 = $res['products_price9'] - ($res['products_price9']*($group_discount/100));
              $products_price10 = $res['products_price10'] - ($res['products_price10']*($group_discount/100));
              $products_price11 = $res['products_price11'] - ($res['products_price11']*($group_discount/100));

              $sql_data_array = array(         
              'customers_group_id' => $new_cg_id,
              'customers_group_price' =>  $products_price0,
              'customers_group_price1' => $products_price1,
              'customers_group_price2' => $products_price2,
              'customers_group_price3' => $products_price3,
              'customers_group_price4' => $products_price4,
              'customers_group_price5' => $products_price5,
              'customers_group_price6' => $products_price6,
              'customers_group_price7' => $products_price7,
              'customers_group_price8' => $products_price8,
              'customers_group_price9' => $products_price9,
              'customers_group_price10' => $products_price10,
              'customers_group_price11' => $products_price11,
              'products_id' => $data_product["products_id"]);
            }
            tep_db_perform(TABLE_PRODUCTS_GROUPS, $sql_data_array);
          }
          tep_redirect(tep_href_link('customers_groups.php', tep_get_all_get_params(array('action'))));
          break;
      }
    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>

<!-- Tabs code -->
<script type="text/javascript" src="includes/javascript/tabpane/local/webfxlayout.js"></script>
<link type="text/css" rel="stylesheet" href="includes/javascript/tabpane/tab.webfx.css">
<style type="text/css">
.dynamic-tab-pane-control h2 {
  text-align: center;
  width:    auto;
}

.dynamic-tab-pane-control h2 a {
  display:  inline;
  width:    auto;
}

.dynamic-tab-pane-control a:hover {
  background: transparent;
}
</style>
<?php
include('includes/javascript/image_manager.js.php');
;?>
<script type="text/javascript" src="includes/javascript/tabpane/tabpane.js"></script>
<!-- End Tabs -->
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
<!-- body_text //-->
    <td class="page-container" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">

<?php
    if (isset($_GET['action']) && $_GET['action'] == 'edit') {
      $customers_groups_query = tep_db_query("select * from " . TABLE_CUSTOMERS_GROUPS . " c  where c.customers_group_id = '" . $_GET['cID'] . "'");
      $customers_groups = tep_db_fetch_array($customers_groups_query);
      $cInfo = new objectInfo($customers_groups);
      $payments_allowed = explode (";",$cInfo->group_payment_allowed);
      $shipment_allowed = explode (";",$cInfo->group_shipment_allowed);
      $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
      $ship_module_directory = DIR_FS_CATALOG_MODULES . 'shipping/';
      $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
      $directory_array = array();
      if ($dir = @dir($module_directory)) {
        while ($file = $dir->read()) {
          if (!is_dir($module_directory . $file)) {
            if (substr($file, strrpos($file, '.')) == $file_extension) {
              $directory_array[] = $file; // array of all the payment modules present in includes/modules/payment
            }
          }
        }
        sort($directory_array);
        $dir->close();
      }

      $ship_directory_array = array();
      if ($dir = @dir($ship_module_directory)) {
        while ($file = $dir->read()) {
          if (!is_dir($ship_module_directory . $file)) {
            if (substr($file, strrpos($file, '.')) == $file_extension) {
              $ship_directory_array[] = $file; // array of all shipping modules present in includes/modules/shipping
            }
          }
        }
        sort($ship_directory_array);
        $dir->close();
      }
?>

<script language="javascript"><!--
function check_form() {
  var error = 0;

  var customers_group_name = document.customers.customers_group_name.value;
  
  if (customers_group_name == "") {
    error_message = "<?php echo ERROR_CUSTOMERS_GROUP_NAME; ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>

    <tr><?php echo tep_draw_form('customers', 'customers_groups.php', tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();"'); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
      </tr>

      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_GROUPS_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('customers_group_name', $cInfo->customers_group_name, 'maxlength="32"', false); ?> &#160;&#160;<?php echo TEXT_CUSTOMERS_GROUPS_1;?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_GROUPS_TEMPLATE; ?></td>
            <td class="main"><?php echo cre_template_switch('group_template', isset($cInfo->group_template) ? $cInfo->group_template: ''); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_GROUPS_HIDE_SHOW_PRICES; ?></td>
             <td class="main" colspan = "2"><?php echo tep_draw_radio_field('group_hide_show_prices', '0', (($cInfo->group_hide_show_prices == 0)? 'Selected' : '' ), '0') . '&nbsp;&nbsp;' . ENTRY_GROUP_HIDE_SHOW_PRICES_OPTION1 . '&nbsp;&nbsp;' . tep_draw_radio_field('group_hide_show_prices', '1', (($cInfo->group_hide_show_prices == 1)? 'Selected' : '' ), '0') . '&nbsp;&nbsp;' . ENTRY_GROUP_HIDE_SHOW_PRICES_OPTION2 ; ?></td>
          </tr>
          
          <tr>
            <td class="main"><?php echo ENTRY_GROUP_SHOW_TAX; ?></td>
            <td class="main"><?php echo tep_draw_pull_down_menu('customers_group_show_tax', $cg_show_tax_array, (($cInfo->customers_group_show_tax == '1') ? '1' : '0')); ?> &#160;&#160;<?php echo TEXT_CUSTOMERS_GROUPS_2;?></td>
          </tr>
          <tr>
            <td class="main">&#160;</td>
            <td class="main" style="line-height: 2"><?php echo TEXT_CUSTOMERS_GROUPS_3;?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_GROUP_TAX_EXEMPT; ?></td>
            <td class="main"><?php
            echo tep_draw_pull_down_menu('customers_group_tax_exempt', $cg_tax_exempt_array, (($cInfo->customers_group_tax_exempt == '1') ? '1' : '0')); ?></td>
          </tr></table>
        </td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php include_once(DIR_WS_LANGUAGES . $language . '/modules.php');
          echo HEADING_TITLE_MODULES_PAYMENT; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr bgcolor="#DEE4E8">
            <td class="main"><?php echo tep_draw_radio_field('group_payment_settings', '1', (tep_not_null($cInfo->group_payment_allowed)? true : false )) . '&nbsp;&nbsp;' . ENTRY_GROUP_PAYMENT_SET . '&nbsp;&nbsp;' . tep_draw_radio_field('group_payment_settings', '0', (tep_not_null($cInfo->group_payment_allowed)? false : true )) . '&nbsp;&nbsp;' . ENTRY_GROUP_PAYMENT_DEFAULT ; ?></td>
          </tr>
<?php
    $module_active = explode (";",MODULE_PAYMENT_INSTALLED);
    $installed_modules = array();
    for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++) {
    $file = $directory_array[$i];
    if (in_array ($directory_array[$i], $module_active)) {
      include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/payment/' . $file);
      include($module_directory . $file);

     $class = substr($file, 0, strrpos($file, '.'));
     if (tep_class_exists($class)) {
       $module = new $class;
       if ($module->check() > 0) {
         $installed_modules[] = $file;
       }
     } // end if (tep_class_exists($class))
?>
     <tr>
            <td class="main"><?php echo tep_draw_checkbox_field('payment_allowed[' . $i . ']', $module->code.".php" , (in_array ($module->code.".php", $payments_allowed)) ?  1 : 0); ?>&#160;&#160;<?php echo $module->title; ?></td>
           </tr>
<?php
  } // end if (in_array ($directory_array[$i], $module_active)) 
 } // end for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++)
?>
     <tr>
            <td class="main" style="padding-left: 30px; padding-right: 10px; padding-top: 10px;"><?php echo ENTRY_PAYMENT_SET_EXPLAIN ?></td>
           </tr>
        </table>
       </td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo HEADING_TITLE_MODULES_SHIPPING; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr bgcolor="#DEE4E8">
            <td class="main"><?php echo tep_draw_radio_field('group_shipment_settings', '1', (tep_not_null($cInfo->group_shipment_allowed) ? true : false )) . '&nbsp;&nbsp;' . ENTRY_GROUP_SHIPPING_SET . '&nbsp;&nbsp;' . tep_draw_radio_field('group_shipment_settings', '0', (tep_not_null($cInfo->group_shipment_allowed) ? false : true )) . '&nbsp;&nbsp;' . ENTRY_GROUP_SHIPPING_DEFAULT ; ?></td>
          </tr>
<?php
    $ship_module_active = explode (";",MODULE_SHIPPING_INSTALLED);
    $installed_shipping_modules = array();
    for ($i = 0, $n = sizeof($ship_directory_array); $i < $n; $i++) {
    $file = $ship_directory_array[$i];
    if (in_array ($ship_directory_array[$i], $ship_module_active)) {
      include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/shipping/' . $file);
      include($ship_module_directory . $file);

     $ship_class = substr($file, 0, strrpos($file, '.'));
     if (tep_class_exists($ship_class)) {
       $ship_module = new $ship_class;
       if ($ship_module->check() > 0) {
         $installed_shipping_modules[] = $file;
       }
     } // end if (tep_class_exists($ship_class))
?>
          <tr>
            <td class="main"><?php echo tep_draw_checkbox_field('shipping_allowed[' . $i . ']', $ship_module->code.".php" , (in_array ($ship_module->code.".php", $shipment_allowed)) ?  1 : 0); ?>&#160;&#160;<?php echo $ship_module->title; ?></td>
          </tr>
<?php
  } // end if (in_array ($ship_directory_array[$i], $ship_module_active)) 
 } // end for ($i = 0, $n = sizeof($ship_directory_array); $i < $n; $i++)
?>
          <tr>
            <td class="main" style="padding-left: 30px; padding-right: 10px; padding-top: 10px;"><?php echo ENTRY_SHIPPING_SET_EXPLAIN ?></td>
          </tr>
        </table>
       </td>
      </tr>      
      <?php      
      $str1 = tep_db_query("select * from " . TABLE_CUSTOMER_GROUPS_DESCRIPTION . " cgd  where cgd.customers_group_id = '" . $_GET['cID'] . "'");
      while($res1 = tep_db_fetch_array($str1)) {  
        $arr['public_description'][$res1['language_id']] = $res1['public_description'];
        $arr['approval_message'][$res1['language_id']] = $res1['approval_message'];
        $arr['comments'] = $res1['comments'];
      }
      ?>
      <tr>
        <td class="formAreaTitle"><?php echo HEADING_TITLE_ADDITIONAL_INFO; ?></td>
      </tr>
      <tr>
        <td class="formArea">
          
                <table border="0" cellspacing="5" cellpadding="4" summary="Title table" width="100%" style="padding-left: 30px; padding-right: 10px; padding-top: 10px;">
                  <tr valign="top">
                    <td class="main">
                      <?php echo ENTRY_COMMENTS ; ?>
                    </td>
                  </tr>
                  <tr>
                    <td class="main">
                      <?php echo tep_draw_textarea_field('comments', 'soft', '100', '3', isset($arr['comments']) ? $arr['comments'] : '','style="width: 100%"'); ?>
                    </td>
                  </tr>
                </table>
                <?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>
                <!-- <fieldset> -->
                <table border="0" cellspacing="0" cellpadding="2" width="100%" align="center" >                  
                  <tr>
                    <td class="main" valign="top" width="100%" style="padding-left: 30px; padding-right: 10px; padding-top: 10px;" >
                      <div class="tab-pane" id="tabPane1">
                        <script type="text/javascript">
                          tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
                        </script>
                        <?php
                        for ($i=0; $i<sizeof($languages); $i++) {
                        ?>
                        <div class="tab-page" id="<?php echo $languages[$i]['name'];?>">
                          <h2 class="tab">
                            <nobr>
                              <?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'align="absmiddle" style="height:16px; width:30px;"') . '&nbsp;' .$languages[$i]['name'];?>
                            </nobr>
                          </h2>
                          <script type="text/javascript">
                            tp1.addTabPage( document.getElementById( "<?php echo $languages[$i]['name'];?>" ) );
                          </script>
                          <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="tab table">
                            <tr>
                              <td valign="top" width="100%">
                                <table width="100%"  border="0" cellspacing="4" cellpadding="0" summary="description tabe" >
                                  <tr valign="top">
                                    <td class="main"><?php echo ENTRY_PUBLIC_DESCRIPTION; ?></td>
                                  </tr>
                                  <tr>
                                    <td style="padding-left: 30px; padding-right: 10px; padding-top: 10px;"><?php echo tep_draw_textarea_field('public_description[' . $languages[$i]['id'] . ']', 'soft', '70', '3', isset($arr['public_description'][$languages[$i]['id']]) ? $arr['public_description'][$languages[$i]['id']] : '', 'style="width: 100%;" mce_editable="true"'); ?> </td>
                                  </tr>
                                </table> 

                                <table width="100%"  border="0" cellspacing="4" cellpadding="0" summary="description tabe">
                                  <tr valign="top">
                                    <td class="main"><?php echo ENTRY_APPROVAL_MESSAGE; ?></td>
                                  </tr>
                                  <tr>
                                    <td style="padding-left: 30px; padding-right: 10px; padding-top: 10px;"><?php echo tep_draw_textarea_field('approval_message[' . $languages[$i]['id'] . ']', 'soft', '70', '3', isset($arr['approval_message'][$languages[$i]['id']]) ? $arr['approval_message'][$languages[$i]['id']] : '','style="width: 100%"'); ?></td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>
                        </div>
                        <?php
                        }
                        ?>
                      </div>
                      <script type="text/javascript">
                      //<![CDATA[
                      setupAllTabs();
                      //]]>
                      </script>
                    </td>
                  </tr>
                </table>
              <!-- </fieldset> -->
        </td>
      </tr>
      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo '<a href="' . tep_href_link('customers_groups.php', tep_get_all_get_params(array('action','cID'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
      </tr>
      </form>

    <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '70'); ?></td>
      </tr>

<?php
  } else if( isset($_GET['action']) &&  $_GET['action'] == 'new') {   
?>
<script language="javascript"><!--
function check_form() {
  var error = 0;

  var customers_group_name = document.customers.customers_group_name.value;
  
  if (customers_group_name == "") {
    error_message = "<?php echo ERROR_CUSTOMERS_GROUP_NAME; ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr><?php echo tep_draw_form('customers', 'customers_groups.php', tep_get_all_get_params(array('action')) . 'action=newconfirm', 'post', 'onSubmit="return check_form();"'); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_GROUPS_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('customers_group_name', '', 'maxlength="32"', false); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_GROUPS_TEMPLATE; ?></td>
            <td class="main"><?php echo cre_template_switch('group_template'); ?></td>
          </tr>

          <tr>
            <td class="main"><?php echo ENTRY_GROUPS_HIDE_SHOW_PRICES;            
            ?></td>
             <td class="main" colspan = "2"><?php echo tep_draw_radio_field('group_hide_show_prices', '0') . '&nbsp;' . ENTRY_GROUP_HIDE_SHOW_PRICES_OPTION1 . '&nbsp;&nbsp;' . tep_draw_radio_field('group_hide_show_prices', '1','Selected') . '&nbsp;' . ENTRY_GROUP_HIDE_SHOW_PRICES_OPTION2 ; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_GROUP_SHOW_TAX; ?></td>
            <td class="main"><?php
            echo tep_draw_pull_down_menu('customers_group_show_tax', $cg_show_tax_array, '1'); ?>  <?php echo TEXT_CUSTOMERS_GROUPS_2;?></td>
          </tr>
          <tr>
            <td class="main">&#160;</td>
            <td class="main" style="line-height: 2"> <?php echo TEXT_CUSTOMERS_GROUPS_3;?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_GROUP_TAX_EXEMPT; ?></td>
            <td class="main"><?php
            echo tep_draw_pull_down_menu('customers_group_tax_exempt', $cg_tax_exempt_array, '0'); ?></td>
          </tr>
   </table>
  </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php include_once(DIR_WS_LANGUAGES . $language . '/modules.php');
  echo HEADING_TITLE_MODULES_PAYMENT; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
     <tr bgcolor="#DEE4E8">
            <td class="main"><?php echo tep_draw_radio_field('group_payment_settings', '1', false, '0') . '&nbsp;&nbsp;' . ENTRY_GROUP_PAYMENT_SET . '&nbsp;&nbsp;' . tep_draw_radio_field('group_payment_settings', '0', true, '0') . '&nbsp;&nbsp;' . ENTRY_GROUP_PAYMENT_DEFAULT ; ?></td>
          </tr>
<?php
  $module_active = explode (";",MODULE_PAYMENT_INSTALLED);
  $ship_module_active = explode (";",MODULE_SHIPPING_INSTALLED);
  $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
  $ship_module_directory = DIR_FS_CATALOG_MODULES . 'shipping/';

// code slightly adapted from admin/modules.php
  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
  $directory_array = array();
  if ($dir = @dir($module_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $directory_array[] = $file; // array of all the payment modules present in includes/modules/payment
        }
      }
    }
    $dir->close();
  } // end if ($dir = @dir($module_directory))

   $ship_directory_array = array();
   if ($dir = @dir($ship_module_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($ship_module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $ship_directory_array[] = $file; // array of all shipping modules present in includes/modules/shipping
        }
      }
    }
    sort($ship_directory_array);
    $dir->close();
  }
    $installed_modules = array();
    for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++) {
    $file = $directory_array[$i];
    if (in_array ($directory_array[$i], $module_active)) {
      include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/payment/' . $file);
      include($module_directory . $file);

     $class = substr($file, 0, strrpos($file, '.'));
     if (tep_class_exists($class)) {
       $module = new $class;
       if ($module->check() > 0) {
         $installed_modules[] = array('file_name' => $file, 'title' => $module->title);
       }
     } // end if (tep_class_exists($class))
   } // end if (in_array ($directory_array[$i], $module_active)) 
 } // end for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++)

  for ($y = 0; $y < sizeof($installed_modules) ; $y++) {
?>
     <tr>
            <td class="main"><?php echo tep_draw_checkbox_field('payment_allowed[' . $y . ']', $installed_modules[$y]['file_name'] , 0); ?>&#160;&#160;<?php echo $installed_modules[$y]['title']; ?></td>
           </tr>
<?php
 } // end for ($y = 0; $y < sizeof($installed_modules) ; $y++)
?>
     <tr>
            <td class="main" style="padding-left: 30px; padding-right: 10px; padding-top: 10px;"><?php echo ENTRY_PAYMENT_SET_EXPLAIN ?></td>
           </tr>
        </table>
       </td>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo HEADING_TITLE_MODULES_SHIPPING; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
     <tr bgcolor="#DEE4E8">
            <td class="main"><?php echo tep_draw_radio_field('group_shipment_settings', '1', false) . '&nbsp;&nbsp;' . ENTRY_GROUP_SHIPPING_SET . '&nbsp;&nbsp;' . tep_draw_radio_field('group_shipment_settings', '0', true) . '&nbsp;&nbsp;' . ENTRY_GROUP_SHIPPING_DEFAULT ; ?></td>
          </tr>
<?php
    $ship_module_active = explode (";",MODULE_SHIPPING_INSTALLED);
    $installed_shipping_modules = array();
    for ($i = 0, $n = sizeof($ship_directory_array); $i < $n; $i++) {
    $file = $ship_directory_array[$i];
    if (in_array ($ship_directory_array[$i], $ship_module_active)) {
      include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/shipping/' . $file);
      include($ship_module_directory . $file);

     $ship_class = substr($file, 0, strrpos($file, '.'));
     if (tep_class_exists($ship_class)) {
       $ship_module = new $ship_class;
       if ($ship_module->check() > 0) {
         $installed_shipping_modules[] = array('file_name' => $file, 'title' => $ship_module->title);
       }
     } // end if (tep_class_exists($ship_class))
   } // end if (in_array ($ship_directory_array[$i], $ship_module_active))
 } // end for ($i = 0, $n = sizeof($ship_directory_array); $i < $n; $i++)

 for ($y = 0; $y < sizeof($installed_shipping_modules) ; $y++) {
?>
          <tr>
            <td class="main"><?php echo tep_draw_checkbox_field('shipping_allowed[' . $y . ']', $installed_shipping_modules[$y]['file_name'] , 0); ?>&#160;&#160;<?php echo $installed_shipping_modules[$y]['title']; ?></td>
          </tr>
<?php
  } // end for ($y = 0; $y < sizeof($installed_modules) ; $y++) 
?>
          <tr>
            <td class="main" style="padding-left: 30px; padding-right: 10px; padding-top: 10px;"><?php echo ENTRY_SHIPPING_SET_EXPLAIN ?></td>
          </tr>
        </table>
       </td>
      </tr>
      </tr>
      <!-- /***************** GSR Start *******************/ -->
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo HEADING_TITLE_ADDITIONAL_INFO; ?></td>
      </tr>
      <tr>
        <td class="formArea">
          
                <table border="0" cellspacing="5" cellpadding="4" summary="Title table" width="100%" style="padding-left: 30px; padding-right: 10px; padding-top: 10px;">
                  <tr valign="top">
                    <td class="main">
                      <?php echo ENTRY_COMMENTS ; ?>
                    </td>
                  </tr>
                  <tr>
                    <td class="main">
                      <?php echo tep_draw_textarea_field('comments', 'soft', '100', '3', '','style="width: 100%"'); ?>
                    </td>
                  </tr>
                </table>


                

<?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>

                <!-- <fieldset> -->

                <table border="0" cellspacing="0" cellpadding="2" width="100%" align="center" >
                  <!-- <tr>
                    <td class="main">
                      <?php echo ENTRY_ADMIN_COMMENTS;?>
                    </td>
                  </tr> -->
                  <tr>
                    <td class="main" valign="top" width="100%" style="padding-left: 30px; padding-right: 10px; padding-top: 10px;" >
                      <div class="tab-pane" id="tabPane1">
                        <script type="text/javascript">
                          tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
                        </script>
                        <?php
                        for ($i=0; $i<sizeof($languages); $i++) {
                        ?>
                        <div class="tab-page" id="<?php echo $languages[$i]['name'];?>">
                          <h2 class="tab">
                            <nobr>
                              <?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'align="absmiddle" style="height:16px; width:30px;"') . '&nbsp;' .$languages[$i]['name'];?>
                            </nobr>
                          </h2>
                          <script type="text/javascript">
                            tp1.addTabPage( document.getElementById( "<?php echo $languages[$i]['name'];?>" ) );
                          </script>
                          <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="tab table">
                            <tr>
                              <td valign="top" width="100%">

                                <!-- <table border="0" cellspacing="5" cellpadding="4" summary="Title table" width="100%">
                                  <tr valign="top">
                                    <td class="main">
                                      <?php echo ENTRY_COMMENTS ; ?>
                                    </td>
                                    <td class="main">
                                      <?php echo tep_draw_textarea_field('comments[' . $languages[$i]['id'] . ']', 'soft', '100', '3', '','style="width: 100%"'); ?>
                                    </td>
                                  </tr>
                                </table> -->

                                <table width="100%"  border="0" cellspacing="4" cellpadding="0" summary="description tabe" >
                                  <tr valign="top">
                                    <td class="main"><?php echo ENTRY_PUBLIC_DESCRIPTION; ?></td>
                                  </tr>
                                  <tr>
                                    <td style="padding-left: 30px; padding-right: 10px; padding-top: 10px;"><?php echo tep_draw_textarea_field('public_description[' . $languages[$i]['id'] . ']', 'soft', '70', '3', '', 'style="width: 100%;" mce_editable="true"'); ?> </td>
                                  </tr>
                                </table> 

                                <table width="100%"  border="0" cellspacing="4" cellpadding="0" summary="description tabe">
                                  <tr valign="top">
                                    <td class="main"><?php echo ENTRY_APPROVAL_MESSAGE; ?></td>
                                  </tr>
                                  <tr>
                                    <td style="padding-left: 30px; padding-right: 10px; padding-top: 10px;"><?php echo tep_draw_textarea_field('approval_message[' . $languages[$i]['id'] . ']', 'soft', '70', '3', '','style="width: 100%"'); ?></td>
                                  </tr>
                                </table>



                              </td>
                            </tr>
                          </table>

                          


                        </div>
                        <?php
                        }
                        ?>
                      </div>
                      <script type="text/javascript">
                      //<![CDATA[
                      setupAllTabs();
                      //]]>
                      </script>
                    </td>
                  </tr>
                </table>
              <!-- </fieldset> -->

        </td>
      </tr>
      <!-- /************** GSR END **********************/ -->
             <!-- /************** GSR Start **********************/ -->
<tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
      </tr>
      <tr>
              <td><fieldset><legend><?php echo HEADING_TITLE_GROUP_DISCOUNT; ?></legend>
                <table width="90%" border="0" cellspacing="3" cellpadding="3" align="center">
                  <tr valign="top">
                    <td width="58%" class="main">&nbsp;
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="main" width = "20%">
                            <?php echo ENTRY_GROUPS_DISCOUNT;?>
                          </td>
                          <td class="main" >
                            <?php echo tep_draw_input_field('group_discount', '', 'maxlength="32" size = "10"', false); ?><b>%</b>
                          </td>
                        </tr>
                        <tr><td height="40" colspan="2"><?php tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
                        <tr>
                          <td colspan="2" class="main" >
                            <?php echo TEXT_GROUP_TOOL ;?>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td><fieldset><legend><?php echo TEXT_PUSH_SAVE_OPTION;?></legend>
                      <table width="95%" border="0" cellspacing="2" cellpadding="2">


                        <tr>
                          <td>
                            <?php echo tep_draw_radio_field('group_discount_QTY_Breaks', '0', false, '0');?>
                          </td>
                          <td>
                            <?php echo ENTRY_GROUP_DISCOUNT_OPTION1;?>
                          </td>
                        </tr>

                        <tr>
                          <td>
                            <?php echo tep_draw_radio_field('group_discount_QTY_Breaks', '1', true, '0');?>
                          </td>
                          <td>
                            <?php echo ENTRY_GROUP_DISCOUNT_OPTION2;?>
                          </td>
                        </tr>

                        <tr>
                          <td colspan = "2">                            
                            <?php echo tep_image('images/icons/warning.gif',TEXT_PUSH_WARNING) . '&nbsp;<font size="1">'.TEXT_PUSH_WARNING.'</font>';?>
                          </td>
                        </tr>

                      </table>                      
                    </fieldset></td>
                  </tr>
                </table>
              </fieldset></td>
            </tr>

      <!-- /************** GSR END **********************/ -->
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo '<a href="' . tep_href_link('customers_groups.php', tep_get_all_get_params(array('action','cID'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
      </tr>
      </form>
<?php 
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><?php echo tep_draw_form('search', 'customers_groups.php', '', 'get'); ?>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td>
          </form></tr>
        </table></td>
      </tr>
      <tr>

          <?php
          $listing = (isset($listing) ? $listing : '');
          switch ($listing) {
              case "group":
              $order = "g.customers_group_name";
              break;
              case "group-desc":
              $order = "g.customers_group_name DESC";
              break;
              default:
              $order = "g.customers_group_id ASC";
          }
          ?>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" style="line-height: 16px; vertical-align: middle;">
                  <?php echo TABLE_HEADING_NAME; ?>
                  <a href="<?php echo "$PHP_SELF?listing=group"; ?>"><?php echo tep_icon_submit('ic_up.gif', ' Sort ' . TABLE_HEADING_NAME . ' --> A-B-C From Top '); ?></a><a href="<?php echo "$PHP_SELF?listing=group-desc"; ?>"><?php echo tep_icon_submit('ic_down.gif', ' Sort ' . TABLE_HEADING_NAME . ' --> Z-X-Y From Top '); ?></a>
                </td>
                <td class="dataTableHeadingContent" valign="bottom"><?php echo TABLE_HEADING_ID; ?></td>
                <td class="dataTableHeadingContent" valign="bottom"><?php echo TABLE_HEADING_GROUPS_TEMPLATE; ?></td>
                <td class="dataTableHeadingContent" align="center" valign="bottom"><?php echo TABLE_HEADING_GROUPS_ACCESS; ?></td>
                <td class="dataTableHeadingContent" align="center" valign="bottom"><?php echo TABLE_HEADING_GROUPS_PRICE; ?></td>
                <td class="dataTableHeadingContent" align="center" valign="bottom"><?php echo TABLE_HEADING_GROUPS_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right" valign="bottom"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
         </tr>

<?php
    $search = '';
    if ( isset($_GET['search']) && (tep_not_null($_GET['search'])) ) {
      $keywords = tep_db_input(tep_db_prepare_input($_GET['search']));
      $search = "where g.customers_group_name like '%" . $keywords . "%'";
    }

    $customers_groups_query_raw = "select g.customers_group_id, g.customers_group_name, g.group_template, g.group_access, g.group_price, g.group_status from " . TABLE_CUSTOMERS_GROUPS . " g  " . $search . " order by $order";
    $customers_groups_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_groups_query_raw, $customers_groups_query_numrows);
    $customers_groups_query = tep_db_query($customers_groups_query_raw);

    while ($customers_groups = tep_db_fetch_array($customers_groups_query)) {
      $info_query = tep_db_query("select customers_info_date_account_created as date_account_created, customers_info_date_account_last_modified as date_account_last_modified, customers_info_date_of_last_logon as date_last_logon, customers_info_number_of_logons as number_of_logons from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customers_groups['customers_group_id'] . "'");
      $info = tep_db_fetch_array($info_query);

      if ((!isset($_GET['cID']) || (@$_GET['cID'] == $customers_groups['customers_group_id'])) && (!isset($cInfo))) {
        $cInfo = new objectInfo($customers_groups);
      }

      if ( isset($cInfo) && (is_object($cInfo)) && ($customers_groups['customers_group_id'] == $cInfo->customers_group_id) ) {
        echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link('customers_groups.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_group_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link('customers_groups.php', tep_get_all_get_params(array('cID')) . 'cID=' . $customers_groups['customers_group_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $customers_groups['customers_group_name']; ?></td>
                <td class="dataTableContent"><?php echo $customers_groups['customers_group_id']; ?></td>
                <td class="dataTableContent"><?php echo $customers_groups['group_template']; ?></td>
                <td class="dataTableContent" align="center">
<?php 
      if ($customers_groups['group_access'] == '1') {
        echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_GROUPS, 'action=setflag&flag=0&type=access&cID=' . $customers_groups['customers_group_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'key.png', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_GROUPS, 'action=setflag&flag=1&type=access&cID=' . $customers_groups['customers_group_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'key-off.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>';
      }
?></td>
                <td class="dataTableContent" align="center">
<?php 
      if ($customers_groups['group_price'] == '1') {
        echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_GROUPS, 'action=setflag&flag=0&type=price&cID=' . $customers_groups['customers_group_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'coins.png', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_GROUPS, 'action=setflag&flag=1&type=price&cID=' . $customers_groups['customers_group_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'coins-off.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>';
      }
?></td>
                <td class="dataTableContent" align="center">
<?php 
      if ($customers_groups['group_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'accept.png', IMAGE_ICON_STATUS_GREEN, 16, 16) . '&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS_GROUPS, 'action=setflag&flag=0&type=status&cID=' . $customers_groups['customers_group_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'cancel-off.png', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_GROUPS, 'action=setflag&flag=1&type=status&cID=' . $customers_groups['customers_group_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'accept-off.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>&nbsp;' . tep_image(DIR_WS_IMAGES . 'cancel.png', IMAGE_ICON_STATUS_RED, 16, 16);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if ( (isset($cInfo)) && (is_object($cInfo)) && ($customers_groups['customers_group_id'] == $cInfo->customers_group_id) ) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link('customers_groups.php', tep_get_all_get_params(array('cID')) . 'cID=' . $customers_groups['customers_group_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $customers_groups_split->display_count($customers_groups_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_GROUPS); ?></td>
                    <td class="smallText" align="right"><?php echo $customers_groups_split->display_links($customers_groups_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
                  </tr>
<?php
    if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link('customers_groups.php') . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                  </tr>
<?php
    } else {
?>
            <tr>
                    <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . tep_href_link('customers_groups.php', 'page=' . $_GET['page'] . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  switch ($action) {
    case 'confirm':
        if ($_GET['cID'] != '0') {
            $heading[] = array('text' => TEXT_INFO_HEADING_DELETE_GROUP);
            $contents = array('form' => tep_draw_form('customers_groups', 'customers_groups.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_group_id . '&action=deleteconfirm'));
            $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_group_name . ' </b>');
            if (isset($cInfo->number_of_reviews) && $cInfo->number_of_reviews > 0) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_reviews', 'on', true) . ' ' . sprintf(TEXT_DELETE_REVIEWS, $cInfo->number_of_reviews));
            $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link('customers_groups.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_group_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        } else {
            $heading[] = array('text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'&nbsp;<br><b>' . TEXT_INFO_HEADING_DELETE_GROUP . '</b>');
            $contents[] = array('text' => TEXT_CUSTOMERS_GROUPS_4 . $cInfo->customers_group_name . ' </b>');
        }
      break;
    default:
      if (is_object($cInfo)) {
        $heading[] = array('text' => $cInfo->customers_group_name);
        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link('customers_groups.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_group_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a> <a href="' . tep_href_link('customers_groups.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_group_id . '&action=confirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');

      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
