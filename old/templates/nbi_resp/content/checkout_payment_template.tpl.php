<?php
/*
  $Id: checkout_payment_template.php,v 1.0 2009/02/10 00:36:41 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI top
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('checkoutpaymenttemplate', 'top');
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
  <?php
  if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
    $header_text = '&nbsp;'
    ?>
    <tr>
      <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
       <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          <td align="right"></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><?php echo TEXT_ORDER_PLACED; ?></td>
    </tr>
    <?php
  } else {
    $header_text = HEADING_TITLE;
  }
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_top(false, false, $header_text);
  }
  ?>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td>[[FORM INSERT]]
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <?php
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_bottom();
  }
?>
</table>
<?php
// RCI bottom 
echo $cre_RCI->get('global', 'bottom');
echo $cre_RCI->get('checkoutpaymenttemplate', 'bottom');
?>