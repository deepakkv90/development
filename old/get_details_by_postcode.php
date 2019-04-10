<?php
require('includes/configure.php');
require('includes/filenames.php');
require('includes/database_tables.php');
require('includes/functions/database.php');
require('includes/functions/general.php');
require('includes/functions/html_output.php');
tep_db_connect();
$opt = 1;
if (isset($_POST['pCode'])){

    $pCode = $_POST['pCode'];  
	
	if(!empty($pCode)) {
		
		$pc_query = tep_db_query("select * from postcode where postcode = '" . $pCode . "'");		
		if(tep_db_num_rows($pc_query)>0) {			
			$opt = 	0;
		}
    }	
}
echo json_encode(array("returnValue"=>$opt)); 
?>