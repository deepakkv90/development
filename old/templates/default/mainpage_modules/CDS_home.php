<?php
/*
  $Id: cre_cds_home.php,v 1.0 2008/01/31 11:21:11 wa4u Exp $

  CRE Loaded, Commercial Open Source E-Commerce
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

/*
//SQL configurations
//Use below queries if you are adding this module seperately.
INSERT INTO `configuration` VALUES ('', 'Home Page Module: Show Number of Items', 'CDS_HOMEPAGE_NUMBER_ITEMS', '3', 'Home Page Module show number of items (default is 3).', 480, 22, now(), now(), NULL, NULL);
INSERT INTO `configuration` VALUES ('', 'Home Page Module: Rotate Items', 'CDS_HOMEPAGE_ROTATE', 'true', 'Home Page Module rotate items on home page.', 480, 23, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO `configuration` VALUES ('', 'Home Page Module: Show From This Category', 'CDS_HOMEPAGE_USE_CATEGORY', '0', 'Home Page Module show from this category.', 480, 24, now(), now(), NULL, NULL);
INSERT INTO `configuration` VALUES ('', 'Home Page Module: Show Title', 'CDS_HOMEPAGE_SHOW_TITLE', 'true', 'Home Page Module show item title.', 480, 25, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO `configuration` VALUES ('', 'Home Page Module: Show Blurb', 'CDS_HOMEPAGE_SHOW_BLURB', 'false', 'Home Page Module show item blurb.', 480, 26, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO `configuration` VALUES ('', 'Home Page Module: Show Body', 'CDS_HOMEPAGE_SHOW_BODY', 'true', 'Home Page Module show item body.', 480, 27, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO `configuration` VALUES ('', 'Home Page Module: Show Thumb', 'CDS_HOMEPAGE_SHOW_THUMB', 'true', 'Home Page Module show item thumb.', 480, 28, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO `configuration` VALUES ('', 'Home Page Module: Body Text Count', 'CDS_HOMEPAGE_BODY_COUNT', '200', 'Home Page Module set number of charecters displayed for item body text.', 480, 29, now(), now(), NULL, NULL);
*/

//some configuration checks, if not loaded in admin >> configuration load some defaults and keep module working.
if(!defined('CDS_HOMEPAGE_NUMBER_ITEMS')) { define('CDS_HOMEPAGE_NUMBER_ITEMS','3');}
if(!defined('CDS_HOMEPAGE_ROTATE')) { define('CDS_HOMEPAGE_ROTATE', 'true');}
if(!defined('CDS_HOMEPAGE_USE_CATEGORY')) { define('CDS_HOMEPAGE_USE_CATEGORY','0');}
if(!defined('CDS_HOMEPAGE_SHOW_TITLE')) { define('CDS_HOMEPAGE_SHOW_TITLE','true');}
if(!defined('CDS_HOMEPAGE_SHOW_BLURB')){define('CDS_HOMEPAGE_SHOW_BLURB','false');}
if(!defined('CDS_HOMEPAGE_SHOW_BODY')) { define('CDS_HOMEPAGE_SHOW_BODY','true');}
if(!defined('CDS_HOMEPAGE_SHOW_THUMB')){define('CDS_HOMEPAGE_SHOW_THUMB','false');}
if(!defined('CDS_HOMEPAGE_BODY_COUNT')){define('CDS_HOMEPAGE_BODY_COUNT','200');}

//clean html and extract only text
  if(!function_exists('cre_clean_html')){
     function cre_clean_html($html){
         $search = array('@<script[^>]*?>.*?</script>@si', 
               '@<[\/\!]*?[^<>]*?>@si',
               '@<style[^>]*?>.*?</style>@siU',
               '@<![\s\S]*?--[ \t\n\r]*>@'
               );
         return preg_replace($search, '', $html);
}
}

require_once(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CDS_INDEX);
require_once(DIR_WS_FUNCTIONS . FILENAME_CDS_FUNCTIONS);


  $cds_home_sql .= "SELECT distinct(p.pages_id) FROM
                  " . TABLE_CDS_PAGES . " p,
                  " . TABLE_CDS_PAGES_DESCRIPTION . "  pd,
                  " . TABLE_CDS_PAGES_TO_CATEGORIES . " p2c
                  WHERE
                  p.pages_status = '1'
                  AND pd.language_id = '" . (int)$languages_id . "'
                  AND p.pages_id = pd.pages_id";

  if(defined('CDS_HOMEPAGE_USE_CATEGORY') && CDS_HOMEPAGE_USE_CATEGORY !='0'){
  $cds_home_sql .= " AND p2c.categories_id ='" . CDS_HOMEPAGE_USE_CATEGORY . "' AND p.pages_id = p2c.pages_id";
  }

  if(defined('CDS_HOMEPAGE_ROTATE') && CDS_HOMEPAGE_ROTATE == 'true'){
  $cds_home_sql .= " order by rand() " ;
  }
  
  $cds_home_sql .= " LIMIT " . CDS_HOMEPAGE_NUMBER_ITEMS;
                           
  $cds_listing_query = tep_db_query($cds_home_sql);
  $cds_listing_check = tep_db_num_rows($cds_listing_query);

  if ($cds_listing_check > 0) { 
      
  echo '<!-- CRE_CDS_HOMEPAGE_MODULE BOF -->' . "\n";
  $info_box_contents = array();
  $info_box_contents[] = array('text' => 'News and Information');

  new contentBoxHeading($info_box_contents);
  $cds_homepage_id = '';
  $cds_homepage_title = '';
  $cds_homepage_blurb = '';
  $cds_homepage_body = '';
  $cds_homepage_image = '';
  $row = 0;
  $info_box_contents = array();

   while ($cds_listing_result = tep_db_fetch_array($cds_listing_query)) {
  $cds_homepage_id = $cds_listing_result['pages_id'];
  if(defined('CDS_HOMEPAGE_SHOW_TITLE') && CDS_HOMEPAGE_SHOW_TITLE == 'true') $cds_homepage_title = '<a href="' . tep_href_link(FILENAME_PAGES, 'pID=' . $cds_homepage_id . '&amp;CDpath=' . cre_get_cds_page_path($cds_homepage_id)) . '"><span class="cds_home_title">' . htmlspecialchars(cre_get_page_title($cds_homepage_id)) . '</span></a><br>';
  if(defined('CDS_HOMEPAGE_SHOW_BLURB') && CDS_HOMEPAGE_SHOW_BLURB == 'true') $cds_homepage_blurb = '<span class="cds_home_blurb">' . cre_clean_html(cre_get_page_blurb($cds_homepage_id)) . '</span><br>';
  if(defined('CDS_HOMEPAGE_SHOW_THUMB') && CDS_HOMEPAGE_SHOW_THUMB == 'true') $cds_homepage_image = '<a href="' . tep_href_link(FILENAME_PAGES, 'pID=' . $cds_homepage_id . '&amp;CDpath=' . cre_get_cds_page_path($cds_homepage_id)) . '">' . cre_get_page_thumbnail($cds_homepage_id) . '</a>';

  if(defined('CDS_HOMEPAGE_SHOW_BODY') && CDS_HOMEPAGE_SHOW_BODY == 'true') {
  $txt_only = trim(cre_clean_html(cre_get_page_body($cds_homepage_id)));
  if (strlen($txt_only) <= CDS_HOMEPAGE_BODY_COUNT) {
  $txt_to_use = $txt_only;
  } else {
  if (substr($txt_only, CDS_HOMEPAGE_BODY_COUNT, 1) == ' ' || substr($txt_only, CDS_HOMEPAGE_BODY_COUNT, 1) == '.') {
  // if the next character is a space, no agjustments needed
     $txt_to_use = substr($txt_only, 0, CDS_HOMEPAGE_BODY_COUNT);
     } else {
    $txt_short = substr($txt_only, 0, CDS_HOMEPAGE_BODY_COUNT);
    // now back up to the last space
    $pos = strrpos($txt_short, " ");
    $txt_to_use = substr($txt_short, 0, $pos);
      }
    }
  $cds_homepage_body = '<span class="cds_home_body">' . $txt_to_use . ' <a href="' . tep_href_link(FILENAME_PAGES, 'pID=' . $cds_homepage_id . '&amp;CDpath=' . cre_get_cds_page_path($cds_homepage_id)) . '"><span class="cds_home_readmore">...Read More</span></a></span>';
  }
    $info_box_contents[$row][0] = array('align' => 'left',
                                           'params' => 'class="main" valign="top"',
                                           'text' => '<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" class="cds_home_image">' . $cds_homepage_image . '</td>
    <td valign="top">' . $cds_homepage_title . $cds_homepage_blurb . $cds_homepage_body . '</td>
  </tr>
  <tr>
    <td colspan="2" valign="top" class="cds_home_spacer"></td>
  </tr>
</table>' );

    $row ++;
  }

  new contentBox($info_box_contents, true, true);
if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){ 
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
  new contentBoxFooter($info_box_contents);
  }
  echo '<!-- CRE_CDS_HOMEPAGE_MODULE EOF -->' . "\n";
 }
?>