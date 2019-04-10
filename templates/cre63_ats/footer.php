<?php
/*
  $Id: footer.php,v 1.0 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI top
echo $cre_RCI->get('footer', 'top');
if (DOWN_FOR_MAINTENANCE_FOOTER_OFF =='false') {
  require(DIR_WS_INCLUDES . 'counter.php');
  ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="footer">   
    <tr>
      <td align="center" colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
    <tr>
      <td class="footer_left" width="197" valign="top"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'cards.gif');?></td>
      <td class="footer_center">
        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="2" class="footer_menu">
          <tr>
            <td align="center" valign="middle">
              <a href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'SSL'); ?>"><?php echo MENU_TEXT_HOME; ?></a> |
              <?php
              if (isset($_SESSION['customer_id'])) { 
                ?> 
                <a href="<?php echo tep_href_link(FILENAME_LOGOUT, '', 'SSL'); ?>"><?php echo MENU_TEXT_LOGOUT; ?></a> |  
                <?php
              } else {
                ?>
                <a href="<?php echo tep_href_link(FILENAME_LOGIN, '', 'SSL'); ?>"><?php echo MENU_TEXT_LOGIN; ?></a> | 
                <?php
              }
              ?>
              <a href="<?php echo tep_href_link(FILENAME_SPECIALS, '', 'SSL'); ?>"><?php echo MENU_TEXT_SPECIALS; ?></a> |  
              <a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'); ?>"><?php echo MENU_TEXT_CART; ?></a> | 
              <a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>"><?php echo MENU_TEXT_CHECKOUT; ?></a> | 
              <a href="<?php echo tep_href_link(FILENAME_CONTACT_US, '', 'SSL');?>"><?php echo MENU_TEXT_CONTACTUS; ?></a> |
              <a href="<?php echo tep_href_link(FILENAME_PAGES, 'pID=1', 'SSL');?>"><?php echo MENU_TEXT_TERMS; ?></a> | 
              <a href="<?php echo tep_href_link(FILENAME_PAGES, 'pID=2', 'SSL');?>"><?php echo MENU_TEXT_PRIVACY; ?></a> | 
              <a href="<?php echo tep_href_link(FILENAME_PAGES, 'pID=3', 'SSL');?>"><?php echo MENU_TEXT_SHIPPING; ?></a>  
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" class="footer_copyright" colspan="2">
        <br>
        <?php echo FOOTER_TEXT1_BODY . '<br>This template is designed and contributed by <a href="http://www.algozone.com/shop/?ref=2" target="_blank" title="CRE Loaded Templates">AlgoZone.com</a>' . FOOTER_TEXT2_BODY; ?>
      </td>
    </tr>
  </table>
  <?php
}
//if (!(getenv('HTTPS') == 'on')){
  if ($banner = tep_banner_exists('dynamic', 'googlefoot')) {
    ?>
    <br>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="footer_banner">
      <tr>
        <td align="center"><?php echo tep_display_banner('static', $banner); ?></td>
      </tr>
    </table>
    <?php
  }
//}
if (SITE_WIDTH!='100%') {
  ?>
      </td>
    </tr>
  </table>
  <?php
}
// RCI bottom
echo $cre_RCI->get('footer', 'bottom');
?>