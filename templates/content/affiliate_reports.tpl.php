<?php
// RCI code start
echo $cre_RCI->get('affiliatepayment', 'top');
// RCI code eof
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
// BOF: Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'affiliate_reports.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>

<?php
// BOF: Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Added for template MOD
?>

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
// BOF: Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Added for template MOD
?>
          <tr>
            <td class="main"><?php echo TEXT_INFORMATION; ?></td>
          </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>

      <tr>
        <td colspan="4"><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="10"></td>
      </tr>
      <tr>
        <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_CLICKS, '', 'SSL'). '">' . TEXT_AFFILIATE_CLICKS . '</a>';?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="4"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td width="60"><img src="images/affiliate_clicks.gif" border="0" alt="" width="60" height="60"></td>
                <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10">&nbsp;<?php echo TEXT_INFORMATION_CLICKS ;?></td>
                    <td width="200" class="main" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_CLICKS, '', 'SSL') . '">' . tep_template_image_button('button_affiliate_clickthroughs.gif', IMAGE_CLICKS) . '</a>';?></td>
                  </tr>
                  </table></td>
               <td width="10" align="right"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
             </tr>
           </table></td>
         </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="4"><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="10"></td>
      </tr>
      <tr>
        <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_SALES, '', 'SSL'). '">' . TEXT_AFFILIATE_SALES . '</a>';?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="4"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td width="60"><img src="images/affiliate_sales.gif" border="0" alt="" width="60" height="60"></td>
                <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10">&nbsp;<?php echo TEXT_INFORMATION_SALES ;?></td>
                    <td width="200" class="main" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_SALES, '', 'SSL') . '">' . tep_template_image_button('button_affiliate_sales.gif', IMAGE_SALES) . '</a>';?></td>
                  </tr>
                  </table></td>
               <td width="10" align="right"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
             </tr>
           </table></td>
         </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="4"><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="10"></td>
      </tr>
      <tr>
        <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, '', 'SSL'). '">' . TEXT_AFFILIATE_PAYMENT . '</a>';?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="4"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td width="60"><img src="images/affiliate_payment.gif" border="0" alt="" width="60" height="60"></td>
                <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main"><img src="images/arrow_green.gif" border="0" alt="" width="12" height="10">&nbsp;<?php echo TEXT_INFORMATION_PAYMENT ;?></td>
                    <td width="200" class="main" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, '', 'SSL') . '">' . tep_template_image_button('button_affiliate_payment.gif', IMAGE_PAYMENT) . '</a>';?></td>
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
echo $cre_RCI->get('affiliatepayment', 'menu');
// RCI code eof
// BOF: Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Added for template MOD
?>
        </table></td>
      </tr>
    </table>
<?php
// RCI code start
echo $cre_RCI->get('affiliatereports', 'bottom');
// RCI code eof
?>
