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
	

</head>

<?php
// RCO start

if ($cre_RCO->get('mainpage', 'body') !== true) {              

 echo '<body class="home page-template page-template-homepage page-template-homepage-php page page-id-2" id="home">' . "\n";

}

// RCO end
?>
<div class="store-alert">Need help? Call us on 02 8003 5046<br></div>
<?php //echo $modules_folder . INCLUDE_MODULE_TWO; 

if (!tep_session_is_registered('customer_id')) {
	$h_links = '<div class="menu-item-wrapper"><a href="#">Register</a></div>';
	$h_links .= '<div class="menu-item-wrapper"><a href="#">Login</a></div>';
	$h_links .= '<div class="menu-item-wrapper"><a href="#">Cart</a></div>';
	//$h_links = '<a href="create_account.php">Register</a> <a href="login.php">Cart</a> <a href="shopping_cart.php">View cart</a>';
	$m_links = '<a href="create_account.php" id="user">&nbsp;</a> <a href="login.php" id="contact">&nbsp;</a>';
} else {
	$h_links = '<div class="menu-item-wrapper"><a href="#">Account</a></div>';
	$h_links .= '<div class="menu-item-wrapper"><a href="#">Logoff</a></div>';
	$h_links .= '<div class="menu-item-wrapper"><a href="#">Cart</a></div>';
	//$h_links = '<a href="' . tep_href_link(FILENAME_ACCOUNT, "", "SSL") . '">Account</a> <a href="logoff.php">Logoff</a> <a href="shopping_cart.php">Cart</a>';
	$m_links = '<a href="' . tep_href_link(FILENAME_ACCOUNT, "", "SSL") . '" id="user">&nbsp;</a> <a href="logoff.php" id="contact">&nbsp;</a>';
}
?>
<style type="text/css">
	.slick-slide img{
		margin: 0 auto;
	}
	ul.products li.product{
		
	}
</style>
<div id="header">
<div class="max-cont">
<div class="flexbox align-center">
<div class="left flexbox align-center">
<div class="menu-item-wrapper">
<a href="<?php echo HTTP_SERVER; ?>">Home</a>
</div>
<div class="menu-item-wrapper">
<a href="<?php echo HTTP_SERVER; ?>/instant_quote.php">Quote</a>
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
<img src="https://1l07p14dnnmetfi4z2lw1wwn-wpengine.netdna-ssl.com/wp-content/themes/jackrudy/images/nav-icon.png" alt="Menu">
</div>
<div class="cart-icon">
<a href="/cart" title="cart">
<img src="https://1l07p14dnnmetfi4z2lw1wwn-wpengine.netdna-ssl.com/wp-content/themes/jackrudy/images/cart-icon.png" alt="Cart">
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
<?php if ((eregi('index[.]php$', $_SERVER['SCRIPT_NAME'])) && !@$_GET['cPath'] && !@$_GET['info_id']) { ?>

<div id="banner-cont">
	<div id="home-img"><img src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/image/banner.jpg" class="attachment-full size-full wp-post-image" alt="Jack Rudy Cocktail Co." width="100%" /></div>
</div>
<div id="gray-cont" class="text-center">
	<div class="mid-cont">
		<div class="skinny-cont">
      <h1>Quality Bar Goods</h1>
      <p class="body-italic">Our products are formulated in small batches and distributed across the globe to the finest bottle shops, bars, restaurants and discerning customers. </p>
    </div>
	</div>
</div>


<div id="featured-prods" class="section">
<ul class="products">
<?php
$dd=0;
while ($bbiProducts = tep_db_fetch_array($bbiProductsQuery)){ ?>

<li class="product">
<a href="#" title="<?php echo $bbiProducts['products_name']; ?>">
<img src="images/<?php echo $bbiProducts['products_image_med']; ?>" alt="<?php echo $bbiProducts['products_name']; ?>">
<h2 class="woocommerce-loop-product__title"><?php echo $bbiProducts['products_name']; ?></h2>
</a>
</li>

<?php } ?>
</ul>
<a href="#" class="slick-nav slick-prev" id="slick-prev-1"></a>
<a href="#" class="slick-nav slick-next" id="slick-next-1"></a>
</div>
<?php } ?>

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
				

<!-- Footer Start -->
<?php 

	if (DOWN_FOR_MAINTENANCE_FOOTER_OFF =='false') {

		require(DIR_WS_INCLUDES . FILENAME_COUNTER); 
		require(DIR_WS_TEMPLATES . TEMPLATE_NAME. "/footer.php"); 
	
	} 
?>	
<!-- Footer End -->

</div>
  
<!-- CSS Part End-->
<!-- JS Part Start-->

<?php
if (isset($javascript) && file_exists(DIR_WS_JAVASCRIPT . basename($javascript))) { require(DIR_WS_JAVASCRIPT . basename($javascript)); } 
?>

<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery-migrate.min.js"></script>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/acea0db5c5.js"></script>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/slick.min.js"></script>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/mytheme.js"></script>

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