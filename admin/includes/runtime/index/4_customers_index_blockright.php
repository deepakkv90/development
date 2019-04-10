<?php
/*
  $Id: 4_customers_index_blockleft.php,v 1.0.0.0 2007/07/25 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if(defined('ADMIN_BLOCKS_CUSTOMERS_STATUS') && ADMIN_BLOCKS_CUSTOMERS_STATUS == 'true'){
  //Customer Count Code
  $customer_query = tep_db_query("select count(customers_id) as customercnt from " . TABLE_CUSTOMERS);
  $customercount = tep_db_fetch_array($customer_query);
  define('CUSTOMER_COUNT',$customercount['customercnt']);
  //Customer Subscribed Count Code
  $customer_query = tep_db_query("select count(customers_id) as customercnt from " . TABLE_CUSTOMERS." where customers_newsletter=1");
  $customercount = tep_db_fetch_array($customer_query);
  define('CUSTOMER_SUBSCRIBED_COUNT',$customercount['customercnt']);
  ?>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="Customer Information">
    <tr valign="top">
      <td width="100%" style="padding-right: 12px;"><div class="form-head-light"><?php cre_index_block_title(BLOCK_TITLE_CUSTOMERS,tep_href_link(FILENAME_CREATE_ACCOUNT,'selected_box=customers','NONSSL'),BLOCK_HELP_CUSTOMERS);?></div><div class="form-body form-body-fade">
        <ul class="ul_index">
          <li><?php echo BLOCK_CONTENT_CUSTOMERS_TOTAL.' : '.CUSTOMER_COUNT;?></li>
          <li><?php echo BLOCK_CONTENT_CUSTOMERS_SUBSCRIBED.' : '.CUSTOMER_SUBSCRIBED_COUNT;?></li>
        </ul>
      </div></td>
    </tr>
  </table>
  <?php
}
?>