<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
	
	<?php
		require(DIR_WS_TEMPLATES . TEMPLATE_NAME. "/head.php"); 
	?>

</head>

<?php

// RCO start

if ($cre_RCO->get('mainpage', 'body') !== true) {              

 echo '<body>' . "\n";

}

// RCO end
?>
<!--
<div id="layoutTopBar">
  <div class="layoutInner">
  
    <ul id="systemMenu">
      <li class="viewcart">
	  	<?php
			
			// show Cart Details

			if (SHOW_CART_IN_HEADER=='yes') {
				 echo "<a href='".tep_href_link(FILENAME_SHOPPING_CART, "", "SSL")."'>View Cart</a>"; 
			} ?>
			
	  </li>
      <?php

				if (!tep_session_is_registered('noaccount')){ // no display of logoff for PWA customers

					if (!tep_session_is_registered('customer_id')) {

						echo '<li class="register"><a href="create-customer-account">Register</a></li>';
						
						echo '<li class="login"><a href="' . tep_href_link("customer-account-login", "", "SSL") . '">' . HEADER_LINKS_LOGIN . '</a></li>';

					} else {
	
						echo '<li class="login"><a href="' . tep_href_link(FILENAME_ACCOUNT, "", "SSL") . '">' . HEADER_LINKS_ACCOUNT_INFO . '</a></li>';
						
						echo '<li class="register"><a href="create-customer-account">Register</a></li>';
	
					}

				}
				

				if (tep_session_is_registered('noaccount')) { // no display of account for PWA customers

					if (!tep_session_is_registered('customer_id')) {

						echo '<li class="register"><a href="create-customer-account">Register</a></li>';
						
						echo '<li class="login"><a href="' . tep_href_link("customer-account-login", "", "SSL") . '">' . HEADER_LINKS_LOGIN . '</a></li>';

					} else {

						echo '<li class="register"><a href="create-customer-account">Register</a></li>';
						
						echo '<li class="login"><a href="' . tep_href_link(FILENAME_ACCOUNT, "", "SSL") . '">' . HEADER_LINKS_ACCOUNT_INFO . '</a></li>';

					}

				}

		?>  
		
		
    </ul>
	<?php
		$pageId = "";
		if(isset($_GET['pID'])) {
			$pageId = $_GET['pID'];
		}		
		if(isset($_GET['tPath'])) {
			$artId = $_GET['tPath'];
		}	
		$acturl = basename($_SERVER['SCRIPT_NAME']);
		$topic_uri = $_SERVER['REQUEST_URI'];
		
	?>									   
	<ul id="mainMenu">
      <li <?php if($acturl=="index.php") echo "class='selected'"; ?><a href="<?php echo HTTP_SERVER; ?>/index.php" title="Click here for more info">Home</a> </li>
      <li <?php if($pageId==22) echo "class='selected'"; ?>><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=22&amp;CDpath=0" target="_self">About Us</a></li>
      <li <?php if($pageId==27) echo "class='selected'"; ?>><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=27&amp;CDpath=0" target="_self">Prices</a></li>
      <li <?php if($pageId==24) echo "class='selected'"; ?>><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=24&amp;CDpath=0">Name Badges</a></li>
	  <li <?php  if($artId==77) echo "class='selected' "; ?>><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=77" target="_self">School Badges</a></li>
      <li <?php  if($artId==21) echo "class='selected' "; ?>><a href="<?php echo HTTP_SERVER; ?>/articles.php?tPath=21&amp;CDpath=0" target="_self">Technical</a></li>
      <li <?php if($pageId==51) echo "class='selected'";  ?>><a href="<?php echo HTTP_SERVER; ?>/pages.php?CDpath=0&amp;pID=51" target="_self" >Contact Us</a></li>
	  <li><a href="<?php echo HTTP_SERVER; ?>/instant-quote-name-badge" target="_self" >Instant Quote</a></li>
	  
    </ul>
	
    <div class="clearer"></div>
  </div>
</div>

//-->
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
					  <li><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=24&amp;CDpath=0">Name Badges |</a></li>
					  <li><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=27&amp;CDpath=0">Price List |</a></li>
					  <li><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=77&amp;CDpath=0">Gallery |</a></li>
					  <li><a href="<?php echo HTTP_SERVER; ?>/pages.php?pID=22&amp;CDpath=0">About Us |</a></li>
					  <li><a href="<?php echo HTTP_SERVER; ?>/articles.php?tPath=21&amp;CDpath=0">Artwork |</a></li>
					  <li><a href="<?php echo HTTP_SERVER; ?>/articles.php?tPath=20&amp;CDpath=0">FAQs |</a></li>
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
				
				 <?php if ((eregi('index[.]php$', $_SERVER['SCRIPT_NAME'])) && !@$_GET['cPath'] && !@$_GET['info_id']) { ?>
				  
				  <section class="hsecond">							
					<!-- Nivo Slider Start -->
					<section class="slider-wrapper">
					  <div id="slideshow" class="nivoSlider"> <div class="nivoSlider" href="/p24/Name-Badges/"><img src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/image/slider/slide1.jpg" alt="slide-1" /></div> <div class="nivo-imageLink" href="/Executive-Name-Badge/c28/"><img src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/image/slider/slide2.jpg" alt="slide-2" /></div> <div class="nivo-imageLink" href="/Reusable-Name-Badge/c64/"><img src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/image/slider/slide3.jpg" alt="slide-3" /></div> </div>
					</section>
					<script type="text/javascript"><!--
					$(document).ready(function() {
						$('#slideshow').nivoSlider();
					});
					--></script>
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
		
</body>
  </html>