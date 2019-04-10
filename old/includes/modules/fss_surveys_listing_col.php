<?php
/*
  $Id: fss_forms_listing_col.php,v 1.1.1.1 2004/03/04 23:41:11 ccwjr Exp $
*/

  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'ff.forms_id');

  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
<!--product-listin-col -->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_SURVEYS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php
  }
  $list_box_contents = array();

  if ($listing_split->number_of_rows > 0) {
    $listing_query = tep_db_query($listing_split->sql_query);

    $row = 0;
    $column = 0;
    $no_of_listings = tep_db_num_rows($listing_query);

    while ($_listing = tep_db_fetch_array($listing_query)) {
      $listing[] = $_listing;
    }
    $rows = 0;
    for ($x = 0; $x < $no_of_listings; $x++) {
      $rows++;
      $form_contents = array();
      if (tep_fss_is_completed_survey($customer_id, $listing[$x]['forms_id'])) {
        $link = tep_href_link(FILENAME_FSS_SURVEYS_INFO, (isset($fPath) ? 'fPath=' . $fPath . '&' : '') . 'forms_id=' . $listing[$x]['forms_id']);
      } else {
        $link = tep_href_link(FILENAME_FSS_FORMS_DETAIL, (isset($fPath) ? 'fPath=' . $fPath . '&' : '') . 'forms_id=' . $listing[$x]['forms_id']);
      }
      $lc_align = '';
      $lc_text = '&nbsp;<a href="' . $link . '">' . $listing[$x]['forms_name'] . '</a>&nbsp;';      
        $form_contents[] = $lc_text;

      $lc_text = implode('<br>', $form_contents);
      $list_box_contents[$row][$column] = array('align' => 'center',
                                                'params' => 'class="productListing-data"',
                                                'text'  => $lc_text);

      $column ++;
      if ($column >= COLUMN_COUNT) {
        $row ++;
        $column = 0;
      }
    }

    new productListingBox($list_box_contents);
  }
  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_SURVEYS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php
  }
?>