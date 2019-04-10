<?php
/*
  $Id: affiliate.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- affiliate //-->
          <tr>
            <td>
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_AFFILIATE,
                   'link'  => tep_href_link(FILENAME_AFFILIATE_SUMMARY, 'selected_box=affiliate'));
if ($_SESSION['selected_box'] == 'affiliate' || MENU_DHTML == 'True') {
  //RCI to include links 
  $returned_rci_top = $cre_RCI->get('affiliate', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('affiliate', 'boxesbottom');
  $contents[] = array('text'  => $returned_rci_top .
                                 tep_admin_files_boxes(FILENAME_AFFILIATE_SUMMARY,BOX_AFFILIATE_SUMMARY, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_AFFILIATE,BOX_AFFILIATE, 'SSL','','2')  .
                                 tep_admin_files_boxes(FILENAME_AFFILIATE_PAYMENT,BOX_AFFILIATE_PAYMENT, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_AFFILIATE_SALES,BOX_AFFILIATE_SALES, 'SSL','','2')  .
                                 tep_admin_files_boxes(FILENAME_AFFILIATE_CLICKS,BOX_AFFILIATE_CLICKS, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_AFFILIATE_BANNER_MANAGER,BOX_AFFILIATE_BANNERS, 'SSL','','2')  .
                                 tep_admin_files_boxes(FILENAME_AFFILIATE_NEWS,BOX_AFFILIATE_NEWS, 'SSL','','2')  .
                                 tep_admin_files_boxes(FILENAME_NEWSLETTERS,BOX_AFFILIATE_NEWSLETTER_MANAGER, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_AFFILIATE_CONTACT,BOX_AFFILIATE_CONTACT, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_CONFIGURATION, BOX_CONFIGURATION_AFFILIATE, 'SSL','gID=900','2') .
                                 $returned_rci_bottom);
  }
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- affiliate_eof //-->