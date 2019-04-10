<?php
/*
  $Id: whos_online.php,v 1.32 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();


/*
  Configuration Values
    Set these to easily personalize your Whos Online
*/

// Seconds that a visitor is considered "active"
  $active_time = 300;
// Seconds before visitor is removed from display
  $track_time = 900;

// Automatic refresh times in seconds and display names
//   Time and Display Text order must match between the arrays
//   "None" is handled separately in the code
  $refresh_time = array(     30,    60,     120,    180 );
  $refresh_display = array( ':30', '1:00', '2:00', '3:00' );

// Images used for status lights
  $status_active_cart = 'accept.png';
  $status_inactive_cart = 'cancel.png';
  $status_active_nocart = 'accept-off.png';
  $status_inactive_nocart = 'cancel-off.png';
  $status_active_bot = 'icon_status_green_border_light.gif';
  $status_inactive_bot = 'icon_status_red_border_light.gif';

// Text color used for table entries
//   Different colored text for different users
//   Named colors and Hex values should work fine here
  $fg_color_bot = 'maroon';
  $fg_color_admin = 'darkblue';
  $fg_color_guest = 'green';
  $fg_color_account = '#000000'; // Black

/*
  Determines status and cart of visitor and displays appropriate icon.
*/
function tep_check_cart($which, $customer_id, $session_id) {
  global $cart, $status_active_cart, $status_inactive_cart, $status_active_nocart, $status_inactive_nocart, $status_inactive_bot, $status_active_bot, $active_time;

    if (STORE_SESSIONS == 'mysql') {
      $session_data = tep_db_query("select value from " . TABLE_SESSIONS . " WHERE sesskey = '" . $session_id . "'");
      $session_data = tep_db_fetch_array($session_data);
      $session_data = trim($session_data['value']);
    } else {
      if ((file_exists(tep_session_save_path() . '/sess_' . $session_id)) && (filesize(tep_session_save_path() . '/sess_' . $session_id) > 0)) {
        $session_data = file(tep_session_save_path() . '/sess_' . $session_id);
        $session_data = trim(implode('', $session_data));
      }
    }


    $products =0;
    if ($length = strlen($session_data)) {
      #contents";a:0: <= no products in cart
      #contents";a:5: <= 5 products in cart
      preg_match('|contents";a:(\d+):|i',$session_data, $find);
      $products = $find[1];
    }
    
  $which_query = $session_data;                               
  $who_data =   tep_db_query("select time_entry, time_last_click from " . TABLE_WHOS_ONLINE . " where session_id='" . $session_id . "'");
  $who_query = tep_db_fetch_array($who_data);                           
  
  // Determine if visitor active/inactive
  $xx_mins_ago_long = (time() - $active_time);

  // Determine Bot active/inactive
  if( $customer_id < 0 ) {
    // inactive 
    if ($who_query['time_last_click'] < $xx_mins_ago_long) {
      return tep_image(DIR_WS_IMAGES . $status_inactive_bot, TEXT_STATUS_INACTIVE_BOT);
    // active 
    } else {
      return tep_image(DIR_WS_IMAGES . $status_active_bot, TEXT_STATUS_ACTIVE_BOT);
    }
  } 

  // Determine active/inactive and cart/no cart status
    if ($products == 0 ) {
          // inactive
          if ($who_query['time_last_click'] < $xx_mins_ago_long) {
            return tep_image(DIR_WS_IMAGES . $status_inactive_nocart, TEXT_STATUS_INACTIVE_NOCART);
          // active
          } else {
            return tep_image(DIR_WS_IMAGES . $status_active_nocart, TEXT_STATUS_ACTIVE_NOCART);
          }
    // cart
    } else {
          // inactive
          if ($who_query['time_last_click'] < $xx_mins_ago_long) {
            return tep_image(DIR_WS_IMAGES . $status_inactive_cart, TEXT_STATUS_INACTIVE_CART);
          // active
          } else {
            return tep_image(DIR_WS_IMAGES . $status_active_cart, TEXT_STATUS_ACTIVE_CART);
          }
    } //$products == 0
}// eof tep_check_cart


/* Display the details about a visitor */
function display_details() {
   global $whos_online, $is_bot, $is_admin, $is_guest, $is_account;
   
  // Display Name
   echo '<b>'.TEXT_NAME.'</b> ' . $whos_online['full_name'];
   echo '<br clear="all">' . tep_draw_separator('pixel_trans.gif', '10', '4') . '<br clear="all">';
   // Display Customer ID for non-bots
   if ( !$is_bot ){
      echo '<b>'.TEXT_CUSTOMER_ID.'</b> ' . $whos_online['customer_id'];
      echo '<br clear="all">' . tep_draw_separator('pixel_trans.gif', '10', '4') . '<br clear="all">';
   } 
  // Display IP Address
   echo '<b>'.TEXT_IP_ADDRESS.'</b> ' . $whos_online['ip_address'];
   echo '<br clear="all">' . tep_draw_separator('pixel_trans.gif', '10', '4') . '<br clear="all">';
  // Display User Agent
   echo '<b>' . TEXT_USER_AGENT . ':</b> ' . $whos_online['user_agent'];
   echo '<br clear="all">' . tep_draw_separator('pixel_trans.gif', '10', '4') . '<br clear="all">';
  // Display Session ID.  Bots with no Session ID, have it set to their IP address.  Don't display these.
   if ( $whos_online['session_id'] != $whos_online['ip_address'] ) {
      echo '<b>' . TEXT_OSCID . ':</b> ' . $whos_online['session_id'];
      echo '<br clear="all">' . tep_draw_separator('pixel_trans.gif', '10', '4') . '<br clear="all">';
   }
  // Display Referer if available
   if($whos_online['http_referer'] != "" ) {
      echo '<b>'.TEXT_REFERER.'</b> ' . $whos_online['http_referer']; 
      echo '<br clear="all">' . tep_draw_separator('pixel_trans.gif', '10', '4') . '<br clear="all">';
   }
}


  // Time to remove old entries
  $xx_mins_ago = (time() - $track_time);

// remove entries that have expired
  tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where time_last_click < '" . $xx_mins_ago . "'");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<!-- WOL 1.6 - Cleaned up refresh -->
<?php if( $_SERVER["QUERY_STRING"] > 0 ){  ?>
  <meta http-equiv="refresh" content="<?php echo $_SERVER["QUERY_STRING"];?>;URL=whos_online.php?<?php echo $_SERVER["QUERY_STRING"];?>">
<?php } ?>
<!-- WOL 1.6 EOF -->

<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
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
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="bottom" class="pageHeading">
            <?php echo HEADING_TITLE; ?>
            <br clear="all"><span class="smallText" style="color:#909090"><?php echo TEXT_SET_REFRESH_RATE; ?>:</span>
            <span class="dataTableContent" style="font-size: 10px; color:#000000">
            <!-- For loop displays refresh time links -->
            <?php
              echo '<a href="whos_online.php"><b>None</b></a>';
              foreach ($refresh_time as $key => $value) {
                echo ' &#183; <a href="whos_online.php?' . $value . '"><b>' . $refresh_display[$key] . '</b></a>';
              }
            ?>
<script language="JavaScript">
<!-- Begin
Stamp = new Date();
document.write('&nbsp;&nbsp;-&nbsp;&nbsp;Last Refresh: ' + (Stamp.getMonth() + 1) +"/"+Stamp.getDate()+ "/"+Stamp.getFullYear() + '&nbsp;&nbsp;');
var Hours;
var Mins;
var Time;
Hours = Stamp.getHours();
if (Hours >= 12) {
Time = " p.m.";
}
else {
Time = " a.m.";
}
if (Hours > 12) {
Hours -= 12;
}
if (Hours == 0) {
Hours = 12;
}
Mins = Stamp.getMinutes();
if (Mins < 10) {
Mins = "0" + Mins;
} 
document.write('<strong>' + Hours + ":" + Mins + Time + '</strong>');
// End -->
</script>           
            
            <!-- Display Profile links -->
            <br clear="all">
            <span class="smallText" style="color:#909090"><?php echo TEXT_PROFILE_DISPLAY; ?>:</span>
            <a href="whos_online.php"><b><?php echo TEXT_NONE_; ?></b></a> &#183; 
            <a href="whos_online.php?showAll"><b><?php echo TEXT_ALL; ?></b></a> &#183; 
            <a href="whos_online.php?showBots"><b><!-- Bots --><?php echo TEXT_BOTS;?></b></a> &#183; 
            <a href="whos_online.php?showCust"><b><!-- Customers --><?php echo TEXT_CUSTOMERS;?></b></a>
            </span>
            </td>
            <!-- Status Legend - Uses variables for image names -->
            <td rowspan="2" align="right" class="smallText">
              <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="smallText"><?php echo
                  tep_image(DIR_WS_IMAGES . $status_active_cart, TEXT_STATUS_ACTIVE_CART) . '&nbsp;' . TEXT_STATUS_ACTIVE_CART . '&nbsp;&nbsp;';
              ?></td>
                <td class="smallText"><?php echo
                  tep_image(DIR_WS_IMAGES . $status_inactive_cart, TEXT_STATUS_INACTIVE_CART) . '&nbsp;' . TEXT_STATUS_INACTIVE_CART . '&nbsp;&nbsp;';
              ?></td>
              </tr>
              <tr>
                <td class="smallText"><?php echo
                  tep_image(DIR_WS_IMAGES . $status_active_nocart, TEXT_STATUS_ACTIVE_NOCART) . '&nbsp;' . TEXT_STATUS_ACTIVE_NOCART   .'&nbsp;&nbsp;';
              ?></td>
                <td class="smallText"><?php echo
                  tep_image(DIR_WS_IMAGES . $status_inactive_nocart, TEXT_STATUS_INACTIVE_NOCART) . '&nbsp;' . TEXT_STATUS_INACTIVE_NOCART   . '&nbsp;&nbsp;';
              ?></td>
              </tr>
              <tr>
                <td class="smallText"><?php echo
                  tep_image(DIR_WS_IMAGES . $status_active_bot, TEXT_STATUS_ACTIVE_BOT) . '&nbsp;' . TEXT_STATUS_ACTIVE_BOT . '&nbsp;&nbsp;';
              ?></td>
                <td class="smallText"><?php echo
                  tep_image(DIR_WS_IMAGES . $status_inactive_bot, TEXT_STATUS_INACTIVE_BOT) . '&nbsp;' . TEXT_STATUS_INACTIVE_BOT . '&nbsp;&nbsp;';
             ?></td>
             </tr>
             </table>
           </td>
         </tr>
         <tr>
           <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>
           </td>
         </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td>&nbsp;</td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ONLINE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FULL_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_IP_ADDRESS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ENTRY_TIME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LAST_CLICK; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LAST_PAGE_URL; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_USER_SESSION; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_HTTP_REFERER; ?>&nbsp;</td>
              </tr>

<?php
  // Order by is on Last Click. Also initialize total_bots and total_admin counts 
  $whos_online_query = tep_db_query("select customer_id, full_name, ip_address, time_entry, time_last_click, last_page_url, http_referer, user_agent, session_id from " . TABLE_WHOS_ONLINE . ' order by time_last_click DESC');
  $total_bots=0;
  $total_admin=0;
  $total_guests=0;
  $total_loggedon=0;
  $i=0;
  
  while ($whos_online = tep_db_fetch_array($whos_online_query)) {
  
    $time_online = ($whos_online['time_last_click'] - $whos_online['time_entry']);
    if ((!isset($_GET['info']) || (isset($_GET['info']) && ($_GET['info'] == $whos_online['session_id']))) && !isset($info)) {
      $info = $whos_online['session_id'];
    }

/* BEGIN COUNT MOD */
    if (isset($old_array['ip_address']) && $old_array['ip_address'] == $whos_online['ip_address']) {
      $i++;
    }
/* END COUNT MOD */

    if ($whos_online['session_id'] == $info) {
       if($whos_online['http_referer'] != "")
       {
        $http_referer_url = $whos_online['http_referer'];
       }
      echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
    } else {
      echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_WHOS_ONLINE, tep_get_all_get_params(array('info', 'action')) . 'info=' . $whos_online['session_id'], 'NONSSL') . '\'">' . "\n";
    }

   // Display Status
   //   Check who it is and set values
    $is_bot = $is_admin = $is_guest = $is_account = false;
    // Bot detection
    if ($whos_online['customer_id'] < 0) {
      $total_bots++;
      $fg_color = $fg_color_bot;
      $is_bot = true;
      // Admin detection
    } elseif ($whos_online['ip_address'] == tep_get_ip_address() ) { //$_SERVER["REMOTE_ADDR"]) {
      $total_admin++;
      $fg_color = $fg_color_admin;
      $is_admin = true;
    // Guest detection (may include Bots not detected by Prevent Spider Sessions/spiders.txt)
    } elseif ($whos_online['customer_id'] == 0) {
      $fg_color = $fg_color_guest;
      $is_guest = true;
      $total_guests++;
    // Everyone else (should only be account holders)
    } else {
      $fg_color = $fg_color_account;
      $is_account = true;
      $total_loggedon++;
    }
?>
                <!-- Status Light Column -->
                <td class="dataTableContent" align="left" valign="top">
                  <?php echo '&nbsp;' . tep_check_cart($whos_online['session_id'], $whos_online['customer_id'], $whos_online['session_id']); ?>
                </td>

                <!-- Time Online Column -->
                <td class="dataTableContent" valign="top"><font color="<?php echo $fg_color; ?>">
                  <?php echo gmdate('H:i:s', $time_online); ?>
                </font>&nbsp;</td>

                <!-- Name Column -->
                <td class="dataTableContent" valign="top"><font color="<?php echo $fg_color; ?>">
                  <?php
                  // WOL 1.6 Restructured to Check for Guest or Admin
                  if ( $is_guest || $is_admin ) 
                  { 
                    echo $whos_online['full_name'] . '&nbsp;';
                  // Check for Bot
                  } elseif ( $is_bot ) { 
                    // Tokenize UserAgent and try to find Bots name
                    $tok = strtok($whos_online['full_name']," ();/");
                    while ($tok) {
                      if ( strlen($tok) > 3 )
                        if ( !strstr($tok, "mozilla") && 
                             !strstr($tok, "compatible") &&
                             !strstr($tok, "msie") &&
                             !strstr($tok, "windows") 
                           ) {
                          echo "$tok";
                          break;
                        }
                      $tok = strtok(" ();/");
                    }
                  // Check for Account
                  } elseif ( $is_account ) {
                    echo '<a HREF="customers.php?selected_box=customers&cID=' . $whos_online['customer_id'] . '&action=edit">' . $whos_online['full_name'] . '</a>';
                  } else {
                    echo TEXT_ERROR;
                  }
                  ?>
                </font>&nbsp;</td>   

                <!-- IP Address Column -->
                <td class="dataTableContent" valign="top"><font color="<?php echo $fg_color; ?>">
                  <?php
                  // Show 'Admin' instead of IP for Admin
                  if ( $is_admin ) 
                    echo TEXT_ADMIN;
                  else
                    // Show IP with link to IP checker
                    echo '<a HREF="http://private.dnsstuff.com/tools/whois.ch?ip=' . $whos_online['ip_address'] . '" target="_blank">' . $whos_online['ip_address'] . '</a>';
                  ?>
                </font>&nbsp;</td>

                <!-- Time Entry Column -->
                <td class="dataTableContent" valign="top"><font color="<?php echo $fg_color; ?>">
                  <?php echo date('H:i:s', $whos_online['time_entry']); ?>
                </font></td>

                <!-- Last Click Column -->
                <td class="dataTableContent" align="center" valign="top"><font color="<?php echo $fg_color; ?>">
                  <?php echo date('H:i:s', $whos_online['time_last_click']); ?>
                </font>&nbsp;</td>

                <!-- Last URL Column -->
                <td class="dataTableContent" valign="top">
                <?php 
                $temp_url_link = $whos_online['last_page_url'];
                if (eregi('^(.*)' . tep_session_name() . '=[a-f,0-9]+[&]*(.*)', $whos_online['last_page_url'], $array)) {
                  $temp_url_display =  $array[1] . $array[2];
                } else {
                  $temp_url_display = $whos_online['last_page_url'];
                }
                // WOL 1.6 - Removes osCid from the Last Click URL and the link
                if ( $osCsid_position = strpos($temp_url_display, "osCsid") )
                  $temp_url_display = substr_replace($temp_url_display, "", $osCsid_position - 1 );
                if ( $osCsid_position = strpos($temp_url_link, "osCsid") )
                  $temp_url_link = substr_replace($temp_url_link, "", $osCsid_position - 1 );
                ?>
                  <a HREF="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . $temp_url_link; ?>" target=\"_blank\">
                    <font color="<?php echo $fg_color; ?>">
                      <?php 
                        echo $temp_url_display;
                      ?>
                    </font>
                  </a>
                </td>

                <!-- osCsid? Column -->
                <td class="dataTableContent" align="center" valign="top"><font color="<?php echo $fg_color; ?>">
                  <?php
                  if($whos_online['session_id'] != $whos_online['ip_address']) {
                      echo 'Y';
                  } else {
                      echo "&nbsp;";
                  }
                  ?>
                </font></td>

                <!-- Referer? Column -->
                <td class="dataTableContent" align="center" valign="top"><font color="<?php echo $fg_color; ?>">
                  <?php
                  if($whos_online['http_referer'] == "") {
                      echo '&nbsp;';
                  } else {
                      echo TEXT_HTTP_REFERER_FOUND;
                  }
                  ?>
                </font></td>
              </tr>

              <tr class="dataTableRow">
                <td class="dataTableContent" colspan="3"></td>
                <td class="dataTableContent" colspan="6"><font color="<?php echo $fg_color; ?>">
                <?php 
                // Display Details for All
                if ( $_SERVER["QUERY_STRING"] == 'showAll' ) {
                  display_details();
                }
                // Display Details for Bots
                else if( $_SERVER["QUERY_STRING"] == 'showBots' ){
                  if ( $is_bot ) {
                    display_details();
                  }
                } 
                // Display Details for Customers
                else if( $_SERVER["QUERY_STRING"] == 'showCust' ){
                  if ( $is_guest || $is_account || $is_admin ) {
                    display_details();
                  }
                } 
            ?>
            </font></td>
            </tr>

<?php
 $old_array = $whos_online;
  }

  if (!$i) {
    $i=0;
  }
  $total_dupes = $i;
  $total_sess = tep_db_num_rows($whos_online_query);
  // WOL 1.4 - Subtract Bots and Me from Real Customers.  Only subtract me once as Dupes will remove others
  $total_cust = $total_sess - $total_dupes - $total_bots - ($total_admin > 1? 1 : $total_admin);
  // WOL 1.4 eof
?>
<?php
  if(isset($http_referer_url))
  {
?>
  <tr>
  <td class="smallText" colspan="9"><?php echo '<strong>' . TEXT_HTTP_REFERER_URL . ':</strong> ' . $http_referer_url; ?></td>
  </tr>
  <?php
  }
  if ($total_cust < 0){
  $total_cust = 0;
  }

?>
              <tr>
<!-- WOL 1.4 - Added Bot and Me counts -->
                <td class="smallText" colspan="9"><br><?php echo sprintf(TEXT_NUMBER_OF_CUSTOMERS, $total_sess); ?>
                  <?php echo "<br>" . TEXT_DUPLICATE_IP . ":" . $total_dupes . "<br>" . TEXT_BOTS . ": " . $total_bots . "<br>" . TEXT_ME . ": " . $total_admin . "<br>" . TEXT_REAL_CUSTOMERS . ": " . $total_cust . "<br><br>" . TEXT_YOUR_IP_ADDRESS . ": " .tep_get_ip_address();?></td><!--  //$_SERVER["REMOTE_ADDR"];?></td> -->
<!-- WOL 1.4 eof -->                
              </tr>
            </table></td>

<?php
  $heading = array();
  $contents = array();
  $heading[] = array('text' => '<b>' . TABLE_HEADING_SHOPPING_CART . '</b>');
  if (isset($info)) {
    if (STORE_SESSIONS == 'mysql') {
      $session_data = tep_db_query("select value from " . TABLE_SESSIONS . " WHERE sesskey = '" . $info . "'");
      $session_data = tep_db_fetch_array($session_data);
      $session_data = trim($session_data['value']);
    } else {
      if ( (file_exists(tep_session_save_path() . '/sess_' . $info)) && (filesize(tep_session_save_path() . '/sess_' . $info) > 0) ) {
        $session_data = file(tep_session_save_path() . '/sess_' . $info);
        $session_data = trim(implode('', $session_data));
      }
    }
    $length = strlen($session_data);
    if ($length > 0) {
      $start_id = strpos($session_data, 'customer_id|s');
      $start_cart = strpos($session_data, 'shoppingCart_data|a');
      $start_currency = strpos($session_data, 'currency|s');
      $start_country = strpos($session_data, 'customer_country_id|s');
      $start_zone = strpos($session_data, 'customer_zone_id|s');
      
      if ($start_cart == '') $start_cart = 0;

      for ($i=$start_cart; $i<$length; $i++) {
        if ($session_data[$i] == '{') {
          if (isset($tag)) {
            $tag++;
          } else {
            $tag = 1;
          }
        } elseif ($session_data[$i] == '}') {
          $tag--;
        } elseif ( (isset($tag)) && ($tag < 1) ) {
          break;
        }
      }

      $session_data_id = substr($session_data, $start_id, (strpos($session_data, ';', $start_id) - $start_id + 1));
      $session_data_cart = substr($session_data, $start_cart, $i);
      $session_data_currency = substr($session_data, $start_currency, (strpos($session_data, ';', $start_currency) - $start_currency + 1));
      $session_data_country = substr($session_data, $start_country, (strpos($session_data, ';', $start_country) - $start_country + 1));
      $session_data_zone = substr($session_data, $start_zone, (strpos($session_data, ';', $start_zone) - $start_zone + 1));

      session_decode($session_data_id);
      session_decode($session_data_currency);
      session_decode($session_data_country);
      session_decode($session_data_zone);
      session_decode($session_data_cart);

      if (is_array($_SESSION['shoppingCart_data'])) {
        $products = $_SESSION['shoppingCart_data']['contents'];
        foreach ($products as $key => $value) {
          $contents[] = array('text' => $value['qty'] . ' x ' . tep_get_products_name((int)$key));
        }

        if (sizeof($products) > 0) {
         $contents[] = array('text' => tep_draw_separator('pixel_black.gif', '100%', '1'));
         $contents[] = array('align' => 'right', 'text'  => TEXT_SHOPPING_CART_SUBTOTAL . ' ' . $currencies->format($_SESSION['shoppingCart_data']['total'], true, $currency));
        } else {
         $contents[] = array('text' => 'Empty');
      }
    }
  }
 }
   // Show shopping cart contents for selected entry
   echo '            <td valign="top">' . "\n";

   $box = new box;
   echo $box->infoBox($heading, $contents);

   echo '</td>' . "\n";
?>
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