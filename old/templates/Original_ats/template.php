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
  define('TEMPLATE_NAME_REF', 'original_ats');
  define('TEMPLATE_VERSION', '1.0');
  define('TEMPLATE_SYSTEM', 'ATS');
  define('TEMPLATE_AUTHOR', 'Algozone, Inc.');
  
  define('TEMPLATE_STYLE', DIR_WS_TEMPLATES . TEMPLATE_NAME . "/stylesheet.css");
  
  //used to get boxes from default
  define('DIR_FS_TEMPLATE_BOXES', DIR_FS_TEMPLATES . 'default/boxes/');
  define('DIR_FS_TEMPLATE_MAINPAGES', DIR_FS_TEMPLATES . 'default/mainpage_modules/');
  define('DIR_WS_TEMPLATE_IMAGES', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/');
  
  //which files to use
  define('DIR_WS_TEMPLATE_MAIN', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/');
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

  //buttons
  define('TEMPLATE_BUTTONS_USE_CSS','false');

  //on/ off switches for infobox
  //for sidebox footer display these images
  define('TEMPLATE_INCLUDE_FOOTER', 'true');//false = disable footer on all infoboses, this is used in infoboxes, not in boxes.tpl.php
  define('TEMPLATE_BOX_IMAGE_FOOTER_LEFT', 'false');
  define('TEMPLATE_BOX_IMAGE_FOOTER_RIGHT', 'false');
  //for side header display on/off
  define('TEMPLATE_INFOBOX_TOP_LEFT', 'false');  
  define('TEMPLATE_INFOBOX_TOP_RIGHT', 'true');
   //infobox and content box side bars, 
   //need soem workaround to show different borders
  define('TEMPLATE_INFOBOX_BORDER_LEFT', 'true');//show left side border for infobox
  define('TEMPLATE_INFOBOX_BORDER_RIGHT', 'true');//show right side brder for infobox
  define('TEMPLATE_INFOBOX_BORDER_IMAGE_LEFT', '');   //left side image, if this is blank and TEMPLATE_INFOBOX_BORDER_LEFT is set to true, it will use BoxBorderLeft css class
  define('TEMPLATE_INFOBOX_BORDER_IMAGE_RIGHT', ''); //right side image, if this is blank, if this is blank and TEMPLATE_INFOBOX_BORDER_RIGHT is set to true, it will use BoxBorderRight css class

  //infobox header images
  define('TEMPLATE_INFOBOX_IMAGE_TOP_LEFT', 'corner_left.gif');
  define('TEMPLATE_INFOBOX_IMAGE_TOP_RIGHT', 'no_arrow_right.gif');
  define('TEMPLATE_INFOBOX_IMAGE_TOP_RIGHT_ARROW', 'arrow_right.gif');
  //infoboxfooter images
    //infobox header images
  define('TEMPLATE_INFOBOX_IMAGE_FOOTER_LEFT', '');
  define('TEMPLATE_INFOBOX_IMAGE_FOOTER_RIGHT', '');

  //contentboxes  #############
  define('TEMPLATE_CONTENT_TABLE_WIDTH','100%');
  define('TEMPLATE_CONTENT_TABLE_CELLPADDIING','0');
  define('TEMPLATE_CONTENT_TABLE_CELLSPACING','0');
  //turn on/off
  define('TEMPLATE_INCLUDE_CONTENT_FOOTER','true');//footer for content boxes
  //content header
  define('TEMPLATE_CONTENTBOX_TOP_LEFT','false');
  define('TEMPLATE_CONTENTBOX_TOP_RIGHT','false');
  //footer
  define('TEMPLATE_CONTENTBOX_FOOTER_LEFT','false');
  define('TEMPLATE_CONTENTBOX_FOOTER_RIGHT','false');
  
  //images
  define('TEMPLATE_CONTENTBOX_IMAGE_TOP_LEFT', '');
  define('TEMPLATE_CONTENTBOX_IMAGE_TOP_RIGHT', '');
  define('TEMPLATE_CONTENTBOX_IMAGE_TOP_RIGHT_ARROW','arrow_right.gif');

  //footer
  define('TEMPLATE_CONTENTBOX_IMAGE_FOOT_LEFT','');
  define('TEMPLATE_CONTENTBOX_IMAGE_FOOT_RIGHT','');  
?>