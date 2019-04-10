<?php
/*
  $Id: fss_functions.php,v 1.0.0.0 2008/06/18 10:20:48 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  function tep_fss_get_html_code($name, $type, $questions_id) {
    $fields_val_count = tep_db_fetch_array(tep_db_query("SELECT count(*) as count FROM " . TABLE_FSS_VALUES_FIELDS . " fvf, " . TABLE_FSS_FIELDS_TO_VALUES . " ff2v WHERE fvf.fields_id = ff2v.fields_id AND ff2v.values_type_name = '" . $type . "' AND fvf.fields_validation = '1'"));
    $fields_query = tep_db_query("SELECT fvf.fields_name, fvf.fields_default_value, fvf.fields_remarks, fvf.fields_validation, fvf.fields_type, fvf.fields_value, ff2v.values_type_id FROM " . TABLE_FSS_VALUES_FIELDS . " fvf, " . TABLE_FSS_FIELDS_TO_VALUES . " ff2v WHERE fvf.fields_id = ff2v.fields_id AND ff2v.values_type_name = '" . $type . "' ORDER BY ff2v.sort_order");
    $str = '<table border="0" cellpadding="5" cellspacing="0"><tbody>' . "\n";
    $flag = true;
    $row = 0;
    while ($fields = tep_db_fetch_array($fields_query)) {
      $fields_id = tep_fss_get_fields_id($fields['values_type_id']);
      $fields_value = tep_fss_get_fields_value($questions_id, $fields_id);
      $fields_type = tep_fss_get_fields_type($fields_id);
      $row++;
      if ($fields_type == 'checkbox' && $fields_value != '') {
        $checked = true;
      } else {
        $checked = false;
      }
      if ($fields_value == '') {
        $fields_value = $fields['fields_value'];
      }
      if ($fields['fields_validation'] == '0') {
        $str .=  '  <tr>' . "\n";
        $str .=  '    <td class="' . ($row == 1 ? 'fss_left_header' : 'fss_left') . '" valign="top"><b>' . $fields['fields_name'] . '</b></td>' . "\n";
        switch ($fields['fields_type']) {
          case 'input':
            $str .=  '    <td class="' . ($row == 1 ? 'fss_header' : 'fss') . '">' . tep_draw_input_field('field_' . $fields['values_type_id'], $fields_value, 'id="field_' . $fields['values_type_id'] . '"') . '&nbsp;' . $fields['fields_remarks'] . '</td>' . "\n";
            break;
          case 'dropdownmenu':            
            $str .=  '    <td class="' . ($row == 1 ? 'fss_header' : 'fss') . '">' . tep_fss_get_dropdownmenu_code('field_' . $fields['values_type_id'], $fields['fields_value'], $fields_value) . '&nbsp;' . $fields['fields_remarks'] . '</td>' . "\n";
            break;
          case 'textarea':
            $str .=  '    <td class="' . ($row == 1 ? 'fss_header' : 'fss') . '">' . tep_draw_textarea_field('field_' . $fields['values_type_id'], '', '24', '3', $fields_value, 'id="field_' . $fields['values_type_id'] . '"') . '&nbsp;' . $fields['fields_remarks'] . '</td>' . "\n";
            break;
          case 'checkbox':
            $str .=  '    <td class="' . ($row == 1 ? 'fss_header' : 'fss') . '">' . tep_draw_checkbox_field('field_' . $fields['values_type_id'], $fields_value, $checked, '', 'id="field_' . $fields['values_type_id'] . '"') . '&nbsp;' . $fields['fields_remarks'] . '</td>' . "\n";
            break;
          case 'radiobutton':
            $str .=  '    <td class="' . ($row == 1 ? 'fss_header' : 'fss') . '">' . tep_fss_get_radiobutton_code('field_' . $fields['values_type_id'], $fields['fields_value'], $fields_value) . '&nbsp;' . $fields['fields_remarks'] . '</td>' . "\n";
            break;
          case 'dropdownmenudynamic':
            $str .=  '    <td class="' . ($row == 1 ? 'fss_header' : 'fss') . '" valign="middle">' . tep_fss_get_dropdownmenudynamic_code('field_' . $fields['values_type_id'], $fields_value, $fields['fields_default_value']) . '&nbsp;' . $fields['fields_remarks'] . '</td>' . "\n";
            break;
        }
        $str .=  '  </tr>' . "\n";
      } else {
        if ($flag) {
          $str .=  '  <tr>' . "\n";
          $str .=  '    <td rowspan="' . $fields_val_count['count'] . '" class="fss_left" valign="top"><b>Validation</b></td>' . "\n";
          $flag = false;
        } else {
          $str .=  '  <tr>' . "\n";
        }
        switch ($fields['fields_type']) {
          case 'input':
            $str .=  '    <td class="fss">' . $fields['fields_name'] . '&nbsp;' . tep_draw_input_field('field_' . $fields['values_type_id'], $fields_value, 'id="field_' . $fields['values_type_id'] . '"') . '&nbsp;' . $fields['fields_remarks'] . '</td>' . "\n";
            break;
          case 'checkbox':
            $str .=  '    <td class="fss">' . $fields['fields_name'] . '&nbsp;' . tep_draw_checkbox_field('field_' . $fields['values_type_id'], $fields_value, $checked, '', 'id="field_' . $fields['values_type_id'] . '"') . '&nbsp;' . $fields['fields_remarks'] . '</td>' . "\n";
            break;
        }
        $str .=  '  </tr>' . "\n";
      }
    }
    $str .= '</tbody></table>' . "\n";
    return $str;
  }
    
  function tep_fss_get_dropdownmenu_code($name, $fields_value, $default) {
    $value_array = explode(',', $fields_value);
    $row = array();
    foreach ($value_array as $dropdown_content) {
      $row[] = array('id' => $dropdown_content, 'text' => $dropdown_content);
    }
    return tep_draw_pull_down_menu($name, $row, $default, 'id="' . $name . '"');
  }
    
  function tep_fss_get_radiobutton_code($name, $fields_value, $default) {
    $value_array = explode(',', $fields_value);
    $ret_str = '';
    foreach ($value_array as $value) {
      if ($value == $default) {
        $ret_str .= tep_draw_radio_field($name, $value, true, '', 'id="' . $name . '"') . '&nbsp;' . $value . '&nbsp;&nbsp;&nbsp;&nbsp;';
      } else {
        $ret_str .= tep_draw_radio_field($name, $value, false, '', 'id="' . $name . '"') . '&nbsp;' . $value . '&nbsp;&nbsp;&nbsp;&nbsp;';
      }
    }
    return $ret_str;
  }
    
  function tep_fss_get_dropdownmenudynamic_code($name, $fields_value, $default) {
    $value_array = explode(',', $fields_value);
    $default_array = explode(',', $default);
    $str = '<table><tr><td>';
    $str .= tep_draw_hidden_field($name, '', 'id="' . $name . '"');
    $name = $name . '_' . $name;
    $str .= '<SELECT id="' . $name . '" name="' . $name . '" size="10" onchange="option_select(this.selectedIndex, \'' . $name . '\');">';
    for ($i = 0, $n = sizeof($value_array); $i < $n; $i++) {
      if (in_array($value_array[$i], $default_array)) {
        $str .= '<option value="'.$value_array[$i].'" selected="selected">'. $value_array[$i] .'</option>';
      } else {
        $str .= '<option value="'.$value_array[$i].'">'. $value_array[$i] .'</option>';
      }
    }
    $str .= '</select></td>';
    $str .= '<td><a href="javascript:option_delete(\'' . $name . '\');">[Delete]</a>&nbsp;&nbsp;&nbsp;<a href="javascript:moveup_option(\'' . $name . '\');">+</a>&nbsp;&nbsp;&nbsp;<a href="javascript:movedown_option(\'' . $name . '\');">-</a></td>';
    $str .= '<tr><td>Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Value:<br><input type="text" id="option_name" name="option_name" size="10" />&nbsp;&nbsp;&nbsp;<input type="text" id="option_value" name="option_value" size="5" /></td>';
    $str .= '<td>&nbsp;&nbsp;&nbsp;<a href="javascript:option_create(\'' . $name . '\');">Add</a>&nbsp;&nbsp;&nbsp;<a href="javascript:option_namechange(\'' . $name . '\');">Save</a></td></tr>';    
    $str .= '</tr></table>';
    return $str;
  }
    
  function tep_fss_get_fields_type($fields_id) {
    $fields_type = tep_db_fetch_array(tep_db_query("SELECT fields_type FROM " . TABLE_FSS_VALUES_FIELDS . " WHERE fields_id = '" . $fields_id . "'"));
    return $fields_type['fields_type'];
  }
    
  function tep_fss_get_fields_id($values_type_id) {
    $fields_id = tep_db_fetch_array(tep_db_query("SELECT fields_id FROM " . TABLE_FSS_FIELDS_TO_VALUES . " WHERE values_type_id = '" . $values_type_id . "'"));
    return $fields_id['fields_id'];
  }
    
  function tep_fss_get_fields_value($questions_id, $fields_id) {
    $fields_value = tep_db_fetch_array(tep_db_query("SELECT fields_value, fields_value_text FROM " . TABLE_FSS_QUESTIONS_FIELDS_VALUES . " WHERE questions_id = '" . $questions_id . "' AND fields_id = '" . $fields_id . "'"));
    $fields_type = tep_fss_get_fields_type($fields_id);
    if ($fields_type == 'textarea' || $fields_type == 'dropdownmenudynamic') {
      return $fields_value['fields_value_text'];
    } else {
      return $fields_value['fields_value'];
    }
  }
    
  function tep_fss_get_questions($forms_id = '') {
    $questions = array();
    if ($forms_id == '') {
      $questions_query = tep_db_query("SELECT fq.questions_id, fqd.questions_label, fq.questions_type, fq.updatable FROM " . TABLE_FSS_QUESTIONS . " fq, " . TABLE_FSS_QUESTIONS_DESCRIPTION . " fqd WHERE fq.questions_id = fqd.questions_id AND fqd.language_id = '" . $_SESSION['languages_id'] . "' ORDER BY fq.sort_order");
    } else {
      $questions_query = tep_db_query("SELECT fq.questions_id, fqd.questions_label, fq.questions_type, fq.updatable FROM " . TABLE_FSS_QUESTIONS . " fq, " . TABLE_FSS_QUESTIONS_DESCRIPTION . " fqd, " . TABLE_FSS_QUESTIONS_TO_FORMS . " fq2f WHERE fq.questions_id = fqd.questions_id AND fq2f.questions_id = fq.questions_id AND fq2f.forms_id = '" . $forms_id . "' AND fqd.language_id = '" . $_SESSION['languages_id'] . "' ORDER BY fq.sort_order");
    }
    while ($questions_array = tep_db_fetch_array($questions_query)) {
      $questions[] = array ('id' => $questions_array['questions_id'],
                            'label' => $questions_array['questions_label'],
                            'questions_type' => $questions_array['questions_type'],
                            'updatable' => $questions_array['updatable']);
    }
    return $questions;
  }
    
  function tep_get_folder_tree($parent_id = '0', $spacing = '', $exclude = '', $folder_tree_array = '', $include_itself = false) {
    if (!is_array($folder_tree_array)) $folder_tree_array = array();
    if ( (sizeof($folder_tree_array) < 1) && ($exclude != '0') ) $folder_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);
    if ($include_itself) {
      $folder_query = tep_db_query("SELECT fss_categories_name FROM " . TABLE_FSS_CATEGORIES . " WHERE fss_categories_id = '" . (int)$parent_id . "'");
      $folder = tep_db_fetch_array($folder_query);
      $folder_tree_array[] = array('id' => $parent_id, 'text' => $folder['fss_categories_name']);
    }
    $folder_query = tep_db_query("SELECT fss_categories_id, fss_categories_name, fss_categories_parent_id FROM " . TABLE_FSS_CATEGORIES . " WHERE fss_categories_parent_id = '" . (int)$parent_id . "' ORDER BY sort_order, fss_categories_name");
    while ($folder = tep_db_fetch_array($folder_query)) {
      if ($exclude != $folder['fss_categories_id']) $folder_tree_array[] = array('id' => $folder['fss_categories_id'], 'text' => $spacing . $folder['fss_categories_name']);
      $folder_tree_array = tep_get_folder_tree($folder['fss_categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $folder_tree_array);
    }
    return $folder_tree_array;
  }
    
  function tep_get_folder_str($categories_id) {
    $folder_tree_array = tep_get_folder_tree($categories_id, '', '0');
    $categories = '(' . $categories_id . ', ';
    foreach ($folder_tree_array as $value) {
      $categories .= $value['id'] . ', ';
    }
    $categories = substr($categories, 0, strlen($categories) - 2) . ')';
    return $categories;
  }
    
  function tep_childs_in_categories_count($categories_id) {
    $categories = tep_get_folder_str($categories_id);
    $childs = tep_db_fetch_array(tep_db_query("SELECT count(*) as total FROM " . TABLE_FSS_CATEGORIES . " WHERE fss_categories_parent_id in " . $categories));
    return $childs['total'];
  }
    
  function tep_forms_in_categories_count($categories_id) {
    $categories = tep_get_folder_str($categories_id);
    $childs = tep_db_fetch_array(tep_db_query("SELECT count(*) as total FROM " . TABLE_FSS_FORMS_TO_CATEGORIES . " WHERE categories_id in " . $categories));
    return $childs['total'];
  }
    
  function tep_delete_fss_categories($categories_id) {
    $categories_str = tep_get_folder_str($categories_id);
    $forms_query = tep_db_query("SELECT forms_id FROM " . TABLE_FSS_FORMS_TO_CATEGORIES . " WHERE categories_id in " . $categories_str);
    while ($forms = tep_db_fetch_array($forms_query)) {
      if (tep_db_num_rows(tep_db_query("SELECT forms_id FROM " . TABLE_FSS_FORMS_TO_CATEGORIES . " WHERE forms_id = '" . $forms['forms_id'] . "' AND categories_id not in " . $categories_str)) == 0) {
        tep_delete_fss_forms($forms['forms_id']);
      }
    }
    $categories = tep_get_folder_tree($categories_id, '', '0', '', true);
    for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
      tep_db_query("delete FROM " . TABLE_FSS_FORMS_TO_CATEGORIES . " WHERE categories_id = '" . $categories[$i]['id'] . "'");
      tep_db_query("delete FROM " . TABLE_FSS_CATEGORIES . " WHERE fss_categories_id = '" . $categories[$i]['id'] . "'");      
    }   
  }
    
  function tep_move_fss_categories($categories_id, $new_categories_id) {    
    $categories = tep_get_folder_tree($categories_id, '', '0');
    $flag = true;
    for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
      if ($new_categories_id == $categories[$i]['id']) {
        $flag = false;
      }
    }
    if ($flag) {
      tep_db_query("update " . TABLE_FSS_CATEGORIES . " set fss_categories_parent_id = '" . $new_categories_id . "' WHERE fss_categories_id = '" . $categories_id . "'");
    }
  }
    
  function tep_delete_fss_forms($forms_id) {
    tep_db_query("delete FROM " . TABLE_FSS_FORMS . " WHERE forms_id = '" . $forms_id . "'");
    tep_db_query("delete FROM " . TABLE_FSS_FORMS_DESCRIPTION . " WHERE forms_id = '" . $forms_id . "'");
    tep_db_query("delete FROM " . TABLE_FSS_FORMS_TO_CATEGORIES . " WHERE forms_id = '" . $forms_id . "'");
    tep_db_query("delete FROM " . TABLE_FSS_QUESTIONS_TO_FORMS . " WHERE forms_id = '" . $forms_id . "'");
  }
    
  function tep_move_fss_forms($forms_id, $new_categories_id, $old_categories_id) {
    tep_db_query("update " . TABLE_FSS_FORMS_TO_CATEGORIES . " set categories_id = '" . $new_categories_id . "' WHERE forms_id = '" . $forms_id . "' AND categories_id = '" . $old_categories_id . "'");
  }
    
  function tep_copy_fss_forms($forms_id, $new_categories_id, $copy_as) {
    if ($copy_as == 'link') {
      if (tep_db_num_rows(tep_db_query("SELECT forms_id FROM " . TABLE_FSS_FORMS_TO_CATEGORIES . " WHERE forms_id = '" . $forms_id . "' AND categories_id = '" . $new_categories_id . "'")) == 0) {
        tep_db_query("insert into " . TABLE_FSS_FORMS_TO_CATEGORIES . " values ('" . $forms_id . "', '" . $new_categories_id . "')");
      }
    } else {
      tep_db_query("insert into " . TABLE_FSS_FORMS . " (SELECT '', forms_status, forms_type, forms_post_name, send_email_to, send_post_data, enable_vvc, sort_order FROM " . TABLE_FSS_FORMS . " WHERE forms_id = '" . $forms_id . "')");
      $new_forms_id = tep_db_insert_id();
      tep_db_query("insert into " . TABLE_FSS_FORMS_DESCRIPTION . " (SELECT '" . $new_forms_id . "', language_id, forms_name, forms_confirmation_content, forms_description FROM " . TABLE_FSS_FORMS_DESCRIPTION . " WHERE forms_id = '" . $forms_id . "')");
      tep_db_query("insert into " . TABLE_FSS_FORMS_TO_CATEGORIES . " values ('" . $new_forms_id . "', '" . $new_categories_id . "')");
      tep_db_query("insert into " . TABLE_FSS_QUESTIONS_TO_FORMS . " (SELECT '" . $new_forms_id . "', questions_id FROM " . TABLE_FSS_QUESTIONS_TO_FORMS . " WHERE forms_id = '" . $forms_id . "')");
    }
  }
    
  function tep_get_forms() {
    $forms = array();
    $forms_query = tep_db_query("SELECT ff.forms_id, ffd.forms_name FROM " . TABLE_FSS_FORMS . " ff, " . TABLE_FSS_FORMS_DESCRIPTION . " ffd WHERE ff.forms_id = ffd.forms_id AND ffd.language_id = '" . $_SESSION['languages_id'] . "' ORDER BY ffd.forms_name");
    while ($forms_array = tep_db_fetch_array($forms_query)) {
      $forms[] = array ('id' => $forms_array['forms_id'],
                        'text' => $forms_array['forms_name']);
    }
    return $forms;
  }
    
  function tep_move_fss_question($questions_id, $new_forms_id, $old_forms_id) {
    tep_db_query("update " . TABLE_FSS_QUESTIONS_TO_FORMS . " set forms_id = '" . $new_forms_id . "' WHERE questions_id = '" . $questions_id . "' AND forms_id = '" . $old_forms_id . "'");
  }
    
  function tep_copy_fss_questions($questions_id, $new_forms_id, $copy_as) {
    if ($copy_as == 'link') {
      if (tep_db_num_rows(tep_db_query("SELECT questions_id FROM " . TABLE_FSS_QUESTIONS_TO_FORMS . " WHERE forms_id = '" . $new_forms_id . "' AND questions_id = '" . $questions_id . "'")) == 0) {
        tep_db_query("insert into " . TABLE_FSS_QUESTIONS_TO_FORMS . " values ('" . $new_forms_id . "', '" . $questions_id . "')");
      }
    } else {
      tep_db_query("insert into " . TABLE_FSS_QUESTIONS . " SELECT '', questions_variable, prefilled_variable, questions_type,questions_layout,  updatable, sort_order, questions_status, date_added FROM " . TABLE_FSS_QUESTIONS . " WHERE questions_id = '" . $questions_id . "'");
      $new_questions_id = tep_db_insert_id();
      tep_db_query("insert into " . TABLE_FSS_QUESTIONS_DESCRIPTION . " SELECT '" . $new_questions_id . "', language_id, questions_label, questions_help FROM " . TABLE_FSS_QUESTIONS_DESCRIPTION . " WHERE questions_id = '" . $questions_id . "'");
      tep_db_query("insert into " . TABLE_FSS_QUESTIONS_TO_FORMS . " values ('" . $new_forms_id . "', '" . $new_questions_id . "')");
      tep_db_query("insert into " . TABLE_FSS_QUESTIONS_FIELDS_VALUES . " SELECT '" . $new_questions_id . "', fields_id, fields_value, fields_value_text FROM " . TABLE_FSS_QUESTIONS_FIELDS_VALUES . " WHERE questions_id = '" . $questions_id . "'");
    }
  }
    
  function tep_get_forms_name($forms_id) {
    $forms = tep_db_fetch_array(tep_db_query("SELECT forms_name FROM " . TABLE_FSS_FORMS_DESCRIPTION . " WHERE forms_id = '" . $forms_id . "' AND language_id = '" . $_SESSION['languages_id'] . "'"));
    return $forms['forms_name'];
  }

  function tep_has_special_question($forms_id) {
    $query = tep_db_query("SELECT fq.questions_id FROM " . TABLE_FSS_QUESTIONS . " fq, " . TABLE_FSS_QUESTIONS_TO_FORMS . " q2f WHERE fq.questions_id = q2f.questions_id AND q2f.forms_id = '" . $forms_id . "' AND fq.questions_variable in ('email_return', 'customer_id', 'product_id', 'order_id')");
    if (tep_db_num_rows($query) == 4) {
      return true;
    } else {
      return false;
    }
  }
    
  function tep_is_special_question($questions_variable) {
    $special_array = array('email_return', 'customer_id', 'product_id', 'order_id');
    if (in_array($questions_variable, $special_array)) {
      return $questions_variable;
    } else {
      return false;
    }
  }
   
  function tep_fss_get_questions_type($questions_id) {
    $type = tep_db_fetch_array(tep_db_query("SELECT questions_type FROM " . TABLE_FSS_QUESTIONS . " WHERE questions_id = '" . $questions_id . "'"));
    return $type['questions_type'];
  }
    
  function tep_fss_get_questions_label($questions_id) {
    $label = tep_db_fetch_array(tep_db_query("SELECT questions_label FROM " . TABLE_FSS_QUESTIONS_DESCRIPTION . " WHERE questions_id = '" . $questions_id . "' AND language_id = '" . $_SESSION['languages_id'] . "'"));
    return $label['questions_label'];
  }
    
  function tep_fss_get_questions_special_data_type($questions_id) {
    $special_query = tep_db_query("SELECT ffp.orders_id, ffp.customers_id FROM " . TABLE_FSS_FORMS_POSTS . " ffp, " . TABLE_FSS_FORMS_POSTS_CONTENT . " ffpc WHERE ffp.forms_posts_id = ffpc.forms_posts_id AND ffpc.questions_id = '" . $questions_id . "' AND (ffp.orders_id <> 0 or ffp.customers_id <> 0)");
    if ( tep_db_num_rows($special_query) > 0 ) {
      $special = tep_db_fetch_array($special_query);
      if ( $special['orders_id'] != '' && $special['orders_id'] != '0') {
        return 'orders_id';
      } elseif ( $special['customers_id'] != '' && $special['customers_id'] != '0') {
        return 'customers_id';
      }
    }
    return false;
  }
   
  function tep_fss_get_special_str($type, $value) {
    switch ($type) {
      case 'customer_id':
        $customers = tep_db_fetch_array(tep_db_query("SELECT customers_firstname, customers_lastname FROM " . TABLE_CUSTOMERS . " WHERE customers_id = '" . $value . "'"));
        $str = '<a href="' . tep_href_link(FILENAME_CUSTOMERS, 'action=edit&cID=' . $value) . '">' . $customers['customers_firstname'] . ' ' . $customers['customers_lastname'] . '</a>';
        break;      
      case 'product_id':
        $products = tep_db_fetch_array(tep_db_query("SELECT products_name FROM " . TABLE_PRODUCTS_DESCRIPTION . " WHERE products_id = '" . $value . "' AND language_id = '" . $_SESSION['languages_id'] . "'"));
        $str = '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=new_product&pID=' . $value) . '">' . $products['products_name'] . '</a>';
        break;
      case 'order_id':
        $orders = tep_db_fetch_array(tep_db_query("SELECT title, text FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '" . $value . "' AND class = 'ot_total'"));
        $str = '<a href="' . tep_href_link(FILENAME_ORDERS, 'action=edit&oID=' . $value) . '">' . $orders['title'] . ' ' . $orders['text'] . '</a>';
        break;
      case 'url':
        $str = '<a href="' . $value . '" target="_blank">' . $value . '</a>';
        break;
      case 'file':
        if (file_exists(DIR_FS_CATALOG . FSS_UPLOAD_FILE_PATH . $value)) {
          $str = '<a href="' . HTTP_CATALOG_SERVER . DIR_WS_CATALOG . FSS_UPLOAD_FILE_PATH . $value . '" target="_blank">' . $value . '</a>';
        }
        break;
      default:
      case 'email_return':
        $customers = tep_db_fetch_array(tep_db_query("SELECT customers_firstname, customers_lastname FROM " . TABLE_CUSTOMERS . " WHERE customers_email_address = '" . addslashes($value) . "'"));
        if ( tep_not_null($customers['customers_firstname']) || tep_not_null($customers['customers_lastname']) ) {
          $str = '<a href="' . tep_href_link(FILENAME_MAIL, 'customer=' . $value) . '">' . $customers['customers_firstname'] . ' ' . $customers['customers_lastname'] . '</a>';
        } else {
         $str = '';
        }
        break;
    }
    return $str;
  }
   
  function tep_fss_has_activity_data($forms_id) {
    $num = tep_db_num_rows(tep_db_query("SELECT forms_posts_id FROM " . TABLE_FSS_FORMS_POSTS . " WHERE forms_id = '" . $forms_id . "'"));
    if ( $num > 0 ) {
      return true;
    } else {
      return false;
    }
  }
   
  function tep_fss_get_special_dropdown($forms_id) {
    $query = tep_db_query("SELECT fq.questions_variable FROM " . TABLE_FSS_QUESTIONS . " fq, " . TABLE_FSS_QUESTIONS_TO_FORMS . " q2f WHERE fq.questions_id = q2f.questions_id AND q2f.forms_id = '" . $forms_id . "' AND fq.questions_variable in ('email_return', 'customer_id', 'product_id', 'order_id')");
    $array = array();
    while ($data = tep_db_fetch_array($query)) {
      $array[] = $data['questions_variable'];
    }
    $special_type[] = array('id' => 'email_return', 'text' => 'Email Return');
    $special_type[] = array('id' => 'customer_id', 'text' => 'Customer ID');
    $special_type[] = array('id' => 'product_id', 'text' => 'Product ID');
    $special_type[] = array('id' => 'order_id', 'text' => 'Order ID');
    foreach ($special_type as $value) {
      if ( !in_array($value['id'], $array)) {
        $special[] = $value;
      }
    }
    return $special;
  }
    
  function tep_get_cross_customers($cross_reference) {
    $pos = strpos($cross_reference, '_');
    $questions_id = substr($cross_reference, 0, $pos);
    $field_value = substr($cross_reference, $pos + 1);
    $cross_customers = '';
    $customers_query = tep_db_query("SELECT distinct ffp.customers_id FROM " . TABLE_FSS_FORMS_POSTS . " ffp, " . TABLE_FSS_FORMS_POSTS_CONTENT . " ffpc WHERE ffp.forms_posts_id = ffpc.forms_posts_id AND ffpc.questions_id = '" . $questions_id . "' AND ffpc.forms_fields_value = '" . $field_value . "' AND ffp.customers_id > 0");
    while ($customers = tep_db_fetch_array($customers_query)) {
      $cross_customers .= $customers['customers_id'] . ', ';
    }
    if ($cross_customers != '') {
      $cross_customers = ' AND customers_id in (' . substr($cross_customers, 0, strlen($cross_customers) - 2) . ')';
    }
    return $cross_customers;
  }
    
  function tep_fss_get_form_overtime_report($forms_id, $period_from, $period_to, $cross_reference = '') {
    if ($cross_reference != '') {
      $cross_customers = tep_get_cross_customers($cross_reference);
    } else {
      $cross_customers = '';
    }
    $date = array('start' => urldecode($period_from), 'end' => urldecode($period_to));
    $report_query_raw = "SELECT count(posts_date) as count, month(posts_date) as i_month, dayofmonth(posts_date) as i_day, substring(year(posts_date), 3) as i_year FROM " . TABLE_FSS_FORMS_POSTS . " WHERE forms_id = '" . $forms_id . "'" . $cross_customers;
    if ( $date['start'] != '' && $date['end'] != '' ) {
      $report_query_raw .= " AND posts_date between '" . $date['start'] . "' AND '" . $date['end'] . "'";
    }
    $report_query_raw .= " GROUP BY i_year, i_month, i_day ORDER BY posts_date";
    $report_query = tep_db_query($report_query_raw);
    $i = 0;
    $num = tep_db_num_rows($report_query);
    $x = array();
    $y = array();
    $data = array();
    while ( $report_data = tep_db_fetch_array($report_query) ) {
      $x[] = $report_data['i_month'] . '/' . $report_data['i_day'] . '/' . $report_data['i_year'];
      $data[$i] = $report_data['count'];
      $i++;
    }
    if ($i > 0) {
      $y = tep_fss_get_scale_y($data);
      $x = tep_fss_get_scale_x($x);
    }
    return array('x' => $x, 'y' => $y, 'data' => $data);
  }
    
  function tep_fss_get_scale_x($data) {    
    $max = sizeof($data) - 1;
    $s = '';
    if ( $max > 8) {      
      $s[] = $data[0];
      $s[] = $data[(int)($max / 8)];
      $s[] = $data[(int)($max / 4)];
      $s[] = $data[(int)($max / 8 * 3)];
      $s[] = $data[(int)($max / 2)];
      $s[] = $data[(int)($max / 8 * 5)];
      $s[] = $data[(int)($max / 4 * 3)];
      $s[] = $data[(int)($max / 8 * 7)];
      $s[] = $data[$max];
    } else {
      for ($i = 0; $i < $max; $i++) {
       $s[] = $data[$i];
      }
      if ($max == 1) {
        $s[] = $data[0];
      }
    }
    return $s;
  }
    
  function tep_fss_get_scale_y($data) {
    $s[] = 0;
    $max = max($data);
    $max += pow(2, (strlen($max) - 1));
    $max = (10 - ($max % 10)) + $max;
    $s[] = $max / 4;
    $s[] = $max / 2;
    $s[] = $max / 4 * 3;
    $s[] = $max;
    return $s;
  }
    
  function tep_fss_no_data_img($str) {
    if (!empty($str)) {
      $imwidth=strlen($str) * 10;
      $imheight=30;
      Header("Content-type: image/Jpeg");
      $im = @ImageCreate ($imwidth, $imheight) or die ("ERROR! Cannot create new GD image");
      $background_color = ImageColorAllocate ($im, 255, 255, 255);
      $border_color = ImageColorAllocate ($im, 255, 255, 255);
      $text_color = ImageColorAllocate ($im, 0, 0, 0);
      imagestring($im, 5, 3, 5, $str, $text_color);
      imagerectangle ($im, 2, 2, $imwidth-2, $imheight-2, $border_color);
      ImageJpeg($im);
      ImageDestroy;
    }
  }
    
  function tep_fss_image_label($r, $g, $b) {
    $imwidth = 30;
    $imheight = 10;
    Header("Content-type: image/Jpeg");
    $im = @ImageCreate ($imwidth, $imheight) or die ("ERROR! Cannot create new GD image");
    $background_color = ImageColorAllocate ($im, $r, $g, $b);
    $border_color = ImageColorAllocate ($im, 255, 255, 255);
    imagerectangle ($im, -1, -1, $imwidth, $imheight, $border_color);
    ImageJpeg($im);
    ImageDestroy;
  }
    
  function tep_fss_get_question_report($questions_id, $period_from, $period_to, $cross_reference = '') {
    $data = array();
    $x = array();
    $percentage = tep_fss_get_values_percentage($questions_id, $period_from, $period_to, $cross_reference);
    foreach ($percentage as $value) {
      $x[] = tep_not_null($value['fields_value']) ? $value['fields_value'] : 'none';
      $data[] = $value['count'];
    }
    return array('x' => $x, 'data' => $data);
  }
   
  function tep_fss_get_question_overtime_report($questions_id, $period_from, $period_to, $fields_value = '') {
    $date = array('start' => urldecode($period_from), 'end' => urldecode($period_to));
    $data = array();
    $x = array(); 
    $y = array();
    $x_axle = array();
    $max_y = 0;
    $query_raw = "SELECT distinct forms_fields_value FROM " . TABLE_FSS_FORMS_POSTS_CONTENT . " WHERE questions_id = '" . $questions_id . "'";
    if ( tep_not_null($fields_value) && $fields_value != 'all' ) {
      $query_raw .= " AND forms_fields_value = '" . $fields_value . "'";
    }
    $query = tep_db_query($query_raw);
    while ($values = tep_db_fetch_array($query)) {
      $report_query_raw = "SELECT count(ffp.posts_date) as count, month(ffp.posts_date) as i_month, dayofmonth(ffp.posts_date) as i_day, substring(year(ffp.posts_date), 3) as i_year FROM " . TABLE_FSS_FORMS_POSTS . " ffp, " . TABLE_FSS_FORMS_POSTS_CONTENT . " ffpc WHERE ffpc.forms_posts_id = ffp.forms_posts_id AND ffpc.questions_id = '" . $questions_id . "' AND ffpc.forms_fields_value = '" . $values['forms_fields_value'] . "'";
      if ( $date['start'] != '' && $date['end'] != '' ) {
        $report_query_raw .= " AND ffp.posts_date between '" . $date['start'] . "' AND '" . $date['end'] . "'";
      }
      $report_query_raw .= " GROUP BY i_year, i_month, i_day ORDER BY ffp.posts_date";
      $report_query = tep_db_query($report_query_raw);
      $i = 0;      
      if ( trim($values['forms_fields_value']) == '' ) {
        $name = 'none';
      } else {
        $name = $values['forms_fields_value'];
      }
      while ( $report_data = tep_db_fetch_array($report_query) ) {
        $x_axle[$name][$i] = $report_data['i_month'] . '/' . $report_data['i_day'] . '/' . $report_data['i_year'];
        $data[$name][$i] = $report_data['count'];
        if ( $max_y < $report_data['count'] ) {
          $max_y = $report_data['count'];
        }
        $i++;
      }
      if ($i > 0) {
        $y = tep_fss_get_scale_y(array(0, $max_y));        
      }
    }
    $max = 0;
    $max_key = '';
    foreach ($data as $key => $value) {
      if ($max < sizeof($value)) {
        $max = sizeof($value);
        $max_key = $key;
      }
    }
    foreach ($data as $key => $value) {
      for ($i = 0; $i < $max; $i++) {
        if ( !isset($value[$i]) ) {
          $data[$key][$i] = 0;
        }
      }
    }
    if ( tep_not_null($y) ) {
      $x = tep_fss_get_scale_x($x_axle[$max_key]);
    }
    return array('x' => $x, 'y' => $y, 'data' => $data);
  }
    
  function tep_fss_get_array_id($array, $needle) {
    foreach($array as $key => $value) {
      if ($needle == $value) {
        return $key;
      }
    }
    return false;
  }

  function tep_fss_get_values_percentage($questions_id, $period_from, $period_to, $cross_reference = '') {
    if ($cross_reference != '') {
      $cross_customers = tep_get_cross_customers($cross_reference);
    } else {
      $cross_customers = '';
    }
    $date = array('start' => urldecode($period_from), 'end' => urldecode($period_to));
    $data_tmp = tep_db_fetch_array(tep_db_query("SELECT ffp.customers_id FROM " . TABLE_FSS_FORMS_POSTS . " ffp, " . TABLE_FSS_FORMS_POSTS_CONTENT . " ffpc WHERE ffp.forms_posts_id = ffpc.forms_posts_id AND ffpc.questions_id = '" . $questions_id . "'" . ($date['start'] != '' && $date['end'] != '' ? " AND ffp.posts_date between '" . $date['start'] . "' AND '" . $date['end'] . "'" : '')));
    if ($data_tmp['customers_id'] != '0') {
      $query_raw = "SELECT ffp.customers_id, ffpc.forms_fields_value FROM " . TABLE_FSS_FORMS_POSTS_CONTENT . " ffpc, " . TABLE_FSS_FORMS_POSTS . " ffp WHERE ffp.forms_posts_id = ffpc.forms_posts_id AND questions_id = '" . $questions_id . "'" . $cross_customers;
      if ( $date['start'] != '' && $date['end'] != '' ) {
        $query_raw .= " AND ffp.posts_date between '" . $date['start'] . "' AND '" . $date['end'] . "'";
      }
      $query_raw .= " ORDER BY ffp.posts_date";
      $customers = array();
      $fields_value = array();
      $query = tep_db_query($query_raw);
      $values = array();
      $total = 0;
      while ($data = tep_db_fetch_array($query)) {
        $values_array = explode(',', $data['forms_fields_value']);
        foreach ($values_array as $value) {
          $value = trim($value);
          $customers[$data['customers_id']] = $value;
          if (!in_array($value, $fields_value)) {
            $fields_value[] = $value;
            $len = sizeof($fields_value);
            $values[$len - 1] = array('fields_value' => $value,
                                      'count' => 1);
          } else {
            $id = tep_fss_get_array_id($fields_value, $value);
            $values[$id] = array('fields_value' => $value,
                                 'count' => $values[$id]['count'] + 1);
          }
          $total++;
        }
      }
/*
      $values = array();
      $total = 0;
      foreach ($customers as $key => $value) {
        $id = tep_fss_get_array_id($fields_value, $value);
        $values[$id] = array('fields_value' => $value,
                             'count' => $values[$id]['count'] + 1);
        $total++;
      }
*/
      foreach ($values as $key => $value) {
        $values[$key]['percentage'] = number_format($value['count'] / $total * 100, 2);
      }
    } else {
      $query_raw = "SELECT ffpc.forms_fields_value FROM " . TABLE_FSS_FORMS_POSTS_CONTENT . " ffpc, " . TABLE_FSS_FORMS_POSTS . " ffp WHERE ffp.forms_posts_id = ffpc.forms_posts_id AND questions_id = '" . $questions_id . "'" . $cross_customers;
      if ( $date['start'] != '' && $date['end'] != '' ) {
        $query_raw .= " AND ffp.posts_date between '" . $date['start'] . "' AND '" . $date['end'] . "'";
      }
//      $query_raw .= " GROUP BY forms_fields_value";
      $query = tep_db_query($query_raw);
      $total = 0;
      $values = array();
      $fields_array = array();
      while ($data = tep_db_fetch_array($query)) {
        $values_array = explode(',', $data['forms_fields_value']);
        foreach ($values_array as $value) {
          $value = trim($value);
          if (!in_array($value, $fields_array)) {
            $fields_array[] = $value;
          }
          $id = tep_fss_get_array_id($fields_array, $value);
          
          $values[$id] = array('fields_value' => $value,
                               'count' => $values[$id]['count'] + 1);
        }
        $total++;
      }
      foreach ($values as $key => $value) {
        $values[$key]['percentage'] = number_format($value['count'] / $total * 100, 2);
      }
    }
    $values_tmp = array();
    foreach ($values as $value) {
      $values_tmp[] = $value;
    }
    $values = $values_tmp;
    $switch_flag = true;
    while ($switch_flag) {
      $switch_flag = false;
      for ($i = 0; $i < sizeof($values) - 1; $i++) {
        if ($values[$i]['count'] < $values[$i + 1]['count']) {
          $tmp = $values[$i + 1];
          $values[$i + 1] = $values[$i];
          $values[$i] = $tmp;
          $switch_flag = true;
        }
      }
    }
    return $values;
  }
    
  function tep_fss_get_folder_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false) {
     if (!is_array($category_tree_array)) $category_tree_array = array();
     if ( (sizeof($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);
     if ($include_itself) {
      $category_query = tep_db_query("SELECT fss_categories_name as categories_name FROM " . TABLE_FSS_CATEGORIES . " WHERE fss_categories_id = '" . (int)$parent_id . "'");
      $category = tep_db_fetch_array($category_query);
      $category_tree_array[] = array('id' => $parent_id, 'text' => $category['categories_name']);
    }
    $categories_query = tep_db_query("SELECT fss_categories_id as categories_id, fss_categories_name as categories_name, fss_categories_parent_id as parent_id FROM " . TABLE_FSS_CATEGORIES . " WHERE fss_categories_parent_id = '" . (int)$parent_id . "' ORDER BY sort_order, fss_categories_name");
    while ($categories = tep_db_fetch_array($categories_query)) {
      if ($exclude != $categories['categories_id']) $category_tree_array[] = array('id' => $categories['categories_id'], 'text' => $spacing . $categories['categories_name']);
      $category_tree_array = tep_fss_get_folder_tree($categories['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);
    }
    return $category_tree_array;
  }
    
  function tep_fss_export_excel($filter, $period_from, $period_to, $questions_id, $type) {
    $date = array('start' => urldecode($period_from), 'end' => urldecode($period_to));
    $str = '';
    switch ($type) {
      case 'customer':
        $customers_query_raw = "SELECT distinct ffp.customers_id FROM " . TABLE_FSS_FORMS_POSTS . " ffp, " . TABLE_FSS_FORMS_POSTS_CONTENT . " ffpc WHERE ffp.forms_posts_id = ffpc.forms_posts_id AND ffpc.questions_id = '" . $questions_id . "'";
        if ( tep_not_null($filter) ) {
          $customers_query_raw .= " AND (ffpc.forms_fields_value LIKE '" . $filter . ",%' OR ffpc.forms_fields_value LIKE '%, " . $filter . ",%' OR ffpc.forms_fields_value LIKE '%, " . $filter . "')";
        }
        if ( $date['start'] != '' && $date['end'] != '' ) {
          $customers_query_raw .= " AND ffp.posts_date between '" . $date['start'] . "' AND '" . $date['end'] . "'";
        }  
        $customers_query = tep_db_query($customers_query_raw);
        $customers_ids = "'0', ";
        while ($customers = tep_db_fetch_array($customers_query)) {
          $customers_ids .= $customers['customers_id']. ', ';
        }
        $customers_ids = substr($customers_ids, 0, strlen($customers_ids) - 2);
        $customers_query_raw = "SELECT c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address, ab.entry_telephone as customers_telephone, ab.entry_state, ab.entry_country_id, ab.entry_zone_id FROM " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab WHERE c.customers_id in (" . $customers_ids . ") AND c.customers_default_address_id = ab.address_book_id";
        $customers_query = tep_db_query($customers_query_raw);
        while ($customers = tep_db_fetch_array($customers_query)) {
          $str .= '"' . tep_fss_quote($customers['customers_firstname']) . '","' . tep_fss_quote($customers['customers_lastname']) . '","' . tep_fss_quote(tep_get_zone_name($customers['entry_country_id'], $customers['entry_zone_id'], $customers['entry_state'])) . '","' . tep_fss_quote(tep_get_country_name($customers['entry_country_id'])) . '","' . tep_fss_quote($customers['customers_email_address']) . '","' . tep_fss_quote($customers['customers_telephone']) . '"' . "\r\n";
        }
        $savename='customers.csv';
        break;
      case 'order':
        $orders_query_raw = "SELECT o.orders_id, o.customers_name, o.date_purchased, os.orders_status_name, ot.text as order_total FROM " . TABLE_FSS_FORMS_POSTS . " ffp, " . TABLE_FSS_FORMS_POSTS_CONTENT . " ffpc, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " os, " . TABLE_ORDERS_TOTAL . " ot WHERE ffp.forms_posts_id = ffpc.forms_posts_id AND o.orders_id = ffp.orders_id AND o.orders_status = os.orders_status_id AND os.language_id = '" . $_SESSION['languages_id'] . "' AND o.orders_id = ot.orders_id AND ot.class = 'ot_total' AND ffpc.questions_id = '" . $questions_id . "'";
        if ( tep_not_null($filter) ) {
          $orders_query_raw .= " AND (ffpc.forms_fields_value LIKE '" . $filter . ",%' OR ffpc.forms_fields_value LIKE '%, " . $filter . ",%' OR ffpc.forms_fields_value LIKE '%, " . $filter . "')";
        }
        if ( $date['start'] != '' && $date['end'] != '' ) {
          $orders_query_raw .= " AND ffp.posts_date between '" . $date['start'] . "' AND '" . $date['end'] . "'";
        }
        $orders_query = tep_db_query($orders_query_raw);
        while ($orders = tep_db_fetch_array($orders_query)) {
          $str .= '"' . tep_fss_quote($orders['customers_name']) . '","' . tep_fss_quote(strip_tags($orders['order_total'])) . '","' . tep_fss_quote($orders['date_purchased']) . '","' . tep_fss_quote($orders['orders_status_name']) . '"' . "\r\n";
        }
        $savename='orders.csv';
        break;
    }    
    header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
    header("Last-Modified: " . gmdate('D,d M Y H:i:s') . ' GMT');
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-Type: Application/octet-stream");
    header("Content-Disposition: attachment; filename=$savename");
    echo $str;
    die();
  }
    
  function tep_fss_quote($str) {
    return str_replace('"', '""', $str);
  }

  function tep_get_questions_forms($questions_id) {
    $query = tep_db_query("SELECT ffd.forms_id, ffd.forms_name FROM " . TABLE_FSS_QUESTIONS_TO_FORMS . " fq2f, " . TABLE_FSS_FORMS_DESCRIPTION . " ffd WHERE ffd.forms_id = fq2f.forms_id AND fq2f.questions_id = '" . $questions_id . "' AND ffd.language_id = '" . $_SESSION['languages_id'] . "'");
    $forms = array();
    while ($data = tep_db_fetch_array($query)) {
      $forms[] = array('id' => $data['forms_id'],
                       'name' => $data['forms_name']);
    }
    return $forms;
  }
  
  function tep_count_forms_posts($forms_id) {
    $posts_query = tep_db_query("SELECT COUNT(*) AS number FROM " . TABLE_FSS_FORMS_POSTS . " WHERE forms_id = '" . $forms_id . "'");
    $data = tep_db_fetch_array($posts_query);
    return $data['number'];
  }
?>