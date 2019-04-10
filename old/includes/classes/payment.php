<?php
/*
  $Id: payment.php,v 1.1.1.1 2004/03/04 23:40:46 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class payment {
    var $modules, $selected_module;

// class constructor
    function payment($module = '') {
// BOF: WebMakers.com Added: Downloads Controller
      global  $language, $PHP_SELF, $cart;
// EOF: WebMakers.com Added: Downloads Controller

      if (defined('MODULE_PAYMENT_INSTALLED') && tep_not_null(MODULE_PAYMENT_INSTALLED)) {
      //Eversun mod for sppc and qty price breaks
//       $this->modules = explode(';', MODULE_PAYMENT_INSTALLED);
     
     if( ! isset($_SESSION['sppc_customer_group_id']) ) {
      $customer_group_id = '0';
     } else {
      $customer_group_id = $_SESSION['sppc_customer_group_id'];
     }
     $customer_payment_query = tep_db_query("select IF(c.customers_payment_allowed <> '', c.customers_payment_allowed, cg.group_payment_allowed) as payment_allowed from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_GROUPS . " cg where c.customers_id = '" . $_SESSION['customer_id'] . "' and cg.customers_group_id =  '" . $customer_group_id . "'");
     if ($customer_payment = tep_db_fetch_array($customer_payment_query)  ) {
       if (tep_not_null($customer_payment['payment_allowed'])) {
        $temp_payment_array = explode(';', $customer_payment['payment_allowed']);
        $installed_modules = explode(';', MODULE_PAYMENT_INSTALLED);
        for ($n = 0; $n < sizeof($installed_modules) ; $n++) {
          // check to see if a payment method is not de-installed
          if ( in_array($installed_modules[$n], $temp_payment_array ) ) {
            $payment_array[] = $installed_modules[$n];
          }
        } // end for loop
        if (is_array($payment_array)) {
                                $this->modules = $payment_array;
                          } else {
                                $this->modules = array();
                          }
        } else {
        $this->modules = explode(';', MODULE_PAYMENT_INSTALLED);
        }
      } else { // default
        $this->modules = explode(';', MODULE_PAYMENT_INSTALLED);
      }
// Eversun mod end for sppc and qty price breaks
        

        $include_modules = array();


        if ( (tep_not_null($module)) && (in_array($module . '.' . substr($PHP_SELF, (strrpos($PHP_SELF, '.')+1)), $this->modules)) ) {
          $this->selected_module = $module;

          $include_modules[] = array('class' => $module, 'file' => $module . '.php');
        } else {
          reset($this->modules);
// BOF: WebMakers.com Added: Downloads Controller - Free Shipping and Payments
// Show either normal payment modules or free payment module when Free Shipping Module is On
          // Free Payment Only

          if (tep_get_configuration_key_value('MODULE_PAYMENT_FREECHARGER_STATUS') and ($cart->show_total()==0 and $cart->show_weight()==0)) {
            $this->selected_module = $module;
            $include_modules[] = array('class'=> 'freecharger', 'file' => 'freecharger.php');
          } else {
            // All Other Payment Modules
            while (list(, $value) = each($this->modules)) {
              $class = substr($value, 0, strrpos($value, '.'));
              // Don't show Free Payment Module
              if ($class !='freecharger' && $class !='ccerr') {
                $include_modules[] = array('class' => $class, 'file' => $value);
              }
            }
// EOF: WebMakers.com Added: Downloads Controller
          }
        }

        for ($i=0, $n=sizeof($include_modules); $i<$n; $i++) {
          include(DIR_WS_LANGUAGES . $language . '/modules/payment/' . $include_modules[$i]['file']);
          include(DIR_WS_MODULES . 'payment/' . $include_modules[$i]['file']);

          $GLOBALS[$include_modules[$i]['class']] = new $include_modules[$i]['class'];
        }

// if there is only one payment method, select it as default because in
// checkout_confirmation.php the $payment variable is being assigned the
// $_POST['payment'] value which will be empty (no radio button selection possible)
if (!isset($_SESSION['payment'])) {
  $_SESSION['payment'] = '';
}
        if ( (tep_count_payment_modules() == 1) && (!isset($GLOBALS[$_SESSION['payment']]) || (isset($GLOBALS[$_SESSION['payment']]) && !is_object($GLOBALS[$_SESSION['payment']]))) ) {
          $_SESSION['payment'] = $include_modules[0]['class'];
          $this->selected_module = $_SESSION['payment'];
        }

        if ( (tep_not_null($module)) && (in_array($module, $this->modules)) && (isset($GLOBALS[$module]->form_action_url)) ) {
          $this->form_action_url = $GLOBALS[$module]->form_action_url;
        }
      }
    }

// class methods
/* The following method is needed in the checkout_confirmation.php page
   due to a chicken and egg problem with the payment class and order class.
   The payment modules needs the order destination data for the dynamic status
   feature, and the order class needs the payment module title.
   The following method is a work-around to implementing the method in all
   payment modules available which would break the modules in the contributions
   section. This should be looked into again post 2.2.
*/
    function update_status() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module])) {
          if (function_exists('method_exists')) {
            if (method_exists($GLOBALS[$this->selected_module], 'update_status')) {
              $GLOBALS[$this->selected_module]->update_status();
            }
          } else { // PHP3 compatibility
            @call_user_method('update_status', $GLOBALS[$this->selected_module]);
          }
        }
      }
    }

    function javascript_validation() {
      $js = '';
      if (is_array($this->modules)) {
        $js = '<script type="text/javascript"><!-- ' . "\n" .
              'function check_form() {' . "\n" .
              '  var error = 0;' . "\n" .
              '  var error_message = "' . JS_ERROR . '";' . "\n" .
              '  var payment_value = null;' . "\n" .
              '  if (document.checkout_payment.payment.length) {' . "\n" .
              '    for (var i=0; i<document.checkout_payment.payment.length; i++) {' . "\n" .
              '      if (document.checkout_payment.payment[i].checked) {' . "\n" .
              '        payment_value = document.checkout_payment.payment[i].value;' . "\n" .
              '      }' . "\n" .
              '    }' . "\n" .
              '  } else if (document.checkout_payment.payment.checked) {' . "\n" .
              '    payment_value = document.checkout_payment.payment.value;' . "\n" .
              '  } else if (document.checkout_payment.payment.value) {' . "\n" .
              '    payment_value = document.checkout_payment.payment.value;' . "\n" .
              '  }' . "\n\n";

        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if (isset($GLOBALS[$class]->enabled)) {
            $js .= $GLOBALS[$class]->javascript_validation();
          }
        }

        $js .= "\n" . '  if (payment_value == null && submitter != 1) {' . "\n" . // ICW CREDIT CLASS Gift Voucher System
               '    error_message = error_message + "' . JS_ERROR_NO_PAYMENT_MODULE_SELECTED . '";' . "\n" .
               '    error = 1;' . "\n" .
               '  }' . "\n\n" .
//  ICW CREDIT CLASS Gift Voucher System Line below amended
               '  if (error == 1 && submitter != 1) {' . "\n" .
               '    alert(error_message);' . "\n" .
               '    return false;' . "\n" .
               '  } else {' . "\n" .
               '    return true;' . "\n" .
               '  }' . "\n" .
               '}' . "\n" .
               '//--></script>' . "\n";
      }

      return $js;
    }

    function selection() {
      $selection_array = array();

      if (is_array($this->modules)) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if (isset($GLOBALS[$class]->enabled) && $GLOBALS[$class]->enabled) {
            $selection = $GLOBALS[$class]->selection();
            if (is_array($selection) && sizeof($selection) > 0) $selection_array[] = $selection;
          }
        }
      }

      return $selection_array;
    }
 //ICW CREDIT CLASS Gift Voucher System
 // check credit covers was setup to test whether credit covers is set in other parts of the code
function check_credit_covers() {
  

  return $_SESSION['credit_covers'];
}
    function pre_confirmation_check() {
      global  $payment_modules; //ICW CREDIT CLASS Gift Voucher System
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {

          if (isset($_SESSION['credit_covers'])) { //  ICW CREDIT CLASS Gift Voucher System
            $GLOBALS[$this->selected_module]->enabled = false; //ICW CREDIT CLASS Gift Voucher System
            $GLOBALS[$this->selected_module] = NULL; //ICW CREDIT CLASS Gift Voucher System
            $payment_modules = ''; //ICW CREDIT CLASS Gift Voucher System
          } else { //ICW CREDIT CLASS Gift Voucher System
            $GLOBALS[$this->selected_module]->pre_confirmation_check();
          }
        }
      }
    } //ICW CREDIT CLASS Gift Voucher System

    function confirmation() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->confirmation();
        }
      }
    }

    function process_button() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->process_button();
        }
      }
    }

    function before_process() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->before_process();
        }
      }
    }

    function after_process() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->after_process();
        }
      }
    }

    function get_error() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->get_error();
        }
      }
    }
  }
?>