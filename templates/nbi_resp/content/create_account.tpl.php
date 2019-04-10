<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/jquery-ui-1.8.9.custom.css" />

<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/jquery.ui.combogrid.css" />

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
			  email: true,			  
			  remote: "check_email.php"
			},
			telephone: {
			  required: true			  
			},
			password: {
			  required: true			  
			},
			confirmation: {
			  required: true			  
			},
			street_address: {
			  required: true,
			  maxlength: 40,
			  minlength: 5
			},
			postcode: {
			  required: true,
			  minlength: 4,
			  maxlength: 5
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
			visual_verify_code: {
			  required: true			  
			}
		},
        errorPlacement: function (error, element) {
            var type = $(element).attr("type");
            if (type === "radio") {
                // custom placement
                error.insertBefore(element).wrap('<span/>');
            }
        },
        messages: {            
			gender: "*",
			firstname: {
			  required: "Enter your firstname",
			  maxlength: "Firstname should not exceeds 18 characters"
			},
			lastname: {
			  required: "Enter your lastname",		  
			  maxlength: "Lastname should not exceeds 17 characters"
			},
			email_address: {
			  required: "Enter your email address",
			  email: "Email address must be valid",
			  remote: "Email address already exists."
			},	
			telephone: "Enter your contact number",
			password: "Create your password",
			confirmation: "Confirm your password",			
			street_address: { 
			  required: "Enter your street address", 
			  maxlength: "Street Address should not exceeds 40 characters"
			},
			postcode: {
                required: "Enter your Postcode",
                minlength: "Postcode must contain at least 4 characters" 
            },
			city: "Enter your city",
			state: "Enter your state",
			country: "Select your country",
			visual_verify_code: "Enter valid security code"
		}
		
    });	
	//Form validation
	
	
});

</script>

<?php 

echo tep_draw_form('create_account', tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'), 'post', 'id="frm_add_to" class="cmxform" enctype="multipart/form-data"') . tep_draw_hidden_field('action', 'process'); ?>
	  
	  <h2>Register your Account</h2>
      <!--<p><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></p>-->

		<?php
		  if ($messageStack->size('create_account') > 0) {
		?>
			  <div class="content"><?php echo $messageStack->output('create_account'); ?></div>
		<?php
		  }
		?>
	  <div class="register-content form-content">
		
		<div class="left"><h2><?php echo CATEGORY_PERSONAL; ?></h2></div>
		<div class="right">
			<?php
			  if (ACCOUNT_GENDER == 'true') {
				echo tep_draw_radio_field('gender', 'm') . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f') . '&nbsp;&nbsp;' . FEMALE; 
			?><br/><br/>
			
			<?php } ?>
			
			<input type="text" name="firstname" placeholder="<?php echo ENTRY_FIRST_NAME; ?>" />
			<input type="text" name="lastname" placeholder="<?php echo ENTRY_LAST_NAME; ?>" />
			
			<?php if (ACCOUNT_COMPANY == 'true') { ?>
			<input type="text" name="company" placeholder="<?php echo ENTRY_COMPANY; ?>" />
			<?php } ?>
			
			<?php
			  if (ACCOUNT_DOB == 'true') {
			?>
			<input type="text" name="dob" placeholder="<?php echo ENTRY_DATE_OF_BIRTH; ?>" />
			<?php } ?>
			<input type="text" name="email_address" placeholder="<?php echo ENTRY_EMAIL_ADDRESS; ?>" />
			<input type="text" name="telephone" placeholder="<?php echo ENTRY_TELEPHONE_NUMBER; ?>" />
			<input type="text" name="fax" placeholder="<?php echo ENTRY_FAX_NUMBER; ?>" />
		</div>
		<div class="clear"></div>
		
		<div class="left"><h2><?php echo CATEGORY_ADDRESS; ?></h2></div>
		<div class="right">
			<input type="text" name="street_address" placeholder="<?php echo ENTRY_STREET_ADDRESS; ?>" />
			<?php if (ACCOUNT_SUBURB == 'true') { ?>
			<input type="text" name="suburb" placeholder="<?php echo ENTRY_SUBURB; ?>" />
			<?php } ?>
			<input type="text" name="postcode" id="postcode" value="<?php echo ($postcode)?$postcode:""; ?>" placeholder="<?php echo ENTRY_POST_CODE; ?>" />
			<input type="text" name="city" id="city" value="<?php echo ($city)?$city:""; ?>" placeholder="<?php echo ENTRY_CITY; ?>" />
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
						echo tep_draw_pull_down_menu('state', $zones_array,($state)?$state:"",' id="state" placeholder='.ENTRY_STATE);
					  } else {
						echo tep_draw_input_field('state',($state)?$state:"",' id="state" placeholder='.ENTRY_STATE);
					  }
					} else {
						  echo tep_draw_input_field('state',($state)?$state:"",' id="state" placeholder='.ENTRY_STATE);	
					}
				?>
			</span>
			<br/>
			<?php } ?>
			
			<?php echo tep_get_country_list('country',($country)?$country:'',' id="country" style="width:210px;"'); ?>
			
		</div>
		<div class="clear"></div>
		
		<div class="left"><h2><?php echo CATEGORY_PASSWORD; ?></h2></div>
		<div class="right">
			<input type="password" name="password" placeholder="<?php echo ENTRY_PASSWORD; ?>" />
			<input type="password" name="confirmation" placeholder="<?php echo ENTRY_PASSWORD_CONFIRMATION; ?>" />
		</div>
		<div class="clear"></div>
		
		<div class="left"><h2><?php echo VISUAL_VERIFY_CODE_CATEGORY; ?></h2></div>
		<div class="right">
			<input type="text" name="visual_verify_code" placeholder="" />
			<?php
			  //can replace the following loop with $visual_verify_code = substr(str_shuffle (VISUAL_VERIFY_CODE_CHARACTER_POOL), 0, rand(3,6)); if you have PHP 4.3
			$visual_verify_code = ""; 
			for ($i = 1; $i <= rand(3,6); $i++){
				  $visual_verify_code = $visual_verify_code . substr(VISUAL_VERIFY_CODE_CHARACTER_POOL, rand(0, strlen(VISUAL_VERIFY_CODE_CHARACTER_POOL)-1), 1);
			 }
			 $vvcode_oscsid = tep_session_id();
			 tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . $vvcode_oscsid . "'");
			 $sql_data_array = array('oscsid' => $vvcode_oscsid, 'code' => $visual_verify_code);
			 tep_db_perform(TABLE_VISUAL_VERIFY_CODE, $sql_data_array);
			 $visual_verify_code = "";
			 echo('<img src="' . FILENAME_VISUAL_VERIFY_CODE_DISPLAY . '?vvc=' . $vvcode_oscsid . '">');
			
			 ?>
			<br/><br/>
			Subscribe Newsletter: <?php echo tep_draw_checkbox_field('newsletter', '1', true); ?>
		</div>
		<div class="clear"></div>
		<div class="right"><div><input type="submit" class="button" value="Continue"></div></div>
		
	</div>

</form>