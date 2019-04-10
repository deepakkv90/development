<?php
/*
  $Id: returns_track.tpl.php,v 1.2 2008/10/05 00:36:42 wa4u Exp $

  author Puddled Internet - http://www.puddled.co.uk
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('returntrack', 'top');
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
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_return.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
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
      <table border="0" width="100%" cellspacing="0" cellpadding="2" valign = "top">
        <?php
        if ($_GET['action'] == 'returns_show') {
          include(DIR_WS_MODULES . 'returns_track.php');
        } else {
        ?>      
        <tr>
          <td>
          <?php echo tep_draw_form('rma_show', tep_href_link(FILENAME_RETURNS_TRACK, 'action=returns_show', 'SSL', false), 'post');?>
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="2" class="main"><center><?php echo TEXT_TRACK_DETAILS_2;?></center><br></td>
            </tr>
            <tr>
              <td width="45%" height="30" align="right" class="main"><?php echo TEXT_YOUR_RMA_NUMBER;?>&nbsp;</td>
              <td width="50%" height="30" align="left" class="main"><?php echo tep_draw_input_field('rma','','size="20"');?></td>
            </tr>
            <tr>
              <td colspan="2" align="center"><?php echo tep_template_image_submit('button_confirm.gif', TEXT_FIND_RETURN);?></td>
            </tr>
          </table>
        </form>
        </td>
            </tr>
<?php
}

// RCI code start
echo $cre_RCI->get('returntrack', 'menu');
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
                <td align="right"><?php echo '<a href="javascript:history.go(-1)">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>
<?php
// RCI code start
echo $cre_RCI->get('returntrack', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>