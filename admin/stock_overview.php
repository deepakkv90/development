<?php
/*
  $Id: stock_overview.php,v 2.3 2003/06/04 11:44:34 HRB Exp $

  
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
<style>
	.echoButton {
		color:white;
		background : #008A4E;
		font-weight:bold;
		font-size:12px;
		margin:5px;
	}
</style>

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
		   
		   <tr><td colspan="4" height="5">&nbsp;</td></tr>
		   
		   <tr align="center">
			
			
			<td class="smallText" align="center" valign="top" width="25%">
				<?php echo tep_draw_form('row_by_page', FILENAME_STOCK_OVERVIEW, tep_get_all_get_params(array('action')). 'action=update', 'get','', 'SSL'); 
					echo tep_draw_hidden_field( 'manufacturer', $manufacturer); 
					echo tep_draw_hidden_field( 'cPath', $current_category_id);?>
				<?php echo TEXT_MAXI_ROW_BY_PAGE . '<br>' . tep_draw_pull_down_menu('row_by_page', $row_bypage_array, $row_by_page, 'onChange="this.form.submit();"'); 
				echo tep_hide_session_id();
				?>
				 </form>	 
			 </td>
			 
			 <td class="smallText" align="center" valign="top" width="25%">
				<?php echo tep_draw_form('frmSelOption', FILENAME_STOCK_OVERVIEW, tep_get_all_get_params(array('action')). 'action=update_option', 'get','', 'SSL'); echo tep_draw_hidden_field( 'row_by_page', $row_by_page); echo tep_draw_hidden_field( 'manufacturer', $manufacturer); ?>
				<?php echo DISPLAY_OPTIONS . '<br>'; 
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
				
					<?php echo tep_draw_form('categories', FILENAME_STOCK_OVERVIEW, tep_get_all_get_params(array('action')). 'action=update', 'get','', 'SSL'); echo tep_draw_hidden_field( 'row_by_page', $row_by_page); echo tep_draw_hidden_field( 'manufacturer', $manufacturer); ?>
					<?php echo DISPLAY_CATEGORIES . '<br>' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"'); 
					echo tep_hide_session_id();
					?>
					</form>
				
				<?php } ?>
				
			</td>
				
			<td class="smallText" align="center" valign="top" width="25%">
				
				<?php if($sel_option!=2) { ?>
				
					<?php echo tep_draw_form('manufacturers', FILENAME_STOCK_OVERVIEW, tep_get_all_get_params(array('action')). 'action=update', 'get','', 'SSL'); echo tep_draw_hidden_field( 'row_by_page', $row_by_page); echo tep_draw_hidden_field( 'cPath', $current_category_id);?>
					<?php echo DISPLAY_MANUFACTURERS . '<br>' . manufacturers_list(); 
					echo tep_hide_session_id();
					?>
					</form>
				
				<?php } ?>
				
			</td>
			
		   </tr>
		  </table>

   
   <br>
   
     <?php
		if($sel_option<2) {
	?>	
		<form name="update" method="POST" action="<?php echo "$PHP_SELF?action=update&page=$page&sort_by=$sort_by&cPath=$current_category_id&row_by_page=$row_by_page&manufacturer=$manufacturer"; ?>">
    
	<?php } else { ?>
	
		<form name="update_option" method="POST" action="<?php echo "$PHP_SELF?action=update_option&sel_option=2&page=$page&sort_by=$sort_by&row_by_page=$row_by_page&manufacturer=$manufacturer"; ?>">
	
	<?php } ?>
	<?php echo tep_hide_session_id(); ?>
	
 
   </td>
   
   </tr>
   
   <tr>
   
   <td>
   
   <?php
		if($sel_option<2) {
	?>	
	
	
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
    
    <td valign="top" >
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
     <tr class="dataTableHeadingRow">
      <td  align="center" valign="middle"><table border="0" cellspacing="0" cellpadding="0">
        <tr >
         <td  align="center" valign="middle" class="dataTableHeadingContent"><?php echo " <a href=\"" . tep_href_link( FILENAME_STOCK_OVERVIEW, 'cPath='. $current_category_id .'&sort_by=pd.products_name ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_PRODUCTS . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_STOCK_OVERVIEW, 'cPath='. $current_category_id .'&sort_by=pd.products_name DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_PRODUCTS . ' ' . TEXT_DESCENDINGLY)."</a>
                     "  . TABLE_HEADING_PRODUCTS . "</td>" ; ?> 
        </tr>
       </table></td>
      <td  align="center" valign="middle"><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text">
         <td  align="center" valign="middle" class="dataTableHeadingContent"><?php if(DISPLAY_STATUT == 'true')echo " <a href=\"" . tep_href_link( FILENAME_STOCK_OVERVIEW, 'cPath='. $current_category_id .'&sort_by=p.products_status ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . 'OFF ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_STOCK_OVERVIEW, 'cPath='. $current_category_id .'&sort_by=p.products_status DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . 'ON ' . TEXT_ASCENDINGLY)."</a>
                     off / on</td>" ; ?>
        </tr>
       </table></td>
     
       <td  align="center" valign="middle"><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text">
         <td  align="center" valign="middle" class="dataTableHeadingContent"><?php if(DISPLAY_QUANTITY == 'true')echo " <a href=\"" . tep_href_link( FILENAME_STOCK_OVERVIEW, 'cPath='. $current_category_id .'&sort_by=p.products_quantity ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_QUANTITY . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_STOCK_OVERVIEW, 'cPath='. $current_category_id .'&sort_by=p.products_quantity DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_QUANTITY . ' ' . TEXT_DESCENDINGLY)."</a>
                     " . TABLE_HEADING_QUANTITY . "</td>" ; ?>
        </tr>
       </table></td>
	   
	   <td  align="center" valign="middle"><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text">
         <td  align="center" valign="middle" class="dataTableHeadingContent"><?php if(DISPLAY_QUANTITY == 'true')echo " <a href=\"" . tep_href_link( FILENAME_STOCK_OVERVIEW, 'cPath='. $current_category_id .'&sort_by=p.progressed_quantity ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_PROGRESSED_QUANTITY . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_STOCK_OVERVIEW, 'cPath='. $current_category_id .'&sort_by=p.progressed_quantity DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_PROGRESSED_QUANTITY . ' ' . TEXT_DESCENDINGLY)."</a>
                     " . TABLE_HEADING_PROGRESSED_QUANTITY . "</td>" ; ?>
        </tr>
       </table></td>
	   
	   <td  align="center" valign="middle"><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text">
         <td  align="center" valign="middle" class="dataTableHeadingContent"><?php if(DISPLAY_QUANTITY == 'true')echo " <a href=\"" . tep_href_link( FILENAME_STOCK_OVERVIEW, 'cPath='. $current_category_id .'&sort_by=p.overall_quantity ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_OVERALL_QUANTITY . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_STOCK_OVERVIEW, 'cPath='. $current_category_id .'&sort_by=p.overall_quantity DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_OVERALL_QUANTITY . ' ' . TEXT_DESCENDINGLY)."</a>
                     " . TABLE_HEADING_OVERALL_QUANTITY . "</td>" ; ?>
        </tr>
       </table></td>
	   
      <td  align="left" valign="middle"><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text" >
         <td  align="left" valign="middle" class="dataTableHeadingContent"><?php if(DISPLAY_IMAGE == 'true')echo "&nbsp; <a href=\"" . tep_href_link( FILENAME_STOCK_OVERVIEW, 'cPath='. $current_category_id .'&sort_by=p.products_image ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_IMAGE . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_STOCK_OVERVIEW, 'cPath='. $current_category_id .'&sort_by=p.products_image DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_IMAGE . ' ' . TEXT_DESCENDINGLY)."</a>
                    &nbsp; " . TABLE_HEADING_IMAGE . "</td>" ; ?>
        </tr>
       </table></td>
            
     </tr>
     <tr class="datatableRow">
      <?php
//// control string sort page
     if ($sort_by && !preg_match('/^[a-z][ad]$/',$sort_by)) $sort_by = 'order by '.$sort_by ;
//// define the string parameters for good back preview product
     $origin = FILENAME_STOCK_OVERVIEW."?info_back=$sort_by-$page-$current_category_id-$row_by_page-$manufacturer";
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

				if($products['products_quantity'] <= STOCK_REORDER_LEVEL) {
					echo '<tr  class="dataTableRow" style="background-color:#FF0000;">';
				} else {
					echo "<tr class='dataTableRow'>";
				}
											
				echo "<td class=\"smallText\" align=\"center\">".$products['products_name'];
				
				if(file_exists("../images/".$products['products_image']) && !empty($products['products_image'])) {
					echo "<br><img src='../images/".$products['products_image']."' width='100px'/>";
				} else {					
					echo "<br><img src='./images/image_not_avail.jpg'/>";
				}
				
				echo "</td>\n";
				
				//// Product status radio button                
				if ($products['products_status'] == '1') {
				 echo "<td class=\"smallText\" align=\"center\">On</td>\n";
				} else {
				 echo "<td class=\"smallText\" align=\"center\">Off</td>\n";
				}
                        
				echo "<td class=\"smallText\" align=\"center\">".$products['products_quantity']."</td>";
						
				echo "<td class=\"smallText\" align=\"center\">".$products['progressed_quantity']."</td>";
				
				echo "<td class=\"smallText\" align=\"center\">".$products['overall_quantity']."</td>";
		
               // echo "<td class=\"smallText\" align=\"center\"></td>";
			   
			   echo "</tr>";
				
     }
    

?>
		
		</table>
		
		
     </td>
     
     </tr>
     
    </table>
	
	
	<?php } else {
	
		include("stock_overview_options.php");	
		
	}
	
	?>
	
	
    </td>
    
    </tr>
    
    <tr>
     <td align="right"><?php
                 //// display bottom page buttons
              //echo '<a href="javascript:window.print()">' . PRINT_TEXT . '</a>&nbsp;&nbsp;';
              //echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
              
			  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_STOCK_OVERVIEW,"row_by_page=$row_by_page") . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
              $manufacturer = tep_db_prepare_input($_GET['manufacturer']);
?></td>
    </tr>
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
