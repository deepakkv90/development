<?php
/*
$Id: languages.php,v 1.1.1.1 2004/03/04 23:38:39 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

require('includes/application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');

if (tep_not_null($action)) {
  switch ($action) {
    case 'insert':
      $name = tep_db_prepare_input($_POST['name']);
      $code = tep_db_prepare_input($_POST['code']);
      $image = tep_db_prepare_input($_POST['image']);
      $directory = tep_db_prepare_input($_POST['directory']);
      $sort_order = tep_db_prepare_input($_POST['sort_order']);
      tep_db_query("insert into " . TABLE_LANGUAGES . " (name, code, image, directory, sort_order) values ('" . tep_db_input($name) . "', '" . tep_db_input($code) . "', '" . tep_db_input($image) . "', '" . tep_db_input($directory) . "', '" . tep_db_input($sort_order) . "')");
      $insert_id = tep_db_insert_id();
      // create additional affiliate_news_contents records
      $language_query1 = tep_db_query("select affiliate_news_contents_id, affiliate_news_id, affiliate_news_languages_id, affiliate_news_headlines , affiliate_news_contents  from " . TABLE_AFFILIATE_NEWS_CONTENTS . " where affiliate_news_languages_id = '" . (int)$languages_id . "' ");
      while ($language_array1 = tep_db_fetch_array($language_query1)) {
        tep_db_query("insert into " . TABLE_AFFILIATE_NEWS_CONTENTS . " (affiliate_news_contents_id, affiliate_news_id, affiliate_news_languages_id, affiliate_news_headlines , affiliate_news_contents ) values ('" . (int)$language_array1['affiliate_news_contents_id'] . "', '" . tep_db_input($language_array1['affiliate_news_id']) . "', '" . (int)$insert_id . "', '" . tep_db_input($language_array1['affiliate_news_headlines']) . "', '" . tep_db_input($language_array1['affiliate_news_contents']) . "')");
      }
      // create additional affiliate_payment_status records
      $language_query2 = tep_db_query("select affiliate_payment_status_id, affiliate_language_id, affiliate_payment_status_name from " . TABLE_AFFILIATE_PAYMENT_STATUS . " where affiliate_language_id = '" . (int)$languages_id . "'");
      while ($language_array2 = tep_db_fetch_array($language_query2)) {
        tep_db_query("insert into " . TABLE_AFFILIATE_PAYMENT_STATUS . " (affiliate_payment_status_id, affiliate_language_id, affiliate_payment_status_name) values ('" . (int)$language_array2['affiliate_payment_status_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($language_array2['affiliate_payment_status_name']) . "')");
      }
      // create additional article reviews description records
      $language_query = tep_db_query("select reviews_id, reviews_text from " . TABLE_ARTICLE_REVIEWS_DESCRIPTION . " where languages_id = '" . (int)$languages_id . "'");
      while ($language_array = tep_db_fetch_array($language_query)) {
        tep_db_query("insert into " . TABLE_ARTICLE_REVIEWS_DESCRIPTION . " (reviews_id, reviews_text, languages_id) values ('" . (int)$language_array['reviews_id'] . "', '" . tep_db_input($language_array['reviews_text']) . "', '" . (int)$insert_id . "')");
      }
      // create additional articles description records
      $language_query = tep_db_query("select articles_id, articles_name, articles_description, articles_url, articles_head_title_tag, articles_head_desc_tag, articles_head_keywords_tag from " . TABLE_ARTICLES_DESCRIPTION . " where language_id = '" . (int)$languages_id . "'");
      while ($language_array = tep_db_fetch_array($language_query)) {
        tep_db_query("insert into " . TABLE_ARTICLES_DESCRIPTION . " (articles_id, articles_name, articles_description, articles_url, articles_head_title_tag, articles_head_desc_tag, articles_head_keywords_tag, language_id) values ('" . (int)$language_array['articles_id'] . "', '" . tep_db_input($language_array['articles_name']) . "', '" . tep_db_input($language_array['articles_description']) . "', '" . tep_db_input($language_array['articles_url']) . "', '" . tep_db_input($language_array['articles_head_title_tag']) . "', '" . tep_db_input($language_array['articles_head_desc_tag']) . "', '" . tep_db_input($language_array['articles_head_keywords_tag']) . "', '" . (int)$insert_id . "')");
      }
      // create additional authors info records
      $language_query = tep_db_query("select authors_id, authors_description, authors_url from " . TABLE_AUTHORS_INFO . " where languages_id = '" . (int)$languages_id . "'");
      while ($language_array = tep_db_fetch_array($language_query)) {
        tep_db_query("insert into " . TABLE_AUTHORS_INFO . " (authors_id, authors_description, authors_url, languages_id) values ('" . (int)$language_array['reviews_id'] . "', '" . tep_db_input($language_array['authors_description']) . "', '" . tep_db_input($language_array['authors_url']) . "', '" . (int)$insert_id . "')");
      }
      // create additional categories_description records
      $language_query3 = tep_db_query("select  categories_id, language_id, categories_name, categories_heading_title ,categories_description, categories_head_title_tag, categories_head_desc_tag, categories_head_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where language_id = '" . (int)$languages_id . "'");
      while ($language_array3 = tep_db_fetch_array($language_query3)) {
        tep_db_query("insert into " . TABLE_CATEGORIES_DESCRIPTION . " (categories_id, language_id, categories_name, categories_heading_title ,categories_description, categories_head_title_tag, categories_head_desc_tag, categories_head_keywords_tag) values ('" . (int)$language_array3['categories_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($language_array3['categories_name']) . "', '" . tep_db_input($language_array3['categories_heading_title']) . "', '" . tep_db_input($language_array3['categories_description']) . "', '" . tep_db_input($language_array3['categories_head_title_tag']) . "', '" . tep_db_input($language_array3['categories_head_desc_tag']) . "', '" . tep_db_input($language_array3['categories_head_keywords_tag']) . "')");
      }
      // create additional coupon_description records
      $language_query4 = tep_db_query("select coupon_id, language_id, coupon_name, coupon_description from " . TABLE_COUPONS_DESCRIPTION . " where language_id = '" . (int)$languages_id . "'");
      while ($language_array4 = tep_db_fetch_array($language_query4)) {
        tep_db_query("insert into " . TABLE_COUPONS_DESCRIPTION . " (coupon_id, language_id, coupon_name, coupon_description) values ('" . (int)$language_array4['coupon_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($language_array4['coupon_name']) . "', '" . tep_db_input($language_array4['coupon_description']) . "')");
      }
      // create additional events calendar records
      $language_query = tep_db_query("select event_id, start_date, end_date, title, event_image, link, OSC_link, description, date_added from " . TABLE_EVENTS_CALENDAR . " where language_id = '" . (int)$languages_id . "'");
      while ($language_array = tep_db_fetch_array($language_query)) {
        tep_db_query("insert into " . TABLE_EVENTS_CALENDAR . " (event_id, start_date, end_date, title, event_image, link, OSC_link, description, date_added, language_id) values ('" . (int)$language_array['event_id'] . "', '" . $language_array['start_date'] . "', '" . $language_array['end_date'] . "', '" . tep_db_input($language_array['title']) . "', '" . $language_array['event_image'] . "', '" . tep_db_input($language_array['link']) . "', '" . tep_db_input($language_array['OSC_link']) . "', '" . tep_db_input($language_array['description']) . "', '" . $language_array['date_added'] . "', '" . (int)$insert_id . "')");
      }
      // create additional FAQ categories description records
      $language_query = tep_db_query("select categories_id, categories_name, categories_description from " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " where language_id = '" . (int)$languages_id . "'");
      while ($language_array = tep_db_fetch_array($language_query)) {
        tep_db_query("insert into " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " (categories_id, categories_name, categories_description, language_id) values ('" . (int)$language_array['categories_id'] . "', '" . tep_db_input($language_array['categories_name']) . "', '" . tep_db_input($language_array['categories_description']) . "', '" . (int)$insert_id . "')");
      }
      // create additional infobox heading records
      $language_query18 = tep_db_query("select infobox_id, languages_id, box_heading from " . TABLE_INFOBOX_HEADING . " where languages_id = '" . (int)$languages_id . "'");
      while ($language_array18 = tep_db_fetch_array($language_query18)) {
        tep_db_query("insert into " . TABLE_INFOBOX_HEADING . " (infobox_id, languages_id, box_heading) values ('" . (int)$language_array18['infobox_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($language_array18['box_heading']) . "')");
      }
      // create additional link_categories_description records
      $language_query9 = tep_db_query("select link_categories_id, language_id, link_categories_name, link_categories_description from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where language_id = '" . (int)$languages_id . "'");
      while ($language_array9 = tep_db_fetch_array($language_query9)) {
        tep_db_query("insert into " . TABLE_LINK_CATEGORIES_DESCRIPTION . " (link_categories_id, language_id, link_categories_name, link_categories_description) values ('" . (int)$language_array9['link_categories_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($language_array9['link_categories_name']) . "', '" . tep_db_input($language_array9['link_categories_description']) . "')");
      }
      // create additional links records
      $language_query10 = tep_db_query("select links_id, language_id, links_title, links_description from " . TABLE_LINKS_DESCRIPTION . " where language_id = '" . (int)$languages_id . "'");
      while ($language_array10 = tep_db_fetch_array($language_query10)) {
        tep_db_query("insert into " . TABLE_LINKS_DESCRIPTION . " (links_id, language_id, links_title, links_description) values ('" . (int)$language_array10['links_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($language_array10['links_title']) . "', '" . tep_db_input($language_array10['links_description']) . "')");
      }
      // create additional links_status records
      $language_query11 = tep_db_query("select links_status_id, language_id, links_status_name from " . TABLE_LINKS_STATUS . " where language_id = '" . (int)$languages_id . "'");
      while ($language_array11 = tep_db_fetch_array($language_query11)) {
        tep_db_query("insert into " . TABLE_LINKS_STATUS . " (links_status_id, language_id, links_status_name) values ('" . (int)$language_array11['links_status_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($language_array11['links_status_name']) . "')");
      }
      // create additional manufacturers_info records
      $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_url from " . TABLE_MANUFACTURERS_INFO . " where languages_id = '" . (int)$languages_id . "'");
      while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
        tep_db_query("insert into " . TABLE_MANUFACTURERS_INFO . " (manufacturers_id, languages_id, manufacturers_url) values ('" . (int)$manufacturers['manufacturers_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($manufacturers['manufacturers_url']) . "')");
      }
      // create additional orders_pay_methods records
      $language_query12 = tep_db_query("select pay_methods_id, pay_method_language, pay_method_sort, pay_method from " . TABLE_ORDERS_PAY_METHODS . " where pay_method_language = '" . (int)$languages_id . "'");
      while ($language_array12 = tep_db_fetch_array($language_query12)) {
        tep_db_query("insert into " . TABLE_ORDERS_PAY_METHODS . " (pay_methods_id, pay_method_language, pay_method_sort, pay_method) values ('" . (int)$language_array12['pay_methods_id'] . "', '" . (int)$insert_id . "', '" . (int)$language_array12['pay_method_sort'] . "', '" . tep_db_input($language_array12['pay_method']) . "')");
      }
      // create additional orders_ship_method records
      $language_query13 = tep_db_query("select ship_methods_id, ship_method_language, ship_method_sort, ship_method from " . TABLE_ORDERS_SHIP_METHODS . " where ship_method_language = '" . (int)$languages_id . "'");
      while ($language_array13 = tep_db_fetch_array($language_query13)) {
        tep_db_query("insert into " . TABLE_ORDERS_SHIP_METHODS . " (ship_methods_id, ship_method_language, ship_method_sort, ship_method) values ('" . (int)$language_array13['ship_methods_id'] . "', '" . (int)$insert_id . "', '" . (int)$language_array13['ship_method_sort'] . "', '" . tep_db_input($language_array13['ship_method']) . "')");
      }
      // create additional orders_status records
      $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
      while ($orders_status = tep_db_fetch_array($orders_status_query)) {
        tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$orders_status['orders_status_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($orders_status['orders_status_name']) . "')");
      }
      // create additional pages categories description records
      $language_query = tep_db_query("select categories_id, categories_name, categories_description from " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " where language_id = '" . (int)$languages_id . "'");
      while ($language_array = tep_db_fetch_array($language_query)) {
        tep_db_query("insert into " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " (categories_id, categories_name, categories_description, language_id) values ('" . (int)$language_array['categories_id'] . "', '" . tep_db_input($language_array['categories_name']) . "', '" . tep_db_input($language_array['categories_description']) . "', '" . (int)$insert_id . "')");
      }
      // create additional pages description records
      $language_query = tep_db_query("select pages_id, pages_title, pages_meta_title, pages_meta_keywords, pages_meta_description, pages_blurb, pages_body from " . TABLE_PAGES_DESCRIPTION . " where language_id = '" . (int)$languages_id . "'");
      while ($language_array = tep_db_fetch_array($language_query)) {
        tep_db_query("insert into " . TABLE_PAGES_DESCRIPTION . " (pages_id, pages_title, pages_meta_title, pages_meta_keywords, pages_meta_description, pages_blurb, pages_body, language_id) values ('" . (int)$language_array['pages_id'] . "', '" . tep_db_input($language_array['pages_title']) . "', '" . tep_db_input($language_array['pages_meta_title']) . "', '" . tep_db_input($language_array['pages_meta_keywords']) . "', '" . tep_db_input($language_array['pages_meta_description']) . "', '" . tep_db_input($language_array['pages_blurb']) . "', '" . tep_db_input($language_array['pages_body']) . "', '" . (int)$insert_id . "')");
      }
      // create additional products_description records
      $language_query14 = tep_db_query("select products_id, language_id, products_name, products_description,  products_url, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag  from " . TABLE_PRODUCTS_DESCRIPTION . " where language_id = '" . (int)$languages_id . "'");
      while ($language_array14 = tep_db_fetch_array($language_query14)) {
        tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_description,  products_url, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag) values ('" . (int)$language_array14['products_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($language_array14['products_name']) . "', '" . tep_db_input($language_array14['products_description']) . "', '" . tep_db_input($language_array14['products_url']) . "', '" . tep_db_input($language_array14['products_head_title_tag']) . "', '" . tep_db_input($language_array14['products_head_desc_tag']) . "', '" . tep_db_input($language_array14['products_head_keywords_tag']) . "')");
      }

      // create additional products extra fields records
      $language_query = tep_db_query("select products_extra_fields_id, products_extra_fields_name, products_extra_fields_order, products_extra_fields_status from " . TABLE_PRODUCTS_EXTRA_FIELDS . " where languages_id = '" . (int)$languages_id . "'");
      while ($language_array = tep_db_fetch_array($language_query)) {
        tep_db_query("insert into " . TABLE_PRODUCTS_EXTRA_FIELDS . " (products_extra_fields_id, products_extra_fields_name, products_extra_fields_order, products_extra_fields_status, languages_id) values ('" . (int)$language_array['products_extra_fields_id'] . "', '" . tep_db_input($language_array['products_extra_fields_name']) . "', '" . (int)$language_array['products_extra_fields_order'] . "', '" . (int)$language_array['products_extra_fields_status'] . "', '" . (int)$insert_id . "')");
      }

      // create additional products_options text records
      $language_query15 = tep_db_query("select products_options_text_id, products_options_name, products_options_instruct from " . TABLE_PRODUCTS_OPTIONS_TEXT . " where language_id = '" . (int)$languages_id . "'");
      while ($language_array15 = tep_db_fetch_array($language_query15)) {
        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_TEXT . " (products_options_text_id, products_options_name, products_options_instruct, language_id) values ('" . (int)$language_array15['products_options_text_id'] . "', '" . tep_db_input($language_array15['products_options_name']) . "', '" . tep_db_input($language_array15['products_options_instruct']) . "', '" . (int)$insert_id . "')");
      }

      // create additional products_options_values records
      $language_query16 = tep_db_query("select products_options_values_id, products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . (int)$languages_id . "'");
      while ($language_array16 = tep_db_fetch_array($language_query16)) {
        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . (int)$language_array16['products_options_values_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($language_array16['products_options_values_name']) . "')");
      }

      // create additional reviews_description records
      $language_query17 = tep_db_query("select reviews_id, languages_id , reviews_text from " . TABLE_REVIEWS_DESCRIPTION . " where languages_id = '" . (int)$languages_id . "'");
      while ($language_array17 = tep_db_fetch_array($language_query17)) {
        tep_db_query("insert into " . TABLE_REVIEWS_DESCRIPTION . " (reviews_id, languages_id , reviews_text) values ('" . (int)$language_array17['reviews_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($language_array17['reviews_text']) . "')");
      }

      // create additional topics description records
      $language_query = tep_db_query("select topics_id, topics_name, topics_heading_title, topics_description from " . TABLE_TOPICS_DESCRIPTION . " where language_id = '" . (int)$languages_id . "'");
      while ($language_array = tep_db_fetch_array($language_query)) {
        tep_db_query("insert into " . TABLE_TOPICS_DESCRIPTION . " (topics_id, topics_name, topics_heading_title, topics_description, language_id) values ('" . (int)$language_array['topics_id'] . "', '" . tep_db_input($language_array['topics_name']) . "', '" . tep_db_input($language_array['topics_heading_title']) . "', '" . tep_db_input($language_array['topics_description']) . "', '" . (int)$insert_id . "')");
      }


      if (isset($_POST['default']) && ($_POST['default'] == 'on')) {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($code) . "' where configuration_key = 'DEFAULT_LANGUAGE'");
        $query = tep_db_query("select * from languages where code='" . tep_db_input($code) . "' ");
        $result = tep_db_fetch_array($query);          

        $_SESSION['language'] = $result['directory'];
        $_SESSION['languages_id'] = $result['languages_id'];
      }

      tep_redirect(tep_href_link(FILENAME_LANGUAGES, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'lID=' . $insert_id));
    break;
    case 'save':
      $lID = tep_db_prepare_input($_GET['lID']);
      $name = tep_db_prepare_input($_POST['name']);
      $code = tep_db_prepare_input($_POST['code']);
      $image = tep_db_prepare_input($_POST['image']);
      $directory = tep_db_prepare_input($_POST['directory']);
      $sort_order = tep_db_prepare_input($_POST['sort_order']);

      tep_db_query("update " . TABLE_LANGUAGES . " set name = '" . tep_db_input($name) . "', code = '" . tep_db_input($code) . "', image = '" . tep_db_input($image) . "', directory = '" . tep_db_input($directory) . "', sort_order = '" . tep_db_input($sort_order) . "' where languages_id = '" . (int)$lID . "'");

      if ($_POST['default'] == 'on') {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($code) . "' where configuration_key = 'DEFAULT_LANGUAGE'");
        $query = tep_db_query("select * from languages where code='" . tep_db_input($code) . "' ");
        $result = tep_db_fetch_array($query);          

        $_SESSION['language'] = $result['directory'];
        $_SESSION['languages_id'] = $result['languages_id'];
      }

      tep_redirect(tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $_GET['lID']));
    break;
    case 'deleteconfirm':
      $lID = tep_db_prepare_input($_GET['lID']);
      $lng_query = tep_db_query("select languages_id from " . TABLE_LANGUAGES . " where code = '" . DEFAULT_CURRENCY . "'");
      $lng = tep_db_fetch_array($lng_query);
      if ($lng['languages_id'] == $lID) {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '' where configuration_key = 'DEFAULT_CURRENCY'");
      }

      tep_db_query("delete from " . TABLE_AFFILIATE_NEWS_CONTENTS . " where affiliate_news_languages_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_AFFILIATE_PAYMENT_STATUS . " where affiliate_language_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_ARTICLE_REVIEWS_DESCRIPTION . " where languages_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_ARTICLES_DESCRIPTION . " where language_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_AUTHORS_INFO . " where languages_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_CATEGORIES_DESCRIPTION . " where language_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_COUPONS_DESCRIPTION . " where language_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_EVENTS_CALENDAR . " where language_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " where language_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_INFOBOX_HEADING . " where languages_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where language_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_LINKS_DESCRIPTION . " where language_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_LINKS_STATUS . " where language_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_MANUFACTURERS_INFO . " where languages_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_ORDERS_PAY_METHODS . " where pay_method_language = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_ORDERS_SHIP_METHODS . " where ship_method_language = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " where language_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_PAGES_DESCRIPTION . " where language_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_PRODUCTS_DESCRIPTION . " where language_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_PRODUCTS_EXTRA_FIELDS . " where languages_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_TEXT . " where language_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION . " where languages_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_TOPICS_DESCRIPTION . " where language_id = '" . (int)$lID . "'");
      tep_db_query("delete from " . TABLE_LANGUAGES . " where languages_id = '" . (int)$lID . "'");

      tep_redirect(tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page']));
    break;
    case 'delete':
      $lID = tep_db_prepare_input($_GET['lID']);

      $lng_query = tep_db_query("select code from " . TABLE_LANGUAGES . " where languages_id = '" . (int)$lID . "'");
      $lng = tep_db_fetch_array($lng_query);

      $remove_language = true;
      if ($lng['code'] == DEFAULT_LANGUAGE) {
        $remove_language = false;
        $messageStack->add('search', ERROR_REMOVE_DEFAULT_LANGUAGE, 'error');
      }
    break;
  }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
  <title><?php echo TITLE; ?></title>
  <script type="text/javascript" src="includes/prototype.js"></script>
  <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
  <!--[if IE]>
  <link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
  <![endif]-->
  <script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
  <!-- header //-->
  <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
  <!-- header_eof //-->
  <!-- body //-->
  <div id="body">
    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
      <tr>
        <!-- left_navigation //-->
        <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
        <!-- left_navigation_eof //-->
        <!-- body_text //-->
        <td class="page-container" valign="top">
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td width="100%">
                <table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="pageHeading"><?php echo HEADING_TITLE; ?>
                    <?php
                      if(isset($_GET['action']) && $_GET["action"] == "sync") {           
                        $ln_id = get_degault_language_id();
                        $s_lang = "select name from languages where languages_id = ".$ln_id;
                        $res_lang = mysql_query($s_lang) or die(mysql_error());
                        $data_lang = mysql_fetch_array($res_lang);
                        Print(" <span class='dataTableContent'><br> ".DEFAULT_LANGUAGE_IS." ".$data_lang["name"] . "</span>");
                      }
                    ?>
                    </td>
                    <td class="pageHeading" align="right">
                      <?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td valign="top">
                    <?php
                    $s_dbnm = DB_DATABASE;
                    $arr_show = array();
                    $arr_update_show = array();
                    $arr_missing_lang_tables = array();
                    if($action == "sync" || $action == 'show') {
                      $s_mode = $action;
                      // step 1 : collect default language id
                      $i_default_language_id = get_degault_language_id();
                      // step 2 : collect all the table name which has language_id or languages_id as one of its field 
                      $arr_table_names = get_table_names($s_dbnm);
                      // step 3 : collect all the defined languages from the language table 
                      $arr_languages_id = collect_all_defined_languages_id();
                      $i_lang_num_rec = count($arr_languages_id);
                      // step 4 : Initialization for some escape and explicite tables
                      $arr_escape_tables = array("orders_session_info");
                      $arr_explicite_tables = array("affiliate_news_contents","affiliate_payment_status","orders_pay_methods","orders_ship_methods");
                      //step 5 : Merge the explicite tables with the table names find out from step2
                      $arr_table_names = array_merge($arr_table_names,$arr_explicite_tables);
                      for($icounter = 0 ; $icounter < count($arr_table_names); $icounter++) {
                        $s_table_name = $arr_table_names[$icounter];
                        if(in_array($s_table_name,$arr_escape_tables)) {   
                          continue;
                        }
                        //step 6 : get the primary coloumn name and language field name from tables to find the unique record for default language
                        // collect primary key column name
                        $arr_key_name = get_primary_key_column_name($s_table_name);
                        // collect the language defined column name
                        $s_language_key = get_language_field_name($s_table_name);
                        if($s_table_name == "orders_pay_methods") {
                          $s_language_key = "pay_method_language";
                        }
                        if($s_table_name == "orders_ship_methods") {
                          $s_language_key = "ship_method_language";
                        }
                        //step 7 : collect the no of records for each unique record id. If no. of records are mismatch with teh defined language, the find for which language  the record is missing
                        //collect the distinct record for default language id
                        $arr_distinct_id = get_distinct_record_ids($arr_key_name[0],$s_table_name,$i_default_language_id,$s_language_key);
                        // if no record exist 
                        if(empty($arr_distinct_id)) {
                          continue;
                        }
                        $s_show = "";
                        $s_show_update = "";
                        $tmp = 0;
                        $tmp_update = 0;
                        for($vloop = 0 ; $vloop < count($arr_distinct_id) ; $vloop++) {  
                          if($arr_key_name[0] == 'language_id' || $s_table_name == 'branding_description' ) {
                            continue;
                          }
                          // get no of record for each distinct id
                          $i_num_rec = get_no_of_records_for_distinct_id($arr_distinct_id[$vloop],$arr_key_name[0],$s_table_name);
                          // If record is missing for any language
                          if($i_num_rec < $i_lang_num_rec) { 
                            $arr_missing_lang_tables[] = $s_table_name;
                            $arr_missing_lang_for_distinct_id[] = $arr_distinct_id[$vloop];
                            //for each defined langauge
                            for($iloop = 0 ; $iloop < count($arr_languages_id);$iloop++) {
                              if($arr_languages_id[$iloop] == $i_default_language_id) {
                                continue;
                              }
                              //step 8 : So insert the record for missing language same as the record for default language id
                              //Check for which language the record is missing
                              if(!record_exist_for_languages_id($arr_languages_id[$iloop],$arr_distinct_id[$vloop],$arr_key_name[0],$s_table_name,$s_language_key)) { 
                                $total_rec = $iloop;
                                $arr_tables_missing_languages[$s_table_name][$arr_distinct_id[$vloop]][] = $arr_languages_id[$iloop];
                                $arr_tables_missing_languages_only[$s_table_name][] = $arr_languages_id[$iloop];
                                $arr_tables_distinct_ids_only[$s_table_name][] = $arr_distinct_id[$vloop];
                                // insert the record for missing langauge
                                $s_show .= insert_record_for_languages_id($i_default_language_id,$arr_languages_id[$iloop],$s_table_name,$arr_key_name[0],$arr_distinct_id[$vloop],$s_language_key,$s_mode)."<br><br>";
                                $arr_show[$s_table_name] = $s_show;
                                $tmp++;
                              }                           
                              
                              // Check for which language the data is missing
                              if(data_exist_for_languages_id($arr_languages_id[$iloop],$arr_distinct_id[$vloop],$arr_key_name[0],$s_table_name,$s_language_key)) {
                                $s_show_update .=  update_record_for_missing_languages_id($i_default_language_id,$arr_languages_id[$iloop],$s_table_name,$arr_key_name[0],$arr_distinct_id[$vloop],$s_language_key,$s_mode)."<br><br>";
                                $arr_update_show[$s_table_name] = $s_show_update;
                                $arr_update_show_dist_missing_lng[$s_table_name][$arr_distinct_id[$vloop]][]= $arr_languages_id[$iloop];
                                $arr_update_show_missing_lng[$s_table_name][]= $arr_languages_id[$iloop];
                                $arr_tables_update_distinct_ids_only[$s_table_name][] = $arr_distinct_id[$vloop];
                                $tmp_update++;
                              }
                            }
                          }
                        }
                        // collect total no of records inserted for the table
                        if($tmp > 0) {
                          $arr_added_records[$s_table_name] = $tmp;
                        }
                        // collect total no of records updated for the table
                        if($tmp_update > 0) {
                          $arr_update_records[$s_table_name] = $tmp_update;
                        } 
                      }
                      if(isset($arr_missing_lang_tables) && is_array($arr_missing_lang_tables) && count($arr_missing_lang_tables) > 0) {
                        // Collect unique table name for missing language records
                        $arr_missing_lang_tables = array_unique($arr_missing_lang_tables);
                      }
                    }
                    if(isset($_GET['action']) && $_GET["action"] == "sync") { 
                    ?>
                    <table border="0" width="100%" cellspacing="1" cellpadding="0" bgcolor = "#c9c9c9">
                      <tr>
                        <td>
                          <table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor = "#FFFFFF">
                          <?php
                          if((is_array($arr_missing_lang_tables) && count($arr_missing_lang_tables) > 0) || (is_array($arr_update_show) && count($arr_update_show) > 0)) {
                          ?>
                            <tr>
                              <td>
                                <table border="0" width="100%" cellspacing="1" cellpadding="2">
                                  <tr class="dataTableHeadingRow">
                                    <td class="dataTableHeadingContent" style = "padding-left:20"><?php print(TABLE_NAMES);?> </td>
                                    <td class="dataTableHeadingContent" style = "padding-left:20"><?php print(MISSING_LANGUAGE);?> </td>
                                    <td class="dataTableHeadingContent" style = "padding-left:20"><?php  print(MISSING_LANGUAGES_FOR_DISTINCT_ID);?></td>
                                    <td class="dataTableHeadingContent" style = "padding-left:20"><?php print(TOTAL_NO_OF_RECORDS_IMPACTED);?> </td>
                                  </tr>
                                  <tr class="dataTableHeadingRow">
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>
                                      <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                        <tr class="dataTableHeadingRow">
                                          <td width = "50%"  class="dataTableHeadingContent"><?php print(DISTINCT_ID);?> </td>
                                          <td width = "50%" class="dataTableHeadingContent"><?php  print(MISSING_LANGUAGE);?></td>
                                        </tr>
                                      </table>
                                    </td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <?php                                  
                                  if(isset($arr_missing_lang_tables) && is_array($arr_missing_lang_tables)) {
                                    $ar_count = count($arr_missing_lang_tables);
                                  }
                                  if($ar_count > 0) {
                                  ?>
                                  <tr>
                                    <td><B><?php  print(INSERT_RECORDS_ANALYSIS);?></B></td>
                                  </tr>
                                  <?php
                                  }
                                  $ii_count = 0;
                                  foreach($arr_missing_lang_tables as $s_tbl_name) {
                                  ?>
                                  <tr bgcolor = "#FFFFFF">
                                    <td style = "padding-left:20" class="dataTableContent" valign = "top"><br><?php print( $s_tbl_name );?> </td>
                                    <td class="dataTableContent" valign = "top"><br>
                                      <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                        <?php
                                        $arr_lang1 = array_unique($arr_tables_missing_languages_only[$s_tbl_name]);
                                        sort($arr_lang1);
                                        for($j = 0 ; $j < count($arr_lang1) ; $j++) {
                                        ?>
                                        <tr>
                                          <td style = "padding-left:20" class="dataTableContent" valign = "top"><?php print($arr_lang1[$j]); ?> </td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                      </table>
                                    </td>
                                    <td>
                                      <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                        <?php  
                                        $x_count = 0;
                                        foreach(array_unique($arr_tables_distinct_ids_only[$s_tbl_name]) as $i_dist_id) {
                                          if($x_count%2 == 0) {
                                            $s_bgcolor = '#FFFFFF';
                                          } else {
                                            $s_bgcolor = '#F3F3F3';
                                          }
                                          $x_count++;
                                        ?>
                                        <tr bgcolor = "<?php echo $s_bgcolor;?>">
                                          <td width = "50%" style = "padding-left:20"><?php print($i_dist_id); ?> </td>
                                          <td width = "50%">
                                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                              <?php               
                                              foreach($arr_tables_missing_languages[$s_tbl_name][$i_dist_id] as $val) {
                                              ?>
                                              <tr>
                                                <td style = "padding-left:20" ><?php print($val);?> </td>
                                              </tr>
                                              <?php
                                              }
                                              ?>
                                            </table>
                                          </td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                      </table>
                                    </td>
                                    <td style = "padding-left:20" class="dataTableContent" valign = "top">
                                      <br><?php print($arr_added_records[$s_tbl_name]);?>
                                    </td>
                                  </tr>
                                  <?php               
                                  if($ar_count > 0 && $ii_count < ($ar_count-1) ) { 
                                  ?>
                                  <tr>
                                    <td colspan="10" bgcolor = "#D8D8D8"></td>
                                  </tr>
                                  <?php
                                  }
                                  $ii_count++;                                        
                                }
                                
                                if(isset($arr_update_show) && is_array($arr_update_show)) {
                                  $ar_count = count($arr_update_show);
                                }
                                if($ar_count > 0) {
                                ?>
                                  <tr>
                                    <td colspan="10" bgcolor = "#D8D8D8"></td>
                                  </tr>
                                  <tr>
                                    <td colspan="10"><B><?php print(UPDATE_RECORDS_ANALYSIS);?></B></td>
                                  </tr>
                                <?php
                                }
                                $iii_count = 0;
                                foreach($arr_update_show as $x_key_update => $x_val_update) {
                                  if($ii_count > 0 && $iii_count == 0) {
                                ?>
                                  <tr>
                                    <td colspan="10" bgcolor = "#D8D8D8"></td>
                                  </tr>
                                <?php
                                  }
                                ?>
                                  <tr>
                                    <td style = "padding-left:20" valign = "top"><br>
                                      <?php print($x_key_update);?>
                                    </td>
                                    <td valign = "top"><br>
                                      <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                        <?php
                                        $arr_lang_update1 = array_unique($arr_update_show_missing_lng[$x_key_update]);
                                        sort($arr_lang_update1);
                                        for($j = 0 ; $j < count($arr_lang_update1) ; $j++) {
                                        ?>
                                        <tr>
                                          <td style = "padding-left:20" valign = "top">
                                            <?php print($arr_lang_update1[$j]); ?>
                                          </td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                      </table>
                                    </td>
                                    <td>
                                      <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                        <?php 
                                        $x_count = 0;
                                        foreach(array_unique($arr_tables_update_distinct_ids_only[$x_key_update]) as $i_update_dist_id) {
                                          if($x_count%2 == 0) { 
                                            $s_bgcolor = '#FFFFFF';
                                          } else {
                                            $s_bgcolor = '#F3F3F3';
                                          }
                                          $x_count++;
                                        ?>
                                        <tr bgcolor = "<?php echo $s_bgcolor;?>">
                                          <td width = "50%" style = "padding-left:20">
                                            <?php print($i_update_dist_id); ?>
                                          </td>
                                          <td width = "50%">
                                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                              <?php               
                                              foreach($arr_update_show_dist_missing_lng[$x_key_update][$i_update_dist_id] as $s_update_val) {
                                              ?>
                                              <tr>
                                                <td style = "padding-left:20"><?php print($s_update_val);?> </td>
                                              </tr>
                                              <?php
                                              }
                                              ?>
                                            </table>
                                          </td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                      </table>
                                    </td>
                                    <td style = "padding-left:20" valign = "top"><br>
                                      <?php print($arr_update_records[$x_key_update]);?>
                                    </td>
                                  </tr>
                                  <?php               
                                  if($ar_count > 0 && $iii_count < ($ar_count-1) ) { 
                                  ?>
                                  <tr>
                                    <td colspan="10" bgcolor = "#D8D8D8"></td>
                                  </tr>
                                  <?php
                                  }
                                $iii_count++; 
                                }
                                ?>
                                </table>
                              </td>
                            </tr>
                          <?php
                          } else {
                            print("<tr><td height = '35'><p><center>".THERE_ARE_NO_MISSING_RECORD_FOR_ANY_LANGUAGES."</center></p></td></tr>");
                          }
                          ?>
                          </table>
                        </td>
                      </tr>
                    </table>
                    <?php
                    } else {
                    ?>
                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LANGUAGE_NAME; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LANGUAGE_CODE; ?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                    </tr>
                    <?php
                    $languages_query_raw = "select languages_id, name, code, image, directory, sort_order from " . TABLE_LANGUAGES . " order by sort_order";
                    $languages_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $languages_query_raw, $languages_query_numrows);
                    $languages_query = tep_db_query($languages_query_raw);

                    while ($languages = tep_db_fetch_array($languages_query)) {
                      if ((!isset($_GET['lID']) || (isset($_GET['lID']) && ($_GET['lID'] == $languages['languages_id']))) && !isset($lInfo) && (substr($action, 0, 3) != 'new')) {
                        $lInfo = new objectInfo($languages);
                      }
                      if (isset($lInfo) && is_object($lInfo) && ($languages['languages_id'] == $lInfo->languages_id) ) {
                        echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=edit') . '\'">' . "\n";
                      } else {
                        echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $languages['languages_id']) . '\'">' . "\n";
                      }
                      if (DEFAULT_LANGUAGE == $languages['code']) {
                        echo '<td class="dataTableContent"><b>' . $languages['name'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
                      } else {
                        echo '<td class="dataTableContent">' . $languages['name'] . '</td>' . "\n";
                      }
                    ?>
                      <td class="dataTableContent"><?php echo $languages['code']; ?></td>
                      <td class="dataTableContent" align="right"><?php if (isset($lInfo) && is_object($lInfo) && ($languages['languages_id'] == $lInfo->languages_id)) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png'); } else { echo '<a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $languages['languages_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                      <td colspan="3">
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr>
                            <td class="smallText" valign="top"><?php echo $languages_split->display_count($languages_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_LANGUAGES); ?></td>
                            <td class="smallText" align="right"><?php echo $languages_split->display_links($languages_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                          </tr>
                          <?php
                          if (empty($action)) {
                          ?>
                          <tr>
                            <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=new') . '">' . tep_image_button('button_new_language.gif', IMAGE_NEW_LANGUAGE) . '</a>'; ?></td>
                          </tr>
                          <?php
                          }
                          ?>
                        </table>
                      </td>
                    </tr>
                    </table>
                    <?php
                    }
                    ?>
                    </td>
                  <?php
                    $heading = array();
                    $contents = array();

                    switch ($action) {
                    case 'new':
                    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_LANGUAGE . '</b>');

                    $contents = array('form' => tep_draw_form('languages', FILENAME_LANGUAGES, 'action=insert'));
                    $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
                    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_NAME . '<br>' . tep_draw_input_field('name'));
                    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_CODE . '<br>' . tep_draw_input_field('code'));
                    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_IMAGE . '<br>' . tep_draw_input_field('image', 'icon.gif'));
                    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_DIRECTORY . '<br>' . tep_draw_input_field('directory'));
                    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order'));
                    $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
                    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $_GET['lID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                    break;
                    case 'edit':
                    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_LANGUAGE . '</b>');

                    $contents = array('form' => tep_draw_form('languages', FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=save'));
                    $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
                    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_NAME . '<br>' . tep_draw_input_field('name', $lInfo->name));
                    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_CODE . '<br>' . tep_draw_input_field('code', $lInfo->code));
                    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_IMAGE . '<br>' . tep_draw_input_field('image', $lInfo->image));
                    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_DIRECTORY . '<br>' . tep_draw_input_field('directory', $lInfo->directory));
                    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $lInfo->sort_order));
                    if (DEFAULT_LANGUAGE != $lInfo->code) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
                    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                    break;    

                    case 'show':    
                    $heading[] = array('text' => '<b>' . TABLE_SYNCHRONIZATION . '</b>');
                    $contents = array('form' => tep_draw_form('languages_sync', FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=sync'));

                    if(!empty($arr_missing_lang_tables))
                    {
                    $contents[] = array('text' => "<b><center> ".INSERT_QUERIES." </center></b><br>");
                    foreach($arr_missing_lang_tables as $val1)
                    {
                    $contents[] = array('text' => "<b>$val1</b><br>");
                    $contents[] = array('text' =>$arr_show[$val1]."<br>");
                    }
                    }

                    if(!empty($arr_update_show))
                    {
                    $contents[] = array('text' => "<b><center> ".UPDATE_QUERIES." </center></b><br>");
                    foreach($arr_update_show as $x_key=>$x_val)
                    {
                    $contents[] = array('text' => "<b>$x_key</b><br>");
                    $contents[] = array('text' =>$x_val);
                    }
                    }
                    if(empty($arr_missing_lang_tables) && empty($arr_update_show))
                    {
                    $contents[] = array('align' => 'center', 'text' => '<br>' . THERE_ARE_NO_MISSING_RECORD_FOR_ANY_LANGUAGES. '<br><br> <a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                    }
                    else
                    {
                    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                    }
                    break;

                    case 'delete':
                    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_LANGUAGE . '</b>');

                    $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
                    $contents[] = array('text' => '<br><b>' . $lInfo->name . '</b>');
                    $contents[] = array('align' => 'center', 'text' => '<br>' . (($remove_language) ? '<a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=deleteconfirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>' : '') . ' <a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                    break;
                    default:
                    if (is_object($lInfo)) {
                    $heading[] = array('text' => '<b>' . $lInfo->name . '</b>');
                    $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_EDIT_TEXT, '&lng=' . $lInfo->directory . '&lngdir=') . '">' . tep_image_button('button_edit_lang_define.gif', IMAGE_EDIT_LANG_DEFINE) . '</a> <a href="' . tep_href_link(FILENAME_LANGUAGES,'action=show') . '">' . tep_image_button('sync.gif', IMAGE_SYNC). '</a>');
                    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_NAME . ' ' . $lInfo->name);
                    $contents[] = array('text' => TEXT_INFO_LANGUAGE_CODE . ' ' . $lInfo->code);
                    $contents[] = array('text' => '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $lInfo->directory . '/images/' . $lInfo->image, $lInfo->name));
                    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_DIRECTORY . '<br>' . DIR_WS_CATALOG_LANGUAGES . '<b>' . $lInfo->directory . '</b>');
                    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_SORT_ORDER . ' ' . $lInfo->sort_order);
                    }
                    break;
                    }

                    if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
                    echo '            <td width="25%" valign="top">' . "\n";

                    $box = new box;
                    echo $box->infoBox($heading, $contents);

                    echo '            </td>' . "\n";
                    }
                    ?>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
        <!-- body_text_eof //-->
      </tr>
    </table>
  </div>
  <!-- body_eof //-->
  <!-- footer //-->
  <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
  <!-- footer_eof //-->
  <br>
  </body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<?php

#############################################
## show all the table names from database  ##
#############################################
function show_tables($s_dbnm) {
  // Run a query to get all the table names
  $s_show_tables = "show tables from ".$s_dbnm." ";
  $res_show_tables = mysql_query($s_show_tables) or die(mysql_error());
  While($data_show_tables = mysql_fetch_array($res_show_tables)) {
    $arr_table_name_tmp[] = $data_show_tables[0];
  }
  return $arr_table_name_tmp;
}
#########################################################
## get table names which has language_id/languages_id  ##
## as one of the field                                 ##
#########################################################
function get_table_names($s_dbnm) { 
  // show all the tables in database
  $arr_table_name_tmp = show_tables($s_dbnm);
  for($vloop = 0 ; $vloop < count($arr_table_name_tmp) ; $vloop++) {
    $s_table_name_tmp = $arr_table_name_tmp[$vloop];
    // escape for language table as it is our reference table
    if($s_table_name_tmp == "languages") {
      continue;
    }
    // run a query to show all the fields of the table
    $s_tbl_hving_langs_id = "show fields from ".$s_table_name_tmp."";
    $res_tbl_hving_langs_id = mysql_query($s_tbl_hving_langs_id) or die(mysql_error());
    while($data_tbl_hving_langs_id = mysql_fetch_array($res_tbl_hving_langs_id)) {     
      $s_table_field = $data_tbl_hving_langs_id["Field"];
      // If table have `languages_id` as one of its field   
      if($s_table_field == "language_id") {
        $arr_table_names[] = $s_table_name_tmp;
        break;
      }
      if($s_table_field == "languages_id") {
        $arr_table_names[] = $s_table_name_tmp;
        break;
      }
    }
  } 
  return $arr_table_names;
}

#############################################
## Get the primary_key name for table      ##
#############################################
function get_primary_key_column_name($s_table_name) { 
  $arr_key_name = array();
  $s_pkquery = "show keys from `".$s_table_name."`";
  $res_pkquery = mysql_query($s_pkquery) or die(mysql_error());
  while($data_pkquery = mysql_fetch_array($res_pkquery)) {
    // collect only the Primaray key colounm name
    if($data_pkquery["Key_name"] == "PRIMARY" && $data_pkquery["Column_name"] != "languages_id") {
      $arr_key_name[] = $data_pkquery["Column_name"];
    }
  }
  if(empty($arr_key_name)) {
    $s_pkquery = "show keys from `".$s_table_name."`";
    $res_pkquery = mysql_query($s_pkquery) or die(mysql_error());
    while($data_pkquery1 = mysql_fetch_array($res_pkquery)) {
      $arr_key_name[] = $data_pkquery1["Column_name"];
    }
  }
  return $arr_key_name;
}

function get_language_field_name($s_table_name) {
  $s_kquery = "show fields from `".$s_table_name."`";
  $res_kquery = mysql_query($s_kquery) or die(mysql_error());
  while($data_kquery = mysql_fetch_array($res_kquery)) {
    // collect only the Primaray key colounm name
    if(is_numeric(strpos($data_kquery["Field"],"language")) && is_numeric(strpos($data_kquery["Field"],"id")) ) {
      return $data_kquery["Field"];
    }   
  } 
}

#################################################
## Get the get distinct record ids for table   ##
#################################################
function get_distinct_record_ids($s_key_name,$s_table_name,$i_default_language_id,$s_language_key) {
  $arr_distinct_id = array();
  $s_distinctrec = "select distinct(".$s_key_name.") from `".$s_table_name."` where ".$s_language_key." = ".$i_default_language_id." order by ".$s_key_name; 
  $res_distinctrec = mysql_query($s_distinctrec) or die(mysql_error());
  while($data_distinctrec = mysql_fetch_array($res_distinctrec)) {
    $arr_distinct_id[] = $data_distinctrec[0];
  }
  return($arr_distinct_id);
}

#########################################
## collect all defined languages id    ##
#########################################
function collect_all_defined_languages_id() {
  $s_languages_id = "select languages_id from languages order by languages_id";
  $res_languages_id = mysql_query($s_languages_id) or die(mysql_error());
  while($data_languages_id = mysql_fetch_array($res_languages_id)) {
    $arr_languages_id[] = $data_languages_id["languages_id"];
  }
  return $arr_languages_id;
}

#########################################
## get no of records for distinct id   ##
#########################################
function get_no_of_records_for_distinct_id($i_distinct_id,$s_key_name,$s_table_name) {
  $s_distinct_id = "select count(*) from ".$s_table_name." where ".$s_key_name." = '".$i_distinct_id."'";
  $res_distinct_id = mysql_query($s_distinct_id) or die(mysql_error());
  $data_distinct_id = mysql_fetch_array($res_distinct_id);
  return $data_distinct_id[0];
}

#########################################
## get no of records for distinct id   ##
#########################################
function record_exist_for_languages_id($i_languages_id,$i_distinct_id,$s_key_name,$s_table_name,$s_language_key) {
  $s_chk_record = "select count(*) from ".$s_table_name." where ".$s_language_key." = '".$i_languages_id."' and ".$s_key_name." = '".$i_distinct_id."'";
  $res_chk_record = mysql_query($s_chk_record) or die(mysql_error());
  $data_chk_record = mysql_fetch_array($res_chk_record);
  $i_exist = $data_chk_record[0];
  return $i_exist;
}

#########################################
## insert record for languages id      ##
#########################################
function insert_record_for_languages_id($i_default_language_id,$i_languages_id,$s_table_name,$s_key_name,$i_distinct_id,$s_language_key,$s_mode) {
  // collect fields in array `$arr_default_language_fields`
  $arr_default_language_fields = get_fields_name($s_table_name);
  // collect field data in array `$arr_default_language_data`
  $arr_default_language_data = get_fields_data($s_table_name,$i_default_language_id,$s_key_name,$i_distinct_id,$arr_default_language_fields,$i_languages_id,$s_language_key);
  $s_fields = "";
  $s_data   = "";
  // generate fields string and values string for insert query
  for($iloop = 0 ;$iloop < count($arr_default_language_fields); $iloop++) {
    if($iloop == (count($arr_default_language_fields) - 1 )) {
      $s_fields = $s_fields.$arr_default_language_fields[$iloop];
      $s_data = $s_data."'".mysql_escape_string($arr_default_language_data[$iloop])."'";
    } else {
      $s_fields = $s_fields.$arr_default_language_fields[$iloop].",";
      $s_data = $s_data."'".mysql_escape_string($arr_default_language_data[$iloop])."' , ";
    }
  }
  // insert data in table for missing language
  $s_insert = "insert into ".$s_table_name." ( ".$s_fields." ) values ( ".$s_data.");";
  if($s_mode == "show") {    
    return($s_insert);
  } else if($s_mode == "sync") {   
    mysql_query($s_insert) or die($s_insert."<br><br>".mysql_error());
  }
}

#########################
## get fields name     ##
#########################
function get_fields_name($s_table_name) {
  // run query to get all the fields from table
  $s_default_language_fields = "show fields from ".$s_table_name."";
  $res_default_language_fields = mysql_query($s_default_language_fields) or die(mysql_error());
  while($data_default_language_fields = mysql_fetch_array($res_default_language_fields)) {
    $arr_default_language_fields[] = $data_default_language_fields["Field"];
  }
  return $arr_default_language_fields;
}

#########################
## get_fields_data     ##
#########################
function get_fields_data($s_table_name,$i_default_language_id,$s_key_name,$i_distinct_id,$arr_default_language_fields,$i_languages_id,$s_language_key) {
  // Run the query to get the data for default language
  $s_default_language_data = "select * from ".$s_table_name." where ".$s_language_key." = '".$i_default_language_id."' and ".$s_key_name." = '".$i_distinct_id."'"; 
  $res_default_language_data = mysql_query($s_default_language_data) or die(mysql_error());
  $data_default_language_data = mysql_fetch_array($res_default_language_data);
  for($vloop = 0; $vloop < count($arr_default_language_fields) ; $vloop++) {
    if($arr_default_language_fields[$vloop] == "languages_id" || $arr_default_language_fields[$vloop] == "language_id" || $arr_default_language_fields[$vloop] == "pay_method_language" || $arr_default_language_fields[$vloop] == "ship_method_language" || $arr_default_language_fields[$vloop] == "affiliate_language_id") {
      $arr_default_language_data[] = $i_languages_id;
    } else {
      $arr_default_language_data[] = $data_default_language_data[$arr_default_language_fields[$vloop]];
    }   
  }
  return $arr_default_language_data;
}

function data_exist_for_languages_id($i_languages_id,$i_distinct_id,$s_key_name,$s_table_name,$s_language_key) {
  $arr_missing_data_field = array();
  // collect all the fields of  the table
  $arr_table_fields = get_fields_name($s_table_name);
  // run query to collect all the data for perticular record
  $s_chk_data = "select * from ".$s_table_name." where ".$s_key_name." = '".$i_distinct_id."' and ".$s_language_key." = '".$i_languages_id."'";
  $res_chk_data = mysql_query($s_chk_data) or die(mysql_error($s_chk_data ));
  $r_count = mysql_num_rows($res_chk_data);
  $data_chk_data = mysql_fetch_array($res_chk_data);
  if($r_count > 0) {
    // check data for each field
    foreach($arr_table_fields as $s_field) {
    // if there is blank data for any field then return true else false
      if($data_chk_data[$s_field] == "") {
        return true;
      }
    }   
  }
  return false;
}

function update_record_for_missing_languages_id($i_default_language_id,$i_languages_id,$s_table_name,$s_key_name,$i_distinct_id,$s_language_key,$s_mode) { 
  if($i_languages_id == $i_default_language_id) {
    return;
  }
  // collect fields in array `$arr_default_language_fields`
  $arr_default_language_fields = get_fields_name_for_update($i_default_language_id,$i_languages_id,$s_table_name,$s_key_name,$i_distinct_id,$s_language_key);
  // collect field data in array `$arr_default_language_data`
  $arr_default_language_data = get_fields_data($s_table_name,$i_default_language_id,$s_key_name,$i_distinct_id,$arr_default_language_fields,$i_languages_id,$s_language_key);
  $s_fields = "";
  $s_data   = "";
  $s_update_string = "";
  $s_update = "update ".$s_table_name." SET ";
  // generate fields string and values string for insert query
  for($iloop = 0 ;$iloop < count($arr_default_language_fields); $iloop++) {
    if($iloop == (count($arr_default_language_fields) - 1 )) {
      $s_update_string = $s_update_string.$arr_default_language_fields[$iloop]." = '".mysql_escape_string($arr_default_language_data[$iloop])."'";
    } else {
      $s_update_string = $s_update_string.$arr_default_language_fields[$iloop]." = '".mysql_escape_string($arr_default_language_data[$iloop])."',";
    }
  }
  // update data in table for missing language
  $s_update = $s_update.$s_update_string." where ".$s_key_name." = '".$i_distinct_id."' and ".$s_language_key." = '".$i_languages_id."'"; 
  if($s_mode == "show") {
    return($s_update);
  } else if ($s_mode == "sync") {   
    mysql_query($s_update) or die($s_update."<br><br>".mysql_error());
  }
}


#################################
## get_fields_name_for_update  ##
#################################
function get_fields_name_for_update($i_default_language_id,$i_languages_id,$s_table_name,$s_key_name,$i_distinct_id,$s_language_key) {
  // run query to get all the fields from table
  $s_default_language_fields = "show fields from ".$s_table_name."";
  $res_default_language_fields = mysql_query($s_default_language_fields) or die(mysql_error());
  while($data_default_language_fields = mysql_fetch_array($res_default_language_fields)) {
    $arr_default_language_fields[] = $data_default_language_fields["Field"];
  }
  $s_str = "select * from ".$s_table_name." where ".$s_language_key." = ".$i_languages_id . " and ".$s_key_name. "='".$i_distinct_id."'"; 
  $res = mysql_query($s_str) or die(mysql_error());
  $data = mysql_fetch_array($res);
  foreach($arr_default_language_fields as $xval) {
    if($data[$xval] == "") {
      $arr_default_language_fields_1[] = $xval;
    }
  }
  return $arr_default_language_fields_1;
}

function get_degault_language_id() {
  $s = "select * from configuration where configuration_key = 'DEFAULT_LANGUAGE'";
  $r = mysql_query($s) or die(mysql_error());
  $d = mysql_fetch_array($r);
  $ln = $d["configuration_value"];

  $s1 = "select languages_id from languages where code = '$ln'";
  $r1 = mysql_query($s1) or die(mysql_error());
  $d1 = mysql_fetch_array($r1);
  $ln_id = $d1["languages_id"];

  return $ln_id;
}
?>