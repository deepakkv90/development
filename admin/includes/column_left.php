<?php
/*
  $Id: column_left.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

if (MENU_DHTML == 'False') {
  ?>
  <td valign="top" class="menu-container" width="200"><table border="0" cellspacing="0" cellpadding="0" width="100%" class="columnLeft menu-table">
    <?php
    if (tep_admin_check_boxes('administrator.php') == true) {
      require(DIR_WS_BOXES . 'administrator.php');
    }
    if (tep_admin_check_boxes('configuration.php') == true) {
      require(DIR_WS_BOXES . 'configuration.php');
    }
    if (tep_admin_check_boxes('catalog.php') == true) {
      require(DIR_WS_BOXES . 'catalog.php');
    }
    if (tep_admin_check_boxes('customers.php') == true) {
      require(DIR_WS_BOXES . 'customers.php');
    }
    if (tep_admin_check_boxes('marketing.php') == true) {
      require(DIR_WS_BOXES . 'marketing.php');
    }
    if (tep_admin_check_boxes('gv_admin.php') == true) {
      require(DIR_WS_BOXES . 'gv_admin.php');
    }
    if (tep_admin_check_boxes('affiliate.php') == true) {
      require(DIR_WS_BOXES . 'affiliate.php');
    }
    if (tep_admin_check_boxes('reports.php') == true) {
      require(DIR_WS_BOXES . 'reports.php');
    }
    if (tep_admin_check_boxes('data.php') == true) {
      require(DIR_WS_BOXES . 'data.php');
    }
    if (tep_admin_check_boxes('information.php') == true) {
      require(DIR_WS_BOXES . 'information.php');
    }
    if (tep_admin_check_boxes('articles.php') == true) {
      require(DIR_WS_BOXES . 'articles.php');
    }
    if (tep_admin_check_boxes('design_controls.php') == true) {
      require(DIR_WS_BOXES . 'design_controls.php');
    }
    // RCI include RCI menus
    echo $cre_RCI->get('boxes', 'menu');
    if (tep_admin_check_boxes('links.php') == true) {
      require(DIR_WS_BOXES . 'links.php');
    }
    if (tep_admin_check_boxes('modules.php') == true) {
      require(DIR_WS_BOXES . 'modules.php');
    }
    if (tep_admin_check_boxes('taxes.php') == true) {
      require(DIR_WS_BOXES . 'taxes.php');
    }
    if (tep_admin_check_boxes('localization.php') == true) {
      require(DIR_WS_BOXES . 'localization.php');
    }
    if (tep_admin_check_boxes('tools.php') == true) {
      require(DIR_WS_BOXES . 'tools.php');
    }
    if (file_exists('includes/boxes/newsdesk.php')) {
       if (tep_admin_check_boxes('newsdesk.php') == true) {
         require(DIR_WS_BOXES . 'newsdesk.php');
       }
    }
    if (file_exists('includes/boxes/faqdesk.php')) {
      if (tep_admin_check_boxes('faqdesk.php') == true) {
        require(DIR_WS_BOXES . 'faqdesk.php');
      }
    }
    if (file_exists('includes/boxes/b2bsettings.php')) {
      if (tep_admin_check_boxes('b2bsettings.php') == true) {
        require(DIR_WS_BOXES . 'b2bsettings.php');
      }
    }
    if (file_exists('includes/boxes/vendors.php') && (defined('MVS_STATUS') && MVS_STATUS == 'true')) {
      if (tep_admin_check_boxes('vendors.php') == true) {
        require(DIR_WS_BOXES . 'vendors.php');
      }
    } 
	?>
  </table></td>
  <?php
}
?>