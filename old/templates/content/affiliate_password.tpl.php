<?php
// RCI code start
echo $cre_RCI->get('affiliatepassword', 'top');
// RCI code eof
echo tep_draw_form('affiliatepassword', tep_href_link(FILENAME_AFFILIATE_PASSWORD, '', 'SSL'), 'post', 'onSubmit="return check_form();"') . tep_draw_hidden_field('action', 'process'); ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
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
        <td>
<?php
  if ($messageStack->size('a_password') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('a_password'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>

             <tr class="infoBoxContents">
                <td align="center"><table border="0" cellspacing="2" cellpadding="2">
                 <tr>
   <td class="main"><b><?php echo MY_PASSWORD_TITLE; ?></b></td>
   <td class="inputRequirement"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
  </tr>
                  <tr>
                    <td class="main"><?php echo ENTRY_PASSWORD_CURRENT; ?></td>
                    <td class="main"><?php echo tep_draw_password_field('password_current') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CURRENT_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CURRENT_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo ENTRY_PASSWORD_NEW; ?></td>
                    <td class="main"><?php echo tep_draw_password_field('password_new') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_NEW_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_NEW_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
                    <td class="main"><?php echo tep_draw_password_field('password_confirmation') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                  <td><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_SUMMARY, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                   <td align="right"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                </form>
                 </tr>

                </table></td>
                </tr>

<?php
// RCI code start
echo $cre_RCI->get('affiliatepassword', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>

        </table></td>
      </tr>


      </tr>
    </table>
<?php
// RCI code start
echo $cre_RCI->get('affiliatepassword', 'bottom');
// RCI code eof
?>