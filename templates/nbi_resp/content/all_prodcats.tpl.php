<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('allprodcats', 'top');
// RCI code eof
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_default.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
       </tr>

<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>

<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td><table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr class="productListing-heading">
        <td align="left" class="productListing-heading"><?php echo HEADING_ALL_CATSUB; ?></td>
       <td align="center" class="productListing-heading"><?php echo HEADING_ALL_PRODUCTS; ?></td>

       </tr>
<?php

$language_code = (isset($HTTP_GET_VARS['language']) && tep_not_null($HTTP_GET_VARS['language'])) ? (int)$HTTP_GET_VARS['language'] : DEFAULT_LANGUAGE;

$included_categories_query = tep_db_query("SELECT c.categories_id, c.parent_id, cd.categories_name FROM " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd WHERE c.categories_id = cd.categories_id AND cd.language_id = FLOOR($languages_id)");

$inc_cat = array();
while ($included_categories = tep_db_fetch_array($included_categories_query)) {
  $inc_cat[] = array (
     'id' => $included_categories['categories_id'],
     'parent' => $included_categories['parent_id'],
     'name' => $included_categories['categories_name']);
  }
$cat_info = array();
for ($i=0; $i < sizeof($inc_cat); $i++)
  $cat_info[$inc_cat[$i]['id']] = array (
    'parent'=> $inc_cat[$i]['parent'],
    'name'  => $inc_cat[$i]['name'],
    'path'  => $inc_cat[$i]['id'],
    'link'  => '' );

for ($i=0; $i < sizeof($inc_cat); $i++) {
  $cat_id = $inc_cat[$i]['id'];
  while ($cat_info[$cat_id]['parent'] != 0){
    $cat_info[$inc_cat[$i]['id']]['path'] = $cat_info[$cat_id]['parent'] . '_' . $cat_info[$inc_cat[$i]['id']]['path'];
    $cat_id = $cat_info[$cat_id]['parent'];
    }
  $link_array = split('_', $cat_info[$inc_cat[$i]['id']] ['path']);
  for ($j=0; $j < sizeof($link_array); $j++) {
    $cat_info[$inc_cat[$i]['id']]['link'] .= '&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cat_info[$link_array[$j]]['path']) . '"><nobr>' . $cat_info[$link_array[$j]]['name'] . '</nobr></a>&nbsp;&raquo;&nbsp;';
    }
  }

$products_query = tep_db_query("SELECT p.products_id, pd.products_name, pc.categories_id FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " pc WHERE p.products_id = pd.products_id AND p.products_id = pc.products_id AND p.products_status = 1 AND pd.language_id = FLOOR($languages_id) ORDER BY pc.categories_id, pd.products_name");

while($products = tep_db_fetch_array($products_query)) {
  echo
"          <tr>\n" .
'           <td class="main">' . (($memory == $products['categories_id'])? '': $cat_info[$products['categories_id']]['link']) . "</td>\n" .
'           <td class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id'] . (($language_code == DEFAULT_LANGUAGE) ? '' : ('&amp;language=' . $language_code))) . '">' . $products['products_name'] . "</a></td>\n" .
"          </tr>\n";
  $memory = $products['categories_id'];
  }

?>
            </td>
          </tr>
         </table></td>
      </tr>
<?php
// RCI code start
echo $cre_RCI->get('allprodcats', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>
<?php 
// RCI code start
echo $cre_RCI->get('allprodcats', 'top');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>