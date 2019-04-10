<?php
/*
  $Id: checkout_success.php,v 1.1.1.1 2006/06/26 23:40:46 datazen Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class checkout_success {
    var $modules;

// class constructor
    function checkout_success() {
      global $language;

      if (defined('MODULE_CHECKOUT_SUCCESS_INSTALLED') && tep_not_null(MODULE_CHECKOUT_SUCCESS_INSTALLED)) {
        $this->modules = explode(';', MODULE_CHECKOUT_SUCCESS_INSTALLED);
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          include(DIR_WS_LANGUAGES . $language . '/modules/checkout_success/' . $value);
          include(DIR_WS_MODULES . 'checkout_success/' . $value);

          $class = substr($value, 0, strrpos($value, '.'));
          $GLOBALS[$class] = new $class;
        }
      }
    }

    function process() {
      $checkout_success_array = array();
      if (is_array($this->modules)) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          $GLOBALS[$class]->process();
          for ($i=0, $n=sizeof($GLOBALS[$class]->output); $i<$n; $i++) {
            if (isset($GLOBALS[$class]->output[$i]['title']) && isset($GLOBALS[$class]->output[$i]['text']) && tep_not_null($GLOBALS[$class]->output[$i]['title']) && tep_not_null($GLOBALS[$class]->output[$i]['text'])) {
              $checkout_success_array[] = array('code' => $GLOBALS[$class]->code,
                                           'title' => $GLOBALS[$class]->output[$i]['title'],
                                           'text' => $GLOBALS[$class]->output[$i]['text'],
                                           'value' => $GLOBALS[$class]->output[$i]['value'],
                                           'sort_order' => $GLOBALS[$class]->sort_order);
            }
          }
        }
      }
      return $checkout_success_array;
    }

    function output() {
      $output_string = '';
      if (is_array($this->modules)) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          for ($i=0, $n=sizeof($GLOBALS[$class]->output); $i<$n; $i++) {
            $output_string .= '              <tr>' . "\n" .
                              '                <td>' . $GLOBALS[$class]->output[$i]['text'] . '</td>' . "\n" .
                              '              </tr>';
          }
        }
      }
      return $output_string;
    }
}
?>