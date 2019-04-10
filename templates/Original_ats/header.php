<?php
/*
  $Id: header.php,v 1.0 2008/06/23 00:18:17 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- header //-->
<?php
// Down for Maintenance - Hide header if not to show
if (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_HEADER_OFF =='false') {
  if (SITE_WIDTH!='100%') {
    ?>
    <table width="100%" cellpadding="10" cellspacing="0" border="0">
      <tr><td>
        <table BORDERCOLORLIGHT="2F4F2F" CELLSPACING="2" CELLPADDING="4" BORDER="2" width="<?php echo SITE_WIDTH;?>" align="center" BGCOLOR="FFFFFF">
          <tr><td BORDERCOLOR="FF0000">
            <table border="0" width="100%"><tr><td>
    <?php
  }
  ?>
  <table border="0" width="100%" cellspacing="2" cellpadding="2">
    <tr class="header">
      <td valign="middle"><?php echo cre_site_branding('logo') . '<br>' . cre_site_branding('slogan'); ?></td>
      <td align="right" valign="bottom"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_account.gif', HEADER_TITLE_MY_ACCOUNT) . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '">' . tep_image(DIR_WS_IMAGES . 'header_cart.gif', HEADER_TITLE_CART_CONTENTS) . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_checkout.gif', HEADER_TITLE_CHECKOUT) . '</a>'; ?>&nbsp;&nbsp;</td>
    </tr>
  </table>
  <table border="0" width="100%" cellspacing="0" cellpadding="1">
    <tr class="headerNavigation">
      <td class="headerNavigation">&nbsp;&nbsp;<?php echo $breadcrumb->trail(' &raquo; '); ?></td>
      <td align="right" class="headerNavigation">
        <?php
        if ( ! isset($_SESSION['noaccount']) ){// DDB - PWA - 040622 - no display of logoff for PWA customers
          if ( ! isset($_SESSION['customer_id']) ) {
            echo ' <a class="headerNavigation" href="' . tep_href_link(FILENAME_LOGIN, "", "SSL") . '">' . HEADER_TITLE_LOGIN . '</a>&nbsp;|&nbsp;';
          } else {
            echo ' <a class="headerNavigation" href="' . tep_href_link(FILENAME_ACCOUNT, "", "SSL") . '">' . HEADER_TITLE_MY_ACCOUNT . ' </a> &nbsp;|&nbsp;';
            echo ' <a class="headerNavigation" href="' . tep_href_link(FILENAME_LOGOFF, "", "SSL") . '">' . HEADER_TITLE_LOGOFF . ' </a> &nbsp;|&nbsp;';
          }
        }
        if ( isset($_SESSION['noaccount']) ) {
          if ( ! isset($_SESSION['customer_id']) ) {
            echo ' <a class="headerNavigation" href="' . tep_href_link(FILENAME_LOGIN, "", "SSL") . '">' . HEADER_TITLE_LOGIN . ' </a> &nbsp;|&nbsp;';
          } else {
            echo ' <a class="headerNavigation" href="' . tep_href_link(FILENAME_LOGOFF, "", "SSL") . '">' . HEADER_TITLE_LOGOFF . ' </a> &nbsp;|&nbsp;';
          }
        }
        ?>
        <a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>" class="headerNavigation"><?php echo HEADER_TITLE_CART_CONTENTS; ?></a>
        &nbsp;|&nbsp; <a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>" class="headerNavigation"><?php echo HEADER_TITLE_CHECKOUT; ?>&nbsp;|&nbsp;</a>
      </td>
    </tr>
  </table>
  <?php
}
?>
<!-- header_eof //-->