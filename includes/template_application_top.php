<?php
/*
  $Id: template_application_top.php,v 1.0.0.0 2007/02/16 11:21:11 Exp $

  CRE Loaded, Commercial Open Source E-Commerce
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

 // allow for badly written older templates
 require DIR_FS_CLASSES . 'tableBoxMessagestack.php';
 
$thema_template = '';

if (isset($_SESSION['customer_id'])) {
  // get customer selected template if there is a customer selected template
  // Check to see if there is an update to the customer selected template
  if (isset($_GET['action']) && $_GET['action'] == 'update_template') {
    $thema_template = $purifier->purify($_POST['template']);
    if ($thema_template != '') {
      tep_db_query("UPDATE " . TABLE_CUSTOMERS . "
                    SET customers_selected_template = '$thema_template'
                    WHERE customers_id = " . $_SESSION['customer_id']);
      // tep_redirect(tep_href_link(basename(FILENAME_DEFAULT)));
    }
  } else {
    $customer_template_query = tep_db_query("SELECT customers_selected_template
                                             FROM " . TABLE_CUSTOMERS . "
                                             WHERE customers_id = " . $_SESSION['customer_id']);
    $ctemplate = tep_db_fetch_array($customer_template_query);
    
    if($ctemplate['customers_selected_template'] != "") {
      $thema_template = $ctemplate['customers_selected_template'];
    } else {
      $group_template_query = tep_db_query("SELECT group_template FROM ". TABLE_CUSTOMERS_GROUPS." cg,". TABLE_CUSTOMERS ." c WHERE cg.customers_group_id = c.customers_group_id AND c.customers_id = ".$_SESSION['customer_id']);
      $gtemplate = tep_db_fetch_array($group_template_query);
      $thema_template = $gtemplate['group_template'];
    }
    
    unset($customer_template_query, $ctemplate);
  }
}

// check to see if there is an affiliate template set, if there is no customer override
if ($thema_template == '' && isset($_SESSION['affiliate_ref'])) {
  $affiliate_query = tep_db_query("SELECT affiliate_template
                                            FROM " . TABLE_AFFILIATE . "
                                            WHERE affiliate_id = " . $_SESSION['affiliate_ref']);
  $affiliate_data = tep_db_fetch_array($affiliate_query);
  $thema_template = $affiliate_data['affiliate_template'];
  unset($affiliate_query, $affiliate_data);
}

// check to selected or default template is actually on the filesystem
if ($thema_template != '') {
  define('TEMPLATE_NAME', $thema_template);
} else {
  define('TEMPLATE_NAME', DEFAULT_TEMPLATE);
}

if ( ! file_exists(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/main_page.tpl.php')) {
  die('<strong style="color:#f00;">Template Error : Error with "' . TEMPLATE_NAME . '" Template</strong>');
}


// there appears to be a valid template, so read the information from the table
$template_query = tep_db_query("SELECT *
                                FROM " . TABLE_TEMPLATE . "
                                WHERE template_name = '" . TEMPLATE_NAME . "'");
$template = tep_db_fetch_array($template_query);

define('TEMPLATE_ID', $template['template_id']);
define('CELLPADDING_MAIN', $template['template_cellpadding_main']);
define('CELLPADDING_LEFT', $template['template_cellpadding_left']);
define('CELLPADDING_RIGHT', $template['template_cellpadding_right']);
define('CELLPADDING_SUB', $template['template_cellpadding_sub']);

//Over Rideing the Settings based on Forced Login Settings
if ( ! isset($_SESSION['customer_id']) && B2B_REQUIRE_LOGIN == 'true') {
  define('DISPLAY_COLUMN_LEFT', 'No');
  define('DISPLAY_COLUMN_RIGHT', 'No');
} else {
  define('DISPLAY_COLUMN_LEFT', $template['include_column_left']);
  define('DISPLAY_COLUMN_RIGHT', $template['include_column_right']);
}

define('SITE_WIDTH', $template['site_width']);
define('BOX_WIDTH_LEFT', $template['box_width_left']);
define('BOX_WIDTH_RIGHT', $template['box_width_right']);
define('SIDE_BOX_LEFT_WIDTH', $template['side_box_left_width']);
define('SIDE_BOX_RIGHT_WIDTH', $template['side_box_right_width']);
define('MAIN_TABLE_BORDER', $template['main_table_border']);
define('SHOW_HEADER_LINK_BUTTONS', $template['show_header_link_buttons']);
define('SHOW_CART_IN_HEADER', $template['cart_in_header']);
define('SHOW_LANGUAGES_IN_HEADER', $template['languages_in_header']);
define('SHOW_HEADING_TITLE_ORIGINAL', $template['show_heading_title_original']);
define('INCLUDE_MODULE_ONE', $template['module_one']);
define('INCLUDE_MODULE_TWO', $template['module_two']);
define('INCLUDE_MODULE_THREE', $template['module_three']);
define('INCLUDE_MODULE_FOUR', $template['module_four']);
define('INCLUDE_MODULE_FIVE', $template['module_five']);
define('INCLUDE_MODULE_SIX', $template['module_six']);
define('SHOW_CUSTOMER_GREETING', $template['customer_greeting']);


// find out of this is a ATS or BTS template
  if (file_exists(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/template.php')) {
  require DIR_FS_TEMPLATES . TEMPLATE_NAME . '/template.php';
} else {
}
// this code is added to set to default any values not set by the template.php file
// this also removes the ATS_template_application_top.php and CRE_template_application_top.php files

// if the style sheet is not defined, add it
if ( ! defined('TEMPLATE_STYLE') && file_exists(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/stylesheet.css')) {
  define('TEMPLATE_STYLE', DIR_WS_TEMPLATES . TEMPLATE_NAME . "/stylesheet.css");
}

if ( ! defined('DIR_FS_TEMPLATE_BOXES')) {
  if (file_exists(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/')) {
    define('DIR_FS_TEMPLATE_BOXES', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/');
  } else {
    define('DIR_FS_TEMPLATE_BOXES', DIR_WS_TEMPLATES . 'default/boxes/');
  }
}
// needed to provide an alternative location
define('DIR_FS_TEMPLATE_DEFAULT_BOXES', DIR_WS_TEMPLATES . 'default/boxes/');

if ( ! defined('DIR_FS_TEMPLATE_MAINPAGES') ) {
  if (file_exists(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/')) {
    define('DIR_FS_TEMPLATE_MAINPAGES', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/');
  } else {
    define('DIR_FS_TEMPLATE_MAINPAGES', DIR_WS_TEMPLATES . 'default/mainpage_modules/');
  }
}
// this is to provide backward compatability
$modules_folder = DIR_FS_TEMPLATE_MAINPAGES;

if ( ! defined('DIR_WS_TEMPLATE_IMAGES') ) {
  if (file_exists(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/')) {
    define('DIR_WS_TEMPLATE_IMAGES', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/');
  } else {
    define('DIR_WS_TEMPLATE_IMAGES', DIR_WS_IMAGES);
  }
}

if ( ! defined('TEMPLATE_BOX_TPL') ) {
  if (file_exists(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes.tpl.php')) {
    define('TEMPLATE_BOX_TPL', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes.tpl.php');
  } else {
    define('TEMPLATE_BOX_TPL', DIR_WS_TEMPLATES . 'default/boxes.tpl.php');
  }
}

if ( ! defined('TEMPLATE_HTML_OUT') ) {
  if (file_exists(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/extra_html_output.php')) {
    define('TEMPLATE_HTML_OUT', DIR_WS_TEMPLATES . TEMPLATE_NAME . '/extra_html_output.php');
  } else {
    define('TEMPLATE_HTML_OUT', DIR_WS_TEMPLATES . 'default/extra_html_output.php' );
  }
}

if ( ! defined('TEMPLATE_TABLE_BORDER') ) {
  define('TEMPLATE_TABLE_BORDER', '0');
}
if ( ! defined('TEMPLATE_TABLE_WIDTH') ) {
  define('TEMPLATE_TABLE_WIDTH', '100%');
}
if ( ! defined('TEMPLATE_TABLE_CELLSPACING') ) {
  define('TEMPLATE_TABLE_CELLSPACING', '0');
}
if ( ! defined('TEMPLATE_TABLE_CELLPADDIING') ) {
  define('TEMPLATE_TABLE_CELLPADDIING', '0');
}
if ( ! defined('TEMPLATE_TABLE_PARAMETERS') ) {
  define('TEMPLATE_TABLE_PARAMETERS', '');
}
if ( ! defined('TEMPLATE_TABLE_ROW_PARAMETERS') ) {
  define('TEMPLATE_TABLE_ROW_PARAMETERS', '');
}
if ( ! defined('TEMPLATE_TABLE_DATA_PARAMETERS') ) {
  define('TEMPLATE_TABLE_DATA_PARAMETERS', '');
}
if ( ! defined('TEMPLATE_TABLE_CONTENT_CELLPADING') ) {
  define('TEMPLATE_TABLE_CONTENT_CELLPADING', '6');
}
if ( ! defined('TEMPLATE_TABLE_CENTER_CONTENT_CELLPADING') ) {
  define('TEMPLATE_TABLE_CENTER_CONTENT_CELLPADING', '4');
}

if ( ! defined('TEMPLATE_INCLUDE_FOOTER') ) {
  define('TEMPLATE_INCLUDE_FOOTER', 'true');
}
if ( ! defined('TEMPLATE_BOX_IMAGE_FOOTER_LEFT') ) {
  define('TEMPLATE_BOX_IMAGE_FOOTER_LEFT', 'true');
}
if ( ! defined('TEMPLATE_BOX_IMAGE_FOOTER_RIGHT') ) {
  define('TEMPLATE_BOX_IMAGE_FOOTER_RIGHT', 'true');
}
  
if ( ! defined('TEMPLATE_INFOBOX_TOP_LEFT') ) {
  define('TEMPLATE_INFOBOX_TOP_LEFT', 'true');
}
if ( ! defined('TEMPLATE_INFOBOX_TOP_RIGHT') ) {
  define('TEMPLATE_INFOBOX_TOP_RIGHT', 'true');
}
  
if ( ! defined('TEMPLATE_INFOBOX_BORDER_LEFT') ) {
  define('TEMPLATE_INFOBOX_BORDER_LEFT', 'true');
}
if ( ! defined('TEMPLATE_INFOBOX_BORDER_RIGHT') ) {
  define('TEMPLATE_INFOBOX_BORDER_RIGHT', 'true');
}
if ( ! defined('TEMPLATE_INFOBOX_BORDER_IMAGE_LEFT') ) {
  define('TEMPLATE_INFOBOX_BORDER_IMAGE_LEFT', '');
}
if ( ! defined('TEMPLATE_INFOBOX_BORDER_IMAGE_RIGHT') ) {
  define('TEMPLATE_INFOBOX_BORDER_IMAGE_RIGHT', '');
}

if ( ! defined('TEMPLATE_INFOBOX_IMAGE_TOP_LEFT') ) {
  define('TEMPLATE_INFOBOX_IMAGE_TOP_LEFT', 'infobox_top_left.png');
}
if ( ! defined('TEMPLATE_INFOBOX_IMAGE_TOP_RIGHT') ) {
  define('TEMPLATE_INFOBOX_IMAGE_TOP_RIGHT', 'infobox_top_right.png');
}
if ( ! defined('TEMPLATE_INFOBOX_IMAGE_TOP_RIGHT_ARROW') ) {
  define('TEMPLATE_INFOBOX_IMAGE_TOP_RIGHT_ARROW', 'infobox_top_right_arrow.png');
}
  
if ( ! defined('TEMPLATE_INFOBOX_IMAGE_FOOTER_LEFT') ) {
  define('TEMPLATE_INFOBOX_IMAGE_FOOTER_LEFT', 'infobox_footer_left.png');
}
if ( ! defined('TEMPLATE_INFOBOX_IMAGE_FOOTER_RIGHT') ) {
  define('TEMPLATE_INFOBOX_IMAGE_FOOTER_RIGHT', 'infobox_footer_right.png');
}

if ( ! defined('TEMPLATE_CONTENT_TABLE_WIDTH') ) {
  define('TEMPLATE_CONTENT_TABLE_WIDTH','100%');
}
if ( ! defined('TEMPLATE_CONTENT_TABLE_CELLPADDIING') ) {
  define('TEMPLATE_CONTENT_TABLE_CELLPADDIING','0');
}
if ( ! defined('TEMPLATE_CONTENT_TABLE_CELLSPACING') ) {
  define('TEMPLATE_CONTENT_TABLE_CELLSPACING','0');
}
  
if ( ! defined('TEMPLATE_INCLUDE_CONTENT_FOOTER') ) {
  define('TEMPLATE_INCLUDE_CONTENT_FOOTER','true');
}
  
if ( ! defined('TEMPLATE_CONTENTBOX_TOP_LEFT') ) {
  define('TEMPLATE_CONTENTBOX_TOP_LEFT','true');
}
if ( ! defined('TEMPLATE_CONTENTBOX_TOP_RIGHT') ) {
  define('TEMPLATE_CONTENTBOX_TOP_RIGHT','true');
}
  
if ( ! defined('TEMPLATE_CONTENTBOX_FOOTER_LEFT') ) {
  define('TEMPLATE_CONTENTBOX_FOOTER_LEFT','true');
}
if ( ! defined('TEMPLATE_CONTENTBOX_FOOTER_RIGHT') ) {
  define('TEMPLATE_CONTENTBOX_FOOTER_RIGHT','true');
}
  
if ( ! defined('TEMPLATE_CONTENTBOX_IMAGE_TOP_LEFT') ) {
  define('TEMPLATE_CONTENTBOX_IMAGE_TOP_LEFT', 'content_top_left.png');
}
if ( ! defined('TEMPLATE_CONTENTBOX_IMAGE_TOP_RIGHT') ) {
  define('TEMPLATE_CONTENTBOX_IMAGE_TOP_RIGHT', 'content_top_right.png');
}
if ( ! defined('TEMPLATE_CONTENTBOX_IMAGE_TOP_RIGHT_ARROW') ) {
  define('TEMPLATE_CONTENTBOX_IMAGE_TOP_RIGHT_ARROW','content_top_rightarrow.png');
}

if ( ! defined('TEMPLATE_CONTENTBOX_IMAGE_FOOT_LEFT') ) {
  define('TEMPLATE_CONTENTBOX_IMAGE_FOOT_LEFT','content_footer_left.png');
}
if ( ! defined('TEMPLATE_CONTENTBOX_IMAGE_FOOT_RIGHT') ) {
  define('TEMPLATE_CONTENTBOX_IMAGE_FOOT_RIGHT','content_footer_right.png');
}

if ( ! defined('TEMPLATE_BUTTONS_USE_CSS') ) {
  define('TEMPLATE_BUTTONS_USE_CSS','false');
}

if ( ! defined('TEMPLATE_FS_CUSTOM_INCLUDES') ) {
  if (file_exists(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/includes/')) {
    define('TEMPLATE_FS_CUSTOM_INCLUDES',DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/includes/');
  } else {
    define('TEMPLATE_FS_CUSTOM_INCLUDES',DIR_FS_CATALOG . DIR_WS_INCLUDES);
  }
}

if ( ! defined('TEMPLATE_FS_CUSTOM_MODULES') ) {
  if (file_exists(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/includes/modules/')) {
    define('TEMPLATE_FS_CUSTOM_MODULES',DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/includes/modules/');
  } elseif (file_exists(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/modules/')) {
    define('TEMPLATE_FS_CUSTOM_MODULES',DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/modules/');
  } else {
    define('TEMPLATE_FS_CUSTOM_MODULES',DIR_FS_CATALOG . DIR_WS_TEMPLATES . 'default/modules/');
  }
}
// this is to provide backward compatability
$additional_module_folder = TEMPLATE_FS_CUSTOM_MODULES;

// load the needed files
// Check if the optiona extra_html_output.php file is present
if ( file_exists(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/extra_html_output.php')) {
  require DIR_FS_TEMPLATES . TEMPLATE_NAME . '/extra_html_output.php';
}
// include the default version in case some function was not redefined
require DIR_FS_TEMPLATES . 'default/extra_html_output.php';

// Check if the optiona boxes.tpl.php file is present, if not, use the default
if ( file_exists(DIR_FS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes.tpl.php')) {
  require DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes.tpl.php';
} else {
  require DIR_FS_TEMPLATES . 'default/boxes.tpl.php';
}

// need3ed fro compatability
if ( ! function_exists('tep_image_infobox')) {
  function tep_image_infobox($corner, $alt = '', $width = '', $height = '', $params = '') {
    $image = '<img src="' . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infobox/' . $corner . '" border="0" alt="' . $alt . '"';
    if ($alt) $image .= ' title=" ' . $alt . ' "';
    if ($width) $image .= ' width="' . $width . '"';
    if ($height) $image .= ' height="' . $height . '"';
    if ($params) $image .= ' ' . $params;
    $image .= '>';
    
    return $image;
  }
}


//branding manager added as part of ATS 1.1
function cre_site_branding($show = '') {
  global $languages_id;    
    
  $store_info = '';
    
  $site_branding_query = tep_db_query("SELECT *
                                       FROM " . TABLE_BRANDING_DESCRIPTION . "
                                       WHERE language_id = " . $languages_id);
  $site_brand_info = tep_db_fetch_array($site_branding_query);
  
  $affiliate_branding = array();
  if (isset($_SESSION['affiliate_ref'])) {
    $affiliate_branding_query = tep_db_query("SELECT affiliate_template, affiliate_cobrand_image, affiliate_cobrand_name, affiliate_cobrand_slogan, affiliate_cobrand_url, affiliate_cobrand_support_email, affiliate_cobrand_support_phone
                                              FROM " . TABLE_AFFILIATE . "
                                              WHERE affiliate_id = " . $_SESSION['affiliate_ref']);
    $affiliate_branding = tep_db_fetch_array($affiliate_branding_query);
  }
  
  if (tep_not_null($affiliate_branding['affiliate_cobrand_url'])) {
    $brand_url = $affiliate_branding['affiliate_cobrand_url'];
  } elseif (tep_not_null($site_brand_info['store_brand_homepage'])) {
    $brand_url = $site_brand_info['store_brand_homepage'];
  } else {
    $brand_url = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
  }
  
  switch($show){
    case 'storeurl':
      $store_info = '<a href="' . $brand_url . '">' . str_replace('http://','',$brand_url) . '</a>';
      break;

    case 'phone':
      if (tep_not_null($affiliate_branding['affiliate_cobrand_support_phone'])) {
        $store_info = $affiliate_branding['affiliate_cobrand_support_phone'];
      } elseif (tep_not_null($site_brand_info['store_brand_support_phone'])) {
        $store_info = $site_brand_info['store_brand_support_phone'];
      }
      break;

    case 'email':
      if (tep_not_null($affiliate_branding['affiliate_cobrand_support_email'])) {
        $branding_mailto = $affiliate_branding['affiliate_cobrand_support_email'];
      } elseif (tep_not_null($site_brand_info['store_brand_support_email'])) {
        $branding_mailto = $site_brand_info['store_brand_support_email'];
      } else {
        $branding_mailto = STORE_OWNER_EMAIL_ADDRESS;
      }
      $branding_mailto = str_replace('@','&#64;',$branding_mailto); //let's fight spam. Not strong as javascript, but works...!
      $store_info = '<a href="mailto&#x3A;' . $branding_mailto . '">' . $branding_mailto . '</a>';
      break;

    case 'logo':
      if (tep_not_null($affiliate_branding['affiliate_cobrand_image']) && file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . 'affiliate_cobrand/' . $affiliate_branding['affiliate_cobrand_image'])) {
        $store_info = '<a href="'. $brand_url . '">' . tep_image(DIR_WS_IMAGES . 'affiliate_cobrand/' . $affiliate_branding['affiliate_cobrand_image']) . '</a>';
      } elseif (tep_not_null($affiliate_branding['affiliate_cobrand_name']))  {
        $store_info = '<a href="'. $brand_url . '" class="branding_name">' . $affiliate_branding['affiliate_cobrand_name'] . '</a>';
      } elseif (tep_not_null($site_brand_info['store_brand_image']) && file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . 'logo/' . $site_brand_info['store_brand_image'])) {
        $store_info = '<a href="' . $brand_url . '">' . tep_image(DIR_WS_IMAGES . 'logo/' . $site_brand_info['store_brand_image']) . '</a>';
      } elseif (tep_not_null($site_brand_info['store_brand_name'])) {
        $store_info = '<a class="branding_name" href="' . $brand_url . '">' . $site_brand_info['store_brand_name'] . '</a>';
      } else {
        $store_info = '<a class="branding_name" href="' . $brand_url . '">' . STORE_NAME .'</a>';
      }
      break;

    case 'slogan':
      if (tep_not_null($affiliate_branding['affiliate_cobrand_slogan'])) {
        $store_info = '<span class="branding_slogan">' . $affiliate_branding['affiliate_cobrand_slogan'] . '</span>';
      } elseif (tep_not_null($site_brand_info['store_brand_slogan']) ) {
        $store_info = '<span class="branding_slogan">' . $site_brand_info['store_brand_slogan'] . '</span>';
      }
      break;

    default:
      $store_info = '<a class="store_name" href="' . tep_href_link(FILENAME_DEFAULT) . '">' . STORE_NAME .'</a>';
      break;
        
  }//end switch
  return $store_info;
 }
?>