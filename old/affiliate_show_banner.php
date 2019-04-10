<?php
/*
  $Id: affiliate_show_banner.php,v 1.1.1.1 2004/03/04 23:37:55 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

// CHECKIT
// -> optimize code -> double parts

// require of application_top not possible 
// cause then whois online registers it also as visitor
//

  define('TABLE_AFFILIATE_BANNERS_HISTORY', 'affiliate_banners_history');
  define('TABLE_AFFILIATE_BANNERS', 'affiliate_banners');
  define('TABLE_PRODUCTS', 'products');

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');
  require('includes/configure.php');
  if (file_exists('includes/local/affiliate_configure.php')) include('includes/local/affiliate_configure.php');
  require('includes/affiliate_configure.php');

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');
// make a connection to the database... now
 // tep_db_connect() or die('Unable to connect to database server!');
  tep_db_connect() or die(UNABLE_TO_CONNECT_TO_DATABASE_SERVER);

  function affiliate_show_banner($pic) {
//Read Pic and send it to browser
    $fp = fopen($pic, "rb");
    if (!$fp) exit();
// Get Image type
    $img_type = substr($pic, strrpos($pic, ".") + 1);
// Get Imagename
    $pos = strrpos($pic, "/");
    if ($pos) {
      $img_name = substr($pic, strrpos($pic, "/" ) + 1);
    } else {
      $img_name=$pic;
    }
    header ("Content-type: image/$img_type");
    header ("Content-Disposition: inline; filename=$img_name");
    fpassthru($fp);
    // The file is closed when fpassthru() is done reading it (leaving handle useless).  
    // fclose ($fp);
    exit();
  }

  function affiliate_debug($banner,$sql) {
?>
   <!-- <table border=1 cellpadding=2 cellspacing=2>
      <tr><td colspan=2>Check the pathes! (catalog/includes/configure.php)</td></tr>
      <tr><td>absolute path to picture:</td><td><?php echo DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG. DIR_WS_IMAGES . $banner; ?></td></tr>
      <tr><td>build with:</td><td>DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG . DIR_WS_IMAGES . $banner</td></tr>
      <tr><td>DIR_FS_DOCUMENT_ROOT</td><td><?php echo DIR_FS_DOCUMENT_ROOT; ?></td></tr>
      <tr><td>DIR_WS_CATALOG</td><td><?php echo DIR_WS_CATALOG ; ?></td></tr>
      <tr><td>DIR_WS_IMAGES</td><td><?php echo DIR_WS_IMAGES; ?></td></tr>
      <tr><td>$banner</td><td><?php echo $banner; ?></td></tr>
      <tr><td>SQL-Query used:</td><td><?php echo $sql; ?></td></tr>
      <tr><th>Try to find error:</td><td>&nbsp;</th></tr>
      <tr><td>SQL-Query:</td><td><?php if ($banner) echo "Got Result"; else echo "No result"; ?></td></tr>
      <tr><td>Locating Pic</td><td> -->

    <table border=1 cellpadding=2 cellspacing=2>
      <tr><td colspan=2><?php echo AFFILIATE_SHOW_BANNER_CHECK_PATHES;?></td></tr>
      <tr><td><?php echo AFFILIATE_SHOW_BANNER_ABSOLUTE_PATH;?></td><td><?php echo DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG. DIR_WS_IMAGES . $banner; ?></td></tr>
      <tr><td><?php echo AFFILIATE_SHOW_BANNER_BUILD_WITH_1;?></td><td><?php echo AFFILIATE_SHOW_BANNER_BUILD_WITH_2;?></td></tr>
      <tr><td><?php echo AFFILIATE_SHOW_BANNER_DIR_FS_DOCUMENT_ROOT;?></td><td><?php echo DIR_FS_DOCUMENT_ROOT; ?></td></tr>
      <tr><td><?php echo AFFILIATE_SHOW_BANNER_DIR_WS_CATALOG;?></td><td><?php echo DIR_WS_CATALOG ; ?></td></tr>
      <tr><td><?php echo AFFILIATE_SHOW_BANNER_DIR_WS_IMAGES;?></td><td><?php echo DIR_WS_IMAGES; ?></td></tr>
      <tr><td><?php echo AFFILIATE_SHOW_BANNER_BANNER;?></td><td><?php echo $banner; ?></td></tr>
      <tr><td><?php echo AFFILIATE_SHOW_BANNER_SQL_QUERY_USED;?></td><td><?php echo $sql; ?></td></tr>
      <tr><th><?php echo AFFILIATE_SHOW_BANNER_TRY_TO_FIND_ERROR;?></td><td>&nbsp;</th></tr>
      <tr><td><?php echo AFFILIATE_SHOW_BANNER_SQL_QUERY;?></td><td><?php if ($banner) echo "Got Result"; else echo "No result"; ?></td></tr>
      <tr><td><?php echo AFFILIATE_SHOW_BANNER_LOCATING_PIC;?></td><td>
<?php 
    $pic = DIR_FS_CATALOG . DIR_WS_IMAGES . $banner;
    echo $pic . "<br>";
    if (!is_file($pic)) {
      echo "failed<br>";
    } else {
      echo "success<br>";
    }
?>
      </td></tr>
    </table>
<?php
    exit();
  }

// Register needed Post / Get Variables
  if ($_GET['ref']) $affiliate_id=$_GET['ref'];
  if ($_POST['ref']) $affiliate_id=$_POST['ref'];

  if ($_GET['affiliate_banner_id']) $banner_id = $_GET['affiliate_banner_id'];
  if ($_POST['affiliate_banner_id']) $banner_id = $_POST['affiliate_banner_id'];
  if ($_GET['affiliate_pbanner_id']) $prod_banner_id = $_GET['affiliate_pbanner_id'];
  if ($_POST['affiliate_pbanner_id']) $prod_banner_id = $_POST['affiliate_pbanner_id'];

  $banner = '';
  $products_id = '';

  if ($banner_id) {
    $sql = "select affiliate_banners_image, affiliate_products_id from " . TABLE_AFFILIATE_BANNERS . " where affiliate_banners_id = '" . (int)$banner_id  . "' and affiliate_status = 1";
    $banner_values = tep_db_query($sql);
    if ($banner_array = tep_db_fetch_array($banner_values)) {
      $banner = $banner_array['affiliate_banners_image'];
      $products_id = $banner_array['affiliate_products_id']; 
    }
  }

  if ($prod_banner_id) {
    $banner_id = 1; // Banner ID for these Banners is one
    $sql = "select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$prod_banner_id  . "' and products_status = 1";
    $banner_values = tep_db_query($sql);
    if ($banner_array = tep_db_fetch_array($banner_values)) {
      $banner = $banner_array['products_image'];
      $products_id = (int)$prod_banner_id;
    }
  }

// DebugModus
  if (AFFILIATE_SHOW_BANNERS_DEBUG == 'true') affiliate_debug($banner,$sql);

  if ($banner) {
    $pic = DIR_FS_CATALOG . DIR_WS_IMAGES . $banner;

    // Show Banner only if it exists:
    if (is_file($pic)) {
      $today = date('Y-m-d');
    // Update stats:
      if ($affiliate_id) {
        $banner_stats_query = tep_db_query("select * from " . TABLE_AFFILIATE_BANNERS_HISTORY . " where affiliate_banners_id = '" . (int)$banner_id  . "' and affiliate_banners_products_id = '" . $products_id ."' and affiliate_banners_affiliate_id = '" . (int)$affiliate_id. "' and affiliate_banners_history_date = '" . $today . "'");
    // Banner has been shown today 
        if ($banner_stats_array = tep_db_fetch_array($banner_stats_query)) {
          tep_db_query("update " . TABLE_AFFILIATE_BANNERS_HISTORY . " set affiliate_banners_shown = affiliate_banners_shown + 1 where affiliate_banners_id = '" . (int)$banner_id  . "' and affiliate_banners_affiliate_id = '" . (int)$affiliate_id. "' and affiliate_banners_products_id = '" . $products_id ."' and affiliate_banners_history_date = '" . $today . "'");
        } else { // First view of Banner today
          tep_db_query("insert into " . TABLE_AFFILIATE_BANNERS_HISTORY . " (affiliate_banners_id, affiliate_banners_products_id, affiliate_banners_affiliate_id, affiliate_banners_shown, affiliate_banners_history_date) VALUES ('" . (int)$banner_id  . "', '" .  $products_id ."', '" . (int)$affiliate_id. "', '1', '" . $today . "')");
        }
      }
    // Show Banner
      affiliate_show_banner($pic);
    }
  }

// Show default Banner if none is found
  if (is_file(AFFILIATE_SHOW_BANNERS_DEFAULT_PIC)) {
    affiliate_show_banner(AFFILIATE_SHOW_BANNERS_DEFAULT_PIC);
  } else {
    echo "<br>"; // Output something to prevent endless loading
  }
  exit();
?>