<?php
require('includes/configure.php');
require('includes/filenames.php');
require('includes/database_tables.php');
require('includes/functions/database.php');
tep_db_connect();
//Get Post Variables. The name is the same as
//what was in the object that was sent in the jQuery
if (isset($_POST['sendValue'])){
    $value = $_POST['sendValue'];  
}else{
    $value = "";
}

$sel_pro = tep_db_query("SELECT op.orders_products_id, op.products_name FROM orders_products op LEFT JOIN products p ON op.products_id=p.products_id WHERE op.orders_id='".$value."' and (p.badge_data!='' OR p.badge_data IS NOT NULL)");
if(tep_db_num_rows($sel_pro)>0) {
	$opt = '<select name="product_name" id="product_name" style="width:185px;" validate="required:true">';
	while($products = tep_db_fetch_array($sel_pro)) {	
		$opt .= "<option value='".$products['orders_products_id']."'> " . $products['products_name'] . " </option>";
	}
	$opt .= '</select>';
} else {
	$opt = '<input type="text" name="product_name" id="product_name" validate="required:true">';
}

//Because we want to use json, we have to place things in an array and encode it for json.
//This will give us a nice javascript object on the front side.
echo json_encode(array("returnValue"=>$opt)); 

?>
