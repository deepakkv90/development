<?php


/*
  $Id: easypopulate_basic.php,v 3.01 2005/09/06 $
  
*/

// Current EP Version
$curver = '3.01 Basic';

require('epconfigure.php');
include ('includes/functions/easypopulate_functions.php');
include (DIR_WS_LANGUAGES . $language . '/easypopulate.php');


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

$dltype = isset($_POST['dltype']) ? $_POST['dltype'] : '';
$download = isset($_POST['download']) ? $_POST['download'] : '';


//end intilization
// queries to pull data
if ($dltype != '' ){
  // if dltype is set, then create the filelayout.  Otherwise it gets read from the uploaded file

  global $GLOBALS, $filelayout, $filelayout_count, $filelayout_sql, $langcode, $fileheaders, $max_categories, $rangebegin, $rangeend, $catsort, $catfilter, $BEGIN1, $BEEND1, $limit_man, $limit_cat, $categories_range;
  // depending on the type of the download the user wanted, create a file layout for it.
  $fieldmap = array(); // default to no mapping to change internal field names to external.
  switch( $dltype ){
  case 'full':
    // The file layout is dynamically made depending on the number of languages
    $iii = 0;
    $filelayout = array(
      'v_products_model'    => $iii++,
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
      //echo $langcode['id'] . $langcode['code'];
      $filelayout  = array_merge($filelayout , array(
          'v_products_name_' . $l_id    => $iii++,
          //'v_products_description_' . $l_id => $iii++,
          'v_products_description_' . $l_id => (str_replace('"', '\"', $iii++)),
          'v_products_url_' . $l_id => $iii++,
          'v_products_head_title_tag_'.$l_id  => $iii++,
          'v_products_head_desc_tag_'.$l_id => $iii++,
          'v_products_head_keywords_tag_'.$l_id => $iii++,
          ));
    }


    // uncomment the customer_price and customer_group to support multi-price per product contrib

    // VJ product attribs begin
     $header_array = array(
      'v_products_price'    => $iii++,
      'v_products_weight'   => $iii++,
      'v_date_avail'      => $iii++,
      'v_date_added'      => $iii++,
      'v_products_quantity'   => $iii++,
      );

      $languages = tep_get_languages();

    $header_array['v_manufacturers_name'] = $iii++;

    $filelayout = array_merge($filelayout, $header_array);
    // VJ product attribs end

    // build the categories name section of the array based on the number of categores the user wants to have
    for($i=1;$i<$max_categories+1;$i++){
      $filelayout = array_merge($filelayout, array('v_categories_name_' . $i => $iii++));
    }

    $filelayout = array_merge($filelayout, array(
      'v_tax_class_title'   => $iii++,
      'v_status'      => $iii++,
      ));

    $filelayout_sql = "SELECT
      p.products_id as v_products_id,
      p.products_model as v_products_model,
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
      p.products_status as v_status
      FROM
      ".TABLE_PRODUCTS." as p,
      ".TABLE_CATEGORIES." as subc,
      ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
      WHERE
      p.products_id = ptoc.products_id AND
      ptoc.categories_id = subc.categories_id
      ";

    break;

  case 'priceqty':
    $iii = 0;
    // uncomment the customer_price and customer_group to support multi-price per product contrib
    $filelayout = array(
      'v_products_model'    => $iii++,
      'v_products_price'    => $iii++,
      'v_products_quantity'   => $iii++,
      #'v_customer_price_1'   => $iii++,
      #'v_customer_group_id_1'    => $iii++,
      #'v_customer_price_2'   => $iii++,
      #'v_customer_group_id_2'    => $iii++,
      #'v_customer_price_3'   => $iii++,
      #'v_customer_group_id_3'    => $iii++,
      #'v_customer_price_4'   => $iii++,
      #'v_customer_group_id_4'    => $iii++,
        );
    $filelayout_sql = "SELECT
      p.products_id as v_products_id,
      p.products_model as v_products_model,
      p.products_price as v_products_price,
      p.products_quantity as v_products_quantity
      FROM
      ".TABLE_PRODUCTS." as p
      ";

    break;

  case 'category':
    // The file layout is dynamically made depending on the number of languages
    $iii = 0;
    $filelayout = array(
      'v_products_model'    => $iii++,
    );

    // build the categories name section of the array based on the number of categores the user wants to have
    for($i=1;$i<$max_categories+1;$i++){
      $filelayout = array_merge($filelayout, array('v_categories_name_' . $i => $iii++));
    }


    $filelayout_sql = "SELECT
      p.products_id as v_products_id,
      p.products_model as v_products_model,
      subc.categories_id as v_categories_id
      FROM
      ".TABLE_PRODUCTS." as p,
      ".TABLE_CATEGORIES." as subc,
      ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
      WHERE
      p.products_id = ptoc.products_id AND
      ptoc.categories_id = subc.categories_id
      ";
    break;


// VJ product attributes begin
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


    // If you have other modules that need to be available, put them here

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
    $row['v_products_price']  = $row['v_products_price'] +
              ($price_with_tax * round($row['v_products_price'] * $row_tax_multiplier / 100,2));


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
    $EXPORT_TIME = "EPB" . $EXPORT_TIME;

  // now either stream it to them or put it in the temp directory for all files
  if ($download == 'stream'){
    //*******************************
    // STREAM FILE
    //*******************************
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition: attachment; filename=$EXPORT_TIME.txt");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo $filestring;
    die();

    //tep_redirect(tep_href_link(FILENAME_EASYPOPULATE_BASIC_EXPORT, 'mesID=MSG2&name=' . $EXPORT_TIME));
  } else {
    //*******************************
    // PUT FILE IN TEMP DIR
    //*******************************
    $tmpfname = DIR_FS_DOCUMENT_ROOT . $tempdir . "$EXPORT_TIME.txt";
    //unlink($tmpfname);
    $fp = fopen( $tmpfname, "w+");
    fwrite($fp, $filestring);
    fclose($fp);
    //echo EASY_FILE_LOCATE . $tempdir .  $EXPORT_TIME . ".txt" ;
          tep_redirect(tep_href_link(FILENAME_EASYPOPULATE_BASIC_EXPORT, 'mesID=MSG1&name=' . $EXPORT_TIME));
    //echo  '<a href="easypopulate_export.php">' . EASY_FILE_RETURN . '</a><br>';


    //die();
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
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
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
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo EASY_VERSION_B . EASY_VER_B . EASY_EXPORT; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>

<?php
if (isset($_GET['mesID']) && $_GET['mesID'] == 'MSG1'){
       echo '<tr class="epa_msg"><td>' . EASY_FILE_LOCATE . $tempdir .  $name . ".txt" . '</td></tr>';
       
}

if (isset($_GET['mesID']) && $_GET['mesID'] == 'MSG2'){
       echo '<tr><td>' . EASY_FILE_LOCATE2 .  $name . ".txt" . '</td></tr>';
}
?>
               </tr>
        <tr class="dataTableHeadingRow">
<td class="dataTableHeadingContent">
<b><?php echo EASY_LABEL_CREATE . '</b>' ;
         echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_file_export') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
?>                 </td>
               </tr>
               <tr>
               <td>
 <?php echo tep_draw_form('localfile_export', 'easypopulate_basic_export.php', 'action=export', 'post', 'enctype="multipart/form-data"'); ?>
                 </td>
               </tr>
               <tr>
                 <td>
                 <b><?php echo EASY_LABEL_CREATE_SELECT. '</b>' ;
         echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_select_method') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
         echo '&nbsp;';?>
      <select name="download">
      <option selected value ="stream" size="10"><?php echo EASY_LABEL_DOWNLOAD . '<b> ';?>
      <option value="tempfile" size="10"><?php echo EASY_LABEL_CREATE_SAVE;?>
      </select>
                   </td>
      </tr>
      <tr>
       <td>

      
 <b><?php echo EASY_LABEL_SELECT_DOWN . '</b>';
  echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_select_down') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'?>
      <select name="dltype">
      <option selected value ="full" size="10"><?php echo EASY_LABEL_COMPLETE //full;?>
      <option value="priceqty" size="10"><?php echo EASY_LABEL_MPQ //model price qty;?>
      <option value="category" size="10"><?php echo EASY_LABEL_EP_MC //model category;?>
<?php // <option value="attrib" size="10"><?php echo EASY_LABEL_EP_ATTRIB //attibutes
 ;?>
      </select>
       </td>
      </tr>
      <tr>
       <td>
                <?php echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . tep_image_submit('button_start_file_creation.gif', EASY_LABEL_PRODUCT_START); ?>
        </form>
        </td>
                 </tr>
                 <tr>
                 <td>
<?php
//  End TIMER
//  ---------
$etimer = explode( ' ', microtime() );
$etimer = $etimer[1] + $etimer[0];
echo '<p style="margin:auto; text-align:center">';
printf( TEXT_INFO_TIMER . " <b>%f</b> "  . TEXT_INFO_SECOND, ($etimer-$stimer) );
echo '</p>';
//  ---------
 ?>                
               
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