<?php
/*
  $Id: 3_reports_index_blockleft.php,v 1.0.0.0 2007/07/25 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if(defined('ADMIN_BLOCKS_REPORTS_STATUS') && ADMIN_BLOCKS_REPORTS_STATUS == 'true'){
  ?>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="Reports Block">
    <tr valign="top">
      <td width="100%" style="padding-right: 12px;"><div class="form-head-light"><?php cre_index_block_title(BLOCK_TITLE_REPORTS,'',BLOCK_HELP_REPORTS);?></div>
      <div class="form-body form-body-fade">
        <ul class="ul_index">
          <li><a class="adminLink" href="<?php echo tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED,'selected_box=reports','NONSSL');?>"><?php echo BLOCK_CONTENT_REPORTS_PRODUCTS_VIEWED;?></a></li>
          <li><a class="adminLink" href="<?php echo tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED,'selected_box=reports','NONSSL');?>"><?php echo BLOCK_CONTENT_REPORTS_PRODUCTS_PURCHASED;?></a></li>
          <li><a class="adminLink" href="<?php echo tep_href_link(FILENAME_STATS_CUSTOMERS,'selected_box=reports','NONSSL');?>"><?php echo BLOCK_CONTENT_REPORTS_CUSTOMER_ORDERS_TOTAL;?></a></li>
          <li><a class="adminLink" href="<?php echo tep_href_link(FILENAME_STATS_MONTHLY_SALES,'selected_box=reports','NONSSL');?>"><?php echo BLOCK_CONTENT_REPORTS_MONTHLY_SALES_TAX;?></a></li>
        </ul>
      </div></td>
    </tr>
  </table>
  <?php
}
?>