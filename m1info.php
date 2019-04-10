<?php
/*-----------------------------------------------------------------------------+
| Shopping Cart Diagnostic Utility                                             |
| Will work with osCommerce, CRE Loaded, Zen Cart and X-Cart                   |
|                                                                              |
| What is this:                                                                |
| This utility greatly helps to diagnose shopping cart problems                |
|                                                                              |
| How to use:                                                                  |
| Copy to store WEB ROOT folder using FTP connection where your primary        |
| index.php is located. Open http://<YOUR_STORE_URL>/m1diagnostic.php          |
| in your browser                                                              |
|                                                                              |
| $Revision: 52 $                                                              |
|                                                                              |
| Developed by MagneticOne,                                                    |
| Copyright (C) 2008                                                           |
+-----------------------------------------------------------------------------*/

/*
Don't change anything below this line. You should REALLY understand what are you doing.
*/

// CONFIGURATION
define("TECHNICAL_EMAIL", "contact@magneticone.com");

//Slash masks definition - use in configuration checking
define('NONE_SLASHES_MASK', '^[^\/].*[^\/]$');
define('BOTH_SLASHES_MASK', '^\/.+\/$');
define('BEGIN_SLASH_MASK', '^\/.*[^\/]$');
define('END_SLASH_MASK', '^[^\/].*\/$');
define('END_NOSLASH_MASK', '^.*[^\/]$');
define('PHYSICAL_PATH_MASK', '^.+[\\\\\/]$');

// Turn off all error reporting
error_reporting(0);
ini_set('display_errors', 0);

if (isset($_GET['phpinfo'])) {
	phpinfo();
	echo '<center><input type="button" value="Home" onclick="document.location=\'' . 'http://' .
				$_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '\'"></center>';
	exit;
}

if (file_exists('includes/configure.php')) { //oscommerce and clones
	require_once('includes/configure.php');
	
	if (file_exists(DIR_WS_INCLUDES . 'version.php')) {
		require_once(DIR_WS_INCLUDES . 'version.php');
		
	} elseif (file_exists(DIR_FS_CATALOG . DIR_WS_INCLUDES . 'version.php')) {
		
		//admin section
		require_once(DIR_FS_CATALOG . DIR_WS_INCLUDES . 'version.php'); 
	}
	
/*	if (defined('PROJECT_VERSION') || defined('PROJECT_VERSION_NAME')) {
		
		//CRE Loaded and Zen Cart
		if (file_exists(DIR_WS_INCLUDES . 'database_tables.php')) {
			require_once(DIR_WS_INCLUDES . 'database_tables.php'); 
	
		} elseif (file_exists(DIR_FS_CATALOG . DIR_WS_INCLUDES . 'database_tables.php')) {
		
			//admin section of Zen Cart
			require_once(DIR_FS_CATALOG . DIR_WS_INCLUDES . 'database_tables.php'); 
		}
		
		require_once(DIR_WS_FUNCTIONS . 'database.php'); 

		session_start();
		$applicationTopIncluded = false;
		
	} else {*/
		
		//osCommerce
		require_once('includes/application_top.php'); 
		$applicationTopIncluded = true;
	//}

} elseif (file_exists('auth.php')) { //X-Cart
	require_once('auth.php');
} 

// Turn off all error reporting
error_reporting(0);
ini_set('display_errors', 0);

$curFilename = basename($_SERVER['PHP_SELF']);

$database = new m1info_database();

$shoppingCart = null;

if ($shoppingCartName = CompatibilityChecker::getShoppingCartType()) {
	$className = 'm1info_' . $shoppingCartName;
	
	if (class_exists($className)) {
		$shoppingCart = new $className();
		$shoppingCart->setDbFields();
	}

} else {
	
	//Shopping cart is not defined
}

$checker = new CompatibilityChecker();

if (isset($_GET['send'])) {
	$checker->send(TECHNICAL_EMAIL);
	$matches = array();
	preg_match("/(.*)\?.*/", "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $matches);
	$redirect_url = $matches[1];
	header("Location: ".$redirect_url);
	exit;
}

class CompatibilityChecker
{
	var $mysqlHost;
	var $mysqlUser;
	var $mysqlPass;
	var $mysqlDb;
	var $sslServer;
	var $nosslServer;
	var $storeOwnerEmail;
	
	function CompatibilityChecker()
	{
		global $sql_tbl, $sql_fld;
		
		if (is_object($GLOBALS['shoppingCart'])) {
			
			$this->mysqlHost	= $GLOBALS['shoppingCart']->mysqlHost;
			$this->mysqlUser	= $GLOBALS['shoppingCart']->mysqlUser;
			$this->mysqlPass	= $GLOBALS['shoppingCart']->mysqlPass;
			$this->mysqlDb		= $GLOBALS['shoppingCart']->mysqlDb;
			
			$this->sslServer	= $GLOBALS['shoppingCart']->sslServer;
			$this->nosslServer	= $GLOBALS['shoppingCart']->nosslServer;
			
			$this->storeOwnerEmail = $GLOBALS['shoppingCart']->getAdminEmail();
			
		} else {
			
			//Shopping cart is not defined
			session_start();
		}
	}
	
	# Get properties methods
	
	function getShoppingCartType() {

				//For osCommerce & CRE loaded
		if (defined('PROJECT_VERSION'))
		{
			if (preg_match("/oscommerce/i",PROJECT_VERSION)) return "oscommerce";
			if (preg_match("/cre.*loaded/i",PROJECT_VERSION)) return "creloaded";
		}

		//For ZenCart
		if (defined('PROJECT_VERSION_NAME'))
		{
			if (preg_match("/zen cart/i", PROJECT_VERSION_NAME)) return "zencart";
		}

		// X-Cart
		if (defined('XCART_START')) 
		{
			return "xcart";
		}

		return false; //Shopping cart is not defined
	}
	
	function getShoppingCartName() {
		
		switch ($this->getShoppingCartType())
		{
			case 'oscommerce': $name = "osCommerce"; break;
			case 'creloaded': $name = "CRE Loaded"; break;
			case 'xcart': $name = "X-Cart"; break;
			case 'zencart': $name = "Zen Cart"; break;
			default: $name = "Unknown"; break;
		}
		return $name;
	}
	
	function getTMPFolder()
	{
		if (!function_exists('sys_get_temp_dir')) {
			
			// Based on http://www.phpit.net/
			// article/creating-zip-tar-archives-dynamically-php/2/
			// Try to get from environment variable
			if (!empty($_ENV['TMP'])) {
				return realpath( $_ENV['TMP'] );
			
			} elseif (!empty($_ENV['TMPDIR'])) {
				return realpath( $_ENV['TMPDIR'] );
			
			} elseif (!empty($_ENV['TEMP'])) {
				return realpath( $_ENV['TEMP'] );
			
			} else {
				
				// Detect by creating a temporary file
				// Try to use system's temporary directory
				// as random name shouldn't exist
				$temp_file = @tempnam( md5(uniqid(rand(), TRUE)), '' );
				
				if ($temp_file) {
					$temp_dir = realpath( dirname($temp_file) );
					unlink( $temp_file );
					return $temp_dir;
					
				} else {
					return FALSE;
				}
			}
			
		} else {
			return sys_get_temp_dir();
		}
	}
	
	# Get properties methods end
	
	function Run()
	{
		$checkMethods = $this->getCheckMethods();
		$methodSectionTitles = $this->getSectionTitles();
		
		if(count($checkMethods) > 0) 
		{
			$this->showCheckHeader();
			
			foreach ($checkMethods as $section => $sectionMethods)
			{
				$this->showSectionTitle($methodSectionTitles[$section]);

				foreach ($sectionMethods as $id => $method) {
					$checkResult = call_user_func(array($this, $method['check_method']));
					
					if ($errCount = count($checkResult['problems'])) {
						
						//Save problems
						$GLOBALS['problems'][$section][$id] = array('problems'	=> $checkResult['problems'],
																	'solution'	=> $checkResult['check_issue_solution']);	

						//Show link on problems
			
						if ($errCount == 1) {
							$problemsWord = " problem";
			
						} else {
							$problemsWord = " problems";
						}

						if (function_exists('tep_href_link')) {
							$href = tep_href_link($GLOBALS['curFilename'], 'action=show_problems&section=' . $section . '&id=' . $id);
						
						} else {
							$href = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?action=show_problems&section=' . $section . '&id=' . $id;
						}
						
						$checkResult['check_result'] = '<a href="' . $href . '" class="error_message">' . $errCount . $problemsWord . '</a>';
					}
					
					$checkResult['check_title'] = $method['check_title'];
					$this->showCheckResult($checkResult);
				}
			}
			
			$this->showCheckFooter();
			$this->sessionSave('problems');
		}
	}
	
	function send($email_address_to)
	{
		ob_start();
		$this->Run();
		$content = ob_get_contents();
		ob_end_clean();
		
		$content .= "<br>Comments: ".htmlspecialchars($_POST['comments']);
		
		$headers  = "Content-type: text/html; charset=ISO-8859-1 \r\n";
		$headers .= "From: ".$_POST['email']." \r\n";
			
		if( mail($email_address_to, "Diagnostic Utility Information on \"".$_SERVER['HTTP_HOST']."\" domain", $content, $headers) ) {
			$GLOBALS['message_sent'] = true;
			$this->sessionSave('message_sent');
		}
		
	}
	
	function sqlQuery($query)
	{
		$result = mysql_query($query) or die(mysql_error() . '<br>' . $query);
		return $result;
	}
	
	function getSectionTitles()
	{
		$sectionTitles = array(
			'shopping_cart_info'=> 'Store Information',
			'server_info'=> 'Server Information',
			'product_errors'	=> 'Product Problems',
			'category_errors'	=> 'Category Problems',
			'configuration_errors'	=> 'Configuration Problems',
			'file_system_errors'	=> 'File System Problems'/*,
			'other_errors'	=> 'Other Problems'*/
		);
		
		return $sectionTitles;
	}
	
	function getCheckMethods()
	{
		
		//Shopping Cart Information section
		
		$checkMethods['shopping_cart_info'][] = array(
				"check_title"	=> "Shopping Cart",
				"check_method"	=> "check_ShoppingCartVersion"
		);
		
		$checkMethods['shopping_cart_info'][] = array(
				"check_title"	=> "Count of Products",
				"check_method"	=> "check_ProductCount"
		);
		
		$checkMethods['shopping_cart_info'][] = array(
				"check_title"	=> "Count of Categories",
				"check_method"	=> "check_CategoryCount"
		);
		
		$checkMethods['shopping_cart_info'][] = array(
				"check_title"	=> "Count of Manufacturers",
				"check_method"	=> "check_ManufacturerCount"
		);
		
		$checkMethods['shopping_cart_info'][] = array(
				"check_title"	=> "Database Structure",
				"check_method"	=> "check_DatabaseStructure"
		);
		
		//Server Information section
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='http://en.wikipedia.org/wiki/Operating_system' target='_blank' title='What is Operation System'>Server Operation System:</a>",
				"check_method"	=> "check_OperationSystem"
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='http://en.wikipedia.org/wiki/Web_server' target='_blank' title='What is HTTP (Web) Server'>HTTP server type:</a>",
				"check_method"	=> "check_ServerType",
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='http://en.wikipedia.org/wiki/PHP' target='_blank' title='What is PHP'>PHP Version:</a>",
				"check_method"	=> "check_PHPVersion",
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='http://en.wikipedia.org/wiki/MySQL' target='_blank' title='What is MySQL'>MySQL Version:</a>",
				"check_method"	=> "check_MySQLVersion",
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='http://dev.mysql.com/doc/refman/5.0/en/server-sql-mode.html' target='_blank' title='Server SQL Modes'>MySQL Server SQL Mode:</a>",
				"check_method"	=> "check_MySQLMode",
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='http://en.wikipedia.org/wiki/PHP_accelerator#Zend_Optimizer' target='_blank' title='What is Zend Optimizer'>Zend Optimizer:</a>",
				"check_method"	=> "check_ZendOptimizer",
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='http://en.wikipedia.org/wiki/PHP_accelerator#ionCube_PHP_Accelerator' target='_blank' title='What is ionCube'>ionCube Loader:</a>",
				"check_method"	=> "check_IonCubeLoader",
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='http://www.php.net/manual/en/ref.dom.php' target='_blank' title='What is DOM XML / DOM'>PHP DOM XML / DOM:</a>",
				"check_method"	=> "check_DOMXML_DOM",
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='http://www.php.net/curl' target='_blank' title='What is cURL'>PHP CURL Extension:</a>",
				"check_method"	=> "check_CURL",
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='http://www.hardened-php.net/suhosin/' target='_blank' title='What is Suhosin'>PHP Suhosin Extension:</a>",
				"check_method"	=> "check_Suhosin",
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='http://www.php.net/features.safe-mode' target='_blank' title='What is Safe Mode'>PHP Safe Mode:</a>",
				"check_method"	=> "check_SafeMode",
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='http://ua.php.net/manual/en/ini.core.php#ini.upload-max-filesize' target='_blank' title='What is PHP upload_max_filesize'>PHP upload_max_filesize:</a>",
				"check_method"	=> "check_UploadMaxFileSize",
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='http://ua.php.net/manual/en/ini.core.php#ini.memory-limit' target='_blank' title='What is PHP memory_limit'>PHP memory_limit:</a>",
				"check_method"	=> "check_MemoryLimit",
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='http://ua.php.net/manual/en/ini.core.php#ini.register-globals' target='_blank' title='What is PHP register_globals'>PHP register_globals:</a>",
				"check_method"	=> "check_RegisterGlobals",
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='http://ua.php.net/manual/en/ini.core.php#ini.register-long-arrays' target='_blank' title='What is PHP register_long_arrays'>PHP register_long_arrays:</a>",
				"check_method"	=> "check_RegisterLongArrays",
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='http://httpd.apache.org/docs/2.2/mod/mod_rewrite.html' target='_blank' title='What is Apache Mod Rewrite'>Apache Mod Rewrite:</a>",
				"check_method"	=> "check_ModRewrite",
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='http://en.wikipedia.org/wiki/Temporary_folder' target='_blank' title='What is TMP Folder'>TMP Folder:</a>",
				"check_method"	=> "check_TMPFolder",
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "<a href='#'>MagneticOne License Server:</a>",
				"check_method"	=> "check_M1LicenseSrv",
		);
		
		$checkMethods['server_info'][] = array(
				"check_title"	=> "PHP Mbstring Extension",
				"check_method"	=> "check_phpMbstringExtension",
		);
		
		//Product Problems section
		
		if (is_object($GLOBALS['shoppingCart'])) {
		
		$checkMethods['product_errors'][] = array(
				"check_title"	=> "HTML in Product Names",
				"check_method"	=> "check_HtmlInProductNames",
				"check_field"	=> $GLOBALS['shoppingCart']->productName
		);
		
		$checkMethods['product_errors'][] = array(
				"check_title"	=> "HTML in Product Models",
				"check_method"	=> "check_HtmlInProductModels",
				"check_field"	=> $GLOBALS['shoppingCart']->productModel
		);
		
		$checkMethods['product_errors'][] = array(
				"check_title"	=> "HTML in Product Prices",
				"check_method"	=> "check_HtmlInProductPrices",
				"check_field"	=> $GLOBALS['shoppingCart']->productPrice
		);
		
		$checkMethods['product_errors'][] = array(
				"check_title"	=> "Product Price is null",
				"check_method"	=> "check_ProductPricesByNull",
				"check_field"	=> $GLOBALS['shoppingCart']->productPrice
		);
		
		$checkMethods['product_errors'][] = array(
				"check_title"	=> "Product Name is empty",
				"check_method"	=> "check_ProductNamesByEmpty",
				"check_field"	=> $GLOBALS['shoppingCart']->productName
		);
		
		$checkMethods['product_errors'][] = array(
				"check_title"	=> "Non-printable characters in Product Descriptions",
				"check_method"	=> "check_NonPrintableDescriptionChars",
				"check_field"	=> $GLOBALS['shoppingCart']->productDescription
		);
		
		if ($GLOBALS['shoppingCart']->fields['product_parent_id'])
		{
			
			$checkMethods['product_errors'][] = array(
				"check_title"	=> "Parent ID is null",
				"check_method"	=> "check_ParentProductsByNull",
				"check_field"	=> $GLOBALS['shoppingCart']->fields['product_parent_id']
			);
		
			$checkMethods['product_errors'][] = array(
				"check_title"	=> "Parent Product not exists",
				"check_method"	=> "check_ParentProductsByExistance",
				"check_field"	=> $GLOBALS['shoppingCart']->fields['product_parent_id']
			);
		
			$checkMethods['product_errors'][] = array(
				"check_title"	=> "Parent Product is self",
				"check_method"	=> "check_ParentProductsBySelf",
				"check_field"	=> $GLOBALS['shoppingCart']->fields['product_parent_id']
			);
		
			$checkMethods['product_errors'][] = array(
				"check_title"	=> "Parent Product is cycling",
				"check_method"	=> "check_ParentProductsByCycling",
				"check_field"	=> $GLOBALS['shoppingCart']->fields['product_parent_id']
			);	
		}
		
		//Category Problems section
		
		$checkMethods['category_errors'][] = array(
				"check_title"	=> "HTML in Category Names",
				"check_method"	=> "check_HtmlInCategoryNames",
				"check_field"	=> $GLOBALS['shoppingCart']->categoryName
		);
		
		$checkMethods['category_errors'][] = array(
				"check_title"	=> "Category Name is empty",
				"check_method"	=> "check_CategoryNamesByEmpty",
				"check_field"	=> $GLOBALS['shoppingCart']->categoryName
		);
		
		$checkMethods['category_errors'][] = array(
				"check_title"	=> "Parent ID is null",
				"check_method"	=> "check_ParentCategoriesByNull",
				"check_field"	=> $GLOBALS['shoppingCart']->categoryParentId
		);
		
		$checkMethods['category_errors'][] = array(
				"check_title"	=> "Parent Category not exists",
				"check_method"	=> "check_ParentCategoriesByExistance",
				"check_field"	=> $GLOBALS['shoppingCart']->categoryParentId
		);
		
		$checkMethods['category_errors'][] = array(
				"check_title"	=> "Parent Category is self",
				"check_method"	=> "check_ParentCategoriesBySelf",
				"check_field"	=> $GLOBALS['shoppingCart']->categoryParentId
		);
		
		$checkMethods['category_errors'][] = array(
				"check_title"	=> "Parent Category is cycling",
				"check_method"	=> "check_ParentCategoriesByCycling",
				"check_field"	=> $GLOBALS['shoppingCart']->categoryParentId
		);
		
		$checkMethods['category_errors'][] = array(
				"check_title"	=> "Parent Category ID is not integer",
				"check_method"	=> "check_ParentCategoriesByInteger",
				"check_field"	=> $GLOBALS['shoppingCart']->categoryParentId
		);
		
		//Configuration Problems section
		
		$checkMethods['configuration_errors'][] = array(
				"check_title"	=> "Directory names/paths",
				"check_method"	=> "check_PhysicalPaths",
				"check_field"	=> "Value"
		);
		
		$checkMethods['configuration_errors'][] = array(
				"check_title"	=> "Definition of configuration options",
				"check_method"	=> "check_ConfigurationOptions",
				"check_field"	=> "Value"
		);
		
		$checkMethods['configuration_errors'][] = array(
				"check_title"	=> "Existance of physical paths",
				"check_method"	=> "check_ExistancePaths",
				"check_field"	=> "Value"
		);
		
		$checkMethods['configuration_errors'][] = array(
				"check_title"	=> "Leading/trailing slashes in configuration values",
				"check_method"	=> "check_ConfigurationValues",
				"check_field"	=> "Value"
		);
		
		$checkMethods['configuration_errors'][] = array(
				"check_title"	=> "Store owner e-mail address",
				"check_method"	=> "check_AdminEmail"
		);
		
		$checkMethods['configuration_errors'][] = array(
				"check_title"	=> "HTTPS (SSL) Host:",
				"check_method"	=> "check_SSLDomain_RegularDomain"
			);
			
		$checkMethods['configuration_errors'][] = array(
				"check_title"	=> "Catalog Directory:",
				"check_method"	=> "check_CatalogDir"
			);
		
		//File System Problems section
		
		 $checkMethods['file_system_errors'][] = array(
				"check_title"	=> "Writability of folders",
				"check_method"	=> "check_WritableFolders",
				"check_field"	=> "Value"
		);
		
		$checkMethods['file_system_errors'][] = array(
				"check_title"	=> "Compliance of key files to shopping cart",
				"check_method"	=> "check_KeyFiles",
				"check_field"	=> "File"
		);
		
		} //if (is_object($GLOBALS['shoppingCart']))
		
		//Other Problems section
		
		return $checkMethods;
	}
	
	function showCheckHeader()
	{
		echo "<h1 align='center'>Shopping Cart Diagnostic Utility v 1.0." . $this->getRevision() . "</h1></h1>";
		echo "<center><b>Current domain is: ".$_SERVER['HTTP_HOST']."</b></center><br>";
		
		$this->sessionLoad('message_sent');
		
		if ($GLOBALS['message_sent']) {
			$GLOBALS['message_sent'] = false;
			$this->sessionSave('message_sent');
			echo "<center><span style='color: green; font-size:11px;'>Message with your system information was sent successfully to ".TECHNICAL_EMAIL."</span></center><br>";
		}
		
		echo "
		<table align='center' class='diagnostic_table'><tr>
		<th width='30%'>Check Type:</th>
		<th width='20%'>Check Results:</th>
		<th>What to do:</th>
		</tr>
		";
	}
	
	function showCheckFooter()
	{
		echo "</table>";
	}
	
	function showSectionTitle($title)
	{
		echo '<tr><td colspan="3" style="padding:20px" align="center"><b>' . $title . '</b></td></tr>';
	}
	
	function showCheckResult($check_result_arr)
	{
		
 # Modified by tsergiy - added gray color of result messages
 # http://jira:8080/browse/MINFO-10
 # 25.11.2008
	 
		//$result_color=($check_result_arr['check_result_status'] == "OK")?"green":"red";
		switch ($check_result_arr['check_result_status']) {
			
			case 'OK':
				$result_color = 'green';
				break;
				
			case 'BAD':
				$result_color = 'red';
				break;
				
			default:
				$result_color = 'gray';
				break;
		}
		
 # End of tsergiy's modification
		
		echo "<tr>
					<td nowrap style='font-weight:bold'>".$check_result_arr['check_title']."</td>
					<td style='color:".$result_color."'>".$check_result_arr['check_result']."</td>
					<td style='color: gray;'>".$check_result_arr['check_issue_solution']."</td>
					</tr>";
		
	}
	
	# test methods
	
	//Server Information methods
	
	function check_OperationSystem()
	{
		$result = array(
			"check_result"			=>	"Unknown",
			"check_result_status"	=>	"BAD",
			"check_issue_solution"	=>	"&nbsp;",
		);
		
		$os = explode(" ", php_uname());
		
		if (is_array($os) && isset($os[0])) {
			$result['check_result'] = $os[0];
			$result['check_result_status'] = "OK";
		}
		
		return $result;
	}
	
	function check_ServerType()
	{
		$result['check_result'] = $_SERVER['SERVER_SOFTWARE'];

		if (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'apache') !== false) {
			$result['check_result_status'] = 'OK';
			$result['check_issue_solution'] = '&nbsp;';
		
		} else {
			$result['check_result_status'] = 'BAD';
			$result['check_issue_solution'] = 'Possible problems on non-Apache web servers';
		}
				
		return $result;
	}
	
	function check_PHPVersion()
	{
		$phpVersion = phpversion();
		
		$result = array(
			"check_result"			=>	"<a href='http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?phpinfo=1' title='View PHP info'>" . $phpVersion . "</a>",
			"check_result_status"	=>	"OK",
			"check_issue_solution"	=>	"&nbsp;",
		);
		
		if(preg_match("/^3.*/", phpversion()))	{
			$result['check_result_status'] = "BAD";
			$result['check_issue_solution'] = "You need to upgrade your PHP version";
		}
		
		$arrPhpVersion = explode('.', $phpVersion);
		
		if (($arrPhpVersion[0] == '5') && ($arrPhpVersion[1] == '2') && (intval(($arrPhpVersion[2]) >= 10))) {
			$result['check_issue_solution'] = 'Please use ionCube encoded version of the module with the current PHP version';
		}
		
		return $result;
	}

	function check_MySQLVersion()
	{
		$result = array(
			'check_result'			=>	'Unknown',
			'check_result_status'	=>	'BAD',
			'check_issue_solution'	=>	'Can\'t connect to database because shopping cart is not recognized'
		);
		
		if (is_object($GLOBALS['shoppingCart'])) {

			if ($GLOBALS['shoppingCart']->mysqlError) {
				
				$result = array(
					'check_result'			=>	mysql_error(),
					'check_result_status'	=>	'BAD',
					'check_issue_solution'	=>	'Check your configuration file'
				);

			} else {
				$res_id = mysql_query("SELECT VERSION() as ver;");
				$res = mysql_fetch_array($res_id);
				
				$result = array(
					'check_result'			=>	$res['ver'],
					'check_result_status'	=>	'OK',
					'check_issue_solution'	=>	'&nbsp;'
				);
			}
		}
		
		return $result;
	}
	
	function check_MySQLMode()
	{
		$result = array(
			"check_result"			=>	"Unknown",
			"check_result_status"	=>	"BAD",
			"check_issue_solution"	=>	"Can't get MySQL server SQL mode",
		);
		
		if (is_object($GLOBALS['shoppingCart'])) {

			if ($GLOBALS['shoppingCart']->mysqlError) {
				$result['check_result'] = mysql_error();
				$result['check_issue_solution'] = "Check your configuration file";

			} else {
                mysql_query('set sql_mode=""');
				$res_id = mysql_query("SELECT @@SESSION.sql_mode");
				$res = mysql_fetch_array($res_id, MYSQL_NUM);
				
				if ($res[0] === '') {
					$result['check_result'] = '[Empty]';
					$result['check_result_status'] = "OK";
					$result['check_issue_solution'] = "&nbsp;";
					
				} elseif ($result['check_result']) {
					$result['check_result'] = $res[0];
					$result['check_issue_solution'] = "This mode can cause problems for modules. Recommended SQL mode is default (empty)";
				}
			}
		}
		
		return $result;
	}
	
	function check_ZendOptimizer()
	{
		$result = array(
			"check_result"			=>	"Enabled",
			"check_result_status"	=>	"OK",
			"check_issue_solution"	=>	"&nbsp;",
		);
		
		if (!extension_loaded("Zend Optimizer")) {
			$result['check_result'] = "Not Installed/Disabled";	
			$result['check_result_status'] = "BAD";
			$result['check_issue_solution'] = "Please contact your hosting provider to install and enable Zend Optimizer.<br>See <a href='http://www.zend.com/products/zend_optimizer/general_faq' target='_blank'>Zend Optimizer FAQ</a> for installation instructions and <a href='http://www.zend.com/products/zend_optimizer/general_faq#root_6' target='_blank'>availability checking</a>.<br><br>If Zend Optimizer is incompatible with your server environment, please request the ionCube version from <a href='mailto:".TECHNICAL_EMAIL."?subject=Shopping Cart Diagnostic Utility Results'>MagneticOne</a>.";
		}
				
		return $result;
	}
	
	function check_IonCubeLoader()
	{
		$result = array(
			"check_result"			=>	"Installed",
			"check_result_status"	=>	"OK",
			"check_issue_solution"	=>	"&nbsp;",
		);
		
		if (!extension_loaded('ionCube Loader')) {
			$__oc=strtolower(substr(php_uname(),0,3));
			$__ln='/ioncube/ioncube_loader_'.$__oc.'_'.substr(phpversion(),0,3).(($__oc=='win')?'.dll':'.so');
			$__oid=$__id=realpath(ini_get('extension_dir'));
			$__here=dirname(__FILE__);
			
			if ((@$__id[1])==':') {
				$__id=str_replace('\\','/',substr($__id,2));
				$__here=str_replace('\\','/',substr($__here,2));
			}
			
			$__rd=str_repeat('/..',substr_count($__id,'/')).$__here.'/';
			$__i=strlen($__rd);
			
			while ($__i--) {
				
				if ($__rd[$__i]=='/') {
					
					$__lp=substr($__rd,0,$__i).$__ln;
					
					if (file_exists($__oid.$__lp)) {
						$__ln=$__lp;
						break;
					}
					
				}
			}
			$msgZend = 'Required only if Zend Optimizer can\'t be used. ';
			dl($__ln);
			
			  # Modified by tsergiy - 16.04.2009
			  
			//}
			
			//Since PHP 5.2.5 it may cause problems with using directories in file in function dl()
			//Warning: dl() [function.dl]: Temporary module name should contain only filename
			//http://aspn.activestate.com/ASPN/Mail/Message/php-dev/3604381
			
			$arrPhpVersion = explode('.', phpversion());
			$dlDirSupported = true;
			
			if ($arrPhpVersion[0] > 5) {
				
				//PHP 6+ - on future...
				$dlDirSupported = false;
			
			} elseif ($arrPhpVersion[0] == 5) {
				
				if ($arrPhpVersion[1] > 2) {
					
					//PHP 5.3+
					$dlDirSupported = false;
				
				} elseif (($arrPhpVersion[1] == 2) && (intval($arrPhpVersion[2]) >= 5)) {
					
					//PHP 5.2.5+
					$dlDirSupported = false;
					
					if (intval($arrPhpVersion[2]) >= 10) {
						$msgZend = '';
					}
				}
			}
			
			  # End of tsergiy's modification
		
			if (!(bool)ini_get("enable_dl") || (bool)ini_get("safe_mode")
			
				  # Added by tsergiy - 16.04.2009
				|| !$dlDirSupported
			  	  # End of tsergiy's adding
			  	  
			) {
    			$dl_check = "";

			} else {
   				$dl_check = " You can enable IonCube by uploading dynamic IonCube Loader - ".basename($__ln).". <a href='http://www.ioncube.com/loaders' target='_blank'>See details here</a>";
   			}
				
			if (!function_exists('_il_exec'))	{
				$result['check_result'] = "Not Installed";
				$result['check_result_status'] = "BAD";
				$result['check_issue_solution'] = $msgZend . "Please configure/check <a href='http://www.ioncube.com/faqs/loaders.php' target='_blank'>all settings/conditions</a> and request the ionCube encoded versions from <a href='mailto:".TECHNICAL_EMAIL."?subject=Shopping Cart Diagnostic Utility Results'>MagneticOne</a>." . $dl_check;
			}
		
		  # Added by tsergiy - 16.04.2009
		}
		  # End of tsergiy's adding
		
		return $result;
	}
	
	function check_SafeMode()
	{
		$result = array(
			"check_result"			=>	"Off",
			"check_issue_solution"	=>	"&nbsp;",
		);

		if( ini_get('safe_mode') ){
			$result['check_result'] = "On";
			$result['check_issue_solution'] = "Disable safe mode in your php.ini :)";
		}
			
		return $result;
	}
	
	function check_DOMXML_DOM()
	{
		$result = array(
			"check_result"			=>	"None",
			"check_result_status"	=>	"BAD",
			"check_issue_solution" 	=>	"&nbsp;"
		);
		
		if(extension_loaded("dom"))
		{
			$result['check_result'] = "DOM";
			$result['check_result_status'] = "OK";
		}
		
		if(extension_loaded("domxml"))
		{
			$result['check_result'] = "DOM XML";
			$result['check_result_status'] = "OK";
		}
		
		if($result['check_result_status'] != "OK") {
			if(substr(phpversion(), 0, 1) == 4) {
				$result['check_issue_solution'] = "Required for Google Checkout only. Module will use emulation library instead of PHP DOM XML Extension";
			} else {
				$result['check_issue_solution'] = "Required for Google Checkout only. Module will use emulation library instead of PHP DOM Extension";
			}
		}
		
		return $result;
	}
	
	function check_CURL()
	{
		$result = array(
			"check_result"			=>	"Installed",
			"check_result_status"	=>	"OK",
			"check_issue_solution"	=>	"&nbsp;",
		);
		
		if(!extension_loaded("curl"))
		{
			$result['check_result'] = "Not installed";
			$result['check_result_status'] = "BAD";
			$result['check_issue_solution'] = "Please contact your hosting provider to enable PHP CURL extension OR use <a target='_blank'  href='http://support.magneticone.com/index.php?_m=knowledgebase&_a=viewarticle&kbarticleid=76'>CURL emulation library</a>";
		}
		
		return $result;
	}
	
	function check_Suhosin()
	{
	    $result = array(
			"check_result"			=>	"Not Installed",
			"check_result_status"	=>	"OK",
			"check_issue_solution"	=>	"&nbsp;",
		);
		
		if(extension_loaded("suhosin"))
		{
			$result['check_result'] = "Installed";
			$result['check_result_status'] = "BAD";
			$result['check_issue_solution'] = "Please contact your hosting provider to disable Suhosin extension";
		}
		
		return $result;
	}
	
	function check_TMPFolder()
	{
		if (is_writable($tmpFolder = $this->getTMPFolder())) {
			
			$result = array(
				"check_result"			=>	$tmpFolder . " - writable",
				"check_result_status"	=>	"OK",
				"check_issue_solution"	=>	"&nbsp;",
			);
			
		} elseif ($tmpFolder) {
			$result['check_result']	= $tmpFolder . " - not writable";
			$result['check_result_status'] = "BAD";
			
		} else {
			$result['check_result']	= "Not writable";
			$result['check_result_status'] = "BAD";
			$result['check_issue_solution'] = "Can't get temporary folder";
		}
		
		return $result;
	}
	
	function check_UploadMaxFileSize()
	{
		$result = array(
			"check_result"			=>	ini_get('upload_max_filesize'),
			"check_result_status"	=>	"OK",
			"check_issue_solution"	=>	"&nbsp;",
		);
		
		if(ini_get("upload_max_filesize") < 1)
		{
			$result['check_result_status'] = "BAD";
			$result['check_issue_solution'] = "&nbsp;";
		}
				
		return $result;
	}
	
	function check_MemoryLimit()
	{
		$result = array(
			"check_result"			=>	(ini_get('memory_limit'))?ini_get('memory_limit'):"Unknown",
			"check_result_status"	=>	"OK",
			"check_issue_solution"	=>	"&nbsp;",
		);
		
		if( (ini_get('memory_limit') < 8) && (ini_get('memory_limit') !== ""))
		{
			$result['check_result_status'] = "BAD";
			$result['check_issue_solution'] = "&nbsp;";
		}
				
		return $result;
	}
	
	function check_RegisterGlobals()
	{
		$result = array(
			"check_result"			=>	"On",
			"check_issue_solution"	=>	"&nbsp;",
		);
				
		if(!ini_get('register_globals'))
		{
			$result['check_result'] = "Off";
			$result['check_issue_solution'] = "Please enable PHP setting register_globals on your server. This option is necessary for some default shopping carts only";
		}
		
		return $result;
	}
	
	function check_RegisterLongArrays()
	{
		$result = array(
			"check_result"			=>	"On",
			"check_result_status"	=>	"OK",
			"check_issue_solution"	=>	"&nbsp;",
		);
		
		if(!ini_get('register_long_arrays') && preg_match("/^5.*/", phpversion())) {
			$result['check_result'] = "Off";
			$result['check_result_status'] = "BAD";
			$result['check_issue_solution'] = "Please enable PHP setting register_long_arrays on your server";
		}
						
		return $result;
	}
	
	function check_ModRewrite()
	{
		$result = array(
			"check_result"			=>	"Can Not Determine",
			"check_issue_solution"	=>	"&nbsp;",
		);
			
		if(strtolower(substr($_SERVER['SERVER_SOFTWARE'], 0, 6)) !== "apache") {
			$result['check_result'] = "Not Installed";
			$result['check_issue_solution'] = "Your server software is not Apache HTTP Server. You need Apache HTTP Server for this module to work properly";
		} else {
			if(function_exists("apache_get_modules"))
			{
				if(in_array("mod_rewrite", apache_get_modules()))	{
					$result['check_result'] = "Installed";
					$result['check_result_status'] = "OK";
				} else {
					$result['check_result'] = "Not Installed";
					$result['check_issue_solution'] = "Please contact your hosting provider to enable mod_rewrite Apache module. Apache mod_rewrite module required only for SEO URLs module";
				}
			}	
		}
		
		return $result;
	}
	
	function check_M1LicenseSrv()
	{
		
		$result = array(
			"check_result"			=>	"Available",
			"check_result_status"	=>	"OK",
			"check_issue_solution"	=>	"&nbsp;",
		);
		
		$fp = @fsockopen("license.magneticone.com", 80, $errno, $errstr, 30);

		if (!$fp) {
		    $result = array(
					"check_result"			=>	"Unreachable",
					"check_result_status"	=>	"BAD",
					"check_issue_solution"	=>	"Software can't communicate with licensing server for license validation. <a href='http://support.magneticone.com/70/unable-to-communicate-with-licensing-server.html' target='_blank'>Click here for more information</a>",
				);
		}
		
		return $result;
	}
	
	function check_phpMbstringExtension()
	{
		
		if (extension_loaded("mbstring")) {
			$result = array(
				"check_result"			=>	"OK",
				"check_result_status"	=>	"OK",
				"check_issue_solution"	=>	"&nbsp;",
			);
		
		} else {
			$result = array(
				"check_result"			=>	"BAD",
				"check_result_status"	=>	"BAD",
				"check_issue_solution"	=>	"Please contact your hosting provider to enable mbstring extension for PHP",
			);
		}
		
		return $result;
	}
	
	//Shopping Cart Information methods
	
	function check_ShoppingCartVersion()
	{
		if (is_object($GLOBALS['shoppingCart'])) {
			$version = $GLOBALS['shoppingCart']->getShoppingCartVersion();
		
			$result = array ('check_result'			=> $version,
							'check_result_status'	=> 'OK',
							'check_issue_solution'	=> '&nbsp;');
							
		} else {
			$errMsg =
<<<ERRMSG
<span style="color:red">Your shopping cart is unsupported or not found in the current directory<br>
1. Please check the m1info.php file located in shopping cart root directory<br>
2. Check if your shopping cart is in supported shopping cart list for all products you bought<br>
3. Use form below to get support with this issue</span>
ERRMSG
			;
			
			$result = array ('check_result'			=> 'Unknown',
							'check_result_status'	=> 'BAD',
							'check_issue_solution'	=> $errMsg

);
		}
		
		return $result;
	}
	
	function check_ProductCount()
	{
		
		$result = array (
			'check_result'			=> 'Unknown',
			'check_result_status'	=> 'BAD',
			'check_issue_solution'	=> 'Can\'t get count of products'
		);
							
		$sql = "SELECT COUNT(" . $GLOBALS['shoppingCart']->fields['product_id'] . ") AS total " .
			"FROM " . $GLOBALS['shoppingCart']->tables['products'];
			
		$query = $this->sqlQuery($sql);
		
		if (mysql_num_rows($query)) {
			$row = mysql_fetch_assoc($query);
			
			if (isset($row['total'])) {
				$cnt = intval($row['total']);
				
				$result = array (
					'check_result'			=> $cnt,
					'check_result_status'	=> 'OK',
					'check_issue_solution'	=> '&nbsp;'
				);
			}
		}
		
		return $result;
	}
	
	function check_CategoryCount()
	{
		
		$result = array (
			'check_result'			=> 'Unknown',
			'check_result_status'	=> 'BAD',
			'check_issue_solution'	=> 'Can\'t get count of categories'
		);
							
		$sql = "SELECT COUNT(" . $GLOBALS['shoppingCart']->fields['category_id'] . ") AS total " .
			"FROM " . $GLOBALS['shoppingCart']->tables['categories'];
			
		$query = mysql_query($sql);
		
		if (mysql_num_rows($query)) {
			$row = mysql_fetch_assoc($query);
			
			if (isset($row['total'])) {
				$cnt = intval($row['total']);
				
				$result = array (
					'check_result'			=> $cnt,
					'check_result_status'	=> 'OK',
					'check_issue_solution'	=> '&nbsp;'
				);
			}
		}
		
		return $result;
	}
	
	function check_ManufacturerCount()
	{
		
		$result = array (
			'check_result'			=> 'Unknown',
			'check_result_status'	=> 'BAD',
			'check_issue_solution'	=> 'Can\'t get count of manufacturers'
		);
							
		$sql = "SELECT COUNT(" . $GLOBALS['shoppingCart']->fields['manufacturer_id'] . ") AS total " .
			"FROM " . $GLOBALS['shoppingCart']->tables['manufacturers'];
			
		$query = $this->sqlQuery($sql);
		
		if (mysql_num_rows($query)) {
			$row = mysql_fetch_assoc($query);
			
			if (isset($row['total'])) {
				$cnt = intval($row['total']);
				
				$result = array (
					'check_result'			=> $cnt,
					'check_result_status'	=> 'OK',
					'check_issue_solution'	=> '&nbsp;'
				);
			}
		}
		
		return $result;
	}
	
	function check_DatabaseStructure()
	{
		$result = array (
			'check_result'			=> 'OK',
			'check_result_status'	=> 'OK',
			'check_issue_solution'	=> '&nbsp;'
		);
		
		if ($errData = $this->getDatabaseProblems()) {
			$urlRepairBase = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?action=repair_database';
			$problems = array();
			
			foreach ($errData as $data) {
			
				  # Preparing data for repair
						
				if (is_array($data['recommended_val'])) {
					$valRecommended = $data['recommended_val'][0];
					$arrRecommendVal = $data['recommended_val'];
					
					foreach ($arrRecommendVal as $key => $val) {
									
						if (is_null($val)) {
							$arrRecommendVal[$key] = '&lt;Null&gt;';
									
						} elseif ($val === '') {
							$arrRecommendVal[$key] = '&lt;Empty&gt;';
						}
					}
					
					$valRecommendList = join(',', $arrRecommendVal);
				
				} else {
					$valRecommended = $valRecommendList = $data['recommended_val'];
				}
				
				$repairData = array(
					'field'		=> $data['field'],
					'parameter'	=> $data['parameter'],
					'value'		=> $valRecommended
				);
						
				$urlRepair = $urlRepairBase . '&data=' . base64_encode(serialize($repairData));
				
				$problems[] = array(
					'Field'					=> $data['field'],
					'Parameter'				=> $data['parameter'],
					'Value'					=> $data['value'],
					'Recommended values'	=> $valRecommendList,
					'What to do'			=> 'Click <a href="' . $urlRepair . '">here</a> to repair'
				);
			}
			
			$result = array (
				'problems'				=> $problems,
				'check_result_status'	=> 'BAD',
				'check_issue_solution'	=> 'Some configuration fields have structure not compatible with some our modules. ' .
					'Click <a href="' . $urlRepairBase . '">here</a> to repair database structure'
			);
		}

		return $result;
	}
	
	
	//Product Problems section methods
	
	function check_HtmlInProductNames()
	{
		return $this->check_Field($GLOBALS['shoppingCart']->productName, " LIKE '%<%>%'", 'Remove HTML tags from product names');
	}
	
	function check_HtmlInProductModels()
	{
		return $this->check_Field($GLOBALS['shoppingCart']->productModel, " LIKE '%<%>%'", 'Remove HTML tags from product models');
	}
	
	function check_HtmlInProductPrices()
	{
		return $this->check_Field($GLOBALS['shoppingCart']->productPrice, " LIKE '%<%>%'", 'Remove HTML tags from product prices');
	}
	
	function check_ProductPricesByNull()
	{
		return $this->check_Field($GLOBALS['shoppingCart']->productPrice, ' IS null', 'Set valid prices for products');
	}
	
	function check_ProductNamesByEmpty()
	{
		return $this->check_Field($GLOBALS['shoppingCart']->productName, ' IS null OR ' . $GLOBALS['shoppingCart']->productName . " = ''", 'Set valid names for products');
	}
	
	function check_NonPrintableDescriptionChars()
	{
		$errProducts = array();
		$sql = $GLOBALS['shoppingCart']->selectProducts($GLOBALS['shoppingCart']->productDescription);
		$query = $this->sqlQuery($sql);

		while ($dataArray = mysql_fetch_assoc($query)) {
			preg_match_all('/.{8}[^[:print:]\r\n\t].{8}/u', $dataArray[$GLOBALS['shoppingCart']->productDescription], $matches);

			if (sizeof($matches[0])) {
				
				foreach ($matches[0] as $badChar) {
					$dataArray[$GLOBALS['shoppingCart']->productDescription] = htmlspecialchars($dataArray[$GLOBALS['shoppingCart']->productDescription]);
					$badChar = htmlspecialchars($badChar);
					$dataArray[$GLOBALS['shoppingCart']->productDescription] = str_replace($badChar, '<font color="red">' . $badChar . '</font>',
						$dataArray[$GLOBALS['shoppingCart']->productDescription]);
				}
				$errProducts[] = $dataArray;
			}
		}

		return $this->outputResult($errProducts, 'Check non-printable characters in product descriptions');
	}
	
	//Parent products checking
	
	function check_ParentProductsByNull()
	{
		return $this->check_Field($GLOBALS['shoppingCart']->fields['product_parent_id'], ' IS null', 'Set parent ID for this product, it must be an integer value');
	}
	
	function check_ParentProductsByExistance()
	{
		return $this->check_Field('p2.' . $GLOBALS['shoppingCart']->productId,
			' IS null AND p.' . $GLOBALS['shoppingCart']->fields['product_parent_id'] . ' > 0',
			'Set ID of existent product as parent ID for this product');
	}
	
	function check_ParentProductsBySelf()
	{
		return $this->check_Field($GLOBALS['shoppingCart']->fields['product_parent_id'], ' = ' . $GLOBALS['shoppingCart']->productId,
			'Set valid parent ID for this product, it cannot be ID of same product');
	}
	
	function check_ParentProductsByCycling()
	{
		return $this->check_Field('p2.' . $GLOBALS['shoppingCart']->fields['product_parent_id'],
			' = p.' . $GLOBALS['shoppingCart']->productId .
			' AND p.' . $GLOBALS['shoppingCart']->fields['product_parent_id'] . ' <> p.' . $GLOBALS['shoppingCart']->productId,
			'Set valid parent ID for this product, logic loop in parent product ID\'s');
	}
	
	//End of parent products checking
	
	//Category Problems section methods
	
	function check_HtmlInCategoryNames()
	{
		return $this->check_Field($GLOBALS['shoppingCart']->categoryName, " LIKE '%<%>%'", 'Remove HTML tags from category names');
	}
	
	function check_CategoryNamesByEmpty()
	{
		return $this->check_Field($GLOBALS['shoppingCart']->categoryName, ' IS null OR ' . $GLOBALS['shoppingCart']->categoryName . " = ''", 'Set names for categories');
	}
	
	function check_ParentCategoriesByNull()
	{
		return $this->check_Field($GLOBALS['shoppingCart']->categoryParentId, ' IS null', 'Set parent ID for categories, parent category is null now');
	}
	
	function check_ParentCategoriesByExistance()
	{
		return $this->check_Field('c2.' . $GLOBALS['shoppingCart']->categoryId,
			' IS null AND c.' . $GLOBALS['shoppingCart']->categoryParentId . ' > 0',
			'Set correct parent ID for categories, parent category doesn\'t exist');
	}
	
	function check_ParentCategoriesBySelf()
	{
		return $this->check_Field($GLOBALS['shoppingCart']->categoryParentId, ' = ' . $GLOBALS['shoppingCart']->categoryId,
			'Change parent ID for categories, this and parent category id\'s are equal now');
	}
	
	function check_ParentCategoriesByCycling()
	{
		return $this->check_Field('c2.' . $GLOBALS['shoppingCart']->categoryParentId,
			' = c.' . $GLOBALS['shoppingCart']->categoryId .
			' AND c.' . $GLOBALS['shoppingCart']->categoryParentId . ' <> c.' . $GLOBALS['shoppingCart']->categoryId,
			'Change parent ID for categories, logic loop in parent category id\'s');
	}
	
	function check_ParentCategoriesByInteger()
	{
		//This query works on MySQL version >= 4.0.2
		//return $this->check_Field($GLOBALS['shoppingCart']->categoryParentId, ' <> CONVERT(CAST(' . $GLOBALS['shoppingCart']->categoryParentId . ' AS UNSIGNED), CHAR)',
		
		//This query works on MySQL version >= 3.23
		return $this->check_Field($GLOBALS['shoppingCart']->categoryParentId, ' <> CONCAT(' . $GLOBALS['shoppingCart']->categoryParentId . ' + 0, \'\')' ,
			'Set integer parent ID for categories, wrong value now');
	}

	//Configuration Problems section methods
	
	function check_PhysicalPaths()
	{
		
		//Check paths
		if (defined('DIR_FS_ADMIN')) {

			//this is osCommerce administration area
			$pathOption = $GLOBALS['shoppingCart']->getAdminConfigurationPaths();
		
		} else {
			
			//this is store configuration
			$pathOption = $GLOBALS['shoppingCart']->getStoreConfigurationPaths();
		}
		
		$problems = $this->checkPaths($pathOption);
		return $this->outputResult($problems, 'Set full paths in configuration file');
	}
	
	function check_ConfigurationOptions()
	{
	
		//Check paths
		if (defined('DIR_FS_ADMIN')) {
			
			//this is osCommerce administration area
			$keys = $GLOBALS['shoppingCart']->getAdminConfigurationKeys();
		
		} else {
			
			//this is store configuration
			$keys = $GLOBALS['shoppingCart']->getStoreConfigurationKeys();
		}
		
		$problems = $this->checkConfigurationKeys($keys);
		return $this->outputResult($problems, 'Define configuration constants/variables');
	}
	
	function check_ExistancePaths()
	{
		
		//Check paths
		if (defined('DIR_FS_ADMIN')) {
			
			//this is osCommerce administration area
			$pathOption = $GLOBALS['shoppingCart']->getAdminConfigurationPaths();
		
		} else {
			
			//this is store configuration
			$pathOption = $GLOBALS['shoppingCart']->getStoreConfigurationPaths();
		}
		
		$problems = $this->findPaths($pathOption);
		return $this->outputResult($problems, 'Set valid paths in configuration');
	}
	
	function check_ConfigurationValues()
	{
	
		//Check paths
		if (defined('DIR_FS_ADMIN')) {
			
			//this is osCommerce administration area
			$keys = $GLOBALS['shoppingCart']->getAdminConfigurationKeys();
		
		} else {
			
			//this is store configuration
			$keys = $GLOBALS['shoppingCart']->getStoreConfigurationKeys();
		}
		
		$problems = $this->checkValues($keys);
		return $this->outputResult($problems, 'Check leading/trailing slashes in configuration values');
	}
	
	function check_AdminEmail()
	{
		$adminEmail = $this->storeOwnerEmail;
		
		if (!$this->validateEmail($adminEmail)) {
			
			//E-mail address is invalid
			$result = array(
				"check_result_status"	=>	'BAD',
				"check_issue_solution"	=>	'Set valid store admin e-mail address'
			);
		
		} elseif (preg_match('/localhost\.([a-z]>?)*$/i', $adminEmail)) {
			
			//E-mail address is on localhost
			$result = array(
				"check_result_status"	=>	'BAD',
				"check_issue_solution"	=>	'Set non-localhost store admin e-mail address'
			);
		
		} else {
			
			//E-mail is valid
			$result = array(
				"check_result_status"	=>	'OK',
				"check_issue_solution"	=>	'&nbsp;'
			);
		}
		
		$result['check_result'] = htmlspecialchars($adminEmail);
		return $result;
	}
	
	function check_SSLDomain_RegularDomain()
	{
		$result = array(
			"check_result"			=>	"Unknown",
			"check_result_status"	=>	"BAD",
			"check_issue_solution"	=>	"&nbsp;",
		);
		
		if(($this->nosslServer != "")) {
			
			if($this->sslServer !== "") {
				$sslUrlData = parse_url($this->sslServer);
				$nosslUrlData = parse_url($this->nosslServer);
				$_nosslServer = $nosslUrlData['host'];
				$_sslServer = $sslUrlData['host'];
				
				if($_sslServer != $_nosslServer) {
					$result['check_result'] = "Yes";
					$result['check_result_status'] = "ATTENTION";
					$result['check_issue_solution'] = "You're using SSL host, please <a href='mailto:" . TECHNICAL_EMAIL . "?subject=" .
						urlencode('Shopping Cart Diagnostic Utility Results') . "'>contact MagneticOne</a> to include following domains to your license: <b>" . 
						$_nosslServer . "," . $_sslServer . "</b>";
						
				} else {
					$result['check_result'] = "Configured";
					$result['check_result_status'] = "OK";
				}
				
			} else {
				$result['check_result'] = "Not Configured";
				$result['check_result_status'] = "ATTENTION";
				$result['check_issue_solution'] = "Required for Google Checkout module only. Please enable SSL-protected domain in your configuration file";
			}
		}
		
		return $result;
	}
	
	function check_CatalogDir()
	{
		$flagResult = false;
		
		if (defined('DIR_FS_ADMIN')) {
			
			//This is osCommerce admin configuration area
			$rootDir = preg_replace('/[^\/]+\/' . quotemeta($GLOBALS['curFilename']) . '$/i', '', $_SERVER['PHP_SELF']);
			$documentRoot = $_SERVER["DOCUMENT_ROOT"];
			
			if (strpos($GLOBALS['shoppingCart']->catalogDir, $rootDir) === 0) {

				if (file_exists($documentRoot . $GLOBALS['shoppingCart']->catalogDir . 'includes/application_top.php')) {
					
					if ($this->getShoppingCartType() == 'zencart') {
						$checkFiles = array('includes/filenames.php', 'includes/database_tables.php');
				
					} else {
					
						//osCommerce, CRE Loaded
						$checkFiles = array('product_info.php', 'shopping_cart.php');
					}
					
					foreach ($checkFiles as $val) {
						
						if (file_exists($documentRoot . $GLOBALS['shoppingCart']->catalogDir . $val)) {
							$flagResult = true;
							break;
						}
					}
				} 
			}
		
		} else {
			
			//This is store catalog
			$catalogDir = preg_replace('/' . quotemeta($GLOBALS['curFilename']) . '$/i', '', $_SERVER['PHP_SELF']);
			
			if ($GLOBALS['shoppingCart']->catalogDir == $catalogDir) {
				$flagResult = true;
			}
		}
		
		if ($flagResult) {
			$result['check_result'] = $GLOBALS['shoppingCart']->catalogDir;
			$result['check_result_status'] = "OK";
			$result['check_issue_solution'] = "";
		
		} else {
			$result['check_result'] = $GLOBALS['shoppingCart']->catalogDir;
			$result['check_result_status'] = "BAD";
			$result['check_issue_solution'] = "Check correctness of {$GLOBALS['shoppingCart']->configCatalogDir} configuration value";
		}
		
		return $result;
	}
	
	//File System problems section methods
	
	function check_WritableFolders()
	{
		
		$writableFolders = $GLOBALS['shoppingCart']->getWritableFolders();
		$problems = array();
		
		foreach ($writableFolders as $folder) {
			
			if (!is_writable($folder)) {
				$problems[] = array('Folder'		=> $folder,
									'Value'			=> 'Not writable',
									'What to do:'	=> 'Set writable permissions for this folder, folder is not writable');
			}
		}
		
		return $this->outputResult($problems, 'Set writable permissions for folders');
	}
	
	function check_KeyFiles()
	{
		$problems = array();
		
		if (is_array($GLOBALS['shoppingCart']->foreignFiles)) {
			
			foreach ($GLOBALS['shoppingCart']->foreignFiles as $file) {
				
				if (file_exists($file)) {
					$problems[] = array('File'			=> $file,
										'What to do:'	=> 'Possible problems because this file is from another shopping cart');
				}
			}
		}
		
		return $this->outputResult($problems, 'Possible problems because there are files from another shopping cart');
	}
	
	//Other problems section methods
	
	# end of test methods
	
	# confiruration check methods
	
	function checkPaths($paths)
	{
		$problems = array();
		
		foreach ($paths as $key => $value) {
			
			if (!preg_match('/^([a-z]:[\\\\\/]|\/)/i', $value)) {
				$problems[] = array('Option'		=> $key,
									'Value'			=> $value,
									'What to do:'	=> 'Set valid path in configuration constant/variable');
			}
		}
		
		return $problems;
	}
	
	function checkConfigurationKeys($keys)
	{
		$problems = array();
		
		foreach ($keys as $key => $value) {
			
			if (!isset($value['value']) || ($value['value'] === $key)) {
				$problems[] = array('Option'		=> $key,
									'Value'			=> $value['value'],
									'What to do:'	=> 'Define configuration constant/variable');
			}
		}
		
		return $problems;
	}
	
	function findPaths($keys)
	{
		$problems = array();
		
		foreach ($keys as $key => $value) {
			
			if (!file_exists($value)) {
				$problems[] = array('Option'		=> $key,
									'Value'			=> $value,
									'What to do:'	=> 'Set valid path in configuration constant/variable');
			}
		}
		
		return $problems;
	}
	
	function checkValues($keys)
	{
		$problems = array();
		
		foreach ($keys as $key => $value) {
			
			if (defined($key)) {
			
				if ($value['mask'] && $value['value']) {
			
					if (!preg_match('/' . $value['mask'] . '/i', $value['value'])) {
					
						$problems[] = array('Option'		=> $key,
											'Value'			=> $value['value'],
											'What to do:'	=> 'Check leading/trailing slashes in configuration constant/variable value (example: ' . $value['example'] . ')');
					}
				}
			
				if ($value['relative']) {
				
					if (preg_match('|^http://|i', $value['value'])) {
					
						//Configuration path is absolute but must be relative
						$problems[] = array('Option'		=> $key,
											'Value'			=> $value['value'],
											'What to do:'	=> 'Relative path is recommended ' . 
											'(example: ' . $value['example'] . ')');
					
					}
				}
			}
		}
		
		return $problems;
	}
	
	function validateEmail($email)
	{
		$result = false;

		if (preg_match("/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z\.\-]+)\.([a-z]{2,3}$)/i", $email, $check)) {

			//if (getmxrr($check[1] . "." . $check[2])){
				$result = true;
		
			//} else { die('hhh');
				//No MX for $check[1] . $check[2];
			//}
		
		} else {
			//Badly formed address
		}

		return $result;
	}
	
	# end of confiruration check methods
	
	# database check methods
	
	function check_Field($field, $condition, $solution)
	{
		$errProducts = array();
		
		switch ($field) {
			
			case $GLOBALS['shoppingCart']->productName:
			case $GLOBALS['shoppingCart']->productModel:
			case $GLOBALS['shoppingCart']->productPrice:
			case $GLOBALS['shoppingCart']->fields['product_parent_id']:
				$sql = $GLOBALS['shoppingCart']->selectProducts($field, $condition);
				break;
				
			case $GLOBALS['shoppingCart']->categoryName:
			case $GLOBALS['shoppingCart']->categoryParentId:
				$sql = $GLOBALS['shoppingCart']->selectCategories($field, $condition);
				break;
				
			case 'c2.' . $GLOBALS['shoppingCart']->categoryId:
			case 'c2.' . $GLOBALS['shoppingCart']->categoryParentId:
				$sql = $GLOBALS['shoppingCart']->selectCategories($field, $condition, $GLOBALS['shoppingCart']->categoryParentId);
				break;
				
			case 'p2.' . $GLOBALS['shoppingCart']->productId:
			case 'p2.' . $GLOBALS['shoppingCart']->fields['product_parent_id']:
				$sql = $GLOBALS['shoppingCart']->selectProducts($field, $condition, $GLOBALS['shoppingCart']->fields['product_parent_id']);
				break;
		
			default:
				die('Unknown field: ' . $field);
				break;
		}

		$query = $this->sqlQuery($sql);
	 
		while ($dataArray = mysql_fetch_assoc($query)) {
			$errProducts[] = $dataArray;
		}

		return $this->outputResult($errProducts, $solution);
	}
	
	# end of database check methods
	
	function outputResult($problems, $solution)
	{

		if ($errCount = count($problems)) {
			
			$result = array(
				"check_result_status"	=>	"BAD",
				"check_issue_solution"	=>	$solution,
				"problems"				=>	$problems
			);
			
		} else {
			
			$result = array(
				"check_result"			=>	"0 problems",
				"check_result_status"	=>	"OK",
				"check_issue_solution"	=>	"&nbsp;",
			);
		}
		
		return $result;
	}
	
	function showProblems($testData = null)
	{  
		
		if (is_null($testData)) {
			$this->sessionLoad('problems');
			$checkMethods = $this->getCheckMethods();
			$section = urldecode($_GET['section']);
			$test = $checkMethods[$section][$_GET['id']];
		
			if (is_array($GLOBALS['problems'])) {
				$problems = $GLOBALS['problems'][$section][$_GET['id']]['problems'];
				$solution = $GLOBALS['problems'][$section][$_GET['id']]['solution'];
			}
		
			if (empty($problems)) {
			
				//Run test
				$checkMethods = $this->getCheckMethods();
				$method = $checkMethods[$section][$_GET['id']];
				$checkResult = call_user_func(array($this, $method['check_method']));
					
				if ($errCount = count($checkResult['problems'])) {
					$problems = $checkResult['problems'];
					$solution = $checkResult['check_issue_solution'];	
				}
			}
		
		} else {
			
			$test = array(
				'check_title'	=> $testData['title'],
				'check_field'	=> $testData['check_field']
			);
			
			$problems = $testData['problems'];
			$solution = $testData['solution'];
		}

		echo '<h1 align="center">' . ucfirst(htmlspecialchars($test['check_title'])) . '</h1>' . "\n";
		echo '<table border=0 cellpadding=5 width=100% align="center">' . "\n";
		
		if ($errCount = count($problems)) {
			echo '<tr><td><b>Count of problems: <font color="red">' . $errCount . '</font></b></td></tr>' . "\n";
		
		} else {
			echo '<tr><td><b>Count of problems: <font color="green">0</font></b></td></tr>' . "\n";
		}
		
		if ($solution) {
			echo '<tr><td><b>What to do: <font color="gray">' . $solution . '</font></b></td></tr>' . "\n";
		
		}
		
		if ($errCount) {
			echo '<tr><td align="center" style="padding-top:20px;">' . $this->outputProblems($problems, $test['check_field']) . '</td></tr>' . "\n";
		}
		
		echo '</table><br>';
		echo '<center><input type="button" value="Home" onclick="document.location=\'' . 'http://' .
				$_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '\'"></center>';
	}
	
    function outputProblems($products, $testField)
    {
    	$headOutput = '';
    	$heads = array_keys($products[0]);
    	$codeField = '';
    	$codeHeadOutput = '';
    	$testHeadOutput = '';
		
    	if (is_object($GLOBALS['shoppingCart'])) {
    		$codeFieldConditionArray = array($GLOBALS['shoppingCart']->productId, $GLOBALS['shoppingCart']->categoryId, 'Option', 'Folder');
    		$testFieldCondition = ($testField == $GLOBALS['shoppingCart']->productDescription);
    		
    		
    	} else {
    		$codeFieldConditionArray = array('Option', 'Folder');
    		$testFieldCondition = false;
    	}
    	
    	foreach ($heads as $head) {

    		if (in_array($head, $codeFieldConditionArray)) {
    			$codeField = $head;
    			$codeHeadOutput = '<th><b>' . $head . '</b></th>' . "\n";
    			
    		} elseif ($head == 'What to do:') {
    			$headOutput .= '<th width="45%"><b>' . $head . '</b></th>' . "\n";
    			
    		} elseif ($head != $testField) {
    			$headOutput .= '<th><b>' . $head . '</b></th>' . "\n";
    		}
    	}
    	
    	if ($testField && ($testField != $codeField)) {
    		
    		if ($testFieldCondition) {
    			$testColor = 'black';
    		
    		} else {
    			$testColor = 'red';
    		}
    		
    		$testHeadOutput = '<th><b>' . $testField . '</b></th>' . "\n";
    	}
    	
    	$output = '<table class="diagnostic_table" border="0" cellpadding="5" width="100%">' . "\n";
    	$output .= '<tr>' . "\n";
    	$output .= $codeHeadOutput . $testHeadOutput . $headOutput;
    	$output .= '</tr>' . "\n";
    	
    	foreach ($products as $product) {
    		$output .= '<tr>' . "\n";
    		$codeOutput = '';
    		$testOutput = '';
    		$fieldOutput = '';
    		
    		if ($codeField) {
    			$codeOutput = '<td align="' . $this->formatValue($product[$codeField]) . '">' . $product[$codeField] . '</td>' . "\n";
    		}

    		if ($testField && ($testField != $codeField)) {
    			
    			if ($testColor == 'red') {
    				$product[$testField] = htmlspecialchars($product[$testField]);
    			}
    			
    			$testOutput = '<td align="' . $this->formatValue($product[$testField]) . '" style="color:' . $testColor . '; font-weight:bold">' . $product[$testField] . '</td>' . "\n";
    		}
    		
    		foreach ($product as $fieldName => $fieldValue) {
    			
    			if ($fieldName == 'What to do:') {
    				$style =  ' style="color: gray;"';
    			
    			} else {
    				$style = '';
    			}
    			
    			if (!in_array($fieldName, array($codeField, $testField))) {
    				$fieldOutput .= '<td align="' . $this->formatValue($fieldValue) . '"' . $style .'>' . $fieldValue . '</td>' . "\n";
    			}
    		}
    		
    		$output .= $codeOutput . $testOutput . $fieldOutput . '</tr>' . "\n";
    	}
    	
		$output .= '</table>' . "\n";
		return $output;
    }
    
    function formatValue(&$value)
    {
    	
    	if (is_numeric($value)) {
    		$align = 'center';
    					
    	} elseif (is_null($value)) {
    		$align = 'center';
    		$value = 'Null';
    			
    	} else {
    		$align = 'left';
    	}
    	
    	return $align;
    }
	
    function getRevision()
	{
		$matches = array();
		preg_match('(\\d+)', '$Revision: 52 $', $matches);
		return $matches[0];
	}
	
	function repairDatabase($data)
	{
		$allData = $this->getDbDataForUpdate();
		
		if ($data && is_array($data)) {
			
			//Repair only current database problem
			$updData[$data['field']] = array(
				$data['parameter']		=> $data['value'],
				'Autoincrement_type'	=> $allData[$data['field']]['Type']
			);
		
		} else {
			
			//Repair all database problem
			$updData = $allData;
		}
		
		$updFields = array_keys($updData);
		
		  # Get current structore of configuration table
		  
		$curData = $GLOBALS['database']->getTableStructure($GLOBALS['shoppingCart']->tables['configuration'], $updFields);
		
		foreach ($updData as $field => $data) {
			$updData[$field] = array_merge($curData[$field], $data);
		}		  
		
		  # Updating configuration table structure	
		
		$GLOBALS['database']->alterTable($GLOBALS['shoppingCart']->tables['configuration'], $updData);
		
		//Run database test
		
		$checkResult = $this->check_DatabaseStructure();
					
		if ($checkResult['problems']) {
			
			$testData = array(
				'title'		=> 'Database Structure',
				'problems'	=> $checkResult['problems'],
				'solution'	=> $checkResult['check_issue_solution']
			);
				
			$this->showProblems($testData);
		
		} else {
			
			//All problems are resolved
			echo 'Database problems are resolved<br><br>';
			echo '<center><input type="button" value="Home" onclick="document.location=\'' . 'http://' .
				$_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '\'"></center>';
		}
		
		return true;
	}
	
	function getDbDataForUpdate()
	{
		$updData = array();
		$errData = $this->getDatabaseProblems();
		
		foreach ($errData as $data) {
			
			if (is_array($data['recommended_val'])) {
				$value = $data['recommended_val'][0];
				
			} else {
				$value = $data['recommended_val'];
			}
			
			$updData[$data['field']][$data['parameter']] = $value;
		}
		
		return $updData;
	}
	
	function getDatabaseProblems()
	{
		$errData = array();
		
		if ($validStructure = $GLOBALS['shoppingCart']->getValidConfigurationStructure()) {
			
			if ($cfgStructure = $GLOBALS['database']->getTableStructure($GLOBALS['shoppingCart']->tables['configuration'])) {
				
				foreach ($validStructure as $field => $fldStructure) {
					
					foreach ($fldStructure as $paramName => $validVal) {
						
						if ($paramVal = $cfgStructure[$field][$paramName]) {
							$paramVal = strtoupper($paramVal);
						}
						
						$is_error = false;
						
						if (is_array($validVal)) {
						
							if (!in_array($paramVal, $validVal, true)) {
								$is_error = true;
							}
			
						} else {
				
							if ($paramVal !== $validVal) {
								$is_error = true;
							}
						}
						
						if ($is_error) {
							
							$errData[] = array(
								'field'					=> $field,
								'parameter'				=> $paramName,
								'value'					=> $paramVal,
								'recommended_val'		=> $validVal,
							);
						}
					}
				}
				
			} else {
				
				//Structure of configuration table didn't loaded from database
			}
		}
		
		return $errData;
	}
	
	# session save methods
	
	function sessionSave($variable)
	{
		if (is_object($GLOBALS['shoppingCart'])) {
			$GLOBALS['shoppingCart']->sessionSave($variable);
		
		} else {
			$_SESSION[$variable] = $GLOBALS[$variable];
		}
		
		return true;
	}
	
	function sessionLoad($variable)
	{
		if (is_object($GLOBALS['shoppingCart'])) {
			$GLOBALS['shoppingCart']->sessionLoad($variable);
			
		} else {
			$GLOBALS[$variable] = $_SESSION[$variable];
		}
		
		return true;
	}
	
	# session save methods end
}

class m1info_shoppingCart
{
	
	/**
	 * Load data from database to array
	 *
	 * @param string $sql
	 * @param string $arrValue
	 * @param string $arrKey
	 * @return array
	 */
	function dbLoadDataToArray($sql, $arrValue = '', $arrKey = '')
	{
		return $GLOBALS['database']->dbLoadDataToArray($sql, $arrValue, $arrKey);
	}
}

class m1info_oscommerce extends m1info_shoppingCart
{
	var $mysqlHost	= DB_SERVER;
	var $mysqlUser	= DB_SERVER_USERNAME;
	var $mysqlPass	= DB_SERVER_PASSWORD;
	var $mysqlDb	= DB_DATABASE;
	var $mysqlError;
	
	var $sslServer;
	var $nosslServer	= HTTP_SERVER;
	
	var $catalogDir;
	var $configCatalogDir;
			
	var $productId		= 'products_id';
	var $productName	= 'products_name';
	var $productModel	= 'products_model';
	var $productPrice	= 'products_price';
	var $productDescription	= 'products_description';
	
	var $categoryId			= 'categories_id';
	var $categoryName		= 'categories_name';
	var $categoryParentId	= 'parent_id';
	
	var $foreignFiles = array('auth.php');
	
	var $tables = array(
		'categories'	=> TABLE_CATEGORIES,
		'configuration'	=> TABLE_CONFIGURATION,
		'manufacturers'	=> TABLE_MANUFACTURERS,
		'products'		=> TABLE_PRODUCTS
	);
	
	var $fields = array(
		'product_id'		=> 'products_id',
		'category_id'		=> 'categories_id',
		'manufacturer_id'	=> 'manufacturers_id'
	);
	
	function m1info_oscommerce()
	{
		//Set SSL server
		
		if (defined('HTTPS_SERVER')) {
			$this->sslServer = HTTPS_SERVER;
		
		} elseif (defined('HTTPS_CATALOG_SERVER')) {
			$this->sslServer = HTTPS_CATALOG_SERVER;
		}
		
		//Set name of catalog directory
		
		if (defined('DIR_WS_HTTP_CATALOG')) {
			$this->catalogDir = DIR_WS_HTTP_CATALOG;
			$this->configCatalogDir = 'DIR_WS_HTTP_CATALOG';
		
		} elseif (defined('DIR_WS_CATALOG')) {
			$this->catalogDir = DIR_WS_CATALOG;
			$this->configCatalogDir = 'DIR_WS_CATALOG';
		}
		
		//Connect to database
		
		/*mysql_connect($this->mysqlHost, $this->mysqlUser, $this->mysqlPass);
		
		if (mysql_select_db($this->mysqlDb) === false) {
			$this->mysqlError = mysql_error();	
		}*/
		
		return true;
	}
	
	function setDbFields()
	{
		
		  # Set product parent ID field if shopping cart supports subproducts
		  
		$productFields = $GLOBALS['database']->getFields($this->tables['products']);
		
		if (in_array('products_parent_id', $productFields)) {
			$this->fields['product_parent_id'] = 'products_parent_id';
		}
		
		return true;
	}
	
	
 # Database methods
 
 	/**
 	 * Run SQL query via shopping cart function
 	 *
 	 * @param string $sql
 	 * @return mixed
 	 */
 	function dbQuery($sql)
 	{
 		return tep_db_query($sql);
 	}
 	
 	/**
 	 * Return count of rows in SQL result
 	 *
 	 * @param resource $result
 	 * @return integer
 	 */
 	function dbNumRows(&$result)
 	{
 		return tep_db_num_rows($result);
 	}
 	
 	/**
 	 * Return SQL results of current row in array
 	 *
 	 * @param resource $result
 	 * @param integer $resultType
 	 * @return array
 	 */
 	function dbFetchArray(&$result, $resultType = MYSQL_ASSOC)
 	{
 		return tep_db_fetch_array($result);
 	}
 
 # Database methods end
 
	
	function getShoppingCartVersion()
	{
		$version = PROJECT_VERSION;
		return $version;
	}
	
	function selectProducts($field = '', $condition = '', $joinField = '')
	{
		
		  # Set fields for selection query
		  
		$arrFields = array(
			'products_id',
			'products_model',
			'products_quantity',
			'products_image',
			'products_price',
			'products_weight',
			'products_status'
		); 
		
		if ($this->fields['product_parent_id']) {
			$arrFields[] = $this->fields['product_parent_id'];
		}
		
		  # Set condition
		
		if ($field && $condition) {
			$where = " WHERE $field $condition";
		
		} else {
			$where = '';
		}
		
		  # Set SQL query
		  
		switch ($field) {
			
			case $this->productName:
			case $this->productDescription:
				$sql = "SELECT * FROM " . TABLE_PRODUCTS_DESCRIPTION . $where;
				break;
		
			default:
				
				if ($joinField) {
					
					$sql = "SELECT p." . join(', p.', $arrFields)  . " FROM " . TABLE_PRODUCTS . " p " .
						"LEFT JOIN " . TABLE_PRODUCTS . " p2 ON p2.products_id = p." . $joinField . $where;
						
				} else {
					$sql = "SELECT " . join(', ', $arrFields)  . " FROM " . TABLE_PRODUCTS . $where;
				}
				
				break;
		}
		
		return $sql;
	}
	
	function selectCategories($field, $condition, $joinField = '')
	{
		switch ($field) {
			
			case $this->categoryName:
				$sql = "SELECT * FROM " . TABLE_CATEGORIES_DESCRIPTION . " WHERE $field $condition";
				break;
		
			default:
				
				if ($joinField) {
					$sql = "SELECT c.* FROM " . TABLE_CATEGORIES . " c " .
						"LEFT JOIN " . TABLE_CATEGORIES . " c2 ON c2.categories_id = c." . $joinField .
						" WHERE $field $condition";
						
				} else {
					$sql = "SELECT * FROM " . TABLE_CATEGORIES . " WHERE $field $condition";
				}

				break;
		}
		
		return $sql;
	}
	
	function getStoreConfigurationPaths()
	{
		return array(
			'DIR_FS_CATALOG'			=> DIR_FS_CATALOG,
			'DIR_FS_DOWNLOAD'			=> DIR_FS_DOWNLOAD,
			'DIR_FS_DOWNLOAD_PUBLIC'	=> DIR_FS_DOWNLOAD_PUBLIC
		);
	}
	
	function getAdminConfigurationPaths()
	{
		return array(
			'DIR_FS_DOCUMENT_ROOT' 		=> DIR_FS_DOCUMENT_ROOT,
			'DIR_FS_ADMIN' 				=> DIR_FS_ADMIN,
			'DIR_FS_CATALOG' 			=> DIR_FS_CATALOG,
			'DIR_FS_CATALOG_LANGUAGES' 	=> DIR_FS_CATALOG_LANGUAGES,
			'DIR_FS_CATALOG_IMAGES' 	=> DIR_FS_CATALOG_IMAGES,
			'DIR_FS_CATALOG_MODULES' 	=> DIR_FS_CATALOG_MODULES,
			'DIR_FS_BACKUP'				=> DIR_FS_BACKUP
		);
	}
	
	function getStoreConfigurationKeys()
	{
		return array(
		
			'HTTP_SERVER'			=> array(	'value'		=> HTTP_SERVER,
												'mask'		=> END_NOSLASH_MASK,
												'example'	=> 'http://yourhost.com'),
												
			'HTTPS_SERVER'			=> array(	'value'		=> HTTPS_SERVER,
												'mask'		=> END_NOSLASH_MASK,
												'example'	=> 'https://yourhost.com'),
												
			'ENABLE_SSL'			=> array(	'value'		=> ENABLE_SSL),
			'HTTP_COOKIE_DOMAIN'	=> array(	'value'		=> HTTP_COOKIE_DOMAIN),
			'HTTPS_COOKIE_DOMAIN'	=> array(	'value'		=> HTTPS_COOKIE_DOMAIN),
			'HTTP_COOKIE_PATH'		=> array(	'value'		=> HTTP_COOKIE_PATH),
			'HTTPS_COOKIE_PATH'		=> array(	'value'		=> HTTPS_COOKIE_PATH),
			
			'DIR_WS_HTTP_CATALOG'	=> array(	'value'		=> DIR_WS_HTTP_CATALOG,
												'mask'		=> BOTH_SLASHES_MASK,
												'example'	=> '/catalog/'),
												
			'DIR_WS_HTTPS_CATALOG'	=> array(	'value'		=> DIR_WS_HTTPS_CATALOG,
												'mask'		=> '(' . BOTH_SLASHES_MASK . '|^$)',
												'example'	=> '/catalog/'),
												
			'DIR_WS_IMAGES'			=> array(	'value'		=> DIR_WS_IMAGES,
												'mask'		=> END_SLASH_MASK,
												'relative'	=> true,
												'example'	=> 'images/'),
												
			'DIR_WS_ICONS'			=> array(	'value'		=> DIR_WS_ICONS,
												'mask'		=> END_SLASH_MASK,
												'relative'	=> true,
												'example'	=> 'images/icons'),
												
			'DIR_WS_INCLUDES'		=> array(	'value'		=> DIR_WS_INCLUDES,
												'mask'		=> END_SLASH_MASK,
												'relative'	=> true,
												'example'	=> 'includes/'),
												
			'DIR_WS_BOXES'			=> array(	'value'		=> DIR_WS_BOXES,
												'mask'		=> END_SLASH_MASK,
												'relative'	=> true,
												'example'	=> 'includes/boxes/'),
												
			'DIR_WS_FUNCTIONS'		=> array(	'value'		=> DIR_WS_FUNCTIONS,
												'mask'		=> END_SLASH_MASK,
												'relative'	=> true,
												'example'	=> 'includes/functions/'),
												
			'DIR_WS_CLASSES'		=> array(	'value'		=> DIR_WS_CLASSES,
												'mask'		=> END_SLASH_MASK,
												'relative'	=> true,
												'example'	=> 'includes/classes/'),
												
			'DIR_WS_MODULES'		=> array(	'value'		=> DIR_WS_MODULES,
												'mask'		=> END_SLASH_MASK,
												'relative'	=> true,
												'example'	=> 'includes/modules/'),
												
			'DIR_WS_LANGUAGES'		=> array(	'value'		=> DIR_WS_LANGUAGES,
												'mask'		=> END_SLASH_MASK,
												'relative'	=> true,
												'example'	=> 'includes/languages/'),
												
			'DIR_WS_DOWNLOAD_PUBLIC'=> array(	'value'		=> DIR_WS_DOWNLOAD_PUBLIC,
												'mask'		=> END_SLASH_MASK,
												'relative'	=> true,
												'example'	=> 'pub/'),
												
			'DIR_FS_CATALOG'		=> array(	'value'		=> DIR_FS_CATALOG,
												'mask'		=> PHYSICAL_PATH_MASK,
												'example'	=> '/home/httpd/public_html/store/'),
												
			'DIR_FS_DOWNLOAD'		=> array(	'value'		=> DIR_FS_DOWNLOAD),
			'DIR_FS_DOWNLOAD_PUBLIC'=> array(	'value'		=> DIR_FS_DOWNLOAD_PUBLIC),
			
			'DB_SERVER'				=> array(	'value'		=> DB_SERVER),
			'DB_SERVER_USERNAME'	=> array(	'value'		=> DB_SERVER_USERNAME),
			'DB_SERVER_PASSWORD'	=> array(	'value'		=> DB_SERVER_PASSWORD),
			'DB_DATABASE'			=> array(	'value'		=> DB_DATABASE),
			'USE_PCONNECT'			=> array(	'value'		=> USE_PCONNECT),
			'STORE_SESSIONS'		=> array(	'value'		=> STORE_SESSIONS)
		);
	}
	
	function getAdminConfigurationKeys()
	{
		return array(
		
			'HTTP_SERVER'				=> array(	'value'		=> HTTP_SERVER,
													'mask'		=> END_NOSLASH_MASK,
													'example'	=> 'http://yourhost.com'),
												
			'HTTP_CATALOG_SERVER'		=> array(	'value'		=> HTTP_CATALOG_SERVER,
													'mask'		=> END_NOSLASH_MASK,
													'example'	=> 'http://yourhost.com'),
												
			'HTTPS_CATALOG_SERVER'		=> array(	'value'		=> HTTPS_CATALOG_SERVER,
													'mask'		=> END_NOSLASH_MASK,
													'example'	=> 'https://yourhost.com'),
												
			'ENABLE_SSL_CATALOG'		=> array(	'value'		=> ENABLE_SSL_CATALOG),
			'DIR_FS_DOCUMENT_ROOT'		=> array(	'value'		=> DIR_FS_DOCUMENT_ROOT),
						
			'DIR_WS_ADMIN'				=> array(	'value'		=> DIR_WS_ADMIN,
													'mask'		=> BOTH_SLASHES_MASK,
													'example'	=> '/catalog/admin/'),
												
			'DIR_FS_ADMIN'				=> array(	'value'		=> DIR_FS_ADMIN),
			
			'DIR_WS_CATALOG'			=> array(	'value'		=> DIR_WS_CATALOG,
													'mask'		=> BOTH_SLASHES_MASK,
													'example'	=> '/catalog/'),
												
			'DIR_FS_CATALOG'			=> array(	'value'		=> DIR_FS_CATALOG,
													'mask'		=> PHYSICAL_PATH_MASK,
													'example'	=> '/home/httpd/public_html/store/'),
												
			'DIR_WS_IMAGES'				=> array(	'value'		=> DIR_WS_IMAGES,
													'mask'		=> END_SLASH_MASK,
													'relative'	=> true,
													'example'	=> 'images/'),
												
			'DIR_WS_ICONS'				=> array(	'value'		=> DIR_WS_ICONS,
													'mask'		=> END_SLASH_MASK,
													'relative'	=> true,
													'example'	=> 'images/icons'),
												
			'DIR_WS_CATALOG_IMAGES'		=> array(	'value'		=> DIR_WS_CATALOG_IMAGES,
													'mask'		=> BOTH_SLASHES_MASK,
													'example'	=> '/catalog/images/'),
												
			'DIR_WS_INCLUDES'			=> array(	'value'		=> DIR_WS_INCLUDES,
													'mask'		=> END_SLASH_MASK,
													'relative'	=> true,
													'example'	=> 'includes/'),
												
			'DIR_WS_BOXES'				=> array(	'value'		=> DIR_WS_BOXES,
													'mask'		=> END_SLASH_MASK,
													'relative'	=> true,
													'example'	=> 'includes/boxes/'),
												
			'DIR_WS_FUNCTIONS'			=> array(	'value'		=> DIR_WS_FUNCTIONS,
													'mask'		=> END_SLASH_MASK,
													'relative'	=> true,
													'example'	=> 'includes/functions/'),
												
			'DIR_WS_CLASSES'			=> array(	'value'		=> DIR_WS_CLASSES,
													'mask'		=> END_SLASH_MASK,
													'relative'	=> true,
													'example'	=> 'includes/classes/'),
												
			'DIR_WS_MODULES'			=> array(	'value'		=> DIR_WS_MODULES,
													'mask'		=> END_SLASH_MASK,
													'relative'	=> true,
													'example'	=> 'includes/modules/'),
												
			'DIR_WS_LANGUAGES'			=> array(	'value'		=> DIR_WS_LANGUAGES,
													'mask'		=> END_SLASH_MASK,
													'relative'	=> true,
													'example'	=> 'includes/languages/'),
												
			'DIR_WS_CATALOG_LANGUAGES'	=> array(	'value'		=> DIR_WS_CATALOG_LANGUAGES,
													'mask'		=> BOTH_SLASHES_MASK,
													'example'	=> '/catalog/includes/languages/'),				
												
			'DIR_FS_CATALOG_LANGUAGES'	=> array(	'value'		=> DIR_FS_CATALOG_LANGUAGES),
			'DIR_FS_CATALOG_IMAGES'		=> array(	'value'		=> DIR_FS_CATALOG_IMAGES),
			'DIR_FS_CATALOG_MODULES'	=> array(	'value'		=> DIR_FS_CATALOG_MODULES),
			'DIR_FS_BACKUP'				=> array(	'value'		=> DIR_FS_BACKUP),
			
			'DB_SERVER'					=> array(	'value'		=> DB_SERVER),
			'DB_SERVER_USERNAME'		=> array(	'value'		=> DB_SERVER_USERNAME),
			'DB_SERVER_PASSWORD'		=> array(	'value'		=> DB_SERVER_PASSWORD),
			'DB_DATABASE'				=> array(	'value'		=> DB_DATABASE),
			'USE_PCONNECT'				=> array(	'value'		=> USE_PCONNECT),
			'STORE_SESSIONS'			=> array(	'value'		=> STORE_SESSIONS)
		);
	}
	
	function getWritableFolders()
	{
		
		if (file_exists(DIR_FS_CATALOG)) {
			$folders = array(DIR_FS_CATALOG . 'images');
		
		} else {
			$folders = array();
		}
		
		return $folders;
	}
	
	function getAdminEmail()
	{
		
		$query = mysql_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION .
							" WHERE configuration_key = 'STORE_OWNER_EMAIL_ADDRESS'");
		
		if (mysql_num_rows($query)) {
			$queryArr = mysql_fetch_assoc($query);
			$email = $queryArr['configuration_value'];
		
		} else {
			
			//Store admin email not found in configuration table
			$email = null;
		}
		
		return $email;
	}
	
	function getValidConfigurationStructure()
	{
		return array(
			'configuration_id' =>
				array(
					'Type'		=> 'INT(11)',
					'Default'	=> null,
					'Null'		=> '',
					'Key'		=> 'PRI',
					'Extra'		=> 'AUTO_INCREMENT'
				)
		);
	}
	
	function sessionSave($variable)
	{
		$_SESSION[$variable] = $GLOBALS[$variable];
		return true;
	}
	
	function sessionLoad($variable)
	{
		$GLOBALS[$variable] = $_SESSION[$variable];
		return true;
	}
}

class m1info_creloaded extends m1info_oscommerce
{
	
	function m1info_creloaded(){
		return $this->m1info_oscommerce();
	}
	
	function getShoppingCartVersion()
	{
		$version = PROJECT_VERSION;

		if (defined('PROJECT_PATCH')) {
			$version .= ' patch ' . PROJECT_PATCH;
		}
		
		return $version;
	}
	
	function getStoreConfigurationKeys()
	{
		$cfgKeys = m1info_oscommerce::getStoreConfigurationKeys();
		
		$cfgKeys['DIR_WS_TEMPLATES']	= array(	'value'		=> DIR_WS_TEMPLATES,
													'mask'		=> END_SLASH_MASK,
													'relative'	=> true,
													'example'	=> 'templates/');
													
		$cfgKeys['DIR_WS_CONTENT']		= array(	'value'		=> DIR_WS_CONTENT,
													'mask'		=> END_SLASH_MASK,
													'relative'	=> true,
													'example'	=> 'templates/content/');
													
		$cfgKeys['DIR_WS_JAVASCRIPT']	= array(	'value'		=> DIR_WS_JAVASCRIPT,
													'mask'		=> END_SLASH_MASK,
													'relative'	=> true,
													'example'	=> 'includes/javascript/');
								
		return $cfgKeys;
	}
	
	function getAdminConfigurationKeys()
	{
		$cfgKeys = m1info_oscommerce::getAdminConfigurationKeys();
		
		$cfgKeys['DIR_WS_TEMPLATES']	= array(	'value'		=> DIR_WS_TEMPLATES,
													'mask'		=> BOTH_SLASHES_MASK,
													'example'	=> '/catalog/templates/');
													
		$cfgKeys['DIR_WS_HTTP_ADMIN'] = $cfgKeys['DIR_WS_ADMIN'];
		$cfgKeys['DIR_WS_HTTP_ADMIN']['value'] = DIR_WS_HTTP_ADMIN;
		unset($cfgKeys['DIR_WS_ADMIN']);
			
		return $cfgKeys;
	}
	
	function getWritableFolders()
	{
		
		if (file_exists(DIR_FS_CATALOG)) {
			$folders = array(DIR_FS_CATALOG . 'images', DIR_FS_CATALOG . 'cache', DIR_FS_CATALOG . 'tmp');
		
		} else {
			$folders = array();
		}
		
		return $folders;
	}
}

class m1info_zencart extends m1info_oscommerce
{
	
	function m1info_zencart(){
		return $this->m1info_oscommerce();
	}
	
	function getShoppingCartVersion()
	{
		return PROJECT_VERSION_NAME . ' ' . PROJECT_VERSION_MAJOR . '.' . PROJECT_VERSION_MINOR;
	}
	
 # Database methods
 
 	/**
 	 * Run SQL query via shopping cart function
 	 *
 	 * @param string $sql
 	 * @return mixed
 	 */
 	function dbQuery($sql)
 	{
 		return mysql_query($sql);
 	}
 	
 	/**
 	 * Return count of rows in SQL result
 	 *
 	 * @param resource $result
 	 * @return integer
 	 */
 	function dbNumRows(&$result)
 	{
 		return mysql_num_rows($result);
 	}
 	
 	/**
 	 * Return SQL results of current row in array
 	 *
 	 * @param resource $result
 	 * @param integer $resultType
 	 * @return array
 	 */
 	function dbFetchArray(&$result, $resultType = MYSQL_ASSOC)
 	{
 		return mysql_fetch_array($result);
 	}
 
 # Database methods end
	
	function getAdminConfigurationPaths()
	{
		$cfgKeys = m1info_oscommerce::getAdminConfigurationPaths();
		unset($cfgKeys['DIR_FS_DOCUMENT_ROOT']);
		return $cfgKeys;
	}
	
	function getStoreConfigurationKeys()
	{
		$cfgKeys = m1info_oscommerce::getStoreConfigurationKeys();
		
		$cfgKeys['DIR_WS_CATALOG'] = $cfgKeys['DIR_WS_HTTP_CATALOG'];
		$cfgKeys['DIR_WS_CATALOG']['value'] = DIR_WS_CATALOG;
		unset($cfgKeys['DIR_WS_HTTP_CATALOG']);
		
		unset($cfgKeys['HTTP_COOKIE_DOMAIN']);
		unset($cfgKeys['HTTPS_COOKIE_DOMAIN']);
		unset($cfgKeys['HTTP_COOKIE_PATH']);
		unset($cfgKeys['HTTPS_COOKIE_PATH']);
		unset($cfgKeys['DIR_WS_ICONS']);
		unset($cfgKeys['DIR_WS_BOXES']);
		
		$cfgKeys['DIR_WS_DOWNLOAD_PUBLIC']['mask'] = BOTH_SLASHES_MASK;
		
		return $cfgKeys;
	}
	
	function getAdminConfigurationKeys()
	{
		$cfgKeys = m1info_oscommerce::getAdminConfigurationKeys();
		
		unset($cfgKeys['DIR_FS_DOCUMENT_ROOT']);
		
		$cfgKeys['DIR_WS_CATALOG_IMAGES']['mask'] = END_SLASH_MASK;
		$cfgKeys['DIR_WS_CATALOG_LANGUAGES']['mask'] = END_SLASH_MASK;
		
		return $cfgKeys;
	}
	
	function getWritableFolders()
	{
		
		if (file_exists(DIR_FS_CATALOG)) {
			$folders = array(DIR_FS_CATALOG . 'images', DIR_FS_CATALOG . 'cache');
		
		} else {
			$folders = array();
		}
		
		return $folders;
	}
}

class m1info_xcart extends m1info_shoppingCart
{
	var $mysqlHost;
	var $mysqlUser;
	var $mysqlPass;
	var $mysqlDb;
	var $mysqlError;
	var $sslServer;
	var $nosslServer;
	
	var $catalogDir;
	var $configCatalogDir;
	
	var $productId		= 'productid';
	var $productName	= 'product';
	var $productModel	= 'productcode';
	var $productPrice	= 'price';
	var $productDescription	= 'descr';
	
	var $categoryId			= 'categoryid';
	var $categoryName		= 'category';
	var $categoryParentId	= 'parentid';
	
	var $foreignFiles = array('includes/application_top.php');
	
	var $tables;
	
	var $fields = array(
		'product_id'		=> 'productid',
		'category_id'		=> 'categoryid',
		'manufacturer_id'	=> 'manufacturerid'
	);
	 
	function m1info_xcart()
	{
		global $sql_tbl;
		
		$this->mysqlHost	= $GLOBALS['sql_host'];
		$this->mysqlUser	= $GLOBALS['sql_user'];
		$this->mysqlPass	= $GLOBALS['sql_password'];
		$this->mysqlDb		= $GLOBALS['sql_db'];
		
		$this->sslServer	= $GLOBALS['https_location'];
		$this->nosslServer	= $GLOBALS['http_location'];
		
		$this->catalogDir = $GLOBALS['xcart_web_dir'] . '/';
		$this->configCatalogDir = '$xcart_web_dir';
		
		/*mysql_connect($this->mysqlHost, $this->mysqlUser, $this->mysqlPass);
		
		if (mysql_select_db($this->mysqlDb) === false) {
			$this->mysqlError = mysql_error();	
		}*/
		
		$this->tables = array(
			'categories'	=> $sql_tbl['categories'],
			'configuration'	=> $sql_tbl['config'],
			'manufacturers'	=> $sql_tbl['manufacturers'],
			'products'		=> $sql_tbl['products']
		);
	}
	
	function setDbFields()
	{
		return true;
	}
	
	function getShoppingCartVersion()
	{
		return 'X-Cart ' . $GLOBALS['config']['version'];
	}
	
	/**
 	 * Run SQL query via shopping cart function
 	 *
 	 * @param string $sql
 	 * @return mixed
 	 */
 	function dbQuery($sql)
 	{
  		return db_query($sql);
 	}
 	
 	/**
 	 * Return count of rows in SQL result
 	 *
 	 * @param resource $result
 	 * @return integer
 	 */
 	function dbNumRows(&$result)
 	{
 		return db_num_rows($result);
 	}
 	
 	/**
 	 * Return SQL results of current row in array
 	 *
 	 * @param resource $result
 	 * @param integer $resultType
 	 * @return array
 	 */
 	function dbFetchArray(&$result, $resultType = MYSQL_ASSOC)
 	{
 		return db_fetch_array($result, $resultType);
 	}
	
	function selectProducts($field = '', $condition = '')
	{
		global $sql_tbl;
		$selectFields = 'productid, productcode, product, weight, list_price, descr, fulldescr, avail, forsale';
		
		if ($field && $condition) {
			$where = " WHERE $field $condition";
		
		} else {
			$where = '';
		}
		
		switch ($field) {
			
			case $this->productPrice:
				$sql = "SELECT p.$selectFields, price.price FROM {$sql_tbl['products']} p " . 
					"LEFT JOIN {$sql_tbl['pricing']} price ON price.productid = p.productid " .
					"WHERE price." . $this->productPrice . $condition;
				break;
		
			default:
				$sql = "SELECT $selectFields FROM {$sql_tbl['products']} $where";
				break;
		}
		return $sql;
	}
	
	function selectCategories($field, $condition, $joinField = '')
	{
		global $sql_tbl;
		
		if ($joinField) {
			$sql = "SELECT c.* FROM {$sql_tbl['categories']} c " .
				"LEFT JOIN {$sql_tbl['categories']} c2 ON c2.categoryid = c." . $joinField .
				" WHERE $field $condition";
						
		} else {
			$sql = "SELECT categoryid, category, parentid, categoryid_path, description, avail, order_by, meta_keywords " .
				"FROM {$sql_tbl['categories']} WHERE $field $condition";
		}
		
		return $sql;
	}
	
	function getStoreConfigurationPaths()
	{
		return array(
			'$xcart_dir' => $GLOBALS['xcart_dir']
		);
	}
	
	function getStoreConfigurationKeys()
	{
		return array(
			'$sql_host'					=> array(	'value'		=> $GLOBALS['sql_host']),
			'$sql_user'					=> array(	'value'		=> $GLOBALS['sql_user']),
			'$sql_db'					=> array(	'value'		=> $GLOBALS['sql_db']),
			'$sql_password'				=> array(	'value'		=> $GLOBALS['sql_password']),
			
			'$xcart_http_host'			=> array(	'value'		=> $GLOBALS['xcart_http_host'],
													'mask'		=> NONE_SLASHES_MASK,
													'example'	=> 'www.yourhost.com'),
												
			'$xcart_https_host'			=> array(	'value'		=> $GLOBALS['xcart_https_host'],
													'mask'		=> NONE_SLASHES_MASK,
													'example'	=> 'www.yourhost.com'),
												
			'$xcart_web_dir'			=> array(	'value'		=> $GLOBALS['xcart_web_dir'],
													'mask'		=> BEGIN_SLASH_MASK,
													'example'	=> '/xcart')
		);
	}
	
	function getWritableFolders()
	{
		$varDir = $GLOBALS['xcart_dir'] . '/var/';
		if (file_exists($varDir)) {
			
			//X-Cart 4.1
			$folders = array($varDir . '/cache/', $varDir . '/templates_c/', $varDir . '/tmp/');
			
		} elseif (file_exists($GLOBALS['xcart_dir'])) {
			//X-Cart 4.0
			$folders = array($GLOBALS['xcart_dir'] . '/templates_c/');
		
		} else {
			$folders = array();
		}
		
		return $folders;
	}
	
	function getAdminEmail()
	{
		return $GLOBALS['config']["Company"]["site_administrator"];
	}
	
	function getValidConfigurationStructure()
	{
		return array(
			'name' =>
				array(
					'Type'		=> 'VARCHAR(255)',
					'Key'		=> 'PRI'
				)
		);
	}
	
	function sessionSave($variable)
	{
		x_session_save($variable);
		return true;
	}
	
	function sessionLoad($variable)
	{
		x_session_register($variable);
		return true;
	}
}

/**
 * Class of database
 *
 */
class m1info_database {
	
	  # Database query methods
	  
	/**
	 * Process SQL query
	 *
	 * @param string $sql
	 * @return resource
	 */
	function dbQuery($sql)
	{
		return $GLOBALS['shoppingCart']->dbQuery($sql);
	}
	
	/**
	 * Return count of rows in SQL query
	 *
	 * @param resource $query
	 * @return integer
	 */
	function dbNumRows($query)
	{
		return $GLOBALS['shoppingCart']->dbNumRows($query);
	}
	
	/**
	 * Load fields of SQL query row into array
	 *
	 * @param resource $query
	 * @return array
	 */
	function dbFetchArray($query)
	{
		return $GLOBALS['shoppingCart']->dbFetchArray($query);
	}
	
	/**
	 * Load data from database to array
	 *
	 * @param string $sql
	 * @param string $arrValue
	 * @param string $arrKey
	 * @return array
	 */
	function dbLoadDataToArray($sql, $arrValue = '', $arrKey = '')
	{
		$results = array();
		
		$query = $this->dbQuery($sql);
		
		if ($this->dbNumRows($query)) {

			while ($arrQueryResults = $this->dbFetchArray($query)) {

				if (!$arrValue) {
					$value = $arrQueryResults;

				} else {
					$value = $arrQueryResults[$arrValue];
				}

				if (!$arrKey) {
					$results[] = $value;

				} else {
					$results[$arrQueryResults[$arrKey]] = $value;
				}
			}
		}

		return $results;
	}
	
	  # Table structure process methods
	
	/**
	 * Alter database table
	 *
	 * @param string $tblName
	 * @param array $tblStructure
	 * @return boolean
	 */
	function alterTable($tblName, $tblStructure)
	{
		
		  # Set new field structure without primary_key and auto_increment
		  
		$arrFields = array();
		$primaryKey = array();
		
		foreach ($tblStructure as $field => $data) {
			
			$isNull = (strtoupper($data['Null']) == 'YES');
			
			  # If default value is null but field is not null - set this field as primary key with auto_increment
			
			if (!$isNull && is_null($data['Default'])) {
				$data['Key'] = 'PRI';
				$data['Extra'] = 'AUTO_INCREMENT';
			}
			
			  # If auto_increment enabled - set default value null and set primary key
			  
			if (strtoupper($data['Extra']) == 'AUTO_INCREMENT') {
				$data['Default'] = null;
				$data['Key'] = 'PRI';
				
				if ($data['Autoincrement_type']) {
					$data['Type'] = $data['Autoincrement_type'];
				}
			}
			
			$arrData = array(
				$field,
				$data['Type'],
			);
			
			if (strtoupper($data['Null']) != 'YES') {
				$arrData[] = 'NOT NULL';				
			}
			
			if (strtoupper($data['Key']) == 'PRI') {
				
				$primaryKey = array(
					'field'		=> $field,
					'type'		=> $data['Type'],
					'default'	=> $data['Default'],
					'extra'		=> $data['Extra']
				);
			}
				
			if (isset($data['Default']) || $isNull) {
				$arrData[] = 'DEFAULT ' . $this->prepareValue($data['Default']);				
			}

			$arrFields[] = join(' ', $arrData);
		}
		
		$sql = 'ALTER TABLE ' . $tblName . ' MODIFY ' . join(', ', $arrFields);
		$this->dbQuery($sql);
		
		  # If primary key need - remove old and set new primary key
		 
		if ($primaryKey) { 
			$this->dropPrimaryKey($tblName);			
			
			$arrData = array(
				$primaryKey['field'],
				$primaryKey['type'],
				'DEFAULT ' . $this->prepareValue($primaryKey['default']),
				'PRIMARY KEY'
			);
			
			if (strtoupper($primaryKey['extra']) == 'AUTO_INCREMENT') {
				$arrData[] = 'AUTO_INCREMENT';
			}
			
			$sql = 'ALTER TABLE ' . $tblName . ' MODIFY ' . join(' ', $arrData);
			$this->dbQuery($sql);
		}
		
		return true;
	}
	
	/**
	 * Drop primary key from table
	 *
	 * @param string $tblName
	 * @return boolean
	 */
	function dropPrimaryKey($tblName)
	{

		if ($primaryFields = $this->getPrimaryKey($tblName)) {
			
			//Primary key exists in table
			
			//Remove auto_increment from fields
			
			$updData = array();
			$tblStructure = $this->getTableStructure($tblName, $primaryFields);
			
			foreach ($tblStructure as $field => $data) {
			
				if (strtoupper($data['Extra']) == 'AUTO_INCREMENT') {
					$data['Key'] = '';
					$data['Extra'] = '';
				
					if (is_null($data['Default'])) {
						$data['Null'] = 'YES';
					}
				
					$updData[$field] = $data;
				}
			}
			
			if ($updData) {
				$this->alterTable($tblName, $updData);
			}
			
			//Remove primary key
			
			$this->dbQuery('ALTER TABLE ' . $tblName . ' DROP PRIMARY KEY');
		}
		
		return true;
	}
	
	/**
	 * Return column names of primary key
	 *
	 * @param string $tblName
	 * @return array
	 */
	function getPrimaryKey($tblName)
	{
		$sql = "SHOW INDEX FROM " . $tblName;
		$query = $this->dbQuery($sql);
		$result = array();
		
		while ($row = $this->dbFetchArray($query)) {
			
			if (strtoupper($row['Key_name']) == 'PRIMARY') {
				$result[] = $row['Column_name'];
			}
		}
		
		return $result;
	}
	
	/**
	 * Return structure of database table
	 *
	 * @param string $tblName
	 * @param array $fields
	 * @return array
	 */
	function getTableStructure($tblName, $fields = array())
	{
		$tblStructure = array();
		$sql = 'DESCRIBE ' . $tblName;
		$query = $this->dbQuery($sql);
		
		while ($row = $this->dbFetchArray($query)) {
			
			if (!$fields || (in_array($row['Field'], $fields))) {
				
				if (strtoupper($row['Null']) == 'NO') {
					$row['Null'] = '';
				}
				
				$tblStructure[$row['Field']] = $row;
			}
		}
		
		return $tblStructure;
	}
	
	/**
 	 * Return list of database tables
 	 *
 	 * @return array
 	 */
 	function getTables()
 	{
 		static $tables;
 		
 		if (is_null($tables)) {
 			$tables = array();
			$query = $this->dbQuery('SHOW tables');
 			
 			while($row = $this->dbFetchArray($query)) { 
 				$row = array_values($row);
 				$tables[] = $row[0];
 			}
 		}

 		return $tables;
 	}
	
	/**
 	 * Return list of database table fields
 	 *
 	 * @param string $table
 	 * @return array
 	 */
 	function getFields($table)
 	{
 		static $fields;
 		
 		if (is_null($fields[$table])) {
 			$dbTables = $this->getTables();
 			
 			if (!in_array($table, $dbTables)) {
 				$fields[$table] = array();
 				
 			} else {
 				$fields[$table] = $this->dbLoadDataToArray('SHOW fields FROM ' . $table, 'Field');
 			}
 		}
 		
 		return $fields[$table];
 	}
	
	/**
	 * Prepare value for SQL query
	 *
	 * @param mixed $value
	 * @return string
	 */
	function prepareValue($value)
	{
		
		if (is_null($value)) {
			$result = 'NULL';
		
		} elseif (is_string($value)) {
			$result = "'" . $value . "'";
		
		} else {
			$result = $value;
		}
		
		return $result;
	}
}

?>
<html>
<head>
<title>Shopping Cart Diagnostic Utility v 1.0.<?php echo $checker->getRevision() ?></title>
<meta charset="ISO-8859-1">
<style type='text/css'>
		body, td {
			font-family: Tahoma;
			font-size: 12px;
		}
		h1 {
			font-size: 18px;
		}
		p {
			font-size: 12px;
			font-weight:bold;
		}
		table {
			width: 90%;
			font-family: Tahoma;
			font-size: 12px;
		}
			 
		td, th {
			padding-left: 10px;
			height: 26px;
		}
		th{
		 	background-color: #EAEAEA;
		 	font-size: 14px;
		 }
		table.diagnostic_table {
			width: 90%;
			border-right: 1px solid #EAEAEA;
			border-top:	1px solid #EAEAEA;
			border-collapse: collapse;
			font-family: Tahoma;
			font-size: 12px;
		}
		 
		table.diagnostic_table td, table.diagnostic_table th {
			border-left: 1px solid #EAEAEA;
			border-bottom:	1px solid #EAEAEA;
			padding-left: 10px;
			padding-right: 10px;
			height: 26px;
		}
		table.diagnostic_table th{
		 	background-color: #EAEAEA;
		 	font-size: 14px;
		 	border-right: 1px solid #FFFFFF;
		}
		a {
			color: #000000;
		 	font-weight: bold;
		}
		a:hover {text-decoration: none;}
		
		textarea {width: 300px; height: 100px;}
		.comments{width: 300px; border:none;}
		.comments TD {border:none;}
		
		a.error_message {color:red;}
</style>
<script type="text/javascript">
function validForm() {
				
	if( document.getElementById('email').value == "" ) {
		alert('Please enter your e-mail address!');;
		document.getElementById('email').focus();
		return false;
	}

	if(! (/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/.test(document.getElementById('email').value)) )
	{
		alert('Please enter your e-mail address correctly!');;
		document.getElementById('email').focus();
		return false;
	}

	if( document.getElementById('comments').value == "") {
		alert('Comments is required!');
		document.getElementById('comments').focus();
		return false;

	} else {
		return true;
	}
}
</script>
</head>
<body>
<?php

if ($_GET['action'] == 'show_problems') {
	$checker->showProblems();
	
} elseif ($_GET['action'] == 'repair_database') {
	
	//Decoding data for repair
	
	if ($_GET['data']) {
		$arrRepairData = unserialize(base64_decode($_GET['data']));
	
	} else {
		
		//Repair all problems
		$arrRepairData = null;
	}
	
	$checker->repairDatabase($arrRepairData);
		
} else {
	$checker->Run();
?>
<form action="<?php echo $curFilename?>?send" method="POST" onsubmit="return validForm();">
<center>
<p>If you have any technical issues with MagneticOne software, please describe them in "Comments" field and press "Send to MagneticOne"</p>
<table class='comments'>
<tr>
	<td nowrap><span style='color:red;'>*</span>Your e-mail:</td>
	<td><input type='text' name='email' id='email' style='width:200px;' value='<?php echo $checker->storeOwnerEmail; ?>'></td>
</tr>
<tr>
	<td><span style='color:red;'>*</span>Comments:</td>
	<td><textarea name="comments" id="comments"></textarea></td>
</tr>
</table>
</center>
<br><center><input type='button' value='Refresh' onclick='window.location.reload();'>
<input type='submit' value='Send to MagneticOne'>
<p>If you have any improvements suggestions on this utility - <a target='_blank' href='http://bugs.magneticone.com/'>click here</a>
</p></center>
</form>

<?php
}
?>

<center><p>&copy; <a href='http://www.magneticone.com' target='_blank'>MagneticOne</a>, <?php echo date("Y"); ?></center>
</body>
</html>

<?php

if (file_exists('includes/application_bottom.php') && $applicationTopIncluded && !defined('STS_START_CAPTURE')) {
	require_once('includes/application_bottom.php'); 
}
?>