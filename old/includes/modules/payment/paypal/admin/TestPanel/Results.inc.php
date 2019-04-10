<?php
/*
  $Id: Results.inc.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/
?>
<form name="ipn" method="GET" action="<?php echo  $_SERVER['HTTP_REFERER']?>">
<input type="hidden" name="action" value="itp"/>
<table border="0" cellspacing="0" cellpadding="0" class="main">
<?php if(!$debug->error) { ?>
  <tr>
    <td>
      <table border="0" cellspacing="0" cellpadding="0" style="padding: 4px; border:1px solid #aaaaaa; background: #ffffcc;">
        <tr>
          <td><br class="text_spacer"></td>
          <td class="pperrorbold" style="text-align: center; width:100%;"><?php echo TEST_COMPLETE; ?></td>
        </tr>
      </table>
    </td>
  </tr>
<?php if($debug->enabled) { ?>
  <tr>
    <td style="pptext"><?php echo $debug->info(true); ?></td>
  </tr>
<?php   } ?>
<?php } else { ?>
  <tr>
    <td>
      <table border="0" cellspacing="0" cellpadding="0" style="padding: 4px; border:1px solid #aaaaaa; background: #ffffcc;">
        <tr>
          <td><?php echo $page->image('icon_error_40x40.gif',IMAGE_ERROR); ?></td>
          <td><br class="text_spacer"></td>
          <td class="pperrorbold" style="text-align: center; width:100%;"><?php echo TEST_INCOMPLETE; ?></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr><td><br class="h10"/></td></tr>
  <tr>
    <td class="ppsmalltext"><?php echo TEST_INCOMPLETE_MSG; ?></td>
  </tr>
  <tr><td><br class="h10"/></td></tr>
<?php } ?>
  <tr><td><hr class="solid"/></td></tr>
  <tr><td class="buttontd"><input class="ppbuttonsmall" type="submit" name="submit" value="Continue"></td></tr>
</table>
</form>
