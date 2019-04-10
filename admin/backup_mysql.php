<?php
/* 
  $Id: backup_mysql.php,v 1.2 2004/03/09 17:56:06 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
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
        tep_redirect(tep_href_link(FILENAME_BACKUP_MYSQL, '', 'SSL'));
        break;
        
      case 'backupnow':
        tep_set_time_limit(250);  // not sure if this is needed anymore?

        $backup_file = 'db_' . DB_DATABASE . '-' . date('YmdHis') . '.sql';

        $dump_params .= ' --host=' . DB_SERVER;
        $dump_params .= ' --user=' . DB_SERVER_USERNAME;
        $dump_params .= ' --password=' . DB_SERVER_PASSWORD;
   //     $dump_params .= ' --opt';   //"optimized" -- turns on all "fast" and optimized export methods
        $dump_params .= ' --complete-insert';  // undo optimization slightly and do "complete inserts"--lists all column names for benefit of restore of diff systems
        $dump_params .= ' --add-drop-table ' ; //adds drop table

//        $dump_params .= ' --skip-comments'; // mysqldump inserts '--' as comment delimiters, which is invalid on import (only for mysql v4.01+)
//        $dump_params .= ' --skip-quote-names';
//        $dump_params .= ' --force';  // ignore SQL errors if they occur
//        $dump_params .= ' --compatible=postgresql'; // other options are: ,mysql323, mysql40
        $dump_params .= ' --result-file=' . DIR_FS_BACKUP . $backup_file;
        $dump_params .= ' ' . DB_DATABASE;

        // if using the "--tables" parameter, this should be the last parameter, and tables should be space-delimited
        // fill $tables_to_export with list of tables, separated by spaces, if wanna just export certain tables
        $dump_params .= (($tables_to_export=='') ? '' : ' --tables ' . $tables_to_export);
        $dump_params .= " 2>&1";

        $toolfilename = (isset($_GET['tool']) && $_GET['tool'] != '') ? $_GET['tool'] : $mysqldump_exe;

        if ($debug=='ON') $messageStack->add_session('search', BACKUP_MYSQl_ERROR_MSG_3.$toolfilename . ' ' . $dump_params, 'caution');
        
        $resultcodes=exec($toolfilename . $dump_params , $output, $dump_results );
        exec("exit(0)");

        #parse the value that comes back from the script
        list($strA, $strB) = split ('[|]', $resultcodes);


        if ($debug=='ON') $messageStack->add_session('search', BACKUP_MYSQl_ERROR_MSG_4 . $strA,'error');
        if ($debug=='ON') $messageStack->add_session('search', BACKUP_MYSQl_ERROR_MSG_5 . $strB,'error');
        if ($debug=='ON' || (tep_not_null($dump_results) && $dump_results!='0')) $messageStack->add_session('search', BACKUP_MYSQl_ERROR_MSG_6.$dump_results, 'caution');


        foreach($output as $key=>$value) {$messageStack->add_session('search', "$key => $value<br>",'caution'); }
        //$output contains response strings from execution. This displays if needed.

        if (file_exists(DIR_FS_BACKUP . $backup_file) && ($dump_results == '0' || $dump_results=='')) { // display success message noting that MYSQLDUMP was used
          $messageStack->add_session('search', '<a href="' . ((ENABLE_SSL == 'true') ? DIR_WS_HTTPS_ADMIN : DIR_WS_ADMIN) . 'backups/' . $backup_file . '">' . SUCCESS_DATABASE_SAVED . '</a>', 'success');
        } elseif ($dump_results=='127') {
          $messageStack->add_session('search', FAILURE_DATABASE_NOT_SAVED_UTIL_NOT_FOUND, 'error');
        } else {
          $messageStack->add_session('search', FAILURE_DATABASE_NOT_SAVED, 'error');
        }

        //compress the file as requested & optionally download
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
        }

        tep_redirect(tep_href_link(FILENAME_BACKUP_MYSQL, '', 'SSL'));
        break;
        
      case 'restorenow':
      case 'restorelocalnow':
        tep_set_time_limit(300);
        
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

        //Restore using "mysql"
        $load_params  = ' --database=' . DB_DATABASE;
        $load_params .= ' --host=' . DB_SERVER;
        $load_params .= ' --user=' . DB_SERVER_USERNAME;
        $load_params .= ((DB_SERVER_PASSWORD =='') ? '' : ' --password=' . DB_SERVER_PASSWORD);
        $load_params .= ' ' . DB_DATABASE; // this needs to be the 2nd-last parameter
        $load_params .= ' < ' . $restore_from; // this needs to be the LAST parameter
        $load_params .= " 2>&1";

        if ($debug=='ON') $messageStack->add_session('search', $mysql_exe . ' ' . $load_params, 'warning');

        if (file_exists($restore_from) && $specified_restore_file != '') {
          $toolfilename = (isset($_GET['tool']) && $_GET['tool'] != '') ? $_GET['tool'] : $mysql_exe;
          if ($debug=='ON') $messageStack->add_session('search', BACKUP_MYSQl_ERROR_MSG_3.$toolfilename . ' ' . $dump_params, 'warning');

          $resultcodes=exec($toolfilename . $load_params , $output, $load_results );
          exec("exit(0)");
          //parse the value that comes back from the script
          list($strA, $strB) = split ('[|]', $resultcodes);

          //if restores from compressed file unlink
          if ($remove_raw == 'true'){
            unlink($restore_from);
          }

          if ($debug=='ON') $messageStack->add_session('search', BACKUP_MYSQl_ERROR_MSG_4 . $strA,'warning');
          if ($debug=='ON') $messageStack->add_session('search', BACKUP_MYSQl_ERROR_MSG_5 . $strB,'warning');
          if ($debug=='ON' || (tep_not_null($load_results) && $load_results!='0')) $messageStack->add_session('search', BACKUP_MYSQl_ERROR_MSG_6.$load_results, 'warning');


          if ($load_results == '0') {
            // store the last-restore-date, if successful
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'DB_LAST_RESTORE'");
            tep_db_query("insert into " . TABLE_CONFIGURATION . " values ('', 'Last Database Restore', 'DB_LAST_RESTORE', '" . $specified_restore_file . "', 'Last database restore file', '6', '', '', now(), '', '')");
            $messageStack->add_session('search', '<a href="' . ((ENABLE_SSL == 'true') ? DIR_WS_HTTPS_ADMIN : DIR_WS_ADMIN) . 'backups/' . $specified_restore_file . '">' . SUCCESS_DATABASE_RESTORED . '</a>', 'success');
            } elseif ($load_results == '127') {
            $messageStack->add_session('search', FAILURE_DATABASE_NOT_RESTORED_UTIL_NOT_FOUND, 'error');
            } else {
            $messageStack->add_session('search', FAILURE_DATABASE_NOT_RESTORED, 'error');
            } // endif $load_results
          } else {
            $messageStack->add_session('search', sprintf(FAILURE_DATABASE_NOT_RESTORED_FILE_NOT_FOUND, '[' . $restore_from .']'), 'error');
          } // endif file_exists

          tep_redirect(tep_href_link(FILENAME_BACKUP_MYSQL, '', 'SSL'));
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
        if (strstr($_GET['file'], '..')) tep_redirect(tep_href_link(FILENAME_BACKUP_MYSQL, '', 'SSL'));

        tep_remove(DIR_FS_BACKUP . '/' . $_GET['file']);

        if (!$tep_remove_error) {
          $messageStack->add_session('search', SUCCESS_BACKUP_DELETED, 'success');

          tep_redirect(tep_href_link(FILENAME_BACKUP_MYSQL, '', 'SSL'));
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

  // check to see if safe_mode is on -- can't use mysqldump in safe mode
  if (get_cfg_var('safe_mode')) {
    $messageStack->add('search', ERROR_CANT_BACKUP_IN_SAFE_MODE, 'error');
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
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
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
          <td>
          <?php
  // echo ZIPARCHIVE::CREATE;
?>
</td>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
</table></td>
<?php if (ENABLE_SSL != 'true') {  // display security warning about downloads if not SSL
?>
          <tr>
            <td class="main"><?php //  echo WARNING_NOT_SECURE_FOR_DOWNLOADS;
             ?></td>
            <td class="main" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php } ?>
<!--        </table></td> //-->
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
//  if (!get_cfg_var('safe_mode') && $dir_ok == true) {
    $dir = dir(DIR_FS_BACKUP);
    $contents = array();
    while ($file = $dir->read()) {
      if (!is_dir(DIR_FS_BACKUP . $file)) {
        if ($file != '.empty' && $file != 'empty.txt') {
          $contents[] = $file;
        }
      }
    }
    sort($contents);

    for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
      $entry = $contents[$i];

      $check = 0;

      if ((!isset($_GET['file']) || (isset($_GET['file']) && ($_GET['file'] == $entry))) && !isset($buInfo) && ($action != 'backup') && ($action != 'restorelocal')) {
        $file_array['file'] = $entry;
        $file_array['date'] = date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry));
       // $file_array['size'] = number_format(filesize(DIR_FS_BACKUP . $entry)) . ' bytes';
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
<!--                 <td class="dataTableContent" onclick="document.location.href='<?php echo tep_href_link(FILENAME_BACKUP_MYSQL, $onclick_link); ?>'"><?php echo '<a href="' . tep_href_link(FILENAME_BACKUP_MYSQL, 'action=download&file=' . $entry) . '">' . tep_image(DIR_WS_ICONS . 'file_download.gif', ICON_FILE_DOWNLOAD) . '</a>&nbsp;' . $entry; ?></td> -->
                <td class="dataTableContent" onclick="document.location.href='<?php echo tep_href_link(FILENAME_BACKUP_MYSQL, $onclick_link); ?>'"><?php echo '<a href="' . ((ENABLE_SSL == 'true') ? DIR_WS_HTTPS_ADMIN : DIR_WS_ADMIN) . 'backups/' . $entry . '">' . tep_image(DIR_WS_ICONS . 'file_download.gif', ICON_FILE_DOWNLOAD) . '</a>&nbsp;' . $entry; ?></td>
                <td class="dataTableContent" align="center" onclick="document.location.href='<?php echo tep_href_link(FILENAME_BACKUP_MYSQL, $onclick_link); ?>'"><?php echo date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry)); ?></td>
                <td class="dataTableContent" align="right" onclick="document.location.href='<?php echo tep_href_link(FILENAME_BACKUP_MYSQL, $onclick_link); ?>'"><?php echo number_format(filesize(DIR_FS_BACKUP . $entry)); ?> <!-- bytes --><?php echo BYTES;?></td>
                <td class="dataTableContent" align="right"><?php if (isset($buInfo) && is_object($buInfo) && ($entry == $buInfo->file)) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_BACKUP_MYSQL, 'file=' . $entry) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
    $dir->close();
//  } // endif safe-mode & dir_ok

// now let's display the backup/restore buttons below filelist
?>            </table>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
              <tr>
                <td class="smallText" colspan="4"><table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><?php echo TEXT_BACKUP_DIRECTORY . ' ' . DIR_FS_BACKUP; ?></td>
                    <td align="right" class="smallText">
                      <?php if ( ($action != 'backup') && (isset($dir)) && !get_cfg_var('safe_mode') && $dir_ok == true ) {
                              echo '<a href="' . tep_href_link(FILENAME_BACKUP_MYSQL, 'action=backup'.(($debug=='ON')?'&debug=ON':''), 'SSL') . '">' .
                                   tep_image_button('button_backup.gif', IMAGE_BACKUP) . '</a>';
                            }
                            if ( ($action != 'restorelocal') && isset($dir) ) {
                              echo '<a href="' . tep_href_link(FILENAME_BACKUP_MYSQL, 'action=restorelocal'.(($debug=='ON')?'&debug=ON':''), 'SSL') . '">' .
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
                <td class="smallText" colspan="4"><?php echo TEXT_LAST_RESTORATION . ' ' . DB_LAST_RESTORE . ' <a href="' . tep_href_link(FILENAME_BACKUP_MYSQL, 'action=forget') . '">' . TEXT_FORGET . '</a>'; ?></td>
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
      $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_NEW_BACKUP . '</strong>');

      $contents = array('form' => tep_draw_form('backup', FILENAME_BACKUP_MYSQL, 'action=backupnow'.(($debug=='ON')?'&debug=ON':''), 'post', '', 'SSL'));
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
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_BACKUP_MYSQL,(($debug=='ON')?'debug=ON':''), 'SSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_backup.gif', IMAGE_BACKUP));
      break;
    case 'restore':
      $heading[] = array('text' => '<strong>' . $buInfo->date . '</strong>');

      $contents[] = array('text' => tep_break_string(sprintf(TEXT_INFO_RESTORE, DIR_FS_BACKUP . (($buInfo->compression != TEXT_NO_EXTENSION) ? substr($buInfo->file, 0, strrpos($buInfo->file, '.')) : $buInfo->file), ($buInfo->compression != TEXT_NO_EXTENSION) ? TEXT_INFO_UNPACK : ''), 35, ' '));
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_BACKUP_MYSQL, 'file=' . $buInfo->file.(($debug=='ON')?'&debug=ON':''), 'SSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a><a href="' . tep_href_link(FILENAME_BACKUP_MYSQL, 'file=' . $buInfo->file . '&action=restorenow'.(($debug=='ON')?'&debug=ON':''), 'SSL') . '">' . tep_image_button('button_restore.gif', IMAGE_RESTORE) . '</a>');
      break;
    case 'restorelocal':
      $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_RESTORE_LOCAL . '</strong>');

      $contents = array('form' => tep_draw_form('restore', FILENAME_BACKUP_MYSQL, 'action=restorelocalnow'.(($debug=='ON')?'&debug=ON':''), 'post', 'enctype="multipart/form-data"', 'SSL'));
      $contents[] = array('text' => TEXT_INFO_RESTORE_LOCAL . '<br><br>' . TEXT_INFO_BEST_THROUGH_HTTPS);
      $contents[] = array('text' => '<br>' . tep_draw_file_field('sql_file'));
      $contents[] = array('text' => TEXT_INFO_RESTORE_LOCAL_RAW_FILE);
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_BACKUP_MYSQL,(($debug=='ON')?'debug=ON':''), 'SSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_restore.gif', IMAGE_RESTORE));
      break;
    case 'delete':
      if ($dir_ok == false) continue;
      $heading[] = array('text' => '<strong>' . $buInfo->date . '</strong>');

      $contents = array('form' => tep_draw_form('delete', FILENAME_BACKUP_MYSQL, 'file=' . $buInfo->file . '&action=deleteconfirm', 'post', '', 'SSL'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><strong>' . $buInfo->file . '</strong>');
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_BACKUP_MYSQL, 'file=' . $buInfo->file, 'SSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE));
      break;
    default:
      if (isset($buInfo) && is_object($buInfo)) {
        $heading[] = array('text' => '<strong>' . $buInfo->date . '</strong>');

        $contents[] = array('align' => 'center',
                            'text' => '<br><a href="' . tep_href_link(FILENAME_BACKUP_MYSQL, 'file=' . $buInfo->file . '&action=restore'.(($debug=='ON')?'&debug=ON':''), 'SSL') . '">' . tep_image_button('button_restore.gif', IMAGE_RESTORE) . '</a>' .
                                      (($dir_ok==true) ? '<a href="' . tep_href_link(FILENAME_BACKUP_MYSQL, 'file=' . $buInfo->file . '&action=delete', 'SSL') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>' : '' ) );
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