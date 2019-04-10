<?php
require('includes/application_top.php');

//get path of directory containing this script

//Code to Check Backup Count
//$handle = opendir(DIR_FS_BACKUP."/ravi");
if ($handle = @opendir(DIR_FS_BACKUP)) 
{
  $count = 0;
  //loop through the directory
  $year="1900"; //please dont change this value
  $dayofyear="0"; //please dont change this value
  $lastbackupdate="";
  while (($filename = readdir($handle)) !== false)
  {
    //evaluate each entry, removing the . & .. entries
  if (($filename != ".") && ($filename != ".."))
  {
    $fileyear=date("Y", filemtime(DIR_FS_BACKUP.$filename));  
    if($fileyear > $year)
    {
      $filedayofyear=date("z", filemtime(DIR_FS_BACKUP.$filename));    
      $year=$fileyear;
      $dayofyear=$filedayofyear;
      $lastbackupdate=date("m/d/Y", filemtime(DIR_FS_BACKUP.$filename));
    }
    elseif($fileyear==$year)
    {
      $filedayofyear=date("z", filemtime(DIR_FS_BACKUP.$filename));    
      if($filedayofyear > $dayofyear)
      {      
        $lastbackupdate=date("m/d/Y", filemtime(DIR_FS_BACKUP.$filename));      
        $dayofyear=$filedayofyear;
      }  
    }  
  $count++;
  }
  }
}//dir check if
else
{$count=0;$lastbackupdate="";}
define('BACKUP_COUNT',$count);
define('LAST_BACKUP_DATE',$lastbackupdate);

// Langauge code
  $languages = tep_get_languages();
  $languages_array = array();
  $languages_selected = DEFAULT_LANGUAGE;
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['code'],
                               'text' => $languages[$i]['name']);
    if ($languages[$i]['directory'] == $language) {
      $languages_selected = $languages[$i]['code'];
    }
  }
// Langauge code EOF  
// Get admin name 
//  $my_account_query = tep_db_query ("select a.admin_id, a.admin_firstname, a.admin_lastname, a.admin_email_address, a.admin_created, a.admin_modified, a.admin_logdate, a.admin_lognum, g.admin_groups_name from " . TABLE_ADMIN . " a, " . TABLE_ADMIN_GROUPS . " g where a.admin_id= " . $login_id . " and g.admin_groups_id= " . $login_groups_id . "");

$myAccount = tep_db_fetch_array($my_account_query);
  define('STORE_ADMIN_NAME',$myAccount['admin_firstname'] . ' ' . $myAccount['admin_lastname']);
  define('TEXT_WELCOME','Welcome <strong>' . STORE_ADMIN_NAME . '</strong> to <strong>' . STORE_NAME . '</strong> Administration!');

  define('MODE_FOR_MAINTENANCE_1',"Active");
define('MODE_FOR_MAINTENANCE_2',"Maintanace");

// Admin Name EOF
// Store Status code 
if (DOWN_FOR_MAINTENANCE == 'false'){
  //$store_status = '<font color="#009900">Active</font>';
  $store_status = '<font color="#009900">'.MODE_FOR_MAINTENANCE_1.'</font>';
  } else {
  //$store_status = '<font color="#FF0000">Maintanace</font>';
  $store_status = '<font color="#FF0000">'.MODE_FOR_MAINTENANCE_2.'</font>';
  }
// Store Status Code EOF

//Affiliate Count Code
$affiliate_query = tep_db_query("select count(affiliate_id) as affiliatecnt from " . TABLE_AFFILIATE_AFFILIATE);
$affiliatecount = tep_db_fetch_array($affiliate_query);
define('AFFILIATE_COUNT',$affiliatecount['affiliatecnt']);

$affiliate_query = tep_db_query('SELECT round(sum( sales.affiliate_value),2)  AS affiliate, 
                                        round(sum( ( sales.affiliate_value * sales.affiliate_percent ) / 100),2)  AS commission
                                 FROM ' . TABLE_AFFILIATE_SALES . ' sales
                                 left join ' . TABLE_ORDERS . ' o on sales.affiliate_orders_id = o.orders_id
                                 where o.orders_id is not null
                                   and affiliate_id != 0
                                   and sales.affiliate_billing_status = 0
                                   and o.orders_status = ' . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . '
                               ');

$affiliatecount = tep_db_fetch_array($affiliate_query);
$affiliatesales=$affiliatecount['affiliate'];
if($affiliatesales==""){$affiliatesales=0;}
$affiliatecomm=$affiliatecount['commission'];
if($affiliatecomm==""){$affiliatecomm=0;}
define('AFFILIATE_SALES_AMOUNT',$affiliatesales);
define('AFFILIATE_COMMISSION_AMOUNT',$affiliatecomm);

//Category Count Code
$category_query = tep_db_query("select count(categories_id) as catcnt from " . TABLE_CATEGORIES);
$categorycount = tep_db_fetch_array($category_query);
define('CATEGORY_COUNT',$categorycount['catcnt']);

//Product Count Code
$product_query = tep_db_query("select count(products_id) as productcnt from " . TABLE_PRODUCTS);
$productcount = tep_db_fetch_array($product_query);
define('PRODUCT_COUNT',$productcount['productcnt']);

//Product Out of Stock Count Code
$product_query = tep_db_query("select count(products_id) as productcnt from " . TABLE_PRODUCTS." where products_quantity <= 0 ");
$productcount = tep_db_fetch_array($product_query);
define('PRODUCT_OUT_OF_STOCK_COUNT',$productcount['productcnt']);


//ActiveProduct Count Code
$product_query = tep_db_query("select count(products_id) as productcnt from " . TABLE_PRODUCTS." where products_status=1");
$productcount = tep_db_fetch_array($product_query);
define('ACTIVE_PRODUCT_COUNT',$productcount['productcnt']);

//Review Count Code
$review_query = tep_db_query("select count(reviews_id) as reviewcnt from " . TABLE_REVIEWS);
$reviewcount = tep_db_fetch_array($review_query);
define('REVIEW_COUNT',$reviewcount['reviewcnt']);

//Customer Count Code
$customer_query = tep_db_query("select count(customers_id) as customercnt from " . TABLE_CUSTOMERS);
$customercount = tep_db_fetch_array($customer_query);
define('CUSTOMER_COUNT',$customercount['customercnt']);

//Customer Subscribed Count Code
$customer_query = tep_db_query("select count(customers_id) as customercnt from " . TABLE_CUSTOMERS." where customers_newsletter=1");
$customercount = tep_db_fetch_array($customer_query);
define('CUSTOMER_SUBSCRIBED_COUNT',$customercount['customercnt']);

//LINK_CATEGORIE Count Code
$link_categories_query = tep_db_query("select count(link_categories_id) as link_categoriescnt from " . TABLE_LINK_CATEGORIES);
$link_categoriescount = tep_db_fetch_array($link_categories_query);
define('LINK_CATEGORIES_COUNT',$link_categoriescount['link_categoriescnt']);

//LINKS Count Code
$link_query = tep_db_query("select count(links_id) as linkcnt from " . TABLE_LINKS);
$linkcount = tep_db_fetch_array($link_query);
define('LINKS_COUNT',$linkcount['linkcnt']);

//LINKS Count Code
$linkapproved_query = tep_db_query("select count(links_id) as linkapprovedcnt from " . TABLE_LINKS." where links_status=1");
$linkapprovedcount = tep_db_fetch_array($linkapproved_query);
define('LINKS_APPROVAL_COUNT',$linkapprovedcount['linkapprovedcnt']);
 
//Language Count Code
$langcount_query = tep_db_query("select count(languages_id ) as langcnt from " . TABLE_LANGUAGES);
$langcount = tep_db_fetch_array($langcount_query);
define('LANGUAGE_COUNT',$langcount['langcnt']);

//Currencies Count Code
$currcount_query = tep_db_query("select count(currencies_id) as currcnt from " . TABLE_CURRENCIES);
$currcount = tep_db_fetch_array($currcount_query);
define('CURRENCIES_COUNT',$currcount['currcnt']);

//Tax Zone Code
$zones="";
$zone_query = tep_db_query("SELECT distinct geo_zone_name, tax_rate, b.geo_zone_id
                              from " . TABLE_ZONES_TO_GEO_ZONES . " a, 
                                                                 " . TABLE_GEO_ZONES . " b, 
                                                                      " . TABLE_TAX_RATES . " c 
                            WHERE a.geo_zone_id = b.geo_zone_id
                              and a.geo_zone_id = tax_zone_id");
                                                                                                    
$tax_contents="";
while ($zone_list = tep_db_fetch_array($zone_query)) {
  $tax_contents .= "<li>". $zone_list['geo_zone_name'] . ' (' . $zone_list['tax_rate'] . '%)' . "</li>";
  //Getting Further Zone Names
  $subzone_query=tep_db_query("SELECT countries_name, zone_name
  from ".TABLE_ZONES_TO_GEO_ZONES." a, ".TABLE_COUNTRIES." d, ".TABLE_ZONES." e
  WHERE d.countries_id = a.zone_country_id AND e.zone_id = a.zone_id AND geo_zone_id = ".$zone_list['geo_zone_id']."
  ORDER BY countries_name, zone_name");
  while ($subzone_list = tep_db_fetch_array($subzone_query)) 
  { 
    $tax_contents .= '<span class="smallText">&nbsp;-&nbsp;' . $subzone_list['countries_name'] . '&nbsp;:&nbsp;' . $subzone_list['zone_name'] . '</span><br>';
  }
}

//TEmplate Check code
  $template_query = tep_db_query("select configuration_id, configuration_title, configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_TEMPLATE'");
  $template = tep_db_fetch_array($template_query);
  $store_template = $template['configuration_value'] ;
  // Template Check Code EOF

  $sesid = $_GET['osCAdminID']; //get session id
  
  //check selected orders status to show in chart
  if(isset($_GET['action']) && $_GET['action']=="os_chart") { 	
	$_SESSION["os"] = $_POST['os'];	
	setcookie('os', json_encode($_POST['os']));
  }   
  
  if(empty($_SESSION["os"])) {	
	if(empty($_COOKIE["os"])) {
		setcookie('os', json_encode(array(1, 2, 4, 6, 100001, 100002, 100003, 100004, 100008, 100009)));
	}	
	$_SESSION["os"] = json_decode(stripslashes($_COOKIE["os"]),true);	
  }  
  
  $os_chart = $_SESSION["os"];
  
  //Orders status query
  $orders_contents = '';
  $orders_status_query = tep_db_query("select orders_status_name, orders_status_id from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "' order by sort_order");
    
  $colors_array = array("#3366CC","#3399FF","#FF9E01","#FCD202","#FF0000","#B0DE09","#04D215","#0D8ECF","#CC6600","#009933","#9966CC","#CD0D74","#336633","#003300","#999999",
  						"#99CC00","#339999","#FFCC33","#33CC33","#CCC00");
						
  $orders_status_graph = "";
  $s=0;
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    
	$status_id = $orders_status['orders_status_id'];
	$status_name = $orders_status['orders_status_name'];	 
	$orders_status_array[$status_id] = $status_name;
	
	//get current orders status count
	$orders_pending_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS . " where orders_status = '" . $status_id . "'");
    $orders_pending = tep_db_fetch_array($orders_pending_query);
    
	$orders_total_query = tep_db_query("SELECT sum(ot.value) as ordtot FROM orders as o inner join orders_total as ot ON (o.orders_id=ot.orders_id ) WHERE o.orders_status = '".$status_id."' and (ot.class = 'ot_grand_subtotal' or ot.class='ot_subtotal' or ot.class='ot_shipping')");
    $orders_total = tep_db_fetch_array($orders_total_query);
	
	if (tep_admin_check_boxes(FILENAME_ORDERS, 'sub_boxes') == true) {
      $orders_contents .= '<li><a class="adminLink" href="' . tep_href_link(FILENAME_ORDERS, 'selected_box=customers&status=' . $status_id) . '">' . $status_name . '</a> : ' . $orders_pending['count'] . "\n" . '<br>' ;
    } else {
      $orders_contents .= '' . $status_name . ': ' . $orders_pending['count'] . '<br>';
    }
   	
	//add selected or default orders status to chart
		foreach($os_chart as $key=>$value) {			
			if($value==$status_id) {
				$orders_status_graph .= '{linkto:"'.HTTPS_SERVER . DIR_WS_HTTP_ADMIN . FILENAME_ORDERS .'?selected_box=customers&status=' . $status_id .'&osCAdminID='.$sesid.'", status:"'.$status_name.'", counts:'.$orders_pending['count'].', ordtot:'.tep_round($orders_total['ordtot'],2).', color:"'.$colors_array[$s].'"},';
				$s++;
								
			}
				
		}	
	
  }
// Order Query EOF

//Customers Query
$customer_query = tep_db_query("SELECT count(*) as all_acc from customers");
$cust_arr = tep_db_fetch_array($customer_query);

	
//get active and inactive customers
//All active customers
$active_cust = tep_get_active_customers();
$inactive_cust = tep_get_inactive_customers();

//customers outside australia
$active_outside_aus = tep_get_active_customers_outside_aus();
$inactive_outside_aus = tep_get_inactive_customers_outside_aus();
$outside_total = $active_outside_aus + $inactive_outside_aus;

$western_act = tep_get_active_customers_by_state("187");
$western_inact = tep_get_inactive_customers_by_state("187");
$western_total = $western_act + $western_inact;

$north_act = tep_get_active_customers_by_state("189");
$north_inact = tep_get_inactive_customers_by_state("189");
$north_total = $north_act + $north_inact;

$south_act = tep_get_active_customers_by_state("186");
$south_inact = tep_get_inactive_customers_by_state("186");
$south_total = $south_act + $south_inact;

$queen_act = tep_get_active_customers_by_state("182");
$queen_inact = tep_get_inactive_customers_by_state("182");
$queen_total = $queen_act + $queen_inact;

$wales_act = tep_get_active_customers_by_state("184");
$wales_inact = tep_get_inactive_customers_by_state("184");
$wales_total = $wales_act + $wales_inact;

$vic_act = tep_get_active_customers_by_state("185");
$vic_inact = tep_get_inactive_customers_by_state("185");
$vic_total = $vic_act + $vic_inact;

$tas_act = tep_get_active_customers_by_state("190");
$tas_inact = tep_get_inactive_customers_by_state("190");
$tas_total = $tas_act + $tas_inact;

$act_act = tep_get_active_customers_by_state("188");
$act_inact = tep_get_inactive_customers_by_state("188");
$act_total = $act_act + $act_inact;


// RSS Read Functions
include('includes/functions/rss2html.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!-- Code related to index.php only -->
<link type="text/css" rel="StyleSheet" href="includes/index.css" />
<link type="text/css" rel="StyleSheet" href="includes/helptip.css" />
<script type="text/javascript" src="includes/javascript/helptip.js"></script>

<!--
<script src="includes/javascript/amcharts/amcharts.js" type="text/javascript"></script>
<script src="includes/javascript/amcharts/raphael.js" type="text/javascript"></script>        

<script type="text/javascript">
        
        var chart;
        
        var chartData = [<?php echo $orders_status_graph; ?>];
		
		var chartData1 = [
		   {linkto:"<?php echo HTTP_SERVER . DIR_WS_HTTP_ADMIN . "customers.php?artwork=pending&osCAdminID=".$sesid; ?>",country:"Pending",litres:<?php echo $artwork_pending["count"]; ?>,color:"#FB0000"},
           {linkto:"<?php echo HTTP_SERVER . DIR_WS_HTTP_ADMIN . "customers.php?artwork=revision&osCAdminID=".$sesid; ?>",country:"Revision",litres:<?php echo $artwork_revision["count"]; ?>,color:"#D769D7"},
           {linkto:"<?php echo HTTP_SERVER . DIR_WS_HTTP_ADMIN . "customers.php?artwork=approved&osCAdminID=".$sesid; ?>",country:"Approved",litres:<?php echo $artwork_approved["count"]; ?>,color:"#99CC00"}];
        
		var chartData2 = [
            {country:"Northern Territory",litres:<?php echo $north_total; ?>,color:"#FFB760"},			
			{country:"ACT",litres:<?php echo $act_total; ?>,color:"#FF0000"},	
			{country:"Western Australia",litres:<?php echo $western_total; ?>,color:"#FFEDB3"},		
			{country:"Victoria",litres:<?php echo $vic_total; ?>,color:"#9CD988"},
			{country:"New South Wales",litres:<?php echo $wales_total; ?>,color:"#4C8BFF"},					
			{country:"Queensland",litres:<?php echo $queen_total; ?>,color:"#FF7900"},
			{country:"Tasmania",litres:<?php echo $tas_total; ?>,color:"#8DCCF7"},	
            {country:"South Australia",litres:<?php echo $south_total; ?>,color:"#B36912"},
			{country:"Other Countries",litres:<?php echo $outside_total; ?>,color:"#33AD5B"}
			];
        
        window.onload = function() 
        {
            // Pie chart
			var chart1 = new AmCharts.AmPieChart();
			chart1.dataProvider = chartData1;
			chart1.urlField = "linkto";
			chart1.titleField = "country";
			chart1.valueField = "litres";
			chart1.colorField = "color";
			chart1.depth3D = 10;
            chart1.angle = 10;
			chart1.labelRadius = -30;
            chart1.labelText = "[[percents]]%";			
			legend = new AmCharts.AmLegend();
			legend.align = "center";
			legend.markerType = "circle";
			chart1.addLegend(legend);		
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
				
			chart = new AmCharts.AmSerialChart();
            chart.dataProvider = chartData;
            chart.categoryField = "country";
            chart.marginTop = 25;
            chart.marginBottom = 150;
            chart.marginLeft = 45;
            chart.marginRight = 15;
            chart.startDuration = 1;
			//chart.depth3D = 10;
            //chart.angle = 10;
            
            var graph = new AmCharts.AmGraph();
			graph.urlField = "linkto";	
            graph.valueField = "visits";
            graph.colorField = "color";
            graph.balloonText="[[category]]: [[value]]";
            graph.type = "column";
            graph.lineAlpha = 0;
            graph.fillAlphas = 0.8;
            chart.addGraph(graph);
            
            var catAxis = chart.categoryAxis;
            catAxis.labelRotation = 90;
            catAxis.gridPosition = "start";
            catAxis.autoGridCount = true;
            
            chart.write("chartdiv");
						
        }
        
        </script>
	-->	
	<script src="includes/javascript/amcharts_new/amcharts.js" type="text/javascript"></script>
        <script src="includes/javascript/amcharts_new/serial.js" type="text/javascript"></script>
        <script>
            var chart;

            var chartData = [<?php echo $orders_status_graph; ?>];

            AmCharts.ready(function () {
                // SERIAL CHART
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartData;
                chart.categoryField = "status";
                // this single line makes the chart a bar chart,
                // try to set it to false - your bars will turn to columns
                chart.rotate = true;
                // the following two lines makes chart 3D
                chart.depth3D = 20;
                chart.angle = 30;

                // AXES
                // Category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.gridPosition = "start";
                categoryAxis.axisColor = "#DADADA";
                categoryAxis.fillAlpha = 1;
                categoryAxis.gridAlpha = 0;
                categoryAxis.fillColor = "#FAFAFA";

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.axisColor = "#DADADA";
				valueAxis.position = "top";
                valueAxis.title = "Orders Total (AUD)";
                valueAxis.gridAlpha = 0.1;
                chart.addValueAxis(valueAxis);

                // GRAPH
                var graph = new AmCharts.AmGraph();
                graph.title = "Orders by Status";
                graph.valueField = "ordtot";
                graph.type = "column";
                graph.balloonText = "[[category]]:[[value]]";
                graph.lineAlpha = 0;
                graph.fillColors = "#bf1c25";
                graph.fillAlphas = 1;
				graph.labelPosition = "inside";
                graph.labelText = "[[category]]: [[value]]";
                chart.addGraph(graph);
				
				// line graph
                var graph2 = new AmCharts.AmGraph();
                graph2.type = "line";
                graph2.lineColor = "#27c5ff";
                graph2.bulletColor = "#FFFFFF";
                graph2.bulletBorderColor = "#27c5ff";
                graph2.bulletBorderThickness = 2;
                graph2.bulletBorderAlpha = 1;
                graph2.title = "Number of Orders";
                graph2.valueField = "counts";
                graph2.lineThickness = 2;
                graph2.bullet = "round";
                graph2.fillAlphas = 0;
                graph2.balloonText = "<span style='font-size:13px;'>[[title]] in [[category]]:<b>[[value]]</b></span>";
                chart.addGraph(graph2);

                chart.creditsPosition = "top-right";

                // WRITE
                chart.write("chartdiv");
            });
        </script>

<!-- code related to index.php EOF -->

<style type="text/css">
	.active-cust { background:url(images/active.png) left no-repeat; height:18px; padding-left:18px; font-weight:bold; }
	.inactive-cust { clear:both; background:url(images/inactive.png) left no-repeat; height:18px; padding-left:18px; font-weight:bold; }
	#western, #northern, #south, #south-wales, #queensland, #victoria, #tasmania, #act, #other  { position:absolute;  clear:both; width:80px; }	
	#western { top:160px; left:40px;}
	#northern { top:80px; left:145px; }
	#south { top:165px; left:145px;  }
	#south-wales { top:210px; left:325px; }
	#queensland { top:120px; left:240px; }
	#victoria { top:255px; left:260px; }
	#tasmania { top:335px; left:310px; }
	#act { top:300px; left:360px; }
	#other { top:300px; left:40px; }
</style>
			
<!-- code related to index.php EOF -->
</head>
<body>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<table width="100%"  border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td valign="top" width="150"><table border="0" width="150" cellspacing="1" cellpadding="1" class="columnLeft" align="center">
        <!-- left_navigation //-->
        <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
        <!-- left_navigation_eof //-->
      </table></td>
    <td valign="top"><table width="100%"  border="0" cellpadding="3" cellspacing="3" summary="Admin Links Welcome Table">
        <tr>
          <td colspan="2" class="admin_text"><?php //echo sprintf(TEXT_WELCOME,$store_admin_name); ?></td>
        </tr>
      </table>
      <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
      <!--BLOCK CODE START-->
      <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="Table holding Store Information">
	  
	  	<!-- code for chart start -->
		<tr valign="top">
          <td colspan=3>
		  	
			<table align="center" width="100%" border="0">
				<tr>
					<td width="20%" valign="top">
						<fieldset>
							<legend><?php echo BLOCK_TITLE_ORDERS;?> </legend>
							Select orders status to show in chart<br>
						<?php echo tep_draw_form('frmOSChart', FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=os_chart', 'post', '', 'SSL'); ?>
							<table align="center" width="100%" border="0">
								
								<?php 								
									$i=1;
									foreach ($orders_status_array as $status_key=>$value) {
																				
										if (in_array($status_key, $os_chart)) {
											$checked_status = " checked";
										}						
										else { $checked_status = ""; }							
										
										echo '<tr><td><input type="checkbox" class="checkbox" name="os[]" value="'.$status_key.'" '.$checked_status.'></td><td>'.$value."</td></tr>";							
										$i++;
										//if(($i%5)==0) echo "</tr>";
									 }
								 ?>
								 
							</table>
							<p align="center"><?php echo tep_image_submit('button_submit.gif', "Submit");  ?></p>
						</form>					
						</fieldset>	
					</td>
					<td width="78%" valign="top">
						<div id="chartdiv" style="width: 100%; height: 700px;"></div>
					</td>
					
				</tr>				
			  </table>
		  </td>
        </tr>
		
		<tr valign="top">
          <td colspan=2><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?> </td>
        </tr>
		
		<tr valign="top">
          
		  <td width="25%">
		  	<table align="center" width="100%" border="0">
				<tr>
					<td>
						<fieldset>
						<legend> <?php echo BLOCK_TITLE_STORE_INFO;?> (<a href="<?php echo tep_href_link(FILENAME_CONFIGURATION,'gID=1','NONSSL');?>"><?php echo TEXT_MANAGE;?></a>) <a class="helpLink" href="?" onMouseover="showhint('<?php echo BLOCK_HELP_STORE_INFO;?>', this, event, '150px'); return false">[?]</a></legend>
							<ul>
							  <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_NAME . ' : ' . STORE_NAME;?> </li>
							  <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_STATUS;?> : <a class="helpLink" href="?" onMouseover="showhint('<?php echo BLOCK_HELP_STORE_STATUS;?>', this, event, '250px'); return false"><strong><?php echo $store_status;?></strong></a> </li>
							  <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_EMAIL . ' : ' . STORE_OWNER_EMAIL_ADDRESS;?> </li>
							  <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_TEMPLATE . ' : ' . $store_template;?></li>
							  <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_LANGUAGE . ' : ' . DEFAULT_LANGUAGE.' ('.LANGUAGE_COUNT;?> Installed) </li>
							  <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_CURRENCY . ' : ' . DEFAULT_CURRENCY.' ('.CURRENCIES_COUNT;?> Installed) </li>
							  <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_BACKUPS.' : '.BACKUP_COUNT;?> (Latest <?php echo LAST_BACKUP_DATE?>) <a href="<?php echo tep_href_link(FILENAME_BACKUP);?>" onMouseover="showhint('<?php echo BLOCK_HELP_STORE_BACKUP;?>', this, event, '100px'); return false"><font color="#FF0000">[!]</font></a></li>
							</ul>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td>
						<fieldset>
						<legend> <?php echo BLOCK_TITLE_PRODUCTS;?> (<a href="<?php echo tep_href_link(FILENAME_CATEGORIES,'selected_box=catalog','NONSSL');?>"><?php echo TEXT_MANAGE;?></a><a href="#"></a>) <a class="helpLink" href="?" onMouseover="showhint('<?php echo BLOCK_HELP_PRODUCTS;?>', this, event, '250px'); return false">[?]</strong></a></legend>
							<ul>
							  <li><?php echo BLOCK_CONTENT_PRODUCTS_CATEGORIES.' : '.CATEGORY_COUNT;?></li>
							  <li><?php echo BLOCK_CONTENT_PRODUCTS_TOTAL_PRODUCTS.' : '.PRODUCT_COUNT;?></li>
							  <li><?php echo BLOCK_CONTENT_PRODUCTS_ACTIVE.' : '.ACTIVE_PRODUCT_COUNT;?></li>
							  <li><?php echo BLOCK_CONTENT_PRODUCTS_NOSTOCK.' : '.PRODUCT_OUT_OF_STOCK_COUNT;?></li>
							</ul>
						</fieldset>
					</td>
				</tr>
			</table>		
			
		  </td>
		  
		  <td colspan="2">
		  	
			<fieldset>
            <legend><?php echo BLOCK_TITLE_CUSTOMERS;?> (<a href="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT,'selected_box=customers','NONSSL');?>"><?php echo TEXT_ADD;?></a> / <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'selected_box=customers','NONSSL');?>"><?php echo TEXT_VIEW;?></a>)</legend>
			
		  	<table align="center" width="100%" border="0">
				<tr>
					<td width="50%" valign="top">&nbsp;				
					  <div id="map-chart" style="background:#FFF url(images/australia.png) 0 0 no-repeat; position:relative; width:377px; height:354px;">
						<div id="western">
							<div class="active-cust">
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=active&zone=187'); ?>">
								<?php echo $western_act; ?>
								</a>
							</div>
							<div class="inactive-cust">
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=inactive&zone=187'); ?>">
								<?php echo $western_inact; ?>
								</a>
							</div>
								
						</div>
						<div id="northern">							
							
							<div class="active-cust">
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=active&zone=189'); ?>">
								<?php echo $north_act; ?>
								</a>
							</div>
							<div class="inactive-cust">
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=inactive&zone=189'); ?>">
								<?php echo $north_inact; ?>
								</a>
							</div>
							
						</div>
						<div id="south">
														
							<div class="active-cust">
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=active&zone=186'); ?>">
								<?php echo $south_act; ?>
								</a>
							</div>
							<div class="inactive-cust">
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=inactive&zone=186'); ?>">
								<?php echo $south_inact; ?>
								</a>
							</div>
							
						</div>
						<div id="south-wales">
							
							<div class="active-cust">
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=active&zone=184'); ?>">
								<?php echo $wales_act; ?>
								</a>
							</div>
							<div class="inactive-cust">
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=inactive&zone=184'); ?>">
								<?php echo $wales_inact; ?>
								</a>
							</div>
							
						</div>
						<div id="queensland">
							
							<div class="active-cust">
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=active&zone=182'); ?>">
								<?php echo $queen_act; ?>
								</a>
							</div>
							<div class="inactive-cust">
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=inactive&zone=182'); ?>">
								<?php echo $queen_inact; ?>
								</a>
							</div>
							
						</div>
						<div id="victoria">
							
							<div class="active-cust">
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=active&zone=185'); ?>">
								<?php echo $vic_act; ?>
								</a>
							</div>
							<div class="inactive-cust">
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=inactive&zone=185'); ?>">
								<?php echo $vic_inact; ?>
								</a>
							</div>
							
						</div>
						<div id="tasmania">
							
							<div class="active-cust">
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=active&zone=190'); ?>">
								<?php echo $tas_act; ?>
								</a>
							</div>
							<div class="inactive-cust">
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=inactive&zone=190'); ?>">
								<?php echo $tas_inact; ?>
								</a>
							</div>
							
						</div>
						<div id="act">
							
							<div class="active-cust">
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=active&zone=188'); ?>">
								<?php echo $act_act; ?>
								</a>
							</div>
							<div class="inactive-cust">
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=inactive&zone=188'); ?>">
								<?php echo $act_inact; ?>
								</a>
							</div>
							
						</div>
						
						<div id="other">
													
							<div class="active-cust" style="width:250px;">Other Countries: 
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=active&zone=0'); ?>">
								<?php echo $active_outside_aus; ?>
								</a>
							</div>
							<div class="inactive-cust" style="width:250px;">Other Countries: 
								<a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=inactive&zone=0'); ?>">
								<?php echo $inactive_outside_aus; ?>
								</a>
							</div>
							
						</div>
						
					  </div>	
					</td>
					<td width="50%" valign="top"><br>
						<h2>Total Accounts: <a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers'); ?>"><?php echo $cust_arr['all_acc']; ?></a></h2>
						<h3 style="background:url(images/active.png) left no-repeat; padding-left:18px;">
							Active Accounts: <a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=active'); ?>"><?php echo $active_cust; ?></a>	
						</h3>
						<h3 style="background:url(images/inactive.png) left no-repeat; padding-left:18px;">
							Inactive Accounts: <a class="adminLink" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&status=inactive'); ?>"><?php echo $inactive_cust; ?></a>
						</h3>
						<br>
						<h3 style="background:url(images/active.png) left no-repeat; padding-left:18px;">New Active Accounts this week: <?php echo tep_get_active_customers_this_week(); ?></h3>
						<h3 style="background:url(images/inactive.png) left no-repeat; padding-left:18px;">New Inactive Accounts this week: <?php echo tep_get_inactive_customers_this_week(); ?></h3>
						<!--<br>
						<h3 style="background:url(images/active.png) left no-repeat; padding-left:18px;">Average New Active Accounts per week: </h3>
						<h3 style="background:url(images/inactive.png) left no-repeat; padding-left:18px;">Average New Inactive Accounts per week: </h3>-->
						<div id="chartdiv2" style="width: 100%; height: 400px;"></div>
					</td>
				</tr>
			  </table>
			  </fieldset>
		  </td>
        </tr>
		
        <tr valign="top">
          <td colspan=2><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?> </td>
        </tr>
		<!-- code for chart End -->
		
      </table>
      <!--BLOCK CODE ENDS -->
      <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
      <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="Footer Banner Table">
        <tr>
          <td align="center"><!-- former position of bottom banner code -->
            <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
          </td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>