<?php
/*
  Created by: Linda McGrath osCOMMERCE@WebMakers.com
  
  Update by: fram 05-05-2003
  Updated by: Donald Harriman - 08-08-2003 - MS2

  down_for_maintenance.php v1.1

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . DOWN_FOR_MAINTENANCE_FILENAME);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(DOWN_FOR_MAINTENANCE_FILENAME));


  $content = CONTENT_DOWN_FOR_MAINTAINANCE;


  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
