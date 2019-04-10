<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('orderinfoprocess', 'top');
// RCI code eof
echo tep_draw_form('account_edit', tep_href_link(FILENAME_ORDER_INFO_PROCESS, '', 'SSL'), 'post', 'onSubmit="return check_form(this);"') . tep_draw_hidden_field('action', 'process'); ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <!--tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr//-->
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php include(DIR_WS_MODULES . 'Order_Info_Check.php'); ?></td>
      </tr>
      <?php 
      // RCI code start
      echo $cre_RCI->get('orderinfoprocess', 'menu');
      // RCI code eof
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
    echo $cre_RCI->get('orderinfoprocess', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof
    ?>