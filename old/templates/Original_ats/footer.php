<?php
/*
  $Id: footer.php,v 1.1.1.1 2004/03/04 23:42:24 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/



// WebMakers.com Added: Down for Maintenance
// Hide footer.php if not to show
if (!defined('FILENAME_COUNTER')) {
  define("FILENAME_COUNTER","counter.php");
}

if (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_FOOTER_OFF =='false') {
  require(DIR_WS_INCLUDES . FILENAME_COUNTER);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="1">
  <tr class="footer">
    <td class="footer">&nbsp;&nbsp;<?php echo strftime(DATE_FORMAT_LONG); ?>&nbsp;&nbsp;</td>
    <td align="right" class="footer">&nbsp;&nbsp;<?php echo $counter_now . ' ' . FOOTER_TEXT_REQUESTS_SINCE . ' ' . $counter_startdate_formatted; ?>&nbsp;&nbsp;</td>
  </tr>
</table>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" class="smallText">
<?php

 //if (!(getenv('HTTPS')=='on')){
//google banner ad
if ($banner = tep_banner_exists('dynamic', 'googlefoot')) {
?>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><?php echo tep_display_banner('static', $banner); ?></td>
  </tr>
</table>
<?php
  }
 // }
?>

<?php
/*
  The following copyright announcement can only be
  appropriately modified or removed if the layout of
  the site theme has been modified to distinguish
  itself from the default osCommerce-copyrighted
  theme.

  For more information please read the following
  Frequently Asked Questions entry on the osCommerce
  support site:

  http://www.oscommerce.com/community.php/faq,26/q,50

  Please leave this comment intact together with the
  following copyright announcement.
*/

  echo FOOTER_TEXT_BODY
?>

    </td>
  </tr>
</table>
<?php
}

// BOF: WebMakers.com Added: Center Shop Bottom of the tables are in footer.php
if (SITE_WIDTH!='100%') {
?>
         </td></tr></table>
       </td></tr>
     </table>
   </td>
  </tr>
</table>
<?php
}
// EOF: WebMakers.com Added: Center Shop Bottom of the tables are in footer.php
?>
<!-- footer_eof //-->
