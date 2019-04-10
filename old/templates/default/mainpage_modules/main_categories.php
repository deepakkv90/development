<?php
/*
  $Id: main_categories.php,v 1.0a 2002/08/01 10:37:00 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com/

  Copyright (c) 2002 Barreto
  Gustavo Barreto <gustavo@barreto.net>
  http://www.barreto.net/

  Based on: all_categories.php Ver. 1.6 by Christian Lescuyer

  History: 1.0 Creation
     1.0a Correction: Extra Carriage Returns
     1.1  added parameters to change display options -- mdt

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

//------------------------------------------------------------------------------------------------------
// CODE - do not change below here
//------------------------------------------------------------------------------------------------------

// error checking on parameters
if($item_column_number < 1)
{
  $item_column_number = 1;
}
if($item_column_number > 9)
{
  $item_column_number = 9;
}
if($item_title_on_newline)
{
  $item_separator = '<br>';
} else {
  $item_separator = '&nbsp;';
}

// preorder_mc tree traversal
  function preorder_mc($cid_cat, $level_cat, $foo_cat, $cpath_cat)
  {
    global $categories_string_cat, $_GET;

// Display link
    if ($cid_cat != 0) {
      for ($i=0; $i<$level_cat; $i++)
        $categories_string_cat .=  '&nbsp;&nbsp;';
      $categories_string_cat .= '<a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath
=' . $cpath_cat . $cid_cat) . '">';
// 1.6 Are we on the "path" to selected category?
      $bold = strstr($_GET['cPath'], $cpath_cat . $cid_cat . '_') || $_GET['cPath'] == $cpath_cat . $cid_cat;
// 1.6 If yes, use <b>
      if ($bold)
        $categories_string_cat .=  '<b>';
      $categories_string_cat .=  $foo_cat[$cid_cat]['name'];
      if ($bold)
        $categories_string_cat .=  '</b>';
      $categories_string_cat .=  '</a>';
// 1.4 SHOW_COUNTS is 'true' or 'false', not true or false
      if (SHOW_COUNTS == 'true') {
        $products_in_category = tep_count_products_in_category($cid_cat);
        if ($products_in_category > 0) {
          $categories_string_cat .= '&nbsp;(' . $products_in_category . ')';
        }
      }
      $categories_string_cat .= '<br>';
    }

// Traverse category tree
    if (is_array($foo_cat)) {
      foreach ($foo_cat as $key => $value) {
        if ($foo_cat[$key]['parent'] == $cid_cat) {
          preorder_mc($key, $level_cat+1, $foo_cat, ($level_cat != 0 ? $cpath_cat . $cid_cat . '_' : ''));
        }
      }
    }
  }

?>
<!-- main_categories //-->
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

$query_cat = "select 
  c.categories_id, 
  cd.categories_name, 
  c.categories_image,
  c.parent_id from 
  " . TABLE_CATEGORIES . " c,
  " . TABLE_CATEGORIES_DESCRIPTION . " cd 
  where 
  c.parent_id = '0' 
 and c.categories_id = cd.categories_id 
 and cd.language_id='" . $languages_id ."' 
 and c.products_group_access like '%". $customer_group_id."%'
 order by sort_order, cd.categories_name";

  $categories_query_cat = tep_db_query($query_cat);


// Initiate tree traverse
$categories_string_cat = '';
preorder_mc(0, 0, $foo_cat, '');

//////////
// Display box contents
//////////

$info_box_contents = array();

$row = 0;
$col = 0;
while ($categories_cat = tep_db_fetch_array($categories_query_cat))
{
  if ($categories_cat['parent_id'] == 0)
    {
      $cpath_cat_new = tep_get_path($categories_cat['categories_id']);
      $text_subcategories = '';
      $subcategories_query_cat = tep_db_query($query_cat);
      while ($subcategories_cat = tep_db_fetch_array($subcategories_query_cat))
      {
        if ($subcategories_cat['parent_id'] == $categories_cat['categories_id'])
      {
                $cPath_new_sub = "cPath="  . $categories_cat['categories_id'] . "_" . $subcategories_cat['categories_id'];
                $text_subcategories .= '• <a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new_sub, 'NONSSL') . '">';
                $text_subcategories .= $subcategories_cat['categories_name'] . '</a>' . " ";

          } // if ($subcategories['parent_id'] == $categories['categories_id'])

      } // while ($subcategories = tep_db_fetch_array($subcategories_query))

    $info_box_contents[$row][$col] = array('align' => 'left',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<div '. $item_div_options . '><a href="' . tep_href_link(FILENAME_DEFAULT, $cpath_cat_new) . '">' . tep_image(DIR_WS_IMAGES . $categories_cat['categories_image'], $categories_cat['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '<br>' . $categories_cat['categories_name'] . '</a></DIV>');

      // determine the column position to see if we need to go to a new row
      $col ++;
      if ($col > ($item_column_number - 1))
      {
          $col = 0;
          $row ++;

      } //if ($col > ($number_of_columns - 1))

    } //if ($categories['parent_id'] == 0)

} // while ($categories = tep_db_fetch_array($categories_query))

//output the contents
  new contentBox($info_box_contents, true, true);
if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){ 
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
  new contentBoxFooter($info_box_contents);
  }

?>

<!-- main_categories_eof //-->
