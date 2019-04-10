<?php
/*
  $Id: gv_redeem.php,v 1.1.1.1 2004/03/04 23:37:59 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Gift Voucher System v1.0
  Copyright (c) 2001, 2002 Ian C Wilson
  http://www.phesis.org

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// if the customer is not logged on, redirect them to the login page
if ( ! isset($_SESSION['customer_id']) ) {
$navigation->set_snapshot();
tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}
// check for a voucher number in the url
  if (isset($_GET['gv_no'])) {
    $error = true;
 $voucher_number=tep_db_prepare_input($_GET['gv_no']);
    $gv_query = tep_db_query("select c.coupon_id, c.coupon_amount from " . TABLE_COUPONS . " c, " . TABLE_COUPON_EMAIL_TRACK . " et where coupon_code = '" . addslashes($voucher_number) . "' and c.coupon_id = et.coupon_id");
    if (tep_db_num_rows($gv_query) >0) {
      $coupon = tep_db_fetch_array($gv_query);
      $redeem_query = tep_db_query("select coupon_id from ". TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $coupon['coupon_id'] . "'");
      if (tep_db_num_rows($redeem_query) == 0 ) {
// check for required session variables
        $_SESSION['gv_id'] = $coupon['coupon_id'];
        $error = false;
      } else {
        $error = true;
      }
    }
  } else {
    tep_redirect(FILENAME_DEFAULT);
  }
  if ((!$error) && ( isset($_SESSION['customer_id']) )) {
// Update redeem status
    $gv_query = tep_db_query("insert into  " . TABLE_COUPON_REDEEM_TRACK . " (coupon_id, customer_id, redeem_date, redeem_ip) values ('" . $coupon['coupon_id'] . "', '" . $_SESSION['customer_id'] . "', now(),'" . $REMOTE_ADDR . "')");
    $gv_update = tep_db_query("update " . TABLE_COUPONS . " set coupon_active = 'N' where coupon_id = '" . $coupon['coupon_id'] . "'");
    tep_gv_account_update($_SESSION['customer_id'], $_SESSION['gv_id']);
    unset($_SESSION['gv_id']);   
  } 
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_GV_REDEEM);


/* 
GV_REDEEM_EXPLOIT_FIX (GVREF)
---------------------------------------------
* case: guest accounts can exploit gift voucher sent using "Mail Gift Voucher" (admin area),
*       by sharing the code until somebody logs with a valid account
*       or successfully created new account.
*
* obv:  the session remains on user while served as a guest. 
*       The gift voucher can now be reused to all guest users until 
*       gift voucher is redeemed
* soln: before releasing the gift voucher, the user must login first
*       or asked to create an account.
*
*
* -- Frederick Ricaforte
*/


/*
* connected files:
*   /catalog/gv_redeem.php
*   /catalog/login.php
*   /catalog/create_account.php 
*   /catalog/includes/languages/english/gv_redeem.php
*
*/

/*******************************************************
**** gv_redeem.php  ************************************
*******************************************************/
  //before:  $redeem_query = tep_db_query("select coupon_id from ". TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $coupon['coupon_id'] . "'");
  //----
      // add:GVREF
      if (isset($_SESSION['customer_id']) && $voucher_not_redeemed) {
        $_SESSION['gv_id'] = $coupon['coupon_id'];
        $gv_query = tep_db_query("insert into  " . TABLE_COUPON_REDEEM_TRACK . " (coupon_id, customer_id, redeem_date, redeem_ip) values ('" . $coupon['coupon_id'] . "', '" . $_SESSION['customer_id'] . "', now(),'" . $REMOTE_ADDR . "')");
        $gv_update = tep_db_query("update " . TABLE_COUPONS . " set coupon_active = 'N' where coupon_id = '" . $coupon['coupon_id'] . "'");
        tep_gv_account_update($_SESSION['customer_id'], $_SESSION['gv_id']);
        $error = false;
      } elseif($voucher_not_redeemed) {
      // endof_add:GVREF

        if ( ! isset($_SESSION['floating_gv_code']) ) {
          //}
          $_SESSION['floating_gv_code'] = tep_db_input(tep_db_prepare_input($_GET['gv_no']));
          $gv_error_message = TEXT_NEEDS_TO_LOGIN;
      } else {
        $gv_error_message = TEXT_INVALID_GV;
     }
    } else {
      $gv_error_message = TEXT_INVALID_GV;
    }
    // endof_replace: GVREF

  // replace: GVREF
  // $message = TEXT_INVALID_GV;
  // with:
  $message = $gv_error_message;
  // endof_replace
  
  

/*******************************************************
****  login.php  ******************************************
*******************************************************/
  //before:    $cart->restore_contents();
  //---------
  //add these new codes:
        if ( isset($_SESSION['floating_gv_code']) ) {
          $gv_query = tep_db_query("SELECT c.coupon_id, c.coupon_amount, IF(rt.coupon_id>0, 'true', 'false') AS redeemed FROM ". TABLE_COUPONS ." c LEFT JOIN ". TABLE_COUPON_REDEEM_TRACK." rt USING(coupon_id), ". TABLE_COUPON_EMAIL_TRACK ." et WHERE c.coupon_code = '". $_SESSION['floating_gv_code'] ."' AND c.coupon_id = et.coupon_id");
          // check if coupon exist
          if (tep_db_num_rows($gv_query) >0) {
            $coupon = tep_db_fetch_array($gv_query);
            // check if coupon_id exist and coupon not redeemed
            if($coupon['coupon_id']>0 && $coupon['redeemed'] == 'false') {
              unset($_SESSION['floating_gv_code']);
              $gv_query = tep_db_query("insert into  " . TABLE_COUPON_REDEEM_TRACK . " (coupon_id, customer_id, redeem_date, redeem_ip) values ('" . $coupon['coupon_id'] . "', '" . $_SESSION['customer_id'] . "', now(),'" . $REMOTE_ADDR . "')");
              $gv_update = tep_db_query("update " . TABLE_COUPONS . " set coupon_active = 'N' where coupon_id = '" . $coupon['coupon_id'] . "'");
              tep_gv_account_update($_SESSION['customer_id'], $coupon['coupon_id']);
            }
          }
        }
//**********



/*******************************************************
****  create_account.php  ***********************************
*******************************************************/
  //before: tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
  //---------
  //add these:
      if ( isset($_SESSION['floating_gv_code']) ) {
        $gv_query = tep_db_query("SELECT c.coupon_id, c.coupon_amount, IF(rt.coupon_id>0, 'true', 'false') AS redeemed FROM ". TABLE_COUPONS ." c LEFT JOIN ". TABLE_COUPON_REDEEM_TRACK." rt USING(coupon_id), ". TABLE_COUPON_EMAIL_TRACK ." et WHERE c.coupon_code = '". $_SESSION['floating_gv_code'] ."' AND c.coupon_id = et.coupon_id");
        // check if coupon exist
        if (tep_db_num_rows($gv_query) >0) {
          $coupon = tep_db_fetch_array($gv_query);
          // check if coupon_id exist and coupon not redeemed
          if($coupon['coupon_id']>0 && $coupon['redeemed'] == 'false') {
              unset($_SESSION['floating_gv_code']);
              $gv_query = tep_db_query("insert into  " . TABLE_COUPON_REDEEM_TRACK . " (coupon_id, customer_id, redeem_date, redeem_ip) values ('" . $coupon['coupon_id'] . "', '" . $_SESSION['customer_id'] . "', now(),'" . $REMOTE_ADDR . "')");
              $gv_update = tep_db_query("update " . TABLE_COUPONS . " set coupon_active = 'N' where coupon_id = '" . $coupon['coupon_id'] . "'");
              tep_gv_account_update($_SESSION['customer_id'], $coupon['coupon_id']);
          }
        }
      }

/*******************************************************
****  /includes/languages/english/gv_redeem.php ******************
*******************************************************/
// add:
define('TEXT_NEEDS_TO_LOGIN', 'We are sorry but we are unable to process your Gift Voucher claim at this time. You need to login first or create an account with us, if you don\'t already have one, before you can claim your Gift Voucher. Please <a href="' . tep_href_link(FILENAME_LOGIN,'','SSL').'">click here to login or create an account.</a> ');
  $breadcrumb->add(NAVBAR_TITLE); 
  $content = CONTENT_GV_REDEEM;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');

?>
