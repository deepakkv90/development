<?php
require('includes/configure.php');
require('includes/filenames.php');
require('includes/database_tables.php');
require('includes/functions/database.php');
require('includes/functions/general.php');
tep_db_connect();
//Get Post Variables. The name is the same as
//what was in the object that was sent in the jQuery



if (isset($_POST['aID'])){

    $aID = $_POST['aID'];  
	


	$opt = "";
	//$opt . = "<h1 class='pageHeading'>Feedback History</h1>";
		
	if($aID>0) {
		
		$artwork_options = tep_get_artwork_option($aID);
		
		$sel_feedback = tep_db_query("SELECT * FROM artwork_feedback WHERE artwork_id='".$aID."' order by artwork_feedback_id desc");		

		if(tep_db_num_rows($sel_feedback)>0) {	
							
			
			$opt .= '<table border="0" cellspacing="0" cellpadding="0"><tr><td style="padding:5px;"><h1 class="pageHeading" style="border: medium none;">Feedback History</h1></td></tr></table>';
			
			$opt .= '<table border="0" cellspacing="0" cellpadding="5" width="100%">
					  <tr class="smallText">                    
						<td width="15%" class="main main-top main-left" style="padding:5px;"><b>Date Added</b></td>                                 
						<td width="10%" class="main main-top" style="padding:5px;"><b>Status</b></td>
						<td width="10%" class="main main-top" style="padding:5px;"><b>User</b></td>					
						<td width="12%" class="main main-top" style="padding:5px;"><b>Option</b></td>			 
						<td width="10%" class="main main-top" style="padding:5px;" align="center"><b>Attachment</b></td>
						<td width="40%" class="main main-top" style="padding:5px;"><b>Comment</b></td>   
					 </tr>';
			$i=1; 
			
			$revision_count = tep_get_artwork_revision_count($aID);
			
			while($artwork_content = tep_db_fetch_array($sel_feedback)) {
				
				if($artwork_content['attachment']=="") {
					$att = "N/A";
				} else {
					$att = '<a href="'.$artwork_content['attachment'].'" target="_blank" style="text-decoration:none;"><img src="images/attachment.png" border="0"><br><font style="text-decoration:underline; color:#CC3300;">'.$artwork_content['attachment_name'].'</font></a>';
				}
				
				if($artwork_content['user_type']=="admin") {				
					$posted_admin = tep_get_admin_details($artwork_content['posted_by']);
					$username = $posted_admin['admin_firstname']." ".$posted_admin['admin_lastname']; 
				} else {
					//Get customer info
					$customers_query = tep_db_query("select customers_lastname, customers_firstname from customers WHERE customers_id = " . $artwork_content['posted_by']);        					
					$customers_array = tep_db_fetch_array($customers_query);
					$username = $customers_array['customers_firstname']." ".$customers_array['customers_lastname']; 
				}
				
				if($artwork_content['status']=="revision") {
					$status = $artwork_content['status'] . " " . $revision_count;
					$revision_count--;
				} else {
					$status = $artwork_content['status'];
				}
										
				$opt .=  '<tr>							
							<td class="main main-left" style="padding:5px;">'.date("d-m-Y H:i:g", strtotime($artwork_content['posted_date'])).'</td>
							<td class="main" style="padding:5px;">'.$status.'</td>
							<td class="main" style="padding:5px;">'.$username.'</td>';
							$p=1;
							foreach($artwork_options as $options=>$ops) {
								if($artwork_content['artwork_option_id']==$ops['artwork_option_id']) {
									$opt .= '<td class="main" style="padding:5px;">'.$ops['option_name'].'</td>';
								}
								$p++;
							}							
						$opt .= '<td class="main" style="padding:5px;">'.$att.'</td>					
							<td class="main" style="padding:5px;">'.stripslashes($artwork_content['feedback']).'</td>							
						</tr>';	
																				
				$i++;
			}
			
						
			
		} else {
			$opt .= '<tr><td colspan="6" class="main main-left" style="padding:5px;">No Feedback available.</td></tr>';	
		}	
		
		$opt .= '</table>';
	}
	//Because we want to use json, we have to place things in an array and encode it for json.
	//This will give us a nice javascript object on the front side.
	echo json_encode(array("returnValue"=>$opt)); 
}

?>
