<?php
/*
  $Id: buysafe.php,v 1.4 2007/03/15 00:04:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class buysafe_class {
    
    // class constructor
    function buysafe_class() {
      global $language;
      if (defined('MODULE_ADDONS_INSTALLED') && tep_not_null(MODULE_ADDONS_INSTALLED)) {            
        if (strstr(MODULE_ADDONS_INSTALLED, 'buysafe.php')) {           
          $this->modules = array('buysafe.php');
          reset($this->modules);
          while (list(, $value) = each($this->modules))
          {
            include_once(DIR_WS_LANGUAGES . $language . '/modules/addons/' . $value);
            include_once(DIR_WS_MODULES . 'addons/' . $value);
            $class = substr($value, 0, strrpos($value, '.'));
            $GLOBALS[$class] = new $class;
          }
        }
      }
    }

    function call_api($method, $params = array())
    {
      if (is_array($this->modules))
      {
        reset($this->modules);
        while (list(, $value) = each($this->modules))
        {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled)
          {
            return $GLOBALS[$class]->call_api($method, $params);
          }
        }
      }
    }

    function get_debug_info()
    {
      if (is_array($this->modules))
      {
        reset($this->modules);
        while (list(, $value) = each($this->modules))
        {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled)
          {
            return $GLOBALS[$class]->get_debug_info();
          }
        }
      }
    }
  }
?>