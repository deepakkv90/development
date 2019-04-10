<?php
/**
 * @file
 * A single location to store configuration.
 */

/**
 * Define for file includes
 */
define('BASE_PATH',realpath('.'));

/**
 * Define which app type you are using: 
 * Private - private app method
 * Public - standard public app method
 * Partner - partner app method      
 */      
define("XRO_APP_TYPE",     "Private");

/**
 * Set your callback url or set 'oob' if none required
 */

//define("OAUTH_CALLBACK",     'http://localhost/ananth/xero/example.php');
define("OAUTH_CALLBACK",     'http://namebadgesinternational.com.au/');

/**
 * Application specific settings
 * Not all are required for given application types
 * consumer_key: required for all applications
 * shared_secret:  for partner applications, set to: s (cannot be blank)
 * rsa_private_key: not needed for public applications
 * rsa_public_key: not needed for public applications
 */
                     	 
$signatures = array( 'consumer_key'     => 'E4VWXO1ULQ2VLTWW1KLWYJEPV38IXV',
              	      	 'shared_secret'    => 'PERNMBLAO4NL1ALSJVUQ1EMXZJAKZF',
                	     'rsa_private_key'	=> 'xero/private.pem',
                     	 'rsa_public_key'	=> 'xero/public.cert',
						 'oauth_token'	=> 'E4VWXO1ULQ2VLTWW1KLWYJEPV38IXV',
						 'oauth_secret'	=> 'PERNMBLAO4NL1ALSJVUQ1EMXZJAKZF');
                     	 
/**
 * Special options for Partner applications - should be commented out for non-partner applications
 * Partner applications require a Client SSL certificate which is issued by Xero
 * the certificate is issued as a .p12 cert which you will then need to split into a cert and private key:
 * openssl pkcs12 -in entrust-client.p12 -clcerts -nokeys -out entrust-cert.pem
 * openssl pkcs12 -in entrust-client.p12 -nocerts -out entrust-private.pem <- you will be prompted to enter a password
 */   	
$options[CURLOPT_SSLCERT] = 'xero/public.cert';
$options[CURLOPT_SSLKEYPASSWD] = 'x=1A0b_hzLMH';
$options[CURLOPT_SSLKEY] = 'xero/private.pem';

/**
 * It is a good idea to set a user agent for the Xero API logs
 */
$useragent = "";

