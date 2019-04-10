

<!--Footer Part Start-->

<div id="footer">
      <div class="mid-cont">
        <div id="footer-nav" class="section flexbox align-center justify-center flex-wrap">
                                  <div class="menu-item-wrapper">
                <a class="" href="<?php echo HTTP_SERVER; ?>" title="">Home</a>
              </div>
                        <div class="menu-item-wrapper">
                <a class="" href="<?php echo HTTP_SERVER; ?>articles.php?tPath=24&CDpath=0" target="_blank" title="">Delivery</a>
              </div>
                        <div class="menu-item-wrapper">
                <a class="" href="<?php echo HTTP_SERVER; ?>articles.php?tPath=26&CDpath=0" target="_blank" title="">Privacy Policy</a>
              </div>
                        <div class="menu-item-wrapper">
                <a class="" href="<?php echo HTTP_SERVER; ?>articles.php?tPath=20&CDpath=0" target="_blank" title="">Terms and Conditions</a>
              </div>
                        <div class="menu-item-wrapper">
                <a class="" href="<?php echo HTTP_SERVER; ?>articles.php?tPath=20&CDpath=0" target="_blank" title="">FAQs</a>
              </div>
                        <div class="menu-item-wrapper">
                <a class="" href="<?php echo HTTP_SERVER; ?>pages.php?pID=56&amp;CDpath=0" target="_blank" title="">Site Map</a>
              </div>
                  </div>

        <div class="section" id="social-cont">
          <div class="flexbox align-center justify-center">
            <a id="instagram" class="social-icon" target="_blank" href="#">
              <i class="fa fa-googleplus" aria-hidden="true"></i>
            </a>
            <a id="facebook" class="social-icon" target="_blank" href="#">
              <i class="fa fa-facebook" aria-hidden="true"></i>
            </a>
            <a id="twitter" class="social-icon" target="_blank" href="#">
              <i class="fa fa-twitter" aria-hidden="true"></i>
            </a>
            <a id="tumblr" class="social-icon" target="_blank" href="#">
              <i class="fa fa-tumblr" aria-hidden="true"></i>
            </a>
          </div>
        </div>

        <div class="section flexbox align-center justify-center" id="copyright">
          <p>&copy; 2018 Button Badges International</p>
        </div>
      </div>
    </div>
<!-- Footer Part End-->
      <!-- Back to Top Button End-->
	  
	  <script type="text/javascript">
		<?php if(basename($_SERVER['PHP_SELF'])!="checkout_success.php"){ ?>
		  var _gaq = _gaq || [];
		  //23997981
		  _gaq.push(['_setAccount', 'UA-75359969-1']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		  <?php } ?>
		</script>
		
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
		
		
  </footer>
  <!--Footer Part End-->