    <?php
    // RCI code start
    echo $cre_RCI->get('global', 'top');
    echo $cre_RCI->get('information', 'top');
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
            <td class="pageHeading"><?php echo $INFO_TITLE; ?></td>
            <td align="right">
        <?php echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>
      </td>
          </tr>
        </table></td>
      </tr>

<?php
// BOF: Lango Added for template MOD
}else{
$header_text = $INFO_TITLE;
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
<?php
if(!$HTTP_GET_VARS['info_id']) {
// joles
$sql_query = tep_db_query("SELECT information_id,languages_id, info_title FROM " . TABLE_INFORMATION . " WHERE visible= '1' and languages_id ='" . (int)$languages_id . "' ORDER BY v_order");
while ($row = tep_db_fetch_array($sql_query)){
$informationString_Page .= '<a href="' . tep_href_link (FILENAME_INFORMATION, 'info_id=' . $row['information_id'] ) . '">' . $row['info_title'] . '</a><br>';
}
echo $informationString_Page;
}
?>
          <tr>
            <td class="main"><?php echo $INFO_DESCRIPTION; ?></td>
          </tr>
<?php
// RCI code start
echo $cre_RCI->get('information', 'menu');
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
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>
    <?php
    // RCI code start
    echo $cre_RCI->get('information', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof
    ?>