<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('contactus', 'top');
// RCI code eof
echo tep_draw_form('contact_us', tep_href_link(FILENAME_CONTACT_US, 'action=send', 'SSL')); ?>

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
          <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_contact_us.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
        </tr>
      </table></td>
  </tr>
  <!--  <tr>
    <td><?php/* echo tep_draw_separator('pixel_trans.gif', '100%', '10'); */?></td>
  </tr> -->
  <?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD

// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD

  if ($messageStack->size('contact') > 0) {
?>
  <tr>
    <td><?php echo $messageStack->output('contact'); ?></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <?php
  }

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'success')) {
?>
  <tr>
    <td class="main" align="center"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_man_on_board.gif', HEADING_TITLE, '0', '0', 'align="left"') . TEXT_SUCCESS; ?></td>
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
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <?php
  } else {
  if (defined('TEXT_BODY') && TEXT_BODY !='') {
?>
  <tr>
    <td class="main"><?php echo TEXT_BODY; ?></td>
  </tr>
 <?php } ?>
  <tr>
    <td class="main"><table width="100%" border="0" cellspacing="4" cellpadding="2">
        <tr>
          <?php if (defined('CONTACT_US_ADDRESS') && CONTACT_US_ADDRESS !='') {?>
          <td width="50%" class="main" valign="top"><?php echo CONTACT_US_ADDRESS; ?></td>
          <?php } ?>
          <td width="50%" class="main" valign="top"><?php if (defined('CONTACT_US_TELPHONE_NUMBER') && CONTACT_US_TELPHONE_NUMBER !='') { echo '<br>' . CONTACT_US_TELPHONE_NUMBER . '<br>';} if (defined('CONTACT_US_FAX_NUMBER') && CONTACT_US_FAX_NUMBER != '') {echo CONTACT_US_FAX_NUMBER; }?></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td class="main"><table width="100%" border="0" cellspacing="4" cellpadding="2">
        <tr>
          <?php if (defined('CONTACT_US_EMAIL_ID') && CONTACT_US_EMAIL_ID != '') {?>
          <td width="50%" class="main"><?php echo CONTACT_US_EMAIL_ID; ?></td>
          <?php }?>
          <td width="50%" class="main"><?php if (defined('CONTACT_US_SKYPE_ID') && CONTACT_US_SKYPE_ID != '') { echo '<a href="skype:' . CONTACT_US_SKYPE_ID . '?call">' . tep_image(DIR_WS_ICONS . 'skype.gif') . '</a>'; }?>
            <?php if (defined('CONTACT_US_YAHOO_IM') && CONTACT_US_YAHOO_IM != '') { echo '<br><a href ="ymsgr:sendim?' . CONTACT_US_YAHOO_IM . '">' . tep_image(DIR_WS_ICONS . 'yahoo.gif') . '</a>';}?>
            <?php if (defined('CONTACT_US_AIM_ID') && CONTACT_US_AIM_ID != '') { echo '<br><a href ="aim:goim?screenname=' . CONTACT_US_AIM_ID . '&amp;message=Hi.+Are+you+there?">' . tep_image(DIR_WS_ICONS . 'aim.gif') . '</a>';}?></td>
        </tr>
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
                <td class="main" width="20%"><?php echo ENTRY_COMPANY; ?></td>
                <td class="main" width="80%"><?php echo tep_draw_input_field('company'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_NAME; ?></td>
                <td class="main"><?php echo tep_draw_input_field('name'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_EMAIL; ?></td>
                <td class="main"><?php echo tep_draw_input_field('email'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
                <td class="main"><?php echo tep_draw_input_field('telephone'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
                <td class="main"><?php echo tep_draw_input_field('street'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_CITY; ?></td>
                <td class="main"><?php echo tep_draw_input_field('city'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_STATE; ?></td>
                <td class="main"><?php echo tep_draw_input_field('state'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
                <td class="main"><?php echo tep_draw_input_field('postcode'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
                <td class="main"><?php echo tep_get_country_list('country'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
              <?php
    $topic_array = array();
    $topic_array = array(array('id' => ENTRY_TOPIC_1, 'text' => ENTRY_TOPIC_1), 
                         array('id' => ENTRY_TOPIC_2, 'text' => ENTRY_TOPIC_2), 
                         array('id' => ENTRY_TOPIC_3, 'text' => ENTRY_TOPIC_3),
                         array('id' => ENTRY_TOPIC_4, 'text' => ENTRY_TOPIC_4)
                         );
?>
              <tr>
                <td class="main"><?php echo ENTRY_TOPIC; ?></td>
                <td class="main"><?php echo tep_draw_pull_down_menu('topic', $topic_array); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_SUBJECT; ?></td>
                <td class="main"><?php echo tep_draw_input_field('subject', '', 'size = "60"'); ?></td>
              </tr>
              <tr>
                <td class="main" valign="top"><?php echo ENTRY_ENQUIRY; ?></td>
                <td><?php echo tep_draw_textarea_field('enquiry', 'soft', 50, 15); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_URGENT; ?></td>
                <td class="main"><?php echo tep_draw_checkbox_field('urgent'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_SELF; ?></td>
                <td class="main"><?php echo tep_draw_checkbox_field('self'); ?></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <!--  VISUAL VERIFY CODE start -->
  <tr>
    <td class="main"><b><?php echo VISUAL_VERIFY_CODE_CATEGORY; ?></b></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo VISUAL_VERIFY_CODE_TEXT_INSTRUCTIONS; ?></td>
                <td class="main"><?php echo tep_draw_input_field('visual_verify_code') . '&nbsp;' . '<span class="inputRequirement">' . VISUAL_VERIFY_CODE_ENTRY_TEXT . '</span>'; ?></td>
                <td class="main"><?php
                      //can replace the following loop with $visual_verify_code = substr(str_shuffle (VISUAL_VERIFY_CODE_CHARACTER_POOL), 0, rand(3,6)); if you have PHP 4.3
                    $visual_verify_code = ""; 
                    for ($i = 1; $i <= rand(3,6); $i++){
                          $visual_verify_code = $visual_verify_code . substr(VISUAL_VERIFY_CODE_CHARACTER_POOL, rand(0, strlen(VISUAL_VERIFY_CODE_CHARACTER_POOL)-1), 1);
                     }
                     $vvcode_oscsid = tep_session_id();
                     tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . $vvcode_oscsid . "'");
                     $sql_data_array = array('oscsid' => $vvcode_oscsid, 'code' => $visual_verify_code);
                     tep_db_perform(TABLE_VISUAL_VERIFY_CODE, $sql_data_array);
                     $visual_verify_code = "";
                     echo('<img src="' . FILENAME_VISUAL_VERIFY_CODE_DISPLAY . '?vvc=' . $vvcode_oscsid . '" alt="' . VISUAL_VERIFY_CODE_CATEGORY . '">');
                  ?>
                </td>
                <td class="main"><?php echo VISUAL_VERIFY_CODE_BOX_IDENTIFIER; ?></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <!--  VISUAL VERIFY CODE stop   -->
  <?php
// RCI code start
echo $cre_RCI->get('contactus', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
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
  <?php
  }
?>
</table>
</form>
<?php 
// RCI code start
echo $cre_RCI->get('contactus', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>
