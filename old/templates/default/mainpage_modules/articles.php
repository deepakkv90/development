<?php
/*
  $Id: articles.php,v 1.1.1.1 2004/03/04 23:41:14 ccwjr Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- mainpages_modules.articles.php//-->
<?php
if(!defined('NEW_ARTICLES_HOMEPAGE_NUMBER_ITEMS')){define('NEW_ARTICLES_HOMEPAGE_NUMBER_ITEMS','3');}
  $new_articles_query = tep_db_query("select a.articles_id, 
                                                a.articles_date_added, 
                                                ad.articles_name, 
                                                ad.articles_head_desc_tag, 
                                                au.authors_id, 
                                                au.authors_name, 
                                                td.topics_id, 
                                                td.topics_name 
                                                from " . TABLE_ARTICLES . " a, 
                                                " . TABLE_AUTHORS . " au, 
                                                " . TABLE_ARTICLES_DESCRIPTION . " ad, 
                                                " . TABLE_ARTICLES_TO_TOPICS . " a2t 
                                                left join " . TABLE_TOPICS_DESCRIPTION . " td on(a2t.topics_id = td.topics_id and td.language_id = '" . (int)$languages_id . "') 
                                                where a.articles_status = '1' 
                                                and (a.articles_date_available IS NULL or to_days(a.articles_date_available) <= to_days(now())) 
                                                and a.authors_id = au.authors_id 
                                                and a.articles_id = a2t.articles_id 
                                                and a.articles_id = ad.articles_id 
                                                and ad.language_id = '" . (int)$languages_id . "' 
                                                and a.articles_date_added > SUBDATE(now(), INTERVAL '" . NEW_ARTICLES_DAYS_DISPLAY . "' DAY) 
                                                order by a.articles_date_added desc, ad.articles_name");
  
  $new_articles_check = tep_db_num_rows($new_articles_query);
  if ($new_articles_check > 0){
      
  include_once( DIR_WS_LANGUAGES . $language . '/' . FILENAME_ARTICLES_NEW);
  $info_box_contents = array();
  $info_box_contents[] = array('text' => sprintf(TABLE_HEADING_NEW_ARTICLES, strftime('%B')));
  new contentBoxHeading($info_box_contents, tep_href_link(FILENAME_ARTICLES_NEW));

  $row = 0;
  $count = 0;
  $info_box_contents = array();
  while ($new_articles = tep_db_fetch_array($new_articles_query)) {
    $article_abstract = '';
    $article_author = '';
    $article_publish = '';
    if (DISPLAY_ABSTRACT_ARTICLE_LISTING == 'true') {
      $article_abstract =  substr(cre_clean_html($new_articles['articles_head_desc_tag']), 0, MAX_ARTICLE_ABSTRACT_LENGTH) . '...<br>';
    }
    if (DISPLAY_AUTHOR_ARTICLE_LISTING == 'true') {
      $article_author = TEXT_BY . '<a href="' . tep_href_link(FILENAME_ARTICLES, 'authors_id=' . $new_articles['authors_id']) . '">' . $new_articles['authors_name'] . '</a><br>';
    }
    if (DISPLAY_DATE_ADDED_ARTICLE_LISTING == 'true') {
      $article_publish = TEXT_DATE_ADDED . $new_articles['articles_date_added'] . '<br>';
    }    
    $info_box_contents[$row][0] = array('align' => 'left',
                                           'params' => 'class="main" valign="top"',
                                           'text' => '<a href="' . tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $new_articles['articles_id']) . '"><span class="pro_list">' . $new_articles['articles_name'] . '</span></a><br>' . $article_author . $article_abstract . $article_publish);
                                           
    if ($new_articles['topics_id'] != '' && DISPLAY_TOPIC_ARTICLE_LISTING == 'true') {
      $info_box_contents[$row][1] = array('align' => 'left',
                                           'params' => 'class="main" valign="top"',
                                           'text' => TEXT_TOPIC . '<a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $new_articles['topics_id']) . '">' . $new_articles['topics_name'] . '</a><br>');
    }
                                           
    $count++;
    $row ++;
    if ($count == NEW_ARTICLES_HOMEPAGE_NUMBER_ITEMS) {
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
  }// check articles
?>
<!-- articles_eof //-->
