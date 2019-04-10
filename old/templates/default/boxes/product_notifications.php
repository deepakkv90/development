<?php
/*
  $Id: product_notifications.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (isset($_POST['products_id']) && tep_not_null($_POST['products_id'])) {
  $products_id = tep_db_prepare_input($_POST['products_id']);
} elseif (isset($_GET['products_id']) && tep_not_null($_GET['products_id'])) {
  $products_id = tep_db_prepare_input($_GET['products_id']);
} 
if ( (isset($_GET['products_id'])) || (isset($_POST['products_id'])) ) {
  ?>
  <!-- notifications //-->
  <tr>
    <td>
      <?php
      $info_box_contents = array();
      $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_NOTIFICATIONS . '</font>');
      new $infobox_template_heading($info_box_contents, tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'), ((isset($column_location) && $column_location !='') ? $column_location : '') );
      if ( isset($_SESSION['customer_id']) ) {
        $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$_GET['products_id'] . "' and customers_id = '" . (int)$_SESSION['customer_id'] . "'");
        $check = tep_db_fetch_array($check_query);
        $notification_exists = (($check['count'] > 0) ? true : false);
      } else {
        $notification_exists = false;
      }
      $info_box_contents = array();
      if ($notification_exists == true) {
        $info_box_contents[] = array('text' => '<table border="0" cellspacing="0" cellpadding="2"><tr><td class="infoBoxContents"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify_remove', $request_type) . '">' . tep_image(DIR_WS_IMAGES . 'box_products_notifications_remove.gif', IMAGE_BUTTON_REMOVE_NOTIFICATIONS) . '</a></td><td class="infoBoxContents"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify_remove', $request_type) . '">' . sprintf(BOX_NOTIFICATIONS_NOTIFY_REMOVE, tep_get_products_name($_GET['products_id'])) .'</a></td></tr></table>');
      } else {
        $info_box_contents[] = array('text' => '<table border="0" cellspacing="0" cellpadding="2"><tr><td class="infoBoxContents"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify', $request_type) . '">' . tep_image(DIR_WS_IMAGES . 'box_products_notifications.gif', IMAGE_BUTTON_NOTIFICATIONS) . '</a></td><td class="infoBoxContents"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify', $request_type) . '">' . sprintf(BOX_NOTIFICATIONS_NOTIFY, tep_get_products_name($_GET['products_id'])) .'</a></td></tr></table>');
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
  <!-- notifications eof//-->
  <?php
}
?>