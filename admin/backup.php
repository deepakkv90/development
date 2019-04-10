<?php
/*
  $Id: backup.php,v 1.1.1.1 2004/03/04 23:38:11 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
Modification for detections, and using php gzip and b2z funcations added by CRE Loaded.com
Add debug enhancments by CRE Loaded.com

Portions Copyright (c) 2003 CRE Loaded
*/

  require('includes/application_top.php');
$debug=MYSQL_BACKUP_DEBUG;

//*******************
//set variables to 0
 $d_serv_gzip_avail = '0' ;
 $d_serv_gunzip_avail = '0' ;
 $d_serv_zip_avail = '0' ;
 $d_serv_unzip_avail = '0' ;
 $d_php_gzip_on = '0';

 $server_os_id = 0;
//*******************
//os detect we need to know if this is a Win32 or Unix compat
//if WINNT then we must use php built in gzip $server_os_id 0 = non found,  1= poxis compatible, 2 = windows

//if (function_exists(PHP_OS) ){

$server_os = PHP_OS ;

  if ($server_os == 'WINNT'){
    $server_os_id = '2' ;
  } else if ($server_os == 'LINUX'){
    $server_os_id = '1' ;
  }else if ($server_os == 'FreeBSD'){
    $server_os_id = '1' ;
  }
//}
//*******************
//gzip detect
  if (function_exists('exec')) {
    $gzip_path_1 = exec('which gzip');

  if($gzip_path_1 > ' '){
    $d_serv_gzip_avail = '1' ;
   }
  }
//*******************
 //gunzip detect
   if (function_exists('exec')) {
     $gunzip_path_1 = exec('which gunzip');

   if($gunzip_path_1 > ' '){
     $d_serv_gunzip_avail = '1' ;
    }
  }
//*******************
//zip detect
  if (function_exists('exec')) {
    $zip_path_1 = exec('which zip');

  if($zip_path_1 > ' '){
    $d_serv_zip_avail = '1' ;
   }
  }

//*******************
//unzip detect
  if (function_exists('exec')) {
    $unzip_path_1 = exec('which unzip');

  if($unzip_path_1 > ' '){
    $d_serv_unzip_avail = '1' ;
   }
  }

//*******************

//zip detect settings

// note ADMIN_GZIP_LEVEL is set in admin/includes/application_top_admin_cre_setting.php the admin only
//when backup mysql has it's own configuration settings change below to new define
//$gzip_level_set = ADMIN_GZIP_LEVEL ;
  $gzip_level_set = 9 ;


// if avaible on server don't use php gzip
  if ($d_serv_gzip_avail == '0'){
    if (extension_loaded('zlib') == 1) {
      $d_php_gzip_on = '1' ;
      $gzip_file_ext = '.gz';
    }
  }

// we cannot have zlib.output_handler set to on if we use regualar gzip compression
//gzopen

  if (isset($_GET['debug']) && $_GET['debug']=='ON'){
    $debug='ON';
  }
// Note that LOCAL_EXE_MYSQL and LOCAL_EXE_MYSQL_DUMP are defined in the /admin/includes/application_top_admin_cre_setting.php file
// These can occasionally be overridden in the URL by specifying &tool=/path/to/foo/bar/plus/utilname, depending on server support
// if windows application_top_admin_cre_setting.php must be edited since I have not fond an elegent was to search for these two apps

if ($server_os_id == '2'){
  $mysql_exe = LOCAL_EXE_MYSQL ;
  $mysqldump_exe = LOCAL_EXE_MYSQLDUMP ;
}

//if posix then issues a which command

if ($server_os_id != '2'){
  //mysql and mysql dump detect
  if (function_exists('exec')) {
    $mysql_path_1 = exec('which mysql');

    if(!($mysql_path_1 == ' ')){
      $d_serv_mysql_avail = '1' ;
    }
  }
  $mysql_exe = $mysql_path_1 ;

  if (function_exists('exec')) {
    $mysql_dump_path_1 = exec('which mysqldump');

    if(!($mysql_dump_path_1 == ' ')){
      $d_serv_mysqldump_avail = '1' ;
    }
  }
  $mysqldump_exe = $mysql_dump_path_1;

}
// debug on
if ($debug=='ON') $messageStack->add_session('search', BACKUP_MYSQl_ERROR_MSG_9,'warning');

//server OS detect
if (($debug=='ON') && ($server_os_id == '0')) {
  $messageStack->add_session('search', BACKUP_MYSQl_DEBUG_MSG_12,'warning');
} else if (($debug=='ON') && ($server_os_id == '1')) {
  $messageStack->add_session('search', BACKUP_MYSQl_DEBUG_MSG_7,'warning');
} else if (($debug=='ON') && ($server_os_id == '2')) {
  $messageStack->add_session('search', BACKUP_MYSQl_DEBUG_MSG_8,'warning');
}

//zip and or zlib detect
if (($debug=='ON') && ($d_serv_gzip_avail == '1') ){
  $messageStack->add_session('search', BACKUP_MYSQl_DEBUG_MSG_1,'warning');
} else if (($debug=='ON') && ($d_serv_gzip_avail == '0') ) {
  $messageStack->add_session('search', BACKUP_MYSQl_DEBUG_MSG_5,'warning');
}

if (($debug=='ON') && ($d_serv_gunzip_avail == '1')){
  $messageStack->add_session('search', BACKUP_MYSQl_DEBUG_MSG_2,'warning');
}
if (($debug=='ON') && ($d_serv_zip_avail == '1')){
  $messageStack->add_session('search', BACKUP_MYSQl_DEBUG_MSG_3,'warning');
} else if (($debug=='ON') && ($d_serv_zip_avail == '0') ) {
  $messageStack->add_session('search', BACKUP_MYSQl_DEBUG_MSG_6,'warning');
}
if (($debug=='ON') && ($d_serv_unzip_avail == '1')){
  $messageStack->add_session('search', BACKUP_MYSQl_DEBUG_MSG_4,'warning');
}

//mysql detect
  if ($debug=='ON') $messageStack->add_session('search', BACKUP_MYSQl_ERROR_MSG_2,'warning');
  if ($debug=='ON') $messageStack->add_session('search', BACKUP_MYSQl_ERROR_MSG_7 .$mysql_exe .'<br>','warning');
  if ($debug=='ON') $messageStack->add_session('search', BACKUP_MYSQl_ERROR_MSG_8. $mysqldump_exe .'<br><br>','warning');


  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'forget':
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'DB_LAST_RESTORE'");

        $messageStack->add_session('search', SUCCESS_LAST_RESTORE_CLEARED, 'success');

        tep_redirect(tep_href_link(FILENAME_BACKUP));
        break;
      case 'backupnow':
        tep_set_time_limit(250);  // not sure if this is needed anymore?
        $backup_file = 'db_' . DB_DATABASE . '-' . date('YmdHis') . '.sql';
        $fp = fopen(DIR_FS_BACKUP . $backup_file, 'w');

        $schema = '# osCommerce, Open Source E-Commerce Solutions' . "\n" .
                  '# http://www.oscommerce.com' . "\n" .
                  '#' . "\n" .
                  '# Database Backup For ' . STORE_NAME . "\n" .
                  '# Copyright (c) ' . date('Y') . ' ' . STORE_OWNER . "\n" .
                  '#' . "\n" .
                  '# Database: ' . DB_DATABASE . "\n" .
                  '# Database Server: ' . DB_SERVER . "\n" .
                  '#' . "\n" .
                  '# Backup Date: ' . date(PHP_DATE_TIME_FORMAT) . "\n\n";
        fputs($fp, $schema);

        $tables_query = tep_db_query('show tables');
        while ($tables = tep_db_fetch_array($tables_query)) {
          list(,$table) = each($tables);

          $schema = 'drop table if exists ' . $table . ';' . "\n" .
                    'create table ' . $table . ' (' . "\n";

          $table_list = array();
          $fields_query = tep_db_query("show fields from " . $table);
          while ($fields = tep_db_fetch_array($fields_query)) {
            $table_list[] = $fields['Field'];

            $schema .= '  ' . $fields['Field'] . ' ' . $fields['Type'];

            if (strlen($fields['Default']) > 0) {
             if ($fields['Default'] == 'CURRENT_TIMESTAMP'){
                $schema .= ' default ' . $fields['Default'] . '';
               }else{
                $schema .= ' default \'' . $fields['Default'] . '\'';
               }
             }
            if ($fields['Null'] != 'YES') $schema .= ' not null';

            if (isset($fields['Extra'])) $schema .= ' ' . $fields['Extra'];

            $schema .= ',' . "\n";
          }

          $schema = ereg_replace(",\n$", '', $schema);

// add the keys
          $index = array();
          $keys_query = tep_db_query("show keys from " . $table);
          while ($keys = tep_db_fetch_array($keys_query)) {
            $kname = $keys['Key_name'];

            if (!isset($index[$kname])) {
              $index[$kname] = array('unique' => !$keys['Non_unique'],
                                     'columns' => array());
            }

            $index[$kname]['columns'][] = $keys['Column_name'];
          }

          while (list($kname, $info) = each($index)) {
            $schema .= ',' . "\n";

            $columns = implode($info['columns'], ', ');

            if ($kname == 'PRIMARY') {
              $schema .= '  PRIMARY KEY (' . $columns . ')';
            } elseif ($info['unique']) {
              $schema .= '  UNIQUE ' . $kname . ' (' . $columns . ')';
            } else {
              $schema .= '  KEY ' . $kname . ' (' . $columns . ')';
            }
          }

          $schema .= "\n" . ');' . "\n\n";
          fputs($fp, $schema);

// dump the data
          $rows_query = tep_db_query("select " . implode(',', $table_list) . " from " . $table);
          while ($rows = tep_db_fetch_array($rows_query)) {
            $schema = 'insert into ' . $table . ' (' . implode(', ', $table_list) . ') values (';

            reset($table_list);
            while (list(,$i) = each($table_list)) {
              if (!isset($rows[$i])) {
                $schema .= 'NULL, ';
              } elseif (tep_not_null($rows[$i])) {
                $row = addslashes($rows[$i]);
                $row = ereg_replace("\n#", "\n".'\#', $row);

                $schema .= '\'' . $row . '\', ';
              } else {
                $schema .= '\'\', ';
              }
            }

            $schema = ereg_replace(', $', '', $schema) . ');' . "\n";
            fputs($fp, $schema);

          }
        }

        fclose($fp);
        
        if (isset($_POST['download']) && ($_POST['download'] == 'yes')) {
          switch ($_POST['compress']) {
            case 'gzip':
              //$backup_file .= '.gz';
              //build the files path and names
              $backup_file_2 = DIR_FS_BACKUP . $backup_file ;
              
              // if gzip library is on the server use it, if not fall back to php gzip
              if ($d_serv_gzip_avail == '1'){
                exec($gzip_path_1 . ' ' . $backup_file_2);
                // unlink(DIR_FS_BACKUP . $backup_file);
                if ($debug=='ON') $messageStack->add_session('search', BACKUP_MYSQl_DEBUG_MSG_9,'warning');
                // reset the $backup_file variable with the new name for use below
                $backup_file = $backup_file . '.gz';

              } else {
                $backup_file_1 = DIR_FS_BACKUP . $backup_file . $gzip_file_ext;
                if ($debug=='ON') $messageStack->add_session('search', BACKUP_MYSQl_DEBUG_MSG_10,'warning');

                //open the uncompressed file and read it
                $fp_1 = fopen($backup_file_2, "r");
                $fp_1_data = fread($fp_1, filesize($backup_file_2));
                //compress data
                $gzdata = gzencode($fp_1_data, $gzip_level_set);
                // write compressed data to a gz file
                $gz = gzopen($backup_file_1,'w');
                gzwrite($gz, $gzdata);
                gzclose($gz);
                //close orginal file and remove it
                fclose($fp_1);
                //delete the orginal file
                unlink($backup_file_2);
                // reset the $backup_file variable with the new name for use below
                $backup_file = $backup_file . $gzip_file_ext;
              }
              break;
            case 'zip':
              exec($zip_path_1 . ' -j ' . DIR_FS_BACKUP . $backup_file . '.zip ' . DIR_FS_BACKUP . $backup_file);
              if ($debug=='ON') $messageStack->add_session('search', BACKUP_MYSQl_DEBUG_MSG_11,'warning');
              unlink(DIR_FS_BACKUP . $backup_file);
              // reset the $backup_file variable with the new name for use below
                $backup_file = $backup_file . '.zip';
          }

          //add mine header for download
          if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
            header('Content-Type: application/octetstream');
            header('Cache-Control: no-store, no-cache, must-revalidate' );
            header('Cache-Control: post-check=0, pre-check=0', false );
            header("Pragma: public");
            header("Cache-control: private");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header('Content-Transfer-Encoding: Binary');
            header("Content-length: " . filesize(DIR_FS_BACKUP . $backup_file));
            header('Content-Disposition: attachment; filename=' . $backup_file);
          } else {
            header('Content-Type: application/octet-stream');
            header('Cache-Control: no-store, no-cache, must-revalidate' );
            header('Cache-Control: post-check=0, pre-check=0', false );
            header("Pragma: no-cache");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header('Content-Transfer-Encoding: Binary');
            header("Content-length: " . filesize(DIR_FS_BACKUP . $backup_file));
            header('Content-Disposition: attachment; filename=' . $backup_file);
          }
          
          readfile(DIR_FS_BACKUP . $backup_file);
          unlink(DIR_FS_BACKUP . $backup_file);

          exit;
        } else {
          switch ($_POST['compress']) {
            case 'gzip':
              //$backup_file .= '.gz';
              //build the files path and names
              $backup_file_2 = DIR_FS_BACKUP . $backup_file ;
              
              // if gzip library is on the server use it, if not fall back to php gzip
              if ($d_serv_gzip_avail == '1'){ 
                exec($gzip_path_1 . ' ' . $backup_file_2);
                // unlink(DIR_FS_BACKUP . $backup_file);
                if ($debug=='ON') $messageStack->add_session('search', BACKUP_MYSQl_DEBUG_MSG_9,'warning');
              } else {
                $backup_file_1 = DIR_FS_BACKUP . $backup_file . $gzip_file_ext;
                if ($debug=='ON') $messageStack->add_session('search', BACKUP_MYSQl_DEBUG_MSG_10,'warning');
                //open the uncompressed file and read it
                $fp_1 = fopen($backup_file_2, "r");
                $fp_1_data = fread($fp_1, filesize($backup_file_2));
                //compress data
                //$gzdata = gzencode($fp_1_data, $gzip_level_set);
                // write compressed data to a gz file
                $gz = gzopen($backup_file_1,"w");
                gzwrite($gz, $fp_1_data);
                gzclose($gz);
                //close orginal file and remove it
                fclose($fp_1);
               //delete the orginal file
                unlink($backup_file_2);
              }
              break;
            case 'zip':
              exec($zip_path_1 . ' -j ' . DIR_FS_BACKUP . $backup_file . '.zip ' . DIR_FS_BACKUP . $backup_file);
              unlink(DIR_FS_BACKUP . $backup_file);
              if ($debug=='ON') $messageStack->add_session('search', BACKUP_MYSQl_DEBUG_MSG_11,'warning');
          }

          $messageStack->add_session('search', SUCCESS_DATABASE_SAVED, 'success');
        }

        tep_redirect(tep_href_link(FILENAME_BACKUP));
        break;
      case 'restorenow':
      case 'restorelocalnow':
        tep_set_time_limit(0);

        $specified_restore_file = isset($_GET['file']) ? $_GET['file'] : '';
        if ($specified_restore_file !='' && file_exists(DIR_FS_BACKUP . $specified_restore_file)) {
          $restore_file = DIR_FS_BACKUP . $specified_restore_file;
          $extension = substr($specified_restore_file, -3);

          //determine file format and unzip if needed
          if ( ($extension == 'sql') || ($extension == '.gz') || ($extension == 'zip') ) {
            switch ($extension) {
              case 'sql':
                $restore_from = $restore_file;
                $remove_raw = false;
                break;
              case '.gz':
                if ($d_serv_gunzip_avail == 1){
                  $restore_from = substr($restore_file, 0, -3);
                  exec($gunzip_path_1 . ' ' . $restore_file . ' -c > ' . $restore_from);
                  $remove_raw = true;
                } else {
                  //use php gzip to uncompress
                  //get file size

                  $restore_from = substr($restore_file, 0, -3);
                  //get file size of final file
                  $file_open = fopen($restore_file, "rb");
                  fseek($file_open, -4, SEEK_END);
                  $buf = fread($file_open, 4);
                  $gz_file_size = end(unpack("V", $buf));
                  fclose($file_open);

                  // getting content of the compressed file
                  $zp = gzopen($restore_file, "r");
                  $data = gzread ($zp, $gz_file_size );
                  gzclose($zp);

                  // write data to temp file for restore
                  $fp = fopen($restore_from, "w");
                  fwrite($fp, $data);
                  fclose($fp);
                  $remove_raw = true;
                }
                break;
              case 'zip':
                $restore_from = substr($restore_file, 0, -4);
                if ($d_serv_unzip_avail == '1') {
                  exec($unzip_path_1 . ' ' . $restore_file . ' -d ' . DIR_FS_BACKUP);
                } else {
                  $backup_file_2 = DIR_FS_BACKUP . $restore_file ;
                  $backup_file_1 = DIR_FS_BACKUP . $restore_file . $bzip_file_ext;

                  $in_file = bzopen ($backup_file_1, "rb");
                  $out_file = fopen ($backup_file_2, "wb");

                  while ($buffer = bzread (filesize($backup_file_1) )) {
                    fwrite ($out_file, $buffer, filesize($backup_file_2));
                  }

                  bzclose ($in_file);
                  fclose ($out_file);
                  $remove_raw = true;
                }
            }
          }
        } elseif ($action == 'restorelocalnow') {
          $sql_file = new upload('sql_file', DIR_FS_BACKUP);
          $specified_restore_file = $sql_file->filename;
          $restore_from = DIR_FS_BACKUP . $specified_restore_file;
        }

        if (isset($restore_query)) {
          $sql_array = array();
          $sql_length = strlen($restore_query);
          $pos = strpos($restore_query, ';');
          for ($i=$pos; $i<$sql_length; $i++) {
            if ($restore_query[0] == '#') {
              $restore_query = ltrim(substr($restore_query, strpos($restore_query, "\n")));
              $sql_length = strlen($restore_query);
              $i = strpos($restore_query, ';')-1;
              continue;
            }
            if ($restore_query[($i+1)] == "\n") {
              for ($j=($i+2); $j<$sql_length; $j++) {
                if (trim($restore_query[$j]) != '') {
                  $next = substr($restore_query, $j, 6);
                  if ($next[0] == '#') {
// find out where the break position is so we can remove this line (#comment line)
                    for ($k=$j; $k<$sql_length; $k++) {
                      if ($restore_query[$k] == "\n") break;
                    }
                    $query = substr($restore_query, 0, $i+1);
                    $restore_query = substr($restore_query, $k);
// join the query before the comment appeared, with the rest of the dump
                    $restore_query = $query . $restore_query;
                    $sql_length = strlen($restore_query);
                    $i = strpos($restore_query, ';')-1;
                    continue 2;
                  }
                  break;
                }
              }
              if ($next == '') { // get the last insert query
                $next = 'insert';
              }
              if ( (eregi('create', $next)) || (eregi('insert', $next)) || (eregi('drop t', $next)) ) {
                $next = '';
                $sql_array[] = substr($restore_query, 0, $i);
                $restore_query = ltrim(substr($restore_query, $i+1));
                $sql_length = strlen($restore_query);
                $i = strpos($restore_query, ';')-1;
              }
            }
          }

          tep_db_query("drop table if exists address_book, address_format, banners, banners_history, categories, categories_description, configuration, configuration_group, counter, counter_history, countries, currencies, customers, customers_basket, customers_basket_attributes, customers_info, languages, manufacturers, manufacturers_info, orders, orders_products, orders_status, orders_status_history, orders_products_attributes, orders_products_download, products, products_attributes, products_attributes_download, prodcts_description, products_options, products_options_values, products_options_values_to_products_options, products_to_categories, reviews, reviews_description, sessions, specials, tax_class, tax_rates, geo_zones, whos_online, zones, zones_to_geo_zones");

          for ($i=0, $n=sizeof($sql_array); $i<$n; $i++) {
            tep_db_query($sql_array[$i]);
          }

          tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'DB_LAST_RESTORE'");
          tep_db_query("insert into " . TABLE_CONFIGURATION . " values ('', 'Last Database Restore', 'DB_LAST_RESTORE', '" . $read_from . "', 'Last database restore file', '6', '', '', now(), '', '')");

          if (isset($remove_raw) && ($remove_raw == true)) {
            unlink($restore_from);
          }

          $messageStack->add_session('search', SUCCESS_DATABASE_RESTORED, 'success');
        }

        tep_redirect(tep_href_link(FILENAME_BACKUP));
        break;
      case 'download':
        $extension = substr($_GET['file'], -3);

        if ( ($extension == 'zip') || ($extension == '.gz') || ($extension == 'sql') ) {
          if ($fp = fopen(DIR_FS_BACKUP . $_GET['file'], 'rb')) {
            $buffer = fread($fp, filesize(DIR_FS_BACKUP . $_GET['file']));
            fclose($fp);

            header('Content-type: application/x-octet-stream');
            header('Content-disposition: attachment; filename=' . $_GET['file']);

            echo $buffer;

            exit;
          }
        } else {
          $messageStack->add('search', ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE, 'error');
        }
        break;
      case 'deleteconfirm':
        if (strstr($_GET['file'], '..')) tep_redirect(tep_href_link(FILENAME_BACKUP, '', 'SSL'));

        tep_remove(DIR_FS_BACKUP . '/' . $_GET['file']);

        if (!$tep_remove_error) {
          $messageStack->add_session('search', SUCCESS_BACKUP_DELETED, 'success');

          tep_redirect(tep_href_link(FILENAME_BACKUP));
        }
        break;
    }
  }

// check if the backup directory exists
  $dir_ok = false;
  if (is_dir(DIR_FS_BACKUP)) {
    if (is_writeable(DIR_FS_BACKUP)) {
      $dir_ok = true;
    } else {
      $messageStack->add('search', ERROR_BACKUP_DIRECTORY_NOT_WRITEABLE, 'error');
    }
  } else {
    $messageStack->add('search', ERROR_BACKUP_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
</head>
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
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TITLE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_FILE_DATE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_FILE_SIZE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  if ($dir_ok == true) {
    $dir = dir(DIR_FS_BACKUP);
    $contents = array();
    while ($file = $dir->read()) {
      if (!is_dir(DIR_FS_BACKUP . $file)) {
        $contents[] = $file;
      }
    }
    sort($contents);

    for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
      $entry = $contents[$i];

      $check = 0;

      if ((!isset($_GET['file']) || (isset($_GET['file']) && ($_GET['file'] == $entry))) && !isset($buInfo) && ($action != 'backup') && ($action != 'restorelocal')) {
        $file_array['file'] = $entry;
        $file_array['date'] = date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry));
//$file_array['size'] = number_format(filesize(DIR_FS_BACKUP . $entry)) . ' bytes';

        $file_array['size'] = number_format(filesize(DIR_FS_BACKUP . $entry)) . BYTES;

        switch (substr($entry, -3)) {
          case 'zip': $file_array['compression'] = 'ZIP'; break;
          case '.gz': $file_array['compression'] = 'GZIP'; break;
          default: $file_array['compression'] = TEXT_NO_EXTENSION; break;
        }

        $buInfo = new objectInfo($file_array);
      }

      if (isset($buInfo) && is_object($buInfo) && ($entry == $buInfo->file)) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
        $onclick_link = 'file=' . $buInfo->file . '&action=restore';
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
        $onclick_link = 'file=' . $entry;
      }
?>
                <td class="dataTableContent" onclick="document.location.href='<?php echo tep_href_link(FILENAME_BACKUP, $onclick_link); ?>'"><?php echo '<a href="' . tep_href_link(FILENAME_BACKUP, 'action=download&file=' . $entry) . '">' . tep_image(DIR_WS_ICONS . 'file_download.gif', ICON_FILE_DOWNLOAD) . '</a>&nbsp;' . $entry; ?></td>
                <td class="dataTableContent" align="center" onclick="document.location.href='<?php echo tep_href_link(FILENAME_BACKUP, $onclick_link); ?>'"><?php echo date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry)); ?></td>
                <td class="dataTableContent" align="right" onclick="document.location.href='<?php echo tep_href_link(FILENAME_BACKUP, $onclick_link); ?>'"><?php echo number_format(filesize(DIR_FS_BACKUP . $entry)); ?> <!-- bytes --><?php echo BYTES;?></td>
                <td class="dataTableContent" align="right"><?php if (isset($buInfo) && is_object($buInfo) && ($entry == $buInfo->file)) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $entry) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
    $dir->close();
  }
?>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
              <tr>
                <td class="smallText" colspan="4"><table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><?php echo TEXT_BACKUP_DIRECTORY . ' ' . DIR_FS_BACKUP; ?></td>
                    <td align="right" class="smallText">
                      <?php if ( ($action != 'backup') && (isset($dir)) && !get_cfg_var('safe_mode') && $dir_ok == true ) {
                              echo '<a href="' . tep_href_link(FILENAME_BACKUP, 'action=backup'.(($debug=='ON')?'&debug=ON':''), 'SSL') . '">' .
                                   tep_image_button('button_backup.gif', IMAGE_BACKUP) . '</a>';
                            }
                            if ( ($action != 'restorelocal') && isset($dir) ) {
                              echo '<a href="' . tep_href_link(FILENAME_BACKUP, 'action=restorelocal'.(($debug=='ON')?'&debug=ON':''), 'SSL') . '">' .
                                   tep_image_button('button_restore.gif', IMAGE_RESTORE) . '</a>';
                            } ?>
                    </td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (defined('DB_LAST_RESTORE')) {
?>
              <tr>
                <td class="smallText" colspan="4"><?php echo TEXT_LAST_RESTORATION . ' ' . DB_LAST_RESTORE . ' <a href="' . tep_href_link(FILENAME_BACKUP, 'action=forget') . '">' . TEXT_FORGET . '</a>'; ?></td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'backup':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_BACKUP . '</b>');

      $contents = array('form' => tep_draw_form('backup', FILENAME_BACKUP, 'action=backupnow'));
      $contents[] = array('text' => TEXT_INFO_NEW_BACKUP);

      $contents[] = array('text' => '<br>' . tep_draw_radio_field('compress', 'no', true) . ' ' . TEXT_INFO_USE_NO_COMPRESSION);
      if ($d_php_gzip_on == '1' || $d_serv_gzip_avail == '1') $contents[] = array('text' => '<br>' . tep_draw_radio_field('compress', 'gzip') . ' ' . TEXT_INFO_USE_GZIP);
      if ($d_serv_zip_avail == '1') $contents[] = array('text' => tep_draw_radio_field('compress', 'zip') . ' ' . TEXT_INFO_USE_ZIP);

      // Download to file --- Should only be done if SSL is active, otherwise database is exposed as clear text
      if ($dir_ok == true) {
        $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('download', 'yes') . ' ' . TEXT_INFO_DOWNLOAD_ONLY . '*<br><span class="errorText">*' . TEXT_INFO_BEST_THROUGH_HTTPS . '</span>');
      } else {
        $contents[] = array('text' => '<br>' . tep_draw_radio_field('download', 'yes', true) . ' ' . TEXT_INFO_DOWNLOAD_ONLY . '*<br><span class="errorText">*' . TEXT_INFO_BEST_THROUGH_HTTPS . '</span>');
      }
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_BACKUP) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_backup.gif', IMAGE_BACKUP));
      break;
    case 'restore':
      $heading[] = array('text' => '<b>' . $buInfo->date . '</b>');

      $contents[] = array('text' => tep_break_string(sprintf(TEXT_INFO_RESTORE, DIR_FS_BACKUP . (($buInfo->compression != TEXT_NO_EXTENSION) ? substr($buInfo->file, 0, strrpos($buInfo->file, '.')) : $buInfo->file), ($buInfo->compression != TEXT_NO_EXTENSION) ? TEXT_INFO_UNPACK : ''), 35, ' '));
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a><a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=restorenow') . '">' . tep_image_button('button_restore.gif', IMAGE_RESTORE) . '</a>');
      break;
    case 'restorelocal':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_RESTORE_LOCAL . '</b>');

      $contents = array('form' => tep_draw_form('restore', FILENAME_BACKUP, 'action=restorelocalnow', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_INFO_RESTORE_LOCAL . '<br><br>' . TEXT_INFO_BEST_THROUGH_HTTPS);
      $contents[] = array('text' => '<br>' . tep_draw_file_field('sql_file'));
      $contents[] = array('text' => TEXT_INFO_RESTORE_LOCAL_RAW_FILE);
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_BACKUP) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_restore.gif', IMAGE_RESTORE));
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . $buInfo->date . '</b>');

      $contents = array('form' => tep_draw_form('delete', FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $buInfo->file . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE));
      break;
    default:
      if (isset($buInfo) && is_object($buInfo)) {
        $heading[] = array('text' => '<b>' . $buInfo->date . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=restore') . '">' . tep_image_button('button_restore.gif', IMAGE_RESTORE) . '</a><a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE . ' <b>' . $buInfo->date . '</b>');
        $contents[] = array('text' => TEXT_INFO_SIZE . ' <b>' . $buInfo->size . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_COMPRESSION . ' <b>' . $buInfo->compression . '</b>');
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
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
