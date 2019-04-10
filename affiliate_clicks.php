<?php
/*
  $Id: affiliate_clicks.php,v 1.1.1.1 2004/03/04 23:37:54 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!isset($_SESSION['affiliate_id'])) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_CLICKS);
  $breadcrumb->add(NAVBAR_TITLE_AFFILIATE_CENTRAL, tep_href_link(FILENAME_AFFILIATE_CENTRAL));
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_CLICKS, '', 'SSL'));
/*
  $affiliate_clickthroughs_raw = "
  select a.*, pd.products_name from
    " . TABLE_AFFILIATE_CLICKTHROUGHS . " a,
    " . TABLE_PRODUCTS . " p ,
    " . TABLE_PRODUCTS_DESCRIPTION . " pd
    where a.affiliate_id = '" . $_SESSION['affiliate_id'] . "' and
    a.affiliate_products_id = p.products_id and
    p.products_id = pd.products_id and
    pd.language_id = '" . $languages_id . "'
    ORDER BY a.affiliate_clientdate desc
    ";
*/
  $affiliate_clickthroughs_raw = "select ac.*, pd.products_name from
" . TABLE_AFFILIATE_CLICKTHROUGHS . " ac left join
" . TABLE_PRODUCTS . " p on ac.affiliate_products_id = p.products_id left join
" . TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "'
where
ac.affiliate_id = '" .$_SESSION['affiliate_id'] . "'
ORDER BY ac.affiliate_clientdate desc";

$affiliate_clickthroughs_1 = tep_db_query($affiliate_clickthroughs_raw );
$affiliate_clickthroughs_numrows = tep_db_num_rows($affiliate_clickthroughs_1) ;

  $affiliate_clickthroughs_split = new splitPageResults($affiliate_clickthroughs_raw, MAX_DISPLAY_SEARCH_RESULTS);

  $content = CONTENT_AFFILIATE_CLICKS;
  $javascript = CONTENT_AFFILIATE_SUMMARY . '.js.php';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
