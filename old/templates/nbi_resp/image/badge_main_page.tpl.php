<?php require_once('bd/badge_designer.php'); ?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
<?php
if ( file_exists(DIR_WS_INCLUDES . FILENAME_HEADER_TAGS) ) {
  require(DIR_WS_INCLUDES . FILENAME_HEADER_TAGS);
} else {
?>
  <title><?php echo TITLE ?></title>
<?php
}
?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<!-- CSS Part Start-->
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/slideshow.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/flexslider.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/colorbox/colorbox.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/carousel.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/custom.css" />
<link rel="canonical" href="http://domedstickersinternational.com.au/" />

<!-- CSS Part End-->
<?php
if (isset($javascript) && file_exists(DIR_WS_JAVASCRIPT . basename($javascript))) { require(DIR_WS_JAVASCRIPT . basename($javascript)); } 
?>

<!-- Latest jQuery and Migrate Plugin -->
<script type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js-----old/jquery-latest.js"></script>
<script type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js-----old/jquery-migrate-1.1.1.js"></script>
<!-- Latest jQuery and Migrate Plugin -->

<!-- badge designer -->

<script type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/tipsy-0.1.7/src/javascripts/jquery.tipsy.js"></script>
<link rel="stylesheet" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/tipsy-0.1.7/src/stylesheets/tipsy.css" type="text/css" />

<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/jquery.event.drag-1.5.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/jquery.event.drop-1.1.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/jquery.ajax_upload.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/jquery.timers.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/ajax_upload.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/json.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/badge_designer.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js-----old/slide.js"></script>



<!-- Nov 12, 2010 -->
<script type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/thickbox.js"></script>
<link rel="stylesheet" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/thickbox.css" type="text/css" media="screen" />
<!-- Nov 12, 2010 -->
<link rel="stylesheet" type="text/css" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/badge_designer.css" />
<link rel="stylesheet" type="text/css" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/ajax_upload.css" />
<!-- end of badge designer -->

<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js-----old/jquery.metadata.js"></script>

<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js-----old/jquery.validate.js"></script>


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


<!-- For Question and Live chat -->
<script type="text/javascript">
jQuery(document).ready(function(){
	
	$(".question").hover(function() { // Mouse over	  
	  $(this).animate({width: '70px'});	
	}, function() { // Mouse out	  
	  $(this).animate({width: '60px'});	  
	});
	
	//For Live chat animation
	$(".live-chat").hover(function() { // Mouse over	  
	  $(this).animate({height: '60px'});	
	}, function() { // Mouse out	  
	  $(this).animate({height: '50px'});	  
	});
	
});
</script>
<style type="text/css">
	.live-chat { z-index:998; bottom:0px; left:70%; position:fixed; }
	.question { z-index:998; top:20%; left:0px; position:fixed; }		
</style>
<!-- For Question and Live chat -->

<!-- stardevelop.com Live Help International Copyright - All Rights Reserved //-->
<!-- BEGIN stardevelop.com Live Help Messenger Code - Copyright - NOT PERMITTED TO MODIFY COPYRIGHT LINE / LINK //-->

<script type="text/javascript">
<!--
    var LiveHelpSettings = {};
    LiveHelpSettings.server = 'namebadgesinternational.com.au';
    LiveHelpSettings.embedded = true;
    (function(d, $, undefined) {
        $(window).ready(function() {
            // JavaScript
            var LiveHelp = d.createElement('script'); LiveHelp.type = 'text/javascript'; LiveHelp.async = true;
            LiveHelp.src = ('https:' == d.location.protocol ? 'https://' : 'http://') + LiveHelpSettings.server + '/livehelp/scripts/jquery.livehelp.js';
            var s = d.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(LiveHelp, s);
        });
    })(document, jQuery);
-->
</script>
<!-- END stardevelop.com Live Help Messenger Code - Copyright - NOT PERMITTED TO MODIFY COPYRIGHT LINE / LINK //-->

</head>
<?php

	// RCO start
	if ($cre_RCO->get('mainpage', 'body') !== true) {              
	 echo '<body>' . "\n";
	}
	// RCO end

	if (!tep_session_is_registered('customer_id')) {
		$h_links = '<a href="create_account.php">Register</a> <a href="login.php">Login</a> ';
		$m_links = '<a href="create_account.php" id="user">&nbsp;</a> <a href="login.php" id="contact">&nbsp;</a>';
	} else {
		$h_links = '<a href="' . tep_href_link(FILENAME_ACCOUNT, "", "SSL") . '">Account</a> <a href="logoff.php">Logoff</a> ';
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
					  <div class="column"> <a href="http://domedstickersinternational.com.au/">Home</a></div>
					  <div class="column"> <a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=24&CDpath=0">Name Badges</a></div>
					  <div class="column"> <a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=22&amp;CDpath=0">About Us</a></div>
					  <div class="column"> <a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=27&amp;CDpath=0">Price List</a></div>
					  <div class="column"> <a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=77&amp;CDpath=0">Gallery</a></div>
					  <div class="column"> <a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=51&amp;CDpath=0">Contact Us</a></div>
					</div>
				  </li>
				</ul>
			  </nav>
			  <!-- Mobile Menu End-->				  
			  <div id="logo"><a href="<?php echo HTTP_SERVER; ?>"><img src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/image/logo-small.png" width="190" height="45" /></a></div>				  
			  <div class="m-links"><?php echo $m_links; ?></div>
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
					<div class="links">
						<?php echo $h_links; ?> 
						<a href="shopping_cart.php">View cart</a> 
					</div>
				  </div>
				  
				  <!--Top Menu(Horizontal Categories) Start-->
				  <nav id="menu">
					<ul>
					  <li><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=24&amp;CDpath=0">Name Badges</a></li>
					  <li><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=27&amp;CDpath=0">Price List</a></li>
					  <li><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=77&amp;CDpath=0">Gallery</a></li>
					  <li><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=22&amp;CDpath=0">About Us</a></li>
					  <li><a href="<?php echo HTTP_SERVER; ?>/articles.php?tPath=21&amp;CDpath=0">Artwork</a></li>
					  <li><a href="<?php echo HTTP_SERVER; ?>/articles.php?tPath=20&amp;CDpath=0">FAQs</a></li>
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
					  <li><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=51&amp;CDpath=0">Contact Us</a></li>
					</ul>
				  </nav>
				  <!--Top Menu(Horizontal Categories) End-->
				</header>
				<!--Header Part End-->
				
				<div class="inner-content">

				<?php require(DIR_WS_INCLUDES . FILENAME_WARNINGS); 
			
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
					
					
					if (get_shapes_list(@$_GET['cPath'])) { ?>
				
					  <div id="design_form" style="display:block;">
					  
						<?php include('bd/index.php');?>
						
					  </div>
				  
					<?php } ?>
					
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
        <div class="clear"></div>
	  </div>
	  <!-- Content part end -->
    </div>
  </div>
</body>
  </html>