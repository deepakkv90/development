<?php
/*
  $Id: cds_popup.php,v 1.0.0.0 2007/02/27 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
$is_62 = (INSTALLED_VERSION_MAJOR == 6 && INSTALLED_VERSION_MINOR == 2) ? true : false;
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CDS_POPUP);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php
if ($is_62) {
    echo '<script language="javascript" src="includes/menu.js"></script>' . "\n";
} else {
    echo '<script type="text/javascript" src="includes/prototype.js"></script>' . "\n";
    echo '<!--[if IE]>' . "\n";
    echo '<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">' . "\n";
    echo '<![endif]-->' . "\n";
}
?>
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10" bgcolor="#DEE4E8">
<?php
$constant = isset($_GET['lang']) ? $_GET['lang'] : ''; 
$pID = isset($_GET['pID']) ? (int)$_GET['pID'] : 0;
$language_id = isset($_GET['language_id']) ? (int)$_GET['language_id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';
?>
<table border="0" width="100%" cellpadding="2"  cellspacing="2">
  <?php  
  if ($action == 'view')  { 
    ?>
    <tr>
      <td class="main"><a href="<?php echo tep_href_link(FILENAME_CDS_POPUP,'action=source&language_id=' . $language_id . '&pID=' . $pID); ?>"><b><?php echo TEXT_CDS_CLICK_TO_PAGE_SOURCE; ?></b></a></td>
    </tr>
    <?php
  }
  if ($action == 'source')  { 
    ?>
    <tr>
      <td class="main"><a href="<?php echo tep_href_link(FILENAME_CDS_POPUP,'action=view&language_id=' . $language_id . '&pID=' . $pID); ?>"><b><?php echo TEXT_CDS_VIEW_OUTPUT; ?></b></a></td>
     </tr>
     <?php
  }
  $sql="SELECT * 
             from  " . TABLE_CDS_PAGES_DESCRIPTION . " 
           WHERE pages_id='" . $pID."' 
             and language_id = " . $language_id ;

  $sql_res = tep_db_query($sql);
  $result = tep_db_fetch_array($sql_res);
  $file = $result['pages_file'];

  $sql_language = "SELECT * 
                             from " . TABLE_LANGUAGES . " 
                           WHERE languages_id = " . $language_id;

  $sql_lang_res = tep_db_query($sql_language);
  $result_languages = tep_db_fetch_array($sql_lang_res);
  $dir = $result_languages['directory'];

  if ($action == 'view')  {
    echo '<tr><td>';
    $fullFilename = ('../' . DIR_WS_LANGUAGES . $dir . '/pages/' . $file);
    include_once($fullFilename);
    $var = file_get_contents('../' . DIR_WS_LANGUAGES . $dir . '/pages/' . $file);
    if ($var == '') {
      echo '<strong><p class="text" align="center"> File Is Empty </p></strong>';
    }
    echo '</tr></td></table>'; 
  } else if ($action == 'source')   {
    $fullFilename =('../'.DIR_WS_LANGUAGES.$dir.'/pages/'.$file);  
    $path_parts = pathinfo("$fullFilename");
    if (is_file($fullFilename) && is_readable($fullFilename) && (($path_parts["extension"] == "php") || ($path_parts["extension"] == "css"))) {
      echo '<tr><td>';
      show_source($fullFilename);
      echo '</tr></td> </table>'; 
    } 
  }
?>
<table width="100%" cellpadding="2" cellspacing="2" border="0">
  <tr>
    <td class="main" align="right"><?php echo '<a href="javascript:window.close()"><b>' . TEXT_CDS_CLOSE_WINDOW . '</b></a>'; ?></td>
  </tr>
</table>
</body>
</html>