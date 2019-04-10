<?php
//
// +----------------------------------------------------------------------+
//  Functional File for EZInstall system
// +----------------------------------------------------------------------+
// | Copyright (c) 2003-2006 AlgoZonem Inc |
// ||
// | http://www.algozone.com
// ||
// +----------------------------------------------------------------------+
// | This source file is subject to AlgoZone specific license
// | This file can be redistibuted without any code changes.
// | All code changes should be reported to support@algozone.com
// |
// | ONLY change in configuration setting are allowed
// |
// |
// +----------------------------------------------------------------------+
//$Id: installer_functions.php 2006-09-13 $
//
//########### Configuration settings ########################//

  //######### Distributor affiliate ID ###########//
  //######### Get your affiliate ID at http://www.affilliateready.com #######/
  DEFINE('RFKEY','2');
  //################################################//
  DEFINE('AI_TMP_DIR',DIR_FS_CATALOG."tmp/");
  DEFINE('AI_BAK_DIR',AI_TMP_DIR."backup/");
  DEFINE('FTP_TIME_OUT','5');
  DEFINE('FTP_PORT','21');

//########### !!!!!!!!DO NOT EDIT BELOW THIS LINE!!!!!!!!!!! ########################//
//###################################################################################//
//###################################################################################//
//###################################################################################//
//###################################################################################//
//###################################################################################//
//###################################################################################//
  require_once("includes/modules/ez_installer/class.xml.php");

  function read_dir($dir) {
     $array = array();
     $d = dir($dir);
     while (false !== ($entry = $d->read())) {
       if($entry!='.' && $entry!='..') {
         $entry = $dir.'/'.$entry;
         if(is_dir($entry)) {
           $array[] = $entry;
           $array = array_merge($array, read_dir($entry));
         } else {
           $array[] = $entry;
         }
       }
     }
     $d->close();
     return $array;
  }

  if (!function_exists('ftp_chmod')) {
     function ftp_chmod($ftp_stream, $mode, $filename)
     {
       return @ftp_site($ftp_stream, sprintf('CHMOD %o %s', $mode, $filename));
     }
  }

  function getServerName(){
    foreach(array('localhost',str_replace('http://','',HTTP_SERVER)) as $server){
      $conn_id = @ftp_connect($server, FTP_PORT, FTP_TIME_OUT);
      if($conn_id == true){
          ftp_close($conn_id);
        return $server;
      }
    }
  }

  function clean($fileglob)
  {
     if (is_string($fileglob)) {
       if (is_file($fileglob)) {
         return @unlink($fileglob);
       } else if (is_dir($fileglob)) {
         $sub_dirs = read_dir($fileglob);
         foreach($sub_dirs as $cur_dir){
         $ok = clean("$cur_dir");
         }
         if (! $ok) {
           return false;
         }
         return @rmdir($fileglob);
       }
     } else {
       return false;
     }

     return true;
  }

  function fileBackup(){
    if(!is_dir(AI_DOWNLOAD_DIR)){
      $message = "The EZ install tool cannot find the template download files. Please restart the install process and download the template files before running the file backup.";
      return array(false, $message,'');
    }

    $tmp_backup_filename = AI_TMP_DIR.AI_BACKUP_FILENAME . '.tar.gz';
    if(isset($tmp_backup_filename)){
      $backup_filename = str_replace(AI_TMP_DIR,'',$tmp_backup_filename);
      $message = "File Backup Complete. <br>Click here to get a copy of the backup file -> ";
      $message.= "<a href='".HTTP_CATALOG_SERVER.DIR_WS_CATALOG.'tmp/'.basename($backup_filename)."'>".basename($backup_filename)."</a>";
      return array(true, $message);
    }

    # BUILD LIST OF TEMPLATE FILES
    $currdir =getcwd();
    chdir(AI_DOWNLOAD_DIR);
    $template_file_list = read_dir(".");
    foreach($template_file_list as $f){
      @chmod($f,0777);
      if(is_dir($f)){
        $tmp = str_replace("./template_source/","",$f);
        if($tmp == "./template_source"){ continue; }
        $dirs[] = $tmp;
        continue;
      }
      $tmp = str_replace("./template_source/","",$f);
      if($tmp == "./template_source"){ continue; }
      $files[] = $tmp;
    }
    chdir($currdir);
    # BUILD LIST OF TEMPLATE FILES

    # LIST DIRS FILES THAT ARE NOT READABLE
    $dir_list = "";$file_list ="";$readable=true;
    foreach($dirs as $f){
      if(file_exists(DIR_FS_CATALOG.$f) && !is_readable(DIR_FS_CATALOG.$f)){
//        $dir_list .= '<tr><td>'.$f.'</td><td style="color:red;">Unreadable</td></tr>';
        $dir_list .= '<tr><td>' .
                 '<table width="100%" border="0" cellpadding="5">'.
                '<tr><td style="border: 1px dashed #000000;border-bottom: 0px;border-right:0px;">'.$f.'</td><td style="color:red;width:10%;border: 1px dashed #000000;border-bottom: 0px;border-left:0px;">Unwritable</td>' .
                '<tr><td colspan="2" style="border: 1px dashed #000000;border-top: 0px dashed #000000;color:#000099">chmod 777 '.DIR_FS_CATALOG.$f.'</td><tr>' .
                '</table>'.
                '</td></tr>';
        $readable = false;
      }
    }
    # LIST DIRS THAT ARE NOT READABLE END

    # LIST FILES THAT ARE NOT READABLE
    $write_errs = "";$perm_cmds ="";$require_display=false;
    foreach($files as $f){
      if(file_exists(DIR_FS_CATALOG.$f) && !is_readable(DIR_FS_CATALOG.$f)){
        $file_list .= '<tr><td>' .
                 '<table width="100%" border="0" cellpadding="5">'.
                '<tr><td style="border: 1px dashed #000000;border-bottom: 0px;border-right:0px;">'.$f.'</td><td style="color:red;width:10%;border: 1px dashed #000000;border-bottom: 0px;border-left:0px;">Unwritable</td>' .
                '<tr><td colspan="2" style="border: 1px dashed #000000;border-top: 0px dashed #000000;color:#000099">chmod 777 '.DIR_FS_CATALOG.$f.'</td><tr>' .
                '</table>'.
                '</td></tr>';
        $readable = false;
      }
    }
    # LIST THAT ARE NOT READABLE END

    if(!$readable){
      $message = "The following files(s)/directories caused the backup to fail. Please copy and execute the commands in blue to update the file permissions and continue with the file backup.<p>";
      $message.= "<table width='600' cellpadding='1' border='0'>";
      $message.= $dir_list;
      $message.= $file_list;
      $message.= "</table>";
      return array(false, $message);
    }

    # CREATE BACKUP DIR
    if(!is_dir(AI_BAK_DIR)){
      //create folder for extracting
      if(!@mkdir(AI_BAK_DIR)){
        return array(false,"Install tool unable to create a backup directory, ".AI_BAK_DIR.". Please give write permissions to your tmp directory in order for the backup to continue.");
      }
      @chmod(AI_BAK_DIR, 0777);
    }
    # CREATE BACKUP DIR END

    # VERIFY BACKUP DIR WRITABLE
    if(!is_writable(AI_BAK_DIR)){
      return array(false,"Install tool is unable to write to the backup directory, ".AI_BAK_DIR.". Please give write permissions to the ".$backup_dir." directory in order for backup to continue");
    }
    # VERIFY BACKUP DIR WRITABLE END

    # CREATE A BACKUP OF EXISTING FILES
    foreach($template_file_list as $sf){
      $sf = str_replace("./template_source/",'template_source/',$sf);
      $f  = str_replace("template_source/",'',$sf);
      if(is_dir(AI_DOWNLOAD_DIR.$sf)){
        if($sf == "./template_source") continue;
        if(!is_dir(AI_BAK_DIR.$f) && is_dir(DIR_FS_CATALOG.$f)){
          //create folder for extracting
          if(!@mkdir(AI_BAK_DIR.$f)){
            echo "Cannot process with update. Backup folder '".AI_BAK_DIR.$f."' is not created.";
            return false;
          }
          @chmod(AI_BAK_DIR.$f, 0777);
        }

        continue;
      }
      # make backup of existing files
      if(file_exists(DIR_FS_CATALOG.$f)){
        copy(DIR_FS_CATALOG.$f,AI_BAK_DIR.$f);
        @chmod(AI_BAK_DIR.$f, 0777);
      }
    }
    # CREATE A BACKUP OF EXISTING FILES END

    # ARCHIVE BACKUP FILES
    $currdir =getcwd();
    chdir(AI_BAK_DIR);

    $filename = "ezinstall_backup-".date("Ymd");

    if(strpos(strtolower($_SERVER["SERVER_SOFTWARE"]), "win32")){
      //first step - create tar archive
      $command_line = DIR_FS_ADMIN . "/7zip/7z.exe a -ttar ".AI_TMP_DIR.AI_BACKUP_FILENAME.".tar ";

      $result = exec($command_line, $a);

      //second step - gzip archive
      $command_line = DIR_FS_ADMIN . "/7zip/7z.exe a -tgzip ".AI_TMP_DIR.AI_BACKUP_FILENAME.".tar.gz ".AI_TMP_DIR.AI_BACKUP_FILENAME.".tar";

      $result = exec($command_line, $a);
      @unlink(AI_TMP_DIR.AI_BACKUP_FILENAME.".tar");
      $files_extracted = true;
    }
    else{
      $command_line =  "tar -cvf ".AI_TMP_DIR.AI_BACKUP_FILENAME.".tar *";
      $result = exec($command_line, $a);

      $command_line =  "gzip ".AI_TMP_DIR.AI_BACKUP_FILENAME.".tar";
      $result = exec($command_line, $a);
      @unlink(AI_TMP_DIR.AI_BACKUP_FILENAME.".tar");
      @chmod(AI_TMP_DIR.AI_BACKUP_FILENAME.".tar.gz", 0777);
    }
    chdir($currdir);

    clean(AI_BAK_DIR);

    $backup_filename = AI_BACKUP_FILENAME.".tar.gz";
    # ARCHIVE BACKUP FILES END

    copy(AI_TMP_DIR.$filename.".tar.gz", DIR_FS_BACKUP.$filename.".tar.gz");
    @chmod(DIR_FS_BACKUP.$filename.".tar.gz", 0777);

    $message = "File Backup Completed. <br>Click here to get a copy of the backup file -> ";
    $message.= "<a href='".HTTP_CATALOG_SERVER.DIR_WS_CATALOG.'tmp/'.basename($backup_filename)."'>".basename($backup_filename)."</a>";
    return array(true, $message);
  }

  function fileDownload(){
    global $action,$key,$email,$type,$image,$download_file,$template_name,$default,$filesize,$fs,$fid,$fpw;

    $url = EZINSTALL_SERVER."?action=$action&key=$key&email=$email&type=$type".moduleparams();

    # IS TMP DIR WRITABLE
    if(!is_writable(AI_TMP_DIR)){
      $dir_list .= '<tr><td>' .
              '<table width="100%" border="0" cellpadding="5">'.
              '<tr><td style="border: 1px dashed #000000;border-bottom: 0px;border-right:0px;">'.AI_TMP_DIR.'</td><td style="color:red;width:10%;border: 1px dashed #000000;border-bottom: 0px;border-left:0px;">Unwritable</td>' .
              '<tr><td colspan="2" style="border: 1px dashed #000000;border-top: 0px dashed #000000;color:#000099">chmod 777 '.AI_TMP_DIR.'</td><tr>' .
              '</table>'.
              '</td></tr>';

      $message = "The following file(s)/directories caused the template download to fail. Please copy and execute the commands in blue to update the file permissions and continue with the download.<p>";
      $message.= "<table width='600' cellpadding='1' border='0'>";
      $message.= $dir_list;
      $message.= "</table>";
      return array(false, array('File download failed. Please see message below.',$message));
    }

    $template_download_dir =  str_replace("_".$key.".tar.gz",'',AI_TMP_DIR.$download_file);

    # IF TMP TEMPLATE FILES EXIST, ASK TO RE-DOWNLOAD
    if(is_dir($template_download_dir) && !is_writable($template_download_dir)){
      return array(false, "The temporary download directory, ".$template_download_dir." is not writable. Please edit the permissions in order to continue.");
    }
    # IF TMP TEMPLATE FILES EXIST, ASK TO RE-DOWNLOAD

    # CREATE TMP DOWNLOAD DIR, IF NOT EXIST
    if(is_dir($template_download_dir) && !is_writable($template_download_dir)){
      return array(false, "The temporary download directory, ".$template_download_dir." is not writable. Please edit the permissions in order to continue.");
    }
    # CREATE TMP DOWNLOAD DIR, IF NOT EXIST END

    # PREP & DOWNLOAD TEMPLATE FILE
    @unlink(AI_TMP_DIR.$download_file);

    $rfile = fopen($url, "r");
    $lfile = fopen(AI_TMP_DIR.$download_file, "w");
    while(!feof($rfile)) fwrite($lfile, fread($rfile, 1024), 1024);
    fclose($rfile);
    fclose($lfile);

    @chmod(AI_TMP_DIR.$download_file,0777);
    # PREP & DOWNLOAD TEMPLATE FILE END

    if(filesize(AI_TMP_DIR.$download_file) != $filesize){
      $rfile = fopen(AI_TMP_DIR.$download_file, "r");
      while(!feof($rfile)) $buffer.=fread($rfile, 1);
      fclose($rfile);

      $xml_parser = new XMLParser();
      $xml = $xml_parser->parse($buffer);

      $error_text    = $xml["install_info"][0]["error"][0]["error_text"][0];

      return array(false, $error_text);
    }

    # EXTRACT TEMPLATE FILES
    if(strpos(strtolower($_SERVER["SERVER_SOFTWARE"]), "win32")){
      //first step - extact tar archive from tar.gz
      $command_line = DIR_FS_ADMIN . "/7zip/7z.exe x -aoa -o\"".AI_TMP_DIR."\" \"".AI_TMP_DIR.$download_file."\"";

      $result = exec($command_line, $a);

      //second step - extract files from tar archive
      $command_line = DIR_FS_ADMIN . "/7zip/7z.exe x -aoa -o\"".AI_TMP_DIR."\" \"". str_replace("_".$key,'', substr(AI_TMP_DIR.$download_file,0, strlen($file_name)-3) )."\"";

      $result = exec($command_line, $a);

      @unlink(substr(AI_TMP_DIR.$download_file, 0, strlen(AI_TMP_DIR.$download_file)-3));
      @unlink(AI_TMP_DIR.$download_file.'.tar');
      $files_extracted = true;
      //backup installed file
      //@rename($file_name, "content/admin/update/installed-". $this->fixes[$fix_id]["base_name"]);
    }
    else{
      $command_line =  "tar -xvzf ".AI_TMP_DIR.$download_file." -C ".AI_TMP_DIR." -P ".str_replace("_".$key.".tar.gz",'',$download_file)."/template_source";
      $result = exec($command_line, $a);

      @chmod($template_download_dir, 0777);
      @unlink(AI_TMP_DIR.$download_file);
    }
    # EXTRACT TEMPLATE FILES END

    $download_dir = str_replace(".tar.gz",'',AI_TMP_DIR.$download_file);

    # CHANGE PERMISSIONS OF ALL FILES UNDER TEMPLATE_SOURCE DIR
    $currdir =getcwd();
    @chdir($template_download_dir);
    $file_list = read_dir(".");
    foreach($file_list as $f){
      @chmod($f, 0777);
    }
    chdir($currdir);
    # CHANGE PERMISSIONS OF ALL FILES UNDER TEMPLATE_SOURCE DIR  END

    return array(true,"Template File Downloaded Successfully");
  }

  function moduleparams(){
    return "&rfkey=".urlencode(RFKEY)."&ver=".urlencode(EZINSTALL_VERSION)."&platform=".urlencode(strtolower(INSTALLED_VERSION_TYPE . INSTALLED_VERSION_MAJOR . INSTALLED_VERSION_MINOR))."&instpath=".urlencode(HTTP_CATALOG_SERVER.DIR_WS_CATALOG);
  }

  function regularUpdate(){
    global $action,$key,$image,$template,$default,$backup_filename,$template_name,$tmp_backup_filename;

    if(!is_dir(AI_DOWNLOAD_DIR)){
      $message = "The EZ install tool cannot find the template download files. Please restart the install process and download the template files before running the file backup.";
      return array(false, $message,'');
    }

    # BUILD LIST OF TEMPLATE FILES
    $currdir =getcwd();
    chdir(AI_DOWNLOAD_DIR);
    $template_file_list = read_dir(".");
    chdir($currdir);
    # BUILD LIST OF TEMPLATE FILES

    $writable = true;
    $dir_list='';$file_list='';
    foreach($template_file_list as $f){
      $f  = str_replace("./template_source/",'',$f);
      if($f == "./template_source") continue;
      if(is_dir(AI_DOWNLOAD_DIR.$f)){
        if(file_exists(DIR_FS_CATALOG.$f) && !is_writable(DIR_FS_CATALOG.$f)){
          $dir_list .= '<tr><td>' .
                  '<table width="100%" border="0" cellpadding="5">'.
                  '<tr><td style="border: 1px dashed #000000;border-bottom: 0px;border-right:0px;">'.$f.'</td><td style="color:red;width:10%;border: 1px dashed #000000;border-bottom: 0px;border-left:0px;">Unwritable</td>' .
                  '<tr><td colspan="2" style="border: 1px dashed #000000;border-top: 0px dashed #000000;color:#000099">chmod 777 '.DIR_FS_CATALOG.$f.'</td><tr>' .
                  '</table>'.
                  '</td></tr>';
          $writable = false;
        }
        continue;
      }

      if(file_exists(DIR_FS_CATALOG.$f) && !is_writable(DIR_FS_CATALOG.$f)){
        $file_list .= '<tr><td>' .
                 '<table width="100%" border="0" cellpadding="5">'.
                '<tr><td style="border: 1px dashed #000000;border-bottom: 0px;border-right:0px;">'.$f.'</td><td style="color:red;width:10%;border: 1px dashed #000000;border-bottom: 0px;border-left:0px;">Unwritable</td>' .
                '<tr><td colspan="2" style="border: 1px dashed #000000;border-top: 0px dashed #000000;color:#000099">chmod 777 '.DIR_FS_CATALOG.$f.'</td><tr>' .
                '</table>'.
                '</td></tr>';
        $writable = false;
      }
    }

    if(!$writable){
      $message = "The following file(s)/directories caused the file update to fail. Please copy and execute the commands in blue to update the file permissions and continue with the backup.<p>";
      $message.= "<table width='600' cellpadding='1' border='0'>";
      $message.= $dir_list.$file_list;
      $message.= "</table>";
      return array(false, $message);
    }

    # START INSTALLING FILES
    foreach($template_file_list as $sf){
      $sf = str_replace("./template_source/",'template_source/',$sf);
      $f  = str_replace("template_source/",'',$sf);
      if(is_dir(AI_DOWNLOAD_DIR.$sf)){
        if($sf == "./template_source") continue;
        if(!is_dir(DIR_FS_CATALOG.$f)){
          //create folder for extracting
          if(!@mkdir(DIR_FS_CATALOG.$f)){
            return array(false,"Install tool unable to create directory ".DIR_FS_CATALOG.$f . " Please set the parent directory, ".str_replace(basename($f),'',DIR_FS_CATALOG.$f)." to writable in order to continue.");
          }
          @chmod(DIR_FS_CATALOG.$f, 0777);
        }
        continue;
      }

      $do_chmod = (!file_exists(DIR_FS_CATALOG.$f) ? true : false );

      # make backup of existing files
      @copy(AI_DOWNLOAD_DIR.$sf,DIR_FS_CATALOG.$f);

      if($do_chmod){
        @chmod(DIR_FS_CATALOG.$f, 0777);
      }
    }
    #START INSTALLING FILES END

    $message = "Please click on the insert button below to complete the installation process. If you wish to make $template_name your default template, please make sure that 'Set as site default' box is checked before hitting the insert button.";

    clean(AI_DOWNLOAD_DIR);
    clean(AI_TMP_DIR.AI_BACKUP_FILENAME.'.tar.gz');
    return array(true,$message);
  }

  function ftpUpdate(){
    global $action,$key,$email,$type,$image,$download_file,$template_name,$default,$filesize,$fs,$fid,$fpw,$tmp_backup_filename;

    if(!is_dir(AI_DOWNLOAD_DIR)){
      $message = "The EZ install tool cannot find the template download files. Please restart the install process and download the template files before running the file backup.";
      return array(false, $message,'');
    }

    $conn_id = @ftp_connect($fs);
    if($conn_id == false){
      return array(false, "Unable to connect to FTP server $fs. Please verify that your server has FTP access enabled.");
    }

    if(@ftp_login($conn_id, $fid, $fpw) == false){
      return array(false, "Unable to Login to $fs. Please verify your information and try again.");
    }

    $ftp_root_dir = '';
    $ftp_root_dir_array = array();
    $ftp_root_dir_array[4] = str_replace($_SERVER['USER_ROOT'], '', DIR_FS_CATALOG);
    $ftp_root_dir_array[3] = DIR_WS_CATALOG;
    $ftp_root_dir_array[2] = "public_html/" . DIR_WS_CATALOG;
    $ftp_root_dir_array[1] = "www/" . DIR_WS_CATALOG;

    //need to loot to get right connection. do test put here. assign working directory to $ftp_root_dir =

    # BUILD LIST OF TEMPLATE FILES
    $currdir =getcwd();
    chdir(AI_DOWNLOAD_DIR);
    $template_file_list = read_dir(".");
    chdir($currdir);
    # BUILD LIST OF TEMPLATE FILES

    # START INSTALLING FILES
    foreach($template_file_list as $sf){
      $sf = str_replace("./template_source/",'template_source/',$sf);
      $f  = str_replace("template_source/",'',$sf);
      if(is_dir(AI_DOWNLOAD_DIR.$sf)){
        if($sf == "./template_source") continue;
        if(!is_dir(DIR_FS_CATALOG.$f)){
          //create folder for extracting
          if(!@ftp_mkdir($conn_id, $ftp_root_dir.$f)){
            ftp_close($conn_id);
            return array(false,"Install tool unable to create directory ".DIR_FS_CATALOG.$f . " Please set the parent directory, ".str_replace(basename($f),'',DIR_FS_CATALOG.$f)." to writable in order to continue.");
          }
          if(ftp_chmod($conn_id, 0777, $ftp_root_dir.$f)==false){
            ftp_close($conn_id);
            return array(false,"Install tool unable to set permissions on new directory, ".DIR_FS_CATALOG.$f . " Please set the parent directory, ".str_replace(basename($f),'',DIR_FS_CATALOG.$f)." to writable in order to continue.");
          }
        }

        if(!is_writable(DIR_FS_CATALOG.$f)){
          if(ftp_chmod($conn_id, 0777, $ftp_root_dir.$f)==false){
            ftp_close($conn_id);
            return array(false,"Install tool unable to update ".DIR_FS_CATALOG.$f . " Please set the parent directory, ".str_replace(basename($f),'',DIR_FS_CATALOG.$f)." to writable in order to continue.");
          }
        }
        continue;
      }

      if(  stristr(AI_DOWNLOAD_DIR.$sf, ".php")
        || stristr(AI_DOWNLOAD_DIR.$sf, ".txt")
        || stristr(AI_DOWNLOAD_DIR.$sf, ".html")
        || stristr(AI_DOWNLOAD_DIR.$sf, ".css")
        || stristr(AI_DOWNLOAD_DIR.$sf, ".js")
      ){
        $mode = FTP_ASCII;
      }
      else{
        $mode = FTP_BINARY;
      }

      $do_chmod = (!file_exists(DIR_FS_CATALOG.$f) ? true : false );
      // if first time in the loop check for directory structure in FTP
      if ($ftp_root_dir == '')
      {
        foreach($ftp_root_dir_array as $test_dir){
          if(@ftp_put($conn_id, $test_dir.$f, AI_DOWNLOAD_DIR.$sf, $mode)==false){
          }
          else{
            // we are lucky
            $ftp_root_dir = $test_dir;
            break;
          }
        }
      }
      // check one more time.
      if(@ftp_put($conn_id, $ftp_root_dir.$f, AI_DOWNLOAD_DIR.$sf, $mode)==false){
        ftp_close($conn_id);
        return array(false,"Install tool encountered problems during ftp of file ".AI_DOWNLOAD_DIR.$sf . " to ".$ftp_root_dir.$f);
      }

      if($do_chmod){
        ftp_chmod($conn_id, 0777, $ftp_root_dir.$f);
      }
    }
    #START INSTALLING FILES END

    ftp_close($conn_id);

    $message = "Please click on the insert button below to complete the installation process. If you wish to make $template_name your default template, please make sure that 'Set as site default' box is checked before hitting the save button.";

    clean(AI_DOWNLOAD_DIR);
    clean(AI_TMP_DIR.AI_BACKUP_FILENAME.'.tar.gz');
    return array(true,$message);
  }

  function getRequestContents(&$handle) {
  $buffer = '';
    if ($handle) {
      while (!feof($handle)) {
        $line = @fgets($handle, 1024);
        $buffer .= $line;
      }
      return $buffer;
    }
    return false;
  }

  function safe_fopen_and_read($url)
  {
    //NOTE: Can use  @file($url); in the future
    $rfile = @fopen($url, "r");
    $response = getRequestContents($rfile);
    @fclose($rfile);
    return $response;
  }

//(isset($download_file) : $download_file ? '')


  DEFINE('EZINSTALL_SERVER','https://store.template-faq.com/ezinstall/ais_pr.php');
  DEFINE('EZINSTALL_VERSION','ezinstall 1.2');
  DEFINE('AI_DOWNLOAD_DIR',str_replace("_".(isset($key)? $key : '').".tar.gz",'',AI_TMP_DIR.(isset($download_file)? $download_file : '')).'/');
  DEFINE('AI_BACKUP_FILENAME',"ezinstall_backup-".date("Ymd"));
?>