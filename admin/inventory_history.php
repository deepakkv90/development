<?php
require('includes/configure.php');
require('includes/filenames.php');
require('includes/database_tables.php');
require('includes/functions/database.php');
require('includes/functions/general.php');
tep_db_connect();
//Get Post Variables. The name is the same as
//what was in the object that was sent in the jQuery



if (isset($_GET['pID'])){

    $pID = $_GET['pID'];  
	$type = $_GET['type'];  
	
	$opt = "";
	
		
	if($pID>0) {
				
		$sel_history = tep_db_query("SELECT a.admin_firstname, a.admin_lastname, i.* FROM inventory_history i LEFT JOIN admin a ON (i.admin_user_id = a.admin_id) WHERE i.products_id = '".$pID."' AND i.type='".$type."' ORDER BY i.inventory_history_id DESC");		

		$opt .= '<table border="1" cellspacing="0" cellpadding="5" width="100%" style="background-color:#FFF769;" bordercolor="white">
					  <tr class="smallText">                    
						<td width="20%" class="main main-top main-left" style="padding:5px;"><b>Modification date</b></td>                                 
						<td width="20%" class="main main-top" style="padding:5px;"><b>User</b></td>						  
						<td width="20%" class="main main-top" style="padding:5px;"><b>Avail. Stock Before</b></td>						  
						<td width="20%" class="main main-top" style="padding:5px;"><b>QTY Added</b></td>						  
						<td width="20%" class="main main-top" style="padding:5px;"><b>Avail. Stock After</b></td>						  
					 </tr>';
		
		if(tep_db_num_rows($sel_history)>0) {	
							
			$i=1; 
					
			while($history = tep_db_fetch_array($sel_history)) {
														
				$opt .=  '<tr>							
							<td class="main main-left" style="padding:5px;">'.tep_date_aus_format($history["date_updated"],"long").'</td>
							<td class="main" style="padding:5px;">'.$history["admin_firstname"] . " " . $history["admin_lastname"].'</td>
							<td class="main" style="padding:5px;">'.$history["aq_before"] .'</td>
							<td class="main" style="padding:5px;">'.$history["added_qty"] .'</td>
							<td class="main" style="padding:5px;">'.$history["aq_after"] .'</td>
						</tr>';	
																				
				$i++;
			}
			
						
			
		} else {
			$opt .= '<tr><td colspan="5" class="main main-left" style="padding:5px;" width="100%">No History for this product.</td></tr>';	
		}	
		
		$opt .= '</table>';
	}
	
	echo $opt;
}

?>