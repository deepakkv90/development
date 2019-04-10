<?php
/*
  $Id: products_attributes.php,v 1.3 2004/03/16 22:36:34 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  $languages = tep_get_languages();
  
  $page_info = 'option_page=' . (isset($_GET['option_page']) ? $_GET['option_page'] : '1') . '&value_page=' . (isset($_GET['value_page']) ? $_GET['value_page'] : '1') . '&attribute_page=' . (isset($_GET['attribute_page']) ? $_GET['attribute_page'] : '1');
    
  if (isset($_GET['action'])) {
    switch($_GET['action']) {
      case 'add_product_options':
     
          $options_type = $_POST['options_type'];
          $options_length= (int)$_POST['options_length'];
          $products_options_sort_order = (int)$_POST['products_options_sort_order'];
       tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, products_options_sort_order, options_type, options_length) values ('" . $_POST['products_options_id'] . "', '" . $products_options_sort_order . "', '" . $options_type . "', '" . $options_length . "')");

      
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $language_id = $languages[$i]['id'];
          $products_options_name = isset($_POST['option_name'][$language_id]) ? $_POST['option_name'][$language_id] : '';
          $products_options_instruct = isset($_POST['products_options_instruct'][$language_id]) ? $_POST['products_options_instruct'][$language_id] : '';
     tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_TEXT . " (products_options_text_id, products_options_name, language_id, products_options_instruct) values ('" . $_POST['products_options_id'] . "', '" . tep_db_input($products_options_name) . "', '" . $language_id . "', '" . tep_db_input($products_options_instruct) . "')");
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'add_product_option_values':
        $value_name_array = $_POST['value_name'];
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $value_name = tep_db_prepare_input($value_name_array[$languages[$i]['id']]);
		   $value_id  = $languages[$i]['id'];
          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . $_POST['value_id'] . "', '" . $languages[$i]['id'] . "', '" . tep_db_input($value_name) . "')");
        }
          if(@$_FILES['attr_file']['error'] === 0){
            $target_path = DIR_FS_CATALOG . DIR_WS_IMAGES . "product_attributes" . DIRECTORY_SEPARATOR;
            $fname = split("\.", $_FILES['attr_file']['name']);
            $fname = 'attribute_image_' . $_POST['value_id'] . '.' . $fname[count($fname)-1];
            $target_path = $target_path . $fname;
            /*echo $target_path;
            echo $_FILES['attr_file']['tmp_name'];
            ini_set('dislay_errors',1);           */ 
            if(move_uploaded_file($_FILES['attr_file']['tmp_name'], $target_path)) {
              //echo "update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set picture = '" . $fname . "' where products_options_values_id = '" . $_POST['value_id'] . "' and language_id = '" . $languages[$i]['id'] . "'";die();
              tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set picture = '" . $fname . "' where products_options_values_id = '" . $_POST['value_id'] . "' and language_id = '" . $value_id . "'");
            }
          }
        
        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " (products_options_id, products_options_values_id) values ('" . $_POST['option_id'] . "', '" . $_POST['value_id'] . "')");
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break; 
     case 'add_product_attributes':
        $values_id = isset($_POST['values_id']) ? (int)$_POST['values_id'] : 0;
        $value_price = isset($_POST['value_price']) ? (float)$_POST['value_price'] : 0;
        $price_prefix = isset($_POST['price_prefix']) ? $_POST['price_prefix'] : '+';
        $sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;
        tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . (int)$_POST['products_id'] . "', '" . (int)$_POST['options_id'] . "', '" . $values_id . "', '" . $value_price . "', '" . $price_prefix . "', '" . $sort_order . "','0','0')");
        $products_attributes_id = tep_db_insert_id();
        if (DOWNLOAD_ENABLED == 'true' && isset($_POST['products_attributes_filename']) && $_POST['products_attributes_filename'] != '') {
          tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " values (" . $products_attributes_id . ", '" . $_POST['products_attributes_filename'] . "', '" . $_POST['products_attributes_maxdays'] . "', '" . $_POST['products_attributes_maxcount'] . "')");
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_option_name':
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
// WebMakers.com Added: Product Options Sort Order
          $option_name = $_POST['option_name'];
          $products_options_sort_order = $_POST['products_options_sort_order'];
          $options_type = $_POST['option_type'];
          $options_length = $_POST['products_options_length'];
          $option_id = $_POST['option_id'];
          $products_options_instruct = $_POST['products_options_instruct'];
          
      tep_db_query("update " . TABLE_PRODUCTS_OPTIONS . " set options_type = '" . $options_type . "', options_length = '" . $options_length . "', products_options_sort_order = '" . $products_options_sort_order . "' where products_options_id = '" . $option_id . "'");
      tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_TEXT . " set products_options_instruct = '" . tep_db_input($products_options_instruct[$languages[$i]['id']]) . "', products_options_name = '" . tep_db_input($option_name[$languages[$i]['id']]) . "' where  products_options_text_id = '" . $_POST['option_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
    
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_value':
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $value_name = $_POST['value_name'];
          tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_name = '" . tep_db_input($value_name[$languages[$i]['id']]) . "' where products_options_values_id = '" . $_POST['value_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
          if(@$_FILES['attr_file']['error'] === 0){
            $target_path = DIR_FS_CATALOG . DIR_WS_IMAGES . "product_attributes" . DIRECTORY_SEPARATOR;
            $fname = split("\.", $_FILES['attr_file']['name']);
            $fname = 'attribute_image_' . $_POST['value_id'] . '.' . $fname[count($fname)-1];            
            $target_path = $target_path . $fname; 
            $values_query = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $_POST['value_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
            $reslt = array();
            while ($r = tep_db_fetch_array($values_query)) {
              $reslt = $r;
            }
            $del_img = @$reslt['picture'];              
            @unlink(DIR_FS_CATALOG . DIR_WS_IMAGES . 'product_attributes/' . DIRECTORY_SEPARATOR . $del_img);
            if(move_uploaded_file($_FILES['attr_file']['tmp_name'], $target_path)) {
              tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set picture = '" . $fname . "' where products_options_values_id = '" . $_POST['value_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
            }
          }
          if(@$_POST['del_attr_img'] == 'on'){
            $values_query = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $_POST['value_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
            $reslt = array();
            while ($r = tep_db_fetch_array($values_query)) {
              $reslt = $r;
            }
            $del_img = @$reslt['picture'];
            
            tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set picture = null where products_options_values_id = '" . $_POST['value_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
            
            @unlink(DIR_FS_CATALOG . DIR_WS_IMAGES . 'product_attributes/' . DIRECTORY_SEPARATOR . $del_img);
            
          }
        }
        tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " set products_options_id = '" . $_POST['option_id'] . "', products_options_values_id = '" . $_POST['value_id'] . "'  where products_options_values_to_products_options_id = '" . $_POST['value_id'] . "'");
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_product_attribute':
// BOF: WebMakers.com Added: Attribute Sorter
          tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set products_id = '" . $_POST['products_id'] . "', options_id = '" . $_POST['options_id'] . "', options_values_id = '" . $_POST['values_id'] . "', options_values_price = '" . $_POST['value_price'] . "', price_prefix = '" . $_POST['price_prefix'] . "', products_options_sort_order = '" . $_POST['sort_order'] . "' where products_attributes_id = '" . $_POST['attribute_id'] . "'");
//        tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set products_id = '" . $_POST['products_id'] . "', options_id = '" . $_POST['options_id'] . "', options_values_id = '" . $_POST['values_id'] . "', options_values_price = '" . $_POST['value_price'] . "', price_prefix = '" . $_POST['price_prefix'] . "', products_options_sort_order = '" . $_POST['sort_order'] . "'");
// EOF: WebMakers.com Added: Attribute Sorter
// BOM Mod: allow for the download filename to be added or deleted when doing an edit
        if (DOWNLOAD_ENABLED == 'true') {
          $download_query_raw ="select products_attributes_filename from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                              where products_attributes_id='" . $_POST['attribute_id'] . "'";
          $download_query = tep_db_query($download_query_raw);
          if (tep_db_num_rows($download_query) > 0) {
            $download_attribute_found = true;
          } else {
            $download_attribute_found = false;
          }
          if ($_POST['products_attributes_filename'] != '') {
            if ($download_attribute_found) {
              tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                            set products_attributes_filename='" . $_POST['products_attributes_filename'] . "',
                                products_attributes_maxdays='" . $_POST['products_attributes_maxdays'] . "',
                                products_attributes_maxcount='" . $_POST['products_attributes_maxcount'] . "'
                            where products_attributes_id = '" . $_POST['attribute_id'] . "'");
            } else {
              tep_db_query("insert " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                            set products_attributes_id = '" . $_POST['attribute_id'] . "',
                                products_attributes_filename='" . $_POST['products_attributes_filename'] . "',
                                products_attributes_maxdays='" . $_POST['products_attributes_maxdays'] . "',
                                products_attributes_maxcount='" . $_POST['products_attributes_maxcount'] . "'");
            }
          } else {
            if ($download_attribute_found) {
              tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                            where products_attributes_id = '" . $_POST['attribute_id'] . "'");
            }
          }
        }
// EOM Mod:
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_option':
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_TEXT . " where products_options_text_id = '" . $_GET['option_id'] . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $_GET['option_id'] . "'");
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_value':
        $values_query = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $_GET['value_id'] . "'");
        $reslt = array();
        while ($r = tep_db_fetch_array($values_query)) {
          $reslt = $r;
        }
        $del_img = @$reslt['picture'];              
        @unlink(DIR_FS_CATALOG . DIR_WS_IMAGES . 'product_attributes/' . DIRECTORY_SEPARATOR . $del_img);

        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $_GET['value_id'] . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $_GET['value_id'] . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_values_id = '" . $_GET['value_id'] . "'");
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_attribute':
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . $_GET['attribute_id'] . "'");
// Added for DOWNLOAD_ENABLED. Always try to remove attributes, even if downloads are no longer enabled
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id = '" . $_GET['attribute_id'] . "'");
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
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
<script language="javascript"><!--
function go_option() {
  if (document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value != "none") {
    location = "<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . ($_GET['option_page'] ? $_GET['option_page'] : 1)); ?>&option_order_by="+document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value;
  }
}
var options_obj = new Object();
<?php
  $values_query = tep_db_query("select povtpo.products_options_id, pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " povtpo,  " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pov.products_options_values_id = povtpo.products_options_values_id and language_id = '" . (int)$languages_id . "' order by povtpo.products_options_id");
  $notFirstTime = False;
  $last_option = '';
  while ($values = tep_db_fetch_array($values_query)) {
    if ( $values['products_options_id'] != $last_option ) {
      if ( $notFirstTime ) {      
        $option_str .= ']; options_obj["' . $values['products_options_id'] . '"] = [' . $values['products_options_values_id'];
      } else {
        $option_str .= ' options_obj["' . $values['products_options_id'] . '"] = [' . $values['products_options_values_id'];
      }
      $last_option = $values['products_options_id'];
    } else {
      $option_str .= ', ' . $values['products_options_values_id'];
    }
    $notFirstTime = true;
  }
  $option_str.= "]; \n";
  
  echo $option_str;
  
?>
var values_obj = new Object();
<?php
  $values_query = tep_db_query("select products_options_values_id, products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . (int)$languages_id . "' order by products_options_values_id");
  while ($values = tep_db_fetch_array($values_query)) {
    $value_str .= ' values_obj["' . $values['products_options_values_id'] . '"] = \'' . addslashes($values['products_options_values_name']) . '\'; ';
  }
  
  echo $value_str . "\n";
  
?>
function setvalues(form) {
  opt = document[form].options_id.options[document[form].options_id.selectedIndex].value;
  document[form].values_id.options.length = 0;
  if ( options_obj[opt] instanceof Array ) {
    for ( var v in options_obj[opt] ) {
      if(gisInteger(v)) {        
        document[form].values_id.options[document[form].values_id.options.length] = new Option( values_obj[options_obj[opt][v]], options_obj[opt][v] );
      }
    }
  } else {
    document[form].values_id.options[document[form].values_id.options.length] = new Option( '<?php echo JAVASCRIPT_TEXT_OPTION_TYPE_TEXT; ?>', 0 );
  }
  
}
function gisInteger (s)
   {
      var i;

      if (gisEmpty(s))
      if (gisInteger.arguments.length == 1) return 0;
      else return (gisInteger.arguments[1] == true);

      for (i = 0; i < s.length; i++)
      {
         var c = s.charAt(i);

         if (!gisDigit(c)) return false;
      }

      return true;
   }

   function gisEmpty(s)
   {
      return ((s == null) || (s.length == 0))
   }

   function gisDigit (c)
   {
      return ((c >= "0") && (c <= "9"))
   }
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<!-- options and values//-->
      <tr>
        <td width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
<!-- product_options //-->          
            <td valign="top" width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
<!-- options //-->
<?php
  if (isset($_GET['option_order_by'])) {
    $option_order_by = $_GET['option_order_by'];
  } else {
    $option_order_by = 'products_options_id';
  }
    
  if (isset($_GET['action']) && $_GET['action'] == 'delete_product_option') { // delete product option
    $options = tep_db_query("select po.products_options_id, pot.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " pot where pot.products_options_text_id = po.products_options_id and pot.language_id = '" . (int)$languages_id . "' order by po.products_options_sort_order, pot.products_options_name");
    $options_values = tep_db_fetch_array($options);
?>
              <tr>
                <td class="pageHeading">&nbsp;<?php echo htmlspecialchars($options_values['products_options_name']); ?>&nbsp;</td>
                <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '53'); ?>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
    //$products = tep_db_query("select p.products_id, pd.products_name, pov.products_options_values_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pov.language_id = '" . (int)$languages_id . "' and pd.language_id = '" . (int)$languages_id . "' and pa.products_id = p.products_id and pa.options_id='" . $_GET['option_id'] . "' and pov.products_options_values_id = pa.options_values_id order by pd.products_name");

    $products = tep_db_query("select pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " pov2po left join " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov on pov.products_options_values_id = pov2po.products_options_values_id where pov2po.products_options_id = '" . (int)$_GET['option_id'] . "' and pov.language_id = '" . (int)$languages_id . "'");

    if (tep_db_num_rows($products)) {
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
      $rows = 0;
      while ($products_values = tep_db_fetch_array($products)) {
        $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center" class="smallText">&nbsp;<?php echo $products_values['products_id']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_name']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo htmlspecialchars($products_values['products_options_values_name']); ?>&nbsp;</td>
                  </tr>
<?php
      }
?>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="main"><br><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="3" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
    } else {
?>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option&option_id=' . $_GET['option_id'] . '&' . $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_delete.gif', ' delete '); ?></a>&nbsp;&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, (isset($_GET['order_by']) ? 'order_by=' . $_GET['order_by'] . '&' : '') . $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
    }
?>
                </table></td>
              </tr>
<?php
  } else {
?>
              <tr>
                <td colspan="2" class="pageHeading">&nbsp;<?php echo HEADING_TITLE_OPT; ?>&nbsp;</td>
                <td class="smallText" align="center"><?php echo TEXT_OPTION_SORTORDER; ?><br><form name="option_order_by" action="<?php echo FILENAME_PRODUCTS_ATTRIBUTES; ?>"><select name="selected" onChange="go_option()"><option value="products_options_id"<?php if ($option_order_by == 'products_options_id') { echo ' selected="selected"'; } ?>><?php echo TEXT_OPTION_ID; ?></option><option value="products_options_name"<?php if ($option_order_by == 'products_options_name') { echo ' selected="selected"'; } ?>><?php echo TEXT_OPTION_NAME; ?></option></select></form></td>
              </tr>
              <tr>
                <td colspan="3" class="smallText">
<?php
    $per_page = MAX_ROW_LISTS_OPTIONS;
    $options = "select * from " . TABLE_PRODUCTS_OPTIONS . " po," . TABLE_PRODUCTS_OPTIONS_TEXT . " pot where pot.products_options_text_id = po.products_options_id and pot.language_id = '" . (int)$languages_id . "' order by " . $option_order_by;
    $option_page = isset($_GET['option_page']) ? $_GET['option_page'] : 1;
    $prev_option_page = $option_page - 1;
    $next_option_page = $option_page + 1;

    $option_query = tep_db_query($options);

    $option_page_start = ($per_page * $option_page) - $per_page;
    $num_rows = tep_db_num_rows($option_query);

    if ($num_rows <= $per_page) {
      $num_pages = 1;
    } else if (($num_rows % $per_page) == 0) {
      $num_pages = ($num_rows / $per_page);
    } else {
      $num_pages = ($num_rows / $per_page) + 1;
    }
    $num_pages = (int) $num_pages;
    
    if ($option_page_start <= '0'){
         $option_page_start = '0';
         }

    $options = $options . " LIMIT $option_page_start, $per_page";

    // Previous
    if ($prev_option_page)  {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $prev_option_page) . '"> &lt;&lt; </a> | ';
    }

    for ($i = 1; $i <= $num_pages; $i++) {
      if ($i != $option_page) {
        echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $i) . '">' . $i . '</a> | ';
      } else {
        echo '<b><font color=red>' . $i . '</font></b> | ';
      }
    }

    // Next
    if ($option_page != $num_pages) {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $next_option_page) . '"> &gt;&gt; </a>';
    }
// WebMakers.com Added: Product Options Sort Order
?>
                </td>
              </tr>
              <tr>
                <td colspan="7"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_OPTION_COMMENTS; ?>&nbsp;</td>
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_TYPE; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_OPT_SIZE; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_OPTION_SORT_ORDER; ?>&nbsp;</td>

                <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="7"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    $next_id = 1;
    $rows = 0;
    $options = tep_db_query($options);
    while ($options_values = tep_db_fetch_array($options)) {
      $rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
//edit line
      if ((isset($_GET['action']) && $_GET['action'] == 'update_option') && ($_GET['option_id'] == $options_values['products_options_id'])) {
        echo '<form name="option" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_name&' . $page_info, 'NONSSL') . '" method="post">';
//get option data
        $inputs = '<td><table>';
          $option_name_raw = tep_db_query("select  po.options_type, po.options_length, po.products_options_sort_order from " . TABLE_PRODUCTS_OPTIONS . " po where po.products_options_id = '" . $options_values['products_options_id'] . "' order by products_options_sort_order");
          $option_name = tep_db_fetch_array($option_name_raw);
//name and comments
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
         $option_name_raw1 = tep_db_query("select pot.products_options_name, pot.products_options_instruct from " . TABLE_PRODUCTS_OPTIONS_TEXT  . " pot where  pot.products_options_text_id ='" . $options_values['products_options_id'] ."' and pot.language_id = '" . $languages[$i]['id'] . "'");
         $option_name1 = tep_db_fetch_array($option_name_raw1);
    $inputs .= '<tr><td class="smallText">' . $languages[$i]['code'] . ':&nbsp;</td><td class="smallText"><input type="text" name="option_name[' . $languages[$i]['id'] . ']" size="15" value="' . htmlspecialchars($option_name1['products_options_name']) . '">&nbsp;</td><td class="smallText"><input type="text" name="products_options_instruct[' . $languages[$i]['id'] . ']" size="20" value="' . htmlspecialchars($option_name1['products_options_instruct']) . '"></td></tr>';
    }
 //display rest of data
 $inputs .= '</table></td><td class="smallText" align="left">' . draw_optiontype_pulldown('option_type', $options_values['options_type']) . '</td> <td class="smallText" align="left"><input type="text" name="products_options_length" size="3" value="' . $option_name['options_length'] . '"> <td class="smallText" align="center"><input type="text" name="products_options_sort_order" size="3" value="' . $option_name['products_options_sort_order'] . '"></td></tr>';
?>
                <td align="center" class="smallText">&nbsp;<?php echo $options_values['products_options_id']; ?><input type="hidden" name="option_id" value="<?php echo $options_values['products_options_id']; ?>">&nbsp;</td>
                <td colspan="5" class="smallText"><table border="0" width="100%" cellspacing="0" cellpadding="0">  <?php echo $inputs; ?> </table> </td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>

         <?php
        echo '</form>' . "\n";
      } else {
// regular list line
?>
                <td align="center" class="smallText">&nbsp;<?php echo $options_values["products_options_id"]; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo htmlspecialchars($options_values["products_options_name"]); ?>&nbsp;</td>
                <td class="smallText" align="left">&nbsp;<?php echo $options_values["products_options_instruct"]; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo translate_type_to_name($options_values["options_type"]); ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo $options_values["options_length"]; ?>&nbsp;</td>
                <td class="smallText" align="center">&nbsp;<?php echo $options_values["products_options_sort_order"]; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option&option_id=' . $options_values['products_options_id'] . '&option_order_by=' . $option_order_by . '&option_page=' . $option_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_page_edit.png', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_product_option&option_id=' . $options_values['products_options_id'], 'NONSSL') , '">'; ?><?php echo tep_image_button('button_delete.gif', IMAGE_DELETE); ?></a>&nbsp;</td>
<?php
      }
?>
              </tr>
<?php
      $max_options_id_query = tep_db_query("select max(products_options_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS);
      $max_options_id_values = tep_db_fetch_array($max_options_id_query);
      $next_id = $max_options_id_values['next_id'];
    }
?>
              <tr>
                <td colspan="7"><?php echo tep_black_line(); ?></td>
              </tr>
<?php //new option line
    if (!isset($_GET['action']) || (isset($_GET['action'])&& $_GET['action'] != 'update_option')) {
?>

<?php
// WebMakers.com Added: Product Options Sort Order
      echo '<form name="options" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_options&' . $page_info, 'NONSSL') . '" method="post"><input type="hidden" name="products_options_id" value="' . $next_id . '">';
      $inputs = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $inputs .= '<tr><td>' . $languages[$i]['code'] . ':&nbsp;</td><td><input type="text" name="option_name[' . $languages[$i]['id'] . ']" size="20">&nbsp;</td><td><input type="text" name="products_options_instruct[' . $languages[$i]['id'] . ']" size="32">&nbsp;</td></tr>';
      }
?><tr>
   <td colspan="7" width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">

               <tr class="dataTableHeadingRow">
                  <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                 <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_LANGUAGE; ?>&nbsp;&nbsp;&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                 <td class="dataTableHeadingContent" align="left">&nbsp;<?php echo TABLE_HEADING_OPTION_COMMENTS; ?>&nbsp;</td>
                 <td class="dataTableHeadingContent" align="left">&nbsp;<?php echo TABLE_HEADING_OPT_TYPE; ?>&nbsp;</td>
                 <td class="dataTableHeadingContent" align="left">&nbsp;&nbsp;&nbsp;<?php echo TABLE_HEADING_OPT_SIZE; ?>&nbsp;</td>
                 <td class="dataTableHeadingContent" align="left">&nbsp;<?php echo TABLE_HEADING_OPTION_SORT_ORDER; ?>&nbsp;</td>
                 <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
               </tr>
               <tr>
                    <td colspan="7"><?php echo tep_black_line(); ?></td>
               </tr>
<tr>
                <td align="center" class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
                <td colspan="2" class="smallText"><table border="0" width="100%" cellspacing="0" cellpadding="0">  <?php echo $inputs; ?> </table> </td>
                 <td > <?php echo draw_optiontype_pulldown('options_type', $options_values['options_type']) ;?> </td> <td align="left"><input type="text" name="options_length" size="3"></td><td align="left"><input type="text" name="products_options_sort_order" size="3"></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?>&nbsp;</td>
<?php
      echo '</form>';
?>
              </tr>
   </table></td></tr>
              <tr>
                <td colspan="7"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    }
  }
?>
            </table></td>
<!-- product_options eof //-->
  </tr>
   <tr>
     
     <td valign="top" width="50%">
       <table width="776" border="0" cellspacing="0" cellpadding="2">
<!-- value //-->
<?php
  if (isset($_GET['action']) && $_GET['action'] == 'delete_option_value') { // delete product option value
    $values = tep_db_query("select products_options_values_id, products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $_GET['value_id'] . "' and language_id = '" . (int)$languages_id . "'");
    $values_values = tep_db_fetch_array($values);
?>
              <tr>
                <td colspan="3" class="pageHeading">&nbsp;<?php echo htmlspecialchars($values_values['products_options_values_name']); ?>&nbsp;</td>
                <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '53'); ?>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<!-- value query//-->
<?php
    $products1 = tep_db_query("select p.products_id, pd.products_name, pot.products_options_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " pot where pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and pot.language_id = '" . (int)$languages_id . "' and pa.products_id = p.products_id and pa.options_values_id='" . $_GET['value_id'] . "' and po.products_options_id = pa.options_id and pa.options_id = pot.products_options_text_id   order by pd.products_name, pa.products_options_sort_order, po.products_options_sort_order");
    if (tep_db_num_rows($products1)) {
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right">&nbsp;<?php echo TABLE_HEADING_OPTION_SORT_ORDER; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
      $rows = 0;
      while ($products_values1 = tep_db_fetch_array($products1)) {
        $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center" class="smallText">&nbsp;<?php echo $products_values1['products_id']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values1['products_name']; ?>&nbsp;</td>
                <td class="smallText" align="right">&nbsp;<?php echo $options_values["products_options_sort_order"]; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo htmlspecialchars($products_values1['products_options_name']); ?>&nbsp;</td>
                  </tr>
<?php
      }
?>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
    } else {
?>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_value&value_id=' . $_GET['value_id'] . '&' . $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_delete.gif', ' delete '); ?></a>&nbsp;&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
    }
?>
                </table></td>
              </tr>
<?php
  } else {
?>
              <tr>
                <td colspan="3" class="pageHeading">&nbsp;<?php echo HEADING_TITLE_VAL; ?>&nbsp;</td>
                <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '36'); ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4" class="smallText">
<?php
    $per_page = MAX_ROW_LISTS_OPTIONS;
    $values = "select distinct pov.products_options_values_id, pov.products_options_values_name, pov.picture, pov2po.products_options_id from
    " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov,
    " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " pov2po
    where 
   pov2po.products_options_values_id = pov.products_options_values_id and 
    pov.language_id = '" . (int)$languages_id . "' 
    order by pov.products_options_values_id";
    $value_page = isset($_GET['value_page']) ? (int)$_GET['value_page'] : 1;
    $prev_value_page = $value_page - 1;
    $next_value_page = $value_page + 1;

    $value_query = tep_db_query($values);

    $value_page_start = ($per_page * $value_page) - $per_page;
    $num_rows = tep_db_num_rows($value_query);

    if ($num_rows <= $per_page) {
      $num_pages = 1;
    } else if (($num_rows % $per_page) == 0) {
      $num_pages = ($num_rows / $per_page);
    } else {
      $num_pages = ($num_rows / $per_page) + 1;
    }
    $num_pages = (int) $num_pages;

    $values = $values . " LIMIT $value_page_start, $per_page";

    // Previous
    if ($prev_value_page)  {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_order_by=' . $option_order_by . '&value_page=' . $prev_value_page) . '"> &lt;&lt; </a> | ';
    }

    for ($i = 1; $i <= $num_pages; $i++) {
      if ($i != $value_page) {
         echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_order_by=' . $option_order_by . '&value_page=' . $i) . '">' . $i . '</a> | ';
      } else {
         echo '<b><font color=red>' . $i . '</font></b> | ';
      }
    }

    // Next
    if ($value_page != $num_pages) {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_order_by=' . $option_order_by . '&value_page=' . $next_value_page) . '"> &gt;&gt;</a> ';
    }
?>
                </td>
              </tr>
              <tr>
                <td colspan="5"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center">&nbsp;File&nbsp;</td>                
                <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="5"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    $next_id = 1;
    $rows = 0;
    $values = tep_db_query($values);
    while ($values_values = tep_db_fetch_array($values)) {
      $options_name   = tep_options_name($values_values['products_options_id']);
      $values_name 	  = $values_values['products_options_values_name'];
	  $values_picture = $values_values['picture'];
      $rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      if ((isset($_GET['action']) && $_GET['action'] == 'update_option_value') && ($_GET['value_id'] == $values_values['products_options_values_id'])) {
        echo '<form enctype="multipart/form-data" name="values" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_value&' . $page_info, 'NONSSL') . '" method="post">';
        $inputs = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $value_name = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $values_values['products_options_values_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
          $value_name = tep_db_fetch_array($value_name);
          $inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" name="value_name[' . $languages[$i]['id'] . ']" size="15" value="' . htmlspecialchars($value_name['products_options_values_name']) . '">&nbsp;<br>';
        }
?>
                <td align="center" class="smallText">&nbsp;<?php echo $values_values['products_options_values_id']; ?><input type="hidden" name="value_id" value="<?php echo $values_values['products_options_values_id']; ?>">&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo "\n"; ?>
                
                <select name="option_id">
<?php
         
        $options = tep_db_query("select po.products_options_id, pot.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " AS po, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " AS pot where po.options_type in (0,2,3,5) and po.products_options_id = pot.products_options_text_id  and pot.language_id = '" . (int)$languages_id . "' order by po.products_options_sort_order, pot.products_options_name");
        while ($options_values = tep_db_fetch_array($options)) {
          echo "\n" . '<option name="' . htmlspecialchars($options_values['products_options_name']) . '" value="' . $options_values['products_options_id'] . '"';
          if ($values_values['products_options_id'] == $options_values['products_options_id']) {
            echo ' selected';
          }
          echo '>' . htmlspecialchars($options_values['products_options_name']) . '</option>';
        }
?>
                </select>&nbsp;</td>
                <td class="smallText"><?php echo $inputs; ?></td>
                <td class="smallText">New image: <input type="file" name="attr_file" /><br />
                <?php if(strlen($values_values["picture"]) > 0){echo "Current image:<br /><img src='http://". $_SERVER['SERVER_NAME'] . '/images/product_attributes/'. $values_values["picture"]."' /><br /><input type='checkbox' name='del_attr_img' id='del_attr_img' /><label for='del_attr_img'>Delete image</label>";} ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>
<?php
        echo '</form>';
      } else {
?>
                <td align="center" class="smallText">&nbsp;<?php echo $values_values["products_options_values_id"]; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo htmlspecialchars($options_name); ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo htmlspecialchars($values_name); ?>&nbsp;</td>
                <!--<td class="smallText" align="center">&nbsp;<?php //if(strlen($values_values["picture"]) > 0){echo "<img src='http://". $_SERVER['SERVER_NAME'] . '/images/product_attributes/'. $values_values["picture"]."' />";} else {echo '[ No image ]';} ?></td>&nbsp;</td>-->
                <td class="smallText" align="center">&nbsp;<?php if(strlen($values_picture) > 0){echo $values_picture;} else {echo '[ No image ]';} ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_value&value_id=' . $values_values['products_options_values_id'] . '&' . $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_page_edit.png', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option_value&value_id=' . $values_values['products_options_values_id'] . '&' . $page_info, 'NONSSL') , '">'; ?><?php echo tep_image_button('button_delete.gif', IMAGE_DELETE); ?></a>&nbsp;</td>
<?php
      }
      $max_values_id_query = tep_db_query("select max(products_options_values_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS_VALUES);
      $max_values_id_values = tep_db_fetch_array($max_values_id_query);
      $next_id = $max_values_id_values['next_id'];
    }
?>
              </tr>
              <tr>
                <td colspan="5"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    if (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] != 'update_option_value')) {
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      echo '<form enctype="multipart/form-data" name="values" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_option_values&' . $page_info, 'NONSSL') . '" method="post">';
?>
                <td align="center" class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;
                
                <select name="option_id">
<?php
     $options = tep_db_query("select po.products_options_id, pot.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " pot where po.options_type in (0,2,3) and pot.products_options_text_id = po.products_options_id and pot.language_id = '" . (int)$languages_id . "' order by po.products_options_sort_order, pot.products_options_name");
      while ($options_values = tep_db_fetch_array($options)) {
        echo '<option name="' . htmlspecialchars($options_values['products_options_name']) . '" value="' . $options_values['products_options_id'] . '">' . htmlspecialchars($options_values['products_options_name']) . '</option>';
      }

      $inputs = '';
      $inputs .= '<table border="0" cellspacing="0" cellpadding="0">'; 
      for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
        $inputs .= '<tr><td align="right">' . $languages[$i]['code'] . ':</td><td>&nbsp;<input type="text" name="value_name[' . $languages[$i]['id'] . ']" size="15">&nbsp;</td></tr>' . "\n";
      }
      $inputs .='</table>' . "\n";
?>
                </select>&nbsp;</td>
                <td class="smallText"><input type="hidden" name="value_id" value="<?php echo $next_id; ?>"><?php echo $inputs; ?></td>
                <td>Image: <input type="file" name="attr_file" /></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?>&nbsp;</td>
<?php
      echo '</form>';
?>
              </tr>
              <tr>
                <td colspan="5"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    }
  }
?>
            </table></td>
          </tr>
        </table></td>
<!-- option value eof //-->
      </tr>
<!-- products_attributes //-->
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE_ATRIB; ?>&nbsp;</td>
            <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '53'); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
<?php
    if (isset($_GET['action']) && $_GET['action'] == 'update_attribute') {
      $form_action = 'update_product_attribute';
    } else {
      $form_action = 'add_product_attributes';
    }
?>

        <td><form name="attributes" action="<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=' . $form_action . '&' . $page_info); ?>" method="post"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="10" class="smallText">
<?php
  $per_page = MAX_ROW_LISTS_OPTIONS;
$attributes = "select pa.* from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, 
  " . TABLE_PRODUCTS_DESCRIPTION . " pd 
  where
  pd.products_id = pa.products_id and 
  pd.language_id = '" . (int)$languages_id . "' 
  order by pd.products_name, pa.products_options_sort_order";

  $attribute_page = isset($_GET['attribute_page']) ? (int)$_GET['attribute_page'] : 1;
  $prev_attribute_page = $attribute_page - 1;
  $next_attribute_page = $attribute_page + 1;

  $attribute_query = tep_db_query($attributes);

  $attribute_page_start = ($per_page * $attribute_page) - $per_page;
  $num_rows = tep_db_num_rows($attribute_query);

  if ($num_rows <= $per_page) {
     $num_pages = 1;
  } else if (($num_rows % $per_page) == 0) {
     $num_pages = ($num_rows / $per_page);
  } else {
     $num_pages = ($num_rows / $per_page) + 1;
  }
  $num_pages = (int) $num_pages;

  $attributes = $attributes . " LIMIT $attribute_page_start, $per_page";

  // Previous
  if ($prev_attribute_page) {
    echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'attribute_page=' . $prev_attribute_page) . '"> &lt;&lt; </a> | ';
  }

  for ($i = 1; $i <= $num_pages; $i++) {
    if ($i != $attribute_page) {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'attribute_page=' . $i) . '">' . $i . '</a> | ';
    } else {
      echo '<b><font color="red">' . $i . '</font></b> | ';
    }
  }

  // Next
  if ($attribute_page != $num_pages) {
    echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'attribute_page=' . $next_attribute_page) . '"> &gt;&gt; </a>';
  }
?>
            </td>
          </tr>
          <tr>
            <td colspan="9"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
<?php
// BOF: WebMakers.com Added: FREE-CALL
//
?>
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_FILENAME; ?>&nbsp;</td>
<?php
// EOF: WebMakers.com Added: FREE-CALL
//
?>
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
<?php
// BOF: WebMakers.com Added: FREE-CALL
//
?>
            <td class="dataTableHeadingContent" align="right">&nbsp;<?php echo 'Sort Order '; ?>&nbsp;</td>

<?php
// EOF: WebMakers.com Added: FREE-CALL
//
?>
            <td class="dataTableHeadingContent" align="right">&nbsp;<?php echo TABLE_HEADING_OPT_PRICE; ?>&nbsp;</td>
            <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_OPT_PRICE_PREFIX; ?>&nbsp;</td>
            <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="9"><?php echo tep_black_line(); ?></td>
          </tr>

<?php
  $next_id = 1;
  $rows = 0;
  $attributes = tep_db_query($attributes);
  while ($attributes_values = tep_db_fetch_array($attributes)) {
    $products_name_only = tep_get_products_name($attributes_values['products_id']);
    $options_name = tep_options_name($attributes_values['options_id']);
    $values_name = tep_values_name($attributes_values['options_values_id']);
    $rows++;
?>
          <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
    if ((isset($_GET['action']) && $_GET['action'] == 'update_attribute') && ($_GET['attribute_id'] == $attributes_values['products_attributes_id'])) {
?>
            <td class="attributeBoxContent">&nbsp;<?php echo $attributes_values['products_attributes_id']; ?><input type="hidden" name="attribute_id" value="<?php echo $attributes_values['products_attributes_id']; ?>">&nbsp;</td>
            <td class="attributeBoxContent" colspan="2"><pre><?php echo $products_name_only; ?></pre><input type="hidden" name="products_id" value="<?php echo $attributes_values['products_id']; ?>"></td>
            <td class="attributeBoxContent">&nbsp;<select name="options_id">
<?php
      $options = tep_db_query("select po.products_options_id, pot.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " pot where pot.products_options_text_id = po.products_options_id and pot.language_id = '" . (int)$languages_id . "' order by po.products_options_sort_order, pot.products_options_name");
      while($options_values = tep_db_fetch_array($options)) {
        if ($attributes_values['options_id'] == $options_values['products_options_id']) {
          echo "\n" . '<option name="' . htmlspecialchars($options_values['products_options_name']) . '" value="' . $options_values['products_options_id'] . '" selected="selected">' . htmlspecialchars($options_values['products_options_name']) . '</option>';
        } else {
          echo "\n" . '<option name="' . htmlspecialchars($options_values['products_options_name']) . '" value="' . $options_values['products_options_id'] . '">' . htmlspecialchars($options_values['products_options_name']) . '</option>';
        }
      }
?>
            </select>&nbsp;</td>
            <td class="attributeBoxContent">&nbsp;<select name="values_id">
<?php
      $values = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id ='" . (int)$languages_id . "' order by products_options_values_name");
      while($values_values = tep_db_fetch_array($values)) {
        if ($attributes_values['options_values_id'] == $values_values['products_options_values_id']) {
          echo "\n" . '<option name="' . htmlspecialchars($values_values['products_options_values_name']) . '" value="' . $values_values['products_options_values_id'] . '" selected="selected">' . htmlspecialchars($values_values['products_options_values_name']) . '</option>';
        } else {
          echo "\n" . '<option name="' . htmlspecialchars($values_values['products_options_values_name']) . '" value="' . $values_values['products_options_values_id'] . '">' . htmlspecialchars($values_values['products_options_values_name']) . '</option>';
        }
      }
?>
            </select>&nbsp;</td>
<?php
// BOF: WebMakers.com Added: Attribute Sorter- Edit
//
?>
            <td align="right" class="attributeBoxContent">&nbsp;<?php echo SORT_ORDER;?> <input type="text" name="sort_order" value="<?php echo $attributes_values['products_options_sort_order']; ?>" size="2" maxlength="3">&nbsp;</td>
            <td align="right" class="attributeBoxContent">&nbsp;<?php echo PRICE;?> <input type="text" name="value_price" value="<?php echo $attributes_values['options_values_price']; ?>" size="6">&nbsp;</td>
            <td align="center" class="attributeBoxContent">&nbsp;<input type="text" name="price_prefix" value="<?php echo $attributes_values['price_prefix']; ?>" size="2">&nbsp;</td>
            <td align="center" class="attributeBoxContent">&nbsp;<?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . '<br><a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '&attribute_page=' . $attribute_page . '&' . $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>
<?php
      if (DOWNLOAD_ENABLED == 'true') {
        $download_query_raw ="select products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount
                              from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                              where products_attributes_id='" . $attributes_values['products_attributes_id'] . "'";
        $download_query = tep_db_query($download_query_raw);
        if (tep_db_num_rows($download_query) > 0) {
          $download = tep_db_fetch_array($download_query);
          $products_attributes_filename = $download['products_attributes_filename'];
          $products_attributes_maxdays  = $download['products_attributes_maxdays'];
          $products_attributes_maxcount = $download['products_attributes_maxcount'];
        }
?>
          <tr class="attributeBoxContent">
            <td class="attributeBoxContent">&nbsp;</td>
            <td colspan="8">
              <table>
                <tr class="attributeBoxContent">
                  <td align="right" class="attributeBoxContent"><?php echo TABLE_HEADING_DOWNLOAD; ?>&nbsp;</td>
                  <td align="right" class="attributeBoxContent"><?php echo TABLE_TEXT_FILENAME; ?></td>
                  <td align="right" class="attributeBoxContent"><?php echo tep_draw_input_field('products_attributes_filename', $products_attributes_filename, 'size="35"'); ?>&nbsp;</td>
                  <td align="right" class="attributeBoxContent"><?php echo TABLE_TEXT_MAX_DAYS; ?></td>
                  <td align="right" class="attributeBoxContent"><?php echo tep_draw_input_field('products_attributes_maxdays', $products_attributes_maxdays, 'size="5"'); ?>&nbsp;</td>
                  <td align="right" class="attributeBoxContent"><?php echo TABLE_TEXT_MAX_COUNT; ?></td>
                  <td align="right" class="attributeBoxContent"><?php echo tep_draw_input_field('products_attributes_maxcount', $products_attributes_maxcount, 'size="5"'); ?>&nbsp;</td>
                </tr>
              </table>
            </td>
            <td>&nbsp;</td>
          </tr>
<?php
      }
?>



<?php /* BOF: WebMakers.com Added: Attribute Enhancements Coming Soon */ ?>
          <tr class="attributeBoxContent">
            <td class="attributeBoxContent">&nbsp;</td>
            <td class="attributeBoxContent" colspan="8">
            </td>
            <td>&nbsp;</td>
          </tr>
<?php /* EOF: WebMakers.com Added: Attribute Enhancements Coming Soon */ ?>





<?php
    } elseif ((isset($_GET['action']) && $_GET['action'] == 'delete_product_attribute') && ($_GET['attribute_id'] == $attributes_values['products_attributes_id'])) {
?>

<?php
// BOF: WebMakers.com Added: Attribute Sorter - Delete
?>
            <td class="smallText">&nbsp;<b><?php echo $attributes_values["products_attributes_id"]; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo $products_name_only; ?></b>&nbsp;</td>

            <td class="smallText">&nbsp;<b><?php echo $the_download['products_attributes_filename']; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo htmlspecialchars($options_name); ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo htmlspecialchars($values_name); ?></b>&nbsp;</td>

            <td align="right" class="smallText">&nbsp;<b><?php echo $attributes_values["products_options_sort_order"]; ?></td>
            <td align="right" class="smallText">&nbsp;<b><?php echo $attributes_values["options_values_price"]; ?></b>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<b><?php echo $attributes_values["price_prefix"]; ?></b>&nbsp;</td>


            <td align="center" class="smallText">&nbsp;<b><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_attribute&attribute_id=' . $_GET['attribute_id'] . '&' . $page_info) . '">'; ?><?php echo tep_image_button('button_confirm.gif', IMAGE_CONFIRM); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</b></td>
<?php
// EOF: WebMakers.com Added: Attribute Sorter - Delete
    } else {
?>
<?php
// BOF: WebMakers.com Added: FREE-CALL FOR PRICE-COMING SOON ETC.
        $the_download_query_raw ="select products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id='" . $attributes_values['products_attributes_id'] . "'";
        $the_download_query = tep_db_query($the_download_query_raw);
        $the_download= tep_db_fetch_array($the_download_query);

?>
            <td class="smallText">&nbsp;<?php echo $attributes_values["products_attributes_id"]; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $products_name_only; ?>&nbsp;</td>
<?php
// BOF: NOTE
// Could go into /admin/includes/configure.php
//  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  $filename_is_missing='';
  $filename_is_missing_bg='';
// EOF: NOTE
if ( $the_download['products_attributes_filename'] ) {
  if ( !file_exists(DIR_FS_DOWNLOAD . $the_download['products_attributes_filename']) ) {
    //$filename_is_missing='<FONT COLOR="FF0000"> - <B>*** Missing</B></FONT>';
    $filename_is_missing='<FONT COLOR="FF0000"> - <B>'.MISSING.'</B></FONT>';
  $filename_is_missing_bg='bgcolor="#FFE9EA"';
  } else {
   // $filename_is_missing='<FONT COLOR="2F4F2F"> - <B>Good File</B></FONT>';
    $filename_is_missing='<FONT COLOR="2F4F2F"> - <B>'.GOOD_FILE.'</B></FONT>';
  $filename_is_missing_bg='bgcolor="#EFFFE8"';
  }
}
?>
            <td class="smallText" <?php echo $filename_is_missing_bg;?>><?php echo $the_download['products_attributes_filename'] . $filename_is_missing; ?>&nbsp;</td>
<?php
// EOF: WebMakers.com Added: FREE-CALL FOR PRICE-COMING SOON ETC.
?>
            <td class="smallText">&nbsp;<?php echo htmlspecialchars($options_name); ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo htmlspecialchars($values_name); ?>&nbsp;</td>
<?php
// BOF: WebMakers.com Added: FREE-CALL FOR PRICE-COMING SOON ETC.
//
?>
            <td align="right" class="smallText"><?php echo $attributes_values["products_options_sort_order"]; ?>&nbsp;</td>
<?php
// EOF: WebMakers.com Added: FREE-CALL FOR PRICE-COMING SOON ETC.
//
?>
            <td align="right" class="smallText">&nbsp;<?php echo $attributes_values["options_values_price"]; ?>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo $attributes_values["price_prefix"]; ?>&nbsp;</td>
            <td align="center" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_attribute&attribute_id=' . $attributes_values['products_attributes_id'] . '&' . $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_page_edit.png', IMAGE_UPDATE) . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '18') . '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_product_attribute&attribute_id=' . $attributes_values['products_attributes_id'] . '&' . $page_info, 'NONSSL') , '">'; ?><?php echo tep_image_button('button_delete.gif', IMAGE_DELETE); ?></a>&nbsp;</td>
<?php
    }
    $max_attributes_id_query = tep_db_query("select max(products_attributes_id) + 1 as next_id from " . TABLE_PRODUCTS_ATTRIBUTES);
    $max_attributes_id_values = tep_db_fetch_array($max_attributes_id_query);
    $next_id = $max_attributes_id_values['next_id'];
?>
          </tr>
<?php
  }
  if (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] != 'update_attribute')) {
?>
          <tr>
            <td colspan="10"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>" valign="bottom">
            <td class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
<?php
// BOF: WebMakers.com Added: FREE-CALL FOR PRICE-COMING SOON ETC.
?>
            <td class="smallText" colspan="2">&nbsp;<select name="products_id">
<?php
// EOF: WebMakers.com Added: FREE-CALL FOR PRICE-COMING SOON ETC.
?>
<?php
    $products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' order by pd.products_name");
    while ($products_values = tep_db_fetch_array($products)) {
      echo '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_name'] . '</option>';
    }
?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="options_id" onchange="javascript:setvalues(this.form.name);"><option value=""><?php echo TEXT_OPTION_SELECTION; ?></option>
<?php
    $options = tep_db_query("select po.products_options_id, pot.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " pot where pot.products_options_text_id = po.products_options_id and pot.language_id = '" . (int)$languages_id . "' order by po.products_options_sort_order, pot.products_options_name");
    while ($options_values = tep_db_fetch_array($options)) {
      echo '<option name="' . htmlspecialchars($options_values['products_options_name']) . '" value="' . $options_values['products_options_id'] . '">' . htmlspecialchars($options_values['products_options_name']) . '</option>';
    }
?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="values_id">
<?php
//    $values = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . (int)$languages_id . "' order by products_options_values_name");
//    while ($values_values = tep_db_fetch_array($values)) {
//      echo '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '">' . $values_values['products_options_values_name'] . '</option>';
//    }
?>
            </select>&nbsp;</td>
<?php
// BOF: WebMakers.com Added: FREE-CALL FOR PRICE-COMING SOON ETC.
?>
            <td align="right" class="smallText">&nbsp;<input type="text" name="sort_order" size="3" maxlength="3">&nbsp;</td>
<?php
// EOF: WebMakers.com Added: FREE-CALL FOR PRICE-COMING SOON ETC.
?>
            <td align="right" class="smallText">&nbsp;<input type="text" name="value_price" size="6">&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<input type="text" name="price_prefix" size="2" value="+" maxlength="1">&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?>&nbsp;</td>
          </tr>
<?php
      if (DOWNLOAD_ENABLED == 'true') {
        $products_attributes_filename = '';
        $products_attributes_maxdays  = DOWNLOAD_MAX_DAYS;
        $products_attributes_maxcount = DOWNLOAD_MAX_COUNT;
?>
          <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
            <td>&nbsp;</td>
            <td colspan="8">
              <table>
                <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
                  <td class="smallText"><?php echo TABLE_HEADING_DOWNLOAD; ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_FILENAME; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_filename', $products_attributes_filename, 'size="35"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_DAYS; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_maxdays', $products_attributes_maxdays, 'size="5"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_COUNT; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_maxcount', $products_attributes_maxcount, 'size="5"'); ?>&nbsp;</td>
                </tr>
              </table>
            </td>
            <td>&nbsp;</td>
          </tr>
<?php
      } // end of DOWNLOAD_ENABLED section
?>
<?php
  }
?>
          <tr>
            <td colspan="10"><?php echo tep_black_line(); ?></td>
          </tr>
        </table></form></td>
      </tr>
    </table></td>
<!-- products_attributes_eof //-->
  </tr>
</table>
<!-- body_text_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>