<?php
/*
  $Id: buysafe_shoppingcart_offsettotal.php,v 1.0.0.0 2007/09/04 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
global $buysafe_result, $WantsBond, $offset_amount;

if (defined('MODULE_ADDONS_BUYSAFE_STATUS') &&  MODULE_ADDONS_BUYSAFE_STATUS == 'True') { 
  $rci = '';
  if (is_array($buysafe_result) && $buysafe_result['IsBuySafeEnabled'] == 'true') {
    ?>
    <script src="<?php echo MODULE_ADDONS_BUYSAFE_ROLLOVER_URL; ?>" type="text/javascript" language="javascript" charset="utf-8"></script>
    <script language="JavaScript" type="text/javascript">
    <!--
    function buySAFEOnClick() {
      if (document.cart_quantity.WantsBond.value == 'false') {
        document.cart_quantity.WantsBond.value = 'true';
      } else {
        document.cart_quantity.WantsBond.value = 'false';
      }
      document.cart_quantity.submit();
    }
    //-->
    </script>
    <?php
    $regs = array();
    preg_match_all("'<BondingSignal[^>]*?>.*?</BondingSignal>'", $_SESSION['nusoap_response'], $regs);
    $rollover = end($regs[0]);
    $rollover = strip_tags(substr($rollover, strpos($rollover, '>')+1));       
    $rollover = html_entity_decode($rollover);
    
    $rci .= '    <tr>' . "\n";
    $rci .= '      <td class="main" align="right"><b>' . tep_draw_hidden_field('WantsBond', ($WantsBond ? $WantsBond : 'false')) . $rollover . '</b></td>' . "\n";
    $rci .= '      <td class="main" align="right"><b>' . $buysafe_result['BondCostDisplayText'] . '</b></td>' . "\n";
    $rci .= '    </tr>' . "\n";
    if ($WantsBond == 'true') {
      $offset_amount = $buysafe_result['TotalBondCost'];
    } else { 
      $offset_amount = 0;
    }
  }
  return $rci;
}
?>