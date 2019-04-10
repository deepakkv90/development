<?php
/*
  $Id: product_listing_col.php,v 1.1.1.1 2004/03/04 23:41:11 ccwjr Exp $
*/
//declare variables and initialize
// added for CDS CDpath support
$params = (isset($_SESSION['CDpath'])) ? '&CDpath=' . $_SESSION['CDpath'] : ''; 
$row = 0;
$column = 0;

  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');

  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
<!--product-listin-col -->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php
  }
  ?>
  
  <div class="product-grid">
  
  <?php
  $list_box_contents = array();

  if ($listing_split->number_of_rows > 0) {
    $listing_query = tep_db_query($listing_split->sql_query);

    $row = 0;
    $column = 0;
    $no_of_listings = tep_db_num_rows($listing_query);

    while ($_listing = tep_db_fetch_array($listing_query)) {
      $listing[] = $_listing;
    }

    for ($x = 0; $x < $no_of_listings; $x++) {
      $product_contents = "";
      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        $lc_align = '';
        $lc_text = '';
        switch ($column_list[$col]) {
          case 'PRODUCT_LIST_MODEL':
            $lc_align = '';
            $lc_text = '&nbsp;' . $listing[$x]['products_model'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_NAME':
            $lc_align = '';
            if (isset($_GET['manufacturers_id'])) {
              $lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $_GET['manufacturers_id'] . '&amp;products_id=' . $listing[$x]['products_id'] . $params) . '">' . $listing[$x]['products_name'] . '</a>';
            } else {
              $lc_text = '<div class="name"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&amp;' : '') . 'products_id=' . $listing[$x]['products_id'] . $params) . '">' . $listing[$x]['products_name'] . '</a></div>';
            }
            if (PRODUCT_BLURB == 'true') {
              $products_blurb_query = tep_db_query("select products_blurb from ".TABLE_PRODUCTS_DESCRIPTION." where products_id = ".$listing[$x]['products_id']." and language_id = ".$languages_id."");
              $products_blurb_data = tep_db_fetch_array($products_blurb_query);
              if (tep_not_null($products_blurb_data['products_blurb'])) {
                $lc_text .= '<br><div class="blurbs">' . $products_blurb_data['products_blurb'] . '</div>';
              } else {
                $lc_text .= '<br>';
              }
            } else {
              $lc_text .= '<br>';
            }
            break;
          case 'PRODUCT_LIST_MANUFACTURER':
            $lc_align = '';
            $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing[$x]['manufacturers_id'] . $params) . '">' . $listing[$x]['manufacturers_name'] . '</a>&nbsp;';
            break;
          case 'PRODUCT_LIST_PRICE':
            $lc_align = 'right';
            $pf->loadProduct($listing[$x]['products_id'],$languages_id);
            $lc_text = '<div class="price"><span class="price-new">'.$pf->getPriceStringShort().'</span></div>';
            break;
          case 'PRODUCT_LIST_QUANTITY':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing[$x]['products_quantity'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_WEIGHT':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing[$x]['products_weight'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_IMAGE':
            $lc_align = 'center';
            if (isset($_GET['manufacturers_id'])) {
              $lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $_GET['manufacturers_id'] . '&amp;products_id=' . $listing[$x]['products_id'] . $params) . '">' . tep_image(DIR_WS_IMAGES . $listing[$x]['products_image'], $listing[$x]['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
            } else {
              $lc_text = '<div class="image"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&amp;' : '') . 'products_id=' . $listing[$x]['products_id'] . $params) . '">' . tep_image(DIR_WS_IMAGES . $listing[$x]['products_image'], $listing[$x]['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></div>';
            }
            break;
          case 'PRODUCT_LIST_DATE_EXPECTED':
              $duedate= str_replace("00:00:00", "" , $listing[$x]['products_date_available']);
                  $lc_align = 'center';
                  $lc_text = '&nbsp;' .  $duedate . '&nbsp;';
            break;
          case 'PRODUCT_LIST_BUY_NOW':
              $hide_add_to_cart = hide_add_to_cart();
              $lc_text = '';
              if ($hide_add_to_cart == 'false' && group_hide_show_prices() == 'true') {
                $lc_align = 'center';
                $lc_text = '<div class="cart"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action','cPath','products_id')) . 'action=buy_now&amp;products_id=' . $listing[$x]['products_id'] . '&amp;cPath=' . tep_get_product_path($listing[$x]['products_id']) . $params) . '">' . tep_template_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a></div>';
              }
              break;
        }
        $product_contents .= $lc_text;
      }

      echo "<div>".$product_contents."</div>";
    }
	
  } else {
    echo TEXT_NO_PRODUCTS;
  }
  ?>
  </div>
  <?php
  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php
  }
?>