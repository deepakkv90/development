<?php
/*
  $Id: FSS_orders_specialform.php,v 1.0.0.0 2008/06/18 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
global $oID;
if (defined('MODULE_ADDONS_FSS_STATUS') && MODULE_ADDONS_FSS_STATUS == 'True') { 
  require_once(DIR_WS_FUNCTIONS . 'fss_functions.php');
  $valuequery = tep_db_query("select ffpc.questions_variable, ffpc.forms_fields_label, ffpc.forms_fields_value FROM ". TABLE_FSS_FORMS_POSTS_CONTENT . " ffpc, " . TABLE_FSS_FORMS_POSTS . " ffp WHERE ffpc.forms_posts_id = ffp.forms_posts_id and ffp.orders_id = '" . $oID . "'");
  if (tep_db_num_rows($valuequery) > 0) {
    ?>
    <style type="text/css">
    TD.fss {
      border-right: 1px solid;
      border-bottom: 1px solid;
      border-color: #b6b7cb;
      font-family: Verdana, Arial, sans-serif;
      font-size: 11px;
    }
    TD.fss_header {
      border-right: 1px solid;
      border-top: 1px solid;
      border-bottom: 1px solid;
      border-color: #b6b7cb;
      font-family: Verdana, Arial, sans-serif;
      font-size: 11px;
    }
    TD.fss_left {
      border-right: 1px solid;
      border-bottom: 1px solid;
      border-left: 1px solid;
      border-color: #b6b7cb;
      font-family: Verdana, Arial, sans-serif;
      font-size: 11px;
    }
    TD.fss_left_header {
      border-right: 1px solid;
      border-bottom: 1px solid;
      border-left: 1px solid;
      border-top: 1px solid;
      border-color: #b6b7cb;
      font-family: Verdana, Arial, sans-serif;
      font-size: 11px;
    }
    </style>
    <tr>
      <td class="main"><b><?php echo TEXT_FORMS_QUESTIONS; ?></b></td>
    </tr>
    <?php
    $rci  = '<tr>' . "\n";
    $rci .= '  <td height="40" class="main" colspan="2">' . "\n";
    $rci .= '    <table border="0" cellspacing="0" cellpadding="5">' . "\n";
    $rci .= '      <tr>' . "\n";
    $rci .= '        <td align="center" width="200px" class="fss_left_header"><b>' . TEXT_TABLE_HEADING_FIELD_LABEL . '</b></td>' . "\n";
    $rci .= '        <td class="fss_header" width="400px" align="center"><b>' . TEXT_TABLE_HEADING_FIELD_VALUE . '</b></td>' . "\n";
    $rci .= '        <td class="fss_header" width="100px" align="center"><b>' . TEXT_TABLE_HEADING_FIELD_SPECIAL . '</b></td>' . "\n";
    $rci .= '      </tr>' . "\n";
    while($fields_values = tep_db_fetch_array($valuequery)) {
      if (ereg("http://[^\s]", $fields_values['forms_fields_value'])) {
        $special = tep_fss_get_special_str('url', $fields_values['forms_fields_value']);
      } else {
        $special = tep_fss_get_special_str($fields_values['questions_variable'], $fields_values['forms_fields_value']);
      }
      $rci .= '    <tr>' . "\n";
      $rci .= '      <td class="fss_left"><b>' . $fields_values['forms_fields_label'] . '</b></td>' . "\n";
      $rci .= '      <td class="fss">&nbsp;' . $fields_values['forms_fields_value'] . '</td>' . "\n";
      $rci .= '      <td class="fss">&nbsp;' . $special . '</td>' . "\n";
      $rci .= '    </tr>' . "\n";
    }
    $rci .= '    </table>' . "\n";
    $rci .= '  </td>' . "\n";
    $rci .= '</tr>' . "\n";
  }
  return $rci;
}
?>