

<table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
    
    <td valign="top" colspan="5">
    	  
	  <table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr class="dataTableHeadingRow">
         <td  align="left" valign="middle" width="20%"><table border="0" cellspacing="0" cellpadding="0">
			<tr >
			 <td  align="left" valign="middle" class="dataTableHeadingContent"><?php echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'sel_option='. $sel_option .'&sort_by=products_options_values_name ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_PRODUCTS . TEXT_ASCENDINGLY)."</a>
						 <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'sel_option='. $sel_option .'&sort_by=products_options_values_name DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_PRODUCTS . ' ' . TEXT_DESCENDINGLY)."</a>
						 "  . TABLE_HEADING_PRODUCTS . "</td>" ; ?> 
			</tr>
		   </table>
		</td>
	   
      <td  align="left" valign="middle" width="20%"><table border="0" cellspacing="0" cellpadding="0">
        <tr >
         <td  align="left" valign="middle" class="dataTableHeadingContent"><?php echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'sel_option='. $sel_option .'&sort_by=options_values_price ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_PRICE . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'sel_option='. $sel_option .'&sort_by=options_values_price DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_PRICE . ' ' . TEXT_DESCENDINGLY)."</a>
                     "  . TABLE_HEADING_PRICE . "</td>" ; ?> 
        </tr>
       </table></td>
      
      <td  align="left" valign="middle" width="20%"><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text">
         <td  align="left" valign="middle" class="dataTableHeadingContent"><?php echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'sel_option='. $sel_option .'&sort_by=quantity ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_QUANTITY . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'sel_option='. $sel_option .'&sort_by=products_quantity DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_QUANTITY . ' ' . TEXT_DESCENDINGLY)."</a>
                     " . TABLE_HEADING_QUANTITY . "</td>" ; ?>
        </tr>
       </table></td>
	   
	   <td  align="left" valign="middle" width="20%"><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text">
         <td  align="left" valign="middle" class="dataTableHeadingContent"><?php echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'sel_option='. $sel_option .'&sort_by=progressed_quantity ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_PROGRESSED_QUANTITY . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'sel_option='. $sel_option .'&sort_by=progressed_quantity DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_PROGRESSED_QUANTITY . ' ' . TEXT_DESCENDINGLY)."</a>
                     " . TABLE_HEADING_PROGRESSED_QUANTITY . "</td>" ; ?>
        </tr>
       </table></td>
	   
	   <td  align="left" valign="middle" width="20%"><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text">
         <td  align="left" valign="middle" class="dataTableHeadingContent"><?php echo " <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'sel_option='. $sel_option .'&sort_by=overall_quantity ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_OVERALL_QUANTITY . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'sel_option='. $sel_option .'&sort_by=overall_quantity DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_OVERALL_QUANTITY . ' ' . TEXT_DESCENDINGLY)."</a>
                     " . TABLE_HEADING_OVERALL_QUANTITY . "</td>" ; ?>
        </tr>
       </table></td>
	   <!--
      <td  align="left" valign="middle" width="20%"><table border="0" cellspacing="0" cellpadding="0">
        <tr class="text" >
         <td  align="left" valign="middle" class="dataTableHeadingContent"><?php echo "&nbsp; <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'sel_option='. $sel_option .'&sort_by=picture ASC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_up.gif', TEXT_SORT_ALL . TABLE_HEADING_IMAGE . ' ' . TEXT_ASCENDINGLY)."</a>
                     <a href=\"" . tep_href_link( FILENAME_QUICK_UPDATES, 'sel_option='. $sel_option .'&sort_by=picture DESC&page=' . $page.'&row_by_page=' . $row_by_page . '&manufacturer=' . $manufacturer)."\" >".tep_image(DIR_WS_IMAGES . 'icon_down.gif', TEXT_SORT_ALL . TABLE_HEADING_IMAGE . ' ' . TEXT_DESCENDINGLY)."</a>
                    &nbsp; " . TABLE_HEADING_IMAGE . "</td>" ; ?>
        </tr>
       </table>
     
	 </td>
	 -->
	   <td  align="left" valign="middle" width="3%">&nbsp;</td>
	   
	 
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
		
        echo "<td class=\"smallText\" align=\"left\"><input type=\"text\" size=\"25\" name=\"product_options_values_new_name[".$products['products_options_values_id']."]\" value=\"".$products['products_options_values_name']."\"></td>\n";
		
		echo "<td class=\"smallText\" align=\"left\"><input type=\"text\" size=\"8\" name=\"product_options_values_new_price[".$products['products_options_values_id']."]\" value=\"".$products['options_values_price']."\"></td>\n";
        
		echo "<td class=\"smallText\" align=\"left\"><input type=\"text\" size=\"8\" name=\"product_options_values_new_quantity[".$products['products_options_values_id']."]\" value=\"".$products['quantity']."\"></td>\n";
				
		echo "<td class=\"smallText\" align=\"left\"><input type=\"text\" size=\"8\" name=\"product_options_values_new_progressed_quantity[".$products['products_options_values_id']."]\" value=\"".$products['progressed_quantity']."\"></td>\n";
		
		echo "<td class=\"smallText\" align=\"left\"><input type=\"text\" size=\"8\" name=\"product_options_values_new_overall_quantity[".$products['products_options_values_id']."]\" value=\"".$products['overall_quantity']."\"></td>\n";
		
        /*echo "<td class=\"smallText\" align=\"left\"><input type=\"text\" size=\"24\" name=\"product_options_values_new_image[".$products['products_options_values_id']."]\" value=\"".$products['picture']."\"></td>\n";
        */ 
		
		echo "<td class=\"smallText\" align=\"left\"><a class='jt' title='Inventory History' rel='inventory_history.php?type=2&pID=".$products['products_options_values_id']."' href=''>". tep_image(DIR_WS_IMAGES . 'icon_info.gif', TEXT_IMAGE_PREVIEW) ."</a></td>\n";
		
		//// Hidden parameters for cache old values
        echo tep_draw_hidden_field('product_old_name['.$products['products_options_values_id'].'] ',$products['products_options_values_name']);
        
		echo tep_draw_hidden_field('product_old_price['.$products['products_options_values_id'].'] ',$products['options_values_price']);
        
        echo tep_draw_hidden_field('product_old_quantity['.$products['products_options_values_id'].']',$products['quantity']);
				
		echo tep_draw_hidden_field('product_old_progressed_quantity['.$products['products_options_values_id'].']',$products['progressed_quantity']);
		
		echo tep_draw_hidden_field('product_old_overall_quantity['.$products['products_options_values_id'].']',$products['overall_quantity']);
		
        echo tep_draw_hidden_field('product_old_image['.$products['products_options_values_id'].']',$products['picture']);
                
		echo "</tr>";
     }
   

?>
	 </table>
	 
	 </td>
	 
	 </tr>
	 
	 </table>
	 