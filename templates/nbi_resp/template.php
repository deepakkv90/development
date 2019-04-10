<?php
/*
  $Id: Nbi.php,

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 CRE Loaded

  Released under the GNU General Public License
*/
define('TEMPLATE_NAME', 'nbi');
define('TEMPLATE_VERSION', '1.0');
define('TEMPLATE_SYSTEM', 'NBI New Version');
define('TEMPLATE_AUTHOR', '');

//used to get boxes from default
define('DIR_FS_TEMPLATE_BOXES', DIR_FS_CATALOG . 'templates/default/boxes');
define('DIR_FS_TEMPLATE_MAINPAGES', DIR_FS_CATALOG . 'templates/default/mainpage_modules/');

//which files to use
define('TEMPLATE_BOX_TPL', DIR_WS_TEMPLATES . '/default/boxes.tpl.php');
define('TEMPLATE_HTML_OUT', DIR_WS_TEMPLATES . '/default/extra_html_output.php' );

define('DIR_WS_TEMPLATE_IMAGES', 'templates/nbi/images/');
define('TEMPLATE_IMAGE_DIRECTORY',DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images');

//variables moved from box.tpl.php
define('TEMPLATE_TABLE_BORDER', '0');
define('TEMPLATE_TABLE_WIDTH', '100%');
define('TEMPLATE_TABLE_CELLSPACING', '0');
define('TEMPLATE_TABLE_CELLPADDIING', '0');
define('TEMPLATE_TABLE_PARAMETERS', '');
define('TEMPLATE_TABLE_ROW_PARAMETERS', '');
define('TEMPLATE_TABLE_DATA_PARAMETERS', '');
define('TEMPLATE_TABLE_CONTENT_CELLPADING', '');
define('TEMPLATE_TABLE_CENTER_CONTENT_CELLPADING', '4');

//for sidebox footer
define('TEMPLATE_BOX_IMAGE_FOOTER_LEFT', 'true');
define('TEMPLATE_BOX_IMAGE_FOOTER_RIGHT', 'true');
define('TEMPLATE_BOX_IMAGE_FOOTER_RIGHT_ARROW', 'true');
define('TEMPLATE_BOX_IMAGE_FOOTER_LEFTRIGHT', 'true');
//for side header
define('TEMPLATE_BOX_IMAGE_BORDER_TOP_LEFT', 'true');
define('TEMPLATE_BOX_IMAGE_BORDER_TOP_RIGHT', 'true');
define('TEMPLATE_BOX_IMAGE_BORDER_TOP_LEFTRIGHT', 'true');

//for content header
define('TEMPLATE_IMAGE_BORDER_TOP_LEFT', 'true');
define('TEMPLATE_IMAGE_BORDER_TOP_RIGHT', 'true');
//include footer
define('TEMPLATE_INCLUDE_FOOTER', 'true');
//images to use or html

define('TEMPLATE_BOX_IMAGE_TOP_TRANS', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infobox/pixel_trans.gif');

//box header images
define('TEMPLATE_BOX_IMAGE_TOP_LEFT', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infobox/corner_left.gif');
define('TEMPLATE_BOX_IMAGE_TOP_RIGHT', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infobox/corner_right.gif');
define('TEMPLATE_BOX_IMAGE_TOP_LEFTRIGHT', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infobox/corner_right_left.gif');
define('TEMPLATE_BOX_IMAGE_TOP_RIGHTARROW', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infobox/arrow_right.gif');
define('TEMPLATE_BOX_IMAGE_TOP_NOARROW', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infobox/no_arrow_right.gif');
define('TEMPLATE_BOX_IMAGE_TOP_BACKGROUND', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infobox/background.gif');

//box footer images
define('TEMPLATE_BOX_IMAGE_FOOT_LEFT', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infobox/corner_left_flip.gif');
define('TEMPLATE_BOX_IMAGE_FOOT_RIGHT', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infobox/corner_right_flip.gif');
define('TEMPLATE_BOX_IMAGE_FOOT_LEFTRIGHT', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infobox/corner_right_left.gif');
define('TEMPLATE_BOX_IMAGE_FOOT_BACKGROUND', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infobox/backgroundfb.gif');
define('TEMPLATE_BOX_IMAGE_FOOT_NOARROW', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infobox/corner_right_flip');
define('TEMPLATE_BOX_IMAGE_FOOTER_IMAGE_RIGHT_ARROW', DIR_WS_TEMPLATES . TEMPLATE_NAME . '');

// box middles images
define('TEMPLATE_BOX_MIDDLE_LEFT_IMAGE', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infobox/box_bg_l.gif');
define('TEMPLATE_BOX_MIDDLE_MIDDLE_IMAGE', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infobox/background_box.gif');
define('TEMPLATE_BOX_MIDDLE_RIGHT_IMAGE', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infobox/box_bg_r.gif');

//define('TEMPLATE_BUTTONS_USE_CSS', 'true'); //for change button using css styles

?>