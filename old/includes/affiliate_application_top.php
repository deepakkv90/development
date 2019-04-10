<?php
/*
  $Id: affiliate_application_top.php,v 1.1.1.1 2004/03/04 23:40:36 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  // Set the local configuration parameters - mainly for developers
  if (file_exists(DIR_WS_INCLUDES . 'local/affiliate_configure.php')) include(DIR_WS_INCLUDES . 'local/affiliate_configure.php');

  // the information in the affiliate_configure.php has been moved to the application_top_cre_setting.php
  // require(DIR_WS_INCLUDES . 'affiliate_configure.php');
  require(DIR_WS_FUNCTIONS . 'affiliate_functions.php');

  // include the language translations
  require(DIR_WS_LANGUAGES . 'affiliate_' . $language . '.php');

  
  // The logic is adjusted to better handle certian conditions where the ref is invalid
  // The affiliate_ref hold the affiliate_id of the person who refer the current customer
  // There is two ways this is determined:
  // 1 - a cookie has been set from previous vist
  // 2 - the $_GET ref is in the URL
  // A cookie overrides the URL value
  // To improve performance, the first time thru will set the session value
  // once set, the logic does not check every thing every time.
  
  $affiliate_clientdate = (date ("Y-m-d H:i:s"));
  $affiliate_clientbrowser = $_SERVER["HTTP_USER_AGENT"];
  $affiliate_clientip = $_SERVER["REMOTE_ADDR"];
  $affiliate_clientreferer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '';
  
  if (!isset($_SESSION['affiliate_ref'])) {  // if we have a cuurent session, assume all is well
    if (isset($_COOKIE['affiliate_ref']) && affiliate_id_valid((int)$_COOKIE['affiliate_ref'])) {
      // a cookie with a valid affiliate id is found, use it
      $_SESSION['affiliate_ref'] = (int)$_COOKIE['affiliate_ref'];
    
    } elseif (isset($_GET['ref']) || isset($_POST['ref'])) {  // do we have a ref id in the URL
      if ( isset($_GET['ref']) ) $affiliate_ref = (int)$_GET['ref'];
      if ( isset($_POST['ref']) ) $affiliate_ref = (int)$_POST['ref'];
      $affiliate_products_id = 0;
      if ( isset($_GET['products_id']) ) $affiliate_products_id = (int)$_GET['products_id'];
      if ( isset($_POST['products_id']) ) $affiliate_products_id = (int)$_POST['products_id'];
      $affiliate_banner_id = 0;
      if ( isset($_GET['affiliate_banner_id']) ) $affiliate_banner_id = (int)$_GET['affiliate_banner_id'];
      if ( isset($_POST['affiliate_banner_id']) ) $affiliate_banner_id = (int)$_POST['affiliate_banner_id'];
      
      // the logic is change to only record information for valid affiliate ids
      // if the id has been deleted, no information will be recorded
      if (affiliate_id_valid($affiliate_ref)) {
        // since it is valid, it needs to be stored in the session
        $_SESSION['affiliate_ref'] = $affiliate_ref;
        
        $sql_data_array = array('affiliate_id' => $affiliate_ref,
                                'affiliate_clientdate' => $affiliate_clientdate,
                                'affiliate_clientbrowser' => $affiliate_clientbrowser,
                                'affiliate_clientip' => $affiliate_clientip,
                                'affiliate_clientreferer' => $affiliate_clientreferer,
                                'affiliate_products_id' => $affiliate_products_id,
                                'affiliate_banner_id' => $affiliate_banner_id);
        tep_db_perform(TABLE_AFFILIATE_CLICKTHROUGHS, $sql_data_array);
        $affiliate_clickthroughs_id = tep_db_insert_id();

        // Banner has been clicked, update stats:
        if ($affiliate_banner_id && $affiliate_ref) {
          $today = date('Y-m-d');
          $sql = "select * from " . TABLE_AFFILIATE_BANNERS_HISTORY . " where affiliate_banners_id = '" . $affiliate_banner_id  . "' and  affiliate_banners_affiliate_id = '" . $affiliate_ref . "' and affiliate_banners_history_date = '" . $today . "'";
          $banner_stats_query = tep_db_query($sql);

          // Banner has been shown today
          if (tep_db_fetch_array($banner_stats_query)) {
            tep_db_query("update " . TABLE_AFFILIATE_BANNERS_HISTORY . " set affiliate_banners_clicks = affiliate_banners_clicks + 1 where affiliate_banners_id = '" . $affiliate_banner_id . "' and affiliate_banners_affiliate_id = '" . $affiliate_ref. "' and affiliate_banners_history_date = '" . $today . "'");
            // Initial entry if banner has not been shown
          } else {
            $sql_data_array = array('affiliate_banners_id' => $affiliate_banner_id,
                                    'affiliate_banners_products_id' => $affiliate_products_id,
                                    'affiliate_banners_affiliate_id' => $affiliate_ref,
                                    'affiliate_banners_clicks' => '1',
                                    'affiliate_banners_history_date' => $today);
            tep_db_perform(TABLE_AFFILIATE_BANNERS_HISTORY, $sql_data_array);
          }
        }
        
        // Set Cookie if the customer comes back and orders it counts
        // added the site doamin and cookie path to allow for seo being different
        // the cookie is only set if it does not already exist
        setcookie('affiliate_ref', $affiliate_ref, time() + AFFILIATE_COOKIE_LIFETIME, HTTP_COOKIE_PATH, HTTP_COOKIE_DOMAIN);
      }
      // remove the variables no longer needed
      unset($affiliate_ref);
      unset($affiliate_products_id);
      unset($affiliate_banner_id );
      unset($banner_stats_query);
    }
  }

?>
