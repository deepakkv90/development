<?php
/*
  $Id: froogle_pre.php,v 1.1.1.1  zip1 Exp $
  http://www.oscommerce.com
   Froogle Data Feeder!

  Copyright (c) 2002 - 2005 Calvin K

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
//  require(DIR_WS_LANGUAGES . froogle_pre.php');
  include(DIR_WS_LANGUAGES . $language . '/froogle_pre.php');

  function tep_get_parent_categories(&$categories, $categories_id) {
    $parent_categories_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$categories_id . "'");
    while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
      if ($parent_categories['parent_id'] == 0) return true;
      $categories[sizeof($categories)] = $parent_categories['parent_id'];
      if ($parent_categories['parent_id'] != $categories_id) {
        tep_get_parent_categories($categories, $parent_categories['parent_id']);
      }
    }
  }

  function tep_get_product_path($products_id) {
    $cPath = '';

    $category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1");
    if (tep_db_num_rows($category_query)) {
      $category = tep_db_fetch_array($category_query);

      $categories = array();
      tep_get_parent_categories($categories, $category['categories_id']);

      $categories = array_reverse($categories);

      $cPath = implode(' > ', $categories);

      if (tep_not_null($cPath)) $cPath .= ' > ';
      $cPath .= $category['categories_id'];
    }

    return $cPath;
  }


 function tep_get_categories_name($cats_id) {
   $categories_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.categories_id = '" . (int)$cats_id . "' ");
    while ($categories = tep_db_fetch_array($categories_query)) {
      $cats_name = $categories['categories_name'];
      }

 return $cats_name;

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
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<div id="body">
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="body-table">
  <tr>  <!-- left_navigation //-->
  <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
  <!-- left_navigation_eof //-->
  <!-- body_text //-->
  <td class="page-container" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
ation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="menuBoxHeading">
                         <tr>
                     <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                       <tr>
                         <td class="pageHeading"><?php echo HEADING_TITLE ; ?></td>
                        </tr>
                        <tr>
                        <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                       </tr>
                     </table></td>
                   </tr>
    <tr>
        <td>
<!--   Run category build        run feed build
 -->   <?php echo TEXT_OUTPUT_20;?>     </td>
      </tr>
   <tr>
        <td>
 <?php

//if ($action == categories)
//  Start TIMER
//  -----------
$stimer = explode( ' ', microtime() );
$stimer = $stimer[1] + $stimer[0];

//  -----------
    $data_files_id1 = (int)$_POST['feed_froogle'];
    //$data_files_id1 = '2';
    $data_query_raw = tep_db_query("select * from  " . TABLE_DATA_FILES . " where data_files_id = '" . $data_files_id1 . "' order by data_files_service ");
    while ($data = tep_db_fetch_array($data_query_raw)) {
  $data_files_id = $data[data_files_id];
  $data_files_type = $data[data_files_type];
  $data_files_disc = $data[data_files_disc];
  $data_files_type1 = $data[data_files_type1];
  $data_files_service = $data[data_files_service];
  $data_status = $data[data_status];
  $data_files_name = $data[data_files_name];
  $data_image_url = $data[data_image_url];
  $ftp_server = $data[data_ftp_server];
  $ftp_user_name = $data[data_ftp_user_name];
  $ftp_user_pass = $data[data_ftp_user_pass];
  $ftp_directory = $data[data_ftp_directory];
  $data_tax_class_id = $data[data_tax_class_id];
  $data_convert_cur = $data[data_convert_cur];
  $data_cur_use = $data[data_cur_use];
  $data_cur = $data[data_cur];
  $data_lang_use = $data[data_lang_use];
  $data_lang = $data[data_lang];

  }


$sql = "
SELECT
products_id AS id,
categories_id AS prodCatID
FROM
products_to_categories
";




$result=tep_db_query( $sql )or die( $FunctionName . ": SQL error " . mysql_error() . "| sql = " . htmlentities($sql) );



$loop_counter = 0;

while( $row = tep_db_fetch_array( $result ) )
{
$PROD_tree=tep_get_product_path($row[id]);

$catPath = explode(' > ', $PROD_tree);
$value1 = ' ';
foreach ($catPath as $value) {
   $value1 .= tep_get_categories_name($value) . ' > ';
   $value3 = rtrim($value1, "> ");
   $value2 = ltrim($value3, "&nbsp;");
   }
   $cat_query = tep_db_query("select * from  " . TABLE_DATA_CAT . " where cat_id = '" . $row[prodCatID] ."' ");
if (tep_db_num_rows($cat_query) < '1') {

 $sql_data_array13 = array('cat_id'  => $row[prodCatID],
                           'cat_tree'  => $value2);

 tep_db_perform(TABLE_DATA_CAT, $sql_data_array13, 'insert' );
 }
if (tep_db_num_rows($cat_query) < '1') {
     }
}



//  End TIMER
//  ---------
$etimer = explode( ' ', microtime() );
$etimer = $etimer[1] + $etimer[0];
echo '<p style="margin:auto; text-align:center">';
printf( TEXT_INFO_TIMER . " <b>%f</b> "  . TEXT_INFO_SECOND, ($etimer-$stimer) );
echo '</p>';
//  ---------
echo '<br> &nbsp;' . TEXT_INFO_DONE . tep_draw_form('run', FILENAME_FROOGLE_ADMIN, 'action=run', 'post', '');
echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . tep_image_submit('button_return.gif', TEXT_INFO_DONE) . '</form>';

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