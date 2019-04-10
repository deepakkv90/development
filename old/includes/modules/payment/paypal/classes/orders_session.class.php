<?php
/*
  $Id: orders_session.class.php,v 2.6a 2004/07/14 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

class orders_session {
  function orders_session($txn_sign) {
    include_once(DIR_WS_MODULES . 'payment/paypal/database_tables.inc.php');
    $txn_signature = tep_db_prepare_input($txn_sign);
    $orders_session_query = tep_db_query("select * from " . TABLE_ORDERS_SESSION_INFO . " where txn_signature ='" . tep_db_input($txn_signature) . "' limit 1");
    if(tep_db_num_rows($orders_session_query)) {
      $orders_session = tep_db_fetch_array($orders_session_query);
      while (list($key, $value) = each($orders_session)) {
        $this->$key = $value;
      }
    }
  }
}
?>
