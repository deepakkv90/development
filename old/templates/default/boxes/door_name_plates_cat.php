<?php

function tep_menu_door_name_plates($counter) {

global $tree, $categories_string, $cPath_array, $cat_str;

global $zoom;

$cat_str = "";

if ($tree[$counter]['parent']==0) {

$cPath_new = 'cPath=' . $counter;

//$categories_ids= tep_has_category_subcategories_parentid($counter);

$category_query_raw = "";

  if(isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id'])) {	
  
		$customer_group_id = tep_get_customers_access_group($_SESSION['customer_id']);	
		
  } else {
  
		$customer_group_id = array("G");
		
  }
	
  $category_query_raw .= tep_get_access_sql('products_group_access', $customer_group_id);
  									  
  $categories_query = tep_db_query("SELECT categories_id 
                                      from " . TABLE_CATEGORIES . " 
                                      WHERE parent_id = '".$counter."' 
                                      $category_query_raw order by sort_order");
   while($catdata = tep_db_fetch_array($categories_query)) {
   		$categories_ids[] = $catdata['categories_id'];
   }

$new_cat= $counter;



if(count($categories_ids)>=1) 

$categories_string .= '<li class="custom_id'.$counter.' cutom-parent-li"><a href="'. tep_href_link(FILENAME_DEFAULT, $cPath_new).'" class="cutom-parent">'.$tree[$counter]['name'].'</a><span class="down"></span>'; 


if(count($categories_ids)>=1) {

$zoom++; 

$categories_string .= '<ul style="display: block;">';


foreach( $categories_ids as $categories_id)

{

$categories_name= tep_has_category_subcategories_name($categories_id);

if ($tree[$counter]['parent'] == 0) { $cPath_new = 'cPath=' .$categories_id; } 

else { $cPath_new = 'cPath=' . $tree[$counter]['path']; }


	if($_GET['cPath'] == $categories_id)	{

		$categories_string .= '<li class="custom_id'.$categories_id.'"><a href="'.tep_href_link(FILENAME_DEFAULT, $cPath_new).'">'.$categories_name.'</a></li>';

	}else	{

		$categories_string .= '<li class="custom_id'.$categories_id.'"><a href="'.tep_href_link(FILENAME_DEFAULT, $cPath_new).'">'.$categories_name.'</a></li>';

	}

}

$cid= ltrim(strstr($_GET['cPath'] ,'_'),'_');

$categories_string .= '</ul></li>';

}


$child_query_new = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cid. "'");

$child_new = tep_db_fetch_array($child_query_new);

$child=$child_new["parent_id"];

if ($new_cat==$child) {

?>

<?php

}



}



	if ($tree[$counter]['next_id'] != false) {
	
		tep_menu_door_name_plates($tree[$counter]['next_id']);
	
	}

}



$categories_string = '';

$tree = array();

$i=0;

$cPath_array=array();

$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '0' and c.categories_id IN ('282','248','281') and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by c.parent_id, sort_order, cd.categories_name");

if(tep_db_num_rows($categories_query)>0) {

	while ($categories = tep_db_fetch_array($categories_query)) {
	
	$tree[$categories['categories_id']] = array('name' => $categories['categories_name'],
	
	'parent' => $categories['parent_id'],
	
	'level' => 0,
	
	'path' => $categories['categories_id'],
	
	'next_id' => false);
		
	
	if (isset($parent_id)) {
	
	$tree[$parent_id]['next_id'] = $categories['categories_id'];
	
	}
		
	$parent_id = $categories['categories_id'];
		
	if (!isset($first_element)) {
	
	$first_element = $categories['categories_id'];
	
	}
	
	$cPath_array[$i]=$categories['categories_id'];
	
	$i++;
	
  } //End while

  tep_menu_door_name_plates($first_element); 
  
} else {

	$categories_string .= '<h5>Designs not available</h5>'; 

}

echo '<div class="box"> <div class="box-content box-category"> <ul id="cat_door_plates_accordion"> ' . $categories_string . '<li><a href="/Table-Number-Plates/c265/">Table Number Plates</a><span class="down"></span><ul><li><a href="/Decorative-Number-Plates/c264/">Decorative Number Plates</a></li><li><a href="/Table-Number-Plates/c255/">Table Number Plates</a></li></ul><li><a href="/Wedding-Name-Plates/c252/">Wedding Plates</a><span class="down"></span><ul><li><a href="/Wedding-Name-Plates/c261/">Wedding Plates With Stand</a></li><li><a href="/Wedding-Name-Plates/Customised-wedding-name-plate/c262/">Customised Wedding Name Plate</a></li><li><a href="/Wedding-Name-Plates/Wedding-name-plate-with-table-number/c263/">Wedding Plate With Number</a></li></ul><li><a href="/Table-Name-Plates/c238/">Table Name Plates</a><span class="down"></span><ul><li><a href="/Free-Standing-Information-Signs/c266/">Standing Information Signs</a></li><li><a href="Table-Name-Signs/c241/">Table Signs</a></li></ul></ul></div></div>';

unset($cPath_array);
unset($categories_string);
unset($tree);
unset($cat_str);
unset($first_element);

?>