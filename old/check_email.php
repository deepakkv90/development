<?php
require('includes/configure.php');
require('includes/filenames.php');
require('includes/database_tables.php');
require('includes/functions/database.php');
require('includes/functions/general.php');
require('includes/functions/html_output.php');
tep_db_connect();
$opt = "true";
if (isset($_GET['email_address'])){

    $email = $_GET['email_address'];  
	
	if(!empty($email)) {
		
		$email_query = tep_db_query("select customers_email_address from customers where customers_email_address = '" . $email . "'");		
		if(tep_db_num_rows($email_query)>0) {
			$arr = tep_db_fetch_array($email_query);
			$opt = 	"false";
		}
    }
	//echo json_encode(array("returnValue"=>$opt)); 	
}
echo $opt;
?>