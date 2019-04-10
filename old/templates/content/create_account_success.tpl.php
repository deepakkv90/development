<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('createaccountsuccess', 'top');
// RCI code eof
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><?php echo tep_image(DIR_WS_IMAGES . 'table_background_man_on_board.gif', HEADING_TITLE); ?></td>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="pageHeading" align="center"><?php echo HEADING_TITLE; ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
              <tr>
                <td class="main">
                  <?php 
                  if ((defined(ACCOUNT_EMAIL_CONFIRMATION)) && (ACCOUNT_EMAIL_CONFIRMATION == 'true')) {
                    echo TEXT_ACCOUNT_CREATED_NEEDS_VALIDATE; 
                  } else {
                    echo TEXT_ACCOUNT_CREATED_NO_VALIDATE; 
                  }
                  ?>
                </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php 
      // RCI code start
      echo $cre_RCI->get('createaccountsuccess', 'menu');
      // RCI code eof
      ?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . $origin_href . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>
<?php 
// RCI code start
echo $cre_RCI->get('createaccountsuccess', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>