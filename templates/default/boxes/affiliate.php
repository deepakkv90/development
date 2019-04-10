<?php
/*
  $Id: affililate.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- affiliate //-->
<tr>
  <td>
    <?php
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_AFFILIATE . '</font>');
    new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') ); 
    if (isset($_SESSION['affiliate_id'])) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => '<b>' . BOX_AFFILIATE_SUMMARY . '</b><br>' .
                                             '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_CENTRAL, '', 'SSL') . '">' . BOX_AFFILIATE_CENTRE . '</a><br>' .
                                             '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_ACCOUNT, '', 'SSL') . '">' . BOX_AFFILIATE_ACCOUNT . '</a><br>' .
                                             '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_PASSWORD, '', 'SSL') . '">' . BOX_AFFILIATE_PASSWORD . '</a><br>' .
                                             '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_NEWSLETTER, '', 'SSL') . '">' . BOX_AFFILIATE_NEWSLETTER . '</a><br>' .
                                             '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_NEWS, '', 'SSL') . '">' . BOX_AFFILIATE_NEWS . '</a><br>' .
                                             '<b>' . BOX_AFFILIATE_BANNERS . '</b><br>' .
                                             '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS, '', 'SSL') . '">' . BOX_AFFILIATE_BANNERS . '</a><br>' .
                                             '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_BANNERS, '', 'SSL') . '">' . BOX_AFFILIATE_BANNERS_BANNERS . '</a><br>' .
                                             '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD, '', 'SSL') . '">' . BOX_AFFILIATE_BANNERS_BUILD . '</a><br>' .
                                             '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD_CAT, '', 'SSL') . '">' . BOX_AFFILIATE_BANNERS_BUILD_CAT . '</a><br>' .
                                             '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_CATEGORY, '', 'SSL') . '">' . BOX_AFFILIATE_BANNERS_CATEGORY . '</a><br>' .
                                             '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_PRODUCT, '', 'SSL') . '">' . BOX_AFFILIATE_BANNERS_PRODUCT . '</a><br>' .
                                             '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_TEXT, '', 'SSL') . '">' . BOX_AFFILIATE_BANNERS_TEXT . '</a><br>' .
                                             '<b>' . BOX_AFFILIATE_REPORTS . '</b><br>' .
                                             '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_CLICKS, '', 'SSL'). '">' . BOX_AFFILIATE_CLICKRATE . '</a><br>' .
                                             '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_SALES, '', 'SSL'). '">' . BOX_AFFILIATE_SALES . '</a><br>' .
                                             '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, '', 'SSL'). '">' . BOX_AFFILIATE_PAYMENT . '</a><br>' .
                                             '<a href="' . tep_href_link(FILENAME_AFFILIATE_CONTACT, '', 'SSL') . '">' . BOX_AFFILIATE_CONTACT . '</a><br>' .
                                             '<a href="' . tep_href_link(FILENAME_AFFILIATE_INFO). '">' . BOX_AFFILIATE_INFO . '</a><br>' .
                                             '<a href="' . tep_href_link(FILENAME_AFFILIATE_FAQ, '', 'SSL') . '">' . BOX_AFFILIATE_FAQ . '</a><br>' .
                                             '<a href="' . tep_href_link(FILENAME_AFFILIATE_LOGOUT). '">' . BOX_AFFILIATE_LOGOUT . '</a>');
    } else {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => '<a href="' . tep_href_link(FILENAME_AFFILIATE_FAQ, '', 'NONSSL') . '">' . BOX_AFFILIATE_FAQ . '</a><br>' .
                                             '<a href="' . tep_href_link(FILENAME_AFFILIATE, '', 'SSL') . '">' . BOX_AFFILIATE_LOGIN . '</a>');
    }
    new $infobox_template($info_box_contents, true, true, ((isset($column_location) && $column_location !='') ? $column_location : '') );
      if (TEMPLATE_INCLUDE_FOOTER =='true'){
        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left',
                                     'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                    );
        new $infobox_template_footer($info_box_contents, ((isset($column_location) && $column_location !='') ? $column_location : '') );
      } 
    ?>
  </td>
</tr>
<!-- affiliate eof//-->