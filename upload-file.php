<?php
require('includes/application_top.php');

$fldname = $_POST['opid'];

$optcount = $_POST['optcount'];

$opId = explode("_",$fldname);

$custom_file = tep_db_prepare_input($_FILES[$fldname]['name']);

$fileInfo = pathinfo($custom_file);

$randamNumber=md5(microtime().rand(0,999999));	

$myfiles_dir = 'users_files/';

$path = DIR_FS_CATALOG . DIR_WS_IMAGES . $myfiles_dir . $randamNumber.".".$fileInfo['extension']; 

if (move_uploaded_file($_FILES[$fldname]['tmp_name'], $path)) {  	

		$files_array = array(str_replace("^"," ",$custom_file), $path, $opId[1], $optcount);

		echo implode("^",$files_array);	

} else {

	echo "";

}

?>