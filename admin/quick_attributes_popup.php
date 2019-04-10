<?php
/*
   quick_attributes_popup.php
   WebMakers.com Added: Show current attributes of the product
*/

include('includes/application_top.php');
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

$look_it_up = isset($_GET['look_it_up']) ? (int)$_GET['look_it_up'] : '';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo QUICK_ATTRIBUTES_POPUP_TXT_0; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script> 
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php
// Get Product Info
  $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $look_it_up . "' and pd.products_id = '" . $look_it_up . "' and pd.language_id = '" . $languages_id . "'");
  $product_info = tep_db_fetch_array($product_info_query);

?>

<table border="0" width="85%" align="center">
<tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
<tr>
  <td class="main" align="left"><?php echo QUICK_ATTRIBUTES_POPUP_TXT_1;?> <?php echo $look_it_up;?></td>
  <td class="main" align="right"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $product_info['products_image'],'', SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);?></td>
</tr>
<tr>
  <td class="main" align="left"><?php echo $product_info['products_name'];?></td>
  <td class="main" align="right"><?php echo QUICK_ATTRIBUTES_POPUP_TXT_2;?> <?php echo $product_info['products_model'];?></td>
</tr>
<tr>
  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
</tr>
</table>

<?php
///////////////////////////////////////////////////////////////////////////
// BOF: attribute options

    echo '<table border="0" width="85%" align="center"><tr><td>';

    $products_attributes = tep_db_query("select poptt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt,  " . TABLE_PRODUCTS_OPTIONS_TEXT  . " poptt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $look_it_up . "' and patrib.options_id = popt.products_options_id and poptt.language_id = '" . $languages_id . "'");
    if (tep_db_num_rows($products_attributes)) {
      $products_attributes = '1';
    } else {
      $products_attributes = '0';
      echo '<table border="0" cellpading="0" cellspacing"0">';
      echo '<tr><td class="main" colspan="2">';
      //echo '<FONT color="FF0000"><b>' . 'NO CURRENT ATTRIBUTES ...' . '</b></FONT>';
      echo '<FONT color="FF0000"><b>' . QUICK_ATTRIBUTES_POPUP_TXT_3 . '</b></FONT>';
      echo '</td></tr></table>';
    }
    if ($products_attributes == '1') {
      $products_options_name = tep_db_query("select distinct popt.products_options_id, poptt.products_options_name, popt.products_options_sort_order from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " poptt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $look_it_up . "' and patrib.options_id = popt.products_options_id and poptt.language_id = '" . $languages_id . "'" . " order by popt.products_options_sort_order");
      echo '<table border="0" cellpading="0" cellspacing"0">';
      echo '<tr><td class="main" colspan="2">';
     // echo '<b>' . 'CURRENT ATTRIBUTES:' . '</b>';
      echo '<b>' . QUICK_ATTRIBUTES_POPUP_TXT_4 . '</b>';
      echo '</td></tr>';
      echo '<tr><td colspan="2">' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td></tr>';
      while ($products_options_name_values = tep_db_fetch_array($products_options_name)) {
        $selected = 0;
        $products_options_array = array();
        echo '<tr><td class="main">' . $products_options_name_values['products_options_name'] . ':</td><td>' . "\n";
        $products_options = tep_db_query("select pa.products_options_sort_order, pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $look_it_up . "' and pa.options_id = '" . $products_options_name_values['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . $languages_id . "'" . " order by pa.products_options_sort_order, pa.options_values_price");
        while ($products_options_values = tep_db_fetch_array($products_options)) {
          $products_options_array[] = array('id' => $products_options_values['products_options_values_id'], 'text' => $products_options_values['products_options_values_name']);
          if ($products_options_values['options_values_price'] != '0') {
            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options_values['price_prefix'] . $currencies->format($products_options_values['options_values_price']) .') ';
          }
        }
        echo tep_draw_pull_down_menu('id[' . $products_options_name_values['products_options_id'] . ']', $products_options_array, $cart->contents[$_GET['products_id']]['attributes'][$products_options_name_values['products_options_id']]);
        echo '</td></tr>';
      }
      echo '</table>';
    }

    echo '</td></tr></table>';
// EOF: attribute options
///////////////////////////////////////////////////////////////////////////
?>

<br><br>
<center>
<table align="center" border="1" cellpadding="6" cellspacing="3"><tr><td class="main">
<a href="javascript:window.close()"> <!-- Close Window --> <?php echo QUICK_ATTRIBUTES_POPUP_TXT_5;?> </a>
</td></tr></table>
</center>
<br><br>

</body>
</html>
