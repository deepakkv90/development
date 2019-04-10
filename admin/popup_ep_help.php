<?php
/*
  $Id: popup_ep_help.php,v 1.1 2004/03/05 00:36:41 ccwjr Exp $

 
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

      case 'ep_file_upload':
      $heading[] = array('text' => '<b>' . TEXT_HEAD_HELP_EP_FILE_UPLOAD . '</b>');
      $contents[] = array('text'  => TEXT_HELP_EP_FILE_UPLOAD);
      break;

      case 'ep_file_upload_split':
      $heading[] = array('text' => '<b>' . TEXT_HEAD_HELP_EP_FILE_UPLOAD_SPLIT . '</b>');
      $contents[] = array('text'  => TEXT_HELP_EP_FILE_UPLOAD_SPLIT);
      break;
      
      case 'ep_file_split':
      $heading[] = array('text' => '<b>' . TEXT_HEAD_HELP_EP_FILE_SPLIT . '</b>');
      $contents[] = array('text'  => TEXT_HELP_EP_FILE_SPLIT);
      break;

      case 'ep_file_insert':
      $heading[] = array('text' => '<b>' . TEXT_HEAD_HELP_EP_FILE_INSERT . '</b>');
      $contents[] = array('text'  => TEXT_HELP_EP_FILE_INSERT);
      break;

      case 'ep_file_export':
      $heading[] = array('text' => '<b>' . TEXT_HEAD_HELP_EP_FILE_EXPORT . '</b>');
      $contents[] = array('text'  => TEXT_HELP_EP_FILE_EXPORT);
      break;

      case 'ep_select_method':
      $heading[] = array('text' => '<b>' . TEXT_HEAD_HELP_EP_SELECT_METHOD . '</b>');
      $contents[] = array('text'  => TEXT_HELP_EP_SELECT_METHOD);
      break;

      case 'ep_select_down':
      $heading[] = array('text' => '<b>' . TEXT_HEAD_HELP_EP_SELECT_DOWN . '</b>');
      $contents[] = array('text'  => TEXT_HELP_EP_SELECT_DOWN);
      break;

      case 'ep_select_sort':
      $heading[] = array('text' => '<b>' . TEXT_HEAD_HELP_EP_SELECT_SORT . '</b>');
      $contents[] = array('text'  => TEXT_HELP_EP_SELECT_SORT);
      break;

      case 'ep_limit_rows':
      $heading[] = array('text' => '<b>' . TEXT_HEAD_HELP_EP_LIMIT_ROWS . '</b>');
      $contents[] = array('text'  => TEXT_HELP_EP_LIMIT_ROWS);
      break;

      case 'ep_limit_cats':
      $heading[] = array('text' => '<b>' . TEXT_HEAD_HELP_EP_LIMIT_CATS . '</b>');
      $contents[] = array('text'  => TEXT_HELP_EP_LIMIT_CATS);
      break;

      case 'ep_limit_man':
      $heading[] = array('text' => '<b>' . TEXT_HEAD_HELP_EP_LIMIT_MAN . '</b>');
      $contents[] = array('text'  => TEXT_HELP_EP_LIMIT_MAN);
      break;

      case 'ep_limit_product':
      $heading[] = array('text' => '<b>' . TEXT_HEAD_HELP_EP_LIMIT_PRODUCT . '</b>');
      $contents[] = array('text'  => TEXT_HELP_EP_LIMIT_PRODUCT);
      $contents[] = array('text'  => TEXT_HELP_EP_LIMIT_PRODUCT1);
      $contents[] = array('text'  => TEXT_HELP_EP_LIMIT_PRODUCT2);
      $contents[] = array('text'  => TEXT_HELP_EP_LIMIT_PRODUCT3);
      $contents[] = array('text'  => TEXT_HELP_EP_LIMIT_PRODUCT4);
      break;

    }
 $box = new box;
  echo $box->infoBox($heading, $contents);



?>

<p class="smallText" align="right"><?php echo '<a href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?></p>


</body>

</html>