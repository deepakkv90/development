<?php
/*
  $Id: header.php,v 1.0 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- header //-->
<?php
if (DOWN_FOR_MAINTENANCE_HEADER_OFF == 'false') {
  if (SITE_WIDTH!='100%') {
    ?> 
    <table width="<?php echo SITE_WIDTH;?>" cellpadding="0" cellspacing="0" border="0" align="center">
      <tr>
        <td>
        <?php
  }
  ?>
  <!-- table with logo -->
  <table width="100%" border="0" align="center" style="vertical-align:top" cellpadding="0" cellspacing="0">
    <tr>
      <td align="left">
        <?php 
        if (cre_site_branding('storeurl') != '' || cre_site_branding('phone') != '' || cre_site_branding('email') != ''){ 
          ?>
          <table width="100%" border="0" cellpadding="0" cellspacing="0" class="top_bar">
            <tr>
              <td class="top_bar_td1" align="left" NOWRAP>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo cre_site_branding('storeurl'); ?></td>
              <td width="80%"></td>
              <td class="top_bar_td2" align="left" NOWRAP>&nbsp; <?php if(cre_site_branding('phone') !='') { echo tep_draw_separator('pixel_trans.gif', '1', '1') . tep_image(DIR_WS_TEMPLATE_IMAGES . 'icon_phone.gif') . ' &nbsp; ' . MENU_TEXT_CALLUS . ' &nbsp;' . cre_site_branding('phone');} ?>&nbsp; <?php if(cre_site_branding('email') != '' ){ echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'icon_email.png') . ' &nbsp; ' . MENU_TEXT_EMAILUS . ' &nbsp;' . cre_site_branding('email');} ?></td> 
            </tr>
          </table> 
          <?php 
        } 
        ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="top_nav">
          <tr>
            <td width="6"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'header_03.png');?></td>
            <td style="background:url(<?php echo DIR_WS_TEMPLATE_IMAGES;?>header_04.png);"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'header_04.png');?></td>
            <td width="6" align="right"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'header_06.png');?></td>
          </tr>
          <tr>
            <td style="background:url(<?php echo DIR_WS_TEMPLATE_IMAGES;?>header_11.png);"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'header_11.png');?></td>
            <td><!-- header content start -->
              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="branding">
                <tr>
                  <td><?php echo cre_site_branding('logo') . '<br>' . cre_site_branding('slogan'); ?></td>
                  <?php
                  if (isset($_SESSION['customer_id'])) { 
                    $login_link = '<a href="' . tep_href_link(FILENAME_LOGOFF, '', 'SSL') . '" class="headerNavigation">' . MENU_TEXT_LOGOUT . '</a>';
                  } else {
                    $login_link = '<a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '" class="headerNavigation">' . MENU_TEXT_LOGIN . '</a>'; 
                  } 
                  ?>                  
                  <td valign="bottom" class="brand_links"><a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>"><?php echo MENU_TEXT_CART . ': <b>' . $cart->count_contents() . '</b>' . MENU_TEXT_ITEMS; ?></a><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'top_link_arrow.png');?><a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>"><?php echo MENU_TEXT_CHECKOUT;?></a><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'top_link_arrow.png');?><a href="<?php echo tep_href_link(FILENAME_CONTACT_US, '', 'SSL');?>"><?php echo MENU_TEXT_CONTACT_US;?></a><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'top_link_arrow.png') . $login_link;?>&nbsp;&nbsp;&nbsp;</td>
                </tr>
              </table>
              <!-- header content eof -->
            </td>
            <td style="background:url(<?php echo DIR_WS_TEMPLATE_IMAGES;?>header_09.png);"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'header_09.png');?></td>
          </tr>
          <tr>
            <td><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'header_13.png');?></td>
            <td style="background:url(<?php echo DIR_WS_TEMPLATE_IMAGES;?>header_14.png);"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'header_14.png');?></td>
            <td align="right"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'header_15.png');?></td>
          </tr>
        </table>
        <!-- eof -->
        <!--- top logo header -->
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="info_bar">
          <tr>
            <td class="info_bar_td1" width="7"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'menu_left.png');?></td>
            <td class="info_bar_td2"><table width="440" border="0" cellspacing="0" cellpadding="0" class="menubarmain">
              <tr>
                <td>&nbsp;<a href="<?php echo tep_href_link(FILENAME_DEFAULT); ?>"><?php echo MENU_TEXT_HOME;?></a></td>
                <td><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'menu_div.png');?></td>
                <td><a href="<?php echo tep_href_link(FILENAME_FEATURED_PRODUCTS); ?>"><?php echo MENU_TEXT_FEATURED; ?></a></td>
                <td><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'menu_div.png');?></td>
                <td><a href="<?php echo tep_href_link(FILENAME_SPECIALS); ?>"><?php echo MENU_TEXT_SPECIALS;?></a></td>
                <td><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'menu_div.png');?></td>
                <td><a href="<?php echo tep_href_link(FILENAME_PRODUCTS_NEW); ?>"><?php echo MENU_TEXT_NEW_PRODUCTS; ?></a></td>
                <td><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'menu_div.png');?></td>
                <td><a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo MENU_TEXT_MEMBERS; ?></a></td>
                <td><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'menu_div.png');?></td>                
              </tr>
            </table></td>
            <td align="right" class="info_bar_td3" width="250"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td>
                  <?php 
                  echo tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get','style="margin:0;padding:0;"');
                  echo '<span class="info_bar_search">'. MENU_TEXT_SEARCH . ':</span>&nbsp;&nbsp;&nbsp;' .  tep_draw_input_field('keywords', '', 'class="search_input" size="25" maxlength="30" style="width: 120px"', 'text', false) . '&nbsp;' . tep_hide_session_id() . '  <input type="image" name="search" id="search" src="' . DIR_WS_TEMPLATE_IMAGES . 'button_ok.png">';
                  echo '</form>';
                  ?>
                </td>
              </tr>
            </table></td>
            <td class="info_bar_td1" width="5"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'menu_right.png');?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <?php
}
?>
<!-- header_eof //-->