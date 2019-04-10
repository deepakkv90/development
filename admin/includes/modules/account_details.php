<?php
/*
  $Id: account_details.php,v 2.0 2008/05/05 00:36:41 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

$is_read_only = isset($is_read_only) ? $is_read_only : false;
$error = isset($error) ? $error : false;
$newsletter_array = array(array('id' => '1',
                                'text' => ENTRY_NEWSLETTER_YES),
                          array('id' => '0',
                                'text' => ENTRY_NEWSLETTER_NO));
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

// prepare the input fields, if the $account is availablem use it
// otherwise load the POST variables into a dummy $account
if (!isset($account)) {
  $account['customers_gender'] = isset($gender) ? $gender : '';
  $account['customers_firstname'] = isset($firstname) ? $firstname : '';
  $account['customers_lastname'] = isset($lastname) ? $lastname : '';
  $account['customers_dob'] = isset($dob) ? $dob : '';
  $account['customers_email_address'] = isset($email_address) ? $email_address : '';
  $account['entry_company'] = isset($company) ? $company : '';
  $account['entry_street_address'] = isset($street_address) ? $street_address : '';
  $account['entry_suburb'] = isset($suburb) ? $suburb : '';
  $account['entry_postcode'] = isset($postcode) ? $postcode : '';
  $account['entry_city'] = isset($city) ? $city : '';
//  $account['entry_state'] = isset($state) ? $state : '';
  $account['entry_country_id'] = isset($country) ? $country : 0;
  $account['entry_zone_id'] = isset($zone_id) ? $zone_id : 0;
  $account['customers_telephone'] = isset($telephone) ? $telephone : '';
  $account['customers_fax'] = isset($fax) ? $fax : '';
  $account['customers_newsletter'] = isset($newsletter) ? $newsletter : '1';
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <?php
          if (!isset($account['customers_gender'])) {
            $account['customers_gender'] = '';
          }
          if (ACCOUNT_GENDER == 'true') {
            $male = ($account['customers_gender'] == 'm') ? true : false;
            $female = ($account['customers_gender'] == 'f') ? true : false;
            ?>
            <tr>
              <td class="main">&nbsp;<?php echo ENTRY_GENDER; ?></td>
              <td class="main">&nbsp;
                <?php
                if ($is_read_only) {
                  echo ($account['customers_gender'] == 'm') ? MALE : FEMALE;
                } elseif ($error) {
                  if ($entry_gender_error) {
                    echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_ERROR;
                  } else {
                    echo ($gender == 'm') ? MALE : FEMALE;
                    echo tep_draw_hidden_field('gender');
                  }
                } else {
                  echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_TEXT;
                }
                ?>
              </td>
            </tr>
            <?php
          }
          ?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main">&nbsp;
              <?php
              if ($is_read_only) {
                echo $account['customers_firstname'];
              } elseif ($error) {
                if ($entry_firstname_error) {
                  echo tep_draw_input_field('firstname') . '&nbsp;' . ENTRY_FIRST_NAME_ERROR;
                } else {
                  echo $firstname . tep_draw_hidden_field('firstname');
                }
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
              if ($is_read_only) {
                echo $account['customers_lastname'];
              } elseif ($error) {
                if ($entry_lastname_error) {
                  echo tep_draw_input_field('lastname') . '&nbsp;' . ENTRY_LAST_NAME_ERROR;
                } else {
                  echo $lastname . tep_draw_hidden_field('lastname');
                }
              } else {
                echo tep_draw_input_field('lastname', (isset($account['customers_lastname']) ? $account['customers_lastname'] : '')) . '&nbsp;' . ENTRY_LAST_NAME_TEXT;
              }
              ?>
            </td>
          </tr>
          <?php
          if (ACCOUNT_DOB == 'true') {
            ?>
            <tr>
              <td class="main">&nbsp;<?php echo ENTRY_DATE_OF_BIRTH; ?></td>
              <td class="main">&nbsp;
                <?php
                if ($is_read_only) {
                  echo tep_date_short($account['customers_dob']);
                } elseif ($error) {
                  if ($entry_date_of_birth_error) {
                    echo tep_draw_input_field('dob') . '&nbsp;' . ENTRY_DATE_OF_BIRTH_ERROR;
                  } else {
                    echo $dob . tep_draw_hidden_field('dob');
                  }
                } else {
                  echo tep_draw_input_field('dob', (isset($account['customers_dob']) ? tep_date_short($account['customers_dob']) : '')) . '&nbsp;' . ENTRY_DATE_OF_BIRTH_TEXT;
                }
                ?>
              </td>
            </tr>
            <?php
          }
          ?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main">&nbsp;
              <?php
              if ($is_read_only) {
                echo $account['customers_email_address'];
              } elseif ($error) {
                if ($entry_email_address_error) {
                  echo tep_draw_input_field('email_address') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR;
                } elseif ($entry_email_address_check_error) {
                  echo tep_draw_input_field('email_address') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_CHECK_ERROR;
                } elseif ($entry_email_address_exists) {
                  echo tep_draw_input_field('email_address') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR_EXISTS;
                } else {
                  echo $email_address . tep_draw_hidden_field('email_address');
                }
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
          <td class="main">
            <table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main">&nbsp;<?php echo ENTRY_COMPANY; ?></td>
                <td class="main">&nbsp;
                  <?php
                  if ($is_read_only) {
                    echo $account['entry_company'];
                  } elseif ($error) {
                    if ($entry_company_error) {
                      echo tep_draw_input_field('company') . '&nbsp;' . ENTRY_COMPANY_ERROR;
                    } else {
                      echo $company . tep_draw_hidden_field('company');
                    }
                  } else {
                    echo tep_draw_input_field('company', (isset($account['entry_company']) ? $account['entry_company'] : ''));
                  }
                  ?>
                </td>
              </tr>      
              <tr>
                <td class="main">&nbsp;<?php echo ENTRY_COMPANY_TAX_ID; ?></td>
                <td class="main">&nbsp; 
                  <?php
                  if ($is_read_only) {
                    echo $account['entry_company_tax_id'];
                  } elseif ($error) {
                    if ($entry_company_tax_id_error) {
                      echo tep_draw_input_field('entry_company_tax_id') . '&nbsp;' . ENTRY_COMPANY_TAX_ID_ERROR;
                    } else {
                      echo $entry_company_tax_id . tep_draw_hidden_field('entry_company_tax_id');
                    }
                  } else {
                    echo tep_draw_input_field('entry_company_tax_id', (isset($account['entry_company_tax_id']) ? $account['entry_company_tax_id'] : ''));
                  }
                  ?>
                </td>
              </tr>   
            </table>
          </td>
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
              if ($is_read_only) {
                echo $account['entry_street_address'];
              } elseif ($error) {
                if ($entry_street_address_error) {
                  echo tep_draw_input_field('street_address') . '&nbsp;' . ENTRY_STREET_ADDRESS_ERROR;
                } else {
                  echo $street_address . tep_draw_hidden_field('street_address');
                }
              } else {
                echo tep_draw_input_field('street_address', (isset($account['entry_street_address']) ? $account['entry_street_address'] : '')) . '&nbsp;' . ENTRY_STREET_ADDRESS_TEXT;
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
                if ($is_read_only) {
                  echo $account['entry_suburb'];
                } elseif ($error) {
                  if ($entry_suburb_error) {
                    echo tep_draw_input_field('suburb') . '&nbsp;' . ENTRY_SUBURB_ERROR;
                  } else {
                    echo $suburb . tep_draw_hidden_field('suburb');
                  }
                } else {
                  echo tep_draw_input_field('suburb', (isset($account['entry_suburb']) ? $account['entry_suburb'] : '')) . '&nbsp;' . ENTRY_SUBURB_TEXT;
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
              if ($is_read_only) {
                echo $account['entry_postcode'];
              } elseif ($error) {
                if ($entry_post_code_error) {
                  echo tep_draw_input_field('postcode','','maxlength="10"') . '&nbsp;' . ENTRY_POST_CODE_ERROR;
                } else {
                  echo $postcode . tep_draw_hidden_field('postcode');
                }
              } else {
                echo tep_draw_input_field('postcode', (isset($account['entry_postcode']) ? $account['entry_postcode'] : ''),'maxlength="10"') . '&nbsp;' . ENTRY_POST_CODE_TEXT;
              }
              ?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_CITY; ?></td>
            <td class="main">&nbsp;
              <?php
              if ($is_read_only) {
                echo $account['entry_city'];
              } elseif ($error) {
                if ($entry_city_error) {
                  echo tep_draw_input_field('city') . '&nbsp;' . ENTRY_CITY_ERROR;
                } else {
                  echo $city . tep_draw_hidden_field('city');
                }
              } else {
                echo tep_draw_input_field('city', (isset($account['entry_city']) ? $account['entry_city'] : '')) . '&nbsp;' . ENTRY_CITY_TEXT;
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
                if (!isset($country)) $country = 0;
                if (!isset($zone_id)) $zone_id = 0;
                $state = sbs_get_zone_name($country, $zone_id);
                if ($is_read_only) {
                  echo sbs_get_zone_name($account['entry_country_id'], $account['entry_zone_id']); 
                } elseif ($error) {
                  if ($entry_state_error) {
                    if ($entry_state_has_zones) {
                      $zones_array = array();
                      $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' order by zone_name");
                      while ($zones_values = tep_db_fetch_array($zones_query)) {
                        $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
                      }
                      echo tep_draw_pull_down_menu('state', $zones_array) . '&nbsp;' . ENTRY_STATE_ERROR;
                    } else {
                      echo tep_draw_input_field('state') . '&nbsp;' . ENTRY_STATE_ERROR;
                    }
                  } else {
                    if ($error) {
                      echo tep_draw_input_field('state') . tep_draw_hidden_field('zone_id') . tep_draw_hidden_field('state');
                    }
                  }
                } else {
                  echo tep_draw_input_field('state', (isset($account['entry_state']) ? sbs_get_zone_name($account['entry_country_id'], $account['entry_zone_id'], $account['entry_state']) : '')) . '&nbsp;' . ENTRY_STATE_TEXT;
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
              $account['entry_country_id'] = STORE_COUNTRY;
              if ($is_read_only) {    
                echo tep_get_country_name($account['entry_country_id']); 
              } elseif ($error) {
                if ($entry_country_error) {
                  echo sbs_get_country_list('country') . '&nbsp;' . ENTRY_COUNTRY_ERROR;
                } else {
                  echo sbs_get_country_list('country');
                }
              } else {
                echo sbs_get_country_list('country', $account['entry_country_id']) . '&nbsp;' . ENTRY_COUNTRY_TEXT;
              }
              ?>
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="formAreaTitle"><br><?php echo CATEGORY_CONTACT; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
            <td class="main">&nbsp;
              <?php
              if ($is_read_only) {
                echo $account['customers_telephone'];
              } elseif ($error) {
                if ($entry_telephone_error) {
                  echo tep_draw_input_field('telephone') . '&nbsp;' . ENTRY_TELEPHONE_NUMBER_ERROR;
                } else {
                  echo $telephone . tep_draw_hidden_field('telephone');
                }
              } else {
                echo tep_draw_input_field('telephone', (isset($account['customers_telephone']) ? $account['customers_telephone'] : '')) . '&nbsp;' . ENTRY_TELEPHONE_NUMBER_TEXT;
              }
              ?>
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_FAX_NUMBER; ?></td>
            <td class="main">&nbsp;
              <?php
              if ($is_read_only) {
                echo $account['customers_fax'];
              } elseif ($error) {
                if ($entry_fax_error) {
                  echo tep_draw_input_field('fax');
                } else {
                  echo $fax . tep_draw_hidden_field('fax');
                }
              } else {
                echo tep_draw_input_field('fax', (isset($account['customers_fax']) ? $account['customers_fax'] : ''));
              }
              ?>
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="formAreaTitle"><br><?php echo CATEGORY_OPTIONS; ?></td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_NEWSLETTER; ?></td>
            <td class="main">&nbsp;
              <?php
              if ($is_read_only) {
                if ($account['customers_newsletter'] == '1') {
                  echo ENTRY_NEWSLETTER_YES;
                } else {
                  echo ENTRY_NEWSLETTER_NO;
                }
              } elseif ($processed) {
                if ($newsletter == '1') {
                  echo ENTRY_NEWSLETTER_YES;
                } else {
                  echo ENTRY_NEWSLETTER_NO;
                }
                echo tep_draw_hidden_field('newsletter');
              } else {
                echo tep_draw_pull_down_menu('newsletter', $newsletter_array, (isset($account['customers_newsletter']) ? $account['customers_newsletter'] : '')) . '&nbsp;' . ENTRY_NEWSLETTER_TEXT;
              }
              ?>
            </td>
          </tr>
          <tr>
          <td class="main" valign="top"><?php echo ENTRY_CUSTOMERS_ACCESS_GROUP; ?></td>
          <td class="main">
            <?php
            $existing_customers_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " WHERE group_status = '1' and group_access = '1' order by customers_group_id ");
            $index = 0;
            while ($existing_customers =  tep_db_fetch_array($existing_customers_query)) {
              $existing_customers_array[] = array("id" => $existing_customers['customers_group_id'], "text" => $existing_customers['customers_group_name']);
              ++$index;
            }
            $tmp_customers_access_group = array();
            if (isset($_POST['customers_access_group'])) {
              $tmp_customers_access_group = $_POST['customers_access_group'];
            }   
            echo  tep_draw_checkbox_field('customers_access_group[]', 'G',(in_array('G',$tmp_customers_access_group) ? ' Checked' : '')) . ENTRY_CUSTOMERS_GUEST_GROUP . '<br>';
            foreach ($existing_customers_array as $group_array) {
              echo  tep_draw_checkbox_field('customers_access_group[]', $group_array['id'], (in_array($group_array['id'],$tmp_customers_access_group) ? ' Checked' : '')) . $group_array['text'] . '<br>';
            }
            ?>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <?php
  echo tep_draw_hidden_field('password', tep_create_hard_pass());
  echo tep_draw_hidden_field('send_password', '1');  
  /*
  if (!$is_read_only) {
    ?>
    <tr>
      <td class="formAreaTitle"><br><?php echo CATEGORY_PASSWORD; ?></td>
    </tr>
    <tr>
      <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
        <tr>
          <td class="main"><table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td class="main">&nbsp;<?php echo ENTRY_PASSWORD; ?></td>
              <td class="main">&nbsp;<?php
                if ($error) {
                  if ($entry_password_error) {
                    echo tep_draw_password_field('password') . '&nbsp;' . ENTRY_PASSWORD_ERROR;
                  } else {
                    echo PASSWORD_HIDDEN . tep_draw_hidden_field('password') . tep_draw_hidden_field('confirmation');
                  }
                } else {
                  echo tep_draw_password_field('password');
                }
                ?>
              </td>
            </tr>
            <?php 
            if ( (!$error) || ($entry_password_error) ) {
              ?>
              <tr>
                <td class="main">&nbsp;<?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
                <td class="main">&nbsp;<?php echo tep_draw_password_field('confirmation'); ?></td>
              </tr>
              <tr>
                <td class="main">&nbsp;<?php echo ENTRY_SEND_PASSWORD; ?></td>
                <td class="main">&nbsp;<?php echo tep_draw_checkbox_field('send_password', '1', true); ?></td>
              </tr>
              <?php
            }
            ?>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <?php
  }
  */
  ?>
  
  <!-- Shipping and Payment option Code -->
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
    <td class="formAreaTitle"><?php include_once(DIR_WS_LANGUAGES . $language . '/modules.php');
      echo HEADING_TITLE_MODULES_PAYMENT; ?>
    </td>
  </tr>
  <tr>
    <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td class="main" colspan="2">
          <?php 
          if ($processed == true) {
            if ($_REQUEST['customers_payment_settings'] == '1') {
              echo ENTRY_CUSTOMERS_PAYMENT_SET ;
              echo ' : ';
            } else {
              echo ENTRY_CUSTOMERS_PAYMENT_DEFAULT;
            }
            echo tep_draw_hidden_field('customers_payment_settings');
          } else { // $processed != true
            echo tep_draw_radio_field('customers_payment_settings', '1', (isset($cInfo->customers_payment_allowed) && tep_not_null($cInfo->customers_payment_allowed) ? true : false )) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_PAYMENT_SET . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_payment_settings', '0', (isset($cInfo->customers_payment_allowed) &&tep_not_null($cInfo->customers_payment_allowed) ? false : true )) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_PAYMENT_DEFAULT ; 
          } 
          ?>
        </td>
      </tr>
      <?php 
      if ($processed != true) {
        if (isset($cInfo->customers_payment_allowed)) {
          $payments_allowed = explode (";",$cInfo->customers_payment_allowed);
        } else {
          $payments_allowed = array();
        }
        $module_active = explode (";",MODULE_PAYMENT_INSTALLED);     
        $installed_modules = array();
        if (!isset($directory_array)) {
          $directory_array = array();
        }
        for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++) {
          $file = $directory_array[$i];
          if (in_array ($directory_array[$i], $module_active)) {
            include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/payment/' . $file);
            include($module_directory . $file);
            $class = substr($file, 0, strrpos($file, '.'));
            if (tep_class_exists($class)) {
              $module = new $class;
              if ($module->check() > 0) {
                $installed_modules[] = $file;
              }
            } // end if (tep_class_exists($class))
            ?>
            <tr>
              <td class="main" colspan="2"><?php echo tep_draw_checkbox_field('payment_allowed[' . $i . ']', $module->code.".php" , (in_array ($module->code.".php", $payments_allowed)) ?  1 : 0); ?>&#160;&#160;<?php echo $module->title; ?></td>
            </tr>
            <?php
          } // end if (in_array ($directory_array[$i], $module_active))
        } // end for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++)
        ?>
        <tr>
          <td class="main" colspan="2" style="padding-left: 30px; padding-right: 10px; padding-top: 10px;"><?php echo ENTRY_CUSTOMERS_PAYMENT_SET_EXPLAIN ?></td>
        </tr>
        <?php
      } else { // end if ($processed != true)
        ?>
        <tr>
          <td class="main" colspan="2">
            <?php 
            if ($_REQUEST['customers_payment_settings'] == '1') {
              echo $customers_payment_allowed;
            } else {
              echo ENTRY_CUSTOMERS_PAYMENT_DEFAULT;
            }
            echo tep_draw_hidden_field('customers_payment_allowed'); 
            ?>
          </td>
        </tr>
        <?php
      } // end else: $processed == true
      ?>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
    <td class="formAreaTitle"><?php echo HEADING_TITLE_MODULES_SHIPPING; ?></td>
  </tr>
  <tr>
    <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td class="main" colspan="2">
          <?php 
          if ($processed == true) {
            if ($_REQUEST['customers_shipment_settings'] == '1') {
              echo ENTRY_CUSTOMERS_SHIPPING_SET ;
              echo ' : ';
            } else {
              echo ENTRY_CUSTOMERS_SHIPPING_DEFAULT;
            }
            echo tep_draw_hidden_field('customers_shipment_settings');
          } else { // $processed != true
            echo tep_draw_radio_field('customers_shipment_settings', '1', (isset($cInfo->customers_shipment_allowed) && tep_not_null($cInfo->customers_shipment_allowed) ? true : false )) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_SHIPPING_SET . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_shipment_settings', '0', (isset($cInfo->customers_shipment_allowed) && tep_not_null($cInfo->customers_shipment_allowed) ? false : true )) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_SHIPPING_DEFAULT ; 
          } 
          ?>
        </td>
      </tr>
      <?php 
      if ($processed != true) {
        if (isset($cInfo->customers_shipment_allowed)) {
          $shipment_allowed = explode (";",$cInfo->customers_shipment_allowed);
        } else {
          $shipment_allowed = array();
        }
        $ship_module_active = explode (";",MODULE_SHIPPING_INSTALLED);
        $installed_shipping_modules = array();
        if (!isset($ship_directory_array)) {
          $ship_directory_array = array();
        }
        for ($i = 0, $n = sizeof($ship_directory_array); $i < $n; $i++) {
          $file = $ship_directory_array[$i];
          if (in_array ($ship_directory_array[$i], $ship_module_active)) {
            include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/shipping/' . $file);
            include($ship_module_directory . $file);
            $ship_class = substr($file, 0, strrpos($file, '.'));
            if (tep_class_exists($ship_class)) {
              $ship_module = new $ship_class;
              if ($ship_module->check() > 0) {
                $installed_shipping_modules[] = $file;
              }     
            } // end if (tep_class_exists($ship_class))
            ?>
            <tr>
              <td class="main" colspan="2"><?php echo tep_draw_checkbox_field('shipping_allowed[' . $i . ']', $ship_module->code.".php" , (in_array ($ship_module->code.".php", $shipment_allowed)) ?  1 : 0); ?>&#160;&#160;<?php echo $ship_module->title; ?></td>
            </tr>
            <?php
          } // end if (in_array ($ship_directory_array[$i], $ship_module_active))
        } // end for ($i = 0, $n = sizeof($ship_directory_array); $i < $n; $i++)
        ?>
        <tr>
          <td class="main" colspan="2" style="padding-left: 30px; padding-right: 10px; padding-top: 10px;"><?php echo ENTRY_CUSTOMERS_SHIPPING_SET_EXPLAIN ?></td>
        </tr>
        <?php
      } else { // end if ($processed != true)
        ?>
        <tr>
          <td class="main" colspan="2">
            <?php 
            if ($_REQUEST['customers_shipment_settings'] == '1') {
              echo $customers_shipment_allowed;
            } else {
              echo ENTRY_CUSTOMERS_SHIPPING_DEFAULT;
            }
            echo tep_draw_hidden_field('customers_shipment_allowed'); 
            ?>
          </td>
        </tr>
        <?php
      } // end else: $processed == true
      ?>
    </table></td>
  </tr>  
</table>