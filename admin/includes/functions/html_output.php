<?php
/*
  $Id: html_output.php,v 1.1.1.1 2004/03/04 23:39:54 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

////
// The HTML href link wrapper function
  function tep_href_link($page = '', $parameters = '', $connection = 'SSL') { //replaced NONSSL to SSL for setting default 
    global $request_type, $session_started, $SID, $spider_flag;
    if ($page == '') {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000">'.UNABLE_TO_DETERMINE_PAGE_LINK.'tep_href_link(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</b>');
    }
    if ($connection == 'NONSSL') {
      $link = HTTP_SERVER . DIR_WS_ADMIN;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL == 'true') {
        // A bug in the install routines cause the DIR_WS_ADMIN to be incorrectly set in the configuration file
        // This is a generated value.  To by pass this issue, DIR_WS_HTTPS_ADMIN will be used
        $link = HTTPS_SERVER . DIR_WS_HTTPS_ADMIN;
      } else {
        $link = HTTP_SERVER . DIR_WS_HTTP_ADMIN;
      }
    } else {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000">'.UNABLE_TO_DETERMINE_CONNECTION_METHOD_ON_PAGE_LINK.'tep_href_link(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</b>');
    }
    if ($parameters == '') {
      $link = $link . $page . '?' . SID;
    } else {
      $link = $link . $page . '?' . tep_output_string($parameters, false, true) . '&amp;' . SID;
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

    return $link;
  }

  function tep_catalog_href_link($page = '', $parameters = '', $connection = 'NONSSL') {
    global $request_type, $session_started, $SID, $spider_flag;

    if ($connection == 'NONSSL') {
      $link = HTTP_CATALOG_SERVER . DIR_WS_HTTP_CATALOG;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL_CATALOG == 'true') {
        $link = HTTPS_CATALOG_SERVER . DIR_WS_HTTPS_CATALOG;
      } else {
        $link = HTTP_CATALOG_SERVER . DIR_WS_HTTP_CATALOG;
      }
    } else {
     // die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL<br><br>Function used:<br><br>tep_href_link(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</b>');
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000">'.UNABLE_TO_DETERMINE_CONNECTION_METHOD_ON_PAGE_LINK.'tep_href_link(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</b>');
    }
    if ($parameters == '') {
      $link .= $page;
    } else {
      $link .= $page . '?' . $parameters;
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

    return $link;
  }

////
// The HTML image wrapper function
  function tep_image($src, $alt = '', $width = '', $height = '', $params = '') {
    $image = '<img src="' . $src . '" border="0" alt="' . tep_output_string($alt) . '"';
    if ($alt) {
      $image .= ' title=" ' . $alt . ' "';
    }
    if ($width) {
      $image .= ' width="' . $width . '"';
    }
    if ($height) {
      $image .= ' height="' . $height . '"';
    }
    if ($params) {
      $image .= ' ' . $params;
    }
    $image .= '>';

    return $image;
  }

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_image_submit($image='', $value = '-AltValueError-', $params = '') {
    global $language;
    $width = strlen(html_entity_decode($value)) * CSS_BUTTON_CHAR_WIDTH;
    $width = (int)$width;
    //if ($width < CSS_BUTTON_MIN_WIDTH) $style = ' style="width: ' . CSS_BUTTON_MIN_WIDTH . 'px;"';
    $style = '';//' style="width: ' . CSS_SUBMIT_BUTTON_WIDTH . 'px;"';
    $cssJS = 'onmouseover="$(this).addClassName(\'cssButtonSubmitOver\')" onmouseout="$(this).removeClassName=(\'cssButtonSubmitOver\')" onmousedown="$(this).addClassName(\'cssButtonSubmitActive\')" onmouseup="$(this).removeClassName(\'cssButtonSubmitActive\')"';
    $image_submit = '<input name="' . $value . '" class="' . CSS_BUTTON_SUBMIT_NORMAL_STYLE . '" ' . $cssJS . ' type="submit" value=" ' . $value . ' " ' . $params . $style . '>';
    return $image_submit;
  }

////
// Output a function button in the selected language
  function tep_image_button($image='', $value = '-AltValueError-', $params = '') {
    global $language;
    $width = strlen(html_entity_decode($value)) * CSS_BUTTON_CHAR_WIDTH;
    $width = (int)$width;
    //if ($width < CSS_BUTTON_MIN_WIDTH) $style = ' style="width: ' . CSS_BUTTON_MIN_WIDTH . 'px;"';
    $style = '';//' style="width: ' . CSS_IMAGE_BUTTON_WIDTH . 'px;"'; 
    $cssJS = 'onmouseover="$(this).addClassName(\'cssButtonOver\')" onmouseout="$(this).removeClassName(\'cssButtonOver\')" onmousedown="$(this).addClassName(\'cssButtonActive\')" onmouseup="$(this).removeClassName(\'cssButtonActive\')"';
    $image = '<span class="cssButton" ' . $cssJS . $params . $style . ' ><div style="text-align: center;">' . $value . '</div></span>';
    return $image;
  }

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_icon_submit($image, $alt = '', $parameters = '') {
    global $language;

    $image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_IMAGES .'icons/' . $image) . '" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= '>';

    return $image_submit;
  }

////
// Draw a 1 pixel black line
  function tep_black_line() {
    return tep_image(DIR_WS_IMAGES . 'pixel_black.gif', '', '100%', '1');
  }

////
// Output a separator either through whitespace, or with an image
  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);
  }

////
// javascript to dynamically update the states/provinces list when the country is changed
// TABLES: zones
  function tep_js_zone_list($country, $form, $field) {
    $countries_query = tep_db_query("select distinct zone_country_id from " . TABLE_ZONES . " order by zone_country_id");
    $num_country = 1;
    $output_string = '';
    while ($countries = tep_db_fetch_array($countries_query)) {
      if ($num_country == 1) {
        $output_string .= '  if (' . $country . ' == "' . $countries['zone_country_id'] . '") {' . "\n";
      } else {
        $output_string .= '  } else if (' . $country . ' == "' . $countries['zone_country_id'] . '") {' . "\n";
      }

      $states_query = tep_db_query("select zone_name, zone_id from " . TABLE_ZONES . " where zone_country_id = '" . $countries['zone_country_id'] . "' order by zone_name");

      $num_state = 1;
      while ($states = tep_db_fetch_array($states_query)) {
        if ($num_state == '1') $output_string .= '    ' . $form . '.' . $field . '.options[0] = new Option("' . PLEASE_SELECT . '", "");' . "\n";
        $output_string .= '    ' . $form . '.' . $field . '.options[' . $num_state . '] = new Option("' . $states['zone_name'] . '", "' . $states['zone_id'] . '");' . "\n";
        $num_state++;
      }
      $num_country++;
    }
    $output_string .= '  } else {' . "\n" .
                      '    ' . $form . '.' . $field . '.options[0] = new Option("' . TYPE_BELOW . '", "");' . "\n" .
                      '  }' . "\n";

    return $output_string;
  }

////
// Output a form
  function tep_draw_form($name, $action, $parameters = '', $method = 'post', $params = '', $connection = 'NONSSL') {
    $form = '<form name="' . tep_output_string($name) . '" action="';

    if (tep_not_null($parameters)) {
      $form .= tep_href_link($action, $parameters, $connection);
    } else {
      $form .= tep_href_link($action, '', $connection);
    }
    $form .= '" method="' . tep_output_string($method) . '"';
    if (tep_not_null($params)) {
      $form .= ' ' . $params;
    }
    $form .= '>';

    return $form;
  }

////
// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = '', $required = false, $type = 'text', $reinsert_value = true) {
    $field = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if (isset($GLOBALS[$name]) && ($reinsert_value == true) && is_string($GLOBALS[$name])) {
      $field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
    } elseif (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }

////
// Output a form password field
  function tep_draw_password_field($name, $value = '', $required = false, $parameters = '') {
    if (!tep_not_null($parameters)) {
      $parameters = 'maxlength="40"';
    }
    $field = tep_draw_input_field($name, $value, $parameters, $required, 'password', false);

    return $field;
  }


////
// Output a form filefield
 function tep_draw_file_field($name, $size = '25', $required = false) {
    $field = tep_draw_input_field($name, '', 'size=' . $size, $required, 'file');

    return $field;
  }





//Admin begin
////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
//  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $compare = '') {
//    $selection = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';
//
//    if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';
//
//    if ( ($checked == true) || (isset($GLOBALS[$name]) && is_string($GLOBALS[$name]) && ($GLOBALS[$name] == 'on')) || (isset($value) && isset($GLOBALS[$name]) && (stripslashes($GLOBALS[$name]) == $value)) || (tep_not_null($value) && tep_not_null($compare) && ($value == $compare)) ) {
//      $selection .= ' CHECKED';
//    }
//
//    $selection .= '>';
//
//    return $selection;
//  }
//
////
// Output a form checkbox field
//  function tep_draw_checkbox_field($name, $value = '', $checked = false, $compare = '') {
//    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $compare);
//  }
//
////
// Output a form radio field
//  function tep_draw_radio_field($name, $value = '', $checked = false, $compare = '') {
//    return tep_draw_selection_field($name, 'radio', $value, $checked, $compare);
//  }
////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $compare = '', $parameter = '') {
    $selection = '<input type="' . $type . '" name="' . $name . '"';
    if ($value != '') {
      $selection .= ' value="' . $value . '"';
    }
    if ( ($checked == true) || (isset($GLOBALS[$name]) && $GLOBALS[$name] == 'on') || ($value && (isset($GLOBALS[$name]) && $GLOBALS[$name] == $value)) || ($value && ($value == $compare)) ) {
      $selection .= ' CHECKED';
    }
    if ($parameter != '') {
      $selection .= ' ' . $parameter;
    }
    $selection .= '>';

    return $selection;
  }

////
// Output a form checkbox field
  function tep_draw_checkbox_field($name, $value = '', $checked = false, $compare = '', $parameter = '') {
    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $compare, $parameter);
  }


////
// Output a form radio field
  function tep_draw_radio_field($name, $value = '', $checked = false, $compare = '', $parameter = '') {
    return tep_draw_selection_field($name, 'radio', $value, $checked, $compare, $parameter);
  }
//Admin end

////
// Output a form textarea field
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    // the wrap is removed because it is not W3C standard and creates problem in IE
    $field = '<textarea name="' . tep_output_string($name) . '" id="' . tep_output_string($name) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= stripslashes($GLOBALS[$name]);
    } elseif (tep_not_null($text)) {
      $field .= $text;
    }

    $field .= '</textarea>';

    return $field;
  }

////
// Output a form hidden field
  function tep_draw_hidden_field($name, $value = '', $parameters = '') {
    $field = '<input type="hidden" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    } elseif (isset($GLOBALS[$name]) && is_string($GLOBALS[$name])) {
      $field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
  }

////
// Hide form elements
  function tep_hide_session_id() {
    return tep_draw_hidden_field(tep_session_name(), tep_session_id());
  }

////
// Output a form pull down menu
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    $field = '<select name="' . tep_output_string($name) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . (isset($values[$i]['id']) ? tep_output_string($values[$i]['id']) : '') . '"';
      if (isset($values[$i]['id']) && $default == $values[$i]['id']) {
        $field .= ' selected="selected"';
      }

      $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }
?>
