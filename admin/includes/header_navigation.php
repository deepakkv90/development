<?php
/*
  $Id: header_navigation.php,v 1.1.1.1 2004/03/04 23:39:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

    Chain Reaction Works, Inc
  Copyright &copy; 2005 - 2006 Chain Reaction Works, Inc.

  Last Modified by $Author$
  Last Modifed on : $Date$
  Latest Revision : $Revision: 4210 $

  Released under the GNU General Public License
*/

if (MENU_DHTML == True) {
  $box_files_list1a = array(array('administrator', 'administrator.php', BOX_HEADING_ADMINISTRATOR),
                            array('configuration', 'configuration.php', BOX_HEADING_CONFIGURATION),
                            array('catalog', 'catalog.php', BOX_HEADING_CATALOG),
                            array('customers', 'customers.php' , BOX_HEADING_CUSTOMERS));

  $box_files_list1b = array(array('marketing', 'marketing.php', BOX_HEADING_MARKETING),
                            array('gv_admin', 'gv_admin.php' , BOX_HEADING_GV_ADMIN),
                            array('reports', 'reports.php' , BOX_HEADING_REPORTS),
                            array('data', 'data.php' , BOX_HEADING_DATA));

  if (defined('MODULE_ADDONS_FDM_STATUS') && MODULE_ADDONS_FDM_STATUS == 'True') {
    $box_files_list2a = array(array('information', 'information.php', BOX_CDS_HEADING),
                              array('fdm_library', 'fdm_library.php' , BOX_HEADING_LIBRARY),
                              array('articles', 'articles.php' , BOX_HEADING_ARTICLES));
  } else {
    $box_files_list2a = array(array('information', 'information.php', BOX_CDS_HEADING),
                              array('articles', 'articles.php' , BOX_HEADING_ARTICLES));
  }
  $box_files_list2b = array(array('design_controls' , 'design_controls.php' , BOX_HEADING_DESIGN_CONTROLS),
                            array('links', 'links.php' , BOX_HEADING_LINKS),
                            array('modules', 'modules.php' , BOX_HEADING_MODULES),
                            array('taxes', 'taxes.php' , BOX_HEADING_LOCATION_AND_TAXES),
                            array('localization', 'localization.php' , BOX_HEADING_LOCALIZATION),
                            array('tools', 'tools.php', BOX_HEADING_TOOLS)
    );
    
    $box_files_list3 = array(array('b2bsettings', 'b2bsettings.php', BOX_HEADING_B2BSETTINGS),
                            array('affiliate', 'affiliate.php', BOX_HEADING_AFFILIATE));

if (MVS_STATUS == 'true') {  
  $box_files_list3 = array_merge($box_files_list3,array(array('mvssetting', 'vendors.php', BOX_HEADING_VENDORS)));
}
if (defined('MODULE_ADDONS_FSS_STATUS') && MODULE_ADDONS_FSS_STATUS == 'True') {
  $box_files_list3 = array_merge($box_files_list3,array(array('fss_menu', 'FSS_boxes_menu.php' , BOX_HEADING_FSS)));
}


  // RCI start
  $returned_rci_first_menu = $cre_RCI->get('boxes', 'dhtmlmenufirst', false);
  $new = str_replace(ord(60), "", $returned_rci_first_menu);
  $box_files_rci_first_menu = array(explode(", ", $new));

  $returned_rci_second_menu = $cre_RCI->get('boxes', 'dhtmlmenusecond', false);
  $new = str_replace(ord(60), "", $returned_rci_second_menu);
  $box_files_rci_second_menu = array(explode(", ", $new));

  $returned_rci_third_menu = $cre_RCI->get('boxes', 'dhtmlmenuthird', false);
  $new = str_replace(ord(60), "", $returned_rci_third_menu);
  $box_files_rci_third_menu = array(explode(", ", $new));

  if ($returned_rci_first_menu == '') {
    $box_files_list1 = array_merge($box_files_list1a, $box_files_list1b);
  } else {
    $box_files_list1 = array_merge($box_files_list1a, $box_files_rci_first_menu, $box_files_list1b);
  }
  if ($returned_rci_second_menu  == '') {
    $box_files_list2 = array_merge($box_files_list2a, $box_files_list2b);
  } else {
    $box_files_list2 = array_merge($box_files_list2a, $box_files_rci_second_menu, $box_files_list2b);
  }
  if ($returned_rci_third_menu  != '') {
    $box_files_list3 = array_merge($box_files_list3, $box_files_rci_third_menu);
  }
  // RCI eof
  ?>
  
  <script type="text/javascript">
    function showMenu (element) {
      element = $(element);
      element.addClassName('nav-item-active');
      element.up('.nav').setStyle({zIndex: 5});
      element.down('.nav-menu').setStyle({visibility: 'visible'});
    }
    function hideMenu (element) {
      element = $(element);
      element.removeClassName('nav-item-active');
      element.up('.nav').setStyle({zIndex: 0});
      element.down('.nav-menu').setStyle({visibility: 'hidden'});
    }
  </script>
  
  <div class="nav">
    <table class="nav-container" border="0" cellpadding="0" cellspacing="0" align="center">
      <tr>
      <?php
      foreach($box_files_list1 as $item_menu) {
        if (tep_admin_check_boxes($item_menu[1]) == true) {
          ?>
          <td>
            <div class="nav-item" onmouseover="showMenu(this)" onmouseout="hideMenu(this)" style="padding-top: 4px;">
              <div onclick="window.location.href = $(this).up('.nav-item').down('a').href;"><nobr><?php echo $item_menu[2]; ?></nobr></div>
              <div class="nav-menu" style="top: 22px;">
                <div class="nav-item-menu-body">
                  <table border="0" cellpadding="0" cellspacing="0">
                  <?php require(DIR_WS_BOXES . $item_menu[1]); ?>
                  </table>
                </div>
                <div class="nav-item-menu-foot"></div>
              </div>
            </div>
          </td>
          <?php
          }
        }
      ?>
      </tr>
    </table>
  </div>
  
  <div class="nav">
    <table class="nav-container" border="0" cellpadding="0" cellspacing="0" align="center">
      <tr>
      <?php
      foreach($box_files_list2 as $item_menu) {
        if (tep_admin_check_boxes($item_menu[1]) == true) {
          ?>
          <td>
            <div class="nav-item" onmouseover="showMenu(this)" onmouseout="hideMenu(this)">
              <div onclick="window.location.href = $(this).up('.nav-item').down('a').href;"><nobr><?php echo $item_menu[2]; ?></nobr></div>
              <div class="nav-menu">
                <div class="nav-item-menu-body">
                  <table border="0" cellpadding="0" cellspacing="0">
                  <?php 
                    require(DIR_WS_BOXES . $item_menu[1]); 
                  ?>
                  </table>
                </div>
                <div class="nav-item-menu-foot"></div>
              </div>
            </div>
          </td>
          <?php
          }
        }
      ?>
      </tr>
    </table>
  </div>

  <div class="nav">
    <table class="nav-container" border="0" cellpadding="0" cellspacing="0" align="center">
      <tr>
      <?php
      foreach($box_files_list3 as $item_menu) {
        if (tep_admin_check_boxes($item_menu[1]) == true) {
          ?>
          <td>
            <div class="nav-item" onmouseover="showMenu(this)" onmouseout="hideMenu(this)">
              <div onclick="window.location.href = $(this).up('.nav-item').down('a').href;"><nobr><?php echo $item_menu[2]; ?></nobr></div>
              <div class="nav-menu">
                <div class="nav-item-menu-body">
                  <table border="0" cellpadding="0" cellspacing="0">
                  <?php 
                    //require(DIR_WS_BOXES . $item_menu[1]); 
                    if($item_menu[1] == 'FSS_boxes_menu.php') {
                      require(DIR_WS_INCLUDES."runtime/boxes/". $item_menu[1]);
                    } else {
                      require(DIR_WS_BOXES . $item_menu[1]); 
                    }
                  ?>
                  </table>
                </div>
                <div class="nav-item-menu-foot"></div>
              </div>
            </div>
          </td>
          <?php
          }
        }
      ?>
      </tr>
    </table>
  </div>

  <!--[if lt IE 7]>
  <script type="text/javascript">
  // I have absolutely no idea why this works.
  $$('.nav-item').each(function (element) {
    setTimeout(function () {
      hideMenu(element);
    }, 0);
  });
  </script>
  
  <![endif]-->

  <?php
}
?>
