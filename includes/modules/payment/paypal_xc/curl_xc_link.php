<?php
/*
  $Id: curl_xc_link.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

class curl_xc_link {
  var $url, $cookie_name, $curl, $error;
  
  function curl_xc_link($url, $cookie_name = '/tmp/paypal_xc_cookie.tmp') {
    if (!function_exists('curl_init')) {
      trigger_error('curl_xc_link::error, Sorry but it appears that CURL is not loaded, Please install it to continue.');
      return false;
    }
    if (empty($url)) {
//      trigger_error('curl_xc_link::error, The link exchange website location is required to continue, Please edit your script.');
      return false;
    }
    $this->url = $url;
    $this->cookie_name = $cookie_name;
    $this->curl = null;
    $this->error = array();
  }
  
  function init() {
    global $_SERVER;
    $this->curl = curl_init();    
//    curl_setopt($this->curl, CURLOPT_HEADER, 1);
//    curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
//    preg_match_all('|Set-Cookie: (.*);|U', $content, $results);   
//    $cookies = implode(';', $results[1]);
//    curl_setopt($this->curl, CURLOPT_COOKIE,  $cookies);
//    curl_setopt($this->curl, CURLOPT_COOKIE, $this->cookie_name);
//    curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_name);
//    curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_name); 
    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($this->curl, CURLOPT_VERBOSE, 0);
    curl_setopt($this->curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
  // added support for curl proxy
    if (defined('CURL_PROXY_HOST') && defined('CURL_PROXY_PORT') && CURL_PROXY_HOST != '' && CURL_PROXY_PORT != '') {
      curl_setopt($this->curl, CURLOPT_HTTPPROXYTUNNEL, TRUE);
      curl_setopt($this->curl, CURLOPT_PROXY, CURL_PROXY_HOST . ":" . CURL_PROXY_PORT);
      curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    }  
  }
  
  function post($post_fields, $url = '') {
    if ($url == '') {
      $url = $this->url;
    }
    if ($this->curl == null) {
      return false;
    }    
                                              
    curl_setopt($this->curl, CURLOPT_URL, $url);
    curl_setopt($this->curl, CURLOPT_POST, 1);
    curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post_fields);
//    curl_setopt($this->curl, CURLOPT_TIMEOUT, 5);
    
    $result = curl_exec($this->curl);
    if (curl_errno($this->curl)) {
      $this->error = curl_errno($this->curl) . ' - ' . curl_error($this->curl);
      curl_close($this->curl);
      $this->write_log($this->error);
      return false;
    }
    return $result;
  }

  function redirect($url) {
    curl_setopt($this->curl, CURLOPT_URL, $url);
    curl_setopt($this->curl, CURLOPT_POST, 0);
    $result = curl_exec($this->curl);
    if (curl_errno($this->curl)) {
      $this->error = curl_errno($this->curl) . ' - ' . curl_error($this->curl);
      curl_close($this->curl);
      $this->write_log($this->error);
      return false;
    }
    return $result;
  }
  
  function close($url = '') {
    if ($url != '') {
      $this->redirect($url);
    }
    curl_close($this->curl);
  }
  
  function array_to_http($array) {
    $retvar = '';
    while (list($field, $data) = @each($array)) {
      $retvar .= (empty($retvar)) ? '' : '&';
      $retvar .= urlencode($field) . '=' . urlencode($data); 
    }
    return $retvar;
  }
  
  function write_log($message, $log_file = 'debug/paypal_xc_debug.txt') {
    $fp = @fopen($log_file, 'a');
    @fwrite($fp, 'Log Time: ' . date('Y-m-d H:i:s') . "\n");
    @fwrite($fp, $message . "\n\n");
    @fclose($fp);
  }
}
?>
