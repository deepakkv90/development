<?php
/*
  $Id: article_reviews.php, v1.0 2003/12/04 12:00:00 ra Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $article_info_query = tep_db_query("select a.articles_id, ad.articles_name from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_DESCRIPTION . " ad where a.articles_id = '" . (int)$_GET['articles_id'] . "' and a.articles_status = '1' and a.articles_id = ad.articles_id and ad.language_id = '" . (int)$languages_id . "'");
  if (!tep_db_num_rows($article_info_query)) {
    tep_redirect(tep_href_link(FILENAME_REVIEWS));
  } else {
    $article_info = tep_db_fetch_array($article_info_query);
  }

  $articles_name = $article_info['articles_name'];

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ARTICLE_REVIEWS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ARTICLE_REVIEWS, tep_get_all_get_params()));

  $content = CONTENT_ARTICLE_REVIEWS;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);  
  require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
