<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('pages', 'top');
// RCI code eof
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
if ($display_mode == 'categories') {
  $pages_category_query = tep_db_query("select icd.categories_name from " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " icd, " . TABLE_PAGES_CATEGORIES . " ic where ic.categories_id = icd.categories_id and icd.categories_id = '" . (int)$cID . "' and icd.language_id = '" . (int)$languages_id . "' and ic.categories_status = '1'");
  
  $pages_category = tep_db_fetch_array($pages_category_query);
?>
  <tr>
    <td class="pageHeading"><?php echo $pages_category['categories_name']; ?></td>
  </tr>

  <tr>
     <td class="main">
<?php
  $pages_query = tep_db_query("select ip.pages_id, ipd.pages_title, ipd.pages_blurb
                               from " . TABLE_PAGES . " ip,
                                    " . TABLE_PAGES_DESCRIPTION . " ipd,
                                    " . TABLE_PAGES_TO_CATEGORIES . " ip2c
                               where ip.pages_status = '1'
                                 and ipd.pages_id = ip.pages_id
                                 and ip.pages_id = ip2c.pages_id
                                 and ip2c.categories_id = '" . (int)$cID . "'
                                 and ipd.language_id = '" . (int)$languages_id . "'
                              ");

  if (tep_db_num_rows($pages_query) > 0) {
    while ($pages = tep_db_fetch_array($pages_query)) {
      echo '<h4 class="pagesTitle">' . '<a href="' . tep_href_link(FILENAME_PAGES, 'cID=' . (int)$cID . '&amp;pID=' . (int)$pages['pages_id']) . '">' . $pages['pages_title'] . '</a>' . '</h4>' . "\n";
      echo '<p class="pagesBlurb">' . $pages['pages_blurb'] . '</p>' . "\n\n";
    }
  } else {
    echo '<p>' . TEXT_NO_PAGES . '</p>' . "\n\n";
  }
?>
    </td>
  </tr>
<?php
} elseif ($display_mode == 'pages') {
  $pages_page_query = tep_db_query("select ipd.pages_id, ipd.pages_title, ipd.pages_body from " . TABLE_PAGES_DESCRIPTION . " ipd, " . TABLE_PAGES . " ip where ip.pages_id = ipd.pages_id and ipd.pages_id = '" . (int)$pID . "' and ipd.language_id = '" . (int)$languages_id . "' and ip.pages_status = '1'");

  if ($pages_page = tep_db_fetch_array($pages_page_query)) {
?>
  <tr>
    <td class="pageHeading"><?php echo $pages_page['pages_title']; ?></td>
  </tr>
  <tr>
     <td class="main"><?php echo $pages_page['pages_body']; ?></td>
  </tr>
<?php
  }
} else {
?>
  <tr>
    <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
  </tr>
  <tr>
     <td class="main">
<?php
  $pages_categories_query = tep_db_query("select ic.categories_id, icd.categories_name, icd.categories_description from " . TABLE_PAGES_CATEGORIES . " ic, " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " icd  where icd.categories_id = ic.categories_id and icd.language_id = '" . (int)$languages_id . "' and ic.categories_status = '1'");

  // pages outside categories
  $pages_query = tep_db_query("select ip.pages_id, ipd.pages_title, ipd.pages_blurb
                               from " . TABLE_PAGES . " ip,
                                    " . TABLE_PAGES_DESCRIPTION . " ipd,
                                    " . TABLE_PAGES_TO_CATEGORIES . " ip2c
                               where ip.pages_status = '1'
                                 and ipd.pages_id = ip.pages_id
                                 and ip2c.pages_id = ip.pages_id
                                 and ip2c.categories_id = '0'
                                 and ipd.language_id = '" . (int)$languages_id . "'
                               order by ip.pages_sort_order, ipd.pages_title");

  if ((tep_db_num_rows($pages_categories_query) > 0) || (tep_db_num_rows($pages_query) > 0)) {
    while ($pages_categories = tep_db_fetch_array($pages_categories_query)) {
      echo '<h4 class="pagesTitle">' . '<a href="' . tep_href_link(FILENAME_PAGES, 'cID=' . (int)$pages_categories['categories_id']) . '">' . $pages_categories['categories_name'] . '</a>' . '</h4>' . "\n";
      echo '<p class="pagesBlurb">' . $pages_categories['categories_description'] . '</p>' . "\n\n";
    }

    while ($pages = tep_db_fetch_array($pages_query)) {
      echo '<h4 class="pagesTitle">' . '<a href="' . tep_href_link(FILENAME_PAGES, 'pID=' . (int)$pages['pages_id']) . '">' . $pages['pages_title'] . '</a>' . '</h4>' . "\n";
      echo '<p class="pagesBlurb">' . $pages['pages_blurb'] . '</p>' . "\n\n";
    }
  } else {
    echo '<p>' . TEXT_NO_CATEGORIES . '</p>' . "\n\n";
  }
?>
    </td>
  </tr>
<?php
}
?>
</table>
<?php 
// RCI code start
echo $cre_RCI->get('pages', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>