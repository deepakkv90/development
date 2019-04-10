<?php
/*
  $Id: mainpage.php,v 2.1 2004/04/07

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
  to change the contents of this file edit
  includes/languages/(language)/mainpage.php
*/
?>
<!-- D Main_page //-->


<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, HEADING_TITLE);
}
// EOF: Lango Added for template MOD


include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFINE_MAINPAGE);



// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>

<!-- Main_page eof //-->
