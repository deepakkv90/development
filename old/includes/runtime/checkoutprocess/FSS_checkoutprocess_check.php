<?php
/*
  $Id: FSS_checkoutprocess_check.php,v 1.0.0 2008/05/22 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
global $error, $messageStack;
if (defined('MODULE_ADDONS_FSS_STATUS') && MODULE_ADDONS_FSS_STATUS == 'True') {
  if ( isset($_POST['forms_id']) && tep_not_null($_POST['forms_id']) ) {
    $forms_id = $_POST['forms_id'];
    $forms_query = tep_db_query("SELECT ff.forms_type, ff.forms_post_name, ff.send_email_to, ff.send_post_data, ff.enable_vvc, ffd.forms_name, ffd. forms_confirmation_content 
                                   from " . TABLE_FSS_FORMS . " ff, 
                                        " . TABLE_FSS_FORMS_DESCRIPTION . " ffd 
                                 WHERE ff.forms_id = '" . $forms_id . "' 
                                   and ff.forms_id = ffd.forms_id 
                                   and ffd.language_id = '" . $_SESSION['languages_id'] . "'");
    if (tep_db_num_rows($forms_query) > 0) {
      $forms = tep_db_fetch_array($forms_query);
      require_once(DIR_WS_FUNCTIONS . 'fss_functions.php');
      $questions = tep_fss_get_forms_questions($forms_id);
      $result = tep_fss_check_required($questions);
      if ( $result !== true ) {
        $max = sizeof($result) - 1;
        foreach ($result as $key => $value) {
          if ( $key == $max ) {
            $error .= $value;
          } else {
            $error .= $value . '<br>';
          }
        }
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, 'error=' . urlencode($error), 'SSL'));
      }
    }
  }
}
?>