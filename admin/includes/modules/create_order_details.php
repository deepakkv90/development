<?php 
if (!isset($is_read_only)) $is_read_only = false;
/*
  $Id: create_order_details.php,v 1.1.1.1 2004/03/04 23:40:22 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

function sbs_get_zone_name($country_id, $zone_id) {
  $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' and zone_id = '" . $zone_id . "'");
  if (tep_db_num_rows($zone_query)) {
    $zone = tep_db_fetch_array($zone_query);
    return $zone['zone_name'];
  } else {
    return (isset($default_zone) ? $default_zone : '');
  }
}
 // Returns an array with countries
function sbs_get_countries($countries_id = '', $with_iso_codes = false) {
  $countries_array = array();
  if ($countries_id) {
    if ($with_iso_codes) {
      $countries = tep_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . $countries_id . "' order by countries_name");
      $countries_values = tep_db_fetch_array($countries);
      $countries_array = array('countries_name' => $countries_values['countries_name'],
                               'countries_iso_code_2' => $countries_values['countries_iso_code_2'],
                               'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
    } else {
      $countries = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . $countries_id . "'");
      $countries_values = tep_db_fetch_array($countries);
      $countries_array = array('countries_name' => $countries_values['countries_name']);
    }
  } else {
    $countries = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
    while ($countries_values = tep_db_fetch_array($countries)) {
      $countries_array[] = array('countries_id' => $countries_values['countries_id'],
                                 'countries_name' => $countries_values['countries_name']);
    }
  }
  return $countries_array;
}

function sbs_get_country_list($name, $selected = '', $parameters = '') {
 $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
 $countries = sbs_get_countries();
 $size = sizeof($countries);
 for ($i=0; $i<$size; $i++) {
   $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
 }
 return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
}

if (isset($account['customers_id'])) {
  tep_draw_hidden_field($account['customers_id']);
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="formAreaTitle"><?php echo CATEGORY_CORRECT; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_CUSTOMERS_ID; ?></td>
            <td class="main">&nbsp;
              <?php
              if (isset($is_read_only)) {
                echo (isset($account['customers_id']) ? (int)$account['customers_id'] : '');
              } else {
    echo tep_draw_input_field('customers_id', (isset($account['customers_id']) ? $account['customers_id'] : 0)) . '&nbsp;' . ENTRY_CUSTOMERS_ID_TEXT;
              }
              ?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main">&nbsp;
              <?php
              if (isset($is_read_only)) {
                echo (isset($account['customers_firstname']) ? $account['customers_firstname'] : '');
              } else {
                echo tep_draw_input_field('firstname', (isset($account['customers_firstname']) ? $account['customers_firstname'] : '')) . '&nbsp;' . ENTRY_FIRST_NAME_TEXT;
              }
              ?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_LAST_NAME; ?></td>
            <td class="main">&nbsp;
              <?php
              if (isset($is_read_only)) {
                echo (isset($account['customers_lastname']) ? $account['customers_lastname'] : '');
              } else {
                echo tep_draw_input_field('lastname', (isset($account['customers_lastname']) ? $account['customers_lastname'] : '')) . '&nbsp;' . ENTRY_LAST_NAME_TEXT;
              }
              ?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main">&nbsp;
              <?php
              if (isset($is_read_only)) {
                echo (isset($account['customers_email_address']) ? $account['customers_email_address'] : '');
              } else {
                echo tep_draw_input_field('email_address', (isset($account['customers_email_address']) ? $account['customers_email_address'] : '')) . '&nbsp;' . ENTRY_EMAIL_ADDRESS_TEXT;
              }
              ?>
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <?php
  if (ACCOUNT_COMPANY == 'true') {
    ?>
    <tr>
      <td class="formAreaTitle"><br><?php echo CATEGORY_COMPANY; ?></td>
    </tr>
    <tr>
      <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
        <tr>
          <td class="main"><table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td class="main">&nbsp;<?php echo ENTRY_COMPANY; ?></td>
              <td class="main">&nbsp;
                <?php  
                if (isset($is_read_only)) {
                  echo (isset($address['entry_company']) ? $address['entry_company'] : '');
                } else {
                  echo tep_draw_input_field('company', (isset($address['entry_company']) ? $address['entry_company'] : '')) . '&nbsp;' . ENTRY_COMPANY_TEXT;
                }
                ?>
              </td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <?php
  }
  ?>
  <tr>
    <td class="formAreaTitle"><br><?php echo CATEGORY_ADDRESS; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_STREET_ADDRESS; ?></td>
            <td class="main">&nbsp;
              <?php
              if (isset($is_read_only)) {
                echo (isset($address['entry_street_address']) ? $address['entry_street_address'] : '');
              } else {
                echo tep_draw_input_field('street_address', (isset($address['entry_street_address']) ? $address['entry_street_address'] : '')) . '&nbsp;' . ENTRY_STREET_ADDRESS_TEXT;
              }
              ?>
            </td>
          </tr>
          <?php
          if (ACCOUNT_SUBURB == 'true') {
            ?>
            <tr>
              <td class="main">&nbsp;<?php echo ENTRY_SUBURB; ?></td>
              <td class="main">&nbsp;
                <?php
                if (isset($is_read_only)) {
                  echo (isset($address['entry_suburb']) ? $address['entry_suburb'] : '');
                } else {
                  echo tep_draw_input_field('suburb', (isset($address['entry_suburb']) ? $address['entry_suburb'] : '')) . '&nbsp;' . ENTRY_SUBURB_TEXT;
                }
                ?>
              </td>
            </tr>
            <?php
          }
          ?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_POST_CODE; ?></td>
            <td class="main">&nbsp;
              <?php
              if (isset($is_read_only)) {
                echo (isset($address['entry_postcode']) ? $address['entry_postcode'] : '');
              } else {
                echo tep_draw_input_field('postcode', (isset($address['entry_postcode']) ? $address['entry_postcode'] : ''),'maxlength="10"') . '&nbsp;' . ENTRY_POST_CODE_TEXT;
              }
              ?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_CITY; ?></td>
            <td class="main">&nbsp;
              <?php
              if (isset($is_read_only)) {
                echo (isset($address['entry_city']) ? $address['entry_city'] : '');
              } else {
                echo tep_draw_input_field('city', (isset($address['entry_city']) ? $address['entry_city'] : '')) . '&nbsp;' . ENTRY_CITY_TEXT;
              }
              ?>
            </td>
          </tr>
          <?php
          if (ACCOUNT_STATE == 'true') {
            ?>
            <tr>
              <td class="main">&nbsp;<?php echo ENTRY_STATE; ?></td>
              <td class="main">&nbsp;
                <?php
                if ($address['entry_state'] == '') $address['entry_state'] = sbs_get_zone_name($address['entry_country_id'], $address['entry_zone_id']);
                if (isset($is_read_only)) {
                  echo (isset($address['entry_state']) ? $address['entry_state'] : '');
                } else {
                  echo tep_draw_input_field('state', (isset($address['entry_state']) ? $address['entry_state'] : '')) . '&nbsp;' . ENTRY_STATE_TEXT;
                }
                ?>
              </td>
            </tr>
            <?php
          }
          ?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_COUNTRY; ?></td>
            <td class="main">&nbsp;
              <?php
              if (isset($is_read_only)) {
                echo (isset($address['entry_country_id']) ? tep_get_country_name($address['entry_country_id']) : '');
              } else {
                echo tep_draw_input_field('country', (isset($address['entry_country_id']) ? tep_get_country_name($address['entry_country_id']) : '')) . '&nbsp;' . ENTRY_COUNTRY_TEXT;
              }
              tep_draw_hidden_field('step', '3')
              ?>
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="formAreaTitle"><br>
      <?php echo CATEGORY_CONTACT; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
            <td class="main">&nbsp;
              <?php
              if (isset($is_read_only)) {
                echo (isset($account['customers_telephone']) ? $account['customers_telephone'] : '');
              } else {
                echo tep_draw_input_field('telephone', (isset($account['customers_telephone']) ? $account['customers_telephone'] : '')) . '&nbsp;' . ENTRY_TELEPHONE_NUMBER_TEXT;
              }
  echo tep_draw_hidden_field('gender', (isset($address['entry_gender']) ? $address['entry_gender'] : '' )) . "\n";
  echo tep_draw_hidden_field('dob', (isset($account['customers_dob']) ? $account['customers_dob'] : '' )) . "\n";
  echo tep_draw_hidden_field('fax' , (isset($address['entry_fax']) ? $address['entry_fax'] : '' )) . "\n";
  echo tep_draw_hidden_field('newsletter', (isset($account['customers_newsletter']) ? $account['customers_newsletter'] : '' )) . "\n";
  echo tep_draw_hidden_field('password', '') . "\n";
  echo tep_draw_hidden_field('confirmation', '') . "\n";
  echo tep_draw_hidden_field('zone_id', '') . "\n";
              ?>
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>