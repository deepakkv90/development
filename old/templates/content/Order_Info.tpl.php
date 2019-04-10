<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('orderinfo', 'top');
// RCI code eof
?>   
<form name="account_edit" method="post" <?php echo 'action="' . tep_href_link('Order_Info_Process.php', '', 'SSL') . '"'; ?> onSubmit="return check_form(this);"><input type="hidden" name="action" value="process">
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
          <td class="pageHeading"><?php echo HEADING_TITLE_CHECKOUT; ?></td> 
          <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td> 
        </tr> 
      </table></td> 
  </tr> 
<!--   <tr> 
    <td><?php/* echo tep_draw_separator('pixel_trans.gif', '100%', '10');*/ ?></td> 
  </tr>  -->
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
          <td class="main"><?php
  if (sizeof($navigation->snapshot) > 0) {
?>
      <tr>
        <td class="smallText"><br><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td>
<?php
  $email_address = tep_db_prepare_input($HTTP_GET_VARS['email_address']);
  $account['entry_country_id'] = STORE_COUNTRY;

//  require(DIR_WS_MODULES . 'Order_Info_Check.php');
  require(DIR_WS_MODULES . FILENAME_ORDER_INFO_CHECK);
?>
        </td>
      </tr>
      <?php
      // RCI code start
      echo $cre_RCI->get('orderinfo', 'menu');
      // RCI code eof
      // BOF: Lango Added for template MOD
      if (MAIN_TABLE_BORDER == 'yes'){
        table_image_border_bottom();
      }
      // EOF: Lango Added for template MOD
      ?> 
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
                <td align="right"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
              </tr> 
            </table></td> 
        </tr> 
      </table></td> 
  </tr> 
</table></form>
<?php 
// RCI code start
echo $cre_RCI->get('orderinfo', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>