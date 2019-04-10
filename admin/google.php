<?php
/*
  $Id: google.php,v 1.1.1.1 2004/03/04 23:38:07 zip1 Exp $
  http://www.oscommerce.com
   google Data Feeder!

  Copyright (c) 2002 - 2005 Calvin K

  Released under the GNU General Public License
*/

  require('includes/application_top.php');



//  Start TIMER
//  -----------
$stimer = explode( ' ', microtime() );
$stimer = $stimer[1] + $stimer[0];
//  -----------
    $data_files_id1 = (int)$_POST['feed_google'];
    //$data_files_id1 = '2';
    $data_query_raw = tep_db_query("select * from  " . TABLE_DATA_FILES . " where data_files_id = '" . $data_files_id1 . "' order by data_files_service ");
    while ($data = tep_db_fetch_array($data_query_raw)) {
  $data_files_id = $data['data_files_id'];
  $data_files_type = $data['data_files_type'];
  $data_files_disc = $data['data_files_disc'];
  $data_files_type1 = $data['data_files_type1'];
  $data_files_service = $data['data_files_service'];
  $data_status = $data['data_status'];
  $data_files_name = $data['data_files_name'];
  $data_image_url = $data['data_image_url'];
  $ftp_server = $data['data_ftp_server'];
  $ftp_user_name = $data['data_ftp_user_name'];
  $ftp_user_pass = $data['data_ftp_user_pass'];
  $ftp_directory = $data['data_ftp_directory'];
  $data_tax_class_id = $data['data_tax_class_id'];
  $data_convert_cur = $data['data_convert_cur'];
  $data_cur_use = $data['data_cur_use'];
  $data_cur = $data['data_cur'];
  $data_lang_use = $data['data_lang_use'];
  $data_lang = $data['data_lang_char'];
  }

$OutFile = $data_files_name;
$source_file = DIR_FS_ADMIN . "feeds/" . $data_files_name;
$destination_file= $data_files_name;

$already_sent = array();

// end configuration information
//Start FTP to google


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
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="menuBoxHeading">
                         <tr>
                     <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                       <tr>
                         <td class="pageHeading"><?php echo HEADING_TITLE ; ?></td>
                        </tr>
                        <tr>
                        <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                       </tr>
                     </table></td>
                   </tr>

    <tr>
        <td>

<?php
function ftp_file( $ftpservername, $ftpusername, $ftppassword, $ftpsourcefile, $ftpdestinationfile )
{
// set up basic connection
$conn_id = ftp_connect($ftpservername);
if ( $conn_id == false )
{
echo TEXT_INFO_FTP_ERROR1 . $ftpservername . "<br>\n" ;
return false;
}

// login with username and password
$login_result = ftp_login($conn_id, $ftpusername, $ftppassword);

// check connection
if ((!$conn_id) || (!$login_result)) {
echo TEXT_INFO_FTP_ERROR2 . "<br>\n";
echo TEXT_INFO_FTP_ERROR3 . $ftpservername . TEXT_INFO_FTP_ERROR4 . $ftpusername . "<br>\n";
return false;
} else {
echo TEXT_INFO_FTP_ERROR5 . $ftpservername . TEXT_INFO_FTP_ERROR4 . $ftpusername . "<br>\n";
}

if ( strlen( $ftpdirectory ) > 0 )
{
if (ftp_chdir($conn_id, $ftpdirectory )) {
echo TEXT_INFO_FTP_ERROR6 . ftp_pwd($conn_id) . "<br>\n";
} else {
echo TEXT_INFO_FTP_ERROR7 . $ftpservername . "<br>\n";
return false;
}
}

ftp_pasv ( $conn_id, true ) ;
// upload the file
$upload = ftp_put( $conn_id, $ftpdestinationfile, $ftpsourcefile, FTP_ASCII );
//echo $conn_id . $ftpdestinationfile . $ftpsourcefile ;
// check upload status
if (!$upload) {
echo $ftpservername . ' ' . $upload . TEXT_INFO_FTP_ERROR8 . "<br>\n";
return false;
} else {
echo sprintf(TEXT_INFO_FTP_ERROR9, $ftpsourcefile, $ftpservername, $ftpdestinationfile) . "<br>\n";
}

// close the FTP stream
ftp_close($conn_id);

return true;
}

ftp_file( $ftp_server, $ftp_user_name, $ftp_user_pass, $source_file, $destination_file);

//End FTP to google


//  End TIMER
//  ---------
$etimer = explode( ' ', microtime() );
$etimer = $etimer[1] + $etimer[0];
echo '<tr><td> <p style="margin:auto; text-align:center">';
echo printf( TEXT_INFO_FTP_SCRITP_TIMER , ($etimer-$stimer) ) ;
echo tep_draw_form('data', FILENAME_GOOGLE_ADMIN, '', 'post', '');

echo '</p></td><td>';


//  ---------


?>
<tr>
<td align="left" class="main"><br><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>
</form>
</td>
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
