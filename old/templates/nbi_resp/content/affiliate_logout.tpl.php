<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('affiliatelogout', 'top');
// RCI code eof
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
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

<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
<?php
  session_start();
  $old_user = $affiliate_id;  // store  to test if they *were* logged in
  $result = session_unregister("affiliate_id");

//session_destroy();

  if (!empty($old_user)) {
    if ($result) { // if they were logged in and are not logged out 
      echo '            <td class="main">' . TEXT_INFORMATION . '</td>';
    } else { // they were logged in and could not be logged out
      echo '            <td class="main">' . TEXT_INFORMATION_ERROR_1 . '</td>';
    } 
  } else { // if they weren't logged in but came to this page somehow
    echo '            <td class="main">' . TEXT_INFORMATION_ERROR_2 . '</td>';
  }
?>
        </table></td>
      </tr>
<?php
// RCI code start
echo $cre_RCI->get('affiliatelogout', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td align="right" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
      </tr>
    </table>
<?php
// RCI code start
echo $cre_RCI->get('affiliatelogout', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>