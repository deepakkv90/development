<?php
/*
  $Id: fss_values_manager.php,v 1.0.0.0 2008/06/19 23:39:49 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$is_62 = (INSTALLED_VERSION_MAJOR == 6 && INSTALLED_VERSION_MINOR == 2) ? true : false;
require(DIR_WS_FUNCTIONS . FILENAME_FSS_FUNCTIONS);
// RCI top
$cre_RCI->get('global', 'top');
$cre_RCI->get('fssvaluesmanager', 'top'); 
$action = (isset($_GET['action']) ? $_GET['action'] : '');
if (tep_not_null($action)) {
  switch ($action) {
    case 'update':
      $questions_id = $_GET['qID'];
      tep_db_query("delete from " . TABLE_FSS_QUESTIONS_FIELDS_VALUES . " where questions_id = '" . $questions_id . "'");
      foreach ($_POST as $key => $value) {
        if (strstr($key, 'field_')) {
          $values_type_id = substr($key, strpos($key, '_') + 1);
          if (strstr($values_type_id, 'field_')) {
            continue;
          }
          $fields_id = tep_fss_get_fields_id($values_type_id);
          $fields_type = tep_fss_get_fields_type($fields_id);
          if ($fields_type == 'textarea' || $fields_type == 'dropdownmenudynamic') {
            $fields_value = '';
            $fields_value_text = $value;
          } else {
            $fields_value = $value;
            $fields_value_text = '';
          }            
          $sql_data = array('questions_id' => $questions_id,
                            'fields_id' => $fields_id,
                            'fields_value' => $fields_value,
                            'fields_value_text' => $fields_value_text);
          tep_db_perform(TABLE_FSS_QUESTIONS_FIELDS_VALUES, $sql_data);
        }
      }
      tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('qID', 'action')) . 'qID=' . $_GET['qID']));
      break;
  }
}
$questions_types = tep_db_fetch_array(tep_db_query("select fq.questions_type, fqd.questions_label from " . TABLE_FSS_QUESTIONS . " fq, " . TABLE_FSS_QUESTIONS_DESCRIPTION . " fqd where fq.questions_id = fqd.questions_id and fqd.language_id = '" . $languages_id . "' and fq.questions_id = '" . $_GET['qID'] . "'"));
$output = tep_fss_get_html_code($questions_types['questions_label'], $questions_types['questions_type'], $_GET['qID']);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php
if ($is_62) {
    echo '<script language="javascript" src="includes/menu.js"></script>' . "\n";
} else {
    echo '<script type="text/javascript" src="includes/prototype.js"></script>' . "\n";
    echo '<!--[if IE]>' . "\n";
    echo '<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">' . "\n";
    echo '<![endif]-->' . "\n";
}
?>
<script language="javascript" src="includes/general.js"></script>
<style type="text/css">
TD.fss {
    border-right: 1px solid;
    border-bottom: 1px solid;
    border-color: #b6b7cb;
    font-family: Verdana, Arial, sans-serif;
    font-size: 11px;
  }
  
  TD.fss_header {
    border-right: 1px solid;
    border-top: 1px solid;
    border-bottom: 1px solid;
    border-color: #b6b7cb;
    font-family: Verdana, Arial, sans-serif;
    font-size: 11px;
  }
  
  TD.fss_left {
    border-right: 1px solid;
    border-bottom: 1px solid;
    border-left: 1px solid;
    border-color: #b6b7cb;
    font-family: Verdana, Arial, sans-serif;
    font-size: 11px;
  }
  
  TD.fss_left_header {
    border-right: 1px solid;
    border-bottom: 1px solid;
    border-left: 1px solid;
    border-top: 1px solid;
    border-color: #b6b7cb;
    font-family: Verdana, Arial, sans-serif;
    font-size: 11px;
  }
  .inputRequirement {
    font-family : Verdana, Arial, sans-serif;
    font-size : 10px;
    color : #ff0000;
    background : inherit;
  }
</style>
<script language="javascript">
<!--
  function LTrim( value ) {
    var re = /\s*((\S+\s*)*)/;
    return value.replace(re, "$1");
  }

  function RTrim( value ) {
    var re = /((\s*\S+)*)\s*/;
    return value.replace(re, "$1");
  }

  function trim( value ) {
    return LTrim(RTrim(value));
  }
  function option_select(selectedIndex, option_name) {
    selectedOptIndex = selectedIndex;
    value = document.getElementById(option_name).value;
    value_array = value.split('|');
    document.getElementById('option_name').value = trim(value_array[0]);
    if (typeof(value_array[1]) == 'undefined') {
      document.getElementById('option_value').value = '';
    } else {
      document.getElementById('option_value').value = trim(value_array[1]);
    }
  }

  function moveup_option(option_name) {
    var to = document.getElementById(option_name);
    var pos = document.getElementById(option_name).selectedIndex;;
    if (pos == 0) {
      return;
    }
    swap_option(to, pos, pos-1);
    selected_option(to, pos-1);
  }

  function movedown_option(option_name) {
    var to = document.getElementById(option_name);
    var pos = selectedOptIndex;
    if (pos == to.length-1) {
      return;
    }
    swap_option(to, pos, pos+1);
    selected_option(to, pos+1);
  }

  function option_create(option_name) {
    var to_pos = document.getElementById(option_name).length;
    var to = document.getElementById(option_name);
    var value1 = document.getElementById('option_name').value;
    var value2 = document.getElementById('option_value').value;
    
    if (str_check(value1, '|') || str_check(value2, '|') || str_check(value1, ',') || str_check(value2, ',')) {
      alert("Invalid Option Name/Value, can't include pipeline(|) and comma(,)");
      return;
    }
    if (value1 != '' && value2 != '') {
      value = value1 + ' | ' + value2;
      document.getElementById('option_name').value = '';
      document.getElementById('option_value').value = '';
      make_option(value, value, to, to_pos);      
      selected_option(to, to_pos);      
    } else {
      alert("Input Option Name/Value first");
    }
  }
  
  function str_check(str, sub_str) {
    if (str.indexOf(sub_str) == -1) {
      return false;
    } else {
      return true;
    }
  }
  
  function option_delete(option_name) {
    var to = document.getElementById(option_name);
    var selectedOptIndex = document.getElementById(option_name).selectedIndex;
    var http_request = false;
    var msg = 'Are you sure you want to delete this option value?';
    if (selectedOptIndex != -1) {
      if (confirm(msg)) {
        to.options[selectedOptIndex] = null;
        document.getElementById(option_name).value='';
        document.getElementById('option_name').value = '';
      }
    } else {
      alert("Select an option value first");
    }    
  }

  function option_namechange(option_name) {
    var option = new Object();
    var to = document.getElementById(option_name);
    var value,value1, value2;
    var text,text1, text2;
    var selectedOptIndex = document.getElementById(option_name).selectedIndex;
    
    text1 = document.getElementById('option_name').value;
    value1 = document.getElementById('option_name').value;
    text2 = document.getElementById('option_value').value;
    value2 = document.getElementById('option_value').value;
    if (str_check(value1, '|') || str_check(value2, '|') || str_check(value1, ',') || str_check(value2, ',')) {
      alert("Invalid Option Name/Value, can't include pipeline(|) and comma(,)");
      return;
    }
    value = value1 + ' | ' + value2;
    text = text1 + ' | ' + text2;
    if (value1 != '' && value2 != '') {
      make_option(text, value, to, selectedOptIndex);
      selected_option(to, selectedOptIndex);
    }    
  }

  function make_option(text, value, target, index) {
    target[index] = new Option(text, value);
  }

  function parseSelectValue(select, selectedIndex) {
    var temp_nm
    var option_value = select.options[selectedIndex].value;

    this.option_no = option_value.substring(option_value.indexOf('option_no=') + 9, option_value.indexOf(','));
    option_value = option_value.substring(option_value.indexOf(',') + 1);

    temp_nm = option_value.substring(option_value.indexOf('option_nm=') + 9, option_value.indexOf('option_sort=')-1);
    this.option_nm = temp_nm.substring(1, temp_nm.length-1);
    option_value = option_value.substring(option_value.indexOf('option_sort='));

    this.option_sort = option_value.substring(option_value.indexOf('option_sort=') + 11 ,option_value.indexOf(','));
    option_value = option_value.substring(option_value.indexOf(',') + 1);

    this.action = option_value.substring(option_value.indexOf('action=') + 7);

    this.changeFrm = _private_change;
    this.deleteFrm = _private_delete;

    return this;
  }

  function selected_option(target, pos) {
    target.options[pos].selected = true;
    option_select(pos);
  }

  function swap_option(target, swap_a, swap_b) {
    var option_a = new Object();
    var option_b = new Object();

    option_a = new parseSelectValue(target, swap_a);
    option_b = new parseSelectValue(target, swap_b);

    var temp_option = new Option(target.options[swap_a].text, target.options[swap_a].text);
    target[swap_a] = new Option(target.options[swap_b].text, target.options[swap_a].text);
    target[swap_b] = temp_option;
  }

  function _private_change() {
    _private_update_frm_element(this.album_nm);
  }

  function _private_delete() {
    _private_update_frm_element('');
  }
  
  function form_submit() {
    value = '';
    for (i=0; i < document.values.elements.length; i++) {
      obj_name = document.values.elements[i].name;
      option_name = get_option_name(obj_name);
      if (option_name != '') {
        for (j = 0; j < document.getElementById(obj_name).length; j++) {
          if (value == '') {
            value = document.getElementById(obj_name).options[j].text;
          } else {
            value += ', ' + document.getElementById(obj_name).options[j].text;
          } 
        }
        document.getElementById(option_name).value = value;
      }
    }
  }
  
  function get_option_name (name) {
    name_array = name.split('_');
    if (name_array.length == 4 && name_array[0] == 'field' && name_array[2] == 'field') {
      return name_array[0] + '_' + name_array[1];
    } else {
      return '';
    } 
  }
  
  function is_post_field(name) {
    name_array = name.split('_');
    if (name_array.length == 2 && name_array[0] == 'field') {
      return true;
    } else {
      return false;
    }
  }
  
  function option_preview() {
    poststr = '';
    form_submit();    
    for(i = 0; i < document.values.elements.length; i++) {
      field_obj = document.values.elements[i];    
      if (is_post_field(field_obj.name)) {
        if ((field_obj.type == 'checkbox' || field_obj.type == 'radio') && !field_obj.checked) {
          continue;
        }
        if (poststr == '') {          
          poststr = field_obj.name + '=' + encodeURI(field_obj.value);
        } else {
          poststr += '&' + field_obj.name + '=' + encodeURI(field_obj.value);
        }
      }
    }
<?php
    echo "    poststr += '&qID=' + encodeURI('" . $_GET['qID'] . "');\n";
    if (isset($_GET[tep_session_name()])) {
      echo "    poststr += '&" . tep_session_name() . "=' + encodeURI('" . $_GET[tep_session_name()] . "');\n";
    }
?>
    makePOSTRequest('<?php echo FILENAME_FSS_QUESTIONS_PREVIEW; ?>', poststr);
  }
  
  function makePOSTRequest(url, parameters) {
    http_request = false;
    if (window.XMLHttpRequest) { // Mozilla, Safari,...
      http_request = new XMLHttpRequest();
      if (http_request.overrideMimeType) {
        http_request.overrideMimeType('text/html');
      }
    } else if (window.ActiveXObject) { // IE
      try {
        http_request = new ActiveXObject("Msxml2.XMLHTTP");
      } catch (e) {
        try {
          http_request = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {}
      }
    }
    if (!http_request) {
      alert('Cannot create XMLHTTP instance');
      return false;
    }      
    http_request.onreadystatechange = alertContents;
    http_request.open('POST', url, true);
    http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http_request.setRequestHeader("Content-length", parameters.length);
    http_request.setRequestHeader("Connection", "close");
    http_request.send(parameters);
  }

  function alertContents() {
    if (http_request.readyState == 4) {
      if (http_request.status == 200) {
        result = http_request.responseText;
        document.getElementById('preview').innerHTML = result;            
      } else {
        alert('There was a problem with the request.');
      }
    }
  }   
-->
</script>
</head>
<!-- ************** -->
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<!-- ************** -->  
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo 'Question: ' . $questions_types['questions_label']; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="pageHeading"><?php echo 'Type: ' . $questions_types['questions_type']; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <?php
          echo tep_draw_form('values', FILENAME_FSS_VALUES_MANAGER, tep_get_all_get_params(array('qID', 'action')) . 'qID=' . $_GET['qID'] . '&action=update', 'post', 'onSubmit="form_submit()"');
          ?>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="center">
              <?php
              echo $output;
              ?>            
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><?php echo '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('qID')) . 'qID=' . $_GET['qID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a><a href="javascript:option_preview();">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>' . tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
          </tr>
          </form>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="center"><div id="preview"></div></td>
      </tr>
      <?php
      // RCI bottom
      $cre_RCI->get('fssvaluesmanager', 'bottom'); 
      $cre_RCI->get('global', 'bottom');                                        
      ?>
    </table></td>
    <!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>