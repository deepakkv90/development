<?php
/*
  $Id: password_funcs.php,v 1.2 2009/11/24 datazen Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// This funstion validates a plain text password with an encrpyted password
if (!function_exists('tep_validate_password')) {
  function tep_validate_password($plain, $encrypted) {
    if (tep_not_null($plain) && tep_not_null($encrypted)) {
      // split apart the hash / salt
      $stack = explode(':', $encrypted);
      if (sizeof($stack) != 2) return false;
      if (md5($stack[1] . $plain) == $stack[0]) {
        return true;
      }
    }

    return false;
  }
}
// This function makes a new password from a plaintext password.
if (!function_exists('tep_encrypt_password')) {
  function tep_encrypt_password($plain) { 
    $password = '';
    for ($i=0; $i<10; $i++) {
      $password .= tep_rand();
    }
    $salt = substr(md5($password), 0, 2);
    $password = md5($salt . $plain) . ':' . $salt;

    return $password;
  }
}
// This function creates a hardened password
function tep_create_hard_pass() {
  $length = (defined('ENTRY_PASSWORD_MIN_LENGTH')) ? ENTRY_PASSWORD_MIN_LENGTH : 12;
  $chars = "234567890abcdefghijkmnopqrstuvwxyz098765432ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $hpass = "";
  for($i = 0; $i < $length; $i++) {
    $hpass .= $chars{mt_rand(0,strlen($chars))};
  }
  // insure password has upper, lower case and numbers
  if (!preg_match('/[0-9][A-Z][a-z]/', $hpass)) tep_create_hard_pass();
  
  return $hpass;
}

//this is the function to validate password as it is. May 24 2011
if (!function_exists('tep_validate_encrypted_password')) {
  function tep_validate_encrypted_password($encrypted_1, $encrypted_2) {
    if (tep_not_null($encrypted_1) && tep_not_null($encrypted_2)) {      
      if ($encrypted_1 == $encrypted_2) {
        return true;
      }
    }
    return false;
  }
}

?>