<?php
/*
  $Id: compatibility.php,v 1.1.1.1 2004/03/04 23:39:50 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
 function parse_url_compat($url, $component=NULL){
   // parse_url adds the $componet for oho 5.12 older version required a compatiblity code to
  //handle the $component feild.
  //http://www.php.net/parse_url

    // Defines only available in PHP 5, created for PHP4
         if(!defined('PHP_URL_SCHEME')) define('PHP_URL_SCHEME', 1);
         if(!defined('PHP_URL_HOST')) define('PHP_URL_HOST', 2);
         if(!defined('PHP_URL_PORT')) define('PHP_URL_PORT', 3);
         if(!defined('PHP_URL_USER')) define('PHP_URL_USER', 4);
         if(!defined('PHP_URL_PASS')) define('PHP_URL_PASS', 5);
         if(!defined('PHP_URL_PATH')) define('PHP_URL_PATH', 6);
         if(!defined('PHP_URL_QUERY')) define('PHP_URL_QUERY', 7);
         if(!defined('PHP_URL_FRAGMENT')) define('PHP_URL_FRAGMENT', 8);

       if(!$component){
       return parse_url($url);
         }
       // PHP 5
       if(phpversion() >= 5){
           return parse_url($url, $component);
        }else{

       // PHP 4
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

   }// end if phpversion
 } //end function

////
// Recursively handle magic_quotes_gpc turned off.
// This is due to the possibility of have an array in
// $HTTP_xxx_VARS
// Ie, product attributes
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
  
  // str_ireplace function for php4 
  if(!function_exists('str_ireplace')) {
    function str_ireplace($find, $replace, $str) {
      $tmpf = array(
       '\\','/','[',']','(',
       ')','*','+','-','?',
       '^','$','.','|','{','}'
      );
      $tmpr = array(
       '\\\\','\/','\[','\]','\(',
       '\)','\*','\+','\-','\?',
       '\^','\$','\.','\|','\{','\}'
      );
      if(!is_array($find)) $find = array($find);
      for($a = 0, $b = count($find); $a < $b; $a++) {
        $find[$a] = '/(?i)'.str_replace($tmpf, $tmpr, $find[$a]).'/';
      }
      return preg_replace($find, $replace, $str);
    }
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
