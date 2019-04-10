<?php 
$cPro = '';
$cProduct = $_GET['cProduct'];
if (isset($cProduct) && tep_not_null($cProduct)) {
	$cPro = $cProduct;
}

require_once('bd/badge_designer.php'); 
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>

	<?php
		require(DIR_WS_TEMPLATES . TEMPLATE_NAME. "/head.php"); 
	?>
	
<!-- Typekit -->
    <script src="https://use.typekit.net/vxz3nqn.js"></script>
    <script>try{Typekit.load({ async: true });}catch(e){}</script>
  <!-- End Typekit -->
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/slick.css" />
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/mytheme.css" />
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/jqvmap.css" />	
	<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery-1.7.1.min.js"></script>
		
	<!-- Latest jQuery and Migrate Plugin -->
	<script type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js-----old/jquery-latest.js"></script>
	<script type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js-----old/jquery-migrate-1.1.1.js"></script>
	<!-- Latest jQuery and Migrate Plugin -->
	
	<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/badge_designer.js"></script>
	
	<?php
	if (isset($javascript) && file_exists(DIR_WS_JAVASCRIPT . basename($javascript))) { require(DIR_WS_JAVASCRIPT . basename($javascript)); } 
	?>

	<!-- badge designer -->

	<script type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/tipsy-0.1.7/src/javascripts/jquery.tipsy.js"></script>
	<link rel="stylesheet" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/tipsy-0.1.7/src/stylesheets/tipsy.css" type="text/css" />

	<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/jquery.event.drag-1.5.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/jquery.event.drop-1.1.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/jquery.ajax_upload.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/jquery.timers.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/ajax_upload.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/json.js"></script>

	<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js-----old/slide.js"></script>
	
	<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js-----old/jquery.metadata.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js-----old/jquery.validate.js"></script>
<style type="text/css">
.design-section{
	width: 80%;
	margin: 0 auto;
}
.clear{ clear: both; }
.category_desc .content-box {
    float: left;
    width: 48%;
}
.content-box.img {
    text-align: center;
}
.content-box.table table{
	    margin: 0 auto;
}
input[type=text], input[type=password], textarea {
    background: #fff;
    border: 1px solid #ddd;
    padding: 5px 7px;
    margin-left: 0;
    margin-right: 0;
    font-size: 13px;
    font-family: Georgia,"Times New Roman",Times,serif;
    -webkit-transition: all .3s ease-in-out;
    -moz-transition: all .3s ease-in-out;
    -o-transition: all .3s ease-in-out;
    -ms-transition: all .3s ease-in-out;
    transition: all .3s ease-in-out;
    width: 218px;
}
</style>
</head>
<body class="home page-template page-template-homepage woocommerce-cart page-template-homepage-php page" id="badgeDesigner">
<?php //echo $modules_folder . INCLUDE_MODULE_TWO; 

if (!tep_session_is_registered('customer_id')) {
	$h_links = '<div class="menu-item-wrapper"><a href="create_account.php">Register</a></div>';
	$h_links .= '<div class="menu-item-wrapper"><a href="login.php">Login</a></div>';
	$h_links .= '<div class="menu-item-wrapper"><a href="shopping_cart.php">Cart</a></div>';
	//$h_links = '<a href="create_account.php">Register</a> <a href="login.php">Cart</a> <a href="shopping_cart.php">View cart</a>';
	$m_links = '<a href="create_account.php" id="user">&nbsp;</a> <a href="login.php" id="contact">&nbsp;</a>';
} else {
	$h_links = '<div class="menu-item-wrapper"><a href="' . tep_href_link(FILENAME_ACCOUNT, "", "SSL") . '">Account</a></div>';
	$h_links .= '<div class="menu-item-wrapper"><a href="logoff.php">Logoff</a></div>';
	$h_links .= '<div class="menu-item-wrapper"><a href="shopping_cart.php">Cart</a></div>';
	//$h_links = '<a href="' . tep_href_link(FILENAME_ACCOUNT, "", "SSL") . '">Account</a> <a href="logoff.php">Logoff</a> <a href="shopping_cart.php">Cart</a>';
	$m_links = '<a href="' . tep_href_link(FILENAME_ACCOUNT, "", "SSL") . '" id="user">&nbsp;</a> <a href="logoff.php" id="contact">&nbsp;</a>';
}
?>
<div class="store-alert">Need help? Call us on 02 8003 5046<br></div>
<div id="header">
<div class="max-cont">
<div class="flexbox align-center">
<div class="left flexbox align-center">
<div class="menu-item-wrapper">
<a href="<?php echo HTTP_SERVER; ?>">Home</a>
</div>
<div class="menu-item-wrapper">
<a href="#">Quote</a>
</div>
<div class="menu-item-wrapper">
<a href="#">Artwork</a>
</div>
<div class="menu-item-wrapper">
<a href="#">Contact Us</a>
</div>
</div>
<div class="center logo text-center">
<a href="/" alt="home"><img src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/image/logo400.png" alt=""></a>
</div>
<div class="right flexbox align-center justify-end">
<?php 
echo $h_links;
?>
</div>
</div>
</div>
<div class="mobile-header">
<div class="rel-wrapper">
<div class="logo">
<a href="/" alt="home">
<img src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/image/logo400.png" alt="">
</a>
</div>
<div class="flexbox space-between align-center">
<div class="mobile-menu-trigger">
<img src="images/nav-icon.png" alt="Menu">
</div>
<div class="cart-icon">
<a href="/cart" title="cart">
<img src="images/cart-icon.png" alt="Cart">
</a>
</div>
</div>
<div class="mobile-nav">
<div class="menu-item-wrapper">
<a href="#">Home</a>
</div>
<div class="menu-item-wrapper">
<a href="#">Quote</a>
</div>
<div class="menu-item-wrapper">
<a href="#">Artwork/a>
</div>
<div class="menu-item-wrapper">
<a href="#">Testimonials</a>
</div>
<div class="menu-item-wrapper">
<a href="#">Contact Us</a>
</div>
<hr>
<div class="menu-item-wrapper">
<a class="account" href="#" title="Account">Account</a>
</div>
</div>
</div>
</div>

</div>




<!-- End Header --> 
<div id="main-wrap">
	<div class="design-section">
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
	<div class="clear"></div>
  </div>

<?php } ?>
</div></div>
<!-- Footer Start -->
<?php 

	if (DOWN_FOR_MAINTENANCE_FOOTER_OFF =='false') {

		require(DIR_WS_INCLUDES . FILENAME_COUNTER); 
		require(DIR_WS_TEMPLATES . TEMPLATE_NAME. "/footer.php"); 
	
	} 
?>	

  <style type="text/css">
  	.content-box ul li {
    list-style: none;
    font-family: "futura-pt-condensed", sans-serif;
    /* font-style: normal; */
    font-size: 16px;
}
.color_set input{
	height: auto !important;
}
  </style>
  <!-- CSS Part Start-->
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/slideshow.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/flexslider.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/colorbox/colorbox.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/carousel.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/custom.css" />
<link rel="canonical" href="<?php echo HTTP_SERVER; ?>" />
<!-- CSS Part End-->


<script type="text/javascript" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/thickbox.js"></script>
<link rel="stylesheet" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/thickbox.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/badge_designer.css" />
<link rel="stylesheet" type="text/css" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/ajax_upload.css" />
<!-- end of badge designer -->

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

<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/mytheme.js"></script>
<!-- For Question and Live chat -->
<script type="text/javascript">
jQuery(document).ready(function(){
	
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
</style>
<!-- For Question and Live chat -->


<!-- Google Fonts (Droid Sans) Start -->
<link href='http://fonts.googleapis.com/css?family=Droid+Sans&amp;v1' rel='stylesheet' type='text/css'>
<!-- Google Fonts (Droid Sans) End -->


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
  
  
</body>
  </html>