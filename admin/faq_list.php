<?php
/*
  $Id: faq_list.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<tr>
  <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
      <td class="pageHeading"><?php echo $title; ?></td>
    </tr>
  </table></td>
</tr>
<tr>
  <td><table border="0" width="100%" cellpadding="2" cellspacing="1" bgcolor="#ffffff">
    <tr class="dataTableHeadingRow">
    <td align="center" class="dataTableHeadingContent"><?php echo FAQ_NUMBER;?></td>
    <td align="center" class="dataTableHeadingContent"><?php echo FAQ_DATE;?></td>
    <td align="center" class="dataTableHeadingContent"><?php echo tep_image(DIR_WS_IMAGES . 'icons/sort.gif', FAQ_SORT_BY); ?></td>
    <td align="center" class="dataTableHeadingContent"><?php echo FAQ_QUESTION;?></td>
    <td align="center" class="dataTableHeadingContent"><?php echo FAQ_ID;?></td>
    <td align="center" class="dataTableHeadingContent"><?php echo FAQ_STATUS;?></td>
    <td align="center" class="dataTableHeadingContent" colspan="2"><?php echo FAQ_ACTION;?></td>
    </tr>
    <?php 
    $no = 1;
    if (sizeof($data) > 0) {
      while (list($key, $val) = each($data)) {
        $no % 2 ? $bgcolor="#DEE4E8" : $bgcolor="#F0F1F1";
        ?>
        <tr bgcolor="<?php echo $bgcolor?>">
          <td align="center" class="dataTableContent"><?php echo $no;?></td>
          <td align="center" class="dataTableContent" nowrap="nowrap"><?php echo $val['d']?></td>
          <td align="center" class="dataTableContent"><?php echo $val['v_order'];?></td>
          <td align="left" class="dataTableContent"><?php echo $val['question'] . ' (' . $val['language'] . ')';?></td>
          <td align="center" class="dataTableContent"><?php echo $val['faq_id'];?></td>
          <td align="center" class="dataTableContent" nowrap="nowrap">
            <?php 
            if ($val['visible'] == 1) {
              echo tep_image(DIR_WS_IMAGES . 'accept.png', IMAGE_ICON_STATUS_GREEN, 16, 16) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FAQ_MANAGER, "faq_action=Visible&faq_id=$val[faq_id]&visible=$val[visible]") . '">' . tep_image(DIR_WS_IMAGES . 'cancel-off.png', FAQ_DEACTIVATION_ID . " $val[faq_id]", 10, 10) . '</a>';
            } else {
              echo tep_image(DIR_WS_IMAGES . 'cancel.png', IMAGE_ICON_STATUS_RED, 16, 16) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FAQ_MANAGER, "faq_action=Visible&faq_id=$val[faq_id]&visible=$val[visible]") . '">' . tep_image(DIR_WS_IMAGES . 'accept-off.png', FAQ_ACTIVATION_ID . " $val[faq_id]", 10, 10) . '</a>';
            }
            ?>
          </td>
          <td align=center class="dataTableContent">
            <?php echo '<a href="' . tep_href_link(FILENAME_FAQ_MANAGER, "faq_action=Edit&faq_id=$val[faq_id]&faq_lang=$val[language]", 'NONSSL') . '">' . tep_image(DIR_WS_ICONS . 'page_edit.png', FAQ_EDIT_ID . " $val[faq_id]") . '</a>'; ?>
          </td>
          <td align=center class="dataTableContent">
            <?php echo '<a href="' . tep_href_link(FILENAME_FAQ_MANAGER, "faq_action=Delete&faq_id=$val[faq_id]", 'NONSSL') . '">' . tep_image(DIR_WS_ICONS . 'delete.gif', FAQ_DELETE_ID . " $val[faq_id]") . '</a>'; ?>
          </td>
        </tr>
        <?php 
        $no++;
      }
    } else {
      ?>
      <tr bgcolor="#DEE4E8">
        <td colspan="7"><?php echo FAQ_ALERT; ?></td>
      </tr>
      <?php 
    }
    ?>
  </table></td>
</tr>
<tr><td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td></tr>
<tr>
  <td align="right">
    <?php echo '<a href="' . tep_href_link(FILENAME_FAQ_MANAGER, '', 'NONSSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a><a href="' . tep_href_link(FILENAME_FAQ_MANAGER, 'faq_action=Added', 'NONSSL') . '">' . tep_image_button('button_insert.gif', FAQ_ADD) . '</a>'; ?>
  </td>
</tr>
<tr>
  <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>              
    <?php
    // RCI code start
    echo $cre_RCI->get('faqlist', 'listingbottom');
    // RCI code eof
    ?>
    </tr>
  </table></td>
</tr> 