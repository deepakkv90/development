<?php
/*
  $Id: compatibility.php,v 1.1.1.1 2004/03/04 23:40:48 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Modified by Marco Canini, <m.canini@libero.it>
  - Fixed a bug with arrays in $HTTP_xxx_VARS
*/

  if (phpversion() < '5.1.2') {
    define('PHP_URL_SCHEME', 0);
    define('PHP_URL_HOST', 1);
    define('PHP_URL_PORT', 2);
    define('PHP_URL_USER', 3);
    define('PHP_URL_PASS', 4);
    define('PHP_URL_PATH', 5);
    define('PHP_URL_QUERY', 6);
    define('PHP_URL_FRAGMENT', 7);
  }

  function parse_url_compat() {
    $args = func_get_args();
    if (phpversion() >= '5.1.2') {
      return call_user_func_array('parse_url', $args);
    } elseif (func_num_args() < 2) {
      return call_user_func_array('parse_url', $args);
    } else {
      // PHP 4
      $url = $args[0];
      $component = $args[1];
      $bits = parse_url($url);
      switch($component){
        case PHP_URL_SCHEME: return $bits['scheme'];
        case PHP_URL_HOST: return $bits['host'];
        case PHP_URL_PORT: return $bits['port'];
        case PHP_URL_USER: return $bits['user'];
        case PHP_URL_PASS: return $bits['pass'];
        case PHP_URL_PATH: return $bits['path'];
        case PHP_URL_QUERY: return $bits['query'];
        case PHP_URL_FRAGMENT: return $bits['fragment'];
      }//end case
    }
  } //end function


////
// Recursively handle magic_quotes_gpc turned off.
// This is due to the possibility of have an array in
// $HTTP_xxx_VARS
// Ie, products attributes
  function do_magic_quotes_gpc(&$ar) {
    if (!is_array($ar)) return false;

    while (list($key, $value) = each($ar)) {
      if (is_array($value)) {
        do_magic_quotes_gpc($value);
      } else {
        $ar[$key] = addslashes($value);
      }
    }
  }

// handle magic_quotes_gpc turned off.
  if (!get_magic_quotes_gpc()) {
    do_magic_quotes_gpc($_GET);
    do_magic_quotes_gpc($_POST);
    do_magic_quotes_gpc($_COOKIE);
  }


  function tep_session_register($variable) {
    global $session_started;

    if (isset($GLOBALS[$variable])) {
      $_SESSION[$variable] =& $GLOBALS[$variable];
    } else {
      $GLOBALS[$variable] = null;
      $_SESSION[$variable] =& $GLOBALS[$variable];
    }

    return false;
  }


  function tep_session_is_registered($variable) {
    return isset($_SESSION) && array_key_exists($variable, $_SESSION);
  }

  
  function tep_session_unregister($variable) {
    if (isset($GLOBALS[$variable])) {
      unset($GLOBALS[$variable]);
    }
    if (isset($_SESSION[$variable])) {
      unset($_SESSION[$variable]);
    }

    return true;
  }
  
  
  // this function has been removed, so a version as added here for compat
  function tep_get_products_special_price($product_id) {
    global $pf;
    $pf->loadProduct($products_id, $languages_id);
    if ($pf->hasSpecialPrice) {
      return $pf->specialPrice;
    } else {
      return false;
    }
  }
?>
