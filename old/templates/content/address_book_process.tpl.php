<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/jquery-ui-1.8.9.custom.css" />

<link rel="stylesheet" type="text/css" href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/css/jquery.ui.combogrid.css" />

<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery-1.6.2.min.js"></script>

<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery-ui-1.8.9.custom.min.js"></script>

<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery.ui.combogrid-1.6.2.js"></script>

<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery.metadata.js"></script>

<script language="JavaScript" type="text/javascript" src="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/js/jquery.validate.min.js"></script>

<script type="text/javascript">

function assignCombo(countryId) {
				
		$( 'input[name="postcode"]' ).combogrid({
			
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
				$(  'input[name="postcode"]'  ).val( ui.item.postcode );
				$(  'input[name="postcode"]'  ).removeClass("error");
				$('label[for="postcode"]').hide();
				
				$(  'input[name="city"]'  ).val( ui.item.city );
				$(  'input[name="city"]' ).removeClass("error");				
				$('label[for="city"]').hide();
				
				$( 'input[name="state"]').val( ui.item.state );
				$( 'input[name="state"]' ).removeClass("error");
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
			$('input[name="state"]').val("");
			$( 'input[name="city"]').val("");
		}
	},	   		
	"json"
	);   
}

//Form validation
$.validator.setDefaults({
	submitHandler: function(form) {					
		form.submit();			
	}
});		
$.metadata.setType("attr", "validate");
	
jQuery(document).ready(function(){
	
	//"keyup" event handler to reset input fields
	$(  'input[name="postcode"]' ).live('keyup', function(){
		if($( 'input[name="postcode"]' ).val().length==0){
			$( 'input[name="postcode"]').val(""); $('input[name="state"]').val(""); $('#country').val("");
		}		
		setFields($( 'input[name="postcode"]' ).val());		
	});

	assignCombo($("select#country").val());
		
	//Form validation
	
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
			  maxlength: 20,
			  minlength: 2
			},
			lastname: {
			  required: true,			  
			  maxlength: 20,
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
			  maxlength: "Firstname should not exceeds 20 characters"
			},
			lastname: {
			  required: "Please enter Lastname",		  
			  maxlength: "Lastname should not exceeds 20 characters"
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
echo $cre_RCI->get('addressbookprocess', 'top');
// RCI code eof   
if (!isset($HTTP_GET_VARS['delete'])) echo tep_draw_form('addressbook', tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, (isset($HTTP_GET_VARS['edit']) ? 'edit=' . $HTTP_GET_VARS['edit'] : ''), 'SSL'), 'post', 'id="frm_add_to" class="cmxform"'); ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php if (isset($HTTP_GET_VARS['edit'])) { echo HEADING_TITLE_MODIFY_ENTRY; } elseif (isset($HTTP_GET_VARS['delete'])) { echo HEADING_TITLE_DELETE_ENTRY; } else { echo HEADING_TITLE_ADD_ENTRY; } ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_address_book.gif', (isset($HTTP_GET_VARS['edit']) ? HEADING_TITLE_MODIFY_ENTRY : HEADING_TITLE_ADD_ENTRY), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>

<?php
// BOF: Lango Added for template MOD
}else{
if (isset($HTTP_GET_VARS['edit'])) { $header_text = HEADING_TITLE_MODIFY_ENTRY; } elseif (isset($HTTP_GET_VARS['delete'])) { $header_text = HEADING_TITLE_DELETE_ENTRY; } else { $header_text = HEADING_TITLE_ADD_ENTRY; }
}
// EOF: Lango Added for template MOD
?>


<?php
  if ($messageStack->size('addressbook') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('addressbook'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
}
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD

  if (isset($HTTP_GET_VARS['delete'])) {
?>
      <tr>
        <td class="main"><b><?php echo DELETE_ADDRESS_TITLE; ?></b></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top"><?php echo DELETE_ADDRESS_DESCRIPTION; ?></td>
                <td align="right" width="50%" valign="top"><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main" align="center" valign="top"><b><?php echo SELECTED_ADDRESS; ?></b><br><?php echo tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" valign="top"><?php echo tep_address_label($customer_id, $HTTP_GET_VARS['delete'], true, ' ', '<br>'); ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $HTTP_GET_VARS['delete'] . '&amp;action=deleteconfirm', 'SSL') . '">' . tep_template_image_button('button_delete.gif', IMAGE_BUTTON_DELETE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td><?php include(DIR_WS_MODULES . FILENAME_ADDRESS_BOOK_DETAILS); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
    if (isset($HTTP_GET_VARS['edit']) && is_numeric($HTTP_GET_VARS['edit'])) {
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right"><?php echo tep_draw_hidden_field('action', 'update') . tep_draw_hidden_field('edit', $HTTP_GET_VARS['edit']) . tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_UPDATE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
    } else {
      if (sizeof($navigation->snapshot) > 0) {
        $back_link = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
      } else {
        $back_link = tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL');
      }
?>
<?php
// RCI code start
echo $cre_RCI->get('addressbookprocess', 'menu');
// RCI code eof 
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo '<a href="' . $back_link . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right"><?php echo tep_draw_hidden_field('action', 'process') . tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>

<?php
    }
  }
?>
    </table><?php if (!isset($HTTP_GET_VARS['delete'])) echo '</form>'; ?>
<?php 
// RCI code start
echo $cre_RCI->get('addressbookprocess', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof 
?>