<?php
/*
  $Id: customer_gv.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- customer_gv -->
<?php
$customer_gv = new box_customer_gv();

if ($customer_gv->amount > 0) {
?>
  <tr>
    <td>
      <?php
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left',
                                   'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_GIFT_VOUCHER . '</font>'
                                  );
      new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') );
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left',
                                   'text'  => '<div align="center">'.GIFT_VOUCHER_ACCOUNT_BALANCE_1.$currencies->format($customer_gv->amount).GIFT_VOUCHER_ACCOUNT_BALANCE_2.'<a href="'.tep_href_link(FILENAME_GV_SEND).'">'.GIFT_VOUCHER_ACCOUNT_BALANCE_3.'</a>'
                                  );
      new $infobox_template($info_box_contents, true, true, ((isset($column_location) && $column_location !='') ? $column_location : '') );
      if (TEMPLATE_INCLUDE_FOOTER =='true') {
        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left',
                                     'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                    );
        new $infobox_template_footer($info_box_contents, ((isset($column_location) && $column_location !='') ? $column_location : '') );
      }
      ?>
    </td>
  </tr>
<?php
}
?>
<!-- customer_gv eof//-->