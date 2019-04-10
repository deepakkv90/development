<?php
/*
  $Id: froogle_pre.php,v 1.1.1.1  zip1 Exp $
  http://www.oscommerce.com
   Froogle Data Feeder!

  Copyright (c) 2002 - 2005 Calvin K

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
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

<?php


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


require('includes/froogle_configure.php');

$OutFile = DIR_FS_ADMIN . "feeds/" . $data_files_name;
$destination_file = DIR_WS_ADMIN . "feeds/" . $data_files_name;
$source_file = $OutFile;
$imageURL = HTTP_CATALOG_SERVER . DIR_WS_CATALOG . 'images/' . $data_image_url ;
$productURL1 = HTTP_CATALOG_SERVER . DIR_WS_CATALOG .'product_info.php?products_id=';

$already_sent = array();


$taxCalc = ($taxRate/100) + 1;  //Do not edit
$convertCur = $data_convert_cur; //default = false
$curType = $data_cur; // Converts Currency to any defined currency (eg. USD, EUR, GBP)
if($convertCur == 'true')
{
$productURL1 = HTTP_SERVER . DIR_WS_CATALOG . "product_info.php?currency=" . $curType . "&products_id=";  //where CURTYPE is your currency type (eg. USD, EUR, GBP)
}

$sql = "SELECT products.products_id AS product_url,
               products_model AS prodModel,
               products_weight,
               manufacturers.manufacturers_name AS mfgName,
               manufacturers.manufacturers_id,
               products.products_id AS id,
               products_description.products_name AS name,
               products_description.products_description AS description,
               products.products_quantity AS quantity,
               products.products_status AS prodStatus,
               FORMAT( IFNULL(specials.specials_new_products_price, products.products_price) ,2) AS price,
               CONCAT( '" . $imageURL . "' ,products.products_image) AS image_url,
               products_to_categories.categories_id AS prodCatID,
               categories.parent_id AS catParentID,
               categories_description.categories_name AS catName
        FROM categories,
             categories_description,
             products_description,
             products_to_categories,
             products
        left join manufacturers on ( manufacturers.manufacturers_id = products.manufacturers_id )
        left join specials on ( specials.products_id = products.products_id AND ( ( (specials.expires_date > CURRENT_DATE) OR (specials.expires_date = 0) ) AND ( specials.status = 1 ) ) )
        WHERE products.products_id=products_description.products_id
          AND products.products_id=products_to_categories.products_id
          AND products_to_categories.categories_id=categories.categories_id
          AND categories.categories_id=categories_description.categories_id
        ORDER BY products.products_id ASC,
                 prodModel
       ";
function tep_get_products_special_price_fr($product_id) {
  global $link;
  
  $product_sql = "select products_price, products_model from products where products_id = '" . $product_id . "'";
  $product_query = tep_db_query($product_sql);
  if (tep_db_num_rows($product_query)) {
    $product = tep_db_fetch_array($product_query);
    $product_price = $product['products_price'];
  } else {
    return false;
  }

  $specials_query = tep_db_query("select specials_new_products_price from specials where products_id = '" . $product_id . "' and status");
  if (tep_db_num_rows($specials_query)) {
    $special = tep_db_fetch_array($specials_query);
    $special_price = $special['specials_new_products_price'];
  } else {
    $special_price = false;
  }
  return $special_price;
}


  function tep_get_category_feed_path($products_id) {
     $category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1");
    if (tep_db_num_rows($category_query)) {
     $category = mysql_fetch_object($category_query);
      $category_query1 = tep_db_query("select * from " . TABLE_DATA_CAT . " where cat_id = '" . $category.p2c.categories_id . "'");
     $category1 = mysql_fetch_object($category_query1);
     }
$feed_cat= $category1['cat_tree'];
    return $feed_cat;
  }

$_strip_search = array(
"![\t ]+$|^[\t ]+!m", // remove leading/trailing space chars
'%[\r\n]+%m'); // remove CRs and newlines
$_strip_replace = array(
'',
'');
$_cleaner_array = array(">" => "> ", "&reg;" => "", "®" => "", "&trade;" => "", "™" => "");


if ( file_exists( $OutFile ) )
unlink( $OutFile );

// do not make language depented these must be in english only

$output = "link \t name \t description \t expiration_date \t price \t image_url \t product_type \t brand \t offer_id \t condition ";


//create optional section
if($optional_sec == 1)
{
// do not make language defines these must be in english only

  if($pquantity == 1)
    $output .= "\t quantity ";
  if($upc == 1)
    $output .= "\t upc ";
  if($manufacturer_id == 1)
    $output .= "\t manufacturer_id";
  if($currency == 1)
    $output .= "\t currency ";
  if($feed_language == 1)
    $output .= "\t language ";
  if($ship_to == 1)
    $output .= "\t ship_to ";
  if($ship_from == 1)
    $output .= "\t ship_from ";
  if($age_range == 1)
$output .= "\t age_range";
  if($made_in == 1)
$output .= "\t made_in";

}
$output .= "\n";
$result=tep_db_query( $sql )or die( $FunctionName . ": SQL error " . mysql_error() . "| sql = " . htmlentities($sql) );

//Currency Information uses store currency
if($convertCur == 'true')
{
  $sql3 = "
  SELECT
  currencies.value AS curUSD
  FROM
  currencies
  WHERE currencies.code = '$curType'
  ";

  $result3=mysql_fetch_object( $sql3 );
  //or die( $FunctionName . ": SQL error " . tep_db_error() . "| sql3 = " . htmlentities($sql3) );
  $row3 = mysql_fetch_object( $result3 );
}

$loop_counter = 0;

while( $row = mysql_fetch_object( $result ) )
{
  //Salesmaker changes... Show correct price....... Begin

   $products_price = $row->price;

   if ($new_price = tep_get_products_special_price_fr($row->id)) {
     $products_price = number_format($new_price, 4, '.', '');
     }

  $row->price = $products_price;

  if (isset($already_sent[$row->id])) continue; // if we've sent this one, skip the rest of the while loop

  if( $row->prodStatus == 1 || ($optional_sec == 1 && $instock == 1) )
  {
              // convert to another currency currency must be installed in cart.
    if($convertCur == 'true')
     {
      $row->price = ereg_replace("[^.0-9]", "", $row->price);
      $row->price = $row->price *  $row3->curUSD;
      $row->price = number_format($row->price, 2, '.', ',');
     }
  // calculate Taxes

         if ($data_tax_class_id == '0'){
         }else{
          $class_id = $data_tax_class_id;
          $tax = tep_get_tax_rate_value($class_id);
    $row->price = number_format($row->price + ($row->price * $tax / 100), 2, '.', ',');
    }

   // get category  data_cat table that was pre built
     $category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . $row->id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1");
     $category = mysql_fetch_object($category_query);
      $category_query1 = tep_db_query("select * from " . TABLE_DATA_CAT . " where cat_id = '" . $category->categories_id . "' limit 1");
     $category1 = mysql_fetch_object($category_query1);

 $feed_cat= $category1->cat_tree;

 $row->name_1= preg_replace($_strip_search, $_strip_replace, strip_tags( strtr($row->name, $_cleaner_array) ) );
 $row->description_1 =  preg_replace($_strip_search, $_strip_replace, strip_tags( strtr($row->description, $_cleaner_array) ) );

//remove tabs from within text fields.
$row->name_1a = str_replace("\x09", '', $row->name_1);
$row->description_1a = str_replace("\x09", '', $row->description_1);


  $row->name_2 = htmlentities($row->name_1a, ENT_QUOTES, 'iso-8859-1');
  $row->description_2 = htmlentities($row->description_1a, ENT_QUOTES, 'iso-8859-1');


//start to build output string

    $output .= $productURL1 . $row->product_url . "\t" .
    $row->name_2 . "\t" .
    $row->description_2 . "\t" .
    $feed_exp_date . "\t" .
    $row->price . "\t" .
    $row->image_url . "\t" .
    $feed_cat . "\t" .
    $row->mfgName . "\t" .
    $row->id . " \t " .
    $default_condition;

  //optional values section
  if($optional_sec == 1)
  {
    if($pquantity == 1){
     $output .= " \t " . $quantity;
   }

    if($upc == 1)
      $output .= " \t " . "Not Supported";
    if($manufacturer_id == 1)
      $output .= " \t " . $row->prodModel;
    if($currency == 1)
      $output .= " \t " . $default_currency;
    if($feed_language == 1)
      $output .= " \t " . $default_feed_language;
    if($ship_to == 1)
      $output .= " \t " . $default_ship_to;
    if($ship_from == 1)
      $output .= " \t " . $default_ship_from;
    if($age_range == 1)
      $output .= " \t " . $default_age_range;
    if($made_in == 1)
     $output .= " \t " . $default_made_in;
  }
  $output .= " \n";
  }
  $already_sent[$row->id] = 1;


  $loop_counter++;
  if ($loop_counter>750) {
  $fp = fopen( $OutFile , "a" );
  $fout = fwrite( $fp , $output );
  fclose( $fp );
  $loop_counter = 0;
  $output = "";
 }
}


$fp = fopen( $OutFile , "a" );
$fout = fwrite( $fp , $output );
fclose( $fp );
chmod($OutFile, 0777);

echo TEXT_OUTPUT_17 . $data_files_type . ' ' . $data_files_disc .  ' ' .  $data_files_type1 = $data[data_files_type1] . '<br>';
echo TEXT_OUTPUT_18."<a href=\"" . $destination_file . "\" target=\"_blank\">" . $destination_file . "</a><br>\n";
echo TEXT_OUTPUT_19;

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