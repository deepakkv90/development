<?php
/*
  $Id: osC_Catalog.tpl.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo TITLE ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_STYLE;?>">
<?php echo $page->importCSS(); ?>
</head>
<body onLoad="<?php echo $page->onLoad; ?>">
<table border="0" cellspacing="2" cellpadding="2" class="main" align="center" style="background: #ffffff;">
  <tr>
    <td><?php require($page->contentFile); ?></td>
  </tr>
  <tr><td><hr class="solid"></td></tr>
  <tr>
    <td class="buttontd"><form name="winClose"><input type="button" value="Close Window" onclick="window.close();return(false);" class="ppbuttonsmall"></form></td>
  </tr>
  <tr><td><br class="h10"></td></tr>
</table>
</body>
</html>
