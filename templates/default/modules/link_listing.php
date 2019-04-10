<?php
/*
  $Id: link_listing.php,v 1.1.1.1 2004/03/04 23:41:10 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class linkListingBox extends tableBox {
    function linkListingBox($contents) {
      $this->table_parameters = 'class="linkListing"';
      $this->tableBox($contents, true);
    }
  }

  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'l.links_id');

  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_LINKS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php
  }

  $list_box_contents = array();

  for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
    switch ($column_list[$col]) {
      case 'LINK_LIST_TITLE':
        $lc_text = TABLE_HEADING_LINKS_TITLE;
        $lc_align = '';
        break;
      case 'LINK_LIST_URL':
        $lc_text = TABLE_HEADING_LINKS_URL;
        $lc_align = '';
        break;
      case 'LINK_LIST_IMAGE':
        $lc_text = TABLE_HEADING_LINKS_IMAGE;
        $lc_align = 'center';
        break;
      case 'LINK_LIST_DESCRIPTION':
        $lc_text = TABLE_HEADING_LINKS_DESCRIPTION;
        $lc_align = 'center';
        break;
      case 'LINK_LIST_COUNT':
        $lc_text = TABLE_HEADING_LINKS_COUNT;
        $lc_align = '';
        break;
    }

    if ($column_list[$col] != 'LINK_LIST_IMAGE') {
      $lc_text = tep_create_sort_heading($_GET['sort'], $col+1, $lc_text);
    }

    $list_box_contents[0][] = array('align' => $lc_align,
                                    'params' => 'class="linkListing-heading"',
                                    'text' => '&nbsp;' . $lc_text . '&nbsp;');
  }

  if ($listing_split->number_of_rows > 0) {
    $rows = 0;
    $listing_query = tep_db_query($listing_split->sql_query);
    while ($listing = tep_db_fetch_array($listing_query)) {
      $rows++;

      if (($rows/2) == floor($rows/2)) {
        $list_box_contents[] = array('params' => 'class="linkListing-even"');
      } else {
        $list_box_contents[] = array('params' => 'class="linkListing-odd"');
      }

      $cur_row = sizeof($list_box_contents) - 1;

      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        $lc_align = '';

        switch ($column_list[$col]) {
          case 'LINK_LIST_TITLE':
            $lc_align = '';
            $lc_text = $listing['links_title'];
            break;
          case 'LINK_LIST_URL':
            $lc_align = '';
            $lc_text = '<a href="' . tep_get_links_url($listing['links_id']) . '" target="_blank">' . $listing['links_url'] . '</a>';
            break;
          case 'LINK_LIST_DESCRIPTION':
            $lc_align = '';
            $lc_text = $listing['links_description'];
            break;
          case 'LINK_LIST_IMAGE':
            $lc_align = 'center';
            if (tep_not_null($listing['links_image_url'])) {
              $lc_text = '<a href="' . tep_get_links_url($listing['links_id']) . '" target="_blank">' . tep_links_image($listing['links_image_url'], $listing['links_title'], LINKS_IMAGE_WIDTH, LINKS_IMAGE_HEIGHT) . '</a>';
            } else {
              $lc_text = '<a href="' . tep_get_links_url($listing['links_id']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . 'no_picture.gif', $listing['links_title'], LINKS_IMAGE_WIDTH, LINKS_IMAGE_HEIGHT) . '</a>';
            }
            break;
          case 'LINK_LIST_COUNT':
            $lc_align = '';
            $lc_text = $listing['links_clicked'];
            break;
        }

        $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                               'params' => 'class="linkListing-data"',
                                               'text'  => $lc_text);
      }
    }

    new linkListingBox($list_box_contents);
  } else {
    $list_box_contents = array();

    $list_box_contents[0] = array('params' => 'class="linkListing-odd"');
    $list_box_contents[0][] = array('params' => 'class="linkListing-data"',
                                   'text' => TEXT_NO_LINKS);

    new linkListingBox($list_box_contents);
  }

  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_LINKS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php
  }
?>
