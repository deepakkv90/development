<?php
/*
  $Id: file_layout.php,v 1.0 2004/04/16 11:22:05 eCartz Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce
  Copyright (c) 2004 eCartz.com, Inc.

  Released under the GNU General Public License
*/

  class file_layout {
    var $modules;

// class constructor
    function file_layout($module = '') {
      global $language, $PHP_SELF;

      if (defined('MODULE_FILE_LAYOUTS_INSTALLED') && tep_not_null(MODULE_FILE_LAYOUTS_INSTALLED)) {
        $this->modules = explode(';', MODULE_FILE_LAYOUTS_INSTALLED);

        $this->include_modules = array();

        if ( (tep_not_null($module)) && (in_array(substr($module['id'], 0, strpos($module['id'], '_')) . '.' . substr($PHP_SELF, (strrpos($PHP_SELF, '.')+1)), $this->modules)) ) {
          $this->include_modules[] = array('class' => substr($module['id'], 0, strpos($module['id'], '_')), 'file' => substr($module['id'], 0, strpos($module['id'], '_')) . '.' . substr($PHP_SELF, (strrpos($PHP_SELF, '.')+1)));
        } else {
          reset($this->modules);
          while (list(, $value) = each($this->modules)) {
            $class = substr($value, 0, strrpos($value, '.'));
            $this->include_modules[] = array('class' => $class, 'file' => $value);
          }
        }

        for ($i=0, $n=sizeof($this->include_modules); $i<$n; $i++) {
          include('includes/languages/' . $language . '/modules/file_layouts/' . $this->include_modules[$i]['file']);
          include(DIR_WS_MODULES . 'file_layouts/' . $this->include_modules[$i]['file']);

          $GLOBALS[$this->include_modules[$i]['class']] = new $this->include_modules[$i]['class'];
        }
      } else {
        $this->include_modules = array();
      }
      $this->selected = $_GET['dltype'];
    }

    function get_header () {
      return $GLOBALS[$this->selected]->get_header();
    }

    function filename () {
      return $GLOBALS[$this->selected]->filename();
    }

    function create() {
      if (is_object($GLOBALS[$this->selected])) {
        $GLOBALS[$this->selected]->create();
      }
    }

    function select($module) {
      $this->selected = $GLOBALS[$module];
    }

    function import_row($line) {

    }

    function export($file_pointer = '') {
      return $GLOBALS[$this->selected]->export($file_pointer);
    }

    function import_selection() {
      $import_array = array();
      foreach ($this->include_modules as $module) {
        $import_array[] = array('id' => $module['class']->code, 'text' => $module['class']->title);
      }
      return $import_array;
    }

    function export_selection() {
      $download_selection = array();
      $tempdir_selection = array();

      foreach ($this->include_modules as $module) {
        $download_selection[] = '<a href="' . tep_href_link(FILENAME_EXPORT, 'download=stream&dltype='   . /*$GLOBALS[*/$module['class']/*]->code*/) . '">' . sprintf(TEMPLATE_EXPORT_STREAM, $GLOBALS[$module['class']]->title) . '</a>';
        $tempdir_selection[]  = '<a href="' . tep_href_link(FILENAME_EXPORT, 'download=tempfile&dltype=' . /*$GLOBALS[*/$module['class']/*]->code*/) . '">' . sprintf(TEMPLATE_EXPORT_TEMPDIR, $GLOBALS[$module['class']]->title) . '</a>';
      }

      return sprintf(TEMPLATE_EXPORT, implode('<br>', $download_selection), IMPORT_EXPORT_TEMP_DIR, implode('<br>', $tempdir_selection));
    }
  }
?>
