<?php
/*
  $Id: template.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  define('TEMPLATE_NAME_REF', 'cre63_ats');
  define('TEMPLATE_VERSION', '1.1');
  define('TEMPLATE_SYSTEM', 'ATS');
  define('TEMPLATE_AUTHOR', 'Algozone, Inc.');
  
  define('TEMPLATE_STYLE', DIR_WS_TEMPLATES . TEMPLATE_NAME . "/stylesheet.css");
  
  //used to get boxes from default
  define('DIR_FS_TEMPLATE_BOXES', DIR_FS_TEMPLATES . 'default/boxes/');
  define('DIR_FS_TEMPLATE_MAINPAGES', DIR_FS_TEMPLATES . 'default/mainpage_modules/');
  define('DIR_WS_TEMPLATE_IMAGES', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/');
    
  //which files to use
  define('TEMPLATE_BOX_TPL', DIR_WS_TEMPLATES . 'default/boxes.tpl.php');
  define('TEMPLATE_HTML_OUT', DIR_WS_TEMPLATES . 'default/extra_html_output.php' );
  //variables moved from box.tpl.php
  define('TEMPLATE_TABLE_BORDER', '0');
  define('TEMPLATE_TABLE_WIDTH', '100%'); //table class width, it is always good to have 100%
  define('TEMPLATE_TABLE_CELLSPACING', '0');
  define('TEMPLATE_TABLE_CELLPADDIING', '0');
  define('TEMPLATE_TABLE_PARAMETERS', '');
  define('TEMPLATE_TABLE_ROW_PARAMETERS', '');
  define('TEMPLATE_TABLE_DATA_PARAMETERS', '');
  define('TEMPLATE_TABLE_CONTENT_CELLPADING', '6');
  define('TEMPLATE_TABLE_CENTER_CONTENT_CELLPADING', '4');
  
  //for sidebox footer display these images
  define('TEMPLATE_INCLUDE_FOOTER', 'true');//false = disable footer on all infoboses, this is used in infoboxes, not in boxes.tpl.php
  define('TEMPLATE_BOX_IMAGE_FOOTER_LEFT', 'true');
  define('TEMPLATE_BOX_IMAGE_FOOTER_RIGHT', 'true');
  
  //for side header display on/off
  define('TEMPLATE_INFOBOX_TOP_LEFT', 'true');  
  define('TEMPLATE_INFOBOX_TOP_RIGHT', 'true');
  
  //infobox and content box side bars, 
  //need soem workaround to show different borders
  define('TEMPLATE_INFOBOX_BORDER_LEFT', 'true');//show left side border for infobox
  define('TEMPLATE_INFOBOX_BORDER_RIGHT', 'true');//show right side brder for infobox
  define('TEMPLATE_INFOBOX_BORDER_IMAGE_LEFT', '');   //left side image, if this is blank and TEMPLATE_INFOBOX_BORDER_LEFT is set to true, it will use BoxBorderLeft css class
  define('TEMPLATE_INFOBOX_BORDER_IMAGE_RIGHT', ''); //right side image, if this is blank, if this is blank and TEMPLATE_INFOBOX_BORDER_RIGHT is set to true, it will use BoxBorderRight css class

  //infobox header images
  define('TEMPLATE_INFOBOX_IMAGE_TOP_LEFT', 'infobox_top_left.png');
  define('TEMPLATE_INFOBOX_IMAGE_TOP_RIGHT', 'infobox_top_right.png');
  define('TEMPLATE_INFOBOX_IMAGE_TOP_RIGHT_ARROW', 'infobox_top_right_arrow.png');
  //infoboxfooter images
    //infobox header images
  define('TEMPLATE_INFOBOX_IMAGE_FOOTER_LEFT', 'infobox_footer_left.png');
  define('TEMPLATE_INFOBOX_IMAGE_FOOTER_RIGHT', 'infobox_footer_right.png');

  //contentboxes  #############
  define('TEMPLATE_CONTENT_TABLE_WIDTH','100%');
  define('TEMPLATE_CONTENT_TABLE_CELLPADDIING','0');
  define('TEMPLATE_CONTENT_TABLE_CELLSPACING','0');
  //turn on/off
  define('TEMPLATE_INCLUDE_CONTENT_FOOTER','true');//footer for content boxes
  //content header
  define('TEMPLATE_CONTENTBOX_TOP_LEFT','true');
  define('TEMPLATE_CONTENTBOX_TOP_RIGHT','true');
  //footer
  define('TEMPLATE_CONTENTBOX_FOOTER_LEFT','true');
  define('TEMPLATE_CONTENTBOX_FOOTER_RIGHT','true');
  
  //images
  define('TEMPLATE_CONTENTBOX_IMAGE_TOP_LEFT', 'content_top_left.png');
  define('TEMPLATE_CONTENTBOX_IMAGE_TOP_RIGHT', 'content_top_right.png');
  define('TEMPLATE_CONTENTBOX_IMAGE_TOP_RIGHT_ARROW','content_top_rightarrow.png');

  //footer
  define('TEMPLATE_CONTENTBOX_IMAGE_FOOT_LEFT','content_footer_left.png');
  define('TEMPLATE_CONTENTBOX_IMAGE_FOOT_RIGHT','content_footer_right.png');
  
  // custom entries
  define('TEMPLATE_FS_CUSTOM_INCLUDES',DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/includes/');
  define('TEMPLATE_BUTTONS_USE_CSS','true');
  define('TEMPLATE_FS_CUSTOM_MODULES',TEMPLATE_FS_CUSTOM_INCLUDES . 'modules/');
?>