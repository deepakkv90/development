<?php

function tep_show_category_javascript($counter) {

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

//else

//$categories_string .= '<div class=""><a href="'. tep_href_link(FILENAME_DEFAULT, $cPath_new).'" class="lightgray">'.$tree[$counter]['name'].'</a></div>'; 



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
	
		tep_show_category_javascript($tree[$counter]['next_id']);
	
	}

}



$categories_string = '';

$tree = array();

$i=0;

$cPath_array=array();

$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '0' and c.categories_id = '242' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by c.parent_id, sort_order, cd.categories_name");

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

  tep_show_category_javascript($first_element); 
  
} else {

	$categories_string .= '<h5>Designs not available</h5>'; 

}

echo '<div class="box"> <div class="box-content box-category"> <ul id="cat_accordion"> ' . $categories_string . '</ul></div></div>';

unset($cPath_array);
unset($categories_string);
unset($tree);
unset($cat_str);
unset($first_element);


?>
