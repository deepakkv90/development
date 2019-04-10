

<table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
    
    <td valign="top" colspan="5">
    	  
	  <table border="0" cellspacing="0" cellpadding="0" width="100%">
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
	  	 
	 </tr>
	 
	
	 
      <?php
//// control string sort page
     if ($sort_by && !preg_match('/^[a-z][ad]$/',$sort_by)) $sort_by = 'order by '.$sort_by ;
//// define the string parameters for good back preview product
     $origin = FILENAME_QUICK_UPDATES."?info_back=$sort_by-$page-$sel_option-$row_by_page-$manufacturer";
//// controle lenght (lines per page)
     $split_page = $page;
     if ($split_page > 1) $rows = $split_page * MAX_DISPLAY_ROW_BY_PAGE - MAX_DISPLAY_ROW_BY_PAGE;

	////  select categories
  
	$products_query_raw = "select products_options_values_id, products_options_values_name, options_values_price, picture, quantity, overall_quantity, progressed_quantity from  " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '$languages_id'  $sort_by ";
	
	//echo $products_query_raw;
       

	//// page splitter and display each products info
    $products_split = new splitPageResults($split_page, MAX_DISPLAY_ROW_BY_PAGE, $products_query_raw, $products_query_numrows);
    $products_query = tep_db_query($products_query_raw);
    while ($products = tep_db_fetch_array($products_query)) {
		$rows++;
		if (strlen($rows) < 2) {
		  $rows = '0' . $rows;
		}
		
				if($products['quantity'] <= STOCK_REORDER_LEVEL) {
					echo '<tr  class="dataTableRow" style="background-color:#FF0000;">';
				} else {
					echo "<tr class='dataTableRow'>";
				}
											
				echo "<td class=\"smallText\" align=\"center\">".$products['products_options_values_name'];
				
				if(file_exists("../images/product_attributes/".$products['picture']) && !empty($products['picture'])) {
					echo "<br><img src='../images/product_attributes/".$products['picture']."' width='100px'/>";
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
                        
				echo "<td class=\"smallText\" align=\"center\">".$products['quantity']."</td>";
						
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
	 