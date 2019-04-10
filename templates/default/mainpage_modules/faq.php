<?php
/*
  $Id: faq.php,v 1.1.1.1 2008/07/30 23:41:14 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

echo '<!--faq mainpage //-->' . "\n";
if(!defined('MAX_DISPLAY_MODULES_FAQ_CATEGORY')) { define('MAX_DISPLAY_MODULES_FAQ_CATEGORY','3');}
if(!defined('MAINPAGE_FAQ_SHOW_CATEGORY_FAQ')) { define('MAINPAGE_FAQ_SHOW_CATEGORY_FAQ','true');}
if(!defined('MAINPAGE_FAQ_NUM_FAQ')) { define('MAINPAGE_FAQ_NUM_FAQ','3');}

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FAQ);
  
  if(!isset($_SESSION['sppc_customer_group_id'])) {
  $customer_group_id = 'G';
  } else {
   $customer_group_id = $_SESSION['sppc_customer_group_id'];
  }
  
  $faq_categories_query = tep_db_query("select ic.categories_id, icd.categories_name 
                                        from " . TABLE_FAQ_CATEGORIES . " ic, 
                                        " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd 
                                        where icd.categories_id = ic.categories_id         
                                        and icd.language_id = '" . (int)$languages_id . "' 
                                        and ic.products_group_access like '%". $customer_group_id."%'
                                        and ic.categories_status = '1' 
                                        order by rand()  ");
  
  $faq_categories_check = tep_db_num_rows($faq_categories_query);
    if ($faq_categories_query > 0){
      
  $info_box_contents = array();
  $info_box_contents[] = array('text' => TABLE_HEADING_NEW_FAQ);
  new contentBoxHeading($info_box_contents, tep_href_link(FILENAME_FAQ));
  
  $info_box_contents = array(); 
  $row = 0;
  $count = 0;
  while ($faq_categories = tep_db_fetch_array($faq_categories_query)) {    
      
      if(MAINPAGE_FAQ_SHOW_CATEGORY_FAQ == 'true'){
          $faq_query = tep_db_query("select ip.faq_id, ip.question 
                                    from " . TABLE_FAQ . " ip, 
                                    " . TABLE_FAQ_TO_CATEGORIES . " ip2c 
                                    where ip.faq_id = ip2c.faq_id 
                                    and ip2c.categories_id = '" . $faq_categories['categories_id'] . "' 
                                    and ip.products_group_access like '%". $customer_group_id."%'
                                    and ip.language = '" . $language . "' 
                                    and ip.visible = '1' ");
          $faq_str = '';
          $faq_num = 0;    
           while ($faq = tep_db_fetch_array($faq_query)) {
               $faq_str .= '&nbsp; &nbsp; &raquo; <a href="' . tep_href_link(FILENAME_FAQ, 'cID=' . (int)$faq_categories['categories_id']) . '#' . $faq['faq_id'] . '">' . cre_clean_html($faq['question']) . '</a><br>';
               $faq_num++;
               if($faq_num == MAINPAGE_FAQ_NUM_FAQ){
                   break;
               }
           }   
      }  
  
          $info_box_contents[$row][0] = array('align' => 'left',
                                           'params' => 'class="main" valign="top"',
                                           'text' => '<a href="' . tep_href_link(FILENAME_FAQ, 'cID=' . (int)$faq_categories['categories_id']) . '"><strong>' . $faq_categories['categories_name'] . '</strong></a><br>' . $faq_str);
  
    $count++;
    $row ++;
    if ($count == MAX_DISPLAY_MODULES_FAQ_CATEGORY) {
      break;
    }
 } 

  new contentBox($info_box_contents, true, true);
  if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){ 
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
  new contentBoxFooter($info_box_contents);
  }
  } //check eof
?>

<!--faq mainpage //-->