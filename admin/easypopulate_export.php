<?php
/*
  $Id: easypopulate.php,v 3.01 2004/09/21  zip1 Exp $

    Released under the GNU General Public License
*/
$curver = '3.01 Advance';

//*******************************
// S T A R T
// INITIALIZATION
//*******************************

require('epconfigure.php');
include ('includes/functions/easypopulate_functions.php');
include (DIR_WS_LANGUAGES . $language . '/easypopulate.php');
//*******************************

//  Start TIMER
//  -----------
$stimer = explode( ' ', microtime() );
$stimer = $stimer[1] + $stimer[0];
global $filelayout, $filelayout_count, $filelayout_sql, $langcode, $fileheaders;

//elari check default language_id from configuration table DEFAULT_LANGUAGE
$epdlanguage_query = tep_db_query("select languages_id, name, code from " . TABLE_LANGUAGES . " where code = '" . DEFAULT_LANGUAGE . "'");
if (tep_db_num_rows($epdlanguage_query)) {
  $epdlanguage = tep_db_fetch_array($epdlanguage_query);
  $epdlanguage_id   = $epdlanguage['languages_id'];
  $epdlanguage_name = $epdlanguage['name'];
  $epdlanguage_code = $epdlanguage['code'];
} else {
  $msg_error = EASY_ERROR_1;
}

$langcode = ep_get_languages();

// Eversun mod for Modify Easy Populate for SPPC
$customers_groups = array();
$customers_groups_query = tep_db_query("select customers_group_id from customers_groups where customers_group_id <> 0");
while ($customers_groups_array = tep_db_fetch_array($customers_groups_query)) {
  $customers_groups[] = $customers_groups_array['customers_group_id'];
}
// Eversun mod end for Modify Easy Populate for SPPC

$download = isset($_POST['download']) ? $_POST['download'] : '';
$dltype = isset($_POST['dltype']) ? $_POST['dltype'] : '';
$catsort = isset($_POST['catsort']) ? $_POST['catsort'] : '';
$limit_cat = isset($_POST['limit_cat']) ? $_POST['limit_cat'] : '';
$limit_man = isset($_POST['limit_man']) ? $_POST['limit_man'] : '';
$rangebegin = isset($_POST['rangebegin']) ? $_POST['rangebegin'] : '';
$rangeend = isset($_POST['rangeend']) ? $_POST['rangeend'] : '';

//end intilization
// queries to pull data
if ( $dltype != '' ){
  // if dltype is set, then create the filelayout.  Otherwise it gets read from the uploaded file

  global $GLOBALS, $filelayout, $filelayout_count, $filelayout_sql, $langcode, $fileheaders, $max_categories, $rangebegin, $rangeend, $catsort, $catfilter, $BEGIN1, $BEEND1, $limit_man, $limit_cat, $categories_range;
  // depending on the type of the download the user wanted, create a file layout for it.
  $fieldmap = array(); // default to no mapping to change internal field names to external.

  //category range to download
  switch( $dltype ){
    case 'full':
    case 'category':
      if ($limit_cat == '0'){
      } else {
        /**************/
        $str_query1 = tep_db_query("select parent_id from categories where categories_id = '".$limit_cat."'");
        $result_1 = tep_db_fetch_array($str_query1);
        $cat_parent_id = $result_1['parent_id'];

        if($cat_parent_id == 0){
          $categories_range .= 'subc.parent_id = \'' . $limit_cat. '\' and ';
        } else {
          $categories_range .= 'ptoc.categories_id = \'' . $limit_cat. '\' and ';
        }
        /**************/ 

       // for one level down
       //$categories_range .= 'ptoc.categories_id =  \'' . $limit_cat. '\' and ';

// for two levels down
//  $catfield=tep_get_category_treea($limit_cat);
//          $categories_range .= "( ";
//for ($i=0, $n=sizeof($catfield); $i<$n; $i++) {
//  $categories_range .= 'ptoc.categories_id = ' . "'"  . tep_output_string($catfield[$i]['id'] . "' ");
//if ($i<$n){
// $categories_range .= ' or ';
//         }
//
//    }
//     $categories_range=substr_replace($categories_range, ')and ', -4);

      }
      break;
    case 'priceqty':
    case 'attrib':
      if ($limit_cat == '0'){
      } else {
        $catfield=tep_get_category_treea($limit_cat);
        $categories_range .= "( ";
        for ($i=0, $n=sizeof($catfield); $i<$n; $i++) {
          $categories_range .= 'ptoc.categories_id = ' . "'"  . tep_output_string($catfield[$i]['id'] . "' ");
          if ($i<$n){
            $categories_range .= ' or ';
          }
        }
        $categories_range=substr_replace($categories_range, ')and ', -4);
      }
      break;
  }
//manufactur range to download

  switch( $dltype ){
    case 'full':
    case 'category':
      if ($limit_man == '0'){
      } else {
        $limit_man1= 'p.manufacturers_id = \'' . $limit_man. '\' and ';
      }
    case 'attrib':
    case 'priceqty':
      break;
  }

//product range to download
  switch( $dltype ){
    case 'full':
    case 'fullwosppc':
    case 'category':
      if ($rangebegin != ''){
        $BEGIN1= 'p.products_id >= \'' . $rangebegin . '\' and';
      } else {
      }
      if ($rangeend != ''){
        $BEEND1= 'p.products_id <= \'' . $rangeend . '\' and';
      } else {
      }
      break;
    case 'priceqty':
    case 'attrib':
      if ( ($rangebegin != '') and ($rangeend == '') ){
        $BEGIN1= 'where p.products_id >= \'' . $rangebegin . '\' and';
      } else if ( ($rangebegin == '') && ($rangeend != '') ){
        $BEEND1= 'where p.products_id <= \'' . $rangeend . '\' ';
      } else if ( ($rangeend != '') && ($rangeend != '') ){
        $BEEND1= 'where  p.products_id >= \'' . $rangebegin . '\' and p.products_id <= \'' . $rangeend . '\' ';
      } else {
      }
      break;
  }
//sort order by category,product, number, manufacture
  switch( $dltype ){
    case 'full':
      if ($catsort == 'none') {
        $catfil= 'ORDER BY p.products_id, pg.customers_group_id';
      } elseif (($catsort == 'category') && ($dltype!= 'attrib')) {
        $catfil= 'ORDER BY subc.categories_id, pg.customers_group_id';
      } elseif($catsort == 'product') {
        $catfil= 'ORDER BY p.products_id, pg.customers_group_id';
      } elseif ($catsort == 'manufacture') {
        $catfil= 'ORDER BY p.manufacturers_id, pg.customers_group_id';
      }
      break;
    case 'category':
    case 'priceqty':
    case 'fullwosppc':
      if ($dltype == 'attrib'){
      } else {
        $WHERE = 'WHERE';
      }
      if ($catsort == 'none'){
        $catfil= '';
      }
      if (($catsort == 'category') && ($dltype!= 'attrib')) {
       $catfil= 'ORDER BY subc.categories_id';
      }
      if($catsort == 'product'){
        $catfil= 'ORDER BY p.products_id';
      }
      if ($catsort == 'manufacture'){
        $catfil= 'ORDER BY p.manufacturers_id';
      }
    case 'attrib':
      break;
  }
  switch( $dltype ){
    case 'fullwosppc':
      // The file layout is dynamically made depending on the number of languages
      $iii = 0;
      $filelayout = array(
              'v_products_id'          => $iii++,
              'v_vendors_id'          => $iii++,
              'v_products_model'          => $iii++,
              'v_products_parent_id' => $iii++,
              'v_products_image'          => $iii++,
              'v_products_image_med'          => $iii++,
              'v_products_image_lrg'          => $iii++,
              'v_products_image_sm_1'          => $iii++,
              'v_products_image_xl_1'          => $iii++,
              'v_products_image_sm_2'          => $iii++,
              'v_products_image_xl_2'          => $iii++,
              'v_products_image_sm_3'          => $iii++,
              'v_products_image_xl_3'          => $iii++,
              'v_products_image_sm_4'          => $iii++,
              'v_products_image_xl_4'          => $iii++,
              'v_products_image_sm_5'          => $iii++,
              'v_products_image_xl_5'          => $iii++,
              'v_products_image_sm_6'          => $iii++,
              'v_products_image_xl_6'          => $iii++
              );

      foreach ($langcode as $key => $lang){
        $l_id = $lang['id'];
        // uncomment the head_title, head_desc, and head_keywords to use
        // Linda's Header Tag Controller 2.0
        $filelayout  = array_merge($filelayout , array(
                         'v_products_name_' . $l_id          => $iii++,
                         'v_products_blurb_' . $l_id     => $iii++,
                         'v_products_description_' . $l_id     => (str_replace('"', '\"', $iii++)),
                         'v_products_url_' . $l_id     => $iii++,
                         'v_products_head_title_tag_'.$l_id     => $iii++,
                         'v_products_head_desc_tag_'.$l_id     => $iii++,
                         'v_products_head_keywords_tag_'.$l_id     => $iii++,
                         ));
      }
      // uncomment the customer_price and customer_group to support multi-price per product contrib
      $header_array = array(
               'v_products_price'          => $iii++,
               'v_products_weight'          => $iii++,
               'v_date_avail'               => $iii++,
               'v_date_added'               => $iii++,
               'v_products_quantity'          => $iii++,
               );
      $languages = tep_get_languages();
      $header_array['v_manufacturers_name'] = $iii++;
      $filelayout = array_merge($filelayout, $header_array);
      // build the categories name section of the array based on the number of categores the user wants to have
      for($i=1;$i<$max_categories+1;$i++){
        $filelayout = array_merge($filelayout, array('v_categories_name_' . $i => $iii++));
      }
      $filelayout = array_merge($filelayout, array('v_categories_image' => $iii++, 
               'v_tax_class_title'          => $iii++,
               'v_prod_products_group_access' => $iii++,
               'v_status'               => $iii++,
               'v_action'               => $iii++,
               ));
      $filelayout_sql = "SELECT
               p.products_id as v_products_id,
               p.vendors_id as v_vendors_id,
               p.products_model as v_products_model,
               p.products_parent_id as v_products_parent_id,
               p.products_image as v_products_image,
               p.products_image_med as v_products_image_med,
               p.products_image_lrg as v_products_image_lrg,
               p.products_image_sm_1 as v_products_image_sm_1,
               p.products_image_xl_1 as v_products_image_xl_1,
               p.products_image_sm_2 as v_products_image_sm_2,
               p.products_image_xl_2 as v_products_image_xl_2,
               p.products_image_sm_3 as v_products_image_sm_3,
               p.products_image_xl_3 as v_products_image_xl_3,
               p.products_image_sm_4 as v_products_image_sm_4,
               p.products_image_xl_4 as v_products_image_xl_4,
               p.products_image_sm_5 as v_products_image_sm_5,
               p.products_image_xl_5 as v_products_image_xl_5,
               p.products_image_sm_6 as v_products_image_sm_6,
               p.products_image_xl_6 as v_products_image_xl_6,
               p.products_price as v_products_price,
               p.products_weight as v_products_weight,
               p.products_date_available as v_date_avail,
               p.products_date_added as v_date_added,
               p.products_tax_class_id as v_tax_class_id,
               p.products_quantity as v_products_quantity,
               p.manufacturers_id as v_manufacturers_id,
               subc.categories_id as v_categories_id,
               subc.categories_image as v_categories_image,
               p.products_group_access as v_prod_products_group_access,
               p.products_status as v_status
               FROM
               ".TABLE_PRODUCTS." as p,
               ".TABLE_CATEGORIES." as subc,
               ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
               WHERE
               ".$categories_range."
               ".$limit_man1."
               p.products_id = ptoc.products_id AND
               ".$BEGIN1."
               ".$BEEND1."
               ptoc.categories_id = subc.categories_id
               ".$catfil."
               ";

      break;

    case 'priceqty':
      $iii = 0;
      $filelayout = array(
          'v_products_id'          => $iii++,  //added
          'v_vendors_id'          => $iii++, 
          'v_products_model'          => $iii++,
          'v_products_parent_id' => $iii++,
          'v_products_price'          => $iii++,
          'v_products_quantity'          => $iii++,
          );
      $filelayout_sql = "SELECT
          p.products_id as v_products_id,
          p.vendors_id as v_vendors_id,
          p.products_model as v_products_model,
          p.products_parent_id as v_products_parent_id,
          p.products_price as v_products_price,
          p.products_quantity as v_products_quantity
          FROM
          ".TABLE_PRODUCTS." as p
          ".$BEGIN1."
          ".$BEEND1."
          ".$categories_range."
          ".$limit_man1."
          ".$catfil."
          ";
      break;
      
    case 'full':
      // Eversun mod for Modify Easy Populate for SPPC
      tep_db_query("DROP TABLE IF EXISTS products_groups_tmp");
      tep_db_query("CREATE TABLE products_groups_tmp (
                  customers_group_id smallint( 5 ) unsigned NOT NULL default '0',
                  customers_group_price decimal( 15, 4 ) NOT NULL default '0.0000',
                  customers_group_price1 decimal( 15, 4 ) NOT NULL default '0.0000',
                  customers_group_price2 decimal( 15, 4 ) NOT NULL default '0.0000',
                  customers_group_price3 decimal( 15, 4 ) NOT NULL default '0.0000',
                  customers_group_price4 decimal( 15, 4 ) NOT NULL default '0.0000',
                  customers_group_price5 decimal( 15, 4 ) NOT NULL default '0.0000',
                  customers_group_price6 decimal( 15, 4 ) NOT NULL default '0.0000',
                  customers_group_price7 decimal( 15, 4 ) NOT NULL default '0.0000',
                  customers_group_price8 decimal( 15, 4 ) NOT NULL default '0.0000',
                  customers_group_price9 decimal( 15, 4 ) NOT NULL default '0.0000',
                  customers_group_price10 decimal( 15, 4 ) NOT NULL default '0.0000',
                  customers_group_price11 decimal( 15, 4 ) NOT NULL default '0.0000',
                  products_id int( 11 ) NOT NULL default '0',
                  PRIMARY KEY ( customers_group_id , products_id )
                  )");
      //    tep_db_query("insert into products_groups_tmp select * from products_groups");
      $products_groups_query = tep_db_query("select * from products_groups");
      while ($products_groups = tep_db_fetch_array($products_groups_query)) {
        if ($products_groups['customers_group_id'] != 0) {
          $products_groups_array = array('customers_group_id' => $products_groups['customers_group_id'],
                                         'customers_group_price' => $products_groups['customers_group_price'],
                                         'customers_group_price1' => $products_groups['customers_group_price1'],
                                         'customers_group_price2' => $products_groups['customers_group_price2'],
                                         'customers_group_price3' => $products_groups['customers_group_price3'],
                                         'customers_group_price4' => $products_groups['customers_group_price4'],
                                         'customers_group_price5' => $products_groups['customers_group_price5'],
                                         'customers_group_price6' => $products_groups['customers_group_price6'],
                                         'customers_group_price7' => $products_groups['customers_group_price7'],
                                         'customers_group_price8' => $products_groups['customers_group_price8'],
                                         'customers_group_price9' => $products_groups['customers_group_price9'],
                                         'customers_group_price10' => $products_groups['customers_group_price10'],
                                         'customers_group_price11' => $products_groups['customers_group_price11'],
                                         'products_id' => $products_groups['products_id']);
          tep_db_perform('products_groups_tmp', $products_groups_array);
        }
      }
      $prod_query = tep_db_query("select products_id, vendors_id, products_price, products_price1, products_price2, products_price3, products_price4, products_price5, products_price6, products_price7, products_price8, products_price9, products_price10, products_price11 from " . TABLE_PRODUCTS);
      while ($prod_array = tep_db_fetch_array($prod_query)) {
        tep_db_query("insert into products_groups_tmp values (0, '" . $prod_array['products_price'] . "', '" . $prod_array['products_price1'] . "', '" . $prod_array['products_price2'] . "', '" . $prod_array['products_price3'] . "', '" . $prod_array['products_price4'] . "', '" . $prod_array['products_price5'] . "', '" . $prod_array['products_price6'] . "', '" . $prod_array['products_price7'] . "', '" . $prod_array['products_price8'] . "', '" . $prod_array['products_price9'] . "', '" . $prod_array['products_price10'] . "', '" . $prod_array['products_price11'] . "', '" . $prod_array['products_id'] . "')");
      }
      // Eversun mod end for Modify Easy Populate for SPPC

      // The file layout is dynamically made depending on the number of languages
      $iii = 0;
      $filelayout = array(
          'v_products_id'   => $iii++,
          'v_vendors_id'   => $iii++,
          'v_products_model'    => $iii++,
          'v_products_parent_id' => $iii++,
          'v_customers_group_id' => $iii++,
          'v_products_image'    => $iii++,
          'v_products_image_med'    => $iii++,
          'v_products_image_lrg'    => $iii++,
          'v_products_image_sm_1'   => $iii++,
          'v_products_image_xl_1'   => $iii++,
          'v_products_image_sm_2'   => $iii++,
          'v_products_image_xl_2'   => $iii++,
          'v_products_image_sm_3'   => $iii++,
          'v_products_image_xl_3'   => $iii++,
          'v_products_image_sm_4'   => $iii++,
          'v_products_image_xl_4'   => $iii++,
          'v_products_image_sm_5'   => $iii++,
          'v_products_image_xl_5'   => $iii++,
          'v_products_image_sm_6'   => $iii++,
          'v_products_image_xl_6'   => $iii++
          );
      foreach ($langcode as $key => $lang){
        $l_id = $lang['id'];
        // uncomment the head_title, head_desc, and head_keywords to use
        // Linda's Header Tag Controller 2.0
        $filelayout  = array_merge($filelayout , array(
          'v_products_name_' . $l_id    => $iii++,
          'v_products_blurb_' . $l_id => $iii++,
          'v_products_description_' . $l_id => (str_replace('"', '\"', $iii++)),
          'v_products_url_' . $l_id => $iii++,
          'v_products_head_title_tag_'.$l_id  => $iii++,
          'v_products_head_desc_tag_'.$l_id => $iii++,
          'v_products_head_keywords_tag_'.$l_id => $iii++,
          ));
      }
      $header_array = array(
//      'v_products_price'    => $iii++,
      'v_products_weight'   => $iii++,
      'v_date_avail'      => $iii++,
      'v_date_added'      => $iii++,
      'v_products_quantity'   => $iii++,
      );

      // Eversun mod for Modify Easy Populate for SPPC
      $my_default_array1 = array(
          'v_products_price' => $iii++,
          'v_products_price1' => $iii++,
          'v_products_price2' => $iii++,
          'v_products_price3' => $iii++,
          'v_products_price4' => $iii++,
          'v_products_price5' => $iii++,
          'v_products_price6' => $iii++,
          'v_products_price7' => $iii++,
          'v_products_price8' => $iii++,
          'v_products_price9' => $iii++,
          'v_products_price10' => $iii++,
          'v_products_price11' => $iii++,
          'v_products_price1_qty' => $iii++,
          'v_products_price2_qty' => $iii++,
          'v_products_price3_qty' => $iii++,
          'v_products_price4_qty' => $iii++,
          'v_products_price5_qty' => $iii++,
          'v_products_price6_qty' => $iii++,
          'v_products_price7_qty' => $iii++,
          'v_products_price8_qty' => $iii++,
          'v_products_price9_qty' => $iii++,
          'v_products_price10_qty' => $iii++,
          'v_products_price11_qty' => $iii++);

      $header_array = array_merge($header_array, $my_default_array1);
      // Eversun mod end for Modify Easy Populate for SPPC
      $languages = tep_get_languages();
      $header_array['v_manufacturers_name'] = $iii++;
      $filelayout = array_merge($filelayout, $header_array);

      // build the categories name section of the array based on the number of categores the user wants to have
      for($i=1;$i<$max_categories+1;$i++){
        $filelayout = array_merge($filelayout, array('v_categories_name_' . $i => $iii++));
      }
      $filelayout = array_merge($filelayout, array('v_categories_image' => $iii++, 
          'v_tax_class_title'   => $iii++,
          'v_prod_products_group_access' => $iii++,
          'v_status'      => $iii++,
          'v_action'      => $iii++,
          ));
      $filelayout_sql = "SELECT
          p.products_id as v_products_id,
          p.vendors_id as v_vendors_id,
          p.products_model as v_products_model,
          p.products_parent_id as v_products_parent_id,
          pg.customers_group_id as v_customers_group_id,
          p.products_image as v_products_image,
          p.products_image_med as v_products_image_med,
          p.products_image_lrg as v_products_image_lrg,
          p.products_image_sm_1 as v_products_image_sm_1,
          p.products_image_xl_1 as v_products_image_xl_1,
          p.products_image_sm_2 as v_products_image_sm_2,
          p.products_image_xl_2 as v_products_image_xl_2,
          p.products_image_sm_3 as v_products_image_sm_3,
          p.products_image_xl_3 as v_products_image_xl_3,
          p.products_image_sm_4 as v_products_image_sm_4,
          p.products_image_xl_4 as v_products_image_xl_4,
          p.products_image_sm_5 as v_products_image_sm_5,
          p.products_image_xl_5 as v_products_image_xl_5,
          p.products_image_sm_6 as v_products_image_sm_6,
          p.products_image_xl_6 as v_products_image_xl_6,
          pg.customers_group_price as v_products_price,
          pg.customers_group_price1 as v_products_price1,
          pg.customers_group_price2 as v_products_price2,
          pg.customers_group_price3 as v_products_price3,
          pg.customers_group_price4 as v_products_price4,
          pg.customers_group_price5 as v_products_price5,
          pg.customers_group_price6 as v_products_price6,
          pg.customers_group_price7 as v_products_price7,
          pg.customers_group_price8 as v_products_price8,
          pg.customers_group_price9 as v_products_price9,
          pg.customers_group_price10 as v_products_price10,
          pg.customers_group_price11 as v_products_price11,
          p.products_price1_qty as v_products_price1_qty,
          p.products_price2_qty as v_products_price2_qty,
          p.products_price3_qty as v_products_price3_qty,
          p.products_price4_qty as v_products_price4_qty,
          p.products_price5_qty as v_products_price5_qty,
          p.products_price6_qty as v_products_price6_qty,
          p.products_price7_qty as v_products_price7_qty,
          p.products_price8_qty as v_products_price8_qty,
          p.products_price9_qty as v_products_price9_qty,
          p.products_price10_qty as v_products_price10_qty,
          p.products_price11_qty as v_products_price11_qty,
          p.products_weight as v_products_weight,
          p.products_date_available as v_date_avail,
          p.products_date_added as v_date_added,
          p.products_tax_class_id as v_tax_class_id,
          p.products_quantity as v_products_quantity,
          p.manufacturers_id as v_manufacturers_id,
          subc.categories_id as v_categories_id,
          subc.categories_image as v_categories_image,
          p.products_group_access as v_prod_products_group_access,
          p.products_status as v_status
          FROM
          ".TABLE_PRODUCTS." as p,
          products_groups_tmp as pg,
          ".TABLE_CATEGORIES." as subc,
          ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
          WHERE p.products_id = pg.products_id and
          ".$categories_range."
          ".$limit_man1."
          p.products_id = ptoc.products_id AND
          ".$BEGIN1."
          ".$BEEND1."
          ptoc.categories_id = subc.categories_id
          ".$catfil."
          ";
      break;
    
    case 'priceqty':
      $iii = 0;
      // uncomment the customer_price and customer_group to support multi-price per product contrib
      $filelayout = array(
        'v_products_id'   => $iii++,  //added
        'v_vendors_id'   => $iii++,
        'v_products_model'    => $iii++,
        'v_products_parent_id' => $iii++,
        'v_products_price'    => $iii++,
        'v_products_price1'   => $iii++,
        'v_products_price2'   => $iii++,
        'v_products_price3'   => $iii++,
        'v_products_price4'   => $iii++,
        'v_products_price5'   => $iii++,
        'v_products_price6'   => $iii++,
        'v_products_price7'   => $iii++,
        'v_products_price8'   => $iii++,
        'v_products_price9'   => $iii++,
        'v_products_price10'    => $iii++,
        'v_products_price11'    => $iii++,
        'v_products_price1_qty'   => $iii++,
        'v_products_price2_qty'   => $iii++,
        'v_products_price3_qty'   => $iii++,
        'v_products_price4_qty'   => $iii++,
        'v_products_price5_qty'   => $iii++,
        'v_products_price6_qty'   => $iii++,
        'v_products_price7_qty'   => $iii++,
        'v_products_price8_qty'   => $iii++,
        'v_products_price9_qty'   => $iii++,
        'v_products_price10_qty'    => $iii++,
        'v_products_price11_qty'    => $iii++,
        'v_products_quantity'   => $iii++,
        );
      $filelayout_sql = "SELECT
          p.products_id as v_products_id,
          p.vendors_id as v_vendors_id,
          p.products_model as v_products_model,
          p.products_parent_id as v_products_parent_id,
          p.products_price as v_products_price,
          p.products_quantity as v_products_quantity
          FROM
          ".TABLE_PRODUCTS." as p
          ".$BEGIN1."
          ".$BEEND1."
          ".$categories_range."
          ".$limit_man1."
          ".$catfil."
          ";
      break;

    case 'category':
      // The file layout is dynamically made depending on the number of languages
      $iii = 0;
      $filelayout = array(
        'v_products_id'   => $iii++,
        'v_vendors_id'   => $iii++,
        'v_products_model'    => $iii++,
        'v_products_parent_id' => $iii++,
        );
      // build the categories name section of the array based on the number of categores the user wants to have
      for($i=1;$i<$max_categories+1;$i++){
        $filelayout = array_merge($filelayout, array('v_categories_name_' . $i => $iii++));
      }
      $filelayout = array_merge($filelayout, array('v_categories_image' => $iii++, 'v_action' => $iii++));

      $filelayout_sql = "SELECT
          p.products_id as v_products_id,
          p.products_model as v_products_model,
          p.vendors_id as v_vendors_id,
          p.products_parent_id as v_products_parent_id,
          subc.categories_id as v_categories_id,
          subc.categories_image as v_categories_image
          FROM
          ".TABLE_PRODUCTS." as p,
          ".TABLE_CATEGORIES." as subc,
          ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
          WHERE
          ".$categories_range."
          ".$limit_man1."
          p.products_id = ptoc.products_id AND
          ".$BEGIN1."
          ".$BEEND1."
           ptoc.categories_id = subc.categories_id
           ".$catfil."
           ";
      break;

    case 'attrib':
      // VJ product attributes begin
      $attribute_options_array = array();
      if ($products_with_attributes == true) {
        if (is_array($attribute_options_select) && (count($attribute_options_select) > 0)) {
          foreach ($attribute_options_select as $value) {
            $attribute_options_query = "select distinct products_options_id from " . TABLE_PRODUCTS_OPTIONS . " where products_options_name = '" . $value . "'";
            $attribute_options_values = tep_db_query($attribute_options_query);
            if ($attribute_options = tep_db_fetch_array($attribute_options_values)){
              $attribute_options_array[] = array('products_options_id' => $attribute_options['products_options_id']);
            }
          }
        } else {
          $attribute_options_query = "select distinct products_options_id from " . TABLE_PRODUCTS_OPTIONS . " order by products_options_id";
          $attribute_options_values = tep_db_query($attribute_options_query);
          while ($attribute_options = tep_db_fetch_array($attribute_options_values)){
            $attribute_options_array[] = array('products_options_id' => $attribute_options['products_options_id']);
          }
        }
      }
      $iii = 0;
      $filelayout = array(
            'v_products_id'   => $iii++,
            'v_products_model'  => $iii++,
            );
      $header_array = array();
      $languages = tep_get_languages();
      global $attribute_options_array;

      $attribute_options_count = 1;
      foreach ($attribute_options_array as $attribute_options_values) {
        $key1 = 'v_attribute_options_id_' . $attribute_options_count;
        $header_array[$key1] = $iii++;
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $l_id = $languages[$i]['id'];
          $key2 = 'v_attribute_options_name_' . $attribute_options_count . '_' . $l_id;
          $header_array[$key2] = $iii++;
        }
        $attribute_values_query = "select products_options_values_id  from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$attribute_options_values['products_options_id'] . "' order by products_options_values_id";
        $attribute_values_values = tep_db_query($attribute_values_query);
        $attribute_values_count = 1;
        while ($attribute_values = tep_db_fetch_array($attribute_values_values)) {
          $key3 = 'v_attribute_values_id_' . $attribute_options_count . '_' . $attribute_values_count;
          $header_array[$key3] = $iii++;
          $key4 = 'v_attribute_values_price_' . $attribute_options_count . '_' . $attribute_values_count;
          $header_array[$key4] = $iii++;
          for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
            $l_id = $languages[$i]['id'];
            $key5 = 'v_attribute_values_name_' . $attribute_options_count . '_' . $attribute_values_count . '_' . $l_id;
            $header_array[$key5] = $iii++;
          }
          $attribute_values_count++;
        }
        $attribute_options_count++;
      }
      $filelayout = array_merge($filelayout, $header_array);
      $filelayout_sql = "SELECT
          p.products_id as v_products_id,
          p.products_model as v_products_model
          FROM
          ".TABLE_PRODUCTS." as p
          ".$WHERE."
          ".$categories_range."
          ".$limit_man1."
          ".$BEGIN1."
          ".$BEEND1."
          ".$catfil."
          ";
      break;
     // VJ product attributes end
  }
  $filelayout_count = count($filelayout);

//end output
}

//build downlaod file
if ( $download == 'stream' or  $download == 'tempfile' ){
  //*******************************
  //*******************************
  // DOWNLOAD FILE
  //*******************************
  //*******************************
  $filestring = ""; // this holds the csv file we want to download

  $result = tep_db_query($filelayout_sql);
  $row =  tep_db_fetch_array($result);

  // Here we need to allow for the mapping of internal field names to external field names
  // default to all headers named like the internal ones
  // the field mapping array only needs to cover those fields that need to have their name changed
  if ( count($fileheaders) != 0 ){
    $filelayout_header = $fileheaders; // if they gave us fileheaders for the dl, then use them
  } else {
    $filelayout_header = $filelayout; // if no mapping was spec'd use the internal field names for header names
  }
  //We prepare the table heading with layout values
  foreach( $filelayout_header as $key => $value ){
    $filestring .= $key . $separator;
  }
  // now lop off the trailing tab
  $filestring = substr($filestring, 0, strlen($filestring)-1);

  // set the type
  $endofrow = $separator . 'EOREOR' . "\n";
  $filestring .= $endofrow;

  $num_of_langs = count($langcode);

  while ($row){
    // names and descriptions require that we loop thru all languages that are turned on in the store
    foreach ($langcode as $key => $lang){
      $lid = $lang['id'];
      $lcd = $lang['code'];

      // for each language, get the description and set the vals
      $sql2 = "SELECT *
        FROM ".TABLE_PRODUCTS_DESCRIPTION."
        WHERE
          products_id = " . $row['v_products_id'] . " AND
          language_id = '" . $lid . "'
         ";
      $result2 = tep_db_query($sql2);
      $row2 =  tep_db_fetch_array($result2);

      //added cpath
      // for the categories, we need to keep looping until we find the root category
      // start with v_categories_id
      // Get the category description
      // set the appropriate variable name
      // if parent_id is not null, then follow it up.
      // we'll populate an aray first, then decide where it goes in the
      $thecategory_id1 = $row['v_categories_id'];
      $fullcategory1 = ''; // this will have the entire category stack for froogle
      for( $categorylevel=1; $categorylevel<$max_categories+1; $categorylevel++){
        if ($thecategory_id1){
          // now get the parent ID if there was one
          $sq23 = "SELECT parent_id
            FROM ".TABLE_CATEGORIES."
            WHERE categories_id = " . $thecategory_id1;
          $result23 = tep_db_query($sq23);
          $row23 =  tep_db_fetch_array($result23);
          $theparent_id1 = $row23['parent_id'];
        }
        $cPath = $theparent_id1 .  '_'  . $row['v_categories_id'];
      }
      // I'm only doing this for the first language, since right now froogle is US only.. Fix later!
      // adding url for froogle, but it should be available no matter what

      $row['v_products_name_' . $lid]   = $row2['products_name'];
      $row['v_products_blurb_' . $lid]  = $row2['products_blurb'];
      $row['v_products_description_' . $lid]  = $row2['products_description'];
      $row['v_products_url_' . $lid]    = $row2['products_url'];

      // support for Linda's Header Controller 2.0 here
      if(isset($filelayout['v_products_head_title_tag_' . $lid])){
        $row['v_products_head_title_tag_' . $lid]   = $row2['products_head_title_tag'];
        $row['v_products_head_desc_tag_' . $lid]  = $row2['products_head_desc_tag'];
        $row['v_products_head_keywords_tag_' . $lid]  = $row2['products_head_keywords_tag'];
      }
      // end support for Header Controller 2.0
    }

    // for the categories, we need to keep looping until we find the root category

    // start with v_categories_id
    // Get the category description
    // set the appropriate variable name
    // if parent_id is not null, then follow it up.
    // we'll populate an aray first, then decide where it goes in the
    $thecategory_id = $row['v_categories_id'];
    $fullcategory = ''; // this will have the entire category stack for froogle
    for( $categorylevel=1; $categorylevel<$max_categories+1; $categorylevel++){
      if ($thecategory_id){
        $sql2 = "SELECT categories_name
          FROM ".TABLE_CATEGORIES_DESCRIPTION."
          WHERE
            categories_id = " . $thecategory_id . " AND
            language_id = " . $epdlanguage_id ;

        $result2 = tep_db_query($sql2);
        $row2 =  tep_db_fetch_array($result2);
        // only set it if we found something
        $temprow['v_categories_name_' . $categorylevel] = $row2['categories_name'];
        // now get the parent ID if there was one
        $sql3 = "SELECT parent_id
          FROM ".TABLE_CATEGORIES."
          WHERE
            categories_id = " . $thecategory_id;
        $result3 = tep_db_query($sql3);
        $row3 =  tep_db_fetch_array($result3);
        $theparent_id = $row3['parent_id'];
        if ($theparent_id != ''){
          // there was a parent ID, lets set thecategoryid to get the next level
          $thecategory_id = $theparent_id;
        } else {
          // we have found the top level category for this item,
          $thecategory_id = false;
        }
        //$fullcategory .= " > " . $row2['categories_name'];
        $fullcategory = $row2['categories_name'] . " > " . $fullcategory;
      } else {
        $temprow['v_categories_name_' . $categorylevel] = '';
      }
    }
    // now trim off the last ">" from the category stack
    $row['v_category_fullpath'] = substr($fullcategory,0,strlen($fullcategory)-3);

    // temprow has the old style low to high level categories.
    $newlevel = 1;
    // let's turn them into high to low level categories
    for( $categorylevel=6; $categorylevel>0; $categorylevel--){
      if ($temprow['v_categories_name_' . $categorylevel] != ''){
        $row['v_categories_name_' . $newlevel++] = $temprow['v_categories_name_' . $categorylevel];
      }
    }
    // if the filelayout says we need a manufacturers name, get it
    if (isset($filelayout['v_manufacturers_name'])){
      if ($row['v_manufacturers_id'] != ''){
        $sql2 = "SELECT manufacturers_name
          FROM ".TABLE_MANUFACTURERS."
          WHERE
          manufacturers_id = " . $row['v_manufacturers_id']
          ;
        $result2 = tep_db_query($sql2);
        $row2 =  tep_db_fetch_array($result2);
        $row['v_manufacturers_name'] = $row2['manufacturers_name'];
      }
    }

    // VJ product attribs begin
    if (isset($filelayout['v_attribute_options_id_1'])){
      $languages = tep_get_languages();

      $attribute_options_count = 1;
      foreach ($attribute_options_array as $attribute_options) {
        $row['v_attribute_options_id_' . $attribute_options_count]  = $attribute_options['products_options_id'];
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $lid = $languages[$i]['id'];
          $attribute_options_languages_query = "select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$attribute_options['products_options_id'] . "' and language_id = '" . (int)$lid . "'";
          $attribute_options_languages_values = tep_db_query($attribute_options_languages_query);
          $attribute_options_languages = tep_db_fetch_array($attribute_options_languages_values);
          $row['v_attribute_options_name_' . $attribute_options_count . '_' . $lid] = $attribute_options_languages['products_options_name'];
        }
        $attribute_values_query = "select products_options_values_id from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$attribute_options['products_options_id'] . "' order by products_options_values_id";
        $attribute_values_values = tep_db_query($attribute_values_query);
        $attribute_values_count = 1;
        while ($attribute_values = tep_db_fetch_array($attribute_values_values)) {
          $row['v_attribute_values_id_' . $attribute_options_count . '_' . $attribute_values_count]   = $attribute_values['products_options_values_id'];
          $attribute_values_price_query = "select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$row['v_products_id'] . "' and options_id = '" . (int)$attribute_options['products_options_id'] . "' and options_values_id = '" . (int)$attribute_values['products_options_values_id'] . "'";
          $attribute_values_price_values = tep_db_query($attribute_values_price_query);
          $attribute_values_price = tep_db_fetch_array($attribute_values_price_values);
          $row['v_attribute_values_price_' . $attribute_options_count . '_' . $attribute_values_count]  = $attribute_values_price['price_prefix'] . $attribute_values_price['options_values_price'];
          for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
            $lid = $languages[$i]['id'];
            $attribute_values_languages_query = "select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$attribute_values['products_options_values_id'] . "' and language_id = '" . (int)$lid . "'";
            $attribute_values_languages_values = tep_db_query($attribute_values_languages_query);
            $attribute_values_languages = tep_db_fetch_array($attribute_values_languages_values);
            $row['v_attribute_values_name_' . $attribute_options_count . '_' . $attribute_values_count . '_' . $lid] = $attribute_values_languages['products_options_values_name'];
          }
          $attribute_values_count++;
        }
        $attribute_options_count++;
      }
    }
    // VJ product attribs end

    // this is for the separate price per customer module
    if (isset($filelayout['v_customer_price_1'])){
      $sql2 = "SELECT
          customers_group_price,
          customers_group_id
        FROM
          ".TABLE_PRODUCTS_GROUPS."
        WHERE
        products_id = " . $row['v_products_id'] . "
        ORDER BY
        customers_group_id"
        ;
      $result2 = tep_db_query($sql2);
      $ll = 1;
      $row2 =  tep_db_fetch_array($result2);
      while( $row2 ){
        $row['v_customer_group_id_' . $ll]  = $row2['customers_group_id'];
        $row['v_customer_price_' . $ll]   = $row2['customers_group_price'];
        $row2 = tep_db_fetch_array($result2);
        $ll++;
      }
    }

    //elari -
    //We check the value of tax class and title instead of the id
    //Then we add the tax to price if $price_with_tax is set to 1

    $row_tax_multiplier     = tep_get_tax_class_rate($row['v_tax_class_id']);
    $row['v_tax_class_title']   = tep_get_tax_class_title($row['v_tax_class_id']);
    if ($price_with_tax == 'true'){
      $row['v_products_price']  = $row['v_products_price'] +
              ($price_with_tax * round($row['v_products_price'] * $row_tax_multiplier / 100,2));
    }

    // Now set the status to a word the user specd in the config vars
    if ( $row['v_status'] == '1' ){
      $row['v_status'] = $active;
    } else {
      $row['v_status'] = $inactive;
    }

    // remove any bad things in the texts that could confuse EasyPopulate
    $therow = '';
    foreach( $filelayout as $key => $value ){
      //echo "The field was $key<br>";

      $thetext = $row[$key];
      // kill the carriage returns and tabs in the descriptions, they're killing me!
      $thetext = str_replace("\r",' ',$thetext);
      $thetext = str_replace("\n",' ',$thetext);
      $thetext = str_replace("\t",' ',$thetext);
      // and put the text into the output separated by tabs
      $therow .= $thetext . $separator;
    }

    // lop off the trailing tab, then append the end of row indicator
    $therow = substr($therow,0,strlen($therow)-1) . $endofrow;

    $filestring .= $therow;
    // grab the next row from the db
    $row =  tep_db_fetch_array($result);
  }

  //End of create download
  #$EXPORT_TIME=time();
  $EXPORT_TIME = strftime('%Y%b%d-%H%I');
  $EXPORT_TIME = "EPA" . $EXPORT_TIME;

  // now either stream it to them or put it in the temp directory
  if ($download == 'stream'){
    //*******************************
    // STREAM FILE
    //*******************************
//    header("Content-type: application/vnd.ms-excel");
//    header("Content-disposition: attachment; filename=$EXPORT_TIME.$file_extension");
//    header("Pragma: no-cache");
//    header("Expires: 0");
    if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
      header('Content-Type: application/octetstream');
      header("Pragma: public");
      header("Cache-control: private");
    } else {
      header('Content-Type: application/octet-stream');
      header("Pragma: no-cache");
    }
    header('Cache-Control: no-store, no-cache, must-revalidate' );
    header('Cache-Control: post-check=0, pre-check=0', false );
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header('Content-Transfer-Encoding: Binary');
    header("Content-length: " . strlen($filestring) );
    header("Content-Disposition: attachment; filename=$EXPORT_TIME.$file_extension");
    
    echo $filestring;
    die();


  } else {
    //*******************************
    // PUT FILE IN TEMP DIR
    //*******************************
    $tmpfname = DIR_FS_DOCUMENT_ROOT . $tempdir . "$EXPORT_TIME.$file_extension";
    //unlink($tmpfname);
    $fp = fopen( $tmpfname, "w+");
    fwrite($fp, $filestring);
    fclose($fp);


  }
}   // *** END *** download section
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
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=450,height=300%,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <?php require(DIR_WS_INCLUDES . 'column_left.php');?>

    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo EASY_VERSION_A . EASY_VER_A . EASY_EXPORT; ?></td>
            </tr>
          </table>
          
          <?php
          $mesID = isset($_GET['mesID']) ? $_GET['mesID'] : '';
          if ($mesID == MSG1){
                 echo '<div>' . EASY_FILE_LOCATE . $tempdir .  $name . ".$file_extension" . '</div>';
          }
          
          if ($mesID == MSG2){
                 echo '<div>' . EASY_FILE_LOCATE2 .  $name . ".$file_extension" . '</div>';
          }
          ?>
          
          <div class="form-head">
            <?php
              echo EASY_LABEL_CREATE;
              //echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_file_export') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
            ?>
          </div>

          <?php echo tep_draw_form('localfile_export', 'easypopulate_export.php', 'action=export', 'post', 'ENCTYPE="multipart/form-data"'); ?>
          
          <div class="form-body">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td class="form-label">
                  <?php echo EASY_LABEL_CREATE_SELECT ; ?>
                </td>
                <td class="form-value">
                  <select name="download">
                  <option selected value ="stream" size="10"><?php echo EASY_LABEL_DOWNLOAD;?>
                  <option value="tempfile" size="10"><?php echo EASY_LABEL_CREATE_SAVE;?>
                  </select>
                </td>
                <td class="form-info">
                  <?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_select_method') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';?>
                </td>
              </tr>
            </table>
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td class="form-label">
                  <?php echo EASY_LABEL_SELECT_DOWN; ?>
                </td>
                <td class="form-value">
                  <select name="dltype">
                  <option selected value ="full" size="10"><?php echo EASY_LABEL_COMPLETE; ?>
                  <option value="priceqty" size="10"><?php echo EASY_LABEL_MPQ; ?>
                  <option value="category" size="10"><?php echo EASY_LABEL_EP_MC; ?>
                  </select>
                </td>
                <td class="form-info">
                  <?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_select_down') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> '; ?>
                </td>
              </tr>
            </table>
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td class="form-label">
                  <?php echo EASY_LABEL_SORT; ?>
                </td>
                <td class="form-value">
                  <select name="catsort">
                  <option selected value ="none" size="10"><?php echo EASY_LABEL_NONE ;?>
                  <option value="category" size="10"><?php echo EASY_LABEL_CATEGORY ;?>
                  <option value="product" size="10"><?php echo EASY_LABEL_PRODUCT ;?>
                  <option value="manufacture" size="10"><?php echo EASY_LABEL_MANUFACTURE ;?>
                  </select>
                </td>
                <td class="form-info">
                  <?php '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_select_sort') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> '; ?>
                </td>
              </tr>
            </table>
          </div>
          <div class="form-head">
            <?php
              echo EASY_LABEL_EP_LIMIT;
              //echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_limit_rows') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
            ?>
          </div>
          <div class="form-body">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td class="form-label">
                  <?php echo EASY_LABEL_LIMIT_CAT; ?>
                </td>
                <td class="form-value">
                  <?php
                  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id, c.sort_order from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.categories_name");
                  $category = tep_db_fetch_array($categories_query);
                  $current_category_id = '0';
                  echo  tep_draw_pull_down_menu('limit_cat', tep_get_category_tree(), $current_category_id);
                  ?>
                </td>
                <td class="form-info">
                  <?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_limit_cats') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> '; ?>
                </td>
              </tr>
            </table>
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td class="form-label">
                  <?php echo EASY_LABEL_LIMIT_MAN; ?>
                </td>
                <td class="form-value">
                  <?php
                    $manufacturers_array = array();
                    $manufacturers_array[] = array('id' => '0', 'text' => PULL_DOWN_MANUFACTURERS);
                    
                    $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
                    while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
                    $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                         'text' => $manufacturers['manufacturers_name']);
                    }
                    
                    echo tep_draw_pull_down_menu('limit_man', $manufacturers_array);
                  ?>                
                </td>
                <td class="form-info">
                  <?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_limit_man') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> '; ?>
                </td>
              </tr>
            </table>
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td class="form-label">
                  <?php echo  EASY_LABEL_PRODUCT_RANGE; ?>
                </td>
                
                <td class="form-value">
                  <?php echo EASY_LABEL_PRODUCT_BEGIN;?><INPUT TYPE="text" name="rangebegin" size="12">
                  <?php echo EASY_LABEL_PRODUCT_END;?><INPUT TYPE="text" name="rangeend" size="12">
                
                </td>
                <td class="form-info">
                  <?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_limit_product') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';?>

                </td>
              </tr>
            </table>
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td class="form-label">
                </td>
                <td class="form-value">
                  <?php // below are the queries to do the counts
                  $totalrows = tep_db_query("SELECT COUNT(*) FROM " . TABLE_PRODUCTS . "");
                  $first_query = tep_db_query("SELECT products_id FROM " . TABLE_PRODUCTS . "  ORDER BY products_id ASC LIMIT 1");
                  while ($firstid = tep_db_fetch_array($first_query)){
                  $firstid1 =  $firstid['products_id'];
                  }
                  $first_query2 = tep_db_query("SELECT products_id FROM " . TABLE_PRODUCTS . "  ORDER BY products_id DESC LIMIT 1");
                  while ($firstid2 = tep_db_fetch_array($first_query2)){
                  $firstid2a =  $firstid2['products_id'];
                  }
                  $total3 = 0;
                  $first_query3 = tep_db_query("SELECT products_id FROM " . TABLE_PRODUCTS . " ");
                  while ($firstid3 = tep_db_fetch_array($first_query3)){
                  $total3 = $total3 + 1 ;
                  }
                  ?>
                  <?php echo EASY_LABEL_PRODUCT_AVAIL . $firstid1 . EASY_LABEL_PRODUCT_TO . $firstid2a . '<br>' . EASY_LABEL_PRODUCT_RECORDS . $total3;?><br>
                </td>
              </tr>
            </table>
          </div>
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
              <td align="left"><?php echo tep_image_submit('button_start_file_creation.gif', EASY_LABEL_PRODUCT_START); ?></td>
              <td align="right">
                <?php
                //  End TIMER
                //  ---------
                $etimer = explode( ' ', microtime() );
                $etimer = $etimer[1] + $etimer[0];
                printf( TEXT_INFO_TIMER . " <b>%f</b> "  . TEXT_INFO_SECOND, ($etimer-$stimer) );
                //  ---------
                ?>
              </td>
            </tr>
          </table>
          </form>
        </td>
      </tr>
    </table></td>
  </tr>
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