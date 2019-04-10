<?php
/*
  $Id: header_navigation.php,v 1.1.1.1 2004/03/04 23:39:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  Updated by Gnidhal (fx@geniehalles.com)
*/

  $box_files_list = array(  array('administrator'   , 'administrator.php', BOX_HEADING_ADMINISTRATOR),
             array('configuration'   , 'configuration.php', BOX_HEADING_CONFIGURATION),
                            array('catalog'         , 'catalog.php', BOX_HEADING_CATALOG),
                            array('information'     , 'information.php', BOX_HEADING_INFORMATION),
                            array('newsdesk'        , 'newsdesk.php', BOX_HEADING_NEWSDESK),
                            array('faqdesk'         , 'faqdesk.php', BOX_HEADING_FAQDESK),
                            array('modules'         , 'modules.php' , BOX_HEADING_MODULES),
                            array('gv_admin'        , 'gv_admin.php' , BOX_HEADING_GV_ADMIN),
                            array('customers'       , 'customers.php' , BOX_HEADING_CUSTOMERS),
                            array('returns'         , 'returns.php' , BOX_HEADING_RETURNS),
                          );
                                              
  $box_files_list02 = array(  array('affiliate'       , 'affiliate.php', BOX_HEADING_AFFILIATE),
                            array('taxes'           , 'taxes.php' , BOX_HEADING_LOCATION_AND_TAXES),
                            array('localization'    , 'localization.php' , BOX_HEADING_LOCALIZATION),
                            array('design_controls' , 'design_controls.php' , BOX_HEADING_DESIGN_CONTROLS),
                            array('links'          , 'links.php' , BOX_HEADING_LINKS),
                            array('reports'         , 'reports.php' , BOX_HEADING_REPORTS),
                            array('tools'           , 'tools.php' , BOX_HEADING_TOOLS)
                          );

   echo '<!-- Menu bar #1. --> <div class="menuBar" style="width:100%;">';
   foreach($box_files_list as $item_menu) {
  if (tep_admin_check_boxes($item_menu[1]) == true) {
     echo "<a class=\"menuButton\" href=\"\" onclick=\"return buttonClick(event, '".$item_menu[0]."Menu');\" onmouseover=\"buttonMouseover(event, '".$item_menu[0]."Menu');\">".$item_menu[2]."</a>" ;
   }
}
   echo "</div>";
    
   echo '<!-- Menu bar #2. --> <div class="menuBar" style="width:100%;">';
   foreach($box_files_list02 as $item_menu) {
  if (tep_admin_check_boxes($item_menu[1]) == true) {
     echo "<a class=\"menuButton\" href=\"\" onclick=\"return buttonClick(event, '".$item_menu[0]."Menu');\" onmouseover=\"buttonMouseover(event, '".$item_menu[0]."Menu');\">".$item_menu[2]."</a>" ;
   }
}
   echo "</div>";

foreach($box_files_list as $item_menu) require(DIR_WS_BOXES. $item_menu[1] );
foreach($box_files_list02 as $item_menu) require(DIR_WS_BOXES. $item_menu[1] );


?>