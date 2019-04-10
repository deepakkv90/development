<?php
/*
  $Id: CDS_categories.php,v 1.0a 2008/07/31 10:37:00 Eversun $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

//------------------------------------------------------------------------------------------------------
// PARAMETERS
//------------------------------------------------------------------------------------------------------

$item_column_number = 3;    // range of 1 to 9
$item_title_on_newline = true;  // true or false

// for item and subcategory options, suugest that you just put in CSS code
// you can also just define a class and then change it in a template addon like BTS
$item_div_options = 'style="text-align:center;font-weight:bold;font-size:larger;margin-top:5px;"';
$item_subcategories_options = '';

// error checking on parameters
if($item_column_number < 1) {
  $item_column_number = 1;
}
if($item_column_number > 9) {
  $item_column_number = 9;
}
if($item_title_on_newline) {
  $item_separator = '<br>';
} else {
  $item_separator = '&nbsp;';
}

?>
<!-- main_CDS_categories //-->
          <tr>
            <td>
<?php
//////////
// Display box heading
//////////
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text'  => BOX_HEADING_CATEGORIES_MAIN_PAGE);
  new contentBoxHeading($info_box_contents, '');


//////////
// Get categories list
//////////

$query_cat = "select c.categories_id, cd.categories_name, c.categories_image,c.categories_parent_id, c.categories_url_override, c.categories_url_override_target 
         from  " . TABLE_PAGES_CATEGORIES . " c , 
               " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " cd 
        where 
              c.categories_parent_id = '0' 
            and c.categories_id = cd.categories_id 
            and cd.language_id='" . $_SESSION['languages_id'] ."' 
            order by c.categories_sort_order, cd.categories_name";

$categories_query_cat = tep_db_query($query_cat);

//////////
// Display box contents
//////////

$info_box_contents = array();


$row = 0;
$col = 0;
while ($categories_cat = tep_db_fetch_array($categories_query_cat)) {
  if ($categories_cat['categories_parent_id'] == 0) {
      if($categories_cat['categories_url_override'] != ''){
          $override_url = $categories_cat['categories_url_override'];
      } else {
          $override_url = tep_href_link(FILENAME_PAGES, 'CDpath=' . $categories_cat['categories_id']);
      }
    $info_box_contents[$row][$col] = array('align' => 'left',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<div '. $item_div_options . '><a href="' . $override_url . '"' . (($categories_cat['categories_url_override_target'] != '') ? ' target="' . $categories_cat['categories_url_override_target'] . '"' : '') . '>' . tep_image(DIR_WS_IMAGES . $categories_cat['categories_image'], $categories_cat['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '<br>' . $categories_cat['categories_name'] . '</a></DIV>');

      // determine the column position to see if we need to go to a new row
    $col ++;
    if ($col > ($item_column_number - 1)) {
      $col = 0;
      $row ++;
    } //if ($col > ($number_of_columns - 1))
  } //if ($categories['categories_parent_id'] == 0)
} // while ($categories = tep_db_fetch_array($categories_query_cat))

//output the contents
new contentBox($info_box_contents, true, true);
if (TEMPLATE_INCLUDE_FOOTER =='true'){
     $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left',
                                    'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                  );
 new contentBoxFooter($info_box_contents);
 } 

?>

<!-- main_CDS_categories_eof //-->
