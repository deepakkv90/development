<?php
/*
  $Id: buysafe.php,v 1.0.0.0 2007/11/09 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_ADDONS_BUYSAFE_TITLE', 'buySAFE');
  if (strpos(STORE_OWNER_EMAIL_ADDRESS, '<')) {
    $store_email = substr(strstr(htmlentities(STORE_OWNER_EMAIL_ADDRESS), htmlentities('<')), 4, -4);
  } else {
    $store_email = STORE_OWNER_EMAIL_ADDRESS;
  }
  $http_server = (defined('HTTP_CATALOG_SERVER')) ? HTTP_CATALOG_SERVER : '';
  define('MODULE_ADDONS_BUYSAFE_DESCRIPTION', '<p><b>buySAFE Bonding</b><br><b>Convert more of your shoppers to buyers with buySAFE</b><br>The buySAFE Bonded Merchant Seal and Bond displayed on a website is PROVEN to make more of the visitors to your website buy from you by giving them complete peace of mind.</p><p><b><u>As a buySAFE Bonded Merchant, you\'ll enjoy: </u></b></p><ul>  <li>Increase in website conversion - 6.8% higher on average.</li>  <li>Increased profitability - net profits normally increase by over 20%</li>  <li>A major competitive advantage - buySAFE is exclusive, only the best can qualify to be bonded.</li></ul><p><b>Introductory Offer for CRE Loaded Merchants</b><br>Try buySAFE and get TWO months of hosting FREE!<br><b>Visit <a target="_blank" href="http://www.buysafe.com/offerCRE2"><font style="font-weight: bold;" color="#0033CC">www.buySAFE.com/offerCRE2</font></a> for details!</b></p><b>3 Steps to buySAFE</b><br>1. Enable the module<br>2. <a href="https://www.buysafe.com/web/login/registrationoptions.aspx?pfNameFull=' . STORE_OWNER . '&pfEmail=' . $store_email . '&pfStoreUrl=' . $http_server . DIR_WS_CATALOG . '&pfStoreName=' . STORE_NAME . '&pfMspId=59" tager="_blank" style="text-decoration:underline">Apply for buySAFE</a><br>3. Authenticate Store');
  define('SUB_TITLE_TOTAL', 'Total:');
?>