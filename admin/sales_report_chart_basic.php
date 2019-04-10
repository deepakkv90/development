<?php 
			$finacial_graph = "";
								
			foreach($chart_value as $month=>$value) {			
				$shipping = $chart_ship[$month];
				$product = $chart_product[$month];
				$finacial_graph .= '{year:"'.$month.'", revenue:'.$value.', shipping:'.$shipping.', productcost:'.$product.', color:"'.CHART_FINANCIAL_YEAR_COLOR.'"},';			
			}
			
		  ?>
		  
		  <script type="text/javascript">
			
			var chart;
			
			var chartData = [<?php echo $finacial_graph; ?>];
			
			var chartData1 = [
			   {country:"Total Revenue",litres:<?php echo $chart_revenue; ?>,color:"<?php echo CHART_REVENUE_COLOR; ?>"},
			   {country:"Product Cost",litres:<?php echo $chart_productcost; ?>,color:"<?php echo CHART_PRODUCT_COST_COLOR; ?>"},
			   {country:"Shipping",litres:<?php echo $chart_shipping; ?>,color:"<?php echo CHART_SHIPPING_COLOR; ?>"}];
			
			var chartData2 = [
				{country:"Overhead",litres:<?php echo $chart_overhead; ?>,color:"<?php echo CHART_OVERHEAD_COLOR; ?>"},			
				{country:"Labour",litres:<?php echo $chart_labour; ?>,color:"<?php echo CHART_LABOUR_COLOR; ?>"},				
				{country:"Material",litres:<?php echo $chart_material; ?>,color:"<?php echo CHART_MATERIAL_COLOR; ?>"}
				];
			
			
			window.onload = function() 
			{
				// Pie chart
				var chart1 = new AmCharts.AmPieChart();
				chart1.dataProvider = chartData1;			
				chart1.titleField = "country";
				chart1.valueField = "litres";
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
				chart2.titleField = "country";
				chart2.valueField = "litres";
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
				
				//Bar column for financial year			
				chart = new AmCharts.AmSerialChart();
				//chart.pathToImages = "../../amcharts/javascript/images/";
				chart.dataProvider = chartData;				
				chart.marginTop = 15;
				chart.marginRight = 20;
				chart.categoryField = "year";				
				chart.angle = 30;
				chart.depth3D = 30;
				
				var legend = new AmCharts.AmLegend();
				chart.addLegend(legend);
				
				var graph1 = new AmCharts.AmGraph();
				graph1.title = "Revenue";
				graph1.valueField = "revenue";
				graph1.lineColor = "<?php echo CHART_FINANCIAL_YEAR_COLOR; ?>";
				graph1.colorField = "color";						
				graph1.type = "column";
				graph1.lineAlpha = 0;
				graph1.fillAlphas = 1;
				chart.addGraph(graph1);
				
				
				var graph2 = new AmCharts.AmGraph();
				graph2.title = "Shipping";
				graph2.valueField = "shipping";						
				graph2.type = "line";
				graph2.lineColor = "<?php echo CHART_SHIPPING_COLOR; ?>";	
				graph2.lineThickness = 2;
				graph2.bullet = "round";
				chart.addGraph(graph2);
				
				var graph3 = new AmCharts.AmGraph();
				graph3.title = "Product Cost";
				graph3.valueField = "productcost";					
				graph3.type = "line";
				graph3.lineColor = "<?php echo CHART_PRODUCT_COST_COLOR; ?>";	
				graph3.lineThickness = 2;
				graph3.bullet = "round";			
				chart.addGraph(graph3);
				
				chart.categoryAxis.gridPosition = "start";
				
				chart.write("chartdiv");
							
			}
			
			</script>
		  
		  
		  <?php
			if($srDetail == 0) {					
			?>
				
				<table align="center" border="0" width="100%">
					
					<tr>
						<td valign="top" width="50%" align="center">
							<br>
							<b>Revenues </b><font style="color:#EC7600;">[ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ] </font>
							<br>
							<div id="chartdiv1" style="width: 100%; height: 400px;  z-index:2;"></div>		
						</td>
						<td valign="top" width="50%" align="center">
							<br>
							<b>Product Cost </b><font style="color:#EC7600;">[ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ] </font>
							<br>
							<div id="chartdiv2" style="width: 100%; height: 400px;  z-index:2;"></div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<b>Current Financial Year Revenue</b>	
							<br>
							<div id="chartdiv" style="width: 60%; height: 400px;  z-index:2;"></div>
						
						</td>
					</tr>
				</table>
				
			<?php			
				//print_r($chart_value);						
			}
			?>