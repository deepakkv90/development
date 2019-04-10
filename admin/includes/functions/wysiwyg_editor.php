<?php
/*
  $Id: wysiwyg_editor.php,v 1.0.0.0 2008/05/28 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
// configurations
//left for future upgrades
define('HTML_EDITOR_LANG','en');//Force editor to use english language, if none, will use default admin language
define('HTML_EDITOR_INTERFACE','tiny_mce'); // The WYSIWYG interface to use when editing pages, we may add more in future
define('HTML_EDITOR_TOOLBAR_SET','advanced');//  Toolbar set options, simple, advanced, for mailing scripts, it uses basic.
// end configurations


//Main Functions for html editor calls
if ((HTML_EDITOR_ENABLE == 'Enable') && (file_exists(DIR_FS_ADMIN . 'editors/' . HTML_EDITOR_INTERFACE . '.php'))){
require('editors/' . HTML_EDITOR_INTERFACE . '.php');
} else {
function tep_load_html_editor() {
  echo '<!-- No editor loaded -->' . "\n";
  echo '<br>Error  HTML editor may be turn off or DIR_FS_ADMIN is incorrect<br>';
}
function tep_insert_html_editor ( $textarea, $tool_bar_set = '', $editor_height = '' ) {
  echo '<!-- No editors to load -->' . "\n";
  echo '<br>Error  HTML editor may be turn off or DIR_FS_ADMIN is incorrect<br>';
}
}
?>