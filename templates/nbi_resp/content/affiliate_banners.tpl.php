<?php
// RCI code start
echo $cre_RCI->get('affiliatebanners', 'top');
// RCI code eof
?>

<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
  <tr>
    <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          <td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
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
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
        <tr>
          <td class="main"align="center"><?php echo TEXT_INFORMATION_BANNERS_BANNERS; ?></td>
        </tr>
        <tr>
          <td class="infoBoxHeading" align="center"><?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER . ' ' . $affiliate_banners['affiliate_banners_title']; ?></td>
        </tr>
        <tr>
          <td class="smallText" align="center"><?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER_INFO . tep_draw_form('individual_banner', tep_href_link(FILENAME_AFFILIATE_BANNERS) ) . "\n" . tep_draw_input_field('individual_banner_id', '', 'size="5"') . "&nbsp;&nbsp;" . tep_template_image_submit('button_affiliate_build_a_link.gif', IMAGE_BUTTON_BUILD_A_LINK); ?>
            </form></td>
        </tr>
        <tr>
          <td class="smallText" align="center"><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_AFFILIATE_VALIDPRODUCTS) . '\')"><b>' . TEXT_AFFILIATE_VALIDPRODUCTS . '</b></a>'; ?>&nbsp;&nbsp;<?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER_VIEW;?><br>
            <?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER_HELP;?></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
        <?php
  if (tep_not_null($HTTP_POST_VARS['individual_banner_id']) || tep_not_null($HTTP_GET_VARS['individual_banner_id'])) {

    if (tep_not_null($HTTP_POST_VARS['individual_banner_id'])) $individual_banner_id = (int)$HTTP_POST_VARS['individual_banner_id'];

    if ($HTTP_GET_VARS['individual_banner_id']) $individual_banner_id = (int)$HTTP_GET_VARS['individual_banner_id'];
    $affiliate_pbanners_values = tep_db_query("select p.products_image, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $individual_banner_id . "' and pd.products_id = '" . $individual_banner_id . "' and p.products_status = '1' and pd.language_id = '" . $languages_id . "'");

    while ($affiliate_pbanners = tep_db_fetch_array($affiliate_pbanners_values)) {
      $product_image = $affiliate_pbanners['products_image'];


      switch (AFFILIATE_KIND_OF_BANNERS) {
        case 1:
          $link = '<a href="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&amp;products_id=' . $individual_banner_id . '&amp;affiliate_banner_id=1" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . DIR_WS_IMAGES . $product_image . '" border="0" alt="' . $affiliate_pbanners['products_name'] . '"></a>';
          break;
        case 2: // Link to Products
          $link = '<a href="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&amp;products_id=' . $individual_banner_id . '&amp;affiliate_banner_id=1" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&amp;affiliate_pbanner_id=' . $individual_banner_id . '" border="0" alt="' . $affiliate_pbanners['products_name'] . '"></a>';
          break;
      }
    }
?>
        <tr>
          <td class="smallText" align="center"><br>
            <?php echo $link; ?></td>
        </tr>
        <tr>
          <td class="smallText" align="center"><?php echo TEXT_AFFILIATE_INFO; ?></td>
        </tr>
        <tr>
          <td align="center"><?php echo tep_draw_textarea_field('affiliate_banner', 'soft', '120', '5', $link); ?></td>
        </tr>
        <?php
  }
?>
        <?php
  if (tep_db_num_rows($affiliate_banners_values)) {

    while ($affiliate_banners = tep_db_fetch_array($affiliate_banners_values)) {
      $affiliate_products_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $affiliate_banners['affiliate_products_id'] . "' and language_id = '" . $languages_id . "'");
      $affiliate_products = tep_db_fetch_array($affiliate_products_query);
      $prod_id = $affiliate_banners['affiliate_products_id'];
      $ban_id = $affiliate_banners['affiliate_banners_id'];
      $cat_id = $affiliate_banners['affiliate_category_id'];
    //$ab_status = $affiliate_banners['affiliate_status'];



      switch (AFFILIATE_KIND_OF_BANNERS) {
        case 1: // Link to Products
          if ($prod_id > 0) {
            $link = '<a href="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&amp;products_id=' . $prod_id . '&amp;affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . DIR_WS_IMAGES . ($affiliate_banners['affiliate_banners_image'] != '' ?  $affiliate_banners['affiliate_banners_image'] : tep_get_products_image($affiliate_banners['affiliate_products_id']) ) . '" border="0" alt="' . $affiliate_products['products_name'] . '"></a>';
          } else { // generic_link
            $link = '<a href="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&cPath=' . $cat_id . '&amp;affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . DIR_WS_IMAGES . $affiliate_banners['affiliate_banners_image'] . '" border="0" alt="' . $affiliate_banners['affiliate_banners_title'] . '"></a>';
          }
          break;
        case 2: // Link to Products
          if ($prod_id > 0) {
            $link = '<a href="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $affiliate_id . '&amp;products_id=' . $prod_id . '&amp;affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&amp;affiliate_banner_id=' . $ban_id . '" border="0" alt="' . $affiliate_products['products_name'] . '"></a>';
          } else { // generic_link
            $link = '<a href="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&cPath=' . $cat_id . '&amp;affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&amp;affiliate_banner_id=' . $ban_id . '" border="0" alt="' . $affiliate_banners['affiliate_banners_title'] . '"></a>';
          }
          break;
      }

if ($prod_id > 0){
$test_affiliate_name = TEXT_AFFILIATE_NAME_PROD ;
}else{
$test_affiliate_name = TEXT_AFFILIATE_NAME_CAT ;
}
if ( $cat_id != 0 || $prod_id != 0) {
?>
        <tr>
          <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="infoBoxHeading"><?php echo $test_affiliate_name . ' ' . $affiliate_banners['affiliate_banners_title']; ?></td>
              </tr>
              <tr>
                <td class="smallText" align="center"><br><?php echo $link; ?></td>
              </tr>
              <tr>
                <td class="smallText"><?php echo TEXT_AFFILIATE_INFO; ?></td>
              </tr>
              <tr>
                <td class="smallText" align="center"><?php echo tep_draw_textarea_field('affiliate_banner', 'soft', '120', '4', $link); ?></td>
              </tr>
            </table></td>
        </tr>
        <?php
  }
  }
      }
  ?>
        <?php
// RCI code start
echo $cre_RCI->get('affiliatebanners', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
        </form>
        
      </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_CENTRAL,'','SSL') . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<?php
// RCI code start
echo $cre_RCI->get('affiliatebanners', 'bottom');
// RCI code eof
?>
