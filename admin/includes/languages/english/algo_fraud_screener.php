<?php
/*
  $Id: orders.php,v 1.25 2003/06/20 00:28:44 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

// AlgoZone - Algozone Fraud Screen Tool definitions

define('ALGO_FS_DISTANCE', '<b>Distance(mi/km)</b> ');
define('ALGO_FS_DISTANCE_M', '<b>Distance(m) </b>');
define('ALGO_FS_DISTANCE_K', '<b>Distance(k) </b>');
define('ALGO_FS_COUNTRY', '<b>Country Match  </b>');
define('ALGO_FS_CODE', '<b>Country Code  </b>');
define('ALGO_FS_FREE_EMAIL', '<b>Free Email  </b>');
define('ALGO_FS_ANONYMOUS', '<b>Anonymous Proxy  </b>');
define('ALGO_FS_SCORE', '<b>Score  </b>');
define('ALGO_FS_FRAUD_LEVEL', '<b>Fraud Level  </b>');
define('ALGO_FS_BIN_MATCH', '<b>Bin Match  </b>');
define('ALGO_FS_BANK_COUNTRY', '<b>Bank Country  </b>');
define('ALGO_FS_ERR', '<b>Error  </b>');
define('ALGO_FS_PROXY_LEVEL', '<b>Proxy Level  </b>');
define('ALGO_FS_SPAM', '<b>Spam Level  </b>');
define('ALGO_FS_BANK_NAME', '<b>Bank Name  </b>');
define('ALGO_FS_BANK_NAME_MATCH', '<b>Bank Name Match  </b>');
define('ALGO_FS_BANK_PHONE', '<b>Bank Phone  </b>');
define('ALGO_FS_BANK_PHONE_MATCH', '<b>Bank Phone Match  </b>');
define('ALGO_FS_IP', '<b>IP Address  </b>');
define('ALGO_FS_IP_ISP', '<b>ISP  </b>');
define('ALGO_FS_IP_ISP_ORG', '<b>ISP Org  </b>');
define('ALGO_FS_IP_CITY', '<b>City   </b>');
define('ALGO_FS_IP_REGION', '<b>Region   </b>');
define('ALGO_FS_IP_LATITUDE', '<b>Latitude   </b>');
define('ALGO_FS_IP_LONGITUDE', '<b>Longitude   </b>');
define('ALGO_FS_ALGOZONE', '<b>*NOTE:  You need to be subscribed to Premium Services at AlgoZone.com for the following fields </b>');
define('ALGO_FS_HI_RISK', '<b>High Risk Country  </b>');
define('ALGO_FS_CUST_PHONE', '<b>Phone Match  </b>');
define('ALGO_FS_DETAILS', 'See <a href="http://www.algozone.com/" target="_blank"><u>AlgoZone.com</u></a> for explanation of fields');

define('ALGO_FS_BREQUEST', 'Verify Bank Info'); 
define('ALGO_HELP_PREM_OPT', '*** - values available with fraud screen paid service'); 

define('FS_HELP_DISTANCE', 'Distance from IP address to Billing Location in miles/kilometers (large distance == higher risk)');
define('FS_HELP_COUNTRY_MATCH', 'Whether country of IP address matches billing address country (mismatch == higher risk)');
define('FS_HELP_COUNTRY_CODE', 'Country Code of the IP address');
define('FS_HELP_EMAIL','Whether e-mail is from free e-mail provider (free e-mail == higher risk)');
define('FS_HELP_ANONYMOUS_IP','Whether IP address is Anonymous Proxy (anonymous proxy == very high risk)');
define('FS_HELP_BANK_COUNTRY','Country Code of the bank which issued the credit card based on BIN number');
define('FS_HELP_BIN_MATCH','Whether country of issuing bank based on BIN number matches billing address country');
define('FS_HELP_BANK_NAME_MATCH','Whether name of issuing bank matches inputed binName. A return value of Yes provides a positive indication that cardholder is in possession of credit card.');
define('FS_HELP_BANK_NAME','Name of the bank which issued the credit card based on BIN number');
define('FS_HELP_BANK_PHONE_MATCH','Whether customer service phone number matches inputed binPhone. A return value of Yes provides a positive indication that cardholder is in possession of credit card.');
define('FS_HELP_BANK_PHONE','Customer service phone number listed on back of credit card');
define('FS_HELP_PHONE_IN_BILLING_LOC','Whether the customer phone number is in the billing location. A return value of Yes provides a positive indication that the phone number listed belongs to the cardholder. Currently we only support US Phone numbers, in the future we may support other countries.');
define('FS_HELP_PROXY_LEVEL','Likelihood of IP Address being an Open Proxy');
define('FS_HELP_SPAM_LEVEL','Likelihood of IP Address being an Spam Source');
define('FS_HELP_FRAUD_LEVEL','Overall Fraud Risk Factor based on outputs listed above');
define('FS_HELP_REGION','Estimated Region of the IP address, ISO-3166-2/FIPS 10-4 code');
define('FS_HELP_CITY','Estimated City of the IP address**');
define('FS_HELP_LATITUDE','Estimated Latitude of the IP address');
define('FS_HELP_LONGITUDE','Estimated Longitude of the IP address');
define('FS_HELP_IP','IP address of the customer. For US addresses, click on the ip address to see a map estimating where the customer is located.');
define('FS_HELP_ISP','ISP of the IP address');
define('FS_HELP_ORG','Organization of the IP address');
define('FS_HELP_IS_HI_RISK_COUNTRY','Whether IP address or billing address country is in Belarus, Colombia, Egypt, Indonesia, Lebanon, Macedonia, Malaysia, Nigeria, Pakistan, Ukraine, or Yugoslavia');
define('FS_HELP_REMAINING_QUERIES','Number of queries remaining in your account, can be used to alert you when you may need to add more queries to your account');
define('FS_HELP_DEFAULT','<i><b>To get field descriptions, place cursor on the field name<b></i>');


define('NO_IP_ADDRESS_RECORDED','*** NO IP ADDRESS RECORDED FOR THIS ORDER');
define('MAX_COMMENT_0','(Extremely Low risk)');
define('MAX_COMMENT_1','(Very Low risk)');
define('MAX_COMMENT_2','(Low risk)');
define('MAX_COMMENT_3','(Low risk)');
define('MAX_COMMENT_4','(Low-Medium risk)');
define('MAX_COMMENT_5','(Medium risk)');
define('MAX_COMMENT_6','(Medium-high risk)');
define('MAX_COMMENT_7','<font color=red>(High risk)</font>');
define('MAX_COMMENT_8','<font color=red>(Very High risk)</font>');
define('MAX_COMMENT_9','<font color=red>(Extremely High risk)</font>');
define('MAX_COMMENT_10','<font color=red>(HIGH PROBABILITY OF FRAUD)</font>');
define('ERROR','Error');
define('LAST_QUERIED_ON','Last Queried on :');
define('REMAINING_QUERIES_1','You have');
define('REMAINING_QUERIES_2','queries remaining');

// End AlgoZone - Algozone Fraud Screen Tool definitions
?>
