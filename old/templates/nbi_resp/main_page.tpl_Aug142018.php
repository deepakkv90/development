<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
	
	<?php
		require(DIR_WS_TEMPLATES . TEMPLATE_NAME. "/head.php"); 
	?>
	
	<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/stylesheet.css" />
	
	<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery-1.7.1.min.js"></script>
	

</head>

<?php
// RCO start

if ($cre_RCO->get('mainpage', 'body') !== true) {              

 echo '<body>' . "\n";

}

// RCO end
?>

<?php //echo $modules_folder . INCLUDE_MODULE_TWO; 

if (!tep_session_is_registered('customer_id')) {
	$h_links = '<a href="create_account.php">Register</a> <a href="login.php">Login</a> <a href="shopping_cart.php">View cart</a>';
	$m_links = '<a href="create_account.php" id="user">&nbsp;</a> <a href="login.php" id="contact">&nbsp;</a>';
} else {
	$h_links = '<a href="' . tep_href_link(FILENAME_ACCOUNT, "", "SSL") . '">Account</a> <a href="logoff.php">Logoff</a> <a href="shopping_cart.php">View cart</a>';
	$m_links = '<a href="' . tep_href_link(FILENAME_ACCOUNT, "", "SSL") . '" id="user">&nbsp;</a> <a href="logoff.php" id="contact">&nbsp;</a>';
}
?>


<div class="wrapper-box">
  
  <div class="main-wrapper">
    
    <div id="container">
	
		<div id="m-header">
			<!-- Mobile Menu Start-->
			  <nav id="menu" class="m-menu"> <span><a href="javascript:void(0)">&nbsp;</a></span>
				<ul>
				  <li class="categories"><a>Name Badges</a>
					<div>
                      <div class="column"> <a href="<?php echo HTTP_SERVER; ?>">Home</a></div>
					  <div class="column"> <a href="<?php echo HTTP_SERVER; ?>/Desk-Name-Plates/c242/">Desk Name Plates</a></div>
					  <div class="column"> <a href="<?php echo HTTP_SERVER; ?>/Door-Name-Plates/c248/">Door Name Plates</a></div>
					  <div class="column"> <a href="<?php echo HTTP_SERVER; ?>/Wedding-Name-Plates/c252/">Wedding Name Plates</a></div>
					  <div class="column"> <a href="<?php echo HTTP_SERVER; ?>/Table-Number-Plates/c265/">Table Number Plates</a></div>
					  <div class="column"> <a href="<?php echo HTTP_SERVER; ?>/Table-Name-Plates/c238/">Table Name Plates</a></div>
					  <div class="column"> <a href="<?php echo HTTP_SERVER; ?>/instant-quote-name-plates">Quote</a></div>
					  <div class="column"> <a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=27&amp;CDpath=0">Prices</a></div>
					  <div class="column"> <a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=22&amp;CDpath=0">About Us</a></div>
					  <!--<div class="column"> <a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=64&amp;CDpath=0">Free Samples</a></div>-->
					  <div class="column"> <a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=51&amp;CDpath=0">Contact</a></div>
					</div>
				  </li>
				</ul>
			  </nav>
			  <!-- Mobile Menu End-->				  
			  <div id="logo"><a href="<?php echo HTTP_SERVER; ?>">&nbsp;</a></div>				  
			  <div class="m-links">&nbsp;<?php echo $m_links; ?></div>
		</div>
		
	    <div id="column-left">
			<div class="box">
				<div id="logo"><a href="<?php echo HTTP_SERVER; ?>">&nbsp;</a></div>
			</div>
			<?php 
			if (DISPLAY_COLUMN_LEFT == 'yes')  {				
				if (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_COLUMN_LEFT_OFF =='false') {		
					require(DIR_WS_INCLUDES . FILENAME_COLUMN_LEFT);		
				}		
			}
			?>
	    </div>

		<div id="content">        
				<!--Header Part Start-->
				<header id="header">
				  <div class="htop">
				    <div class="header-message">Need help? Call us on 02 8003 5046</div>
					<div class="links">&nbsp;
						<?php echo $h_links; ?> 						 
					</div>
				  </div>
				  
				  <!--Top Menu(Horizontal Categories) Start-->
				  <nav id="menu">
					<ul>
					  <li><a href="<?php echo HTTP_SERVER; ?>">Home</a></li>
					  <li><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=27&amp;CDpath=0">Prices</a></li>
					  <li><a href="<?php echo HTTP_SERVER; ?>/instant-quote-name-plates">Quote</a></li>
					  <!--<li><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=64&amp;CDpath=0">Free Samples</a></li>-->
					  <li><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=78&amp;CDpath=0">Artwork</a></li>
					  <!--
					  <li><a>My Account</a>
						<div>
						  <ul>
							<li><a href="#">My Account</a></li>
							<li><a href="#">Order History</a></li>
							<li><a href="#" id="wishlist-total">Wish List (0)</a></li>
							<li><a href="#">Newsletter</a></li>
						  </ul>
						</div>
					  </li>-->
					  <li><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=22&amp;CDpath=0">About Us</a></li>
					  <li><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=51&amp;CDpath=0">Contact</a></li>
					</ul>
				  </nav>
				  <!--Top Menu(Horizontal Categories) End-->
				</header>
				<!--Header Part End-->
				
				 <?php if ((eregi('index[.]php$', $_SERVER['SCRIPT_NAME'])) && !@$_GET['cPath'] && !@$_GET['info_id']) { ?>
				  
				  <section class="hsecond">							
					<!-- Nivo Slider Start -->
					
					<section class="slider-wrapper">
					
					  <div id="slideshow" class="nivoSlider"> 
					    
							<img src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/image/slider/name-plates-signs.jpg" alt="" title="#name-plates-signs" />
							<img src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/image/slider/desk-name-plates-signs.jpg" alt="" title="#desk-name-plates-signs" />
							<a href=""><img src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/image/slider/door-name-plates-signs.jpg" alt="" title="#door-name-plates-signs" /></a>
							<img src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/image/slider/Wedding-name-plates-slider.jpg" alt="" title="#wedding-name-plates-signs" />
							<img src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/image/slider/Table-number-plates-signs-slider.jpg" alt="" title="#table-number-plates-signs" />
							<img src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/image/slider/Table-name-plates-signs-slider.jpg" alt="" title="#table-name-plates-signs" />
							<img src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/image/slider/delivery.jpg" alt="" title="#fast-delivery" />

					  </div>

					  <div id="name-plates-signs" class="nivo-html-caption">
							<h1>The professional choice in <br/>personalized name plates</h1>
							<ul><li>No set-up fee</li><li>No minimum Quantity</li><li>Free design services</li><li>Easy to Design Online</li></ul>
							<a class="button" href="/Name-Plates/c238/">Order your name plates now</a>
					  </div>
					  
					  <div id="desk-name-plates-signs" class="nivo-html-caption">
							<h1>Desk Name Plates</h1>
							<p>It creates the best impression of your office<br /> along with self presentation. All our name plates<br /> includes:</p>
							<ul><li>Reusable</li><li>Affordable</li><li>Personalised</li><li>Richness looks & feel</li></ul>
							<a class="button" href="/Desk-Name-Plates/c242/">Order your desk name plates now</a>
					  </div>
					  
					  <div id="door-name-plates-signs" class="nivo-html-caption">
							<h1>Door Name Plates</h1>
							<p>A door name plate identifies, displays your office<br /> and personal information. </p>
							<ul><li>Professional, Stylish and Simple!!!</li><li>An unique & consistent look throughout<br /> your office.</li></ul>
							<a class="button" href="/Door-Name-Plates/c248/">Order door name plates now</a>
					  </div>
					  <div id="wedding-name-plates-signs" class="nivo-html-caption">
							<h1>Wedding Name Plates</h1>
							<p>More attractive and Perfect for Wedding<br />  receptions and executive events!.
							<ul><li>Supplied with clear Table Stand.</li><li>Complimentary optically clear Lens Cover to<br />  protect your wedding name plate.</li></ul>
							<a class="button" href="/Wedding-Name-Plates/c252/">Buy Wedding plates online</a>
					  </div>
					  <div id="table-number-plates-signs" class="nivo-html-caption">
							<h1>Table Number Plates</h1>
							<ul><li>An attractive table number plate for your functions<br /> and any events.</li>
							<li>A more recognizable feature to add to your table<br />decoration available in black or white.</li></ul>
							<a class="button" href="/Table-Number-Plates/c265/">Order table number plates online</a>
					  </div>
					  <div id="table-name-plates-signs" class="nivo-html-caption">
							<h1>Table Name Plates</h1>
							<ul><li>The small or large standing sign for your reception,<br />hotel lobby or retail counter.</li>
							<li>Acrylic Large & Short Table Name Plates for<br /> your Restaurant, Bar or Cafe.</li></ul>
							<a class="button" href="/Table-Name-Plates/c238/">Design Table name plates online</a>
					  </div>
				      <div id="fast-delivery" class="nivo-html-caption">
							<h1>Delivery Options</h1>
							<p>All orders dispatched within 3-5 working days,<br />need it sooner? Choose our Next day / Same day<br /> dispatch services.</p>
							<ul><li>Same Day Dispatch (Order before 11am weekdays)</li><li>Next Day Dispatch</li><li>Australia Post/Express</li></ul>
							<a class="button" href="/p27/Price-List-per-name-badges/">View our price list</a>
					  </div>
					  
					  
					</section>
					
					<script type="text/javascript"><!--
					$(document).ready(function() {
						$('#slideshow').nivoSlider();
					});
					--></script>
					
				<!-- Boxes - Home -->
				  <div class="box-home-container">
					  <div class="box-home"><a class="quote" href="/instant-quote-name-plates"><span>Get A<br /> Free Quote</span></a></div>
					  <div class="box-home"><a class="delivery" href="/Delivery/t24/"><span>Same Day<br /> Dispatch Services</span></a></div>
					  <div class="box-home"><a class="sample" href="/Name-Plates/c238/"><span>Minimum<br />Order just<br />1 plate!</span></a></div>
				  </div>	
				  <!--<h4><center>We wish you all a Merry Christmas and a Happy New Year!!! Our production will be closed for Christmas from December 21, 2016. We will re-open on Wednesday, January 11, 2017.</center></h4>-->
				  <!-- Nivo Slider End-->
				  </section>
				  
				   <?php } ?>
				
				<div class="inner-content">

				<?php 
					require(DIR_WS_INCLUDES . FILENAME_WARNINGS); 
				
					if (isset($content_template) && file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/'.  basename($content_template))) {		
						
						require(DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/' . $content . '.tpl.php');
				
					} else if (file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/' . $content . '.tpl.php')) {	  	
						
						//echo DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/'. $content . '.tpl.php';
						
						require(DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/'. $content . '.tpl.php');
				
					}else if (isset($content_template) && file_exists(DIR_WS_CONTENT . basename($content_template)) ){	  	
				
						require(DIR_WS_CONTENT . basename($content_template));
				
					} else {	  	
				
						require(DIR_WS_CONTENT . $content . '.tpl.php');
				
					}
						
				?>
				
				</div>
				
				<!-- Footer Start -->
				<?php 

					if (DOWN_FOR_MAINTENANCE_FOOTER_OFF =='false') {

						require(DIR_WS_INCLUDES . FILENAME_COUNTER); 
						require(DIR_WS_TEMPLATES . TEMPLATE_NAME. "/footer.php"); 
					
					} 
				?>	
				<!-- Footer End -->
			
		 </div>
		 <!--right body content end -->
         <div class="clear"></div>
		 
	  </div>
	  <!-- Container part end -->
    </div>
  </div>

  
  
  <!-- CSS Part Start-->
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/slideshow.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/flexslider.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/colorbox/colorbox.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/carousel.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/custom.css" />


<!-- CSS Part End-->
<!-- JS Part Start-->

<?php
if (isset($javascript) && file_exists(DIR_WS_JAVASCRIPT . basename($javascript))) { require(DIR_WS_JAVASCRIPT . basename($javascript)); } 
?>


<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery.nivo.slider.pack.js"></script>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery.flexslider.js"></script>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery.easing-1.3.min.js"></script>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/tabs.js"></script>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/cloud_zoom.js"></script>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery.dcjqaccordion.js"></script>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/custom.js"></script>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/html5.js"></script>
<!-- JS Part End-->

<!-- Google Fonts (Droid Sans) Start -->
<link href='http://fonts.googleapis.com/css?family=Droid+Sans&amp;v1' rel='stylesheet' type='text/css'>
<!-- Google Fonts (Droid Sans) End -->



<!-- stardevelop.com Live Help International Copyright - All Rights Reserved //-->
<!--  BEGIN stardevelop.com Live Help Messenger Code - Copyright - NOT PERMITTED TO MODIFY COPYRIGHT LINE / LINK //-->
<script type="text/javascript">
<!--
	var LiveHelpSettings = {};
	LiveHelpSettings.server = 'namebadgesinternational.com.au';
	LiveHelpSettings.embedded = true;

	(function(d, $, undefined) { 
		$(window).ready(function() {
			var LiveHelp = d.createElement('script'); LiveHelp.type = 'text/javascript'; LiveHelp.async = true;
			LiveHelp.src = ('https:' == d.location.protocol ? 'https://' : 'http://') + LiveHelpSettings.server + '/livehelp/scripts/jquery.livehelp.js';
			var s = d.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(LiveHelp, s);
		});
	})(document, jQuery);
-->
</script>
<!--  END stardevelop.com Live Help Messenger Code - Copyright - NOT PERMITTED TO MODIFY COPYRIGHT LINE / LINK //-->
  
  
</body>
  </html>