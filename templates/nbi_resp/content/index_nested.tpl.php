<?php 
/*
  $Id: index_nested.tpl.php,v 1.2.0.0 2008/01/22 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// added for CDS CDpath support
$params = (isset($_SESSION['CDpath'])) ? '&CDpath=' . $_SESSION['CDpath'] : ''; 

    // Get the category name and description
    $category_query = tep_db_query("select cd.categories_name, cd.categories_heading_title, cd.categories_description, c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . $languages_id . "'");
    $category = tep_db_fetch_array($category_query);
               if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_heading_title'])) ) {
                 $heading_text_box = $category['categories_heading_title'];
               } else {
                 $heading_text_box = $category['categories_name'];
               }
?>
  <!-- Bof content.index_nested.tpl.php-->
  
  <h1><?php echo $heading_text_box;?></h1>
  
  <!--<div class="content">
	<div class="left">
		<?php if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_description'])) ) { 
			echo "<p>".$category['categories_description']."</p>";
		} ?>
	</div>
	<div class="right">
		<?php echo tep_image(DIR_WS_IMAGES . $category['categories_image'], $category['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>
	</div>
  </div>-->
  
  
	<div class="product-grid">
<?php
    if (isset($cPath) && strpos('_', $cPath)) {
// check to see if there are deeper categories within the current category
      $category_links = array_reverse($cPath_array);
      for($i=0, $n=sizeof($category_links); $i<$n; $i++) {
        $categories_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
        $categories = tep_db_fetch_array($categories_query);
        if ($categories['total'] < 1) {
          // do nothing, go through the loop
        } else {
          $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
          break; // we've found the deepest category the customer is in
        }
      }
    } else {
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
    }

    $number_of_categories = tep_db_num_rows($categories_query);

    $rows = 0;
    while ($categories = tep_db_fetch_array($categories_query)) {
      $rows++;
      $cPath_new = tep_get_path($categories['categories_id'] . $params);
      $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';
      echo '<div><div class="name"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . $categories['categories_name'] . '</a></div><div class="price"> <span class="price-old"></span> <span class="price-new"></span> <br />
              <span class="price-tax"></span> </div><div class="image"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '</a></div><div class="cart">
              <a class="more" href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">More</a>
			  <a class="buy button" href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">Design now</a>
            </div></div>' . "\n";
    }

// needed for the new products module shown below
    $new_products_category_id = $current_category_id;
?>
    </div>

	<div class="content">
		<?php
	   if ((INCLUDE_MODULE_ONE == new_products.php) ||
		   (INCLUDE_MODULE_TWO == new_products.php) ||
		   (INCLUDE_MODULE_THREE == new_products.php) ||
		   (INCLUDE_MODULE_FOUR == new_products.php) ||
		   (INCLUDE_MODULE_FIVE == new_products.php) ||
			(INCLUDE_MODULE_SIX == new_products.php) ) { 
				include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS); 
	    }
		?>
	</div>
<!-- Eof content.index_nested.tpl.php-->