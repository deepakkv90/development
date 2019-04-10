<?php
// RCI code start
echo $cre_RCI->get('affiliatesummary', 'top');
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'affiliate_summary.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>

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
            <td class="main"><?php echo TEXT_GREETING . $affiliate['affiliate_firstname'] . ' ' . $affiliate['affiliate_lastname'] . '<br>' . TEXT_AFFILIATE_ID . $affiliate_id; ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="infoboxheading"><?php echo TEXT_SUMMARY_TITLE; ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellpadding="4" cellspacing="2">
              <center>
                <tr>
                  <td width="35%" align="right" class="boxtext"><?php echo TEXT_IMPRESSIONS; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_AFFILIATE_HELP,'help_text=1') . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                  <td width="15%" class="boxtext"><?php echo $affiliate_impressions; ?></td>
                  <td width="35%" align="right" class="boxtext"><?php echo TEXT_VISITS; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_AFFILIATE_HELP,'help_text=2') . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                  <td width="15%" class="boxtext"><?php echo $affiliate_clickthroughs; ?></td>
                </tr>
                <tr>
                  <td width="35%" align="right" class="boxtext"><?php echo TEXT_TRANSACTIONS; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_AFFILIATE_HELP,'help_text=3') . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                  <td width="15%" class="boxtext"><?php echo $affiliate_transactions; ?></td>
                  <td width="35%" align="right" class="boxtext"><?php echo TEXT_CONVERSION; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_AFFILIATE_HELP,'help_text=4') . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                  <td width="15%" class="boxtext"><?php echo $affiliate_conversions;?></td>
                </tr>
                <tr>
                  <td width="35%" align="right" class="boxtext"><?php echo TEXT_AMOUNT; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_AFFILIATE_HELP,'help_text=5') . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                  <td width="15%" class="boxtext"><?php echo $currencies->display_price($affiliate_amount, ''); ?></td>
                  <td width="35%" align="right" class="boxtext"><?php echo TEXT_AVERAGE; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_AFFILIATE_HELP,'help_text=6') . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                  <td width="15%" class="boxtext"><?php echo $currencies->display_price($affiliate_average, ''); ?></td>
                </tr>
                <tr>
                  <td width="35%" align="right" class="boxtext"><?php echo TEXT_COMMISSION_RATE; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_AFFILIATE_HELP,'help_text=7') . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                  <td width="15%" class="boxtext"><?php echo tep_round($affiliate_percent, 2). '%'; ?></td>
                  <td width="35%" align="right" class="boxtext"><?php echo TEXT_COMMISSION; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_AFFILIATE_HELP,'help_text=8') . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                  <td width="15%" class="boxtext"><?php echo $currencies->display_price($affiliate_commission, ''); ?></td>
                </tr>
                <tr>
                  <td colspan="4"><?php echo tep_draw_separator(); ?></td>
                </tr>
                 <tr>
                  <td align="center" class="boxtext" colspan="4"><b><?php echo TEXT_SUMMARY; ?><b></td>
                </tr>
                <tr>
                  <td colspan="4"><?php echo tep_draw_separator(); ?></td>
                </tr>
              </center>
            </table></td>
          </tr>
<?php
// RCI code start
echo $cre_RCI->get('affiliatesummary', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="left"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS, '') . '">' . tep_template_image_button('button_affiliate_banners.gif', IMAGE_BANNERS) . '</a></td><td align="center"> <a href="' . tep_href_link(FILENAME_AFFILIATE_CLICKS, '') . '">' . tep_template_image_button('button_affiliate_clickthroughs.gif', IMAGE_CLICKTHROUGHS) . '</a></td><td align="right"> <a href="' . tep_href_link(FILENAME_AFFILIATE_SALES, '','SSL') . '">' . tep_template_image_button('button_affiliate_sales.gif', IMAGE_SALES) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
        </table>
<?php
// RCI code start
echo $cre_RCI->get('affiliatesummary', 'bottom');
// RCI code eof
?>