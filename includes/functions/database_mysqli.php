<?php
/*
  $Id: database.php,v 1.1.1.1 2004/03/04 23:40:48 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
    global $$link;

      $$link = mysqli_connect($server, $username, $password);
      if ($$link) mysqli_select_db($$link, $database);
    
	return $$link;
  }

  function tep_db_close($link = 'db_link') {
    global $$link;

    return mysqli_close($$link);
  }

  function tep_db_error($query, $errno, $error) {
    global $PHP_SELF;
    ob_start();
    debug_print_backtrace();
    $traceback = ob_get_clean();
    $msg = 'Query Error reported on page ' . $PHP_SELF . "\n" . 'MySQL error: ' . $errno . ' - ' . $error . "\n\n" . $query . "\n\n";
    $msg .= 'URI for the page: ' . $_SERVER['REQUEST_URI'] . "\n\n";
    $msg .= 'Backtrace ' . $traceback . "\n\n";
    $email_address = STORE_OWNER_EMAIL_ADDRESS;
    //if (defined('TEP_STOP_NOTIFY')) $email_address = TEP_STOP_NOTIFY;
    if (DEBUG_EMAIL_MYSQL_ERRORS == 'true') {
      if ($email_address != '') {
        //tep_mail('Stop Notification!', $email_address, 'Critical Store Error!', $msg, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      }
    }
    // log the error message
    _error_handler($msg);
    die(INTERNAL_ERROR);
  }

  function tep_db_query($query, $link = 'db_link') {
    global $$link;

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      $page_name = $_SERVER['SCRIPT_FILENAME'];
      $page_name = str_replace('\\', '/', $page_name);
      $page_name = str_replace('//', '/', $page_name);
      $i = strrpos($page_name, '/');
      $page_name = substr($page_name, $i+1);
      error_log('QUERY - ' . $page_name . ':'. "\n" . $query . "\n", 3, DIR_FS_CATALOG . STORE_PAGE_PARSE_TIME_LOG);
      $sql_start = microtime(true);
    }

    $result = mysqli_query($$link, $query);
    
   // print_r(mysqli_get_connection_stats($$link));
      //exit;
   
	if(mysqli_errno($$link) > 0)
		tep_db_error($query, mysqli_errno($$link), mysqli_error($$link));

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      $sql_end = microtime(true);
      $sql_exec_time = $sql_end - $sql_start;
      $row_count = mysqli_num_rows($result);
      error_log('RESULT - time: ' . $sql_exec_time . ' rows: ' . $row_count . "\n\n", 3, DIR_FS_CATALOG . STORE_PAGE_PARSE_TIME_LOG);
    }

    return $result;
  }

  function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      while (list($columns, ) = each($data)) {
        $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'update ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }

    return tep_db_query($query, $link);
  }
  
  function tep_db_insert_delayed($table, $data, $parameters = '', $link = 'db_link') {
    reset($data);
    $query = 'INSERT DELAYED INTO ' . $table . ' (';
    while (list($columns, ) = each($data)) {
      $query .= $columns . ', ';
    }
    $query = substr($query, 0, -2) . ') values (';
    reset($data);
    while (list(, $value) = each($data)) {
      switch ((string)$value) {
        case 'now()':
          $query .= 'now(), ';
          break;
        case 'null':
          $query .= 'null, ';
          break;
        default:
          $query .= '\'' . tep_db_input($value) . '\', ';
          break;
      }
    }
    $query = substr($query, 0, -2) . ')';
      
    return tep_db_query($query, $link);
  }  

  function tep_db_fetch_array($db_query) {
    return mysqli_fetch_array($db_query, MYSQLI_ASSOC);
  }

  function tep_db_num_rows($db_query) {
    return mysqli_num_rows($db_query);
  }

  function tep_db_data_seek($db_query, $row_number) {
    return mysqli_data_seek($db_query, $row_number);
  }

  function tep_db_insert_id($link = 'db_link') {
    global $$link;
    return mysqli_insert_id($$link);
  }

  function tep_db_free_result($db_query) {
    return mysqli_free_result($db_query);
  }

  function tep_db_fetch_fields($db_query) {
    return mysqli_fetch_field($db_query);
  }

  function tep_db_output($string) {
    return htmlspecialchars($string);
  }

  function tep_db_input($string) {
    return addslashes($string);
  }

  function tep_db_prepare_input($string) {
    if (is_string($string)) {
      return trim(tep_sanitize_string(stripslashes($string)));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = tep_db_prepare_input($value);
      }
      return $string;
    } else {
      return $string;
    }
  }

  //Eversun mod for sppc and qty price breaks
 function tep_db_table_exists($table, $link = 'db_link') {
    $result = tep_db_query("show table status from `" . DB_DATABASE . "`");
    while ($list_tables = tep_db_fetch_array($result)) {
    if ($list_tables['Name'] == $table) {
      return true;
    }
    }
    return false;
  }

  function tep_db_check_age_products_group_prices_cg_table($customer_group_id) {
    $result = tep_db_query("show table status from `" . DB_DATABASE . "`");
    $last_update_table_pgp = strtotime('2000-01-01 12:00:00');
    $table_pgp_exists = false;
    while ($list_tables = tep_db_fetch_array($result)) {
    if ($list_tables['Name'] == TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id) {
    $table_pgp_exists = true;
    $last_update_table_pgp = strtotime($list_tables['Update_time']);
    } elseif ($list_tables['Name'] == TABLE_SPECIALS ) {
    $last_update_table_specials = strtotime($list_tables['Update_time']);
    } elseif ($list_tables['Name'] == TABLE_PRODUCTS ) {
    $last_update_table_products = strtotime($list_tables['Update_time']);
    } elseif ($list_tables['Name'] == TABLE_PRODUCTS_GROUPS ) {
    $last_update_table_products_groups = strtotime($list_tables['Update_time']);
    }
    } // end while

   if ($table_pgp_exists == false) {
      $create_table_sql = "create table " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id . " (products_id int NOT NULL default '0', products_price decimal(15,4) NOT NULL default '0.0000', specials_new_products_price decimal(15,4) default NULL, status tinyint, primary key (products_id) )" ;
      $fill_table_sql1 = "insert into " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." select p.products_id, p.products_price, NULL as specials_new_products_price, NULL as status FROM " . TABLE_PRODUCTS . " p";
      $update_table_sql1 = "update " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." ppt left join " . TABLE_PRODUCTS_GROUPS . " pg using(products_id) set ppt.products_price = pg.customers_group_price where ppt.products_id = pg.products_id and pg.customers_group_id ='" . $customer_group_id . "'";
      $update_table_sql2 = "update " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." ppt left join " . TABLE_SPECIALS . " s using(products_id) set ppt.specials_new_products_price = s.specials_new_products_price, ppt.status = s.status where ppt.products_id = s.products_id and s.customers_group_id = '" . $customer_group_id . "'";
      if ( tep_db_query($create_table_sql) && tep_db_query($fill_table_sql1) && tep_db_query($update_table_sql1) && tep_db_query($update_table_sql2) ) {
         return true;
              }
   } // end if ($table_pgp_exists == false)

   if ( ($last_update_table_pgp < $last_update_table_products && (time() - $last_update_table_products > (int)MAXIMUM_DELAY_UPDATE_PG_PRICES_TABLE * 60) ) || $last_update_table_specials > $last_update_table_pgp || $last_update_table_products_groups > $last_update_table_pgp ) { // then the table should be updated
      $empty_query = "truncate " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id . "";
      $fill_table_sql1 = "insert into " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." select p.products_id, p.products_price, NULL as specials_new_products_price, NULL as status FROM " . TABLE_PRODUCTS . " p";
      $update_table_sql1 = "update " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." ppt left join " . TABLE_PRODUCTS_GROUPS . " pg using(products_id) set ppt.products_price = pg.customers_group_price where ppt.products_id = pg.products_id and pg.customers_group_id ='" . $customer_group_id . "'";
      $update_table_sql2 = "update " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." ppt left join " . TABLE_SPECIALS . " s using(products_id) set ppt.specials_new_products_price = s.specials_new_products_price, ppt.status = s.status where ppt.products_id = s.products_id and s.customers_group_id = '" . $customer_group_id . "'";
      if ( tep_db_query($empty_query) && tep_db_query($fill_table_sql1) && tep_db_query($update_table_sql1) && tep_db_query($update_table_sql2) ) {
         return true;
              }
   } else { // no need to update
     return true;
   } // end checking for update

  }
  //Eversun mod end for sppc and qty price breaks
  
  function tep_db_decoder($string) {
    $string = str_replace('&#39;', "'", $string);
    $string = str_replace('&#39', "'", $string); //backword compatabiliy
    return $string;
  }
?>
