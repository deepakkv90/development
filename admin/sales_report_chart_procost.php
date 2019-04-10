<?php 
			$models_color = array("#33AD5B","#FF7900","#4C8BFF","#9CD988","#B36912","#FFEDB3","#FF0000","#FFB760","#8DCCF7","#69A55C","#F4CCCB","#88CCEE","#C72C95");
			$zones_id_arr = array("0","182","184","185","186","187","188","189","190");
			$zones_color = array("#33AD5B","#FF7900","#4C8BFF","#9CD988","#B36912","#FFEDB3","#FF0000","#FFB760","#8DCCF7");
			$zones_arr = array_combine($zones_id_arr, $zones_color);
			
			$finacial_graph = "";
								
			foreach($chart_value as $month=>$value) {			
				$shipping = $chart_ship[$month];
				$product = $chart_product[$month];
				$finacial_graph .= '{year:"'.$month.'", revenue:'.$value.', shipping:'.$shipping.', productcost:'.$product.', color:"'.CHART_FINANCIAL_YEAR_COLOR.'"},';			
			}
			
			//Revenues per product code
			$i=0;
			foreach($pc_revenues as $pcmodels => $pcrevenue) {				
				$pc_pie_chart .= '{';							
				$pc_pie_chart .= 'code:"'.$pcmodels.'",values:'.$pcrevenue.',color:"'.$models_color[$i].'"';				
				$pc_pie_chart .= '},';
				$i++;
			}
			
			//Product cost per product code
			$j=0;
			foreach($pc_costs as $pcmodels => $pccost) {				
				$pc_pie_chart2 .= '{';							
				$pc_pie_chart2 .= 'code:"'.$pcmodels.'",values:'.$pccost.',color:"'.$models_color[$j].'"';				
				$pc_pie_chart2 .= '},';
				$j++;
			}
			
			//Product cost per product code
			foreach($pm_costs as $pcmodels => $pmcost) {				
				$plcost = $pl_costs[$pcmodels];
				$pocost = $po_costs[$pcmodels];
				//21 Jan;91;96;69\n22 Jan;87;112;101\n23 Jan;68;79;66\n24 Jan;30;32;23\n25 Jan;52;57;41				
				$pc_bar_chart .= $pcmodels.";".$pmcost.";".$plcost.";".$pocost.'\n';				
			}
			
			//For Material, Labour and Overhead cost - Start
			$finacial_line_cost = ""; $finacial_line_cost_setting = "";								
			
			foreach($pm_costs_per_time as $month=>$pm_value) {			
				$pl_value = $pl_costs_per_time[$month];
				$po_value = $po_costs_per_time[$month];
				$finacial_line_cost .= str_replace(",","",$month).';'.$pm_value.';'.$pl_value.';'.$po_value.'\n';						
			}
			//Generate Line chart settings as revenue by state	
			$finacial_line_cost_setting .= "<graph gid='material'><title>Material</title><color>".CHART_MATERIAL_COLOR."</color><color_hover>FF0F00</color_hover><line_width>2</line_width><bullet>round</bullet></graph><graph gid='labour'><title>Labour</title><color>".CHART_LABOUR_COLOR."</color><color_hover>FF0F00</color_hover><line_width>2</line_width><bullet>round</bullet></graph><graph gid='overhead'><title>Overhead</title><color>".CHART_OVERHEAD_COLOR."</color><color_hover>FF0F00</color_hover><line_width>2</line_width><bullet>round</bullet></graph>";
			//For For Material, Labour and Overhead cost - END
			
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
			
			$pc_graph = ""; 
			foreach($zones_arr as $zoneid=>$color) {
				if($zoneid!=0) {
					$zone_name = tep_get_zonename($zoneid);
				} else {
					$zone_name = "Other Countries";
				}							
				//For Pie chart as revenue per state		
				$pc_graph .= '{state:"'.$zone_name.'", pc:"'.$chart_customers_pc[$zoneid].'", color:"'.$color.'"},';				
			}
			
			
		  ?>
		  
		  <script type="text/javascript">
						
			var chartData1 = [<?php echo $pc_pie_chart; ?>];
			
			var chartData2 = [<?php echo $pc_pie_chart2; ?>];
			
			var chartData4 = [<?php echo $revenue_graph; ?>];
			
			var chartData5 = [<?php echo $pc_graph; ?>];
			
			window.onload = function() 
			{
				// Pie chart
				var chart1 = new AmCharts.AmPieChart();
				chart1.dataProvider = chartData1;			
				chart1.titleField = "code";
				chart1.valueField = "values";
				chart1.colorField = "color";
				chart1.depth3D = 20;
				chart1.angle = 30;
				chart1.labelRadius = 30;
				chart1.labelText = "[[percents]]%";			
				legend1 = new AmCharts.AmLegend();
				legend1.align = "center";
				legend1.markerType = "circle";
				chart1.addLegend(legend1);			
				chart1.write("chartdiv1");
				
				// Pie chart
				var chart2 = new AmCharts.AmPieChart();
				chart2.dataProvider = chartData2;
				chart2.titleField = "code";
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
				chart2.write("chartdiv2");
				
				// Pie chart 3
				var chart = new AmCharts.AmPieChart();
				chart.dataProvider = chartData4;			
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
				chart.write("chartdiv4");
								
				// Pie chart 4
				var chart3 = new AmCharts.AmPieChart();
				chart3.dataProvider = chartData5;
				chart3.titleField = "state";
				chart3.valueField = "pc";
				chart3.colorField = "color";
				chart3.depth3D = 20;
				chart3.angle = 30;
				chart3.labelRadius = 30;
				chart3.labelText = "[[percents]]%";			
				legend3 = new AmCharts.AmLegend();
				legend3.align = "center";
				legend3.markerType = "circle";
				chart3.addLegend(legend3);			
				chart3.write("chartdiv5");
											
			}
			
			</script>
		  
		  <script type="text/javascript">
        
            var params = 
            {
                bgcolor:"#FFFFFF"
            };
            
            var flashVars = 
            {
                path: "includes/javascript/amcharts/flash/",
                
                /* in most cases settings and data are loaded from files, but, as this require
                 all the files to be upladed to web server, we use inline data and settings here.*/
                 
                // settings_file: "../sampleData/column_settings.xml",
                // data_file: "../sampleData/column_data.xml"
                
                chart_data: "<?php echo $pc_bar_chart; ?>",
                chart_settings: "<settings><data_type>csv</data_type><plot_area><margins><left>50</left><right>40</right><top>50</top><bottom>50</bottom></margins></plot_area><grid><category><dashed>1</dashed><dash_length>4</dash_length></category><value><dashed>1</dashed><dash_length>4</dash_length></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><legend><enabled>0</enabled></legend><angle>0</angle><column><width>85</width><balloon_text>{title}: ${value}</balloon_text><grow_time>3</grow_time><sequenced_grow>1</sequenced_grow></column><graphs><graph gid='0'><title>Material</title><color><?php echo CHART_MATERIAL_COLOR; ?></color></graph><graph gid='1'><title>Labour</title><color><?php echo CHART_LABOUR_COLOR; ?></color></graph><graph gid='2'><title>Overhead</title><color><?php echo CHART_OVERHEAD_COLOR; ?></color></graph></graphs><labels><label lid='0'><text><![CDATA[]]></text><y>18</y><text_color>000000</text_color><text_size>13</text_size><align>center</align></label></labels></settings>"
            };
                
            // change 8 to 80 to test javascript version
            if (swfobject.hasFlashPlayerVersion("8"))
            {                
				swfobject.embedSWF("includes/javascript/amcharts/flash/amcolumn.swf", "chartdiv", "800", "600", "8.0.0", "includes/javascript/amcharts/flash/expressInstall.swf", flashVars, params);
            }
            else
            {
              	var amFallback = new AmCharts.AmFallback();
               	// amFallback.settingsFile = flashVars.settings_file;  		// doesn't support multiple settings files or additional_chart_settins as flash does
                // amFallback.dataFile = flashVars.data_file;
                amFallback.chartSettings = flashVars.chart_settings;
                amFallback.pathToImages = "includes/javascript/amcharts/javascript/images/";
                amFallback.chartData = flashVars.chart_data;
                amFallback.type = "column";
                amFallback.write("chartdiv");
            }
        
		
		/* Line chart for revenue, shipping and product cost START */
										
					var flashVars2 = 
					{
						path: "includes/javascript/amcharts/flash/",															 
						// settings_file: "../sampleData/column_settings.xml",
						// data_file: "../sampleData/column_data.xml"								
						chart_data: '<?php echo $finacial_line_cost; ?>',
						chart_settings: "<settings><hide_bullets_count>18</hide_bullets_count><data_type>csv</data_type><plot_area><margins><left>50</left><right>40</right><top>55</top><bottom>30</bottom></margins></plot_area><grid><x><alpha>10</alpha><approx_count>8</approx_count></x><y_left><alpha>10</alpha></y_left></grid><axes><x><width>1</width><color>0D8ECF</color></x><y_left><width>1</width><color>0D8ECF</color></y_left></axes><indicator><color>0D8ECF</color><x_balloon_text_color>FFFFFF</x_balloon_text_color><line_alpha>50</line_alpha><selection_color>0D8ECF</selection_color><selection_alpha>20</selection_alpha></indicator><zoom_out_button><text_color_hover>FF0F00</text_color_hover></zoom_out_button><help><button><color>FCD202</color><text_color>000000</text_color><text_color_hover>FF0F00</text_color_hover></button><balloon><color>FCD202</color><text_color>000000</text_color></balloon></help><graphs><?php echo $finacial_line_cost_setting; ?></graphs><labels><label lid='0'><text><![CDATA[<b> </b>]]></text><y>15</y><text_size>13</text_size><align>center</align></label></labels></settings>"
					};
					
					// change 8 to 80 to test javascript version					
					if (swfobject.hasFlashPlayerVersion("8"))
					{
						swfobject.embedSWF("includes/javascript/amcharts/flash/amline.swf", "chartdiv3", "800", "600", "8.0.0", "includes/javascript/amcharts/flash/expressInstall.swf", flashVars2, params);
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
						amFallback.write("chartdiv3");
					}
				/* Line chart for revenue, shipping and product cost END */
				
        </script>
		
		  
		  <?php
			if($srDetail == 0) {					
			?>
				
				<table align="center" border="0" width="100%">
					
					<tr>
						<td valign="top" width="50%" align="center">
							<br>
							<b>Revenues per Products code </b><font style="color:#EC7600;">[ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ] </font>
							<br>
							<div id="chartdiv1" style="width: 100%; height: 400px;  z-index:2;"></div>		
						</td>
						<td valign="top" width="50%" align="center">
							<br>
							<b>Product Costs per Products code</b><font style="color:#EC7600;">[ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ] </font>
							<br>
							<div id="chartdiv2" style="width: 100%; height: 400px;  z-index:2;"></div>		
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<br>
							<b>Detailed Cost per Products Code</b><font style="color:#EC7600;">[ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ] </font>
							<br>
							<div id="chartdiv" style="width:600px; height:400px; background-color:#FFFFFF"></div>				
						</td>
					</tr>
					
					<tr>
						<td colspan="2">
							<br>
							<b>Line Chart for Products Cost</b><font style="color:#EC7600;">[ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ] </font>
							<br>
							<div id="chartdiv3" style="width:600px; height:400px; background-color:#FFFFFF"></div>				
						</td>
					</tr>
					
					<tr>
						<td width="50%" valign="top" align="center">
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
						<td width="50%" valign="top" align="center">
							<b>Revenue per State [ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ]</b>	
							<br>
							<div id="chartdiv4" style="width: 100%; height: 400px;  z-index:2;"></div>
						</td>
					</tr>
					<tr>
						<td width="50%" valign="top" align="center">
							<div class="map2" style="background:#FFF url(images/australia.png) 0 0 no-repeat;">
									
								<div class="queensland"><?php echo $currencies->format($chart_customers_pc[182]); ?></div>
								<div class="south-wales"><?php echo $currencies->format($chart_customers_pc[184]); ?></div>
								<div class="victoria"><?php echo $currencies->format($chart_customers_pc[185]); ?></div>
								<div class="south"><?php echo $currencies->format($chart_customers_pc[186]); ?></div>
								<div class="western"><?php echo $currencies->format($chart_customers_pc[187]); ?></div>
								<div class="act"><?php echo $currencies->format($chart_customers_pc[188]); ?></div>
								<div class="northern"><?php echo $currencies->format($chart_customers_pc[189]); ?></div>
								<div class="tasmania"><?php echo $currencies->format($chart_customers_pc[190]); ?></div>
								<div class="other">Others: <?php echo $currencies->format($chart_customers_pc[0]); ?></div>
															
							</div>
						</td>
						<td width="50%" valign="top" align="center">
							<b>Product Costs per State [ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ]</b>	
							<br>
							<div id="chartdiv5" style="width: 100%; height: 400px;  z-index:2;"></div>
						</td>
					</tr>
					
				</table>
				
			<?php			
				//print_r($chart_value);						
			}
			?>