<?php
/*
  $Id: ot_buysafe.php,v 1.7 2007/03/16 00:12:04 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class ot_buysafe
  {
    var $title, $output;

    function ot_buysafe()
    {
      $this->code = 'ot_buysafe';
      $this->title = (defined('MODULE_ORDER_TOTAL_BUYSAFE_TITLE')) ? MODULE_ORDER_TOTAL_BUYSAFE_TITLE : '';
      $this->description = (defined('MODULE_ORDER_TOTAL_BUYSAFE_DESCRIPTION')) ? MODULE_ORDER_TOTAL_BUYSAFE_DESCRIPTION : '';
      $this->enabled = (defined('MODULE_ADDONS_BUYSAFE_STATUS') && MODULE_ADDONS_BUYSAFE_STATUS == 'True') ? true : false;
      $this->sort_order = (defined('MODULE_ORDER_TOTAL_BUYSAFE_SORT_ORDER')) ? (int)MODULE_ORDER_TOTAL_BUYSAFE_SORT_ORDER : 90;
      $this->output = array();
    }

    function process() {
      global $order, $cart, $buysafe_result, $currencies, $WantsBond, $PHP_SELF;

      if (is_array($buysafe_result) && $buysafe_result['IsBuySafeEnabled'] == 'true') {
        $WantsBond = ($buysafe_result['BondCostDisplayText'] != '') ? true : false;          
        if (strstr($PHP_SELF, FILENAME_CHECKOUT_CONFIRMATION)) {
          $hidden_fields = '';
          if (is_array($_POST) && (sizeof($_POST) > 0)) {
            reset($_POST);
            while (list($key, $value) = each($_POST)) {
              if ( (strlen($value) > 0) && ($key != tep_session_name()) && ($key != 'WantsBond') && ($key != 'x') && ($key != 'y') ) {
                $hidden_fields .= tep_draw_hidden_field($key, stripslashes($value));
              }
            }
          }
          $regs = array();
          preg_match_all("'<BondingSignal[^>]*?>.*?</BondingSignal>'", $_SESSION['nusoap_response'], $regs);
          $rollover = end($regs[0]);
          $rollover = strip_tags(substr($rollover, strpos($rollover, '>')+1));       
          $rollover = html_entity_decode($rollover);

          $output_title = tep_draw_form('buysafe_confirmation', tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post') . tep_draw_hidden_field('WantsBond', ($WantsBond ? $WantsBond : 'false')) . $hidden_fields . '
          <script src="' . MODULE_ADDONS_BUYSAFE_ROLLOVER_URL . '" type="text/javascript" language="javascript" charset="utf-8"></script>
          <script language="JavaScript" type="text/javascript">
          <!--
          function buySAFEOnClick() {
            if (document.buysafe_confirmation.WantsBond.value == \'false\') {
              document.buysafe_confirmation.WantsBond.value = \'true\';
            } else {
              document.buysafe_confirmation.WantsBond.value = \'false\';
            }
            document.buysafe_confirmation.submit();
          }
          //-->
          </script>' . $rollover . '</form>';
        } else {
          $output_title = $buysafe_result['CartLineDisplayText'];
        }
        $this->output[] = array('title' => $output_title,
                                'text' => $buysafe_result['BondCostDisplayText'],
                                'value' => $buysafe_result['BondCostDisplayText'] ? $buysafe_result['TotalBondCost'] : 0);

        if ($buysafe_result['BondCostDisplayText'] && $buysafe_result['TotalBondCost']) {
          $order->info['total'] += $buysafe_result['TotalBondCost'];
        }
      } // end if (is_array($buysafe_result))
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ADDONS_BUYSAFE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_BUYSAFE_SORT_ORDER');
    }

    function install() {
//      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_BUYSAFE_SORT_ORDER', '90', 'Sort order of display.', '6', '2', now())");
//      tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . (int)(MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER - 1 > 0 ? MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER - 1 : 0) . "' where configuration_key = 'MODULE_ORDER_TOTAL_BUYSAFE_SORT_ORDER'");
    }

    function remove() {
//      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>