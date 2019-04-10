<?php 
			
			$zones_id_arr = array("0","182","184","185","186","187","188","189","190");
			$zones_color = array("#33AD5B","#FF7900","#4C8BFF","#9CD988","#B36912","#FFEDB3","#FF0000","#FFB760","#8DCCF7");
			$zones_arr = array_combine($zones_id_arr, $zones_color);
									
			$revenue_graph = ""; 
			foreach($zones_arr as $zoneid=>$color) {
				if($zoneid!=0) {
					$zone_name = tep_get_zonename($zoneid);
				} else {
					$zone_name = "Other Countries";
				}							
				//For Pie chart as revenue per state		
				$revenue_graph .= '{state:"'.$zone_name.'", revenue:"'.$chart_customers_revenue[$zoneid].'", color:"'.$color.'"},';				
			}
			
			$shipping_graph = ""; 
			foreach($zones_arr as $zoneid=>$color) {
				if($zoneid!=0) {
					$zone_name = tep_get_zonename($zoneid);
				} else {
					$zone_name = "Other Countries";
				}							
				//For Pie chart as revenue per state		
				$shipping_graph .= '{state:"'.$zone_name.'", shipping:"'.$chart_customers_shipping[$zoneid].'", color:"'.$color.'"},';				
			}
			
			//print_r($revenue_graph);
			
			foreach($zones_arr as $zoneid=>$color) {
				$male_arr[$zoneid] = tep_get_male_customers_by_state($zoneid);
				$female_arr[$zoneid] = tep_get_female_customers_by_state($zoneid);
			}	
			
		  ?>
		  
		  <script type="text/javascript">
			
			var chart;
			
			var chartData = [<?php echo $revenue_graph; ?>];
						
			var chartData2 = [<?php echo $shipping_graph; ?>];
			
			
			window.onload = function() 
			{
				// Pie chart
				var chart = new AmCharts.AmPieChart();
				chart.dataProvider = chartData;			
				chart.titleField = "state";
				chart.valueField = "revenue";
				chart.colorField = "color";
				chart.depth3D = 20;
				chart.angle = 30;
				chart.labelRadius = 30;
				chart.labelText = "[[percents]]%";		
				legend = new AmCharts.AmLegend();
				legend.align = "center";
				legend.markerType = "circle";
				chart.addLegend(legend);
				chart.write("chartdiv");
								
				// Pie chart
				var chart2 = new AmCharts.AmPieChart();
				chart2.dataProvider = chartData2;
				chart2.titleField = "state";
				chart2.valueField = "shipping";
				chart2.colorField = "color";
				chart2.depth3D = 20;
				chart2.angle = 30;
				chart2.labelRadius = 30;
				chart2.labelText = "[[percents]]%";			
				legend2 = new AmCharts.AmLegend();
				legend2.align = "center";
				legend2.markerType = "circle";
				chart2.addLegend(legend2);			
				chart2.write("chartdiv2");
											
			}
			
			</script>
		  
		  
		  <?php 
			if($srDetail == 0) {					
			?>
				
				<table align="center" border="0" width="100%">
										
					<tr>
						<td valign="top" width="50%" align="center">&nbsp;
							
							<b>Revenue per State [ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ]</b>	
							<br>
							<div id="chartdiv" style="width: 100%; height: 400px;  z-index:2;"></div>
											
						</td>
						<td valign="top" width="50%" align="center">
							<b>Shipping Cost per State [ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ]</b>	
							<br>
							<div id="chartdiv2" style="width: 100%; height: 400px;  z-index:2;"></div>
						</td>
					</tr>
					
					<tr>
						<td valign="top" width="50%" align="center">&nbsp;
							<b>Revenue per State [ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ]</b>	
							<br>
							
							<div class="map1" style="background:#FFF url(images/australia.png) 0 0 no-repeat;">
								
								<div class="queensland"><?php echo $currencies->format($chart_customers_revenue[182]); ?></div>
								<div class="south-wales"><?php echo $currencies->format($chart_customers_revenue[184]); ?></div>
								<div class="victoria"><?php echo $currencies->format($chart_customers_revenue[185]); ?></div>
								<div class="south"><?php echo $currencies->format($chart_customers_revenue[186]); ?></div>
								<div class="western"><?php echo $currencies->format($chart_customers_revenue[187]); ?></div>
								<div class="act"><?php echo $currencies->format($chart_customers_revenue[188]); ?></div>
								<div class="northern"><?php echo $currencies->format($chart_customers_revenue[189]); ?></div>
								<div class="tasmania"><?php echo $currencies->format($chart_customers_revenue[190]); ?></div>
								<div class="other">Others: <?php echo $currencies->format($chart_customers_revenue[0]); ?></div>
								
							</div>	
												
						</td>
						<td valign="top" width="50%" align="center">
							<div class="map2" style="background:#FFF url(images/australia.png) 0 0 no-repeat;">
								<div class="queensland" style="left:240px;">
									<div class="male-cust"><?php echo $male_arr[182]; ?></div>
									<div class="female-cust"><?php echo $female_arr[182]; ?></div>
								</div>
								<div class="south-wales" style="left:300px;">
									<div class="male-cust"><?php echo $male_arr[184]; ?></div>
									<div class="female-cust"><?php echo $female_arr[184]; ?></div>
								</div>
								<div class="victoria" style="left:260px;">
									<div class="male-cust"><?php echo $male_arr[185]; ?></div>
									<div class="female-cust"><?php echo $female_arr[185]; ?></div>
								</div>
								<div class="south" style="left:145px; top:165px;">
									<div class="male-cust"><?php echo $male_arr[186]; ?></div>
									<div class="female-cust"><?php echo $female_arr[186]; ?></div>
								</div>
								<div class="western" style="left:40px;">
									<div class="male-cust"><?php echo $male_arr[187]; ?></div>
									<div class="female-cust"><?php echo $female_arr[187]; ?></div>
								</div>
								<div class="act" style="left:360px;">
									<div class="male-cust"><?php echo $male_arr[188]; ?></div>
									<div class="female-cust"><?php echo $female_arr[188]; ?></div>
								</div>
								<div class="northern" style="left:145px;">
									<div class="male-cust"><?php echo $male_arr[189]; ?></div>
									<div class="female-cust"><?php echo $female_arr[189]; ?></div>
								</div>
								<div class="tasmania" style="left:310px;">
									<div class="male-cust"><?php echo $male_arr[190]; ?></div>
									<div class="female-cust"><?php echo $female_arr[190]; ?></div>
								</div>
								<div class="other" style="left:40px;">
									<div class="male-cust"><?php echo tep_get_male_customers_outside_aus(); ?></div>
									<div class="female-cust"><?php echo tep_get_female_customers_outside_aus(); ?></div>
								</div>
							</div>	
						</td>
					</tr>
				</table>
				
			<?php			
				//print_r($chart_value);						
			}
			?>