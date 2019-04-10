<?php

	define('MODULE_PAYMENT_EWAYPAYMENT_TEXT_TITLE', 'Credit Card - eWay Payment </b>
	<br><br>You will be directed through the Eway Gateway for Secure payment<b>');

	define('MODULE_PAYMENT_EWAYPAYMENT_TEXT_DESCRIPTION', 'Test Credit Card: 4444333322221111<br>Test Customer ID: 87654321<br>Total Amount should end in 00 or 08 to get a successful response (e.g. $10.00 or $10.08)<br>Test Expiration Date: greater than today');

	define('MODULE_PAYMENT_EWAYPAYMENT_TEXT_TYPE', 'Type:');

	define('MODULE_PAYMENT_EWAYPAYMENT_TEXT_CREDIT_CARD_OWNER', 'Name As It Appears On Card:');

	define('MODULE_PAYMENT_EWAYPAYMENT_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');

	define('MODULE_PAYMENT_EWAYPAYMENT_TEXT_CREDIT_CARD_EXPIRES', 'Expiration Date:');

	define('MODULE_PAYMENT_EWAYPAYMENT_TEXT_JS_CC_NUMBER', '* Credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');

	define('MODULE_PAYMENT_EWAYPAYMENT_TEXT_JS_CC_OWNER', '* Cardholder name must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');

	define('MODULE_PAYMENT_EWAYPAYMENT_TEXT_ERROR', 'Credit Card Error!');



	define('MODULE_PAYMENT_EWAYPAYMENT_TEXT_CVV', 'CVV Number <a href="#" onClick="window.open(\'ewaypayment/ewaypayment_cvvhelp.html\',\'\',\'width=500,height=550,resizable=yes,toolbar=no,menubar=no,status=no\');return false;"><u>(more info)</u></a>');

	define('MODULE_PAYMENT_EWAYPAYMENT_TEXT_JS_CC_CVV', '* The 3 or 4 digit CVV number must be entered from the back of the credit card.\n');

?>