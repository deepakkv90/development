<?php
/*
  $Id: quick_updates.php,v 2.3 2003/06/04 11:44:34 HRB Exp $

  
*/

  require('includes/application_top.php');

  //require(DIR_WS_INCLUDES . 'template_top.php');

//page select fix v2.8.2 June 27, 2009

if (isset($_GET['row_by_page'])) {   $row_by_page = (int)$_GET['row_by_page'];}  
if (isset($_GET['manufacturer'])) {   $manufacturer = (int)$_GET['manufacturer'];} else { $manufacturer = ""; } 
if (isset($_GET['sort_by'])) {   $sort_by = $_GET['sort_by'];}  
if (isset($_GET['page'])) {   $page = $_GET['page'];}


if (isset($_GET['sel_option'])) {   $sel_option = (int)$_GET['sel_option'];} else { $sel_option = ""; } 

//end page select fix v2.8.2
 ($row_by_page) ? define('MAX_DISPLAY_ROW_BY_PAGE' , $row_by_page ) : $row_by_page = MAX_DISPLAY_SEARCH_RESULTS; define('MAX_DISPLAY_ROW_BY_PAGE' , MAX_DISPLAY_SEARCH_RESULTS );

//// Tax Row
    $tax_class_array = array(array('id' => '0', 'text' => NO_TAX_TEXT));
    $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while ($tax_class = tep_db_fetch_array($tax_class_query)) {
      $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 'text' => $tax_class['tax_class_title']);
    }

////Info Row pour le champ fabriquant
        $manufacturers_array = array(array('id' => '0', 'text' => NO_MANUFACTURER));
        $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
        while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
                $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                'text' => $manufacturers['manufacturers_name']);
        }

// Display the list of the manufacturers
function manufacturers_list(){
        global $manufacturer;

        $manufacturers_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name from " . TABLE_MANUFACTURERS . " m order by m.manufacturers_name ASC");
        $return_string = '<select name="manufacturer" onChange="this.form.submit();">';
        $return_string .= '<option value="' . 0 . '">' . TEXT_ALL_MANUFACTURERS . '</option>';
        while($manufacturers = tep_db_fetch_array($manufacturers_query)){
                $return_string .= '<option value="' . $manufacturers['manufacturers_id'] . '"';
                if($manufacturer && $manufacturers['manufacturers_id'] == $manufacturer) $return_string .= ' SELECTED';
                $return_string .= '>' . $manufacturers['manufacturers_name'] . '</option>';
        }
        $return_string .= '</select>';
        return $return_string;
}


  function tep_has_category_subcategories($category_id) {
    $child_category_query = tep_db_query("select count(*) as count from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
    $child_category = tep_db_fetch_array($child_category_query);

    if ($child_category['count'] > 0) {
      return true;
    } else {
      return false;
    }
  }
  
##// Uptade database
  switch ($_GET['action']) {
    case 'update' :
      $count_update=0;
      $item_updated = array();
	  
	  //Aug 17 2012
	  $prev_aq = array();
	  $aft_aq = array();
	  $added_aq = array();
	  
                if($_POST['product_new_model']){
                   foreach($_POST['product_new_model'] as $id => $new_model) {
                         if (trim($_POST['product_new_model'][$id]) != trim($_POST['product_old_model'][$id])) {
                           $count_update++;
                           $item_updated[$id] = 'updated';
                           mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_model='" . $new_model . "', products_last_modified=now() WHERE products_id=$id");
                         }
                   }
                }
                  if($_POST['product_new_name']){
                   foreach($_POST['product_new_name'] as $id => $new_name) {
                         if (trim($_POST['product_new_name'][$id]) != trim($_POST['product_old_name'][$id])) {
                           $count_update++;
                           $item_updated[$id] = 'updated';
                           mysql_query("UPDATE " . TABLE_PRODUCTS_DESCRIPTION . " SET products_name='" . $new_name . "' WHERE products_id=$id and language_id=" . $languages_id);
                           mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_last_modified=now() WHERE products_id=$id");
                         }
                   }
                }
                  if($_POST['product_new_price']){
                   foreach($_POST['product_new_price'] as $id => $new_price) {
                         if ($_POST['product_new_price'][$id] != $_POST['product_old_price'][$id] && $_POST['update_price'][$id] == 'yes') {
                           $count_update++;
                           $item_updated[$id] = 'updated';
                           mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_price=$new_price, products_last_modified=now() WHERE products_id=$id");
                         }
                   }
                }
            
                if($_POST['product_new_weight']){
                   foreach($_POST['product_new_weight'] as $id => $new_weight) {
                         if ($_POST['product_new_weight'][$id] != $_POST['product_old_weight'][$id]) {
                           $count_update++;
                           $item_updated[$id] = 'updated';
                           mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_weight=$new_weight, products_last_modified=now() WHERE products_id=$id");
                         }
                   }
                }
                if($_POST['product_new_quantity']){
                   foreach($_POST['product_new_quantity'] as $id => $new_quantity) {
                         if ($_POST['product_new_quantity'][$id] != $_POST['product_old_quantity'][$id]) {
                           $count_update++;
                           $item_updated[$id] = 'updated';
						   
						   //Updated Aug 17 2012
						   $prev_aq[$id] = $_POST['product_old_quantity'][$id];
						   $aft_aq[$id] = $_POST['product_new_quantity'][$id];
						   $added_aq[$id] = $_POST['product_new_quantity'][$id] - $_POST['product_old_quantity'][$id];
						   
                           mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_quantity=$new_quantity, products_last_modified=now() WHERE products_id=$id");
                         } else {
							//Updated Aug 17 2012
						   $prev_aq[$id] = $_POST['product_old_quantity'][$id];
						   $aft_aq[$id] = $_POST['product_new_quantity'][$id];
						   $added_aq[$id] = $_POST['product_new_quantity'][$id] - $_POST['product_old_quantity'][$id];
						 }
                   }
                }
				
				//July 18 2012 - START
				if($_POST['product_progressed_quantity']){
                   foreach($_POST['product_progressed_quantity'] as $id => $new_quantity) {
                         if ($_POST['product_progressed_quantity'][$id] != $_POST['product_old_progressed_quantity'][$id]) {
                           $count_update++;
                           $item_updated[$id] = 'updated';
						   
						   //Updated Aug 17 2012
						   $prev_pq[$id] = $_POST['product_old_progressed_quantity'][$id];
						   $aft_pq[$id] = $_POST['product_progressed_quantity'][$id];
						   $added_pq[$id] = $_POST['product_progressed_quantity'][$id] - $_POST['product_old_progressed_quantity'][$id];
						   
                           mysql_query("UPDATE " . TABLE_PRODUCTS . " SET progressed_quantity=$new_quantity, products_last_modified=now() WHERE products_id=$id");
                         } else {
							
							//Updated Aug 17 2012
						   $prev_pq[$id] = $_POST['product_old_progressed_quantity'][$id];
						   $aft_pq[$id] = $_POST['product_progressed_quantity'][$id];
						   $added_pq[$id] = $_POST['product_progressed_quantity'][$id] - $_POST['product_old_progressed_quantity'][$id];
						   
						 }
                   }
                }
				
				if($_POST['product_overall_quantity']){
                   foreach($_POST['product_overall_quantity'] as $id => $new_quantity) {
                         if ($_POST['product_overall_quantity'][$id] != $_POST['product_old_overall_quantity'][$id]) {
                           $count_update++;
                           $item_updated[$id] = 'updated';
						   
						   //Updated Aug 17 2012
						   $prev_oq[$id] = $_POST['product_old_overall_quantity'][$id];
						   $aft_oq[$id] = $_POST['product_overall_quantity'][$id];
						   $added_oq[$id] = $_POST['product_overall_quantity'][$id] - $_POST['product_old_overall_quantity'][$id];
						   
                           mysql_query("UPDATE " . TABLE_PRODUCTS . " SET overall_quantity=$new_quantity, products_last_modified=now() WHERE products_id=$id");
                         } else {
							//Updated Aug 17 2012
						   $prev_oq[$id] = $_POST['product_old_overall_quantity'][$id];
						   $aft_oq[$id] = $_POST['product_overall_quantity'][$id];
						   $added_oq[$id] = $_POST['product_overall_quantity'][$id] - $_POST['product_old_overall_quantity'][$id];
						 }
                   }
                }
				//July 18 2012 - END
                if($_POST['product_new_manufacturer']){
                   foreach($_POST['product_new_manufacturer'] as $id => $new_manufacturer) {
                         if ($_POST['product_new_manufacturer'][$id] != $_POST['product_old_manufacturer'][$id]) {
                           $count_update++;
                           $item_updated[$id] = 'updated';
                           mysql_query("UPDATE " . TABLE_PRODUCTS . " SET manufacturers_id=$new_manufacturer, products_last_modified=now() WHERE products_id=$id");
                         }
                   }
                }
                if($_POST['product_new_image']){
                   foreach($_POST['product_new_image'] as $id => $new_image) {
                         if (trim($_POST['product_new_image'][$id]) != trim($_POST['product_old_image'][$id])) {
                           $count_update++;
                           $item_updated[$id] = 'updated';
                           mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_image='" . $new_image . "', products_last_modified=now() WHERE products_id=$id");
                         }
                   }
                }
                   if($_POST['product_new_status']){
                           foreach($_POST['product_new_status'] as $id => $new_status) {
                                 if ($_POST['product_new_status'][$id] != $_POST['product_old_status'][$id]) {
                                   $count_update++;
                                   $item_updated[$id] = 'updated';
                                   tep_set_product_status($id, $new_status);
                                   mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_last_modified=now() WHERE products_id=$id");

                                 }
                           }
                }
                   if($_POST['product_new_tax']){
                           foreach($_POST['product_new_tax'] as $id => $new_tax_id) {
                                 if ($_POST['product_new_tax'][$id] != $_POST['product_old_tax'][$id]) {
                                   $count_update++;
                                   $item_updated[$id] = 'updated';
                                   mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_tax_class_id=$new_tax_id, products_last_modified=now() WHERE products_id=$id");
                                 }
                           }
                }
     $count_item = array_count_values($item_updated);
     if ($count_item['updated'] > 0) { $messageStack->add($count_item['updated'].' '.TEXT_PRODUCTS_UPDATED . " $count_update " . TEXT_QTY_UPDATED, 'success'); 
				
		//To track users when updating stock :: Aug 10 2012
		$date_updated = date("Y-m-d h:i:s");
		foreach($item_updated as $id=>$pid) {						
			
			
			tep_db_query("INSERT INTO inventory_history SET products_id='".$id."', type='1', admin_user_id='".$_SESSION['login_id']."', date_updated='".$date_updated."', aq_before='".$prev_aq[$id]."', added_qty='".$added_aq[$id]."', aq_after='".$aft_aq[$id]."'");			
			
			//Update overall qty
			$overall_qty = $aft_aq[$id] + $aft_pq[$id];
			mysql_query("UPDATE " . TABLE_PRODUCTS . " SET overall_quantity='".$overall_qty."' WHERE products_id=$id");
			
			/*
			echo "<br><br><br>====================".$id ."<br>";
			
				echo "&nbsp;&nbsp;Avail Qty:<br>";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;".$prev_aq[$id];
				echo "<br>";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;".$added_aq[$id];
				echo "<br>";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;".$aft_aq[$id];
				echo "<br><br>";
				
				echo "&nbsp;&nbsp;Prog Qty:<br>";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;".$prev_pq[$id];
				echo "<br>";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;".$added_pq[$id];
				echo "<br>";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;".$aft_pq[$id];
				echo "<br><br>";
				
				echo "&nbsp;&nbsp;Over Qty:<br>";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;".$prev_oq[$id];
				echo "<br>";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;".$added_oq[$id];
				echo "<br>";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;".$aft_oq[$id];
				echo "<br><br>";
			*/	
			
		}
		//To track users when updating stock :: Aug 10 2012
		
	 } //exit;
     break;

     case 'calcul' :
      if ($_POST['spec_price']) $preview_global_price = 'true';
     break;
	 
	 case 'update_option' :
		if($sel_option==2) {
			
			$count_update=0;
			$item_updated = array();
			
			//Aug 17 2012
			$prev_aq = array();
			$aft_aq = array();
			$added_aq = array();
	  
                  if($_POST['product_options_values_new_name']){
                   foreach($_POST['product_options_values_new_name'] as $id => $new_name) {
                         if (trim($_POST['product_options_values_new_name'][$id]) != trim($_POST['product_old_name'][$id])) {
                           $count_update++;
                           $item_updated[$id] = 'updated';
                           mysql_query("UPDATE " . TABLE_PRODUCTS_OPTIONS_VALUES . " SET products_options_values_name='" . $new_name . "' WHERE products_options_values_id=$id AND language_id='".(int)$languages_id."'");
                         }
                   }
                }
                  if($_POST['product_options_values_new_price']){
                   foreach($_POST['product_options_values_new_price'] as $id => $new_price) {
                         if (trim($_POST['product_options_values_new_price'][$id]) != trim($_POST['product_old_price'][$id])) {
                           $count_update++;
                           $item_updated[$id] = 'updated';                           
                           mysql_query("UPDATE " . TABLE_PRODUCTS_OPTIONS_VALUES . " SET options_values_price='".$new_price."' WHERE products_options_values_id=$id AND language_id='".(int)$languages_id."'");
                         }
                   }
                }
                  if($_POST['product_options_values_new_quantity']){
                   foreach($_POST['product_options_values_new_quantity'] as $id => $new_quantity) {
                         if ($_POST['product_options_values_new_quantity'][$id] != $_POST['product_old_quantity'][$id]) {
                           $count_update++;
                           $item_updated[$id] = 'updated';
						   
						   //Updated Aug 17 2012
						   $prev_aq[$id] = $_POST['product_old_quantity'][$id];
						   $aft_aq[$id] = $_POST['product_options_values_new_quantity'][$id];
						   $added_aq[$id] = $_POST['product_options_values_new_quantity'][$id] - $_POST['product_old_quantity'][$id];
						   
                           mysql_query("UPDATE " . TABLE_PRODUCTS_OPTIONS_VALUES . " SET quantity='".$new_quantity."' WHERE products_options_values_id=$id AND language_id='".(int)$languages_id."'");
                         } else {
							//Updated Aug 17 2012
						   $prev_aq[$id] = $_POST['product_old_quantity'][$id];
						   $aft_aq[$id] = $_POST['product_options_values_new_quantity'][$id];
						   $added_aq[$id] = $_POST['product_options_values_new_quantity'][$id] - $_POST['product_old_quantity'][$id];
						 }
                   }
                }
            
                if($_POST['product_options_values_new_progressed_quantity']){
                   foreach($_POST['product_options_values_new_progressed_quantity'] as $id => $new_pqty) {
                         if ($_POST['product_options_values_new_progressed_quantity'][$id] != $_POST['product_old_progressed_quantity'][$id]) {
                           $count_update++;
                           $item_updated[$id] = 'updated';
						   
						   //Updated Aug 17 2012
						   $prev_pq[$id] = $_POST['product_old_progressed_quantity'][$id];
						   $aft_pq[$id] = $_POST['product_options_values_new_progressed_quantity'][$id];
						   $added_pq[$id] = $_POST['product_options_values_new_progressed_quantity'][$id] - $_POST['product_old_progressed_quantity'][$id];
						   
                           mysql_query("UPDATE " . TABLE_PRODUCTS_OPTIONS_VALUES . " SET progressed_quantity='".$new_pqty."' WHERE products_options_values_id=$id AND language_id='".(int)$languages_id."'");
                         } else {
							//Updated Aug 17 2012
						   $prev_pq[$id] = $_POST['product_old_progressed_quantity'][$id];
						   $aft_pq[$id] = $_POST['product_options_values_new_progressed_quantity'][$id];
						   $added_pq[$id] = $_POST['product_options_values_new_progressed_quantity'][$id] - $_POST['product_old_progressed_quantity'][$id];
						 }
                   }
                }
                if($_POST['product_options_values_new_overall_quantity']){
                   foreach($_POST['product_options_values_new_overall_quantity'] as $id => $new_oqty) {
                         if ($_POST['product_options_values_new_overall_quantity'][$id] != $_POST['product_old_overall_quantity'][$id]) {
                           $count_update++;
                           $item_updated[$id] = 'updated';
						   
						   //Updated Aug 17 2012
						   $prev_oq[$id] = $_POST['product_old_overall_quantity'][$id];
						   $aft_oq[$id] = $_POST['product_options_values_new_overall_quantity'][$id];
						   $added_oq[$id] = $_POST['product_options_values_new_overall_quantity'][$id] - $_POST['product_old_overall_quantity'][$id];
						   
                           mysql_query("UPDATE " . TABLE_PRODUCTS_OPTIONS_VALUES . " SET overall_quantity=$new_oqty WHERE products_options_values_id=$id AND language_id ='".(int)$languages_id."'");
                         } else {
							//Updated Aug 17 2012
						   $prev_oq[$id] = $_POST['product_old_overall_quantity'][$id];
						   $aft_oq[$id] = $_POST['product_options_values_new_overall_quantity'][$id];
						   $added_oq[$id] = $_POST['product_options_values_new_overall_quantity'][$id] - $_POST['product_old_overall_quantity'][$id];
						 }
                   }
                }
				
				/*
				//July 18 2012 - START
				if($_POST['product_options_values_new_image']){
                   foreach($_POST['product_options_values_new_image'] as $id => $new_image) {
                         if ($_POST['product_options_values_new_image'][$id] != $_POST['product_old_image'][$id]) {
                           $count_update++;
                           $item_updated[$id] = 'updated';
                           mysql_query("UPDATE " . TABLE_PRODUCTS_OPTIONS_VALUES . " SET picture=$new_image WHERE products_options_values_id=$id AND language_id ='".(int)$languages_id."'");
                         }
                   }
                }
				*/		
				//July 18 2012 - END
				
			$count_item = array_count_values($item_updated);
			if ($count_item['updated'] > 0) { 
				$messageStack->add($count_item['updated'].' '.TEXT_PRODUCTS_UPDATED . " $count_update " . TEXT_QTY_UPDATED, 'success');
				
				//To track users when updating stock :: Aug 10 2012
				$date_updated = date("Y-m-d h:i:s");
				foreach($item_updated as $id=>$pid) {						
					tep_db_query("INSERT INTO inventory_history SET products_id='".$id."', type='2', admin_user_id='".$_SESSION['login_id']."', date_updated='".$date_updated."' , aq_before='".$prev_aq[$id]."', added_qty='".$added_aq[$id]."', aq_after='".$aft_aq[$id]."'");		
					
					//Update overall qty
					$overall_qty = $aft_aq[$id] + $aft_pq[$id];
					mysql_query("UPDATE " . TABLE_PRODUCTS_OPTIONS_VALUES . " SET overall_quantity='".$overall_qty."' WHERE products_options_values_id=$id AND language_id ='".(int)$languages_id."'");
			
				}
				//To track users when updating stock :: Aug 10 2012
				
			}	
			
		}
	 break;
 }

//// explode string parameters from preview product
     if($info_back && $info_back!="-") {
       $infoback = explode('-',$info_back);
       $sort_by = $infoback[0];
       $page =  $infoback[1];
       $current_category_id = $infoback[2];
       $row_by_page = $infoback[3];
           $manufacturer = $infoback[4];
     }

//// define the step for rollover lines per page
   $row_bypage_array = array(array());
   for ($i = 10; $i <=100 ; $i=$i+5) {
      $row_bypage_array[] = array('id' => $i,
                                  'text' => $i);
   }

##// Let's start displaying page with forms
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="./includes/javascript/clueTip/jquery.cluetip.css">
<style>
	.echoButton {
		color:white;
		background : #008A4E;
		font-weight:bold;
		font-size:12px;
		margin:5px;
	}
</style>

<script src="./includes/javascript/jquery.js"></script>
<script src="./includes/javascript/clueTip/jquery.cluetip.js"></script>
<script src ="./includes/javascript/clueTip/themeswitchertool.js"></script>
<script src="./includes/javascript/clueTip/jquery.hoverIntent.js"></script>
<script src="./includes/javascript/clueTip/jquery.bgiframe.min.js"></script>
<script src="./includes/javascript/clueTip/demo.js"></script>


<script language="javascript">

<!--

var browser_family;
var up = 1;

if (document.all && !document.getElementById)
  browser_family = "dom2";
else if (document.layers)
  browser_family = "ns4";
else if (document.getElementById)
  browser_family = "dom2";
else
  browser_family = "other";

function display_ttc(action, prix, taxe, up){
  if(action == 'display'){
          if(up != 1)
          valeur = Math.round((prix + (taxe / 100) * prix) * 100) / 100;
  }else{
          if(action == 'keyup'){
                valeur = Math.round((parseFloat(prix) + (taxe / 100) * parseFloat(prix)) * 100) / 100;
        }else{
         valeur = '0';
        }
  }
  switch (browser_family){
    case 'dom2':
          document.getElementById('descDiv').innerHTML = '<?php echo TOTAL_COST; ?> : '+valeur;
      break;
    case 'ie4':
      document.all.descDiv.innerHTML = '<?php echo TOTAL_COST; ?> : '+valeur;
      break;
    case 'ns4':
      document.descDiv.document.descDiv_sub.document.write(valeur);
      document.descDiv.document.descDiv_sub.document.close();
      break;
    case 'other':
      break;
  }
}

-->

</script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
<td valign="top">

<table border="0" width="100%" cellspacing="0" cellpadding="2">
 <tr>
  <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
     <td class="pageHeading" colspan="3" valign="top"><?php echo HEADING_TITLE; ?></td>
     <td class="pageHeading" align="right"><?php
                                 if($current_category_id != 0){
                                        $image_query = tep_db_query("select c.categories_image from " . TABLE_CATEGORIES . " c where c.categories_id=" . $current_category_id);
                                        $image = tep_db_fetch_array($image_query);
                                        echo tep_image(DIR_WS_CATALOG . DIR_WS_IMAGES . $image['categories_image'], '', 40);
                                }else{
                                        if($manufacturer){
                                                $image_query = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id=" . $manufacturer);
                                                $image = tep_db_fetch_array($image_query);
                                                echo tep_image(DIR_WS_CATALOG . DIR_WS_IMAGES . $image['manufacturers_image'], '', 40);
                                        }
                                }
                        ?>
     </td>
    </tr>
   </table>
 </tr>
 <tr>
 <td align="center">
 
 <table width="100%" cellspacing="0" cellpadding="0" border="1" bgcolor="#F3F9FB" bordercolor="#D1E7EF" height="100">
  <tr align="left">
  <td valign="middle">
  
  <table width="100%" cellspacing="0" cellpadding="0" border="0">
   <tr>
    <td height="5"></td>
   </tr>
   <tr align="center">
    
	<td class="smalltext">
				&nbsp;
	</td>
    <td class="smallText" width="25%">
		<?php echo tep_draw_form('row_by_page', FILENAME_QUICK_UPDATES, tep_get_all_get_params(array('action')). 'action=update', 'get','', 'SSL'); 
			echo tep_draw_hidden_field( 'manufacturer', $manufacturer); 
			echo tep_draw_hidden_field( 'cPath', $current_category_id);?>
		<?php echo TEXT_MAXI_ROW_BY_PAGE . '&nbsp;&nbsp;<br>' . tep_draw_pull_down_menu('row_by_page', $row_bypage_array, $row_by_page, 'onChange="this.form.submit();"'); 
		echo tep_hide_session_id();
		?>
		 </form>	 
	 </td>
    
	 <td class="smallText" align="center" valign="top" width="25%">
		<?php echo tep_draw_form('frmSelOption', FILENAME_QUICK_UPDATES, tep_get_all_get_params(array('action')). 'action=update_option', 'get','', 'SSL'); echo tep_draw_hidden_field( 'row_by_page', $row_by_page); echo tep_draw_hidden_field( 'manufacturer', $manufacturer); ?>
		<?php echo DISPLAY_OPTIONS . '&nbsp;&nbsp;<br>'; 
		echo tep_hide_session_id();
		?>
		<select name="sel_option" onChange="this.form.submit();">
			<option value="1" <?php if($sel_option==1) echo "selected"; ?>>Products</option>
			<option value="2" <?php if($sel_option==2) echo "selected"; ?>>Products Options</option>
		</select>
		</form>
	</td>
	
    <td class="smallText" align="center" valign="top" width="25%">
		
		<?php if($sel_option!=2) { ?>
		
		<?php echo tep_draw_form('categories', FILENAME_QUICK_UPDATES, tep_get_all_get_params(array('action')). 'action=update', 'get','', 'SSL'); echo tep_draw_hidden_field( 'row_by_page', $row_by_page); echo tep_draw_hidden_field( 'manufacturer', $manufacturer); ?>
		<?php echo DISPLAY_CATEGORIES . '&nbsp;&nbsp;<br>' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"'); 
		echo tep_hide_session_id();
		?>
		</form>
		
		<?php } ?>
		&nbsp;
		
	</td>
    	
	<td class="smallText" align="center" valign="top" width="25%">
		
		<?php if($sel_option!=2) { ?>
		
		<?php echo tep_draw_form('manufacturers', FILENAME_QUICK_UPDATES, tep_get_all_get_params(array('action')). 'action=update', 'get','', 'SSL'); echo tep_draw_hidden_field( 'row_by_page', $row_by_page); echo tep_draw_hidden_field( 'cPath', $current_category_id);?>
		<?php echo DISPLAY_MANUFACTURERS . '&nbsp;&nbsp;<br>' . manufacturers_list(); 
		echo tep_hide_session_id();
		?>
		</form>
		
		<?php } ?>
		&nbsp;
	</td>
    
   </tr>
  </table>
  
  <?php if($sel_option<2) { ?>
  <!--
  <table width="100%" cellspacing="0" cellpadding="0" border="0">
     
	   <tr>
			<td height="5">&nbsp;</td>
	   </tr>
	   <tr align="center">
		<td align="center"><table border="0" cellspacing="0">
		  <form name="spec_price" <?php echo 'action="' . tep_href_link(FILENAME_QUICK_UPDATES, tep_get_all_get_params(array('action', 'info', 'pID')) . "action=calcul&page=$page&sort_by=$sort_by&cPath=$current_category_id&row_by_page=$row_by_page&manufacturer=$manufacturer" , 'NONSSL') . '"'; ?> method="post">
		   <tr>
			<td class="main"  align="center" valign="middle" nowrap><?php echo TEXT_INPUT_SPEC_PRICE; ?></td>
			<td align="center" valign="middle"><?php echo tep_draw_input_field('spec_price',0,'size="5"'); ?> </td>
			<td class="smalltext" align="center" valign="middle"><?php
																					 if ($preview_global_price != 'true') {
																									echo '&nbsp;&nbsp;' . tep_image_submit('button_preview.gif', IMAGE_PREVIEW, "page=$page&sort_by=$sort_by&cPath=$current_category_id&row_by_page=$row_by_page&manufacturer=$manufacturer");
																					 } else echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_QUICK_UPDATES, "page=$page&sort_by=$sort_by&cPath=$current_category_id&row_by_page=$row_by_page&manufacturer=$manufacturer") . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';?></td>
			<?php if(ACTIVATE_COMMERCIAL_MARGIN == 'true'){ echo '<td class="smalltext" align="center" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;' . tep_draw_checkbox_field('marge','yes','','no') . '&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', TEXT_MARGE_INFO) . '</td>';}?>
		   </tr>
		   <tr>
			<td class="smalltext" align="center" valign="middle" colspan="3" nowrap><?php if ($preview_global_price != 'true') {
																													 echo TEXT_SPEC_PRICE_INFO1 ;
																									  } else echo TEXT_SPEC_PRICE_INFO2;?>
			</td>
		   </tr>
		  </form>
		 </table></td>
	   </tr>
       <tr>
		<td height="5">&nbsp;</td>
	   </tr>
      </td>
     </tr>

   </table>
   -->
   <?php } ?>   
   
   <br>
   
   <?php
		if($sel_option<2) {
	?>	
		<form name="update" method="POST" action="<?php echo "$PHP_SELF?action=update&page=$page&sort_by=$sort_by&cPath=$current_category_id&row_by_page=$row_by_page&manufacturer=$manufacturer"; ?>">
    
	<?php } else { ?>
	
		<form name="update_option" method="POST" action="<?php echo "$PHP_SELF?action=update_option&sel_option=2&page=$page&sort_by=$sort_by&row_by_page=$row_by_page&manufacturer=$manufacturer"; ?>">
	
	<?php } ?>
	<?php echo tep_hide_session_id(); ?>
	
   <table width="100%" cellspacing="0" cellpadding="5" border="0">
    
	<tr align="center">
    
		<td class="smalltext" align="left" class="datatableRow" width="70%"><?php echo WARNING_MESSAGE; ?> </td>
		<td class="smalltext" align="left" class="datatableRow" width="15%"><?php echo '<script language="javascript"><!--
															switch (browser_family)
															{
															case "dom2":
															case "ie4":
															 document.write(\'<div id="descDiv">\');
															 break;
															default:
															 document.write(\'<ilayer id="descDiv"><layer id="descDiv_sub">\');
																  break;
															}
															-->
															</script>' . "\n";
													?>
		</td>											
		<td align="right" valign="middle" width='15%'><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE);?></td>
    </tr>
    
	
	<tr><td colspan="3">&nbsp;</td></tr>
   </table>
   </td>
   
   </tr>
   
   <tr>
   
   <td>
   
   <?php
		if($sel_option<2) {
	?>	
   
   <table border="4" width="100%" cellspacing="0" cellpadding="2">
    <tr>
    
    <td valign="top" >
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
     <tr class="dataTableHeadingRow">
      <td  align="left" valign="middle" ><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text">
         <td  align="left" valign="middle" class="dataTableHeadingContent"><?php if(DISPLAY_MODEL == 'true')echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_model ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_MODEL . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_model DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_MODEL . ' ' . TEXT_DESCENDINGLY)."</a>
                    "  .TABLE_HEADING_MODEL . "</td>" ; ?>
        </tr>
       </table>
       </td>
      <td  align="left" valign="middle"><table border="0" cellspacing="0" cellpadding="0">
        <tr >
         <td  align="left" valign="middle" class="dataTableHeadingContent"><?php echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=pd.products_name ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_PRODUCTS . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=pd.products_name DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_PRODUCTS . ' ' . TEXT_DESCENDINGLY)."</a>
                     "  . TABLE_HEADING_PRODUCTS . "</td>" ; ?> 
        </tr>
       </table></td>
      <td  align="center" valign="middle"><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text">
         <td  align="center" valign="middle" class="dataTableHeadingContent"><?php if(DISPLAY_STATUT == 'true')echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_status ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . 'OFF ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_status DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . 'ON ' . TEXT_ASCENDINGLY)."</a>
                     off / on</td>" ; ?>
        </tr>
       </table></td>
      <td  align="center" valign="middle"><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text">
         <td  align="center" valign="middle" class="dataTableHeadingContent"><?php if(DISPLAY_WEIGHT == 'true')echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_weight ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_WEIGHT . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_weight DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_WEIGHT . ' ' . TEXT_DESCENDINGLY)."</a>
                     " . TABLE_HEADING_WEIGHT . "</td>" ; ?>
        </tr>
       </table></td>
      <td  align="center" valign="middle"><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text">
         <td  align="center" valign="middle" class="dataTableHeadingContent"><?php if(DISPLAY_QUANTITY == 'true')echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_quantity ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_QUANTITY . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_quantity DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_QUANTITY . ' ' . TEXT_DESCENDINGLY)."</a>
                     " . TABLE_HEADING_QUANTITY . "</td>" ; ?>
        </tr>
       </table></td>
	   
	   <td  align="center" valign="middle"><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text">
         <td  align="center" valign="middle" class="dataTableHeadingContent"><?php if(DISPLAY_QUANTITY == 'true')echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.progressed_quantity ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_PROGRESSED_QUANTITY . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.progressed_quantity DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_PROGRESSED_QUANTITY . ' ' . TEXT_DESCENDINGLY)."</a>
                     " . TABLE_HEADING_PROGRESSED_QUANTITY . "</td>" ; ?>
        </tr>
       </table></td>
	   
	   <td  align="center" valign="middle"><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text">
         <td  align="center" valign="middle" class="dataTableHeadingContent"><?php if(DISPLAY_QUANTITY == 'true')echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.overall_quantity ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_OVERALL_QUANTITY . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.overall_quantity DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_OVERALL_QUANTITY . ' ' . TEXT_DESCENDINGLY)."</a>
                     " . TABLE_HEADING_OVERALL_QUANTITY . "</td>" ; ?>
        </tr>
       </table></td>
	   
      <td  align="left" valign="middle"><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text" >
         <td  align="left" valign="middle" class="dataTableHeadingContent"><?php if(DISPLAY_IMAGE == 'true')echo "&nbsp; <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_image ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_IMAGE . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_image DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_IMAGE . ' ' . TEXT_DESCENDINGLY)."</a>
                    &nbsp; " . TABLE_HEADING_IMAGE . "</td>" ; ?>
        </tr>
       </table></td>
      <td  align="left" valign="middle"><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text">
         <td  align="left" valign="middle" class="dataTableHeadingContent"><?php if(DISPLAY_MANUFACTURER == 'true')echo "&nbsp;&nbsp; <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=manufacturers_id ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_MANUFACTURERS . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=manufacturers_id DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_MANUFACTURERS . ' ' . TEXT_DESCENDINGLY)."</a>
                     &nbsp;&nbsp; " . TABLE_HEADING_MANUFACTURERS . "</td>" ; ?>
        </tr>
       </table></td>
      <td  align="left" valign="middle"><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text">
         <td  align="left" valign="middle" class="dataTableHeadingContent"><?php echo "&nbsp;&nbsp;&nbsp; <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_price ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer) ."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_PRICE . ' ' . TEXT_ASCENDINGLY)."</a>
                    <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_price DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer) ."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_PRICE . ' ' . TEXT_DESCENDINGLY)."</a>
                      " . TABLE_HEADING_PRICE . "</td>";?> 
        </tr>
       </table></td>
      <td  align="left" valign="middle" class="text" class="dataTableHeadingContent"><?php if(DISPLAY_TAX == 'true')echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_tax_class_id ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES  . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_TAX . ' ' . TEXT_ASCENDINGLY)."</a>
                    <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'cPath='. $current_category_id .'&sort_by=p.products_tax_class_id DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES  . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_TAX . ' ' . TEXT_DESCENDINGLY)."</a>
                    " . TABLE_HEADING_TAX . " </td> " ; ?>
      <td  align="center" valign="middle" class="dataTableHeadingContent"></td>
      <td  align="center" valign="middle" class="dataTableHeadingContent"></td>
     </tr>
     <tr class="datatableRow">
      <?php
//// control string sort page
     if ($sort_by && !preg_match('/^[a-z][ad]$/',$sort_by)) $sort_by = 'order by '.$sort_by ;
//// define the string parameters for good back preview product
     $origin = FILENAME_QUICK_UPDATES."?info_back=$sort_by-$page-$current_category_id-$row_by_page-$manufacturer";
//// controle lenght (lines per page)
     $split_page = $page;
     if ($split_page > 1) $rows = $split_page * MAX_DISPLAY_ROW_BY_PAGE - MAX_DISPLAY_ROW_BY_PAGE;

////  select categories
  if ($current_category_id == 0){
          if($manufacturer){
            $products_query_raw = "select p.products_id, p.products_image, p.products_model, pd.products_name, p.products_status, p.products_weight, p.products_quantity, p.manufacturers_id,  p.products_price, p.products_tax_class_id, p.overall_quantity, p.progressed_quantity from  " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION .  " pd where p.products_id = pd.products_id AND ((p.products_parent_id =0 OR p.products_parent_id IS NULL) AND (p.default_product_id = 0 OR p.default_product_id IS NULL)) and pd.language_id = '$languages_id' and p.manufacturers_id = " . $manufacturer . " $sort_by ";
          } else {
                $products_query_raw = "select p.products_id, p.products_image, p.products_model, pd.products_name, p.products_status, p.products_weight, p.products_quantity, p.manufacturers_id, p.products_price,  p.products_tax_class_id, p.overall_quantity, p.progressed_quantity from  " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION .  " pd where p.products_id = pd.products_id AND ((p.products_parent_id =0 OR p.products_parent_id IS NULL) AND (p.default_product_id = 0 OR p.default_product_id IS NULL)) and pd.language_id = '$languages_id'  $sort_by ";
				
				//echo $products_query_raw;
        }
		
		
		
  } else {
         if($manufacturer){
                 $products_query_raw = "select p.products_id, p.products_image, p.products_model, pd.products_name, p.products_status, p.products_weight, p.products_quantity, p.manufacturers_id,  p.products_price, p.products_tax_class_id, p.overall_quantity, p.progressed_quantity from  " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION .  " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " pc where p.products_id = pd.products_id and pd.language_id = '$languages_id' and p.products_id = pc.products_id AND ((p.products_parent_id =0 OR p.products_parent_id IS NULL) AND (p.default_product_id = 0 OR p.default_product_id IS NULL)) and pc.categories_id = '" . $current_category_id . "' and p.manufacturers_id = " . $manufacturer . " $sort_by ";
          } else {
          
			 if(tep_has_category_subcategories($current_category_id)){//if - check subcategory existence
					$products_query_raw = "select p.products_id, p.products_image, p.products_model, pd.products_name, p.products_status, p.products_weight, p.products_quantity, p.manufacturers_id, p.products_price,  p.products_tax_class_id, p.overall_quantity, p.progressed_quantity from  " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION .  " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " pc where p.products_id = pd.products_id and pd.language_id = '$languages_id' and p.products_id = pc.products_id AND ((p.products_parent_id =0 OR p.products_parent_id IS NULL) AND (p.default_product_id = 0 OR p.default_product_id IS NULL)) and pc.categories_id IN (select categories_id from categories where parent_id = '" . $current_category_id . "') $sort_by ";
			} else{
			$products_query_raw = "select p.products_id, p.products_image, p.products_model, pd.products_name, p.products_status, p.products_weight, p.products_quantity, p.manufacturers_id, p.products_price,  p.products_tax_class_id, p.overall_quantity, p.progressed_quantity from  " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION .  " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " pc where p.products_id = pd.products_id and pd.language_id = '$languages_id' and p.products_id = pc.products_id AND ((p.products_parent_id =0 OR p.products_parent_id IS NULL) AND (p.default_product_id = 0 OR p.default_product_id IS NULL)) and pc.categories_id = '" . $current_category_id . "'  $sort_by ";
			}//if - check subcategory existence
        }
  }

//// page splitter and display each products info
  $products_split = new splitPageResults($split_page, MAX_DISPLAY_ROW_BY_PAGE, $products_query_raw, $products_query_numrows);
  $products_query = tep_db_query($products_query_raw);
  while ($products = tep_db_fetch_array($products_query)) {
    $rows++;
    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
//// check for global add value or rates, calcul and round values rates
    if ($_POST['spec_price']){
      $flag_spec = 'true' ;
//page select fix v2.8.3 August 11, 2009
	$spec_price = $_POST['spec_price'];
      if (substr($_POST['spec_price'],-1) == '%') {
                  if($_POST['marge'] && substr($_POST['spec_price'],0,1) != '-'){
                        $valeur = (1 - (ereg_replace("%", "", $_POST['spec_price']) / 100));
                        $price = sprintf("%01.2f", round($products['products_price'] / $valeur,2));
                }else{
                $price = sprintf("%01.2f", round($products['products_price'] + (($spec_price / 100) * $products['products_price']),2));
              }
          } else $price = sprintf("%01.2f", round($products['products_price'] + $spec_price,2));
//page select fix v2.8.3 August 11, 2009
    } else $price = sprintf("%01.2f", round($products['products_price'], 2));

//// Check Tax_rate for displaying TTC
        $tax_query = tep_db_query("select r.tax_rate, c.tax_class_title from " . TABLE_TAX_RATES . " r, " . TABLE_TAX_CLASS . " c where r.tax_class_id=" . $products['products_tax_class_id'] . " and c.tax_class_id=" . $products['products_tax_class_id']);
        $tax_rate = tep_db_fetch_array($tax_query);
        if($tax_rate['tax_rate'] == '')$tax_rate['tax_rate'] = 0;

        if(MODIFY_MANUFACTURER == 'false'){
                
				if(isset($products['manufacturers_id'])) {
					$manufacturer_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id=" . $products['manufacturers_id']);
					$manufacturer = tep_db_fetch_array($manufacturer_query);
				}
        }
		
//// display infos per row
			
			if($products['products_quantity'] <= STOCK_REORDER_LEVEL) {
                $highlight = ' style="background-color:#FF0000;" ';
			} else {
					$highlight = "";
			}
							
			
                if($flag_spec){echo '<tr class="dataTableRow" '.$highlight.' onmouseover="'; if(DISPLAY_TVA_OVER == 'true'){echo 'display_ttc(\'display\', ' . $price . ', ' . $tax_rate['tax_rate'] . ');';} echo 'this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="'; if(DISPLAY_TVA_OVER == 'true'){echo 'display_ttc(\'delete\');';} echo 'this.className=\'dataTableRow\'">'; }else{ echo '<tr '.$highlight.' class="dataTableRow" onmouseover="'; if(DISPLAY_TVA_OVER == 'true'){echo 'display_ttc(\'display\', ' . $products['products_price'] . ', ' . $tax_rate['tax_rate'] . ');';} echo 'this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="'; if(DISPLAY_TVA_OVER == 'true'){echo 'display_ttc(\'delete\', \'\', \'\', 0);';} echo 'this.className=\'dataTableRow\'">';}
                if(DISPLAY_MODEL == 'true'){if(MODIFY_MODEL == 'true')echo "<td class=\"smallText\" align=\"center\"><input type=\"text\" size=\"6\" name=\"product_new_model[".$products['products_id']."]\" value=\"".$products['products_model']."\"></td>\n";else echo "<td class=\"smallText\" align=\"left\">" . $products['products_model'] . "</td>\n";}else{ echo "<td class=\"smallText\" align=\"left\">";}
        if(MODIFY_NAME == 'true')echo "<td class=\"smallText\" align=\"center\"><input type=\"text\" size=\"30\" name=\"product_new_name[".$products['products_id']."]\" value=\"".str_replace("\"","&quot;",$products['products_name'])."\"></td>\n";else echo "<td class=\"smallText\" align=\"left\">".$products['products_name']."</td>\n";
//// Product status radio button
                if(DISPLAY_STATUT == 'true'){
                        if ($products['products_status'] == '1') {
                         echo "<td class=\"smallText\" align=\"center\"><input  type=\"radio\" name=\"product_new_status[".$products['products_id']."]\" value=\"0\" ><input type=\"radio\" name=\"product_new_status[".$products['products_id']."]\" value=\"1\" checked ></td>\n";
                        } else {
                         echo "<td class=\"smallText\" align=\"center\"><input type=\"radio\" style=\"background-color: #EEEEEE\" name=\"product_new_status[".$products['products_id']."]\" value=\"0\" checked ><input type=\"radio\" style=\"background-color: #EEEEEE\" name=\"product_new_status[".$products['products_id']."]\" value=\"1\"></td>\n";
                        }
                }else{
                        echo "<td class=\"smallText\" align=\"center\"></td>";
                }
        if(DISPLAY_WEIGHT == 'true')echo "<td class=\"smallText\" align=\"center\"><input type=\"text\" size=\"8\" name=\"product_new_weight[".$products['products_id']."]\" value=\"".$products['products_weight']."\"></td>\n";else echo "<td class=\"smallText\" align=\"center\"></td>";
        
		if(DISPLAY_QUANTITY == 'true')echo "<td class=\"smallText\" align=\"center\"><input type=\"text\" size=\"8\" name=\"product_new_quantity[".$products['products_id']."]\" value=\"".$products['products_quantity']."\"></td>\n";else echo "<td class=\"smallText\" align=\"center\"></td>";
		
		//July 18 2012
		if(DISPLAY_QUANTITY == 'true')echo "<td class=\"smallText\" align=\"center\"><input type=\"text\" size=\"8\" name=\"product_progressed_quantity[".$products['products_id']."]\" value=\"".$products['progressed_quantity']."\"></td>\n";else echo "<td class=\"smallText\" align=\"center\"></td>";
		if(DISPLAY_QUANTITY == 'true')echo "<td class=\"smallText\" align=\"center\"><input readonly type=\"text\" size=\"8\" name=\"product_overall_quantity[".$products['products_id']."]\" value=\"".$products['overall_quantity']."\"></td>\n";else echo "<td class=\"smallText\" align=\"center\"></td>";
		
                if(DISPLAY_IMAGE == 'true')echo "<td class=\"smallText\" align=\"center\"><input type=\"text\" size=\"8\" name=\"product_new_image[".$products['products_id']."]\" value=\"".$products['products_image']."\"></td>\n";else echo "<td class=\"smallText\" align=\"center\"></td>";
                if(DISPLAY_MANUFACTURER == 'true'){if(MODIFY_MANUFACTURER == 'true')echo "<td class=\"smallText\" align=\"center\">".tep_draw_pull_down_menu("product_new_manufacturer[".$products['products_id']."]\"", $manufacturers_array, $products['manufacturers_id'])."</td>\n";else echo "<td class=\"smallText\" align=\"center\">" . $manufacturer['manufacturers_name'] . "</td>";}else{ echo "<td class=\"smallText\" align=\"center\"></td>";}
               
//// get the specials products list
     $specials_array = array();
     $specials_query = tep_db_query("select p.products_id, s.products_id, s.specials_id from " . TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s where s.products_id = p.products_id");
     while ($specials = tep_db_fetch_array($specials_query)) {
       $specials_array[] = $specials['products_id'];
     }
//// check specials
        if ( in_array($products['products_id'],$specials_array)) {
             $spec_query = tep_db_query("select s.products_id, s.specials_id from " . TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s where s.products_id = " . (int)$products['products_id'] . "");
             $spec = tep_db_fetch_array($spec_query);

            echo "<td class=\"smallText\" align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" size=\"8\" name=\"product_new_price[".$products['products_id']."]\" value=\"".$products['products_price']."\" disabled >&nbsp;<a target=blank href=\"".tep_href_link (FILENAME_SPECIALS, 'sID='.$spec['specials_id']).'&action=edit'."\">". tep_image(DIR_WS_IMAGES . 'icon_info.gif', TEXT_SPECIALS_PRODUCTS) ."</a></td>\n";
        } else {
            if ($flag_spec == 'true') {
                   echo "<td class=\"smallText\" align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" size=\"8\" name=\"product_new_price[".$products['products_id']."]\" "; if(DISPLAY_TVA_UP == 'true'){ echo "onKeyUp=\"display_ttc('keyup', this.value" . ", " . $tax_rate['tax_rate'] . ", 1);\"";} echo " value=\"".$price ."\">".tep_draw_checkbox_field('update_price['.$products['products_id'].']','yes','checked','no')."</td>\n";
            } else { echo "<td class=\"smallText\" align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" size=\"8\" name=\"product_new_price[".$products['products_id']."]\" "; if(DISPLAY_TVA_UP == 'true'){ echo "onKeyUp=\"display_ttc('keyup', this.value" . ", " . $tax_rate['tax_rate'] . ", 1);\"";} echo " value=\"".$price ."\">".tep_draw_hidden_field('update_price['.$products['products_id'].']','yes'). "</td>\n";}
        }
        if(DISPLAY_TAX == 'true'){if(MODIFY_TAX == 'true')echo "<td class=\"smallText\" align=\"left\">".tep_draw_pull_down_menu("product_new_tax[".$products['products_id']."]\"", $tax_class_array, $products['products_tax_class_id'])."</td>\n";else echo "<td class=\"smallText\" align=\"left\">" . $tax_rate['tax_class_title'] . "</td>";}else{ echo "<td class=\"smallText\" align=\"center\"></td>";}

//// links to preview or full edit
        
		//if(DISPLAY_PREVIEW == 'true')echo "<td class=\"smallText\" align=\"left\"><a href=\"".tep_href_link (FILENAME_CATEGORIES, 'pID='.$products['products_id'].'&action=new_product_preview&read=only&sort_by='.$sort_by.'&page='.$split_page.'&origin='.$origin)."\">". tep_image(DIR_WS_IMAGES . 'icon_info.gif', TEXT_IMAGE_PREVIEW) ."</a></td>\n";
		
		echo "<td class=\"smallText\" align=\"left\"><a class='jt' title='Inventory History' rel='inventory_history.php?type=1&pID=".$products['products_id']."' href=''>". tep_image(DIR_WS_IMAGES . 'icon_info.gif', TEXT_IMAGE_PREVIEW) ."</a></td>\n";
        
		if(DISPLAY_EDIT == 'true')echo "<td class=\"smallText\" align=\"left\"><a href=\"".tep_href_link (FILENAME_CATEGORIES, 'pID='.$products['products_id'].'&cPath='.$categories_products[0].'&action=new_product')."\">". tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', TEXT_IMAGE_SWITCH_EDIT) ."</a></td>\n";

//// Hidden parameters for cache old values
                if(MODIFY_NAME == 'true') echo tep_draw_hidden_field('product_old_name['.$products['products_id'].'] ',$products['products_name']);
        if(MODIFY_MODEL == 'true') echo tep_draw_hidden_field('product_old_model['.$products['products_id'].'] ',$products['products_model']);
                echo tep_draw_hidden_field('product_old_status['.$products['products_id'].']',$products['products_status']);
        echo tep_draw_hidden_field('product_old_quantity['.$products['products_id'].']',$products['products_quantity']);
		
		//July 18 2012
		echo tep_draw_hidden_field('product_old_progressed_quantity['.$products['products_id'].']',$products['progressed_quantity']);
		echo tep_draw_hidden_field('product_old_overall_quantity['.$products['products_id'].']',$products['overall_quantity']);
		
                echo tep_draw_hidden_field('product_old_image['.$products['products_id'].']',$products['products_image']);
        if(MODIFY_MANUFACTURER == 'true')echo tep_draw_hidden_field('product_old_manufacturer['.$products['products_id'].']',$products['manufacturers_id']);
                echo tep_draw_hidden_field('product_old_weight['.$products['products_id'].']',$products['products_weight']);
        echo tep_draw_hidden_field('product_old_price['.$products['products_id'].']',$products['products_price']);
        
        if(MODIFY_TAX == 'true')echo tep_draw_hidden_field('product_old_tax['.$products['products_id'].']',$products['products_tax_class_id']);
		
		
     }
    echo "</table>\n";

?>
     </td>
     
     </tr>
     
    </table>
	
	<?php 
	
	} else {
		
		include("quick_updates_options.php");	
	
	}
	?>
	
    </td>
    
    </tr>
    
    <tr>
     <td align="right"><?php
                 //// display bottom page buttons
                echo '<a href="javascript:window.print()">' . PRINT_TEXT . '</a>&nbsp;&nbsp;';
              echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
              echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_QUICK_UPDATES,"row_by_page=$row_by_page") . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
              $manufacturer = tep_db_prepare_input($_GET['manufacturer']);
?></td>
    </tr>
		<?php
			//// hidden display parameters
			echo tep_draw_hidden_field( 'row_by_page', $row_by_page);
			echo tep_draw_hidden_field( 'sort_by', $sort_by);
			echo tep_draw_hidden_field( 'page', $split_page);
		?>
	
    </form>
    
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
       <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_ROW_BY_PAGE, $split_page, TEXT_DISPLAY_NUMBER_OF_PRODUCTS);  ?></td>
        <td class="smallText" align="right">
			
			<?php 
			
			if($sel_option!=2) {
				echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_ROW_BY_PAGE, MAX_DISPLAY_PAGE_LINKS, $split_page, '&cPath='. $current_category_id .'&sort_by='.$sort_by . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer); 
			} else {
				echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_ROW_BY_PAGE, MAX_DISPLAY_PAGE_LINKS, $split_page, '&sel_option='. $sel_option .'&sort_by='.$sort_by . '&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer); 
			}			
			?> 
		 
		 </td>
      </table></td>
    </tr>
   </table>
   </td>
   
   </tr>
   
  </table>
  </td>
  
  <!-- body_text_eof //-->
  </tr>
  
 </table>
 <!-- body_eof //-->
 </tr>
 
</table>
<?php   //require(DIR_WS_INCLUDES . 'template_bottom.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
