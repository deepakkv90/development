<?php
/*
  $Id: affiliate_details_ok.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('affiliatedetailsok', 'top');
// RCI code eof

// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
        <tr>
      </tr>

      <?php
      // BOF: Lango Added for template MOD
    } else {
      $header_text = HEADING_TITLE;
    }
    // EOF: Lango Added for template MOD
    // BOF: Lango Added for template MOD
    if (MAIN_TABLE_BORDER == 'yes'){
      table_image_border_top(false, false, $header_text);
    }
    // EOF: Lango Added for template MOD
    ?>
      <tr>
       
          <td align="right" class="smallText"><br><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_SUMMARY) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td></tr></td></tr><?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?></table><?php
// RCI code start
echo $cre_RCI->get('affiliatedetailsok', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>