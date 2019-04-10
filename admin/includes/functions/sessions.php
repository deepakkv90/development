<?php
/*
  $Id: sessions.php,v 1.1.1.1 2004/03/04 23:40:51 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  
  if (defined('MYSESSION_LIFETIME') && tep_not_null(MYSESSION_LIFETIME)) {
    $SESS_LIFE = MYSESSION_LIFETIME;
    ini_set('session.gc_maxlifetime', MYSESSION_LIFETIME);
  } else {
    $SESS_LIFE = 1440;
    ini_set('session.gc_maxlifetime', 1440);
  }
  
  if (STORE_SESSIONS == 'mysql') {
    
    function _sess_open($save_path, $session_name) {
      return true;
    }

    function _sess_close() {
      return true;
    }

    function _sess_read($key) {
      $value_query = tep_db_query("select value from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "' and expiry > '" . time() . "'");
      $value = tep_db_fetch_array($value_query);

      if (isset($value['value'])) {
        return $value['value'];
      }

      return false;
    }

    function _sess_write($key, $val) {
      global $SESS_LIFE;

      $expiry = time() + $SESS_LIFE;
      $value = $val;

      $check_query = tep_db_query("select count(*) as total from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
      $check = tep_db_fetch_array($check_query);

      if ($check['total'] > 0) {
        return tep_db_query("update " . TABLE_SESSIONS . " set expiry = '" . tep_db_input($expiry) . "', value = '" . tep_db_input($value) . "' where sesskey = '" . tep_db_input($key) . "'");
      } else {
        return tep_db_query("insert into " . TABLE_SESSIONS . " values ('" . tep_db_input($key) . "', '" . tep_db_input($expiry) . "', '" . tep_db_input($value) . "')");
      }
    }

    function _sess_destroy($key) {
      return tep_db_query("delete from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
    }

    function _sess_gc($maxlifetime) {
      tep_db_query("delete from " . TABLE_SESSIONS . " where expiry < '" . time() . "'");

      return true;
    }

    session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
  }

  function tep_session_start() {
    global $SESS_LIFE;
    
    $session_id = '';
    $session_name = tep_session_name();
    
    if (isset($_GET[$session_name])) {
      if (preg_match('/^[a-zA-Z0-9]+$/', $_GET[$session_name]) == false) {
        unset($_GET[$session_name]);
      } else {
        $session_id = $_GET[$session_name];
      }
      
    } elseif (isset($_POST[$session_name])) {
      if (preg_match('/^[a-zA-Z0-9]+$/', $_POST[$session_name]) == false) {
        unset($_POST[$session_name]);
      } else {
        $session_id = $_POST[$session_name];
      }
      
    } elseif (isset($_COOKIE[$session_name])) {
      if (preg_match('/^[a-zA-Z0-9]+$/', $_COOKIE[$session_name]) == false) {
        $session_data = session_get_cookie_params();
        setcookie($session_name, '', time()-42000, $session_data['path'], $session_data['domain']);
      } else {
        $session_id = $_COOKIE[$session_name];
      }
    }
    
    // if a session ID has been passed to the site, use it
    if (tep_not_null($session_id)) {
      tep_session_id($session_id);
    }
    
    // do the actual session start
    $session_start_state = session_start();
    
    // if a passed ID was used, see if our server recorded variable is present
    if (tep_not_null($session_id)) {
      if (!isset($_SESSION['session_start_time'])) {
        // if not present, do not use the current session ID
        tep_session_recreate();
      }
    }
    
    // if this is a new session, place our server variable in place
    if (!isset($_SESSION['session_start_time'])) {
      $_SESSION['session_start_time'] = time();
    } else {
      // if the session has existed too long, recreate it to prevent exposure
      $curr_time = time();
      if ($curr_time - $_SESSION['session_start_time'] > $SESS_LIFE) {
        tep_session_recreate();
        $_SESSION['session_start_time'] = time();
      }
    }
    
    return $session_start_state;
  }

  function tep_session_id($sessid = '') {
    if (!empty($sessid)) {
      return session_id($sessid);
    } else {
      return session_id();
    }
  }

  function tep_session_name($name = '') {
    if (!empty($name)) {
      return session_name($name);
    } else {
      return session_name();
    }
  }

  function tep_session_close() {
    session_write_close();
  }

  function tep_session_destroy() {
    // the old registered global did not unset the global when the
    // session was destroyed, so this does not either
    $_SESSION = array();
    return session_destroy();
  }

  function tep_session_save_path($path = '') {
    if (!empty($path)) {
      return session_save_path($path);
    } else {
      return session_save_path();
    }
  }

  function tep_session_recreate() {
/*
    $session_backup = $_SESSION;

    unset($_COOKIE[tep_session_name()]);

    tep_session_destroy();

    if (STORE_SESSIONS == 'mysql') {
      session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
    }

    session_start();  // do not use the tep_ function, it does too much

    $_SESSION = $session_backup;
    unset($session_backup);
*/
    session_regenerate_id();
    
    if (STORE_SESSIONS == 'mysql') {
      session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
    }
  }
?>