<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('popupsearchhelp', 'top');
// RCI code eof
?>
<table border="0" width="100%" cellspacing="3" cellpadding="<?php echo CELLPADDING_MAIN; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;' ;
//EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" valign="top"><?php echo HEADING_SEARCH_HELP; ?></td>
          </tr>
        </table></td>
      </tr>

<?php
}else{
$header_text = HEADING_SEARCH_HELP;
}
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
     <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
    <td><img src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME;?>/images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td>
  </tr>
  <tr>
    <td class="smallText"><?php echo TEXT_SEARCH_HELP; ?></td>
  </tr>

  </table>
      </td>
        </tr>
<tr>
  <td>
<p class="smallText" align="right"><a href="javascript:window.close()"><?php echo TEXT_CLOSE_WINDOW; ?></a></p>
  </td>
 </tr>
 <tr>
  <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
</tr>
    <?php
    // BOF: Lango Added for template MOD
    if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_bottom();
    }
    // EOF: Lango Added for template MOD
// RCI code start
echo $cre_RCI->get('popupsearchhelp', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>