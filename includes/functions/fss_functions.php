<?php
/*
  $Id: fss_functions.php,v 1.0.0.0 2006/10/21 10:20:48 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  function tep_fss_get_forms_questions($forms_id) {
    global $languages_id;
    $questions_query = tep_db_query("select fq.questions_id, fq.questions_variable, fq.questions_type, fqd.questions_label, fqd.questions_help, fq.questions_layout from " . TABLE_FSS_QUESTIONS . " fq, " . TABLE_FSS_QUESTIONS_DESCRIPTION . " fqd, " . TABLE_FSS_QUESTIONS_TO_FORMS . " q2f where q2f.forms_id = '" . $forms_id . "' and fq.questions_id = fqd.questions_id and fqd.language_id = '" . $languages_id . "' and fq.questions_status = '1' and q2f.questions_id = fq.questions_id order by fq.sort_order");
    $return = array();
    while ($questions = tep_db_fetch_array($questions_query)) {
      $return[] = array('questions_id' => $questions['questions_id'],
                        'questions_variable' => $questions['questions_variable'],
                        'questions_type' => $questions['questions_type'],
                        'questions_layout' => $questions['questions_layout'],
                        'questions_label' => $questions['questions_label'],
                        'questions_help' => $questions['questions_help'],
                        'html' => tep_fss_get_html($questions['questions_id']));
    }
    return $return;
  }
  
  function tep_fss_get_html($questions_id, $values = '') {
    $str = '';
    $questions = tep_db_fetch_array(tep_db_query("select questions_id, questions_type, questions_variable, questions_layout from " . TABLE_FSS_QUESTIONS . " where questions_id = '" . $questions_id . "'"));
    if ($values == '') {
      $values = tep_fss_get_html_value($questions_id);
    }      
    if (tep_not_null($questions['questions_variable'])) {
      $name = addslashes($questions['questions_variable']);
    } else {
      $name = 'question_' . $questions_id;
    }
    if (isset($_SESSION['customer_id'])) {
      $customer_info = tep_fss_get_customer_info($_SESSION['customer_id']);
    } else {
      $customer_info = array();
    }
    $last_value = tep_fss_get_last_value($questions_id);
    switch ($questions['questions_type']) {
      case 'Input':
        if (!strstr($values['property'], 'value=')) {
          if ( isset($_GET[$name]) && tep_not_null($_GET[$name]) ) {
            $values['property'] .= ' ' . 'value="' . $_GET[$name] . '"';
          } elseif ( isset($_POST[$name]) && tep_not_null($_POST[$name]) ) {
            $values['property'] .= ' ' . 'value="' . $_POST[$name] . '"';
          } elseif ( isset($_SESSION[$name]) && tep_not_null($_SESSION[$name]) ) {
            $values['property'] .= ' ' . 'value="' . $_SESSION[$name] . '"';
          } elseif ( isset($customer_info[$name]) && tep_not_null($customer_info[$name]) ) {
            $values['property'] .= ' ' . 'value="' . $customer_info[$name] . '"';
          } elseif ( $last_value ) {
            $values['property'] .= ' ' . 'value="' . $last_value . '"';
          }
        }
        $str = '<input name="' . $name . '" type="text"' . $values['property'] . ' /> ' . (isset($values['append']) ? $values['append'] : '') . (isset($values['required']) ? $values['required'] : '');
        break;
      case 'Hidden':
        if (!strstr($values['property'], 'value=')) {
          if ( isset($_GET[$name]) && tep_not_null($_GET[$name]) ) {
            $values['property'] .= ' value="' . $_GET[$name] . '"';
          } elseif ( isset($_POST[$name]) && tep_not_null($_POST[$name]) ) {
            $values['property'] .= ' value="' . $_POST[$name] . '"';
          } elseif ( isset($_SESSION[$name]) && tep_not_null($_SESSION[$name]) ) {
            $values['property'] .= ' value="' . $_SESSION[$name] . '"';
          } elseif ( isset($customer_info[$name]) && tep_not_null($customer_info[$name]) ) {
            $values['property'] .= ' value="' . $customer_info[$name] . '"';
          } elseif ( $last_value ) {
            $values['property'] .= ' value="' . $last_value . '"';
          }
        }
        $str = '<input name="' . $name . '" type="hidden"' . $values['property'] . ' /> ' . $values['append'] . $values['required'];
        break;
      case 'Check Box':
        $str = '<table><tr>';
        foreach ($values['array'] as $key => $value) {
          $str .= '<td><input name="' . $name . '[]" type="checkbox" value="' . $value['value'] . '"' . ($last_value == $value['value'] ? ' checked="checked"' : '') . ' id="' . $name . '" /></td><td>' . $value['text'];
          if ($values['align'] == 'Vertical') {
            if ($key == (sizeof($values['array']) - 1)) {
              $str .= '&nbsp;&nbsp;&nbsp;' . (isset($values['append']) ? $values['append'] : '') . (isset($values['required']) ? $values['required'] : '');
              $str .= '</td></tr>';
            } else {
              $str .= '</td></tr><tr>';
            }
          } else {
            $str .= '</td>';
          }
        }
        if ($values['align'] == 'Horizontal') {
          $str .= '<td>&nbsp;&nbsp;&nbsp;' . (isset($values['append']) ? $values['append'] : '') . (isset($values['required']) ? $values['required'] : '') . '</td>';
          $str .= '</tr>';
        }
        $str .= '</table>';
        break;
      case 'Text Area':
        if ( $last_value ) {
          $values['internal'] = $last_value;
        }
        $str = '<textarea name="' . $name . '"' . $values['property'] . '>' . $values['internal'] . '</textarea> ' . (isset($values['append']) ? $values['append'] : '') . (isset($values['required']) ? $values['required'] : '');
        break;
      case 'File Upload':
        $str = '<input name="' . $name . '" type="file"' . $values['property'] . ' /> ' . (isset($values['append']) ? $values['append'] : '') . (isset($values['required']) ? $values['required'] : '');
        break;
      case 'Radio Button Group':        
        $str = '<table><tr>';
        foreach ($values['array'] as $key => $value) {
          $str .= '<td><input name="' . $name . '" type="radio" value="' . $value['value'] . '"' . ($last_value == $value['value'] ? ' checked="checked"' : '') . ' /></td><td>' . $value['text'];
          if ($values['align'] == 'Vertical') {
            if ($key == (sizeof($values['array']) - 1)) {
              $str .= '&nbsp;&nbsp;&nbsp;' . (isset($values['append']) ? $values['append'] : '') . (isset($values['required']) ? $values['required'] : '');
              $str .= '</td></tr>';
            } else {
              $str .= '</td></tr><tr>';
            }
          } else {
            $str .= '</td>';
          }
        }
        if ($values['align'] == 'Horizontal') {
          $str .= '<td>&nbsp;&nbsp;&nbsp;' . (isset($values['append']) ? $values['append'] : '') . (isset($values['required']) ? $values['required'] : '') . '</td>';
          $str .= '</tr>';
        }
        $str .= '</table>';
        break;        
      case 'Drop Down List':
        $str = '<select name="' . $name . '[]"' . $values['property'] . '">';
        foreach ($values['array'] as $value) {
          $str .= '<option value="' . $value['value'] . '"' . ($last_value == $value['value'] ? ' selected' : '') . '>' . $value['text'] . '</option>' . "\n";
        }
        $str .= '</select>' . (isset($values['append']) ? $values['append'] : '') . (isset($values['required']) ? $values['required'] : '');
        break;
      case 'Drop Down Menu':
        $str = '<select name="' . $name . '"' . $values['property'] . '">';
        foreach ($values['array'] as $value) {
          $str .= '<option value="' . $value['value'] . '"' . ($last_value == $value['value'] ? ' selected' : '') . '>' . $value['text'] . '</option>' . "\n";
        }
        $str .= '</select>' . $values['append'] . $values['required'];
        break;
    }
    if (isset($values['required']) && tep_not_null($values['required'])) {
      $required = true;
    } else {
      $required = false;
    }
    return array('str' => $str, 'required' => $required, 'param' => isset($values['param']) ? $values['param'] : '');
  }
  
  function tep_fss_get_html_value($questions_id, $values = '') {
    
    $str = array();
    $name = '';
    if ($values == '') {
      $values = array();
      $questions = tep_db_fetch_array(tep_db_query("select questions_variable, prefilled_variable, questions_type, questions_layout from " . TABLE_FSS_QUESTIONS . " where questions_id = '" . $questions_id . "'"));
      if (tep_not_null($questions['questions_variable'])) {
        $name = addslashes($questions['questions_variable']);
      } else {
        $name = 'question_' . $questions_id;
      }
      $values_query = tep_db_query("select fields_id, fields_value, fields_value_text from " . TABLE_FSS_QUESTIONS_FIELDS_VALUES . " where questions_id = '" . $questions_id . "'");
      while ($values_array = tep_db_fetch_array($values_query)) {
        $values[] = $values_array;
      }
    } 
    $str['property'] = '';
    $str['internal'] = '';
    $str['required'] = '';
    $str['param'] = '';
    $str['align'] = '';
    $str['array'] = '';
    $str['append'] = '';
    if (sizeof($values) == 0) {
      if ($questions['questions_type'] == 'Input') {
        if ( isset($_POST[$name]) && tep_not_null($_POST[$name]) ) {
          $str['property'] .= ' value="' . $_POST[$name] . '"';
        } elseif (tep_not_null($questions['prefilled_variable'])) {    
          $str['property'] .= ' value="' . tep_fss_get_prefilled($questions['prefilled_variable']) . '"';
        }
      }
    } else {
      foreach ($values as $value) {
        switch ($value['fields_id']) {
          case '1':
            $str['property'] .= ' size="' . $value['fields_value'] . '"';
            break;
          case '2':
          case '17':          
            if ( isset($_POST[$name]) && tep_not_null($_POST[$name]) ) {
              $real_value = $_POST[$name];
            } elseif (isset($questions['prefilled_variable']) && tep_not_null($questions['prefilled_variable'])) {    
              $real_value = tep_fss_get_prefilled($questions['prefilled_variable']);
            } else {
              $real_value = $value['fields_value'];
            }
            $str['property'] .= ' value="' . $real_value . '"';
            break;
          case '3':
          case '16':  
            if ($value['fields_value'] == 'on') {
              $str['required'] = ' <span class="inputRequirement">*</span>';
            }
            break;
          case '22':
            if (is_numeric($value['fields_value']) && $value['fields_value'] > 0) {
              $str['required'] = ' <span class="inputRequirement">*</span>';
              $str['param'] = $value['fields_value'];
            }
            break;
          case '10':
            $str['param'] = $value['fields_value'];
            break;
          case '5':
          case '21':
            $str['param'] = $value['fields_value'];
            break;
          case '6':
            $str['property'] .= ' cols="' . $value['fields_value'] . '"';
            break;
          case '7':
            $str['property'] .= ' rows="' . $value['fields_value'] . '"';
            break;
          case '11':
            $str['property'] .= ' size="' . $value['fields_value'] . '"';
            break;
          case '12':
            $str['property'] .= ' multiple="true"';
            break;
          case '9':
          case '14':
          case '23':
            $name_value_array = explode(',', $value['fields_value_text']);
            $pair = array();
            foreach ($name_value_array as $name_value) {
              $pair_array = explode('|', $name_value);
              $pair[] = array('text' => trim(isset($pair_array[0]) ? $pair_array[0] : ''),
                              'value' => trim(isset($pair_array[1]) ? $pair_array[1] : ''));
            }
            $str['array'] = $pair;
            break;
          case '15':
            if ($value['fields_value'] == 'Horizontal') {
              $str['align'] = 'Horizontal';
            } elseif (trim($value['fields_value']) == 'Vertical') {
              $str['align'] = 'Vertical';
            }
            break;
          case '18':
            $str['append'] .= ' ' . $value['fields_value_text'];
            break;
          case '19':
            if (tep_not_null($value['fields_value'])) {
              $str['param'] = $value['fields_value'];
              $str['required'] = ' <span class="inputRequirement">' . sprintf(TEXT_ONLY_ALLOW_FILE_EXT, trim($value['fields_value'])) . '</span>';
            }
            break;
          case '20':
            $str['internal'] .= $value['fields_value_text'];
            break;
          default:
            break;
        }
      }
    }
    return $str;
  }
  
  function tep_fss_get_forms_id_by_name($forms_name, $cat_id) {
    $forms = tep_db_fetch_array(tep_db_query("select ff.forms_id from " . TABLE_FSS_FORMS . " ff, " . TABLE_FSS_FORMS_DESCRIPTION . " ffd, " . TABLE_FSS_FORMS_TO_CATEGORIES . " ff2c where ff.forms_id = ffd.forms_id and ffd.language_id = '" . $_SESSION['languages_id'] . "' and ffd.forms_name = '" . $forms_name . "' and ff2c.forms_id = ff.forms_id and ff2c.categories_id = '" . $cat_id . "'"));
    return $forms['forms_id'];
  }
  
  function tep_fss_is_special_question($questions_variable) {
    $special_array = array('email_return', 'customer_id', 'product_id', 'order_id');
    if (in_array($questions_variable, $special_array)) {
      return true;
    } else {
      return false;
    }
  }
  
  function tep_fss_get_customer_info($customer_id) {
    $customer = tep_db_fetch_array(tep_db_query("select c.*, ab.* from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab where c.customers_id = '" . $customer_id . "' and c.customers_default_address_id = ab.address_book_id"));
    return $customer;
  }
  
  function tep_fss_check_required($questions) {
    $result = array();
    foreach ($questions as $question) {
      $question_id = $question['questions_id'];
      if (tep_not_null($question['questions_variable'])) {
        $name = addslashes($question['questions_variable']);
      } else {
        $name = 'question_' . $question_id;
      }
      if ($question['html']['required'] && $question['questions_type'] != 'File Upload') {
        if ( !tep_not_null($_POST[$name]) || (!tep_fss_dropdown_menu_first_item_check($question_id, $_POST[$name]))) {
          if ( $question['questions_type'] == 'Input' ) {
            $result[] = 'Please Input ' . $question['questions_label'] . '<br>';
          } else {
            $result[] = 'Please Select ' . $question['questions_label'] . '<br>';
          }
        }
      } elseif ($question['html']['required'] && $question['questions_type'] == 'File Upload') {
        if (! isset($_FILES[$name])) {
          $result[] = 'Please Input ' . $question['questions_label'] . '<br>';
        }
      }
    }
    if ( tep_not_null($result) ) {
      return $result;
    } else {
      return true;
    }
  }
  
  function tep_fss_dropdown_menu_first_item_check($question_id, $value) {
    $num = tep_db_num_rows(tep_db_query("select questions_id from " . TABLE_FSS_QUESTIONS_FIELDS_VALUES . " where questions_id = '" . $question_id . "' and fields_id = 10"));
    if ($num == 0) {
      return true;
    } else {
      $data = tep_db_fetch_array(tep_db_query("select fields_value_text from " . TABLE_FSS_QUESTIONS_FIELDS_VALUES . " where questions_id = '" . $question_id . "' and fields_id = '9'"));
      $data_array = explode(',', $data['fields_value_text']);
      $value_array = explode('|', trim($data_array[0]));
      if (trim($value_array[1]) == $value) {
        return false;
      }
    }
    return true;
  }
  
  function tep_fss_append_space($str, $len) {
    while (strlen($str) < $len) {
      $str .= ' ';
    }
    return $str;
  }
  
  function tep_fss_has_unanwsered_questions($customers_id) {
    global $languages_id;
    $ret = false;
    $forms_id = tep_fss_get_forms_id_by_name('Account', '1');
    if (tep_not_null($forms_id)) {
      $questions = tep_fss_get_forms_questions($forms_id);
      foreach ($questions as $question) {
        if ($question['html']['required']) {
          $post_query = tep_db_query("select ffp.forms_posts_id from " . TABLE_FSS_FORMS_POSTS . " ffp, " . TABLE_FSS_FORMS_POSTS_CONTENT. " ffpc where ffp.forms_posts_id = ffpc.forms_posts_id and ffp.forms_id = '" . $forms_id . "' and ffp.customers_id = '" . $customers_id . "' and ffpc.questions_id = '" . $question['questions_id'] . "'");
          if (tep_db_num_rows($post_query) == 0) {
            $ret = true;
            break;
          }
        }
      }      
    }
    return $ret;
  }
  
  function tep_fss_is_unanwsered_question($customers_id, $forms_id, $questions_id) {
    $ret = false;
    $questions = tep_db_fetch_array(tep_db_query("select updatable from " . TABLE_FSS_QUESTIONS . " where questions_id = '" . $questions_id . "'"));
    if ($questions['updatable'] == 0) {
      $post_query = tep_db_query("select ffpc.forms_fields_value from " . TABLE_FSS_FORMS_POSTS . " ffp, " . TABLE_FSS_FORMS_POSTS_CONTENT. " ffpc where ffp.forms_posts_id = ffpc.forms_posts_id and ffp.forms_id = '" . $forms_id . "' and ffp.customers_id = '" . $customers_id . "' and ffpc.questions_id = '" . $questions_id . "'");
      if (tep_db_num_rows($post_query) > 0) {
        $data = tep_db_fetch_array($post_query);
        $ret = $data['forms_fields_value'];
      }
    }
    return $ret;
  }
  
  function tep_fss_is_completed_survey($customers_id, $forms_id) {
    $post_query = tep_db_query("select ff.forms_id from " . TABLE_FSS_FORMS . " ff, " . TABLE_FSS_FORMS_POSTS . " ffp where ff.forms_status = '1' and ff.forms_type = '1' and ffp.forms_id = ff.forms_id and ffp.customers_id = '" . $customers_id . "' and ff.forms_id = '" . $forms_id . "'");
    if (tep_db_num_rows($post_query) > 0) {
      return true;
    } else {
      return false;
    }
  }
  
  function tep_fss_get_prefilled($prefilled_variable) {
    $array = explode('.', $prefilled_variable);
    $data = array();
    if (sizeof($array) > 1) {
      $table = $array[0];
      $field = $array[1];
      if ( isset($_GET['oID']) && tep_not_null($_GET['oID']) && tep_fss_has_field($table, array($field, 'orders_id')) ) {
        $data = tep_db_fetch_array(tep_db_query("select " . $field . " from " . $table . " where orders_id = '" . $_GET['oID'] . "'"));
      } elseif ( isset($_GET['products_id']) && tep_not_null($_GET['products_id']) && tep_fss_has_field($table, array($field, 'products_id')) ) {
        $data = tep_db_fetch_array(tep_db_query("select " . $field . " from " . $table . " where products_id = '" . $_GET['products_id'] . "'"));
      } elseif ( isset($_SESSION['customer_id']) && isset($_SESSION['customer_id']) && tep_fss_has_field($table, array($field, 'customers_id')) ) {
        $data = tep_db_fetch_array(tep_db_query("select " . $field . " from " . $table . " where customers_id = '" . $_SESSION['customer_id'] . "'"));
      }
    }
    return isset($data[$field]) ? $data[$field] : '';
  }
  
  function tep_fss_has_field($table, $fields) {
    $query = tep_db_query("show fields from " . $table);
    while ($data = tep_db_fetch_array($query)) {
      $field_array[] = $data['Field'];
    }
    $flag = true;
    foreach ($fields as $value) {
      if (!in_array($value, $field_array)) {
        $flag = false;
        break;
      }
    }
    return $flag;
  }
  
  function tep_fss_get_last_value($questions_id) {
    if (isset($_SESSION['customer_id'])) {
      $data = tep_db_fetch_array(tep_db_query("select ffpc.forms_fields_value from " . TABLE_FSS_FORMS_POSTS_CONTENT . " ffpc, " . TABLE_FSS_FORMS_POSTS . " ffp where ffp.forms_posts_id = ffpc.forms_posts_id and ffpc.questions_id = '" . $questions_id . "' and ffp.customers_id = '" . $_SESSION['customer_id'] . "' order by ffp.posts_date desc"));
      $ret = tep_not_null($data['forms_fields_value']) ? $data['forms_fields_value'] : false;
    } else {
      $ret = false;
    }
    return $ret;
  }
?>