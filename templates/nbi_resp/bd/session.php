<?php

if (defined('CONSOLE_MODE') && CONSOLE_MODE)
  $_SESSION = array();

class session {

  var $tag;

  function session() {

    $this->tag = md5(APPROOT_PATH);

  }

  function get($key, $default = null) {
   
    if (defined('CONSOLE_MODE'))
      global $_SESSION;  
      
    if (array_key_exists($this->tag, $_SESSION))
      if (array_key_exists($key, $_SESSION[$this->tag]))
        return $_SESSION[$this->tag][$key];
      else
        return $default;
    else
      return $default;

  }

  function set($key, $value) {

    if (defined('CONSOLE_MODE'))
      global $_SESSION;  

    $_SESSION[$this->tag][$key] = $value;
  
  }

  function clear($key) {
                     
    unset($_SESSION[$this->tag][$key]);

  }

  function clear_all() {
                     
    unset($_SESSION[$this->tag]);

  }
  
}

function session_get($key, $default = null) { global $ses; return $ses->get($key, $default); }
function session_set($key, $value)          { global $ses; return $ses->set($key, $value); }
function session_clear($key)                { global $ses; return $ses->clear($key); }

?>