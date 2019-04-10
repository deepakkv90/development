<?php
/*
  $Id: epconfigure.php,v 1.0 2005/08/30 zip1 $
*/

//
//*******************************
//*******************************
// C O N F I G U R A T I O N
// V A R I A B L E S
//*******************************
//*******************************

// **** Temp directory ****
// if you changed your directory structure from stock and do not have /catalog/temp/, then you'll need to change this accordingly.
//
$tempdir = "temp/";
$tempdir2 = "/temp/";

//**** File Splitting Configuration ****
// we attempt to set the timeout limit longer for this script to avoid having to split the files
// NOTE:  If your server is running in safe mode, this setting cannot override the timeout set in php.ini
// uncomment this if you are not on a safe mode server and you are getting timeouts
// set_time_limit(330);

// if you are splitting files, this will set the maximum number of records to put in each file.
// if you set your php.ini to a long time, you can make this number bigger
global $maxrecs;
$maxrecs = 1500; // default, seems to work for most people.  Reduce if you hit timeouts
//$maxrecs = 4; // for testing

//**** Image Defaulting ****
global $default_images, $default_image_manufacturer, $default_image_product, $default_image_category;

// set them to your own default "We don't have any picture" gif
//$default_image_manufacturer = 'no_image_manufacturer.gif';
//$default_image_product = 'no_image_product.gif';
//$default_image_category = 'no_image_category.gif';

// or let them get set to nothing
$default_image_manufacturer = '';
$default_image_product = '';
$default_image_category = '';

//**** Status Field Setting ****
// Set the v_status field to "Inactive" if you want the status=0 in the system
// Set the v_status field to "Delete" if you want to remove the item from the system <- THIS IS NOT WORKING YET!
// If zero_qty_inactive is true, then items with zero qty will automatically be inactive in the store.
global $active, $inactive, $zero_qty_inactive, $deleteit;
$active = 'Active';
$inactive = 'Inactive';
$deleteit = 'delete'; // functional for EP Advance
$zero_qty_inactive = false;

//**** Size of products_model in products table ****
// set this to the size of your model number field in the db.  We check to make sure all models are no longer than this value.
// this prevents the database from getting fubared.  Just making this number bigger won't help your database!  They must match!
global $modelsize;
$modelsize = 25;

//**** Price includes tax? ****
// Set the v_price_with_tax to
// false if you want the price without the tax included
// true if you want the price to be defined for import & export including tax.
global $price_with_tax;
$price_with_tax = false;

// **** Quote -> Escape character conversion ****
// If you have extensive html in your descriptions and it's getting mangled on upload, turn this off
// set to true = replace quotes with escape characters
// set to false = no quote replacement
global $replace_quotes;
$replace_quotes = true;

// **** Field Separator ****
// change this if you can't use the default of tabs
// Tab is the default, comma and semicolon are commonly supported by various progs
// Remember, if your descriptions contain this character, you will confuse EP!
global $separator;
$separator = "\t"; // tab is default
//$separator = ","; // comma
//$separator = ";"; // semi-colon
//$separator = "~"; // tilde
//$separator = "-"; // dash
//$separator = "*"; // splat

// **** File extension ****
global $file_extension;
$file_extension = "txt"; // .txt is default
//$file_extension = "csv"; // .txt is default

// **** Max Category Levels ****
// change this if you need more or fewer categories
global $max_categories;
$max_categories = 4; // 4 is default

// VJ product attributes begin
// **** Product Attributes ****
// change this to false, if do not want to download product attributes
global $products_with_attributes;
$products_with_attributes = true;

// change this if you want to download selected product options
// this might be handy, if you have a lot of product options, and your output file exceeds 256 columns (which is the max. limit MS Excel is able to handle)
global $attribute_options_select;
//$attribute_options_select = array('Size', 'Model'); // uncomment and fill with product options name you wish to download // comment this line, if you wish to download all product options
// VJ product attributes end

require('includes/application_top.php');
//require('includes/database_tables.php');

global $filelayout, $filelayout_count, $filelayout_sql, $langcode, $fileheaders;

// these are the fields that will be defaulted to the current values in the database if they are not found in the incoming file
global $default_these;
$default_these = array(
  'v_products_image',
  'v_products_image_med',
  'v_products_image_lrg',
  'v_products_image_sm_1',
  'v_products_image_xl_1',
  'v_products_image_sm_2',
  'v_products_image_xl_2',
  'v_products_image_sm_3',
  'v_products_image_xl_3',
  'v_products_image_sm_4',
  'v_products_image_xl_4',
  'v_products_image_sm_5',
  'v_products_image_xl_5',
  'v_products_image_sm_6',
  'v_products_image_xl_6',
  'v_products_model',
  'v_categories_id',
  'v_products_price',
  'v_products_quantity',
  'v_products_weight',
  'v_date_avail',
  'v_date_added',
  'v_instock',
  'v_tax_class_title',
  'v_manufacturers_name',
  'v_manufacturers_id',
  'v_products_dim_type',
  'v_products_length',
  'v_products_width',
  'v_products_height',
  'v_prod_products_group_access'
  );

//*******************************
//*******************************
// E N D
// C O N F I G U R A T I O N
// V A R I A B L E S
//*******************************
//*******************************
?>