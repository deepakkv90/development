<?php
function options_import($filename) {
  if (!file_exists($filename)) {
    return;
  }
  
  $split = "\t";
  $type_array1 = array(0, 2, 3);
  $type_array2 = array(1, 4);
  $fhandle = @fopen($filename ,'r');
  if ($fhandle) {
    $buffer = fgets($fhandle);
    for ($i=0;$i<5;$i++) {
      $str = get_next_str($buffer, $split);
    }
    $lang = array();
    while (trim($str) <> 'Action') {
      $lang_id = (int)substr($str, -1, strlen($str) - strrpos($str, '_'));
      if (!in_array($lang_id, $lang)) {
        $lang[] = $lang_id;
      }     
      $str = get_next_str($buffer, $split);            
    }
    while (!feof($fhandle)) {
      $buffer = fgets($fhandle);
      if (trim($buffer) == '') {
        continue;
      }
      $options_id = get_next_str($buffer, $split);
      $options_type = translate_name_to_type(get_next_str($buffer, $split));
      $options_length = get_next_str($buffer, $split);
      if (($options_type != 1) && ($options_type != 4)) {
        $options_length = 0;
      }
      $options_sort_order = get_next_str($buffer, $split);
      for ($i=0;$i<sizeof($lang);$i++) {
        $options_name[$lang[$i]] = get_next_str($buffer, $split);
        $options_instruct[$lang[$i]] = get_next_str($buffer, $split);
      }
      $action = get_next_str($buffer, $split);
      $sql_query = tep_db_query("select products_options_id, options_type from products_options where products_options_id = '" . $options_id . "'");
      if (tep_db_num_rows($sql_query) > 0) {
        if (trim($action) == 'delete') {
          tep_db_query("delete from products_options where products_options_id = '" . $options_id . "'");
          tep_db_query("delete from products_options_text where products_options_text_id = '" . $options_id . "'");
        } else {
          $sql_array = tep_db_fetch_array($sql_query);
          $sql_query_count = tep_db_query("select count(*) from products_options_values_to_products_options where products_options_id = '" . $options_id . "'");
          if ((in_array($sql_array['options_type'], $type_array1) && in_array($options_type, $type_array1)) || (in_array($sql_array['options_type'], $type_array2) && in_array($options_type, $type_array2)) || (tep_db_num_rows($sql_query_count) == 0)) {
            tep_db_query("update products_options set options_type = '" . $options_type . "', options_length = '" . $options_length . "', products_options_sort_order = '" . $options_sort_order . "' where products_options_id = '" . $options_id . "'");
            for ($i=0;$i<sizeof($lang);$i++) {
              tep_db_query("update products_options_text set products_options_name = '" . $options_name[$lang[$i]] . "', products_options_instruct = '" . $options_instruct[$lang[$i]] . "' where products_options_text_id = '" . $options_id . "' and language_id = '" . $lang[$i] . "'");
            }
          }
        }
      } else {
          if ($options_id != '') {
            $new_options_id = (int)$options_id;
          } else {
            $new_options = tep_db_fetch_array(tep_db_query("select max(products_options_id) as opt_id from products_options"));
            $new_options_id = (int)$new_options['opt_id'] + 1;
          }
        tep_db_query("insert into products_options (products_options_id, options_type, options_length, products_options_sort_order, options_date_added) values ('" . $new_options_id . "', '" . $options_type . "', '" . $options_length . "', '" . $options_sort_order . "', now())");
        $options_id = tep_db_insert_id();
        for ($i=0;$i<sizeof($lang);$i++) {
          tep_db_query("insert into products_options_text (products_options_text_id, language_id, products_options_name, products_options_instruct) values ('" . $new_options_id . "', '" . $lang[$i] . "', '" . $options_name[$lang[$i]] . "', '" . $options_instruct[$lang[$i]] . "')");
        }
      }
    }
    fclose($fhandle);
  }
  tep_redirect(tep_href_link(FILENAME_EASYPOPULATE_OPTIONS_IMPORT));
}

function options_import_check($filename) {
  if (!file_exists($filename)) {
    return EASY_INFO_FILE_NOT_FOUND;
  }
  $split = "\t";
  $ret_msg = '';
  $type_array1 = array(0, 2, 3);
  $type_array2 = array(1, 4);
  $fhandle = @fopen($filename ,'r');
  if ($fhandle) {
    $buffer = fgets($fhandle);
    $db_table_query = tep_db_query("SHOW COLUMNS FROM products_options");
    while ($db_table_array = tep_db_fetch_array($db_table_query)) {
      $db_fields[] = $db_table_array['Field'];
    }
    $db_table_query = tep_db_query("SHOW COLUMNS FROM products_options_text");
    while ($db_table_array = tep_db_fetch_array($db_table_query)) {
      $db_fields[] = $db_table_array['Field'];
    }
    for ($i=0;$i<4;$i++) {
      $str_header[$i] = substr(get_next_str($buffer, $split), 2);
      if (!in_array($str_header[$i], $db_fields)) {
        $ret_msg .= sprintf(EASY_INFO_CHECK_ERROR1, $str_header[$i], 'products_options') . '<br>';
      }
    }
    $lang = array();
    $str = get_next_str($buffer, $split);
    $str_header[] = substr($str, 2);
    while (trim($str) <> 'Action') {
      $lang_id = (int)substr($str, -1, strlen($str) - strrpos($str, '_'));
      if (!in_array($lang_id, $lang)) {
        $lang[] = $lang_id;
        $str = substr($str, 2, strrpos($str, '_') - 2);
        $str_header[] = $str;
        if (!in_array($str, $db_fields)) {
        $ret_msg .= sprintf(EASY_INFO_CHECK_ERROR1, $str_header[$i], 'products_options_text') . '<br>';
      }
      }     
      $str = get_next_str($buffer, $split); 
    }
    if ($ret_msg != '') {
      return $ret_msg;
    }
    $line_count = 0;
    $del_count = 0;
    $update_count = 0;
    $insert_count = 0;
    $err_record = '';
    $err_count = 0;
    while (!feof($fhandle)) {
      $buffer = fgets($fhandle);
      if (trim($buffer) == '') {
        continue;
      }
      $line_count++;
      $options_id = get_next_str($buffer, $split);
      $options_type = translate_name_to_type(get_next_str($buffer, $split));
      $options_length = get_next_str($buffer, $split);
      if (($options_type != 1) && ($options_type != 4)) {
        $options_length = 0;
      }
      $options_sort_order = get_next_str($buffer, $split);
      for ($i=0;$i<sizeof($lang);$i++) {
        $options_name[$lang[$i]] = get_next_str($buffer, $split);
        $options_instruct[$lang[$i]] = get_next_str($buffer, $split);
      }
      $action = get_next_str($buffer, $split);
      $sql_query = tep_db_query("select products_options_id, options_type from products_options where products_options_id = '" . $options_id . "'");
      if (tep_db_num_rows($sql_query) > 0) {
        if (trim($action) == 'delete') {
          $del_count++;
        } else {
          $sql_array = tep_db_fetch_array($sql_query);
          $sql_query_count = tep_db_query("select count(*) from products_options_values_to_products_options where products_options_id = '" . $options_id . "'");
          if ((in_array($sql_array['options_type'], $type_array1) && in_array($options_type, $type_array1)) || (in_array($sql_array['options_type'], $type_array2) && in_array($options_type, $type_array2)) || (tep_db_num_rows($sql_query_count) == 0)) {
            $update_count++;
          } else {
            $err_count++;
            $line_nbr = $line_count + 1;
            //$err_record .= '&nbsp;&nbsp;&nbsp;&nbsp;Can\'t processed on line ' . $line_nbr . ', options type change error, you can\'t change option type from <font color="red">' . translate_type_to_name($sql_array['options_type']) . '</font> to <font color="red">' . translate_type_to_name($options_type) . '</font><br>';
            $err_record .= '&nbsp;&nbsp;&nbsp;&nbsp;'.ERROR_CANT_PROCESSED_ON_LINE . $line_nbr . ', '.ERROR_OPTIONS_TYPE_CHANGE_ERROR_1.' <font color="red">' . translate_type_to_name($sql_array['options_type']) . '</font> '.ERROR_OPTIONS_TYPE_CHANGE_ERROR_2.' <font color="red">' . translate_type_to_name($options_type) . '</font><br>';
          }
        }
      } else {
        $insert_count++;
      }
    }
    fclose($fhandle);
  }
  /*
  $ret_msg .= 'Read records: ' . $line_count . '<br>';
  $ret_msg .= $update_count . ' records will be updated<br>';
  $ret_msg .= $insert_count . ' records will be inserted<br>';
  $ret_msg .= $del_count . ' records will be deleted<br>';
  if ($err_count > 0) {
    $ret_msg .= $err_count . ' records won\'t be processed, because of below reasons: <br>';
    $ret_msg .= $err_record;
  }
  */
  $ret_msg .= MSG_READ_RECORDS . $line_count . '<br>';
  $ret_msg .= $update_count . MSG_RECORDS_WILL_BE_UPDATED.'<br>';
  $ret_msg .= $insert_count . MSG_RECORDS_WILL_BE_INSERTED.' <br>';
  $ret_msg .= $del_count . MSG_RECORDS_WILL_BE_DELETED.' <br>';
  if ($err_count > 0) {
    $ret_msg .= $err_count . MSG_ERROR_RECORDS_WONT_BE_PROCESSED.' <br>';
    $ret_msg .= $err_record;
  }

  $ret_msg .= tep_draw_hidden_field('file_name', $filename);
  $ret_msg .= tep_draw_hidden_field('import', 'options');
  return $ret_msg;
}

function get_next_str(&$str, $split) {
  $pos = strpos($str, $split);
  if ($pos != false) {
    $ret = substr($str, 0, $pos);
    $str = substr($str, $pos + 1);
  } else {
    if (substr($str, 0, 1) != $split) {
      $ret = $str;
    } else {
      $ret = '';
      $str = substr($str, $pos + 1);
    }   
  }
  return $ret;
}

function translate_name_to_type($opt_name) {
  if ($opt_name == 'Select') return 0;
  if ($opt_name == 'Text') return 1;
  if ($opt_name == 'Radio') return 2;
  if ($opt_name == 'Checkbox') return 3;
  if ($opt_name == 'Text Area') return 4;
  return -1;
}
  
// options_import(DIR_FS_CATALOG . $tempdir . $file);
?>