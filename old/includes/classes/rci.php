<?php
/*
  $Id: cre_RCI.php,v 1.0.0.0 2006/11/21 13:41:11 ccwjr Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class cre_RCI {
    var $_folders = array();

    function cre_RCI() {
      if ( ! isset($_SESSION['cre_RCI_data']) ) {
        $_SESSION['cre_RCI_data'] = array('folders' => array() );
      }
      $this->_folders =& $_SESSION['cre_RCI_data']['folders'];
    }

    function get($pageName, $function, $display = true) {
      $pageName = strtolower($pageName);
      $function = strtolower($function);
      $rci_holder = '';
      
      // if cache is allowed, see if the page name is known
      if (USE_CACHE == 'false' || USE_CACHE == 'False' || ! array_key_exists($pageName, $this->_folders) ) {
        $this->_build_folder($pageName);
      }

      if ( array_key_exists($function, $this->_folders[$pageName]) ) {
        foreach( $this->_folders[$pageName][$function] as $fileName ) {
          // safety check in case a fasle positive was received on the cache check
          if ( ! file_exists(DIR_WS_INCLUDES . 'runtime/' . $pageName . '/' . $fileName) ) {
            $this->_folders = array();  // invalid the cache since it is corrupt
            continue;
          }
          $rci = '';
          if ($pageName == 'stylesheet') {  // special case of a style sheet
            $rci = '<link rel="stylesheet" type="text/css" href="' . DIR_WS_INCLUDES . 'runtime/' . $pageName . '/' . $fileName . '">';
          } else {
            include(DIR_WS_INCLUDES . 'runtime/' . $pageName . '/' . $fileName);
          }
          if ((DISPLAY_PAGE_PARSE_TIME == 'true') && ($display == true)) {
            $rci_holder .= '<!-- RCI [BOM] -' . $pageName . '-' . $function . ' : ' . DIR_WS_INCLUDES . 'runtime/' . $pageName . '/' . $fileName . ' -->' . "\n" . $rci . '<!-- RCI [EOM] -' . $pageName . '-' . $function . ' : ' . DIR_WS_INCLUDES . 'runtime/' . $pageName . '/' . $fileName . ' -->' . "\n";
          } else {
            $rci_holder .= $rci;
          }
        }
      }
      return $rci_holder;
    }

    function _build_folder($pageName) {
      $this->_folders[$pageName] = array();    
      if ( is_dir(DIR_WS_INCLUDES . 'runtime/' . $pageName) ) {
        $filesFound = array();
        if ($pageName == 'stylesheet') {
          $pattern = '/(\w*)_*(\w+)_(\w+)_(\w+)\.css$/';
        }else{
          $pattern = '/(\w*)_*(\w+)_(\w+)_(\w+)\.php$/';
        }
        $dir = opendir(DIR_WS_INCLUDES . 'runtime/' . $pageName);
        while( $file = readdir( $dir ) ) {
          if ($file == '.'  || $file == '..') continue;
          $match = array();
          if ( preg_match($pattern, $file, $match) > 0 ) {
            if ( $match[3] == $pageName ) {
              $filesFound[$match[0]] = $match[4];
            }
          }
        }
        if ( count($filesFound) > 0) {
          ksort($filesFound);
          foreach( $filesFound as $file => $function ) {
            $this->_folders[$pageName][$function][] = $file;
          }
        }
      }
    }
  }
?>