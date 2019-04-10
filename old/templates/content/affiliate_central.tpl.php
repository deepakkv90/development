<?php
// RCI code start
echo $cre_RCI->get('affiliatecentral', 'top');
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
      </tr>

<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}


  if ($messageStack->size('account') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('account'); ?></td>
      </tr>
<?php
}


if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_GREETING . $affiliate['affiliate_firstname'] . ' ' . $affiliate['affiliate_lastname'] . '<br>' . TEXT_AFFILIATE_ID . $affiliate_id; ?>
            <br>
            <img src="images/arrow_red.gif" border="0" alt="" width="12" height="10"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_LOGOUT, '', 'SSL') . '">'. TEXT_AFFILIATE_LOGOUT . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><center><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="infoboxheading"><?php echo TEXT_SUMMARY_TITLE_TODATE; ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellpadding="4" cellspacing="2">
              <tr>
                <td width="35%" align="right" class="boxtext"><?php echo TEXT_IMPRESSIONS; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_AFFILIATE_HELP,'help_text=1') . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                <td width="15%" class="boxtext"><?php echo $affiliate_impressions; ?></td>
                <td width="35%" align="right" class="boxtext"><?php echo TEXT_VISITS; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_AFFILIATE_HELP,'help_text=2') . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                <td width="15%" class="boxtext"><?php echo $affiliate_clickthroughs; ?></td>
              </tr>
              <tr>
                 <td align="right" class="boxtext"><?php echo TEXT_CLICKTHROUGH_RATE; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_AFFILIATE_HELP,'help_text=8') . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                 <td class="boxtext"><?php echo  $currencies->display_price(AFFILIATE_PAY_PER_CLICK, ''); ?></td>
                 <td align="right" class="boxtext"><?php echo TEXT_PAYPERSALE_RATE; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_AFFILIATE_HELP,'help_text=9') . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                 <td class="boxtext"><?php echo  $currencies->display_price(AFFILIATE_PAYMENT, ''); ?></td>
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
                <td width="35%" align="right" class="boxtext"><?php echo TEXT_COMMISSION; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_AFFILIATE_HELP,'help_text=10') . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                <td width="15%" class="boxtext"><?php echo $currencies->display_price($affiliate_commission, ''); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="10"></td>
          </tr>    
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="infoboxheading"><?php echo TEXT_SUMMARY_TITLE_PENDING; ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellpadding="4" cellspacing="2">
              <tr>
                <td width="35%" align="right" class="boxtext"><?php echo TEXT_TRANSACTIONS; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_AFFILIATE_HELP,'help_text=3') . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                <td width="15%" class="boxtext"><?php echo $affiliate_pending_transactions; ?></td>
                <td width="35%" align="right" class="boxtext"><?php echo TEXT_AMOUNT; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_AFFILIATE_HELP,'help_text=5') . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                <td width="15%" class="boxtext"><?php echo $currencies->display_price($affiliate_pending_amount, ''); ?></td>
              </tr>
              <tr>
                <td width="35%" align="right" class="boxtext"><?php echo TEXT_COMMISSION; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_AFFILIATE_HELP,'help_text=10') . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                <td width="15%" class="boxtext"><?php echo $currencies->display_price($affiliate_pending_commission, ''); ?></td>
                <td width="35%" align="right" class="boxtext"><?php echo TEXT_AVERAGE; ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_AFFILIATE_HELP,'help_text=6') . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                <td width="15%" class="boxtext"><?php echo $currencies->display_price($affiliate_pending_average, ''); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td align="center" class="boxtext" colspan="4"><b><?php echo TEXT_SUMMARY; ?><b></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="10"></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_SUMMARY, '', 'SSL'). '">' . TEXT_AFFILIATE_SUMMARY . '</a>';?></b></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                    <td width="60"><img src="images/affiliate_account.gif" border="0" alt="" width="60" height="60"></td>
                    <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="main" width="50%"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_ACCOUNT, '', 'SSL'). '">' . TEXT_AFFILIATE_ACCOUNT . '</a>';?></td>
                        <td class="main" width="50%"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_NEWSLETTER, '', 'SSL'). '">' . TEXT_AFFILIATE_NEWSLETTER . '</a>';?></td>
                      </tr>
                      <tr>
                        <td class="main" width="50%"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_PASSWORD, '', 'SSL'). '">' . TEXT_AFFILIATE_PASSWORD . '</a>';?></td>
                        <td class="main" width="50%"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_NEWS, '', 'SSL'). '">' . TEXT_AFFILIATE_NEWS . '</a>';?></td>
                      </tr>
                    </table></td>
                    <td width="10" align="right"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="10"></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo TEXT_AFFILIATE_BANNERS ;?></b></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                    <td width="60"><img src="images/affiliate_links.gif" border="0" alt="" width="60" height="60"></td>
                    <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="main" width="50%"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_BANNERS, '', 'SSL'). '">' . TEXT_AFFILIATE_BANNERS_BANNERS . '</a>';?></td>
                        <td class="main" width="50%"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS, '', 'SSL'). '">' . TEXT_AFFILIATE_BANNERS . '</a>';?></td>
                      </tr>
                      <tr>
                        <td class="main" width="50%"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD, '', 'SSL'). '">' . TEXT_AFFILIATE_BANNERS_BUILD . '</a>';?></td>
                        <td class="main" width="50%"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD_CAT, '', 'SSL'). '">' . TEXT_AFFILIATE_BANNERS_CAT . '</a>';?></td>
                      </tr>
                      <tr>
                        <td class="main" width="50%"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_CATEGORY, '', 'SSL'). '">' . TEXT_AFFILIATE_BANNERS_CATEGORY . '</a>';?></td>
                        <td class="main" width="50%"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_PRODUCT, '', 'SSL'). '">' . TEXT_AFFILIATE_BANNERS_PRODUCT . '</a>';?></td>
                      </tr>
                      <tr>
                        <td class="main" width="50%"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_TEXT, '', 'SSL'). '">' . TEXT_AFFILIATE_BANNERS_TEXT . '</a>';?></td>
                        <td class="main" width="50%"></td>
                      </tr>
                    </table></td>
                    <td width="10" align="right"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="10"></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo TEXT_AFFILIATE_REPORTS ;?></b></td>
          </tr>
        </table></center></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td width="60"><img src="images/affiliate_reports.gif" border="0" alt="" width="60" height="60"></td>
                <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main" width="50%"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_CLICKS, '', 'SSL'). '">' . TEXT_AFFILIATE_CLICKRATE . '</a>';?></td>
                    <td class="main" width="50%"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, '', 'SSL'). '">' . TEXT_AFFILIATE_PAYMENT . '</a>';?></td>
                  </tr>
                  <tr>
                    <td class="main" width="50%"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_SALES, '', 'SSL'). '">' . TEXT_AFFILIATE_SALES . '</a>';?></td>
                    <td class="main" width="50%">&nbsp;</td>
                  </tr>
                </table></td>
                <td width="10" align="right"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
// RCI code start
echo $cre_RCI->get('affiliatecentral', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>

</table>
<?php
// RCI code start
echo $cre_RCI->get('affiliatecentral', 'bottom');
// RCI code eof
?>
