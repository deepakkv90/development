<?php
/*
  $Id: database.php,v 1.1.1.1 2004/03/04 23:39:50 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
    global $$link;

    if (USE_PCONNECT == 'true') {
      $$link =mysqli_pconnect($server, $username, $password);
    } else {
      $$link =mysqli_connect($server, $username, $password);
    }
    if ($$link)mysqli_select_db($$link,$database);

    return $$link;
  }

  function tep_db_close($link = 'db_link') {
    global $$link;

    returnmysql_close($$link);
  }

  function tep_db_error($query, $errno, $error) {
   // die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">'.DATABASE_TEP_DB_ERROR.'</font></small><br><br></b></font>');
  }

 function tep_db_query($query, $link = 'db_link') {
    global $$link, $logger;

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      if (!is_object($logger)) $logger = new logger;
      $logger->write($query, 'QUERY');
    }

    $result =mysqli_query($$link,$query) or tep_db_error($query,mysqli_errno(),mysqli_error());

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      if (mysqli_error()) $logger->write(mysqli_error(), 'ERROR');
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

  function tep_db_result($result, $row, $field = '') {
    return mysqli_result($result, $row, $field);
  }

  function tep_db_num_rows($db_query) {
    return mysqli_num_rows($db_query);
  }

  function tep_db_data_seek($db_query, $row_number) {
    return mysqli_data_seek($db_query, $row_number);
  }

  function tep_db_insert_id() {
    return mysqli_insert_id();
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

  function tep_db_input($string, $link = 'db_link') {
    global $$link;

    return addslashes($string);
  }

  function tep_db_prepare_input($string) {
    if (is_string($string)) {
      return trim(stripslashes($string));
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

  function tep_db_fetch_object($db_query) {
    return mysqli_fetch_object($db_query);
  }

  function tep_db_encoder($string) {
    $string = str_replace("'", '&#39', $string);
    return $string;
  }
  
  function tep_db_decoder($string) {
    $string = str_replace('&#39', "'", $string);
    return $string;
  }
?>
