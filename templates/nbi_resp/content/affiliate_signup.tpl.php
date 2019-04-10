<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('affiliatesignup', 'top');
// RCI code eof
echo tep_draw_form('affiliate_signup',  tep_href_link(FILENAME_AFFILIATE_SIGNUP, '', 'SSL'), 'post') . tep_draw_hidden_field('action', 'process'); ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
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
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
 
<?php
// BOF: Lango Added for template MOD
}else{
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
        <td>
<?php
  if (isset($HTTP_GET_VARS['affiliate_email_address'])) $a_email_address = tep_db_prepare_input($HTTP_GET_VARS['affiliate_email_address']);
  $affiliate['affiliate_country_id'] = STORE_COUNTRY;

  //require(DIR_WS_MODULES . 'affiliate_account_details.php');
  require(DIR_WS_MODULES . FILENAME_AFFILIATE_ACCOUNT_DETAILS);
?>
<?php
// RCI code start
echo $cre_RCI->get('affiliatesignup', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td align="right" class="main"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
          </tr>
                </table></td>
              </tr>
                </table></td>
              </tr>

    </table></form>
<?php 
// RCI code start
echo $cre_RCI->get('affiliatesignup', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>