<?php
// RCI code start
echo $cre_RCI->get('affiliatebannersbanners', 'top');
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
          <td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'affiliate_links.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
        </tr>
      </table></td>
  <tr>

  <?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
  if (tep_db_num_rows($affiliate_banners_values)) {

    while ($affiliate_banners = tep_db_fetch_array($affiliate_banners_values)) {
      $prod_id = $affiliate_banners['affiliate_products_id'];
      $ban_id = $affiliate_banners['affiliate_banners_id'];
      switch (AFFILIATE_KIND_OF_BANNERS) {
        case 1: // Link to Products
          if ($prod_id < 1) {
            $link = '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $affiliate_banners['affiliate_banners_image'] . '" border="0" alt="' . $affiliate_banners['affiliate_banners_title'] . '"></a>';
            $link1 = '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $affiliate_banners['affiliate_banners_image'] . '" border="0" alt="' . $affiliate_banners['affiliate_banners_title'] . '"></a>';
      }
          break;
        case 2: // Link to Products
          if ($prod_id < 1) {
            $link = '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" border="0" alt="' . $affiliate_banners['affiliate_banners_title'] . '"></a>';
            $link1 = '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_DEFAULT . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" border="0" alt="' . $affiliate_banners['affiliate_banners_title'] . '"></a>';
      }
          break;
      }
    
if (AFFILIATE_KIND_OF_BANNERS == '1'){
$text_banner =  TEXT_AFFILIATE_BANNER_1 ;
} else if (AFFILIATE_KIND_OF_BANNERS == '2') {
$text_banner = TEXT_AFFILIATE_BANNER_2 ;
}
?>
  <tr>
    <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td class="infoBoxHeading"><?php echo TEXT_AFFILIATE_NAME . ' ' . $affiliate_banners['affiliate_banners_title']; ?></td>
        </tr>
        <tr>
          <td class="main" align="center"><br>
            <?php echo $link1;   if (!file_exists( DIR_FS_CATALOG . DIR_WS_IMAGES . $affiliate_banners['affiliate_banners_image'])) { ?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="headerError">
                <td class="headerError"><?php echo 'Image Missing - Click to <a href="' . tep_href_link(FILENAME_AFFILIATE_CONTACT,'enquiry=Image missing for Affiliate Banner ID ' . $ban_id,'SSL') . '">Inform Webmaster</a>'; ?></td>
              </tr>
            </table>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td class="smallText"><?php echo TEXT_AFFILIATE_INFO . '  [' . $text_banner . ']'; ?></td>
        </tr>
        <tr>
          <td class="smallText" align="center"><?php echo tep_draw_textarea_field('affiliate_banner', 'soft', '120', '4', $link1); ?></td>
        </tr>
      </table></td>
  </tr>
  <?php
}
  }
?>
  <tr>
    <td><?php echo tep_draw_form('login', tep_href_link(FILENAME_AFFILIATE, 'action=process', 'SSL')); ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD



//}
// RCI code start
echo $cre_RCI->get('affiliatebannersbanners', 'menu');
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
echo $cre_RCI->get('affiliatebannersbanners', 'bottom');
// RCI code eof
?>
