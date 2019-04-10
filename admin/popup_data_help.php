<?php
/*
  $Id: popup_data_help.php,v 1.1 2004/03/05 00:36:41 ccwjr Exp $


  Copyright (c) 2005 Chainreactionworks

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<style type="text/css"><!--
a { color:#080381; text-decoration:none; }
a:hover { color:#aabbdd; text-decoration:underline; }
a.text:link, a.text:visited { color: #000000; text-decoration: none; }
a:text:hover { color: #000000; text-decoration: underline; }



.smallText { font-family: Verdana, Arial, sans-serif; font-size: 10px; }
/* info box */
.DATAHeading { font-family: Verdana, Arial, sans-serif; font-size: 11px; color: #ffffff; background-color: #B3BAC5; }
.DATAContent { font-family: Verdana; font-size: 10pt; border: 1px outset #9B9B9B;
               padding-left: 4; padding-right: 4; padding-top: 1;
               padding-bottom: 1; background-color: #FFFFFF }
//--></style>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10" bgcolor="#DEE4E8">

<?php
  $heading = array();
  $contents = array();

    switch ($_GET['action']) {

      case 'google_category':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_HEADING_SET_CATEGORIES . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_SET_CATEGORIES_HELP);
      break;

      case 'google_configure':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_HEADING_CONFIGURE . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_CONFIGURE_HELP);
      break;

      case 'google_preprocess':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_HEADING_PRE_FEED . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_PRE_FEED_HELP);
      break;

      case 'google_send':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_HEADING_RUN . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_RUN_HELP);
      break;

      case 'position':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_DATA . '</b>');
      $contents[] = array('text'  => TEXT_DATA_HELP_POSITION);
      break;

      case 'active':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_DATA . '</b>');
      $contents[] = array('text'  => TEXT_DATA_HELP_ACTIVE);
      break;

 case 'FEED_LANG_USE':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_LANG_USE . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_LANG_USE_HELP);
      break;

 case 'google_feed_name':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_NAME . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_NAME_HELP);
      break;

 case 'GOOGLE_FEED_DISC':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_DISC . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_DISC_HELP);
      break;

 case 'GOOGLE_FILE_TYPE':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_FILE_TYPE . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_FILE_TYPE_HELP);
      break;

case 'GOOGLE_FILE_TYPE_PRODUCT':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_TYPE . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_TYPE_HELP);
      break;
case 'GOOGLE_FEED_SERVICE':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_FEED_SERVICE . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_FEED_SERVICE_HELP);
      break;

case 'GOOGLE_FEED_STATUS':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_STATUS . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_STATUS_HELP);
      break;
case 'GOOGLE_FEED_FILE':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_FILE_TYPE . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_FILE_TYPE_HELP);
      break;
case 'GOOGLE_FEED_FILE':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_FILE . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_FILE_HELP);
      break;
case 'GOOGLE_FEED_IMAGE':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_IMAGE . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_IMAGE_HELP);
      break;
case 'GOOGLE_FTP_SERVER':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_FTP_SERVER . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_FTP_SERVER_HELP);
      break;

case 'GOOGLE_FTP_USER':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_FTP_USER . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_FTP_USER_HELP);
      break;


case 'GOOGLE_FTP_PASSWORD':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_FTP_PASSWORD . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_FTP_PASSWORD_HELP);
      break;


case 'GOOGLE_FTP_DIRECTORY':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_FTP_DIRECTORY . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_FTP_DIRECTORY_HELP);
      break;


case 'GOOGLE_FEED_CUR':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_CUR . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_CUR_HELP);
      break;


case 'GOOGLE_CUR_USE':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_CUR_USE . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_CUR_USE_HELP);
      break;


case 'GOOGLE_FEED_LANG':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_LANG . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_LANG_HELP);
      break;


case 'GOOGLE_LANG_USE':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_LANG_USE . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_LANG_USE_HELP);
      break;


case 'GOOGLE_CUR_CON':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_CUR_CON . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_CUR_CON_HELP);
      break;


case 'GOOGLE_FEED_TAX':
      $heading[] = array('text' => '<b>' . TEXT_INFO_GOOGLE_FEED_TAX . '</b>');
      $contents[] = array('text'  => TEXT_GOOGLE_FEED_TAX_HELP);
      break;

    }
 $box = new box;
  echo $box->infoBox($heading, $contents);



?>

<p class="smallText" align="right"><?php echo '<a href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?></p>


</body>

</html>