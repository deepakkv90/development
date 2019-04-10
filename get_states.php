<?php
require('includes/configure.php');
require('includes/filenames.php');
require('includes/database_tables.php');
require('includes/functions/database.php');
require('includes/functions/general.php');
require('includes/functions/html_output.php');
tep_db_connect();
//Get Post Variables. The name is the same as
//what was in the object that was sent in the jQuery



//if (isset($_POST['cID'])){

    $cID = $_POST['cID'];  
	$opt = '';
	if($cID!="" || $cID!=0) {
		
		$zones_query = tep_db_query("select zone_name from zones where zone_country_id = '" . $cID . "' order by zone_name");
		
		if(tep_db_num_rows($zones_query)>0) {
		
			$opt .= "<select name='state' style='width:200px;'>";
			
			while ($zones_values = tep_db_fetch_array($zones_query)) {
	
			  //$zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
			  $opt .= '<option value="'.$zones_values['zone_name'].'"> '.$zones_values['zone_name'].' </option>';
	
			}
	
			$opt .= "</select> <span class='inputRequirement'>*</span>";
			
		} else {
		
			$opt .= '<input type="text" name="state"> <span class="inputRequirement">*</span>';
			
		}

    } else {
		
		 $opt .= '<input type="text" name="state"> <span class="inputRequirement">*</span>';

    }
	
		
	//Because we want to use json, we have to place things in an array and encode it for json.
	//This will give us a nice javascript object on the front side.
	echo json_encode(array("returnValue"=>$opt)); 
//}

?>
