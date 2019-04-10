<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('allprodmanf', 'top');
// RCI code eof
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
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

$included_manufacturers_query = tep_db_query("SELECT m.manufacturers_id, mi.manufacturers_url, m.manufacturers_name FROM " . TABLE_MANUFACTURERS . " m, " . TABLE_MANUFACTURERS_INFO . " mi WHERE m.manufacturers_id = mi.manufacturers_id AND mi.languages_id = FLOOR($languages_id) order by m.manufacturers_name");

$inc_manf = array();
while ($included_manufacturers = tep_db_fetch_array($included_manufacturers_query)) {
  $inc_manf[] = array (
     'id' => $included_manufacturers['manufacturers_id'],
     'url' => $included_manufacturers['manufacturers_url'],
     'name' => $included_manufacturers['manufacturers_name']);
  }
$manf_info = array();
for ($i=0; $i < sizeof($inc_manf); $i++)
  $manf_info[$inc_manf[$i]['id']] = array (
    'name'  => $inc_manf[$i]['name'],
    'path'  => $inc_manf[$i]['url'],
    'link'  => '' );

for ($i=0; $i < sizeof($inc_manf); $i++) {
  $man_id = $inc_manf[$i]['id'];

  $link_array = split('_', $manf_info[$inc_manf[$i]['id']] ['path']);
  for ($j=0; $j < sizeof($link_array); $j++) {
    $manf_info[$inc_manf[$i]['id']]['link'] .= '&nbsp;<a href="' . $manf_info[$inc_manf[$i]['id']] ['path'] . '"><nobr>' . $manf_info[$inc_manf[$i]['id']] ['name'] . '</nobr></a>&nbsp;&raquo;&nbsp;';
    }
  }

$products_query = tep_db_query("SELECT p.products_id, pd.products_name, p.manufacturers_id FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd WHERE p.manufacturers_id = manufacturers_id AND p.products_id = pd.products_id AND p.products_status = 1 AND pd.language_id = FLOOR($languages_id) ORDER BY p.manufacturers_id, pd.products_name");

while($products = tep_db_fetch_array($products_query)) {
  echo
"          <tr>\n" .
'           <td class="main">' . (($memory == $products['manufacturers_id'])? '': $manf_info[$products['manufacturers_id']]['link']) . "</td>\n" .
'           <td class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id'] . (($language_code == DEFAULT_LANGUAGE) ? '' : ('&amp;language=' . $language_code))) . '">' . $products['products_name'] . "</a></td>\n" .
"          </tr>\n";
  $memory = $products['manufacturers_id'];
  }

?>
            </td>
          </tr>
         </table></td>
      </tr>
<?php
// RCI code start
echo $cre_RCI->get('allprodmanf', 'menu');
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
echo $cre_RCI->get('allprodmanf', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>