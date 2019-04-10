<?php
/*
  $Id: results.inc.php,v 2.6a 2004/07/14 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/
?>
<?php
define("PAYPAL_SHOPPING_CART_IPN_1","PayPal_Shopping_Cart_IPN");
define("IPN_TEST_RESULTS","IPN Test Results");
define("TEST_COMPLETE","Test Complete!");
define("TEST_NOT_VALID","Test Not Valid!");
define("FOOTER_TEXT_1",'E-Commerce Engine Copyright &copy; 2003 <a href="http://www.oscommerce.com" target="_blank">osCommerce</a><br>osCommerce provides no warranty and is redistributable under the <a href="http://www.fsf.org/licenses/gpl.txt" target="_blank">GNU General Public License</a>');

define("FOOTER_TEXT_2",'Powered by <a href="http://www.oscommerce.com" target="_blank">osCommerce</a>');

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
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">
<form name="ipn" method="GET" action="<?php echo  $_SERVER['HTTP_REFERER']?>">
  <input type="hidden" name="action" value="test"/>
  <table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2">
    <tr valign="middle">
      <td align="center"><table border="0" width="780" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="0" class="box">
                <tr>
                  <td align="center"><a href="http://www.creloaded.com" target="_blank"><img border="0" src="<?php echo DIR_WS_MODULES . 'payment/paypal/images/loaded_header_logo.gif'; ?>" alt="CRE Loaded"  title=" osCommerce " /></a>
                    <h1 class="p"> <?php echo PROJECT_VERSION; ?></h1></td>
                </tr>
                <tr>
                  <td class="pageHeading" style="color:green" align="center"><!-- PayPal_Shopping_Cart_IPN -->
                    <?php echo PAYPAL_SHOPPING_CART_IPN_1;?></td>
                </tr>
                <tr>
                  <td class="pageHeading" style="color:blue; text-align:center; padding-top:5px;"><!-- IPN Test Results -->
                    <?php echo IPN_TEST_RESULTS;?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '25'); ?></td>
          </tr>
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="0" class="box">
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
                </tr>
                <?php if(!$debug->error) { ?>
                <tr>
                  <td class="pageHeading" style="color:red" align="center"><!-- Test Complete! -->
                    <?php echo TEST_COMPLETE;?></td>
                </tr>
                <?php } else { ?>
                <tr>
                  <td class="pageHeading" style="color:red" align="center"><!-- Test Not Valid! -->
                    <?php echo TEST_NOT_VALID;?></td>
                </tr>
                <?php } ?>
                <?php if($debug->enabled) { ?>
                <tr>
                  <td style="padding:5px;" align="left"><?php echo $debug->info(true); ?></td>
                </tr>
                <?php } ?>
                <tr>
                  <td style="color:blue; text-align:center; padding-top:5px;"><input type="submit" value="Continue"></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td><br>
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                  <td align="center" class="smallText"><?php echo FOOTER_TEXT_1;?> </td>
                </tr>
                <tr>
                  <td><?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5'); ?></td>
                </tr>
                <tr>
                  <td align="center" class="smallText"><?php echo FOOTER_TEXT_2;?> </td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
  </table>
</form>
</body>
</html>