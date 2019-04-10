<?php
/*
  $Id: cpurge.php, v 1.0.0.0 2008/01/28 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require_once('includes/application_top.php');

$action = (isset($_GET['action'])) ? $_GET['action'] : '';

if ($_GET['action'] == 'submit') {
  mysql_query("TRUNCATE `components`"); 
  tep_redirect('cpurge.php?action=done');
}
?>
<html <?php echo HTML_PARAMS; ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>SSS Purge Data</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body>
<br>
<form name="purge" action="<?php echo tep_href_link('cpurge.php', 'action=submit'); ?>" method="post">
<?php
  switch ($action) {
    case 'done':
      echo '<center>' . "\n"; 
      echo '  <table width="90%">' . "\n";
      echo '    <tr>' . "\n"; 
      echo '      <td align="center" colspan="2" class="main">bye bye components data ...</td>' . "\n";  
      echo '    </tr>' . "\n"; 
      echo '  </table>' . "\n";
      echo '    <tr>' . "\n"; 
      echo '      <td align="right" colspan="2"><a href="javascript:window.close();"><u>Close Window</u></a></td>' . "\n"; 
      echo '    </tr>' . "\n";             
      echo '</center>' . "\n";
      break;
    
    default:
      echo '<center>' . "\n"; 
      echo '  <table width="90%">' . "\n";
      echo '    <tr>' . "\n"; 
      echo '      <td align="center" colspan="2" class="main">Press the Empty Components Data button to clear components table data.</td>' . "\n";  
      echo '    </tr>' . "\n"; 
      echo '    <tr>' . "\n"; 
      echo '      <td colspan="2" align="center"><input type="submit" name="submit" value="Submit"></td>' . "\n"; 
      echo '    </tr>' . "\n"; 
      echo '    <tr>' . "\n"; 
      echo '      <td colspan="2">&nbsp;</td>' . "\n"; 
      echo '    </tr>' . "\n"; 
      echo '    <tr>' . "\n"; 
      echo '      <td align="right" colspan="2"><a href="javascript:window.close();"><u>Close Window</u></a></td>' . "\n"; 
      echo '    </tr>' . "\n";        
      echo '  </table>' . "\n";      
      echo '</center>' . "\n"; 
      break;
  }
?>
</body>
</html>      