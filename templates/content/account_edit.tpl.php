 	<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/jquery-ui-1.8.9.custom.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/jquery.ui.combogrid.css" />

	<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery-1.6.2.min.js"></script>

	<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery-ui-1.8.9.custom.min.js"></script>

	<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery.ui.combogrid-1.6.2.js"></script>

	<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery.metadata.js"></script>

	<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery.validate.min.js"></script>

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
				},
				password: {
				  required: true			  
				},
				confirmation: {
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
				telephone: "Please enter Telephone",
				password: "Please enter Password",
				confirmation: "Please enter Confirm Password"
			}
			
		});	
		//Form validation
		
		
	});

	</script>
	<style type="text/css">
	.pc_span { font-size:10px; color:#FF0000; }
	.inputRequirement { color: #FF0000; }

	.error{ color:#FF0000; padding-left:5px;  }
	.red_star { color:#FF0000; font-weight:bold; }
	.block { display: block; }
	form.cmxform label.error { display: none; }

	</style>
	
	<?php 
    // RCI code start
    echo $cre_RCI->get('global', 'top');
    echo $cre_RCI->get('accountedit', 'top');
    // RCI code eof
    echo tep_draw_form('account_edit', tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'), 'post', 'id="frm_add_to" class="cmxform" ') . tep_draw_hidden_field('action', 'process'); ?>
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
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
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

<?php
  if ($messageStack->size('account_edit') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('account_edit'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo CATEGORY_PERSONAL; ?></b></td>
                <td class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" cellspacing="2" cellpadding="2">
<?php
  if (ACCOUNT_GENDER == 'true') {
    if (isset($gender)) {
      $male = ($gender == 'm') ? true : false;
    } else {
      $male = ($account['customers_gender'] == 'm') ? true : false;
    }
    $female = !$male;
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_GENDER; ?></td>
                    <td class="main"><?php echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?><label class="error" for="gender">Please Select Gender</label></td>
                  </tr>
<?php
  }
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('firstname', $account['customers_firstname']) . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('lastname', $account['customers_lastname']) . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></td>
                  </tr>
<?php
  if (ACCOUNT_DOB == 'true') {
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_DATE_OF_BIRTH; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('dob', tep_date_short($account['customers_dob'])) . '&nbsp;' . (tep_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>': ''); ?></td>
                  </tr>
<?php
  }
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('email_address', $account['customers_email_address']) . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?></td>
                  </tr>
                  
                </table></td>
              </tr>
            </table></td>
          </tr>
<?php
      // RCI to alow for additioanl fields to be listed
      echo $cre_RCI->get('accountedit', 'listing');
?>
        </table></td>
      </tr>
	  <!--Modified -->
	  <?php
  if (ACCOUNT_COMPANY == 'true') {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo CATEGORY_COMPANY; ?></b></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_COMPANY; ?></td>
                <td class="main"><?php echo tep_draw_input_field('company', $account['entry_company']) . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
	  <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo CATEGORY_ADDRESS; ?></b></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
                <td class="main"><?php echo tep_draw_input_field('street_address', $account['entry_street_address']) . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
              <tr>
                <td class="main"><?php echo ENTRY_SUBURB; ?></td>
                <td class="main"><?php echo tep_draw_input_field('suburb', $account['entry_suburb']) . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
  }
?>
              <tr>
                <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
                <td class="main"><?php echo tep_draw_input_field('postcode', $account['entry_postcode'], ' id="postcode" ') . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_CITY; ?></td>
                <td class="main"><?php echo tep_draw_input_field('city', $account['entry_city'], ' id="city" ') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?></td>
              </tr>  
			  <?php
  if (ACCOUNT_STATE == 'true') {
?>
              <tr>
                <td class="main"><?php echo ENTRY_STATE; ?></td>
                <td class="main"><span id="available_states">
 <?php
    if ($process == true) {
      if ($entry_state_has_zones == true) {
        $zones_array = array();
        $zones_array[] = array('id' => '', 'text' => '');
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
       echo tep_draw_pull_down_menu('state', $zones_array,' id="state"');
      } else {
        echo tep_draw_input_field('state',$account['entry_state'], ' id="state" ');
      }
    }  else {
      echo tep_draw_input_field('state', tep_get_zone_name((isset($account['entry_country_id']) ? $account['entry_country_id'] : 0), (isset($account['entry_zone_id']) ? $account['entry_zone_id'] : 0 ), (isset($account['entry_state']) ? $account['entry_state'] : 0)),' id="state" ');
    }

    if (tep_not_null(ENTRY_STATE_TEXT)) echo '&nbsp;<span class="inputRequirement">' . ENTRY_STATE_TEXT;
?></span>
</td>
          </tr>
<?php
  }
?>
              <tr>
                <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
				 <td class="main"><?php echo tep_get_country_list('country', $account['entry_country_id'],' id="country" style="width:210px;"') . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?></td>               
              </tr>
			  
            </table><?php //tep_draw_hidden_field('country', $account['entry_country_id']) ?>     </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo CATEGORY_CONTACT; ?></b></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
                <td class="main"><?php echo tep_draw_input_field('telephone', $account['entry_telephone']) . '&nbsp;' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_FAX_NUMBER; ?></td>
                <td class="main"><?php echo tep_draw_input_field('fax', $account['entry_fax']) . '&nbsp;' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo CATEGORY_OPTIONS; ?></b></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_NEWSLETTER; ?></td>
                <td class="main"><?php echo tep_draw_checkbox_field('newsletter', '1', $account['customers_newsletter']) . '&nbsp;' . (tep_not_null(ENTRY_NEWSLETTER_TEXT) ? '<span class="inputRequirement">' . ENTRY_NEWSLETTER_TEXT . '</span>': ''); ?></td>
              </tr>
              <?php 
              // RCI code start
              echo $cre_RCI->get('createaccount', 'forms');
              // RCI code eof
              ?>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo CATEGORY_PASSWORD; ?></b></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_PASSWORD; ?></td>
                <td class="main"><?php echo tep_draw_password_field('password') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_TEXT . '</span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
                <td class="main"><?php echo tep_draw_password_field('confirmation') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>': ''); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <?php
      // RCI code start
      echo $cre_RCI->get('accountedit', 'menu');
      // RCI code eof
      // BOF: Lango Added for template MOD
      if (MAIN_TABLE_BORDER == 'yes'){
        table_image_border_bottom();
      }
      // EOF: Lango Added for template MOD
      ?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
	   
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></form>
    <?php
    // RCI code start
    echo $cre_RCI->get('accountedit', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof
    ?>