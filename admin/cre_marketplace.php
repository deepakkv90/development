<?php
/*
  $Id: cre_marketplace.php,v 1.0 2007/09/20 23:38:56 datazen Exp $

  CRE Loaded, Commercial Open Source E-Commerce
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CRE_MARKETPLACE);
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
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); 
function cre_iframe($n,$zone) {
if($n!='' && $zone !='') {
$iframe_content .= <<<EOF
<script language='JavaScript' type='text/javascript' src='https://adserver.authsecure.com/adx.js'></script>
<script language='JavaScript' type='text/javascript'>
<!--
   if (!document.phpAds_used) document.phpAds_used = ',';
   phpAds_random = new String (Math.random()); phpAds_random = phpAds_random.substring(2,11);
   
   document.write ("<" + "script language='JavaScript' type='text/javascript' src='");
   document.write ("https://adserver.authsecure.com/adjs.php?n=" + phpAds_random);
   document.write ("&amp;what=zone:$zone");
   document.write ("&amp;exclude=" + document.phpAds_used);
   if (document.referrer)
      document.write ("&amp;referer=" + escape(document.referrer));
   document.write ("'><" + "/script>");
//-->
</script><noscript><a href='https://adserver.authsecure.com/adclick.php?n=$n' target='_blank'><img src='https://adserver.authsecure.com/adview.php?what=zone:$zone&amp;n=$n' border='0' alt=''></a></noscript>
EOF;
} else { 
$iframe_content = '';
}
return $iframe_content;
}
?>
<!-- header_eof //-->
<!-- body //-->
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
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td class="main" valign="top"><table width="100%" border="0" cellspacing="3" cellpadding="3">
              <tr>
                <td class="main"><!--Banner one Start--><?php echo cre_iframe('a8c53aea','52');?><!--Banner one End--></td>
              </tr>
              <tr>
                <td class="main"><!--Banner two Start--><?php echo cre_iframe('ab67cba4','53');?><!--Banner two End--></td>
              </tr>
              <tr>
                <td class="main"><!--Banner three Start--><?php echo cre_iframe('a601751d','54');?><!--Banner three End--></td>
              </tr>
              <tr>
                <td class="main"><!--Banner four Start--><?php echo cre_iframe('a601751d','55');?><!--Banner four End--></td>
              </tr>
              <tr>
                <td class="main"><!--Banner five Start--><?php echo cre_iframe('a4186fe8','56');?><!--Banner five End--></td>
              </tr>
              <tr>
                <td class="main"><!--Banner six Start--><?php echo cre_iframe('a777c26a','57');?><!--Banner six End--></td>
              </tr>
              <tr>
                <td class="main"><!--Banner sevem Start--><?php echo cre_iframe('ad185eea','58');?><!--Banner seven End--></td>
              </tr>
              <tr>
                <td class="main"><!--Banner eight Start--><?php echo cre_iframe('a3d53437','59');?><!--Banner eight End--></td>
              </tr>              
              <tr>
                <td class="main"><!--Banner nine Start--><?php echo cre_iframe('aa0357d5','60');?><!--Banner nine End--></td>
              </tr>
              <tr>
                <td class="main"><!--Banner ten Start--><?php echo cre_iframe('a18b055c','61');?><!--Banner ten End--></td>
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