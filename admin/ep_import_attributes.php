<?php
function attributes_import($filename) {
  if (!file_exists($filename)) {
    return;
  }

  $split = "\t";
  $fhandle = @fopen($filename ,'r');
  if ($fhandle) {
    $buffer = fgets($fhandle);
    while (!feof($fhandle)) {
      $buffer = fgets($fhandle);
      if (trim($buffer) == '') {
        continue;
      }
      $attributes_id = get_next_str($buffer, $split);
      $products_id = get_next_str($buffer, $split);
      $products_model = get_next_str($buffer, $split);
      $options_id = get_next_str($buffer, $split);
      $values_id = get_next_str($buffer, $split);
      $values_price = (float)get_next_str($buffer, $split);
      $values_price_prefix = get_next_str($buffer, $split);
      $sort_order = get_next_str($buffer, $split);
      $attributes_filename = get_next_str($buffer, $split);
      $attributes_attributes_maxdays = get_next_str($buffer, $split);
      $attributes_attributes_maxcount = get_next_str($buffer, $split);
      $action = get_next_str($buffer, $split);
      $sql_query = tep_db_query("select products_attributes_id from products_attributes where products_attributes_id = '" . $attributes_id . "'");
      if (tep_db_num_rows($sql_query) > 0) {
        if (trim($action) == 'delete') {
          tep_db_query("delete from products_attributes where products_attributes_id = '" . $attributes_id . "'");
          tep_db_query("delete from products_attributes_download where products_attributes_id = '" . $attributes_id . "'");
        } else {
          if (check_option($options_id) && check_product($products_id, $products_model) && check_value($values_id, $options_id)) {
            tep_db_query("update products_attributes set products_id = '" . $products_id . "', options_id = '" . $options_id . "', options_values_id = '" . $values_id . "', options_values_price = '" . $values_price . "',  price_prefix = '" . $values_price_prefix . "', products_options_sort_order = '" . $sort_order . "' where products_attributes_id = '" . $attributes_id . "'");
            $sql_query = tep_db_query("select products_attributes_id from products_attributes_download where products_attributes_id = '" . $attributes_id . "'");
            if ($attributes_filename != '' && file_exists(DIR_FS_CATALOG . 'download/' . $attributes_filename)) {
              if (tep_db_num_rows($sql_query) > 0) {
                tep_db_query("update products_attributes_download set products_attributes_filename = '" . $attributes_filename . "'where products_attributes_id = '" . $attributes_id . "'");
              } else {
                tep_db_query("insert into products_attributes_download (products_attributes_id, products_attributes_filename) values ('" . $attributes_id . "', '" . $attributes_filename . "')");
              }
            } else if ($attributes_filename == '' && tep_db_num_rows($sql_query) > 0) {
              tep_db_query("delete from products_attributes_download where products_attributes_id = '" . $attributes_id . "'");
            }
          }
        }
      } else {
        $sql_query = tep_db_query("select products_attributes_id from products_attributes where products_id = '" . $products_id . "' and options_id = '" . $options_id . "' and options_values_id = '" . $values_id . "'");
        if (tep_db_num_rows($sql_query) == 0 && check_option($options_id) && check_product($products_id, $products_model) && check_value($values_id, $options_id)) {
          tep_db_query("insert into products_attributes (products_attributes_id, products_id, options_id, options_values_id, options_values_price, price_prefix, products_options_sort_order) values ('" . $attributes_id . "', '" . $products_id . "', '" . $options_id . "', '" . $values_id . "', '" . $values_price_prefix . "', '" . $sort_order . "', '" . $sort_order . "')");
          if ($attributes_filename != '' && file_exists(DIR_FS_CATALOG . 'download/' . $attributes_filename)) {
            tep_db_query("insert into products_attributes_download (products_attributes_id, products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount) values ('" . $attributes_id . "', '" . $attributes_filename . "', '" . $attributes_attributes_maxdays . "', '" . $attributes_attributes_maxcount . "' )");
          }
        }
      }
    }
    fclose($fhandle);
  }
}

function check_option($opt_id) {
  $sql = tep_db_query("select products_options_id from products_options where products_options_id = '" . $opt_id . "'");
  if (tep_db_num_rows($sql) > 0) {
    return true;
  } else {
    return false;
  }
}

function check_product($prod_id, $prod_model) {
  if ($prod_id != '') {
    $sql = tep_db_query("select products_id from products where products_id = '" . $prod_id . "'");
  } else {
    $sql = tep_db_query("select products_id from products where products_model = '" . $prod_model . "'");
  }
  if (tep_db_num_rows($sql) == 1) {
    return true;
  } else {
    return false;
  }
}

function check_value($val_id, $opt_id) {
 if ($val_id == 0){
    return true;
  }
  $sql = tep_db_query("select pov.products_options_values_id from products_options_values_to_products_options povtpo, products_options_values pov where pov.products_options_values_id = '" . $val_id . "' and povtpo.products_options_id = '" . $opt_id . "' and pov.products_options_values_id = povtpo.products_options_values_id");
  if (tep_db_num_rows($sql) > 0) {
    return true;
  } else {
    return false;
  }
}

function attributes_import_check($filename) {
  if (!file_exists($filename)) {
    return EASY_INFO_FILE_NOT_FOUND;
  }
  $split = "\t";
  $ret_msg = '';
  $fhandle = @fopen($filename ,'r');
  if ($fhandle) {
    $buffer = fgets($fhandle);
    $db_table_query = tep_db_query("SHOW COLUMNS FROM products_attributes");
    while ($db_table_array = tep_db_fetch_array($db_table_query)) {
      $db_fields[] = $db_table_array['Field'];
    }
    $db_table_query = tep_db_query("SHOW COLUMNS FROM products_attributes_download");
    while ($db_table_array = tep_db_fetch_array($db_table_query)) {
      $db_fields[] = $db_table_array['Field'];
    }
    $db_fields[] = 'products_model';
    for ($i=0;$i<8;$i++) {
      $str_header[$i] = substr(get_next_str($buffer, $split), 2);
      if (!in_array($str_header[$i], $db_fields)) {
        $ret_msg .= sprintf(EASY_INFO_CHECK_ERROR1, $str_header[$i], 'products_attributes') . '<br>';
      }
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
      $attributes_id = get_next_str($buffer, $split);
      $products_id = get_next_str($buffer, $split);
      $products_model = get_next_str($buffer, $split);
      $options_id = get_next_str($buffer, $split);
      $values_id = get_next_str($buffer, $split);
      $values_price = (float)get_next_str($buffer, $split);
      $sort_order = get_next_str($buffer, $split);
      $attributes_filename = get_next_str($buffer, $split);
      $action = get_next_str($buffer, $split);
      $sql_query = tep_db_query("select products_attributes_id from products_attributes where products_attributes_id = '" . $attributes_id . "'");
      if (tep_db_num_rows($sql_query) > 0) {
        if (trim($action) == 'delete') {
          $del_count++;
        } else {
          $err_flag = true;
          if (!check_option($options_id)) {
            $err_flag = false;
            $line_nbr = $line_count + 1;
            $err_record .= '&nbsp;&nbsp;&nbsp;&nbsp;'.ERROR_CANT_PROCESSED_ON_LINE . $line_nbr . ', '.ERROR_DOESNT_HAVE_THIS_OPTIONS_ID . $options_id . '<br>';
          } else if (!check_product($products_id, $products_model)) {
            $err_flag = false;
            $line_nbr = $line_count + 1;
            $err_record .= '&nbsp;&nbsp;&nbsp;&nbsp;'.ERROR_CANT_PROCESSED_ON_LINE . $line_nbr . ', '.ERROR_DOESNT_HAVE_THIS_PRODUCTS_ID_1 . $products_id . ERROR_DOESNT_HAVE_THIS_PRODUCTS_ID_2.$products_model . '<br>';
          } else if (!check_value($values_id, $options_id)) {
            $err_flag = false;
            $line_nbr = $line_count + 1;
            $err_record .= '&nbsp;&nbsp;&nbsp;&nbsp; '.ERROR_CANT_PROCESSED_ON_LINE . $line_nbr . ', '.ERROR_DOESNT_HAVE_THIS_VALUES_ID_1 . $values_id . ERROR_DOESNT_HAVE_THIS_VALUES_ID_2 . $options_id . '<br>';
          }
          if ($err_flag) {
            $update_count++;
          } else {
            $err_count++;
          }
        }
      } else {
        $sql_query = tep_db_query("select products_attributes_id from products_attributes where products_id = '" . $products_id . "' and options_id = '" . $options_id . "' and options_values_id = '" . $values_id . "'");
        if (tep_db_num_rows($sql_query) == 0 && check_option($options_id) && check_product($products_id, $products_model) && check_value($values_id, $options_id)) {
          $insert_count++;
        } else {
          $err_flag = true;
          if (!check_option($options_id)) {
            $err_flag = false;
            $line_nbr = $line_count + 1;
            $err_record .= '&nbsp;&nbsp;&nbsp;&nbsp;'.ERROR_CANT_PROCESSED_ON_LINE . $line_nbr . ', '.ERROR_DOESNT_HAVE_THIS_OPTIONS_ID . $options_id . '<br>';
          } else if (!check_product($products_id, $products_model)) {
            $err_flag = false;
            $line_nbr = $line_count + 1;
            $err_record .= '&nbsp;&nbsp;&nbsp;&nbsp;'.ERROR_CANT_PROCESSED_ON_LINE . $line_nbr . ', '.ERROR_DOESNT_HAVE_THIS_PRODUCTS_ID_1 . $products_id . ERROR_DOESNT_HAVE_THIS_PRODUCTS_ID_2 . $products_model . '<br>';
          } else if (!check_value($values_id, $options_id)) {
            $err_flag = false;
            $line_nbr = $line_count + 1;
            $err_record .= '&nbsp;&nbsp;&nbsp;&nbsp;'.ERROR_CANT_PROCESSED_ON_LINE . $line_nbr . ', '.ERROR_DOESNT_HAVE_THIS_VALUES_ID_1 . $values_id . ERROR_DOESNT_HAVE_THIS_VALUES_ID_2 . $options_id . '<br>';
          }
          if ($err_flag) {
            $update_count++;
          } else {
            $err_count++;
          }
        }
      }
    }
    fclose($fhandle);
  }
  $ret_msg .= MSG_READ_RECORDS . $line_count . '<br>';
  $ret_msg .= $update_count . MSG_RECORDS_WILL_BE_UPDATED.'<br>';
  $ret_msg .= $insert_count . MSG_RECORDS_WILL_BE_INSERTED.' <br>';
  $ret_msg .= $del_count . MSG_RECORDS_WILL_BE_DELETED.' <br>';
  if ($err_count > 0) {
    $ret_msg .= $err_count . MSG_ERROR_RECORDS_WONT_BE_PROCESSED.' <br>';
    $ret_msg .= $err_record;
  }
  $ret_msg .= tep_draw_hidden_field('file_name', $filename);
  $ret_msg .= tep_draw_hidden_field('import', 'attributes');
  return $ret_msg;
}
?>