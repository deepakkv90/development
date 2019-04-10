<?php
/*
  $Id: 6_components_index_blockleft.php,v 1.0.0.0 2007/07/25 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
//if(defined('ADMIN_BLOCKS_COMPONENTS_STATUS') && ADMIN_BLOCKS_COMPONENTS_STATUS == 'true'){
  $components_query = tep_db_query("SELECT * from " . TABLE_COMPONENTS);
  ?>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="Component Information">
    <tr valign="top">
      <td width="100%" style="padding-right: 12px;"><div class="form-head-light"><?php cre_index_block_title(BLOCK_TITLE_COMPONENTS,'','');?></div><div class="form-body form-body-fade">
        <ul class="ul_index">
        <?php
        if (tep_db_num_rows($components_query) > 0) {
          while ($components = tep_db_fetch_array($components_query)) {
            ?>
            <li><?php echo $components['serial_1'] . ' | ' . $components['validation_product'] . ' | ' . $components['expiration_date']; ?></li>
            <?php
          }
        } else {
          echo '<span class="errorText">' . BLOCK_TEXT_EMPTY_COMPONENTS . '</span>';
        }
        ?>
        </ul>
      </div></td>
    </tr>
  </table>
  <?php
//}
?>