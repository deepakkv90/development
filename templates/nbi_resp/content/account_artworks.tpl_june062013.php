<?php
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('accounthistory', 'top');
  // RCI code eof    
  ?>
 <style type="text/css">
 	.main { border-bottom:1px solid #DDD; border-right:1px solid #DDD; }
	.main-left { border-left:1px solid #DDD; }
	.main-top { border-top:1px solid #DDD; }
 </style>
<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>

<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/fancybox/jquery.fancybox-1.3.4.css" />

<script type="text/javascript">
	
	function funGetID(idstring) {		
		var id = idstring.lastIndexOf("#")+4;		
		return idstring.substring(id);			
	}
	
	function pageLoadFunctions() {
		
		$("#option-feedback").hide();
		$('#artwork-option-message').html("");
		//$("#feedback-history").hide();
		
		$(".tab_content").hide(); //Hide all content			
		$("ul.tabs li:first").addClass("active").show(); //Activate first tab
		$(".tab_content:first").show(); //Show first tab content
		default_id = parseInt(funGetID($("ul.tabs li:first").find("a").attr("href")));		
		$("#art_option").val(default_id);
		//alert("hai"+default_id);
		
		
		//On Click Event
		$("ul.tabs li").click(function() {
									
			$("ul.tabs li").removeClass("active"); //Remove any "active" class
			$(this).addClass("active"); //Add "active" class to selected tab			
			$(".tab_content").hide(); //Hide all tab content
			var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
			$(activeTab).fadeIn(); //Fade in the active content			
			active_id = parseInt(funGetID($(this).find("a").attr("href")));
			$("#art_option").val(active_id);
			//alert("hi"+active_id);
			
			return false;
		}); 
		
		$('#btn-approve').click(function(){       			
						
			art_id = $('#active_artwork_id').val();
			art_opt_id = $("#art_option").val();
			action = "approved";			
			if(confirm("Are you sure want to approve this Artwork Option?")) {
				sendValue(art_id,art_opt_id,action);
				getFeedback(art_id); 
			}			    
		   
		}); 
		
		$('#btn-revision').click(function(){   			
						
			$("#option-feedback").show();
			//$("#feedback-history").show();
					   
		}); 
		//fancy box			
		$("a#fbox").fancybox({
				'titlePosition'	: 'over'
		});
							
	}
	
	$(document).ready(function() {
				
		//default artwork		
		default_art_id = $(".artwork").attr("id");
		default_art_opt_id = 0;		
		sendValue(default_art_id,default_art_opt_id,"view");  
		getFeedback(default_art_id); 
		
		//Load functions
		pageLoadFunctions();	
		
		//ajax function started
		$('.artwork').click(function(){       			
			art_id = $(this).attr("id");
			art_opt_id = 0;
			action = "view";			
			sendValue(art_id,art_opt_id,action); 
			getFeedback(art_id); 
		   
		}); 
		
		//right clcik disable for fancybox content
		$("#fancybox-wrap").live("contextmenu",function(e){
			alert("<?php echo ARTWORK_RIGHT_CLICK_ALERT; ?>");
			return false;
		});
			
	});
	

	// Function to handle ajax.
	function sendValue(art,art_opt,act){	   
		// post(file, data, callback, type); (only "file" is required)
		$.post(		   
		"artwork_options.php", 	   
		{ artID: art, opID: art_opt, action: act }, 		
		function(data){
			//alert(data.returnValue);
			if(act=="approved") {
				$('#artwork-status-'+art).html("approved");	
			}		
				
			$('#artwork-options').html(data.returnValue);
			//Load functions
			pageLoadFunctions();			
		},	   		
		"json"
		);   
	} //end send value function
		
	//function to load option feedback
	function getFeedback(aID) {		
		$.post(		   
		"artwork_options_feedback.php", 
		{ aID: aID }, 
		function(data){			
			$('#feedback-history').html(data.returnValue);					
		},	   		
		"json"
		);   
	}
	
	function confirm_feedback() {
				
		if($("#feedback").val()=="") {
			alert("Please enter your message!");
			return false;
		}
		if(confirm("Please confirm your feedback for this option?")) {
			return true;
		}
		return false;
	}
			
</script>
    

<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_history.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
        </tr>
      </table></td>
  </tr>
  <tr>
  	<td>
		<div id="msg" style="color:#0099FF;">
		<?php
		if(isset($_GET['msg']) && $_GET['msg']!="") {
		
			if($_GET['msg']=="suc") {
				echo "Feedback inserted successfully";
			} else if($_GET['msg']=="delete") {
				echo "File deleted successfully";
			}
		
		}
		?>
		</div>
			
	</td>
  </tr>
  
  
  <tr>  
	  <td>
	  	Here you will be able to view your design samples options as they are available. You will also be able to provide feedback, suggestions on your chosen design.
	  </td>  
  </tr>
  
  <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  
  <?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>
  <?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
  <tr>
    <td>
			
	<?php
	//get root
	$root = $_SERVER['DOCUMENT_ROOT'].mb_substr($_SERVER['PHP_SELF'],0,-mb_strlen(strrchr($_SERVER['PHP_SELF'],"/")));
	    			
		$artwork_query_law = "select * from artwork where customers_id='".$customer_id."' order by date_created desc";
					
		$artwork_split = new splitPageResults($artwork_query_law, MAX_DISPLAY_ORDER_HISTORY);
			
		$artwork_query = tep_db_query($artwork_split->sql_query);
		
		?>
		
		  <!-- Artwork Listing table start -->
		  
		  <table border="0" width="100%" cellspacing="0" cellpadding="0">
			 <tr>
				<td class="main main-top main-left" width="25%" style="padding:5px;"><b><?php echo "Product"; ?></b></td>
				<td class="main main-top" width="10%" style="padding:5px;" align="center"><b><?php echo "Order#"; ?></b></td>        
				<td class="main main-top" width="46%" style="padding:5px;"><b><?php echo "Creative Brief"; ?></b></td> 
				<td class="main main-top" width="14%" style="padding:5px;"><b>Date Created</b></td>         
				<td class="main main-top" width="10%" style="padding:5px;" align="center"><b>Status</b></td>   
			</tr>	  
		  
			<?php
			
			if(tep_db_num_rows($artwork_query)>0) {
			
				while ($artwork = tep_db_fetch_array($artwork_query)) {
					
					if(is_numeric($artwork['products_id'])) {
						$ord_prod = tep_get_order_products($artwork['products_id']);
						$prd_name = $ord_prod['products_name'];
					} else {
						$prd_name = $artwork['products_id']; 
					}
		
					echo '  <tr>
								<td class="main main-left" style="padding:5px;"><a href="javascript:void(0);" class="artwork" id="'.$artwork['artwork_id'].'"><b>'.$prd_name.'</b></a></td>
								<td class="main" style="padding:5px;" align="center">'.$artwork["orders_id"].'</td>        
								<td class="main" style="padding:5px;">'.stripslashes($artwork["creative_brief"]).'</td> 
								<td class="main" style="padding:5px;">'.tep_date_aus_format($artwork["date_created"],"short").'</td>     
								<td class="main" style="padding:5px;" align="center" id="artwork-status-'.$artwork["artwork_id"].'">'.$artwork["artwork_status"].'</td>   
							</tr>	';
					
				}
			} else {
				echo '<tr><td colspan="4" class="main main-left" style="padding:5px;">No Artworks available.</td></tr>';
			}
		
			?>
		
		</table>
		
		 <!-- Artwork Listing table ends -->
		<br />
		
			
		<br />
		
		<div id="artwork-option-message" style="font-weight:bold; color:#0099FF;"></div>
		
		<?php	
		  	echo tep_draw_form('frmMyArtwork', tep_href_link(FILENAME_ACCOUNT_ARTWORKS, '', 'SSL'), 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('action', 'process');   
  		?>	  				  			
		<table width="100%" align="center" border="0">
			<tr>
				<td id="artwork-options">
					
					
											
					<!-- Artwork option start -->	
					
					<div class="tap-container" style="display:none;">

						<h1 class="pageHeading" style="border:none;">Artwork Options for Standard Badge - PS001</h1>
				
						<ul class="tabs"><li><a href="#tab01">Option 1</a></li></ul>
				
						<div class="tab_container"><div id="tab20" class="tab_content"><img src="images/artworks/71052c0d75187b95a7ab318b2ef5dc282.jpg"></div><div id="tab19" class="tab_content"><img src="images/artworks/6cfe72a9816c945c35ad656f3a15c1e21.png"></div><div id="tab18" class="tab_content"><img src="images/artworks/f0fa390cce5bae441e9509179ba64d0c0.jpg"></div>			
				
						</div>
				
					 </div>	
					<!--	
					 <table align="center" border="0" width="100%" style="margin:3px;display:none;">
						<tr>
							<td valign="bottom">
								<a href="javascript:void(0);" id="btn-approve"><img src="images/artwork_approve.gif" /></a>							
								
							</td>
							<td valign="bottom" align="right">
								<a href="javascript:void(0);" id="btn-revision"><img src="images/artwork_revision.gif" /></a>								
							</td>
						</tr>
					 </table>	
				 	 
					 -->
					<!-- Artwork option end -->
					
				</td>
			</tr>
			
			
			 
			<tr>
				<td id="option-feedback" align="center" style="display:none;">			
				<!-- Feedback Start -->											
				  
				  <table id="option-feedback" align="center" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #DDD;">
					<tr>
						<td colspan="3" style="background:url('images/feed-bg.jpg') repeat-x;"><strong>Feedback</strong></td>
					</tr>
					<tr>
						<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
					</tr>
					<tr>
					  <td width="1%" valign="top">&nbsp;</td>
					  <td width="18%" valign="top"><strong>Comment</strong></td>
					  <td width="80%" valign="top"><textarea name="feedback" id="feedback" style="width:520px; height:150px;"></textarea></td>
					</tr>
					<tr>
						<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><strong>Attachment</strong></td>
						<td><input type="file" name="attachment" id="attachment" /></td>
					</tr>									
					<tr>
						<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
					</tr>					
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
						<td><input type="image" src="images/artwork_feedback.gif" onclick="return confirm_feedback();" /></td>
					</tr>
					<tr>
						<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
					</tr>						
			   </table>			
						   			  
				<!-- Feedback End -->
				</td>
			</tr>
			<tr>
				<td id="feedback-history">
					<!-- Feedback history -->
					
					<!-- Feedback history -->
				</td>
			</tr>
		</table>
		</form>
    </td>
  </tr>
  
 
  
  <?php
      // RCI code start
      echo $cre_RCI->get('accounthistory', 'menu');
      // RCI code eof    
      // BOF: Lango Added for template MOD
      if (MAIN_TABLE_BORDER == 'yes'){
        table_image_border_bottom();
      }
      // EOF: Lango Added for template MOD
 
        ?>
  <!--
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td class="smallText" valign="top"><?php echo $artwork_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
          <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $artwork_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
        </tr>
      </table></td>
  </tr>
  -->
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  
  <tr>
  
  <td>
  <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
    <tr class="infoBoxContents">
      <td>
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
          <td><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>        
        
        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
		<?php if(tep_db_num_rows($artwork_query)>0) { //display only we have any artwork designs  ?>
        <tr>
          <td colspan="3"><b>Disclaimer</b></td>
          </tr>
		<tr>
          <td colspan="3" style="border:1px dashed #CCC; padding:5px;">
		  	<p>This production design remains the property of and is copyright to Name Badges International & Co. Pty. Ltd.
		  	  Designs are created according to the customer brief and at times may be slightly altered to fit production requirements.Final production colours and finish may vary.</p>
		  	<br />

            <p><b>Please ensure that this design is suitable for your requirement. The quicker you are able to approve the design the faster we are able to complete the order.</b></p>
            <br />

            <p>The default colour model is in CMYK format unless you specify you require PMS (Pantone) colours prior to the start of your project.<br />
              We take pride in our flexibility and are able to produce exceptional quality products from almost any image.<br />
              We are releasing this design for your feedback. If you like it and want the final files released, good. However, if you like to have further work done on any design then please let us have your feedback. <br />
              We are committed to 100% satisfaction.</p>
            <br />

            <p>We have a vast library of fonts. You might want to check with us when submitting your order if your design uses a rare or obscure font. If it is not subject to any licencing then attach the font with your logo / artwork and we will add it to our selection.</p>
            <br />


            <p>Any format change after the start of your project will have an additional charge.<br />
              Please note: Free design is restricted to the initial design plus one modification. Further modification, thereafter, will incur a $88AUD (Incl. GST) per hour design fee.</p>            <br />		  </td>
          </tr>
		<tr>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
		<?php } //display only we have any artwork designs ?>
      </table>
    </td>
    
    </tr>
    
  </table>
  </td>
  
  </tr>
  
</table>
<?php
    // RCI code start
    echo $cre_RCI->get('accounthistory', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof    
    ?>
