<?php
/*
  $Id: cproxy.php, v 1.0.0.0 2008/01/28 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require_once('includes/application_top.php');

$action = (isset($_GET['action'])) ? $_GET['action'] : '';

if ($_GET['action'] == 'submit') {
  tep_db_query("DELETE FROM `configuration` WHERE configuration_key = 'CURL_PROXY_HOST'"); 
  tep_db_query("DELETE FROM `configuration` WHERE configuration_key = 'CURL_PROXY_PORT'");
  tep_db_query("INSERT INTO `configuration` (`configuration_key`, `configuration_value`) VALUES ('CURL_PROXY_HOST', 'http://proxy.shr.secureserver.net')"); 
  tep_db_query("INSERT INTO `configuration` (`configuration_key`, `configuration_value`) VALUES ('CURL_PROXY_PORT', '3128')");
  
  //tep_db_query("UPDATE `configuration` SET configuration_value = '64.202.165.130',  WHERE configuration_key = 'CURL_PROXY_HOST'"); 
  //tep_db_query("UPDATE `configuration` SET configuration_value = '3128',  WHERE configuration_key = 'CURL_PROXY_PORT'");
  tep_redirect('cproxy.php?action=done');
}
?>
<html <?php echo HTML_PARAMS; ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>cURL Proxy for GoDaddy</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body>
<br>
<form name="cproxy" action="<?php echo tep_href_link('cproxy.php', 'action=submit'); ?>" method="post">
<?php
  switch ($action) {
    case 'done':
      echo '<center>' . "\n"; 
      echo '  <table width="90%">' . "\n";
      echo '    <tr>' . "\n"; 
      echo '      <td align="center" colspan="2" style="padding-top:50px;" class="main">cURL Proxy settings have been updated for use on GoDaddy servers.</td>' . "\n";  
      echo '    </tr>' . "\n"; 
      echo '  </table>' . "\n";
      echo '    <tr>' . "\n"; 
      echo '      <td align="center" colspan="2"><br><br>host[' . CURL_PROXY_HOST . ']<br>port[' . CURL_PROXY_PORT . ']</td>' . "\n"; 
      echo '    </tr>' . "\n";         
      echo '</center>' . "\n";
      break;
    
    default:
      echo '<center>' . "\n"; 
      echo '  <table width="90%">' . "\n";
      echo '    <tr>' . "\n"; 
      echo '      <td align="center" style="padding-top:50px;" colspan="2" class="main">Press the Submit button to insert the cURL Proxy settings for GoDaddy servers.</td>' . "\n";  
      echo '    </tr>' . "\n"; 
      echo '    <tr>' . "\n"; 
      echo '      <td colspan="2" align="center"><input type="submit" name="submit" value="Submit"></td>' . "\n"; 
      echo '    </tr>' . "\n"; 
      echo '    <tr>' . "\n"; 
      echo '      <td colspan="2">&nbsp;</td>' . "\n"; 
      echo '    </tr>' . "\n";       
      echo '  </table>' . "\n";      
      echo '</center>' . "\n"; 
      break;
  }
?>
</body>
</html>      