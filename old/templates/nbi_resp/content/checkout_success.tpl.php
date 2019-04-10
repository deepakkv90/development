<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('checkoutsuccess', 'top');
// RCI code eof

?>

<style type="text/css">
.error{ color:#FF0000; padding:1px;  }
.red_star { color:#FF0000; font-weight:bold; }
.block { display: block; }
label.error { display: none; }
</style>

<script type="text/javascript">
		
	//After validation submit form
	$.validator.setDefaults({
		submitHandler: function(form) {			
			form.submit();			
		}
	});		
	$.metadata.setType("attr", "validate");
	
	//onload call functions
	jQuery(document).ready(function($) {		
			//initiate();			
			$("#tell_a_friend").validate();	
	});
	                      
</script>

<?php
echo tep_draw_form('order', tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'action=update', 'SSL')); ?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <!-- 
  <?php
  // BOF: Lango Added for template MOD
  if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
    $header_text = '&nbsp;'
    //EOF: Lango Added for template MOD
    ?>
    <tr>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><?php echo "Thank you ".ucfirst($customer_info['customers_firstname'])." your Order Has Been Processed!"; ?></td>
       </tr>
      </table></td>
    </tr>
    <?php
    // BOF: Lango Added for template MOD
  }else{
    $header_text = "Thank you ".ucfirst($customer_info['customers_firstname'])." your Order Has Been Processed!";
  }
  // EOF: Lango Added for template MOD
  ?>
   
  <tr>
    <td></td>
  </tr>
  -->
  
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  
  <?php
  	if($_GET['action']=="refer") {
			if($refer_status=="yes") { ?>
			
				<tr>
					<td class="main" valign="middle">
						
						<h1 style="font-size:17px; color: #0000FF;">Well done <?php echo ucfirst($your_name); ?>.<br />
						Your recommendation was successfully sent to <?php echo ucfirst($friend_name); ?>.<br />
						Thank you!</h1>
						
					</td>
				</tr>
			<?php } 
	
	} else { ?>
  
	  <!-- checkout_success_modules - start -->
	  <tr>
		<td>
			<table align="center" width="100%" border="0">
				<tr>
					<td width="70%" valign="top" class="main">				 											
						<h1 style="font-size:17px;"><?php echo "Thank you ".ucfirst($customer_info['customers_firstname'])." your Order Has Been Processed!"; ?></h1>
						<br />
						An email with your order details has also been sent to <b><?php echo $customer_info['customers_email_address']; ?></b>.<br />
						For your records, your order details and invoice are avaliable in your account 24/7.				
									
						<?php require('add_checkout_success.php'); //ICW CREDIT CLASS/GV SYSTEM ?>
						<?php
						  if (MODULE_CHECKOUT_SUCCESS_INSTALLED) {
							echo '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
							$checkout_success_modules->process();
							echo $checkout_success_modules->output();
							echo '</table>';
						  }
						?>					
					</td>
					<td width="15%" align="center" valign="top" class="main">
						<?php 
							echo '<a href="javascript:popupWindow(\'' .  tep_href_link("invoice_pdf.php", tep_get_all_get_params(array('order_id')) . 'order_id=' . (int)$_GET['order_id'], 'NONSSL') . '\')"><img src="images/printer.png" width="60" height="60" border="0"></a><br>'; 
							
							echo '<a href="javascript:popupWindow(\'' .  tep_href_link("invoice_pdf.php", tep_get_all_get_params(array('order_id')) . 'order_id=' . (int)$_GET['order_id'], 'NONSSL') . '\')">Print Order</a>';
						
						?>
					</td>
					<td width="15%" align="center" valign="top" class="main">
						<?php 
							echo '<a href="' .  tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'). '"><img src="images/checklist.png" width="60" height="60" border="0"></a><br>'; 
							echo '<a href="' .  tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">View Order</a>';
						
						?>
						
					</td>
				</tr>
			</table>
		</td>
	  </tr>
	  <!-- checkout_success_modules - end -->
  
  <?php } ?>

  <?php
  //RCI start
  echo $cre_RCI->get('checkoutsuccess', 'insideformabovebuttons');
  //RCI end
  ?>
  
  <?php
  //RCI start
  echo $cre_RCI->get('checkoutsuccess', 'insideformbelowbuttons');
   //RCI end
  ?>  
 	     
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
    
  <?php if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php'); ?>
    
</table></form>
<?php 
// RCI code start
echo $cre_RCI->get('checkoutsuccess', 'bottom', false); 
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>

<!-- Tell a friend starts -->
		<?php 
		echo tep_draw_form('tell_a_friend', tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id='.(int)$_GET['order_id'].'&action=refer', 'SSL'),'POST',' id="tell_a_friend" '); 
		
		?>
		<table width="100%" border="0" align="center">
			<tr>
				<td class="pageHeading" style="border-bottom:1px dotted #FF9900; vertical-align:middle;">
				
					<table align="center" border="0">
						<tr>
							<td><img src="images/info.png" width="24" height="24" /></td>
							<td style="font-size:14px; font-weight:bold;">&nbsp;Did you know?</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="main">
					A recent study showed that:<br />
					More than 53% internet users had visited a websites thanks to a referral by friends or family members. (eMarketer.com)<br /><br />
					<b>You too, you can now tell a friend about Name Badges International</b>
				</td>
			</tr>
			<tr>
				<td class="main">
					<?php 
					if($_GET['action']=="refer" && $refer_status=="no") {
						echo "<font style='color: #FF0000;'> Please enter valid details.</font>";
					}
					?>
				</td>
			</tr>
			<tr>
				<td>
					
					
					<div  class="main_wrapper">
						<div class="wrapper">
							<p class="last">
								<label>Your Name</label> <br/>
								<span><input name="your_name" onFocus="if(this.value=='Enter Your Name') {this.value='';}" onBlur="if(this.value=='') {this.value='Enter Your Name'}" type="text" validate="required:true" value="<?php echo ucfirst($customer_info['customers_firstname']); ?>" /></span> <br />
								<label for="your_name" class="error" style="color:#FF0000;">Your name should not be empty</label>
							</p>
							<p >
								<label>Your Email</label> <br/>
								<span><input name="your_email"  onFocus="if(this.value=='Enter Your Email') {this.value='';}" onBlur="if(this.value=='') {this.value='Enter Your Email'}" type="text" validate="required:true, email:true" value="<?php echo $customer_info['customers_email_address']; ?>" /></span> <br />
								<label for="your_email" class="error" style="color:#FF0000;">Please enter valid email address</label>
							</p>
						
							<p class="last friends">
								<label>Friend's Name</label> <br/>
								<span><input name="friend_name" type="text" validate="required:true" /></span> <br />
								<label for="friend_name" class="error" style="color:#FF0000;font-weight:normal;">Friend's name should not be empty</label>
							</p>
							<p  class="friends">
								<label>Friend's Email</label> <br/>
								<span><input name="friend_email" type="text" validate="required:true, email:true" /></span> <br />
								<label for="friend_email" class="error" style="color:#FF0000; font-weight:normal;">Please enter valid email address</label>
							</p>
						
						</div>
						<div class="lettertext">
							<p class="lettertexttop"> <span class="fl"><img src="images/text_bg_l.gif" width="9" height="9" alt="" /></span> <span class="fr"><img src="images/text_bg_r.gif" width="9" height="9" alt="" /></span></p>
							<div class="lettertextmiddle">
<textarea name="your_content" cols="" rows="15" validate="required:true"><?php echo TEXT_TELL_A_FRIEND; ?>
								
<?php echo ucfirst($customer_info['customers_firstname']); ?>
</textarea>
								<!--<br/>
								<br/>-->
							 </div>
							<p class="lettertextbottom"> <span class="fl"><img src="images/text_bg_b-l.gif" width="8" height="8" alt="" /></span> <span class="fr"><img src="images/text_bg_b-r.gif" width="8" height="8" alt="" /></span></p>
						</div>
						
						<input name="smt_tell_friend" type="submit"  value="Send It" class="sendit"/>
					
					
						<div class="spacer"></div>
					</div> 
					
					
				</td>
			</tr>
		</table>
	</form>	
  <!-- Tell a friend ends -->
  
 <table width="100%" border="0">
 	 <!-- Advertisement Content page-ad -->
   <tr>
    <td style="border-bottom:1px dotted #FF9900;"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
	<td align="center">
		<?php		
		if ($banner = tep_banner_exists('dynamic', "page-ad")) {		
			$bannerstring = tep_display_banner_with_text('static', $banner);			
			//print_r($bannerstring);		
			echo $bannerstring['link'];
			echo "<br><br>";
			echo $bannerstring['text'];
		}		
		?>
	</td>     
  </tr>
  <!-- Advertisement content ends here -->
 </table>
