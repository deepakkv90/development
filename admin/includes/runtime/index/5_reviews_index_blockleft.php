<?php
/*
  $Id: 4_reviews_index_blockleft.php,v 1.0.0.0 2007/07/25 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('ADMIN_BLOCKS_REVIEWS_STATUS') && ADMIN_BLOCKS_REVIEWS_STATUS == 'true'){
  //Review Count Code
  $review_query = tep_db_query("select count(reviews_id) as reviewcnt from " . TABLE_REVIEWS);
  $reviewcount = tep_db_fetch_array($review_query);
  //define('REVIEW_COUNT',$reviewcount['reviewcnt']);
  ?>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="Reviews Information">
    <tr valign="top">
      <td width="100%" style="padding-right: 12px;"><div class="form-head-light"><?php cre_index_block_title(BLOCK_TITLE_REVIEWS,tep_href_link(FILENAME_REVIEWS,'selected_box=catalog','NONSSL'),BLOCK_HELP_REVIEWS);?></div>
      <div class="form-body form-body-fade">
        <ul class="ul_index">
          <li><?php echo BLOCK_CONTENT_REVIEWS_TOTAL_REVIEWS.' : '.$reviewcount['reviewcnt'];?></li>
          <!-- li><?php echo BLOCK_CONTENT_REVIEWS_WAITING_APPROVAL;?>: 2 </li -->
        </ul>
      </div></td>
    </tr>
  </table>
  <?php
}
?>