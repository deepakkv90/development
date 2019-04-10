<?php
/*
  $Id: validations.php,v 1.1.1.1 2004/03/04 23:39:56 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  function tep_validate_email($email) {
    $valid_address = true;

    $mail_pat = '^(.+)@(.+)$';
    $valid_chars = "[^] \(\)<>@,;:\.\\\"\[]";
    $atom = "$valid_chars+";
    $quoted_user='(\"[^\"]*\")';
    $word = "($atom|$quoted_user)";
    $user_pat = "^$word(\.$word)*$";
    $ip_domain_pat='^\[([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\]$';
    $domain_pat = "^$atom(\.$atom)*$";

    if (eregi($mail_pat, $email, $components)) {
      $user = $components[1];
      $domain = $components[2];
      // validate user
      if (eregi($user_pat, $user)) {
        // validate domain
        if (eregi($ip_domain_pat, $domain, $ip_components)) {
          // this is an IP address
          for ($i=1;$i<=4;$i++) {
            if ($ip_components[$i] > 255) {
              $valid_address = false;
              break;
            }
          }
        }
        else {
          // Domain is a name, not an IP
          if (eregi($domain_pat, $domain)) {
            /* domain name seems valid, but now make sure that it ends in a valid TLD or ccTLD
               and that there's a hostname preceding the domain or country. */
            $domain_components = explode(".", $domain);
            // Make sure there's a host name preceding the domain.
            if (sizeof($domain_components) < 2) {
              $valid_address = false;
            } else {
              $top_level_domain = strtolower($domain_components[sizeof($domain_components)-1]);
              // Allow all 2-letter TLDs (ccTLDs)
              if (eregi('^[a-z][a-z]$', $top_level_domain) != 1) {
                $tld_pattern = '';
                // Get authorized TLDs from text file
                $tlds = file(DIR_WS_INCLUDES . 'tld.txt');
                while (list(,$line) = each($tlds)) {
                  // Get rid of comments
                  $words = explode('#', $line);
                  $tld = trim($words[0]);
                  // TLDs should be 3 letters or more
                  if (eregi('^[a-z]{3,}$', $tld) == 1) {
                    $tld_pattern .= '^' . $tld . '$|';
                  }
                }
                // Remove last '|'
                $tld_pattern = substr($tld_pattern, 0, -1);
                if (eregi("$tld_pattern", $top_level_domain) == 0) {
                    $valid_address = false;
                }
              }
            }
          }
          else {
            $valid_address = false;
          }
        }
      }
      else {
        $valid_address = false;
      }
    }
    else {
      $valid_address = false;
    }
    if ($valid_address && ENTRY_EMAIL_ADDRESS_CHECK == 'true') {
      if (!checkdnsrr($domain, "MX") && !checkdnsrr($domain, "A")) {
        $valid_address = false;
      }
    }
    return $valid_address;
  }
 
  function setFlags() { 
    $version = (defined('INSTALLED_VERSION_TYPE')) ? strtolower(INSTALLED_VERSION_TYPE) : ''; 
   
//    if ($version == 'standard') $_SESSION['is_std'] = true;

    $components_query = tep_db_query("SELECT validation_product, status from " . TABLE_COMPONENTS);
    if (tep_db_num_rows($components_query) > 0) {
      // compare validationProduct to INSTALLED_VERSION_TYPE
      $found = false;
      while ($components = tep_db_fetch_array($components_query)) {
        if ($version == strtolower($components['validation_product'])) {
          $found = true;
          if ($components['status'] == false) {
            if (!isset($_SESSION['is_std'])) $_SESSION['force_registration'] = true;
            break; 
          }
        }
      }
      if (!$found) {
        $_SESSION['force_registration'] = true;
        $_SESSION['new_registration'] = true;    
      }
    } else {
      // empty components table
      $_SESSION['force_registration'] = true;
      $_SESSION['new_registration'] = true; 
    }
    if (isset($_SESSION['continue']) && $_SESSION['continue'] == true) {
      if (isset($_SESSION['force_registration'])) unset($_SESSION['force_registration']);
    }  
  } 
?>