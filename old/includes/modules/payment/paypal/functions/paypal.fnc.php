<?php
/*
  $Id: paypal.fnc.php,v 2.6a 2004/07/14 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

  function paypal_include_lng($base_dir, $lng_dir, $lng_file) {
    if(file_exists($base_dir . $lng_dir . '/' . $lng_file)) {
      include_once($base_dir . $lng_dir . '/' . $lng_file);
    } else {
      include_once($base_dir . 'english/' . $lng_file);
    }
  }
?>
