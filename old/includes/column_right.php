<?php
/*
  $Id: column_right.php,v 1.1.1.1 2004/03/04 23:40:37 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

// RCI code start
echo $cre_RCI->get('columnright', 'top');
// RCI code eof

$column_query = tep_db_query("SELECT infobox_id, display_in_column as cfgcol, infobox_file_name as cfgtitle, infobox_display as cfgvalue, infobox_define as cfgkey, box_template, box_heading_font_color
                              FROM " . TABLE_INFOBOX_CONFIGURATION . "
                              WHERE template_id = " . TEMPLATE_ID . "
                                and infobox_display = 'yes'
                                and display_in_column = 'right'
                              ORDER BY location");
                                                            
while ($column = tep_db_fetch_array($column_query)) {
  if (file_exists(DIR_FS_TEMPLATE_BOXES . $column['cfgtitle'])) {
    $box_location = DIR_FS_TEMPLATE_BOXES;
  } elseif (DIR_FS_TEMPLATE_DEFAULT_BOXES != DIR_FS_TEMPLATE_BOXES && file_exists(DIR_FS_TEMPLATE_DEFAULT_BOXES . $column['cfgtitle'])) {
    $box_location = DIR_FS_TEMPLATE_DEFAULT_BOXES;
  } else {
    echo "\n" . '<!-- missing infobox ' . $column['cfgtitle'] . ' -->' . "\n";
    continue;
  }
  
  $box_heading = tep_get_box_heading($column['infobox_id'], $languages_id);
  if ($column['cfgkey'] != '') define($column['cfgkey'], $box_heading);
  $infobox_template = $column['box_template'];
  $infobox_template_heading = $infobox_template . 'Heading';
  $infobox_template_footer = $infobox_template . 'Footer';
  $font_color = $column['box_heading_font_color'];
    
  //cache control side box detect
  if ((USE_CACHE == 'true') && empty($SID)) {
    switch ($column['cfgtitle']) {
      case 'categories1.php':
        echo tep_cache_categories_box();
        continue;
        break;
      case 'categories.php':
        echo tep_cache_categories_box();
        continue;
        break;
      case 'categories2.php':
        echo tep_cache_categories_box1();
        continue;
        break;
      case 'categories3.php':
        echo tep_cache_categories_box3();
        continue;
        break;
      case 'categories4.php':
        echo tep_cache_categories_box4();
        continue;
        break;
      case 'categories5.php':
        echo tep_cache_categories_box5();
        continue;
        break;
      case 'manufacturers.php':
        echo tep_cache_manufacturers_box();
        continue;
        break;
    }
  
  } else {
    include_once $box_location . $column['cfgtitle'];
  }
}
// RCI code start
echo $cre_RCI->get('columnright', 'bottom');
// RCI code eof
?>