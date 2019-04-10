<div class='badge_designer_holder'>
  <h2>Design your Products</h2>
  <input type="hidden" name="proIDBadges" id="proIDBadges" value="<?php echo $cPro; ?>">
  <div class='badge_box'>
    <div id='badge_shapes' class='badge_shapes'> <span class='title' id="multi_size" >Click on product size to suit your requirements:</span> <br />
    </div>
    <div id="badge_border_color"> <span class="title">Select Border Colour:</span>
      <div id="badge_border_colors"></div>
    </div>
  </div>
  <div id="debug"></div>
  <div class='badge_box'>
    <div class='badge_and_buttons'>
      <div id='badge_designer_background' class='badge_designer'> 
        <!--<div id='names' class='badge_logo' style="left:0px; top:-25px;"></div>-->
        <div id='badge_designer' class='badge_designer'>
          <div id='bd-logo' class='badge_logo' style="left:20px; top:20px;"></div>         
		  <div class="badge_text badge_text_default" id="badge_text_default" style="font-family: Arial; z-index: 9999; font-size: 6mm; color: rgb(0,0,0);">Text Line</div>		 		  	  
        </div>
      </div>
      <div class='badge_edit_buttons'>
	  	<table border="0" width="100%">
			<tr>
				<td width="68%">
					<input id='badge_add_logo' type='button' value="" alt='Add logo' />
				</td>
				<td width="21%" style="text-align:right;">
					<input id="badge_delete_text" type="button" alt="Delete" disabled="disabled" />					
				</td>
				<td width="9%" style="text-align:right;">	
					<a href="help.html?height=400&width=600" id="badge_help" title="Help" class="thickbox">
						<input id="badge_help" type="button" alt="help" />	
					</a>				
				</td>
			</tr>
			<tr>
				<td colspan="3"><div id="badge_note"></div></td>
			</tr>
		</table>
        <!--<input id='badge_add_text' type='button' value='Add text line' />-->       
      </div>
	  <br />
      <div class='badge_edit_text'>
        <!--<label for="badge_text">Entering Name / Titles</label>
        <br />
        <label for="badge_text"><small>(Click Over the text line on the badge then manually enter or paste in the names then titles)</small></label>
        <br />
        <textarea id="badge_text">Text[1] Qty[1]</textarea> <br /> -->
		<table border="0" width="100%" id="badge_container_default">		
				<tbody>
				<tr id="badge_row_title_1" class="badge_row_title"><td colspan="2" class="title">Plates</td></tr>
				<tr id="badge_row_1" class="badge_row">
					<td width="60%" valign="top">											
						
					</td>
					<td width="39%" valign="top"> 
						
					</td>
				</tr>
				</tbody>
			</table>		
		
		<div id="list_area"></div>
		
		<div id="text_list_default">Text Line 1</div>			
		<input type="button" id="text_list_edit_default" alt="Edit" />
		<input type="button" id="text_list_delete_default" class="text_list_delete" alt="Delete"/>
						
		<table border="0" width="100%">		
			<tr>
				<td width="58%" id="badge_text_container">					
					<input type="text" id="badge_text_array_default" value="Text Line 1"/>				
					<input type="text" id="badge_text_array_1" class="badge_text_array" value="Text Line 1"/>				
					<input type="text" id="badge_text_array_2" class="badge_text_array" value="Text Line 2"/>					
			  	</td>
				<td width="40%" valign="top"><input type="button" alt="Confirm" id="badge_text_confirm" />&nbsp;<input type="button" id="badge_text_add_lines" alt="Add Text Field" style="width:91px;" /></td>
			</tr>
		</table>	
		<br /><br />				
        <table border="0" width="100%">
          <tr>
            <td width="78%" style="padding:3px;"><div class='badge_add_names'>
                <input id='badge_add_names' class="badge_add_names2" type='button' alt='Upload bulk text file' />
              </div></td>            
            <td width="21%" style="padding:3px;"><div class='badge_delete_names'>
                <input id="badge_delete_names" class="badge_delete_names" type="button" alt="Delete" disabled="disabled" />
              </div></td>
          </tr>
        </table>
        <div id='badge_designer_background' class='badge_designer'>
          <div id='badge_designer' class='badge_designer'>
            <div id='names' class='badge_name' style="left:0px;"></div>		
          </div>
        </div>
        <br />
        <br />
        <label for="badge_text"><strong>Upload bulk text file</strong></label>
        <br />
        <label for="badge_text"><small>You can import (attached file) a list of names and title from Microsoft Excel, please ensure that your data is well formatted. <span style="color:red">Please make sure your list of names on your Spreadsheet match the quantity in the Shopping Cart, otherwise, you will receive name badges with no names.</span> </small></label>
        <br />
      </div>
    </div>
    <div class="content_in_tabs">
	
	   <div id="bgcolor_panel" style="display:block;">
	 
		  <center>
			<strong>Select Background Colour</strong><br />
			<div class="badge_border_selector" id="badge_border_selector">
			  <input class="background_color_selector" name="background_color_selector" id="background_color_selector_outer" value="outer" type="radio" checked>
			  <label for="background_color_selector_outer">Outer border</label>
			  <input class="background_color_selector" name="background_color_selector" id="background_color_selector_inner" value="inner" type="radio">
			  <label for="background_color_selector_inner">Inner border</label>
			</div>
		  </center>
		  <div class="tabs_navi">
			<ul class="tabs">
			  <!--<li><a class="tab_page tab_current" id="tab_rgb" href="javascript:;">Standard (RGB)</a></li>--><!-- hided for PMS task June 01 2011 -->
			  <li><a class="tab_page"             id="tab_pms" href="javascript:;">Pantone (PMS)</a></li>
			</ul>
		  </div>
		  <div class="panes">
			 <div style="display: none;" id="rgb_colors"> <span id="badge_bg_color"></span> </div> <!-- hided for PMS task June 01 2011 -->
			 <?php include DIR_WS_TEMPLATES . TEMPLATE_NAME."/bd/bg_pms_colors.php"; ?>
		  </div>
		  <div class="color_set" id="badge_bg_brush">
			<table class="preview_legend">
			  <tr>
				<td>Brushed<br />
				  Gold</td>
				<td>Brushed<br />
				  Silver</td>
			  </tr>
			  <tr>
				<td><span class="badge_bg_brush" id="badge_bg_brush_gold"   rel="gold_brush.jpg"></span></td>
				<td><span class="badge_bg_brush" id="badge_bg_brush_silver" rel="silver_brush.jpg"></span></td>
			  </tr>
			</table>
		  </div>
		  <div class="color_set" style="display:none;"> <!-- hided for PMS task June 01 2011 -->
			<label>RGB</label>
			<input class="badge_bg_color_rgb" id="badge_bg_color_rgb_r" maxlength="3" value="0" type="text">
			<b>:</b>
			<input class="badge_bg_color_rgb" id="badge_bg_color_rgb_g" maxlength="3" value="0" type="text">
			<b>:</b>
			<input class="badge_bg_color_rgb" id="badge_bg_color_rgb_b" maxlength="3" value="0" type="text">
			<b></b> 
		  </div>
		  
		  <div class="color_set">
			<label>PMS</label>
			<input class="badge_bg_color_pms" id="badge_bg_color_pms" style="width:60px;" value="White" type="text">        
		  </div>
	  
	   </div>
	 
      <div id='badge_editor' class='badge_editor' style="display:block;">
        <center>
          <strong>Select Text Settings</strong>
        </center>
        
		<div id="badge_fonts_option" style="display: block;">		
			<span>
				<label class="badge_editor_label" for="badge_font">Font</label>
				<select id="badge_font" disabled="disabled"> 	</select>
			</span> 
			<span>
				<label class="badge_editor_label" for="badge_font_size">Size</label>
				<select id="badge_font_size" disabled="disabled"> </select>					
			</span> 
		</div>	
		
		<div id="text_color_set" style="display:block;">
		
			<span id="badge_color" style="display:none;"><!-- hided for PMS task June 01 2011 -->
				<label>Color</label>
			</span>
			
			<label style="width:150px;">Font Color</label>
			<div class="panes">        	
				<?php include DIR_WS_TEMPLATES . TEMPLATE_NAME."/bd/text_pms_colors.php"; ?>
			</div>
		
			<div class="color_set" style="display:none;"> <!-- hided for PMS task June 01 2011 -->
			  <label>RGB</label>
			  <input class="badge_text_color_rgb" id="badge_text_color_rgb_r" maxlength="3" value="0" type="text" disabled="disabled">
			  <b>:</b>
			  <input class="badge_text_color_rgb" id="badge_text_color_rgb_g" maxlength="3" value="0" type="text" disabled="disabled">
			  <b>:</b>
			  <input class="badge_text_color_rgb" id="badge_text_color_rgb_b" maxlength="3" value="0" type="text" disabled="disabled">
			  <b></b> 
			</div>
			<div class="color_set">
				<label>PMS</label>
				<input id="badge_text_color_pms" style="width:60px;" value="0" type="text">        
			</div>
			
		</div>
		
      </div>
	  
    </div>
	<!-- Mar 09 2011 - add badge comment -->
	  <div style="float:right; margin:15px 0 0 0; width:230px; clear:right;">
	  	<span style="font-size:14px;">Comments about your design</span>...<br />
		<textarea name="badge_cmt" id="badge_cmt" style="width:225px; margin-top:5px; height:65px; border:1px solid #ccc;"></textarea>
	  </div>
    <!-- -->
  </div>
  
  <!-- Form -->
  <form id="badge_submit" method="post"> 
  
  <div id='badge_shapes' class='badge_shapes'> <span class='title'><!--Choose your fit:--></span>
    <div id="fits"></div>
    <div class="clear"></div>
    <br />
  </div>
  <!--  
  <h2>Preview</h2>
  <table width="100%">
    <tr>
      <td><div id="preview_legend"> </div></td>
      <td><center>
          <img id="preview_image" src="" style="display:block" />
        </center></td>
    </tr>
  </table> -->   <!--hided nov 12, 2010-->
  
 <!-- <h2>Name it and order!</h2>
  <div class="badge_box"></div>-->
  
     
    <!--
	<div class="design_name">
      <label>Enter Name for your design: </label>
      <input type='hidden' value='' name="badge_name" /> 
    </div>--><!-- Modified to hide text field May 31 2011 -->
	<input type='hidden' value='' name="badge_name" />
    <input id="continue_to_cart" value="" alt="Continue to cart" class="submit" type="submit">
    <?php if(@(int)$_GET['product_id'] > 0)echo '<input type="hidden" name="delete_product" value="'.$_GET['product_id'].'" />'; ?>
    <input type="hidden" name="category" value="<?php echo($_GET['cPath']); ?>" />
    <input type="hidden" name="fitid" id="fitid" value="0" />
    <input type="hidden" name="user_id"  value="<?php echo($_SESSION['customer_id']); ?>" />
	<input type="hidden" name="lang_id"  value="<?php echo $languages_id; ?>" />
	<input type="hidden" name="lang"  value="<?php echo $language; ?>" />
  </form>
  <input type="hidden" id="hid_1" name="hid_1" value="<?php echo OPTION_ERROR_ALERT; ?>" />
  <?php

	if ($_GET['cPath']) {

    $shapes_list = get_shapes_list($_GET['cPath'],$languages_id);

    if ($badge_data = get_badge_data(@$_GET['product_id'])) {

      	$badge_data = @json_encode(@unserialize($badge_data));
		
		if(isset($_GET["action"]) && $_GET["action"]=="re-order") {
			 //$bd_content = utf8_encode($badge_data);
     		 $badge_data = json_decode($badge_data);
			 $badge_data->texts = Array();
			 $badge_data->multiName = Array();
			 $badge_data = @json_encode($badge_data);
		} 
    }

    if (!$badge_data) {

      $badge_data = 'null';

    }

    $badge_fitting_data = get_badge_fitting_data($_GET['product_id'],$languages_id);

	//All attributes fittings
	$badge_fittings = get_badge_fittings($_GET['product_id']);
	
	 
	//print_r($badge_data);
	//exit;
	

    if ($shapes_list) {

    ?>
  <script type="text/javascript">

	$('#badge_designer').badgeDesigner( { shapes_selector: $('#badge_shapes')

                                      , submit_form: $('#badge_submit')

                                      , base_url: '<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME ;?>/bd/'

                                      , shapes_url: '<?php echo HTTP_SERVER . DIR_WS_HTTP_CATALOG . DIR_WS_IMAGES ;?>'

                                      , currentProduct: '<?php echo $cPro; ?>'

									  , badge_fitting_data: '<?php echo $badge_fitting_data ;?>'
									  
									  , badge_fittings: new Array(<?php echo $badge_fittings ;?>)

                                      , shapes: <?php echo $shapes_list ;?>

                                      , badge_data: <?php echo $badge_data; ?>
									  
									  , fonts: new Array( ''

														, 'Arial,font-family: Arial;'															

														, 'Arial Bold,font-family: Arial Bold;'

														, 'Arial Italic,font-family: Arial Italic;'														

														, 'Comic Sans MS,font-family: Comic Sans MS'

														, 'Monotype Corsiva,font-family: Monotype Corsiva'
														
														, 'Meta Roman Medium,font-family: Meta Roman Medium'															

														, 'Optima,font-family: Optima'														

														, 'Tahoma,font-family: Tahoma'
														
														, 'Times New Roman,font-family: Times New Roman'

														, 'Times New Roman Bold,font-family: Times New Roman Bold'

														, 'Times New Roman Italic,font-family: Times New Roman Italic'

														, 'Trebuchet MS,font-family: Trebuchet MS'

														, 'Trebuchet MS Bold,font-family: Trebuchet MS Bold'

														, 'Trebuchet MS Italic,font-family: Trebuchet MS Italic'

														, 'Verdana,font-family: Verdana'

														, 'Verdana Bold,font-family: Verdana Bold'

														, 'Verdana Italic,font-family: Verdana Italic'														

                                                        )

                                      
										 , fontSizes: new Array( ''

                                                            , '2.5mm,font-size:  '

                                                            , '3mm,font-size:  '

                                                            , '4.25mm,font-size:  '

                                                            , '5mm,font-size:  '
															
															, '5.5mm,font-size:  '

                                                            , '6mm,font-size:  '

                                                            , '7mm,font-size:  '

                                                            , '8mm,font-size:  '

                                                            , '9mm,font-size:  '

                                                            , '10mm,font-size:  '	
															
															, '10.25mm,font-size:  '

                                                            , '10.5mm,font-size:  '

                                                            , '11mm,font-size:  '

                                                            , '11.5mm,font-size:  '

                                                            , '12mm,font-size:  '																																
                                                            
                                                            )

                                      , colors: new Array( 'rgb(0,0,0)'

                                                         , 'rgb(255,255,255)'

                                                         , 'rgb(0,45,255)'

                                                         , 'rgb(28,191,0)'

                                                         , 'rgb(255,0,0)'

                                                         , 'rgb(128,0,0)'

														 , 'rgb(214,199,119)'

														 , 'rgb(215,215,215)'

                                                         )



                                      , bgColors: new Array( 'rgb(255,255,255)'

                                                           , 'rgb(0,0,0)'

                                                           , 'rgb(0, 0, 60)'

                                                           , 'rgb(51, 153, 153)'

                                                           , 'rgb(0, 145, 195)'

                                                           , 'rgb(0, 102, 204)'

                                                           , 'rgb(96, 142, 203)'

                                                           , 'rgb(154, 0, 17)'

                                                           , 'rgb(210, 15, 0)'

                                                           , 'rgb(255, 194, 0)'

                                                           , 'rgb(255, 255, 0)'

                                                           , 'rgb(0, 73, 58)'

                                                           , 'rgb(0, 140, 53)'

                                                           , 'rgb(0, 127, 0)'

                                                           , 'rgb(123, 0, 27)'

                                                           , 'rgb(102, 51, 51)'

                                                           , 'rgb(90, 51, 51)'

                                                           , 'rgb(86, 59, 155)'

                                                           , 'rgb(135, 70, 165)'

                                                           , 'rgb(52, 0, 87)'

                                                           , 'rgb(255, 88, 159)'

                                                           , 'rgb(119, 76, 39)'

                                                           , 'rgb(253, 223, 157)'

                                                           , 'rgb(161, 163, 164)'

                                                           , 'rgb(240, 155, 0)'

                                                           )

                                      });

	</script>
  <?php }  } ?>
  
  <style type="text/css">

.error{ color:#FF0000; padding-left:5px; font-weight:bold; }
.red_star { color:#FF0000; font-weight:bold; }
.block { display: block; }
form#badge_submit label.error { display: none; }

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
			$("#badge_submit").validate();	
	});
	                      
</script>

</div>
