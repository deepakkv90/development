<?php
/*
  $Id: 7_affiliates_index_blockleft.php,v 1.0.0.0 2007/07/25 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if(defined('ADMIN_BLOCKS_AFFILIATES_STATUS') && ADMIN_BLOCKS_AFFILIATES_STATUS == 'true'){
  //Affiliate Count Code
  $affiliate_query = tep_db_query("select count(affiliate_id) as affiliatecnt from " . TABLE_AFFILIATE_AFFILIATE);
  $affiliatecount = tep_db_fetch_array($affiliate_query);
  define('AFFILIATE_COUNT',$affiliatecount['affiliatecnt']);
  $affiliate_query = tep_db_query('SELECT round(sum( sales.affiliate_value),2)  AS affiliate, 
                                          round(sum( ( sales.affiliate_value * sales.affiliate_percent ) / 100),2)  AS commission
                                     from ' . TABLE_AFFILIATE_SALES . ' sales
                                   LEFT JOIN ' . TABLE_ORDERS . ' o on sales.affiliate_orders_id = o.orders_id
                                   WHERE o.orders_id is not null
                                     and affiliate_id != 0
                                     and sales.affiliate_billing_status = 0
                                     and o.orders_status = ' . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . '
                                 ');
  $affiliatecount = tep_db_fetch_array($affiliate_query);
  $affiliatesales=$affiliatecount['affiliate'];
  if($affiliatesales==""){$affiliatesales=0;}
  $affiliatecomm=$affiliatecount['commission'];
  if($affiliatecomm==""){$affiliatecomm=0;}
  define('AFFILIATE_SALES_AMOUNT',$affiliatesales);
  define('AFFILIATE_COMMISSION_AMOUNT',$affiliatecomm);
  ?>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="Affiliates Information">
    <tr valign="top">
      <td width="100%" style="padding-right: 12px;">
        <div class="form-head-light"><?php cre_index_block_title(BLOCK_TITLE_AFFILIATE,tep_href_link(FILENAME_AFFILIATE,'selected_box=affiliate','NONSSL'), BLOCK_HELP_AFFILIATE);?></div>
        <div class="form-body form-body-fade">
          <ul class="ul_index">
            <li><?php echo BLOCK_CONTENT_AFFILIATE_TOTAL.' : '.AFFILIATE_COUNT;?></li>
            <li><?php echo BLOCK_CONTENT_AFFILIATE_SALES.' : $'.AFFILIATE_SALES_AMOUNT;?></li>
            <li><?php echo BLOCK_CONTENT_AFFILIATE_COMMISSION.' : $'.AFFILIATE_COMMISSION_AMOUNT;?></li>
          </ul>
        </div>
      </td>
    </tr>
  </table>
  <?php
  }
?>