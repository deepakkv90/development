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
// +----------------------------------------------------------------------+
//$Id: template_installer.php 2006-09-13 $
//
?>
<br>
<?php
  if (isset($_GET['action'])) { return; }
  require('installer_functions.php');

  $action   = isset($_POST['t_action'])? $_POST['t_action'] : "";
  $default  = isset($_POST['default'])? $_POST['default'] : false;
  $option   = isset($_POST['opt'])? $_POST['opt'] : "false";

  if($action == 'download' && $option=='Check Again'){ $action = 'download'; }

  if(isset($_GET['completed'])){
    $message = "<b>Template install completed.</b>";
  }

  if($action == "insert"){
        $template_sql_file = '';
        $template_name_new = $_POST['template_name'];

        if (file_exists(DIR_FS_TEMPLATES . $template_name_new . "/install.sql")){
            $template_sql_file = "install.sql";
        } else if (file_exists(DIR_FS_TEMPLATES . $template_name_new . "/" . $template_name_new . ".sql")){
            $template_sql_file = $template_name_new . ".sql";
        } else {
            $messageStack->add_session('search', ERROR1, 'error');
            tep_redirect(tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'action=new'));
        }
        
        if($template_sql_file == 'default.sql'){
            $messageStack->add_session('search', ERROR3, 'error');
            tep_redirect(tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'action=new'));
        } else {
            $template_sql_file = DIR_FS_TEMPLATES . $template_name_new . "/" . $template_sql_file;
            $sql_data_array = array('template_name' => $template_name_new);
            $update_sql_data = array('date_added' => 'now()');
            $sql_data_array = array_merge($sql_data_array, $update_sql_data);
            tep_db_perform(TABLE_TEMPLATE, $sql_data_array);
            $cID = tep_db_insert_id();
            $data_query = fread(fopen( $template_sql_file, 'rb'), filesize($template_sql_file)) ;
            $data_query = str_replace('#tID#', $cID, $data_query);
            $data_query = str_replace(';', '', $data_query);

           //make an array split on end of line and
             if (isset($data_query)) {
                 $sql_array = array();
                 $sql_length = strlen($data_query);
                 $pos =  strpos($data_query, "\n");
                 
                 $data_query1 = explode("\n",$data_query);
                 $key = key($data_query1);
                 $sql_length = count($data_query1);
                 $pos = $data_query1[$key];
                 for ($i=$key; $i<$sql_length; $i++) {
                     if ( strrchr($data_query1[$i], '--') ) {
                               //if line starts with -- it's a comment ignore
                     } else if ($data_query1[$i] == '') {
                               //if line is empty ignore
                     } else {
                         tep_db_query( $data_query1[$i] );
                     }
                 }
             }//isset()
     
         // pull infobox info
             $infobox_query = tep_db_query("select box_heading, infobox_id from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" .$cID. "'");
            // $infobox = tep_db_fetch_array($infobox_query);
              while ($infobox = tep_db_fetch_array($infobox_query)) {
                  $languages = tep_get_languages();
                  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                  $language_id = $languages[$i]['id'];
                  $box_heading = tep_db_prepare_input($infobox['box_heading']);
                  $infobox_id = $infobox['infobox_id'];
                  $box_heading = str_replace("'", "\\'", $box_heading);
                   tep_db_query("insert into " . TABLE_INFOBOX_HEADING . " (infobox_id, languages_id, box_heading) values ('" . $infobox_id . "', '" . $language_id . "', '" . $box_heading . "') ");
               }
              }//while    
        }

        $messageStack->add_session('search', sprintf(TEMPLATE_INSTALLED_SUCCESS, $template_name_new) , 'success');
        if ($_POST['default'] == 'on') {
          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $template_name . "' where configuration_key = 'DEFAULT_TEMPLATE'");
         $messageStack->add_session('search', $template_name_new . ' is set to site default template.', 'success');
        }
?>
  <SCRIPT language="JavaScript">
  <!--
  window.location="<?php echo tep_href_link(basename($_SERVER['SCRIPT_NAME']), tep_get_all_get_params(array('action', 't_action','cID')).'cID='.$cID.'&completed', 'SSL')?>";
  //-->
  </SCRIPT>
<?php
  return;
  }

  $ftp_err = false;
  if($action == "ftp_update" || $action == "regular_update"){
    if($action == "ftp_update"){
      $fid      = isset($_POST['uid'])? $_POST['uid'] : "";
      $fpw      = isset($_POST['pass'])? $_POST['pass'] : "";
      $fs       = isset($_POST['server'])? $_POST['server'] : "localhost";
      $result = ftpUpdate();
    }
    else{
      $result = regularUpdate();
    }
    $pass     = $result[0];
    $messages = $result[1];
    $newaction= $result[2];
?>
    <fieldset>
      <legend>&nbsp;EZ Install&nbsp;</legend>  <br>
    <div align='center'>
<?php
    if($pass){
      echo "<b>File update completed successfully.</b><p />";
    }
    else{
      echo "<b>File update failed.</b><p />";
    }
?>
    <img src="<?php echo $image_url?>" /><br>
    <?php echo  $template_name ?><br>
    <br>
    <?php echo  $messages; ?>
    <br>
<?php
    if($pass){
?>
    <br>
    <form method="post" action="<?php echo  tep_href_link(basename($_SERVER['SCRIPT_NAME']), tep_get_all_get_params(array('action')), 'SSL'); ?>">
      <input type="hidden" name="t_action" value="insert" />
      <input type="hidden" name="template_name" value="<?php echo $template_name;?>" />
      <input type="checkbox" name="default" /> Set as site default - template <?php echo $template_name; ?><br><br>
      <input type="submit" name="opt" value="Insert" />
    </form>
<?php
    }
    else{
      $t_action = $action;
      if($action=='ftp_update'){ $t_action ='backup';  }
      if(isset($newaction)){ $t_action = $newaction; }
?>
    <br>
    <form method="post" action="<?php echo  tep_href_link(basename($_SERVER['SCRIPT_NAME']), tep_get_all_get_params(array('action')), 'SSL'); ?>">
      <input type="hidden" name="t_action" value="<?php echo  $t_action ?>" />
      <input type="submit" name="opt" value="Try Again" />
    </form>
    </div>
    </fieldset>
<?php
    }

  }

  if($action == "backup"){
    $frt      = isset($_POST['ftp_retry'])? $_POST['ftp_retry'] : "";

    $pass = true;
    if(!isset($_POST['ftp_retry'])){
    $result = fileBackup();
    $pass     = $result[0];
    $messages = $result[1];
    $newaction= $result[2];
    }
?>
    <fieldset>
      <legend>&nbsp;EZ Install&nbsp;</legend>
    <div align='center'>

<?php
    if(!$pass){
      echo "<br><b>File Backup did not complete successfully. Please see message below.</b>";
    }
    else{
      echo '<br><b>'.$messages.'</b>';
    }
?>
    <p />
    <img src="<?php echo $image_url?>" /><br>
    <?php echo  $template_name ?><br>
<?php
    if(!$pass){
      $t_action = 'backup';
      if(isset($newaction)){ $t_action = $new_action; }
?>
    <p>
    <?php echo  $messages; ?>
    <br><br>
    <form method="post" action="<?php echo  tep_href_link(basename($_SERVER['SCRIPT_NAME']), tep_get_all_get_params(array('action')), 'SSL'); ?>">
    <input type="hidden" name="t_action" value="<?php echo  $t_action; ?>" />
    <input type="submit" name="opt" value="Check Again" />
    </form>
<?php
    }
    else{
?>
    <br>
      Please select one of the methods below to complete the template file installation..
      <br>
      <table cellpadding="0" cellspacing="5" width="100%" border="0">
        <tr>
          <td valign="top">
            <form method="post" action="<?php echo  tep_href_link(basename($_SERVER['SCRIPT_NAME']), tep_get_all_get_params(array('action')), 'SSL'); ?>">
            <input type="hidden" name="t_action" value="ftp_update" />
            <fieldset>
              <legend>&nbsp;FTP/Simple&nbsp;
              </legend>
                The EZ install tool will use the FTP information to connect to your site and install the files in the proper directories. Using this option, installation will run faster because FTP will have proper permissions to update your server files.
                <p />
                Please note that your FTP information will only be used internally, within your domain and is not transmitted to any external sources. <p />
                <table width="100%">
                  <?php
                  $ftp_server = getServerName();
                  if (!$ftp_server)
                  {
                    $disabled = "disabled";
                    $ftp_server = "<b>No FTP server detected!</b>";
                  }
                  ?>
                  <tr>
                    <td align="right">
                      Server :
                    </td>
                    <td><?php echo  $ftp_server ?><input type="hidden" name="server" value="<?php echo  $ftp_server ?>"/>
                    </td>
                  </tr>
                  <tr>
                    <td align="right">
                      User Name :
                    </td>
                    <td>
                      <input type="text" name="uid" <?php echo  $disabled ?>/>
                    </td>
                  </tr>
                  <tr>
                    <td align="right">
                      Password :
                    </td>
                    <td>
                      <input type="password" name="pass" <?php echo  $disabled ?>/>
                    </td>
                  </tr>
                  <tr>
                    <td colspan=2>
                      <input type="submit" name="opt" value="install" <?php echo  $disabled ?>/>
                    </td>
                  </tr>
                </table>
            </fieldset>
            </form>
          </td>
          <td valign="top" width="50%" style="height:100%">
            <form method="post" action="<?php echo  tep_href_link(basename($_SERVER['SCRIPT_NAME']), tep_get_all_get_params(array('action')), 'SSL'); ?>">
            <input type="hidden" name="t_action" value="regular_update" />
            <fieldset>
              <legend>Advanced
              </legend>
              The EZ install tool will perform the file updates through your web server. Please have access to your server files prior to install. EZ install may ask you to update file permissions in order to continue.
              <table width="100%">
                <tr>
                  <td height='190' valign='bottom'>
              <p><p><p><p><p><p><p><p><p>

                    <input type="submit" name="opt" value="install" />
                  </td>
                </tr>
              </table>
            </fieldset>
            </form>
          </td>
        </tr>
      </table>
    </form>
    </fieldset>
    </div>
<?php
    }
  }

  $validate_fail = false;
  if($action == "download"){
    if(is_dir(str_replace("_".$key.".tar.gz",'',DIR_FS_CATALOG."tmp/".$download_file))){
      $pass     = true;
      $message = "Template has already been downloaded before. Installation process can use this file.";
      $message .= "<br> If you want to download again you need to delete following file " . DIR_FS_CATALOG."tmp/".$download_file;
      $message .= "<br> Otherwise, proceed with backup and installation.";
    }
    else{
      $result   = fileDownload();
      $pass     = $result[0];
      $message  = $result[1];
    }

    if($pass){
?>
    <fieldset>
      <legend>&nbsp;EZ Install&nbsp;</legend>
  <br>
    <div align='center'>
    <form method="post" action="<?php echo  tep_href_link(basename($_SERVER['SCRIPT_NAME']), tep_get_all_get_params(array('action')), 'SSL'); ?>">
    <?php echo '<b>'.$message.'</b>';?><br>
    <br>
    <img src="<?php echo $image_url?>" /><br>
    <?php echo  $template_name ?><br>
    <input type="hidden" name="t_action" value="backup" />
    <p />
    Please click on the link below to begin the file backup<p />
    <input type="submit" name="opt" value="Create Backup" />
    </form>
    </div>
    </fieldset>
<?php
    }else if(!is_array($message)){
      $validate_fail = true;
    }else{
?>
    <fieldset>
      <legend>&nbsp;EZ Install&nbsp;</legend>
    <br>
    <div align='center'>
    <b><?php echo  $message[0] ?></b><br>
    <form method="post" action="<?php echo  tep_href_link(basename($_SERVER['SCRIPT_NAME']), tep_get_all_get_params(array('action')), 'SSL'); ?>">
    <br>
    <img src="<?php echo $image_url?>" /><br>
    <?php echo  $template_name ?><br>
    <input type="hidden" name="t_action" value="download" />
    <p />
    <?php echo $message[1] ?>
    <input type="submit" name="opt" value="Check Again" />
    </form>
    </div>
    </fieldset>
<?php
    }
  }

  if($action=="validate"){
    $key      = isset($_POST['key'])? $_POST['key'] : "";
    $email    = isset($_POST['email'])? $_POST['email'] : "";

    $key = urlencode(trim($key));
    $email = urlencode(trim($email));

    $buffer="";
    $image_url='';

    $url = EZINSTALL_SERVER . "?action=$action&key=$key&email=$email".moduleparams();

    $buffer = safe_fopen_and_read($url);

    if ($buffer)
    {
      $xml_parser = new XMLParser();
      $xml = $xml_parser->parse($buffer);

      $error_text    = $xml["install_info"][0]["error"][0]["error_text"][0];
      $image_url     = $xml["install_info"][0]["template"][0]["image_url"][0];
      $template_name = $xml["install_info"][0]["template"][0]["name"][0];
      $download_file = $xml["install_info"][0]["template"][0]["filename"][0];
      $filesize      = $xml["install_info"][0]["template"][0]["filesize"][0];
    }
    else
    {
      $error_text = "Can not connect to template-faq host. Check your proxy or firewall settings";
    }

    if($error_text != ""){
      $message = '<b>'.$error_text.'</b><p />';
      $validate_fail = true;
    }

    if($error_text == ""){
      $pass = true;
      $message = "<b>Your Request has been successfully verified.</b>";

      tep_session_register('key');
      tep_session_register('email');
      tep_session_register('image_url');
      tep_session_register('template_name');
      tep_session_register('download_file');
      tep_session_register('filesize');

        $check_query = tep_db_query("select * from " . TABLE_TEMPLATE . " where template_name = '" . tep_db_input($template_name) . "'");

      if( is_dir(DIR_FS_CATALOG.'templates/'.$template_name)|| tep_db_num_rows($check_query)>0){
        $validate_fail = true;
        $message = "<b><font color='red'>Template ".$template_name.', is already installed on your server.<br>Please remove the template files, if you wish to re-install.</font></b><br>';
      }
      else{
  ?>
    <fieldset>
      <legend>&nbsp;EZ Install&nbsp;</legend>
    <br>
    <div align='center'>
    <?php echo  '<b>'.$message.'</b>'; ?><p />
    <img src="<?php echo $image_url?>" /><br>
    <?php echo  $template_name ?><p />

    <p />Please click on the button below to download of the template.<p />
    <form method="post" action="<?php echo  tep_href_link(basename($_SERVER['SCRIPT_NAME']), tep_get_all_get_params(array('action')), 'SSL'); ?>">
      <input type="hidden" name="t_action" value="download" />
      <input type="submit" value="Download Template File" />
    </form>
    </div>
    </fieldset>
  <?php
      }
    }
  }

  if($action=="" || $validate_fail){


    unset($_SESSION['key']);
    unset($_SESSION['email']);
    unset($_SESSION['image_url']);
    unset($_SESSION['template_name']);
    unset($_SESSION['download_file']);
    unset($_SESSION['filesize']);
    unset($_SESSION['fid']);
    unset($_SESSION['fpw']);
    unset($_SESSION['mp_backup_filename']);

    $type="cre6_2";
   // tep_session_register('type');

     $_SESSION['type'] = $type;

    $url = EZINSTALL_SERVER . "?action=display_products".moduleparams();

    $buffer = safe_fopen_and_read($url);
    if ($buffer)
    {
      $xml_parser = new XMLParser();
      $xml = $xml_parser->parse($buffer);
      $vform    = $xml["ez_install"][0]["validateform"][0];
      $announce = $xml["ez_install"][0]["announcement"][0];
      $products = $xml["ez_install"][0]["products"][0];
      $search   = $xml["ez_install"][0]["search"][0];
    }
    else
    {
      $announce = "Can not connect to template-faq host. Check your proxy or firewall settings";
    }

  ?>
    <fieldset>
      <legend>&nbsp;EZ Install&nbsp;</legend>
      <br>
      <div align='center'>
      <?php if(isset($message)){ echo '<b>'.$message.'</b><p />'; }
      ?>
      <?php echo  sprintf($vform,tep_href_link(basename($_SERVER['SCRIPT_NAME']), tep_get_all_get_params(array('action','completed')), 'SSL')); ?>
      </div>
    </fieldset>
    <fieldset style="text-align:center;">
      <legend>&nbsp;Buy CRE Loaded Templates&nbsp;</legend>
      <?php echo  isset($announce)? $announce : ''; ?>
      <?php echo  $products; ?><hr size=1/>
      <?php echo  $search; ?><br>
    </fieldset>
  <?php
  }
?>