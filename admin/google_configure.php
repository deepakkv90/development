<?php
/*
  $Id: google_pre.php,v 1.1.1.1  zip1 Exp $
  http://www.oscommerce.com
   Google Base Feeder!

  Copyright (c) 2002 - 2005 Calvin K

  Released under the GNU General Public License

Notes:
1. all country code must be  in ISO 3166 country code format.
2. none of these setting are used for basic feed types
3. Not all of these setting have been emplimented in the feed.  ($default_ship_from, $shipping, )
*/


//(0=False 1=True) (optional_sec must be enabled to use any options This is set in the admin when configureing a feed
// in  Configure: Build or edit unique configuration set Product Feed Type  to advance to use these options

//please do not change this
if ($data_files_type == 'basic'){
   $optional_sec = 0;
  } elseif ($data_files_type == 'advance'){
   $optional_sec = 1;
  }
//end please do not change this

//START Advance Optional Values

$taxRate = 0; //default = 0 (e.g. for 17.5% tax use "$taxRate = 17.5;")

  // 0 == do not show qty in stock 1 = show  qty in stock
$pquantity = 0;
// 0 == do not show shipping charges 1 = show shipping charges
$shipping = 0;

// what is the lowest shipping for any product
$lowestShipping = "4.95";  //this is not binary.

// 0 = do not use UPC code 1 =  use UPC code you must add the upc feild to the products table to use
$upc = 0;   //Not supported by default osC

// 0 == do not show store currency use default only 1 = use store currency
$currency = 0;
  $default_currency = "USD";  //this is not binary.

// 0 = do not use store language code, use default only, 1= use store language code
// at this time you must use en for google base
$feed_language = 0;
  $default_feed_language = "en";  //this is not binary.

$ship_to = 0;
  $default_ship_to = "ALL"; //this is not binary, not supported by default osC for individual products.
$ship_from = 0;
  $default_ship_from = "US"; //this is not binary, not supported by default osC for individual products.

//additional required feilds
// if no manfuacture is listed use this as a default replacement
$default_brand = "My Brand";

// use this a default condition new, used, reconditioned
$default_condition = "new";

//How long till my next update, should be no more then 30 days
$feed_exp_date = date('Y-m-d', time() + 2592000 );
//above is 30 days 60 sec (time) 60 min (time) 24 hour (time) 30 days

//Use age range  0 = do not use age_range, 1 = use age_range
//for adult content you must use this, for toys its advixable
$age_range = 0;
// default if age range has not been added to products database
$default_age_range = "0-9";

//Use age range  0 = do not use made_in feild, 1 = use made_in feild
$made_in = 0;
//default Made in ISO 3166 country code.
$default_made_in = 'US';


//END of Advance Optional Values
?>
