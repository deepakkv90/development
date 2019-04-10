	<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css----old/jquery-ui-1.8.9.custom.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css----old/jquery.ui.combogrid.css" />

	<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js-----old/jquery-1.6.2.min.js"></script>

	<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js-----old/jquery-ui-1.8.9.custom.min.js"></script>

	<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js-----old/jquery.ui.combogrid-1.6.2.js"></script>

	<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js-----old/jquery.metadata.js"></script>

	<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js-----old/jquery.validate.min.js"></script>

	<script type="text/javascript">

	function assignCombo(countryId) {
					
			$( "#postcode" ).combogrid({
				
				//disabled: false,
				autoFocus: true,
				autoChoose: true,
				searchIcon: true,
				showOn: true,
				debug:true,			
				colModel: [{'columnName':'postcode','width':'10','label':'Postcode'}, 
						   {'columnName':'city','width':'45','label':'City'},
						   {'columnName':'state','width':'45','label':'State'}],
				url: 'get_combo_data.php?cid='+countryId,
				sord: "asc", 
				rows: 10,			
				sidx: "Postcode",
				rememberDrag: true,		  
				select: function( event, ui ) {			
					$( "#postcode" ).val( ui.item.postcode );
					$( "#postcode" ).removeClass("error");
					$('label[for="postcode"]').hide();
					
					$( "#city" ).val( ui.item.city );
					$( "#city" ).removeClass("error");				
					$('label[for="city"]').hide();
					
					$( "#state" ).val( ui.item.state );
					$( "#state" ).removeClass("error");
					$('label[for="state"]').hide();
					
					$( "#country" ).val( ui.item.country_id );
					$( "#country" ).removeClass("error");
					$('label[for="country"]').hide();
					
					return false;
				},
				
			});
			
	}

	function setFields(postcode){	   
		
		$.post(		   
		"get_details_by_postcode.php", 	   
		{ pCode: postcode }, 		
		function(data) {
			//alert(data.returnValue);	
			if(data.returnValue==1) {		
				$('#country').val("");
				$("#state").val("");
				$("#city").val("");
			}
		},	   		
		"json"
		);   
	}

		
	jQuery(document).ready(function(){
		
		//"keyup" event handler to reset input fields
		$( "#postcode" ).live('keyup', function(){
			if($( "#postcode" ).val().length==0){
				$('#city').val(""); $('#state').val(""); $('#country').val("");
			}
			
			setFields($( "#postcode" ).val());
			
		});

		assignCombo($("select#country").val());
			
		//Form validation
		$.validator.setDefaults({
			submitHandler: function(form) {					
				form.submit();			
			}
		});		
		$.metadata.setType("attr", "validate");
		
		$("#frm_add_to").validate({
			onfocusout: function(element, event) {
				this.element(element);
			},
			onkeyup: false,
			rules: {
				gender: {
				  required: true			  
				},
				firstname: {
				  required: true,
				  maxlength: 18,
				  minlength: 2
				},
				lastname: {
				  required: true,			  
				  maxlength: 17,
				  minlength: 2
				},
				email_address: {
				  required: true,
				  email: true				  
				},
				street_address: {
				  required: true,
				  maxlength: 40,
				  minlength: 5
				},
				postcode: {
				  required: true,
				  maxlength: 5,
				  minlength: 4
				},
				city: {
				  required: true			  
				},
				state: {
				  required: true			  
				},
				country: {
				  required: true			  
				},
				telephone: {
				  required: true			  
				}
				
			},
			messages: {            
				firstname: {
				  required: "Please enter Firstname",
				  maxlength: "Firstname should not exceeds 18 characters"
				},
				lastname: {
				  required: "Please enter Lastname",		  
				  maxlength: "Lastname should not exceeds 17 characters"
				},
				email_address: {
				  required: "Please enter Email address",
				  email: "Email address must be valid"				  
				},			
				street_address: { 
				  required: "Please enter Street address", 
				  maxlength: "Street Address should not exceeds 40 characters"
				},
				postcode: {
					required: "Please enter PostCode",
					minlength: "PostCode must contain at least 4 characters" 
				},
				city: "Please enter City",
				state: "Please enter State",
				country: "Please enter Country",
				telephone: "Please enter Telephone"
			}
			
		});	
		//Form validation
		
		
	});

	</script>

	
	<?php 

    echo tep_draw_form('account_edit', tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'), 'post', 'id="frm_add_to" class="cmxform" ') . tep_draw_hidden_field('action', 'process'); ?>
    
	<h2><?php echo HEADING_TITLE; ?></h2>
	
	<?php
	  if ($messageStack->size('account_edit') > 0) {
	?>
		  <div class="content"><?php echo $messageStack->output('account_edit'); ?></div>
	<?php
	  }
	?>
	
	<div class="register-content form-content">
		
		<div class="left"><h2><?php echo CATEGORY_PERSONAL; ?></h2></div>
		<div class="right">
			
			<?php
			  if (ACCOUNT_GENDER == 'true') {
				  
				    if (isset($gender)) {
						$male = ($gender == 'm') ? true : false;
					} else {
						$male = ($account['customers_gender'] == 'm') ? true : false;
					}
					$female = !$male;
	
				echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE; 
			?><br/><br/>
			
			<?php } ?>
			<input type="text" name="firstname" value="<?php echo $account['customers_firstname']; ?>" placeholder="<?php echo ENTRY_FIRST_NAME; ?>" />
			<input type="text" name="lastname" value="<?php echo $account['customers_lastname']; ?>" placeholder="<?php echo ENTRY_LAST_NAME; ?>" />
			
			<?php if (ACCOUNT_COMPANY == 'true') { ?>
			<input type="text" value="<?php echo $account['entry_company']; ?>" name="company" placeholder="<?php echo ENTRY_COMPANY; ?>" />
			<?php } ?>
			
			<?php
			  if (ACCOUNT_DOB == 'true') {
			?>
			<input type="text" name="dob" value="<?php echo $account['customers_dob']; ?>" placeholder="<?php echo ENTRY_DATE_OF_BIRTH; ?>" />
			<?php } ?>
			<input type="text" value="<?php echo $account['customers_email_address']; ?>"  name="email_address" placeholder="<?php echo ENTRY_EMAIL_ADDRESS; ?>" />
			<input type="text" value="<?php echo $account['entry_telephone']; ?>" name="telephone" placeholder="<?php echo ENTRY_TELEPHONE_NUMBER; ?>" />
			<input type="text" value="<?php echo $account['entry_fax']; ?>" name="fax" placeholder="<?php echo ENTRY_FAX_NUMBER; ?>" />
			
		</div>
		<div class="clear"></div>
		
		<div class="left"><h2><?php echo CATEGORY_ADDRESS; ?></h2></div>
		<div class="right">
			<input type="text" value="<?php echo $account['entry_street_address']; ?>" name="street_address" placeholder="<?php echo ENTRY_STREET_ADDRESS; ?>" />
			<?php if (ACCOUNT_SUBURB == 'true') { ?>
			<input type="text" value="<?php echo $account['entry_suburb']; ?>" name="suburb"  placeholder="<?php echo ENTRY_SUBURB; ?>" />
			<?php } ?>
			<input type="text" name="postcode" id="postcode" value="<?php echo ($postcode)?$postcode:$account['entry_postcode']; ?>" placeholder="<?php echo ENTRY_POST_CODE; ?>" />
			<input type="text" name="city" id="city" value="<?php echo ($city)?$city:$account['entry_city']; ?>" placeholder="<?php echo ENTRY_CITY; ?>" />
			<?php if (ACCOUNT_STATE == 'true') { ?>
			<span id="available_states">
				<?php
					if ($process == true) {
					  if ($entry_state_has_zones == true) {
						$zones_array = array();
						$zones_array[] = array('id' => '', 'text' => '');
						$zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
						while ($zones_values = tep_db_fetch_array($zones_query)) {
						  $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
						}
						echo tep_draw_pull_down_menu('state', $zones_array,($state)?$state:$account['entry_state'],' id="state" placeholder='.ENTRY_STATE);
					  } else {
						echo tep_draw_input_field('state',($state)?$state:$account['entry_state'],' id="state" placeholder='.ENTRY_STATE);
					  }
					} else {
						echo tep_draw_input_field('state', tep_get_zone_name((isset($account['entry_country_id']) ? $account['entry_country_id'] : 0), (isset($account['entry_zone_id']) ? $account['entry_zone_id'] : 0 ), (isset($account['entry_state']) ? $account['entry_state'] : 0)),' id="state" placeholder='.ENTRY_STATE);						  
					}
				?>
			</span>
			<br/>
			<?php } ?>
			
			<?php echo tep_get_country_list('country',($country)?$country:$account['entry_country_id'],' id="country" style="width:210px;"'); ?>
			
		</div>
		<div class="clear"></div>
		
		<div class="left"><h2><?php echo CATEGORY_PASSWORD; ?></h2></div>
		<div class="right">
			<input type="text" name="password" placeholder="<?php echo ENTRY_PASSWORD; ?>" />
			<input type="text" name="confirmation" placeholder="<?php echo ENTRY_PASSWORD_CONFIRMATION; ?>" />
			
			<br/><br/>
			Subscribe Newsletter: <?php echo tep_draw_checkbox_field('newsletter', '1', $account['customers_newsletter']); ?>
			
		</div>
		<div class="clear"></div>
		
		
	</div>	
	   
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
	  <tr>
		<td><?php echo '<a class="button" href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">Back</a>'; ?></td>
		<td align="right"><input type="submit" name="btnAccEdit" value="Update" class="button" /></td>
	  </tr>
	</table>
	
	</form>
   