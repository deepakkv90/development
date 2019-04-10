<?php
function values_import($filename) {
  if (!file_exists($filename)) {
    return;
  }
  
  $split = "\t";
  $fhandle = @fopen($filename ,'r');
  if ($fhandle) {
    $buffer = fgets($fhandle);
    for ($i=0;$i<3;$i++) {
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
      $values_id = get_next_str($buffer, $split);
      $options_id = get_next_str($buffer, $split);
      for ($i=0;$i<sizeof($lang);$i++) {
        $values_name[$lang[$i]] = get_next_str($buffer, $split);
      }
      $action = get_next_str($buffer, $split);
      $sql_query = tep_db_query("select products_options_values_id from products_options_values where products_options_values_id = '" . $values_id . "'");
      if (tep_db_num_rows($sql_query) > 0) {
        if (trim($action) == 'delete') {
          tep_db_query("delete from products_options_values where products_options_values_id = '" . $values_id . "'");
          tep_db_query("delete from products_options_values_to_products_options where products_options_values_id = '" . $values_id . "'");
        } else {
          for ($i=0;$i<sizeof($lang);$i++) {
            tep_db_query("update products_options_values set products_options_values_name = '" . $values_name[$lang[$i]] . "' where products_options_values_id = '" . $values_id . "' and language_id = '" . $lang[$i] . "'");
          }
        }
      } else {
        if ($values_id != '') {
          $new_values_id = (int)$values_id;
        } else {
          $new_values = tep_db_fetch_array(tep_db_query("select max(products_options_values_id) as val_id from products_options_values"));
          $new_values_id = (int)$new_values['val_id'] +1;
        }
        tep_db_query("insert into products_options_values_to_products_options (products_options_id, products_options_values_id) values ('" . $options_id . "', '" . $new_values_id . "')");
        for ($i=0;$i<sizeof($lang);$i++) {
          tep_db_query("insert into products_options_values (products_options_values_id, language_id, products_options_values_name) values ('" . $new_values_id . "', '" . $lang[$i] . "', '" . $values_name[$lang[$i]] . "')");
        }
      }
    }
    fclose($fhandle);
  }
}

function values_import_check($filename) {
  if (!file_exists($filename)) {
    return EASY_INFO_FILE_NOT_FOUND;
  }
  $split = "\t";
  $ret_msg = '';
  $fhandle = @fopen($filename ,'r');
  if ($fhandle) {
    $buffer = fgets($fhandle);
    $db_table_query = tep_db_query("SHOW COLUMNS FROM products_options_values");
    while ($db_table_array = tep_db_fetch_array($db_table_query)) {
      $db_fields[] = $db_table_array['Field'];
    }
    $db_table_query = tep_db_query("SHOW COLUMNS FROM products_options_values_to_products_options");
    while ($db_table_array = tep_db_fetch_array($db_table_query)) {
      $db_fields[] = $db_table_array['Field'];
    }
    for ($i=0;$i<2;$i++) {
      $str_header[$i] = substr(get_next_str($buffer, $split), 2);
      if (!in_array($str_header[$i], $db_fields)) {
        $ret_msg .= sprintf(EASY_INFO_CHECK_ERROR1, $str_header[$i], 'products_options_values') . '<br>';
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
          $ret_msg .= sprintf(EASY_INFO_CHECK_ERROR1, $str_header[$i], 'products_options_values_to_products_options') . '<br>';
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
      $values_id = get_next_str($buffer, $split);
      $options_id = get_next_str($buffer, $split);
      for ($i=0;$i<sizeof($lang);$i++) {
        $values_name[$lang[$i]] = get_next_str($buffer, $split);
      }
      $action = get_next_str($buffer, $split);
      $sql_query = tep_db_query("select products_options_values_id from products_options_values where products_options_values_id = '" . $values_id . "'");
      if (tep_db_num_rows($sql_query) > 0) {
        if (trim($action) == 'delete') {
          $del_count++;
        } else {
          $update_count++;
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
  $ret_msg .= tep_draw_hidden_field('import', 'values');
  return $ret_msg;
}
?>