<?php
/*
  $Id: Help.inc.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/
?>
<table class="popupmain" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #ffffff url(<?php echo $page->baseURL.'images/hdr_ppGlobev4_160x76.gif'?>) no-repeat top right;">
  <tr>
    <tr><td><br class="h10"></td></tr>
  </tr>
  <tr>
    <td class="pptext" valign="top">
    <ul style="margin: 10px 0px 0px 20px; padding: 0px 0px 0px 5px; list-style-image: url(<?php echo $page->baseURL.'images/contents.gif'; ?>);">
      <li>&nbsp;<b><a href="<?php echo tep_href_link(FILENAME_PAYPAL,'action=help-cfg','NONSSL'); ?>">Configuration Guide</a></b></li><br><br class="h6" />
      <li>&nbsp;<b><a href="<?php echo tep_href_link(FILENAME_PAYPAL,'action=help-faqs','NONSSL'); ?>">Frequently Asked Questions</a></b></li><br><br class="h6" />
    </ul>
    </td>
  </tr>
  <tr>
    <tr><td><br class="h10"></td></tr>
  </tr>
</table>
