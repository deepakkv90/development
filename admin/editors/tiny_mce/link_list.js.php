<?php
/*
  $Id: link_list.js.php,v 1.0.0.0 2008/05/28 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
chdir('../../');
include('includes/application_top.php');
?>
var tinyMCELinkList = new Array(
    // Name, URL
<?php 
   $sql_query = tep_db_query("SELECT p.pages_id, pd.pages_title FROM " . TABLE_PAGES_DESCRIPTION . " pd, " . TABLE_PAGES . " p WHERE p.pages_status= '1' ORDER BY p.pages_sort_order");
   echo '["----Pages Links----", ""],' . "\n";
   while ($row = tep_db_fetch_array($sql_query)){
       if($row['pages_title'] !=''){
           echo '["' . $row['pages_title'] . '", "' . HTTP_CATALOG_SERVER . DIR_WS_HTTP_CATALOG . 'pages.php','?pID=' . $row['pages_id'] . '"],' . "\n";
       }
   }
    echo '["----Site Links----", ""],' . "\n";
    echo '["Contact Us Link", "' . HTTP_CATALOG_SERVER . DIR_WS_HTTP_CATALOG . 'contact_us.php"],' . "\n";
    echo '["Catalog Link", "' . HTTP_CATALOG_SERVER . DIR_WS_HTTP_CATALOG . '"]';
?>
);
<?php
    include('includes/application_bottom.php');
?>