<?php
/*

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce
  Portions  Copyright (c) 2007 Chain Reaction Works

  Released under the GNU General Public License
*/

// close session (store variables)
  tep_session_close();

  if (STORE_PAGE_PARSE_TIME == 'true') {
    if (!is_object($logger)) $logger = new logger;
    echo $logger->timer_stop(DISPLAY_PAGE_PARSE_TIME);
  }

//RCI start
$cre_RCI->get('bottom', 'load', false);
//RCI end

?>