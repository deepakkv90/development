<?php
/*
  $Id: sss_verify.php,v 1.0.0.0 2008/05/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Pwithout the customer entering aublic License
*/

  class sss_verify {
    var $output;
    
    function updateRegistration($email_address, $password, $serial) {
      global $url;
      
      $this->serial = $serial;
      $this->email_address = $email_address;
      $this->password = $password;
      // get the domain name the request is being made from
      $this->validationDomain = (defined('HTTP_CATALOG_SERVER') && substr(HTTP_CATALOG_SERVER, strpos(HTTP_CATALOG_SERVER, '//')+2) != '') ? substr(HTTP_CATALOG_SERVER, strpos(HTTP_CATALOG_SERVER, '//')+2) : $_SERVER['SERVER_NAME'];      
      // sanity check to insure ameil and password are not null.
      if ($this->email_address == '' || $this->password == '') {
        $error = true;
        $_SESSION['update_error'] = true;
        $_SESSION['update_error']['error_message'] = TEXT_ERROR_INAVLID_USERNAME_OR_PASSWORD;
        tep_redirect(FILENAME_SSS_REGISTER, '', 'SSL');
      }      
      // format the XML
      $this->data = $this->updateXML();
/*
echo 'sss_verify::update[' . $this->serial . ']<br><br>';      
echo '<b>Request:</b>' . "\n";
echo "<pre>";
print_r(htmlentities($this->data)) . "\n\n";
echo "</pre>";
*/
        // send the request and get the response     
        $this->response = $this->sendToHost();
/* 
echo '<b>Response:</b>' . "\n";
echo "<pre>";     
print_r(htmlentities($this->response));      
echo "</pre>";
*/
        // validate the response
        $owner_name = '';
        $billable_name = '';
        $return_code = '';
        $error_message = ''; 
        $verified = false;
        if (ereg('<Status>', $this->response)) { 
          $status = ereg('<returnCode>(.*)</returnCode>', $this->response, $regs);
          $status = $regs[1];   
          if ($status == 'Success') {
            $verified = true;
            $return_code = 'Success';
            $error_message = ''; 
            $owner_name = ereg('<ownerName>(.*)</ownerName>', $this->response, $regs);
            $owner_name = $regs[1];     
            $billable_name = ereg('<billableName>(.*)</billableName>', $this->response, $regs);
            $billable_name = $regs[1];
            $_SESSION['verify_array']['owner_name'] = $owner_name;
            $_SESSION['verify_array']['billable_name'] = $billable_name; 
          } else {
            $return_code = ereg('<returnCode>(.*)</returnCode>', $this->response, $regs);
            $return_code = $regs[1];
            $error_message = ereg('<errorMessage>(.*)</errorMessage>', $this->response, $regs);
            $error_message = $regs[1];
          }
        } else {
          // show the API error
          $return_code = 'Error[00]';
          $error_message = 'Status not returned in XML response.<br>cURL Host: ' . $url . '<br>' . $this->response;
        }
        // setup the returned data array
        $return_array = array('verified' => $verified, 
                              'serial_1' => $this->serial, 
                              'owner_name' => $owner_name,
                              'billable_name' => $billable_name,
                              'return_code' => $return_code,
                              'error_message' => $error_message);
        return $return_array;

    }
    
    function updateXML() {
      $data_string  = '<SimpleSerializationRequest requestAction="updateSerial">' . "\n";
      $data_string .= '  <updateSerial>' . "\n";
      $data_string .= '    <serial1>' . $this->serial . '</serial1>' . "\n";
      $data_string .= '    <emailAddress>' . $this->email_address . '</emailAddress>' . "\n";
      $data_string .= '    <password>' . $this->password . '</password>' . "\n";
      $data_string .= '    <validationDomain>' . $this->validationDomain . '</validationDomain>' . "\n";
      $data_string .= '  </updateSerial>' . "\n";
      $data_string .= '</SimpleSerializationRequest>' . "\n";
      
      return $data_string;
    }    
    

    function verifySerial($serial = '') {
      global $url;
      
      $this->serial = $serial;
      // sanity check to insure serial is not null.
      if ($this->serial == '') {
        $error = true;
        $_SESSION['new_registration'] = true;
        tep_redirect(FILENAME_SSS_VALIDATE, '', 'SSL');
      }
      // skip validation if last checked < 1 day
      $component_query = tep_db_query("SELECT * from " . TABLE_COMPONENTS . " WHERE serial_1 = '" . $this->serial . "' LIMIT 1");
      if (tep_db_num_rows($component_query) > 0) $_SESSION['new_registration'] = false;
      $component = tep_db_fetch_array($component_query);
      $last_validated = strtotime($component['last_validated']);
      if (( $_SESSION['new_registration'] != true && ((strtotime(date('Y-m-d', strtotime('+1 day'))) - $last_validated) <= 86400) ) &&
          (!$_SESSION['force_registration'])) { 
        // its been less than 1 day since last validation 
        $this->verified = true;
        $return_code = 'Success';
        $error_message = '';
      } else {
        if (isset($_SESSION['continue'])) unset($_SESSION['continue']);
        // its been more than 1 day since last validation
        $this->verified = false;
        $error = false;      
        // get the domain name the request is being made from
        $this->validationDomain = (defined('HTTP_CATALOG_SERVER') && substr(HTTP_CATALOG_SERVER, strpos(HTTP_CATALOG_SERVER, '//')+2) != '') ? substr(HTTP_CATALOG_SERVER, strpos(HTTP_CATALOG_SERVER, '//')+2) : $_SERVER['SERVER_NAME']; 
        // get the validationProduct
        $this->validationProduct = (defined('INSTALLED_VERSION_TYPE')) ? INSTALLED_VERSION_TYPE : '';     
        // format the XML
        $this->data = $this->formatXML(); 
/*
echo 'sss_verify::serial[' . $this->serial . ']<br><br>';      
echo '<b>Request:</b>' . "\n";
echo "<pre>";
print_r(htmlentities($this->data)) . "\n\n";
echo "</pre>";
*/
        // send the request and get the response     
        $this->response = $this->sendToHost();
/*
echo 'new_reg[' . $_SESSION['new_registration'] . ']<br>';  
echo '<b>Response:</b>' . "\n";
echo "<pre>";     
print_r(htmlentities($this->response));      
echo "</pre>";
*/
        // validate the response
        $return_code = '';
        $error_message = '';
        $owner_name = '';
        $billable_name = '';
        $grace_days = '';
        $this->expiration_date = '';
        $this->validation_product = '';
        if (ereg('<Status>', $this->response)) { 
          $status = ereg('<returnCode>(.*)</returnCode>', $this->response, $regs);
          $status = $regs[1];   
          $validation_product = ereg('<validationProduct>(.*)</validationProduct>', $this->response, $regs);
          $this->validation_product = $regs[1]; 
          if ($status == 'Success') {
            $this->verified = true;
            $return_code = 'Success';
            $error_message = ''; 
            $owner_name = ereg('<ownerName>(.*)</ownerName>', $this->response, $regs);
            $owner_name = $regs[1];           
            $billable_name = ereg('<billableName>(.*)</billableName>', $this->response, $regs);
            $billable_name = $regs[1]; 
            $grace_days = ereg('<graceDays>(.*)</graceDays>', $this->response, $regs);
            $grace_days = $regs[1];
            $expiration_date = ereg('<expirationDate>(.*)</expirationDate>', $this->response, $regs);
            $this->expiration_date = $regs[1];
          } else {
            $return_code = ereg('<returnCode>(.*)</returnCode>', $this->response, $regs);
            $return_code = $regs[1];
            $error_message = ereg('<errorMessage>(.*)</errorMessage>', $this->response, $regs);
            $error_message = $regs[1];
          }
        } else {
          // if status=true, ignore API error and proceed to admin index
          $components = tep_db_fetch_array(tep_db_query("SELECT status from " . TABLE_COMPONENTS . " WHERE validation_product = '" . $this->validationProduct . "'"));
          if ($components['status'] == true) {
            $this->verified = true;
            $_SESSION['new_registration'] = false;
            $return_code = 'Success';
            $error_message = '';
          } else {
            // else show the API error
            $return_code = 'Error[00]';
            $error_message = 'Status not returned in XML response.<br>cURL Host: ' . $url . '<br>' . $this->response; 
          }
        }
        // update the components table
        $this->writeComponents();
      }
      // setup the returned data array
      $return_array = array('verified' => $this->verified, 
                            'serial_1' => $this->serial,
                            'owner_name' => $owner_name,
                            'billable_name' => $billable_name,
                            'grace_days' => $grace_days,
                            'return_code' => $return_code,
                            'error_message' => $error_message);
      return $return_array;
    }
    
    function formatXML() {
      global $language;
      
      $data_string  = '<SimpleSerializationRequest requestAction="validateSerial">' . "\n";
      $data_string .= '  <validateSerial>' . "\n";
      $data_string .= '    <serial1>' . $this->serial . '</serial1>' . "\n";
      $data_string .= '    <validationDomain>' . $this->validationDomain . '</validationDomain>' . "\n";
      $data_string .= '    <validationProduct>' . $this->validationProduct . '</validationProduct>' . "\n";
      $data_string .= '    <newRegistration>' . $_SESSION['new_registration'] . '</newRegistration>' . "\n";
      $data_string .= '    <language>' . $language . '</language>' . "\n";   
      $data_string .= '  </validateSerial>' . "\n";
      $data_string .= '</SimpleSerializationRequest>' . "\n";
      
      return $data_string;
    }

    function writeComponents() {
      // write the serial to the components table
      if (($_SESSION['new_registration'] == true) && $this->verified == true) { 
        $sql_data_array = array('serial_1' => $this->serial,
                                'serial_2' => '',
                                'status' => 1,
                                'validation_product' => $this->validation_product,
                                'expiration_date' => $this->expiration_date,
                                'last_validated' => 'now()'
                                );        
        tep_db_perform(TABLE_COMPONENTS, $sql_data_array);
        $components_id = tep_db_insert_id();
      } else {
        $sql_data_array = array('serial_1' => $this->serial,
                                'serial_2' => '',
                                'status' => $this->verified,
                                'expiration_date' => $this->expiration_date,
                                'last_validated' => 'now()'
                                );     
        tep_db_perform(TABLE_COMPONENTS, $sql_data_array, 'update', "validation_product = '" . $this->validation_product . "'");
      }                              
    }
    
    function sendToHost($encode = false) {
      global $url;
      //$url = 'https://api.creloaded.com/sss/sssAPI.php';
      $url = 'https://api.creloaded.com/sss/sssAPI.php'; 
      $port = '443';
      $method = 'POST';
      $data = $this->data;
      // convert characters to proper format for post
      if ($encode == true) {
        $data = urlencode($data);
      }
      // setup the cURL connection
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      //curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_POST, 1);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type", "application/x-www-form-urlencoded"));
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  
     // added support for curl proxy
      if (defined('CURL_PROXY_HOST') && defined('CURL_PROXY_PORT') && CURL_PROXY_HOST != '' && CURL_PROXY_PORT != '') {
        curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, TRUE);
        curl_setopt($curl, CURLOPT_PROXY, CURL_PROXY_HOST . ":" . CURL_PROXY_PORT);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
      }
      if (defined('CURL_PROXY_USER') && defined('CURL_PROXY_PASSWORD') && CURL_PROXY_USER != '' && CURL_PROXY_PASSWORD != '') {
        curl_setopt($curl, CURLOPT_PROXYUSERPWD, CURL_PROXY_USER . ':' . CURL_PROXY_PASSWORD);
      }
      ob_start();
      curl_exec($curl);
      $result = ob_get_contents();
      ob_end_clean(); 
      if (curl_errno($curl)) {
        $result = curl_errno($curl) . ' - cURL ' . curl_error($curl);    
      }
      curl_close($curl);
      return $result;
    } 
  }
?>