<?php 

		//For Revenue, shipping and product cost - Start
			$finacial_line_revenue = ""; $finacial_line_revenue_setting = "";								
			foreach($chart_value as $month=>$value) {			
				$shipping = $chart_ship[$month];
				$product = $chart_product[$month];
				$finacial_line_revenue .= str_replace(",","",$month).';'.$value.';'.$shipping.';'.$product.'\n';						
			}
			//Generate Line chart settings as revenue by state	
			$finacial_line_revenue_setting .= "<graph gid='revenue'><title>Revenue</title><color>".CHART_REVENUE_COLOR."</color><color_hover>FF0F00</color_hover><line_width>2</line_width><bullet>round</bullet></graph><graph gid='shipping'><title>Shipping</title><color>".CHART_SHIPPING_COLOR."</color><color_hover>FF0F00</color_hover><line_width>2</line_width><bullet>round</bullet></graph><graph gid='product_cost'><title>Product Cost</title><color>".CHART_PRODUCT_COST_COLOR."</color><color_hover>FF0F00</color_hover><line_width>2</line_width><bullet>round</bullet></graph>";
			//For Revenue, shipping and product cost - END
			
			//For Revenue per financial year START
			$qryRevenue = tep_db_query("SELECT ot.value as subtotal, o.date_purchased, o.last_modified FROM orders o LEFT JOIN orders_total ot ON (ot.orders_id = o.orders_id AND (ot.class = 'ot_grand_subtotal' OR ot.class='ot_subtotal')) ORDER BY o.orders_id ASC");
			while($qryArr = tep_db_fetch_array($qryRevenue)) {
				$ord_date = $qryArr['date_purchased'];				
				$ord_month = date("m",strtotime($ord_date));
				if($ord_month<7) {										
					$sDate = mktime(0, 0, 0, 7, 1, date("Y",strtotime($ord_date))-1);
					$eDate = mktime(23, 59, 59, 6, 30, date("Y",strtotime($ord_date)));	  
				} else {
					$sDate = mktime(0, 0, 0, 7, 1, date("Y",strtotime($ord_date)));
					$eDate = mktime(23, 59, 59, 6, 30, date("Y",strtotime($ord_date))+1);	 
				}
				$ord_year = date("Y",$sDate)." - ".date("Y",$eDate);								
				$rev_year_arr[$ord_year][date("M",strtotime($ord_date))] += $qryArr['subtotal'];
			}			
			
			$line_revenue_comparision = ""; $line_revenue_comparision_setting = "";
				
			$rev_months_arr = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
			
			foreach($rev_months_arr as $month_name) {
				
				$line_revenue_comparision .= $month_name.';';
				$line_revenue_string = "";
				foreach($rev_year_arr as $year => $rev_months) { 
													
					if($rev_months[$month_name]>0) {						
						$line_revenue_string .= $rev_months[$month_name].';';								
					} else {
						$line_revenue_string .= "0.00".';';	
					}
					
				}
				$line_revenue_comparision .= substr($line_revenue_string,0,-1);
				$line_revenue_comparision .= '\n';				
				
			}
			$years_color = array("#33AD5B","#FF7900","#4C8BFF","#9CD988","#B36912","#FFEDB3","#FF0000","#FFB760","#8DCCF7");
			$y=0;
			foreach($rev_year_arr as $year => $rev_months) { 				
				$line_revenue_comparision_setting .= "<graph gid='".$year."'><title>".$year."</title><color>".$years_color[$y]."</color><color_hover>FF0F00</color_hover><line_width>2</line_width><bullet>round</bullet></graph>";	
				$y++;			
			}
			//For Revenue per financial year END
					
			
			//Revenue per product cost
			$pc_fin_chart = "";
			foreach($pc_revenues as $pcdates => $pcmodels) {				
				$pc_fin_chart .= '{year:"'.$pcdates.'",';
				
				foreach($pcmodels as $pcmodel=>$pcrevenue) {					
					$pc_fin_chart .= $pcmodel.':'.$pcrevenue.',';
				}				
				$pc_fin_chart .= '},';
			}			
			$pc_fin_chart_settings= "";
			
			foreach($models as $model => $mcolor) {
				$pc_fin_chart_settings .= 'var graph = new AmCharts.AmGraph();
						graph.title = "'.$model.'";
						graph.labelText="[[value]]";
						graph.balloonText="'.$model.': [[value]]";
						graph.valueField = "'.$model.'";
						graph.type = "column";
						graph.lineAlpha = 0;
						graph.fillAlphas = 1;
						graph.lineColor = "'.$mcolor.'";
						chart.addGraph(graph);';
			}
			
			//echo $pc_fin_chart;
			
			
			$zones_id_arr = array("0","182","184","185","186","187","188","189","190");
			$zones_color = array("#33AD5B","#FF7900","#4C8BFF","#9CD988","#B36912","#FFEDB3","#FF0000","#FFB760","#8DCCF7");
			$zones_arr = array_combine($zones_id_arr, $zones_color);
			
			//Customer revenue per state	
			foreach($chart_customers_revenue_month as $customers => $month) {
				
				$zone_id = tep_get_customers_zone($customers);			
							
				foreach($month as $month_name => $revenue) {
				
					foreach($revenue as $rev_month => $rev) {																	
						if(is_numeric($zone_id) && $zone_id!=183) {
							$zone_revenue[$zone_id] += $rev;
							$zone_revenue_per_month[$zone_id][$rev_month] += $rev;
						} else {
							$zone_revenue[0] += $rev;
							$zone_revenue_per_month[0][$rev_month] += $rev;
						}	
						
					}
					
				}
						
			}
					
			//Generate Line chart data as revenue by state
			$revenue_line = "";			
			foreach($crevs as $ln_date_x => $ln_zones) {				
				$revenue_line .= $ln_date_x.';';
				foreach($zones_arr as $zoneid=>$color) {
					$revenue_line .= ($ln_zones[$zoneid]>0) ? $ln_zones[$zoneid] :0;
					$revenue_line .= ';';					
				}
				$revenue_line .= '\n';
			}
			
			
			$revenue_graph = ""; 
			foreach($zones_arr as $zoneid=>$color) {
				if($zoneid!=0) {
					$zone_name = tep_get_zonename($zoneid);
				} else {
					$zone_name = "Other Countries";
				}		
				
				//Generate Line chart settings as revenue by state				
				$revenue_setting .= "<graph gid='".$zoneid."'><title>".$zone_name."</title><color>".$color."</color><color_hover>FF0F00</color_hover><line_width>2</line_width><bullet>round</bullet></graph>";				
				//For Pie chart as revenue per state		
				$revenue_graph .= '{country:"'.$zone_name.'", revenue:"'.$zone_revenue[$zoneid].'", color:"'.$color.'"},';				
			}
			
			
			//Active customers
			//customers outside australia
			$active_outside_aus = tep_get_active_customers_outside_aus();						
			$western_act = tep_get_active_customers_by_state("187");				
			$north_act = tep_get_active_customers_by_state("189");
			$south_act = tep_get_active_customers_by_state("186");
			$queen_act = tep_get_active_customers_by_state("182");
			$wales_act = tep_get_active_customers_by_state("184");
			$vic_act = tep_get_active_customers_by_state("185");
			$tas_act = tep_get_active_customers_by_state("190");
			$act_act = tep_get_active_customers_by_state("188");
						
			if($srDetail == 0) {	?>
				
				 <script type="text/javascript">
			
					var chart;
					var chartData = [];
					var newValueAxis;
					var addAxis;
					var removeAxis;
			
					
					var chartData1 = [<?php echo $revenue_graph; ?>];
										
					var chartData2 = [
									{country:"Northern Territory",values:<?php echo $north_act; ?>,color:"#FFB760"},			
									{country:"ACT",values:<?php echo $act_act; ?>,color:"#FF0000"},	
									{country:"Western Australia",values:<?php echo $western_act; ?>,color:"#FFEDB3"},		
									{country:"Victoria",values:<?php echo $vic_act; ?>,color:"#9CD988"},
									{country:"New South Wales",values:<?php echo $wales_act; ?>,color:"#4C8BFF"},					
									{country:"Queensland",values:<?php echo $queen_act; ?>,color:"#FF7900"},
									{country:"Tasmania",values:<?php echo $tas_act; ?>,color:"#8DCCF7"},	
									{country:"South Australia",values:<?php echo $south_act; ?>,color:"#B36912"},
									{country:"Other Countries",values:<?php echo $active_outside_aus; ?>,color:"#33AD5B"}
									];
					
					var chartData3 = [<?php echo $pc_fin_chart; ?>];
					
					
					window.onload = function() 
					{
						// Pie chart
						var chart1 = new AmCharts.AmPieChart();
						chart1.dataProvider = chartData1;			
						chart1.titleField = "country";
						chart1.valueField = "revenue";
						chart1.colorField = "color";
						chart1.depth3D = 20;
						chart1.angle = 30;
						chart1.labelRadius = 30;
						chart1.labelText = "[[percents]]%";			
						legend = new AmCharts.AmLegend();
						legend.align = "center";
						legend.markerType = "circle";
						chart1.addLegend(legend);			
						chart1.write("chart_state_revenue");
						
						// Pie chart
						var chart2 = new AmCharts.AmPieChart();
						chart2.dataProvider = chartData2;
						chart2.titleField = "country";
						chart2.valueField = "values";
						chart2.colorField = "color";
						chart2.depth3D = 20;
						chart2.angle = 30;
						chart2.labelRadius = 30;
						chart2.labelText = "[[percents]]%";			
						legend2 = new AmCharts.AmLegend();
						legend2.align = "center";
						legend2.markerType = "circle";
						chart2.addLegend(legend2);			
						chart2.write("chart_state_active_customers");
						
						//Chart for revenue per products code
						chart = new AmCharts.AmSerialChart();
						chart.dataProvider = chartData3;
						chart.categoryField = "year";
						chart.marginLeft = 90;
						chart.marginTop = 30;
						chart.plotAreaBorderAlpha = 0.2;
						chart.rotate = true;
						
						<?php echo $pc_fin_chart_settings; ?>
						
						var valAxis = new AmCharts.ValueAxis();
						valAxis.stackType = "regular";
						valAxis.gridAlpha = 0.1;
						valAxis.axisAlpha = 0;
						chart.addValueAxis(valAxis);
						  
						var catAxis = chart.categoryAxis;
						catAxis.gridAlpha = 0.1;
						catAxis.axisAlpha = 0;
						catAxis.gridPosition = "start";
						  
						var legend = new AmCharts.AmLegend();
						legend.position = "right";
						legend.borderAlpha = 0.2;
						legend.horizontalGap = 10;
						legend.switchType = "v";
						chart.addLegend(legend);
						
						chart.write("chartdiv3");
					
					}
										
				</script>
				
				<script type="text/javascript">
				  /* Line chart for revenue per state START */
							
					var params = { 	bgcolor:"#FFFFFF" 	};
					
					var flashVars = 
					{
						path: "includes/javascript/amcharts/flash/",															 
						// settings_file: "../sampleData/column_settings.xml",
						// data_file: "../sampleData/column_data.xml"								
						chart_data: '<?php echo $revenue_line; ?>',
						chart_settings: "<settings><hide_bullets_count>18</hide_bullets_count><data_type>csv</data_type><plot_area><margins><left>50</left><right>40</right><top>55</top><bottom>30</bottom></margins></plot_area><grid><x><alpha>10</alpha><approx_count>8</approx_count></x><y_left><alpha>10</alpha></y_left></grid><axes><x><width>1</width><color>0D8ECF</color></x><y_left><width>1</width><color>0D8ECF</color></y_left></axes><indicator><color>0D8ECF</color><x_balloon_text_color>FFFFFF</x_balloon_text_color><line_alpha>50</line_alpha><selection_color>0D8ECF</selection_color><selection_alpha>20</selection_alpha></indicator><zoom_out_button><text_color_hover>FF0F00</text_color_hover></zoom_out_button><help><button><color>FCD202</color><text_color>000000</text_color><text_color_hover>FF0F00</text_color_hover></button><balloon><color>FCD202</color><text_color>000000</text_color></balloon></help><graphs><?php echo $revenue_setting; ?></graphs><labels><label lid='0'><text><![CDATA[<b>Revenues</b>]]></text><y>15</y><text_size>13</text_size><align>center</align></label></labels></settings>"
					};
					
					// change 8 to 80 to test javascript version					
					if (swfobject.hasFlashPlayerVersion("8"))
					{
						swfobject.embedSWF("includes/javascript/amcharts/flash/amline.swf", "chartdiv", "800", "600", "8.0.0", "includes/javascript/amcharts/flash/expressInstall.swf", flashVars, params);
					}
					else
					{ 
						var amFallback = new AmCharts.AmFallback();
						// amFallback.settingsFile = flashVars.settings_file;  		// doesn't support multiple settings files or additional_chart_settins as flash does
						// amFallback.dataFile = flashVars.data_file;
						amFallback.chartSettings = flashVars.chart_settings;
						amFallback.pathToImages = "includes/javascript/amcharts/javascript/images/";
						amFallback.chartData = flashVars.chart_data;
						amFallback.type = "line";
						amFallback.write("chartdiv");
					}
							
				/* Line chart for revenue per state END */
				
				/* Line chart for revenue, shipping and product cost START */
										
					var flashVars2 = 
					{
						path: "includes/javascript/amcharts/flash/",															 
						// settings_file: "../sampleData/column_settings.xml",
						// data_file: "../sampleData/column_data.xml"								
						chart_data: '<?php echo $finacial_line_revenue; ?>',
						chart_settings: "<settings><hide_bullets_count>18</hide_bullets_count><data_type>csv</data_type><plot_area><margins><left>50</left><right>40</right><top>55</top><bottom>30</bottom></margins></plot_area><grid><x><alpha>10</alpha><approx_count>8</approx_count></x><y_left><alpha>10</alpha></y_left></grid><axes><x><width>1</width><color>0D8ECF</color></x><y_left><width>1</width><color>0D8ECF</color></y_left></axes><indicator><color>0D8ECF</color><x_balloon_text_color>FFFFFF</x_balloon_text_color><line_alpha>50</line_alpha><selection_color>0D8ECF</selection_color><selection_alpha>20</selection_alpha></indicator><zoom_out_button><text_color_hover>FF0F00</text_color_hover></zoom_out_button><help><button><color>FCD202</color><text_color>000000</text_color><text_color_hover>FF0F00</text_color_hover></button><balloon><color>FCD202</color><text_color>000000</text_color></balloon></help><graphs><?php echo $finacial_line_revenue_setting; ?></graphs><labels><label lid='0'><text><![CDATA[<b> </b>]]></text><y>15</y><text_size>13</text_size><align>center</align></label></labels></settings>"
					};
					
					// change 8 to 80 to test javascript version					
					if (swfobject.hasFlashPlayerVersion("8"))
					{
						swfobject.embedSWF("includes/javascript/amcharts/flash/amline.swf", "chartdiv4", "800", "600", "8.0.0", "includes/javascript/amcharts/flash/expressInstall.swf", flashVars2, params);
					}
					else
					{ 
						var amFallback = new AmCharts.AmFallback();
						// amFallback.settingsFile = flashVars.settings_file;  		// doesn't support multiple settings files or additional_chart_settins as flash does
						// amFallback.dataFile = flashVars.data_file;
						amFallback.chartSettings = flashVars2.chart_settings;
						amFallback.pathToImages = "includes/javascript/amcharts/javascript/images/";
						amFallback.chartData = flashVars2.chart_data;
						amFallback.type = "line";
						amFallback.write("chartdiv4");
					}
				/* Line chart for revenue, shipping and product cost END */
				
				
				/* Line chart for revenue comparision with years START */
										
					var flashVars3 = 
					{
						path: "includes/javascript/amcharts/flash/",															 
						// settings_file: "../sampleData/column_settings.xml",
						// data_file: "../sampleData/column_data.xml"								
						chart_data: '<?php echo $line_revenue_comparision; ?>',
						chart_settings: "<settings><hide_bullets_count>18</hide_bullets_count><data_type>csv</data_type><plot_area><margins><left>50</left><right>40</right><top>55</top><bottom>30</bottom></margins></plot_area><grid><x><alpha>10</alpha><approx_count>8</approx_count></x><y_left><alpha>10</alpha></y_left></grid><axes><x><width>1</width><color>0D8ECF</color></x><y_left><width>1</width><color>0D8ECF</color></y_left></axes><indicator><color>0D8ECF</color><x_balloon_text_color>FFFFFF</x_balloon_text_color><line_alpha>50</line_alpha><selection_color>0D8ECF</selection_color><selection_alpha>20</selection_alpha></indicator><zoom_out_button><text_color_hover>FF0F00</text_color_hover></zoom_out_button><help><button><color>FCD202</color><text_color>000000</text_color><text_color_hover>FF0F00</text_color_hover></button><balloon><color>FCD202</color><text_color>000000</text_color></balloon></help><graphs><?php echo $line_revenue_comparision_setting; ?></graphs><labels><label lid='0'><text><![CDATA[<b> </b>]]></text><y>15</y><text_size>13</text_size><align>center</align></label></labels></settings>"
					};
					
					// change 8 to 80 to test javascript version					
					if (swfobject.hasFlashPlayerVersion("8"))
					{
						swfobject.embedSWF("includes/javascript/amcharts/flash/amline.swf", "chartdiv5", "800", "600", "8.0.0", "includes/javascript/amcharts/flash/expressInstall.swf", flashVars3, params);
					}
					else
					{ 
						var amFallback = new AmCharts.AmFallback();
						// amFallback.settingsFile = flashVars.settings_file;  		// doesn't support multiple settings files or additional_chart_settins as flash does
						// amFallback.dataFile = flashVars.data_file;
						amFallback.chartSettings = flashVars3.chart_settings;
						amFallback.pathToImages = "includes/javascript/amcharts/javascript/images/";
						amFallback.chartData = flashVars3.chart_data;
						amFallback.type = "line";
						amFallback.write("chartdiv5");
					}
				/* Line chart for revenue comparision with years END */
				
				
			  </script>
			  
				
				<table align="center" border="0" width="100%">
					
					<tr>
						<td colspan="2" valign="top">
							<div id="chartdiv" style="width:800px; height:600px; background-color:#FFFFFF"></div>
						</td>
					</tr>
					<tr>
						<td valign="top" width="50%" align="center">
							<br>
							<b>Revenues per State </b><font style="color:#EC7600;"> [ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ] </font>	
							<br>
							<div id="chart_state_revenue" style="width: 100%; height: 500px;  z-index:2;"></div>		
						</td>
						<td valign="top" width="50%" align="center">
							<br>
							<b>Active Customers per State</b> <font style="color:#EC7600;">[ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ] </font>
							<br>
							<div id="chart_state_active_customers" style="width: 100%; height: 500px;  z-index:2;"></div>
						</td>
					</tr>	
					<tr>
						<td colspan="2">
							<br>
							<b>Revenues per Product Code</b> <font style="color:#EC7600;">[ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ] </font>
							<br>
							<div id="chartdiv3" style="width: 90%; height: 400px;"></div>
						</td>
					</tr>	
					
					<tr>
						<td colspan="2">
							<br>
							<b>Revenues, Shipping and Product cost</b> <font style="color:#EC7600;">[ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ] </font>
							<br>
							<div id="chartdiv4" style="width: 90%; height: 400px;"></div>
						</td>
					</tr>
					
					<tr>
						<td colspan="2">
							<br>
							<b>Revenues Yearly Comparision</b> <font style="color:#EC7600;">[ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ] </font>
							<br>
							<div id="chartdiv5" style="width: 90%; height: 400px;"></div>
						</td>
					</tr>				
								
				</table>
				
			<?php			
				//print_r($chart_value);						
			}
			
			?>