<?php
/*
  $Id: 1_easy_path_index_blockleft.php,v 1.0.0.0 2007/07/25 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('ADMIN_BLOCKS_EASY_PATH') && ADMIN_BLOCKS_EASY_PATH == 'true'){
  ?>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="Reviews Information">
    <tr valign="top">
      <td width="100%" style="padding-right: 12px;">
        <div class="form-head-light"><?php cre_index_block_title(BLOCK_TITLE_EASY_PATH); ?></div>
        <div class="form-body form-body-fade">
          <p><strong>CRE Loaded 6.4 is Certified to the PCI PA-DSS 1.2 standard.</strong></p>
          <p> To put our certification to use towards your PCI requirements as a Merchant, follow our clear PCI implementation guide <a href="http://creloaded.com/pci_guide" target="_blank">found here</a>.</p>
          <a href="<?php echo tep_href_link(REMOVE_EASY_PATH_LINK, 'gID=23&selected_box=configuration&cID=10013', 'SSL'); ?>" style="font-size:smaller"> Click to remove this block</a> 
        </div>
      </td>
    </tr>
  </table>
  <?php
}
?>