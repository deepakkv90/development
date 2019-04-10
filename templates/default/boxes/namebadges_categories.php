<?php



function tep_show_category_javascript($counter) {

global $tree, $categories_string, $cPath_array, $cat_str;

global $zoom;

$cat_str = "";

if ($tree[$counter]['parent']==0) {
	
	$cPath_new = 'cPath=' . $counter;
	
	$categories_ids= tep_has_category_subcategories_parentid($counter);
	
	$new_cat= $counter;
	
	if(count($categories_ids)>=1) 
	
		$categories_string .= '<div class="boxHead">'.$tree[$counter]['name'].'</div>'; 
	
	else
	
		$categories_string .= '<div class="boxHead"><a class="boxmenu" href="'. tep_href_link(FILENAME_DEFAULT, $cPath_new).'" class="lightgray">'.$tree[$counter]['name'].'</a></div>'; 
	
	if(count($categories_ids)>=1) {
		
		$zoom++; 
	
		//$categories_string .= '<ul class="boxMenu">';
	
		foreach( $categories_ids as $categories_id) {
	
			$categories_name= tep_has_category_subcategories_name($categories_id);
	
			if ($tree[$counter]['parent'] == 0) { $cPath_new = 'cPath=' .$categories_id; } 
	
			else { $cPath_new = 'cPath=' . $tree[$counter]['path']; }
			
			//selected category
			if($_GET['cPath'] == $categories_id)	{
				
				$current_category = ' selected ';
			
			} else	{
		
				$current_category = "";
		
			}
			
			$categories_string .= '<a class="boxmenu '.$current_category.'" href="'.tep_href_link(FILENAME_DEFAULT, $cPath_new).'">'. $categories_name. '</a>';
				
		}
	
		$cid= ltrim(strstr($_GET['cPath'] ,'_'),'_');
	
		
	
	}
	
	$child_query_new = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cid. "'");
	
	$child_new = tep_db_fetch_array($child_query_new);
	
	$child=$child_new["parent_id"];
	
	
}

if ($tree[$counter]['next_id'] != false) {

	tep_show_category_javascript($tree[$counter]['next_id']);

}



}

$categories_string = '';

$tree = array();

$i=0;

$cPath_array=array();

$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '0' and c.categories_id IN (24) and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by c.parent_id, c.sort_order, cd.categories_name");

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

}

tep_show_category_javascript($first_element); 

	/*
	$info_box_contents = array();

    $info_box_contents[] = array('align' => 'left',

                                 'text'  => '<font color="' . $font_color . '" align="center">'.$box_heading.'</font>'

                                );

    new infoBoxHeading($info_box_contents);
	*/

    $categories_string3 = '';

    $info_box_contents = array();

    $info_box_contents[] = array('align' => 'left',

                                 'text'  => $categories_string

                                );

    new infoBox($info_box_contents, true);

    if (TEMPLATE_INCLUDE_FOOTER =='true'){

      $info_box_contents = array();

      $info_box_contents[] = array('align' => 'left',

                                   'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')

                                  );

      new infoboxFooter($info_box_contents); 

    }

?>