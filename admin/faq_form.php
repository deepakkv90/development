<?php
/*
  FAQ system for OSC 2.2 MS2 v2.1  22.02.2005
  Originally Created by: http://adgrafics.com admin@adgrafics.net
  Updated by: http://www.webandpepper.ch osc@webandpepper.ch v2.0 (03.03.2004)
  Last Modified: http://shopandgo.caesium55.com timmhaas@web.de v2.1 (22.02.2005)
  Released under the GNU General Public License
  osCommerce, Open Source E-Commerce Solutions
  Copyright (c) 2004 osCommerce
*/
?>
<?php // Load Editor
echo tep_load_html_editor();
echo tep_insert_html_editor('question','advanced','200');
echo tep_insert_html_editor('answer','advanced','400');
?>
<script language="JavaScript">
  var MaxLen = 512;
  function countMe(form) {
    inputStr = form.question.value;
    strlength= inputStr.length;
    if (strlength > MaxLen ) form.question.value = inputStr.substring(0,MaxLen);
    form.num.value = (MaxLen - form.question.value.length);
    form.question.focus();
  }
  
  function change_lang(lang) {
    <?php echo "window.location.href = '" . FILENAME_FAQ_MANAGER . '?faq_action=' . $_GET['faq_action'] . '&' . "faq_lang='+lang;"; ?>
  }
</script>
<tr class="pageHeading"><td><?php echo $title; ?></td></tr>
<tr class="headerBar"><td class="headerBarContent">
<?php 
  echo FAQ_QUEUE_LIST;
  $data = browse_faq($language,$_GET);
  $no = 1;
  if (sizeof($data) > 0) {
    while (list($key, $val) = each($data)) {
    echo $val['v_order'] . ', ';
    $no++;
  }
  } 
?>
</td></tr>
<tr><td>
<table border="0" cellpadding=0 cellspacing=2" width="80%">
<tr><td class="main"><?php echo FAQ_QUEUE;?></td>
<td class="main">
<?php 
  if (isset($edit['v_order']) && $edit['v_order']) {
    $no = $edit['v_order'];
  };
  echo tep_draw_input_field('v_order', "$no", 'size=3 maxlength=4');
?>
</td>
</tr>
<tr>
<td valign="top" class="main"><?php echo FAQ_VISIBLE; ?></td>
<td valign="top" class="main">
<?php
  if (isset($edit['visible']) && $edit['visible']) {
    $checked = "checked";
  };
  echo tep_draw_checkbox_field('visible', '1', (isset($checked) ? $checked : ''));
  /*
  // Not needed, remove comments to show
  if ($edit[visible]==1) {
  echo tep_image(DIR_WS_IMAGES . 'accept.png', FAQ_ID_ACTIVE);
  }else{
  echo tep_image(DIR_WS_IMAGES . 'cancel.png', FAQ_ID_DEACTIVE);
  }
  */
?>
</td>
</tr>
<?php 
  if ($_GET['faq_action'] != 'Edit') {
?>
<tr>
<td valign="top" class="main"><?php echo FAQ_LANGUAGE; ?></td>
<td valign="top" class="main">
<?php
  $lang_query = tep_db_query("select directory from " . TABLE_LANGUAGES . " order by languages_id desc");
  while ($get_lang = tep_db_fetch_array($lang_query)) {
    $langs[] = array('id' => $get_lang['directory'], 'text' => $get_lang['directory']);
  }
  if (isset($_GET['faq_lang'])) {
    $def_lang = $_GET['faq_lang'];
  } else {
    $def_lang = $language;
  }
  echo tep_draw_pull_down_menu('faq_language',$langs,$def_lang,'onchange="change_lang(this.value);"');
?>
</td>
</tr>
<?php
  }
?>

<?php
    $categories_array = array();
    $categories_array[] = array('id' => '', 'text' => TEXT_NO_CATEGORY);
    $categories_query = tep_db_query("select icd.categories_id, icd.categories_name from " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd where language_id = '" . (int)$languages_id . "' order by icd.categories_name");
    while ($categories_values = tep_db_fetch_array($categories_query)) {
      $categories_array[] = array('id' => $categories_values['categories_id'], 'text' => $categories_values['categories_name']);
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_CATEGORY; ?></td>
            <td class="main"><?php echo tep_draw_pull_down_menu('faq_category', $categories_array, (isset($edit['categories_id']) ? $edit['categories_id'] : (isset($_GET['faq_category']) ? $_GET['faq_category'] : ''))); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

<tr><td valign="top" class="main"><b><?php echo FAQ_QUESTION; ?></b><br>
<script>
  document.write("(max. "+MaxLen+")");
</script>
<br>
<?php echo tep_draw_input_field('num', '', 'size=3 readonly STYLE="color: red" '); ?>
</td>
<td valign="top">
<?php echo tep_draw_textarea_field('question', '', '60', '15', (isset($edit['question']) ? $edit['question'] : (isset($_GET['question']) ? $_GET['question'] : '')), 'style="width: 100%" onChange="countMe(document.forms[0])" onKeyUp="countMe(document.forms[0])" '); ?>

</td>
</tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
<tr>
<td valign="top" class="main"><b><?php echo FAQ_ANSWER; ?></b></td>
<td valign="top">
<?php echo tep_draw_textarea_field('answer', '', '60', '25', (isset($edit['answer']) ? $edit['answer'] : (isset($_GET['answer']) ? $_GET['answer'] : '')),' style="width: 100%" mce_editable="true"'); ?>
</td>
</tr>
<tr><td></td>
<td align="right">
<?php
  echo tep_image_submit('button_save.gif', IMAGE_SAVE);
  echo '<a href="' . tep_href_link(FILENAME_FAQ_MANAGER, '', 'NONSSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?>
</td>
</tr>
</table>
</form>
</td></tr>
