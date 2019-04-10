<?php
/*
  $Id: vendor_order_info.php,v 1.1 2008/06/22 22:50:52 datazen Exp $
  for use with Vendors_Auto_Email and MVS by Craig Garrison Sr.(craig@blucollarsales.com) and Jim Keebaugh

  Modified for MVS V1.0 2006/03/25 JCK/CWG
  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<script language="javascript"><!--
function vendorWindow(url) {
  window.open(url,'vendorWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=700,height=500,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
  <tr>
    <td><table border="1" width="100%" cellspacing="0" cellpadding="2">
      <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_PRODUCTS_VENDOR; ?></td>
        <td class="dataTableHeadingContent" align="left">Vendor Email</td>
        <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_VENDORS_SHIP; ?></td>
        <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SHIPPING_METHOD; ?></td>
        <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SHIPPING_COST; ?></td>
        <td class="dataTableHeadingContent" align="center">Empty</td>
        <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_TAX; ?></td>
        <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
        <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
        <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
      </tr>
      <?php
      $package_num = sizeof($order->products);
      $box_num = $i + 1;
      //for ($i=0, $m=sizeof($order->products); $i<$m; $i++) {
      for ($i=0; $i<sizeof($order->products); $i++) {
        $ship_data_text = 'Shipment Number ' . $box_num++ . ' of ' . $package_num;
        echo '<tr class="dataTableRow">' . "\n" .
             '  <td class="dataTableContent" valign="top">' . $order->products[$i]['Vname'] . '<br>' . $ship_data_text . '<br><a href="javascript:vendorWindow(\'' . tep_href_link('vendor_packingslip.php', 'oID=' . $oID . '&vID=' . $order->products[$i]['Vid'] . '&text=' . $ship_data_text) . '\')">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a></td>' . "\n";
        echo '  <td class="dataTableContent" valign="center" align="center"><a href="javascript:vendorWindow(\'' . tep_href_link(FILENAME_VENDORS_EMAIL_SEND, 'vID=' . $order->products[$i]['Vid'] . '&oID=' . $oID . '&vOS=' . $order->products[$i]['Vorder_sent']) . '\')">Vendor Order Sent: <b>' . $order->products[$i]['Vorder_sent'] . '</a></b></td>';
        echo '  <td class="dataTableContent" valign="center" align="center">' . $order->products[$i]['Vmodule'] . '</td>' . "\n" .
             '  <td class="dataTableContent" valign="center" align="center">' . $order->products[$i]['Vmethod'] . '</td>' . "\n" .
             '  <td class="dataTableContent" valign="center" align="center">' . $order->products[$i]['Vcost'] . '</td>' . "\n" .
             '  <td class="dataTableContent" valign="center" align="center">' . $order->products[$i]['spacer'] . '</td>' . "\n" .
             '  <td class="dataTableContent" valign="center" align="center">ship tax<br>' . $order->products[$i]['Vship_tax'] . '</td>' . "\n" .
             '  <td class="dataTableContent" valign="center" align="center">' . $order->products[$i]['spacer'] . '</td>' . "\n" .
             '  <td class="dataTableContent" valign="center" align="center">' . $order->products[$i]['spacer'] . '</td>' . "\n" .
             '  <td class="dataTableContent" valign="center" align="center">' . $order->products[$i]['spacer'] . '</td>' . "\n" .
             '  <td class="dataTableContent" valign="center" align="center">' . $order->products[$i]['spacer'] . '</td>';
        for ($ii=0, $n=sizeof($order->products[$i]['orders_products']); $ii<$n; $ii++) {
          echo '    <tr>' . "\n" .
               '      <td class="dataTableContent" valign="center" align="right">' . $order->products[$i]['orders_products'][$ii]['qty'] . '&nbsp;x</td>' . "\n" .
               '      <td class="dataTableContent" valign="center" align="left">' . $order->products[$i]['orders_products'][$ii]['name'];
               if (isset($order->products[$i]['orders_products'][$ii]['attributes']) && (sizeof($order->products[$i]['orders_products'][$ii]['attributes']) > 0)) {
                 for ($j = 0, $k = sizeof($order->products[$i]['orders_products'][$ii]['attributes']); $j < $k; $j++) {
                   echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['orders_products'][$ii]['attributes'][$j]['option'] . ': ' . $order->products[$i]['orders_products'][$ii]['attributes'][$j]['value'];
                   if ($order->products[$i]['orders_products'][$ii]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['orders_products'][$ii]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['orders_products'][$ii]['attributes'][$j]['price'] * $order->products[$i]['orders_products'][$ii]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
                   echo '</i></small></nobr>';
                 }
               }
          echo '      <td class="dataTableContent" valign="center" align="center">' . $order->products[$i]['orders_products'][$i]['spacer'] . '</td>' . "\n" .
               '      <td class="dataTableContent" valign="center" align="center">' . $order->products[$i]['orders_products'][$i]['spacer'] . '</td>' . "\n" .
               '      <td class="dataTableContent" valign="center" align="center">' . $order->products[$i]['orders_products'][$i]['spacer'] . '</td>' . "\n" .
               '      <td class="dataTableContent" align="center" valign="center">' . tep_display_tax_value($order->products[$i]['orders_products'][$i]['tax']) . '%</td>' . "\n" .
               '      <td class="dataTableContent" align="center" valign="center"><b>' . $currencies->format($order->products[$i]['orders_products'][$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
               '      <td class="dataTableContent" align="center" valign="center"><b>' . $currencies->format(tep_add_tax($order->products[$i]['orders_products'][$i]['final_price'], $order->products[$i]['orders_products'][$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
               '      <td class="dataTableContent" align="center" valign="center"><b>' . $currencies->format($order->products[$i]['orders_products'][$i]['final_price'] * $order->products[$i]['orders_products'][$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
               '      <td class="dataTableContent" align="right" valign="center"><b>' .  $currencies->format(tep_add_tax($order->products[$i]['orders_products'][$i]['final_price'], $order->products[$i]['orders_products'][$i]['tax']) * $order->products[$i]['orders_products'][$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
          echo '    </tr>';
        }
      }
      ?>
    </table></td>
  </tr>