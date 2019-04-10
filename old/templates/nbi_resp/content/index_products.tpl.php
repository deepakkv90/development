<?php
/*
  $Id: index_products.tpl.php,v 1.2.0.0 2008/01/22 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('indexproducts', 'top');
// RCI code eof
// added for CDS CDpath support
$params = (isset($_SESSION['CDpath'])) ? '&CDpath=' . $_SESSION['CDpath'] : ''; 
  
      // Get the category name and description
	  
    $category_query = tep_db_query("select cd.categories_name, cd.categories_heading_title, cd.categories_description, c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where  c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . $languages_id . "'");
    $category = tep_db_fetch_array($category_query);
               if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_heading_title'])) ) {
                 $heading_text_box = $category['categories_heading_title'];
               } else {
                 $heading_text_box = $category['categories_name'];
               }
    ?>
    <!-- bof content.index_products.tpl.php-->
	<h1 class="cart-title"><?php echo $heading_text_box;?></h1>
	
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><td>
<?php
// optional Product List Filter
    if (PRODUCT_LIST_FILTER > 0) {
      if (isset($HTTP_GET_VARS['manufacturers_id'])) {
        $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name
                           from " . TABLE_PRODUCTS . " p,
                                " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c,
                                " . TABLE_CATEGORIES . " c,
                                " . TABLE_CATEGORIES_DESCRIPTION . " cd
                           where p2c.categories_id = c.categories_id
                             and p.products_id = p2c.products_id
                             and cd.categories_id = c.categories_id
                             and cd.language_id = '" . (int)$languages_id . "'
                             and p.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'
                             and p.products_status = '1'
                           order by cd.categories_name";
      } else {
        $filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
      }
      $filterlist_query = tep_db_query($filterlist_sql);
      if (tep_db_num_rows($filterlist_query) > 1) {
        echo '            <td align="center" class="main">' . tep_draw_form('filter', FILENAME_DEFAULT, 'get') . TEXT_SHOW . '&nbsp;';
        if (isset($HTTP_GET_VARS['manufacturers_id'])) {
          echo tep_draw_hidden_field('manufacturers_id', $HTTP_GET_VARS['manufacturers_id']);
          $options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));
        } else {
          echo tep_draw_hidden_field('cPath', $cPath);
          $options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));
        }
        echo tep_draw_hidden_field('sort', $HTTP_GET_VARS['sort']);
        while ($filterlist = tep_db_fetch_array($filterlist_query)) {
          $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);
        }
        echo tep_draw_pull_down_menu('filter_id', $options, (isset($HTTP_GET_VARS['filter_id']) ? $HTTP_GET_VARS['filter_id'] . $params : ''), 'onchange="this.form.submit()"');
        echo '</form></td>' . "\n";
      }
    }

// Get the right image for the top-right
    $image = DIR_WS_IMAGES . 'table_background_list.gif';
    if (isset($HTTP_GET_VARS['manufacturers_id'])) {
       $manufactures_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
       $manufactures = tep_db_fetch_array($manufactures_query);
       $heading_text_box =  $manufactures['manufacturers_name'];
      $image = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['manufacturers_image'];
    } elseif ($current_category_id) {
      $image = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['categories_image'];
    }
?>
            <?php /*?><div class="cat_image"><?php echo tep_image(DIR_WS_IMAGES . $image, $heading_text_box, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></div><?php */?></td>
          </tr>
    <?php if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_description'])) ) { ?>
    <tr>
            <td align="left" colspan="2" class="category_desc"><?php echo $category['categories_description']; ?></td>
    </tr>
    <?php } ?>
        </table>

      <!--manufacture list in index product.php-->
	



  <?php 
  
  //Product Listing Fix - Begin
  //decide which product listing to use
   if (PRODUCT_LIST_CONTENT_LISTING == 'column'){
  $listing_method = FILENAME_PRODUCT_LISTING_COL;
  } else {
  $listing_method = FILENAME_PRODUCT_LISTING;
  }
  //Then show product listing
  
  
  /*
  $other_categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)25 . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");*/
  
  $other_categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)255 . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
	
	$OPID = array();
	$CCID = (int)$current_category_id;
	while ($other_categories = tep_db_fetch_array($other_categories_query)) {
		$OPID[] = $other_categories['categories_id'];
	}
	if(!empty($CCID)){
		if(in_array($CCID,$OPID)){
     
			include(DIR_WS_MODULES . $listing_method);
		}
	}
  
  //Product Listing Fix - End

    // RCI code start
    echo $cre_RCI->get('indexproducts', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof
    ?>