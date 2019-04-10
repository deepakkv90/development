<?php
/*
  $Id: popup_infobox_help.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

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
.infoBoxHeading { font-family: Verdana, Arial, sans-serif; font-size: 11px; color: #ffffff; background-color: #B3BAC5; }
.infoBoxContent { font-family: Verdana; font-size: 10pt; border: 1px outset #9B9B9B;
               padding-left: 4; padding-right: 4; padding-top: 1;
               padding-bottom: 1; background-color: #FFFFFF }
//--></style>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10" bgcolor="#DEE4E8">

<?php
  $heading = array();
  $contents = array();

    switch ($_GET['action']) {

      case 'filename':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_INFOBOX . '</b>');
      $contents[] = array('text'  => sprintf(TEXT_INFOBOX_HELP_FILENAME, tep_db_prepare_input($_GET['templatename'])));
      break;

      case 'heading':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_INFOBOX . '</b>');
      $contents[] = array('text'  => TEXT_INFOBOX_HELP_HEADING);
      break;

      case 'define':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_INFOBOX . '</b>');
      $contents[] = array('text'  => TEXT_INFOBOX_HELP_DEFINE);
      break;

      case 'column':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_INFOBOX . '</b>');
      $contents[] = array('text'  => TEXT_INFOBOX_HELP_COLUMN);
      break;

      case 'position':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_INFOBOX . '</b>');
      $contents[] = array('text'  => TEXT_INFOBOX_HELP_POSITION);
      break;

      case 'active':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_INFOBOX . '</b>');
      $contents[] = array('text'  => TEXT_INFOBOX_HELP_ACTIVE);
      break;

      case 'color':
          $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_INFOBOX . '</b>');
          $contents[] = array('text'  => TEXT_INFOBOX_HELP_COLOR);
          break;


      case 'template':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_INFOBOX . '</b>');
        $contents[] = array('text'  => TEXT_INFOBOX_HELP_TEMPLATE);
      break;

    }
 $box = new box;
  echo $box->infoBox($heading, $contents);



?>

<p class="smallText" align="right"><?php echo '<a href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?></p>


</body>

</html>
