<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('downformaintenance', 'top');
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
           <td>&nbsp;</td>
          </tr>
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'down_for_maintenance.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
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
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
          <tr>
            <td class="main"><?php echo DOWN_FOR_MAINTENANCE_TEXT_INFORMATION; ?></td>
          </tr>
    <?php if (DISPLAY_MAINTENANCE_TIME == 'true') { ?>
          <tr>
            <td class="main"><?php echo TEXT_MAINTENANCE_ON_AT_TIME . TEXT_DATE_TIME; ?></td>
          </tr>
     <?php
      } 
      if (DISPLAY_MAINTENANCE_PERIOD == 'true') { ?>
      <tr>
            <td class="main"><?php echo TEXT_MAINTENANCE_PERIOD . TEXT_MAINTENANCE_PERIOD_TIME; ?></td>
          </tr>
      <?php }
// RCI code start
echo $cre_RCI->get('downformaintenance', 'menu');
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
        <td align="right" class="main"><br><?php echo DOWN_FOR_MAINTENANCE_STATUS_TEXT . '<br><br>' . '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
      </tr>
      <tr>
       <td>&nbsp;</td>
     </tr>
    </table>
<?php 
// RCI code start
echo $cre_RCI->get('downformaintenance', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>