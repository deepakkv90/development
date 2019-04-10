<?php
/*
  $Id: cre_RCO.php,v 1.0.0.0 2006/11/21 13:41:11 ccwjr Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded

  Released under the GNU General Public License
*/

  class cre_RCO {
    var $_folders = array();

    function cre_RCO() {
      if ( ! isset($_SESSION['cre_RCO_data']) ) {
        $_SESSION['cre_RCO_data'] = array('folders' => array() );
      }
      $this->_folders =& $_SESSION['cre_RCO_data']['folders'];
    }

    function get($pageName, $function, $display = true) {
      $pageName = strtolower($pageName);
      $function = strtolower($function);
      $rco_return = false;  // set to false in case no files are found to process
      
      // if cache is allowed, see if the page name is known
      if (USE_CACHE == 'false' || USE_CACHE == 'False' || ! array_key_exists($pageName, $this->_folders) ) {
        $this->_build_folder($pageName);
      }

      if ( array_key_exists($function, $this->_folders[$pageName]) ) {
        $fileName = $this->_folders[$pageName][$function];
        // safety check in case a false positive was received on the cache check
        if ( ! file_exists(DIR_WS_INCLUDES . 'runoverride/' . $pageName . '/' . $fileName) ) {
          $this->_folders = array();  // invalidate the cache since it is corrupt
        } else {
          $rco = true;
          if ((DISPLAY_PAGE_PARSE_TIME == 'true') && ($display == true)) {
            echo '<!-- RCO [BOM] -' . $pageName . '-' . $function . ' : ' . DIR_WS_INCLUDES . 'runoverride/' . $pageName . '/' . $fileName . ' --->';
      }         
          include_once(DIR_WS_INCLUDES . 'runoverride/' . $pageName . '/' . $fileName);
          if ((DISPLAY_PAGE_PARSE_TIME == 'true') && ($display == true)) {
            echo '<!-- RCO [EOM] -' . $pageName . '-' . $function . ' : ' . DIR_WS_INCLUDES . 'runoverride/' . $pageName . '/' . $fileName . ' --->';
      }
          if ($rco === true) $rco_return = true;
        }
      }
      return $rco_return;
    } // end of function

    function _build_folder($pageName) {
      $this->_folders[$pageName] = array();    
      if ( is_dir(DIR_WS_INCLUDES . 'runoverride/' . $pageName) ) {
        $pattern = '/(\w*)_*(\w+)_(\w+)_(\w+)\.php$/';
        $dir = opendir(DIR_WS_INCLUDES . 'runoverride/' . $pageName);
        while( $file = readdir( $dir ) ) {
          if ($file == '.'  || $file == '..') continue;
          $match = array();
          if ( preg_match($pattern, $file, $match) > 0 ) {
            if ( $match[3] == $pageName ) {
              $this->_folders[$pageName][$match[4]] = $match[0];
            }
          }
        }
      }
    }
  }
?>