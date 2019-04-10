var max_images_count = -1;
var max_lines_count = -1;
var designer;
var optionValue = '';
var badge_fitting_data_checked_value = '';

function toggleChoice(tog) { 
	if(tog != ''){
		if(optionValue) {
			optionValue += ',' + tog;
		}else {
			optionValue = tog;
		}
	}
$('#fitid').val(optionValue);
}

function toggletxtChoice(tog) {
	$('#fitid').val(tog);
}

jQuery.fn.styleSelector = function(options) {
  var styles = options.styles;
  return this.each(function() {
    // Store the input field
    var sel = this;
    /* add individual font divs to fonts */
    jQuery.each(styles, function(i, item) {     	
      // Add option to selector
      jQuery(sel).append('<option value="' + item.split(',')[0] + '" style="' + item.split(',')[1] + '" >' + item.split(',')[0] + '</option>');
            
    })
 
  });
 
}

jQuery.fn.badgeDesigner = function(options) {

  // initalization
  var designer           = $(this);

  jQuery.extend(designer, {
    currentShape: null,
    currentBorder: null,
    currentControl: null,
    currentOuterBgBrush: null,
    currentOuterBgColor: null,
	currentOuterBgColorPms: null, //April 11 2011 - for pms entry
    currentInnerBgBrush: null,
    currentInnerBgColor: null,
	currentInnerBgColorPms: null, //April 11 2011 - for pms entry
    currentBgBorder: null,
    PrepareSubmit: function() {
  
  	  var texts = new Array();
    
	 
      $('.badge_text').each(function() {
        if ($(this).attr('id') != 'badge_text_default') {
          var p = $(this).position();
          var x = p.left;
          var y = p.top;
		 
          if ($.browser.msie) {
            //x = x - 4;
            //y = y - 2;
          }
          var angle = ($(this).css('-webkit-transform')?$(this).css('-webkit-transform'):$(this).css('-moz-transform'));
          if (angle == 'none')
            angle = '';
          if ($.browser.safari && angle) {
            y = y + 33;
          }
		  	  
		    var font_type	= $(this).css('font-family');
			var font_weight = $(this).css('font-weight');
			var font_style	= $(this).css('font-style');
			
			//alert(font_weight);
			
			//Modified Sep 29, 2010
			if ($.browser.safari) {
				font_type = font_type.replace("'","");
				font_type = font_type.replace("'","");	
			}
			
			var bld = /Bold/.exec(font_type);	
			var itc = /Italic/.exec(font_type);
			
			if(bld != null || (font_weight=="bold" || font_weight=="700")) { 
				font_type = font_type.replace(" Bold","");				
				font_weight = 'bold';				
				font_style = '';			
			} else if(itc != null || font_style=="italic") { 
				font_type = font_type.replace(" Italic","");
				font_weight = 	'';				
				font_style = 'italic';		
			} else {
				font_weight = '';
				font_style = '';				
			}
			
		   //Modified Sep 24, 2010
		  //browser wise se mm value - start
		  if ($.browser.safari) {			
				curfontsize = $(this).css('font-size');
		  } 
		  else {		  
				//if ($.browser.msie || $.browser.firefox) {			
				var fsize = $(this).css("font-size");
				if(fsize.match(/mm/gi)) {
					curfontsize = $(this).css('font-size');
				}
				else {
					var newFSize = fsize.replace(/px/, "");
					var newMMSize = (newFSize*0.2645).toPrecision(3); 		
					var curfontsize = newMMSize+'';
					var numarr = curfontsize.split('.');
					sDecimal = parseFloat('.'+numarr[1]);
					
					sWhole = curfontsize - sDecimal;
									
					if(sDecimal>0 || sDecimal!="") {
						if(sDecimal<=0.3 && sDecimal>0.1) {
							sDecimal = 0.25;
						}
						else if(sDecimal>0.25 && sDecimal<0.7) {
							sDecimal = 0.5;					
						}
						else if(sDecimal>=0.71) {
							sDecimal = 0.00;
							sWhole += 1;
						}
						else if(sDecimal<=0.1) {
							sDecimal = 0.00;
						}
					}
					curfontsize = (sWhole+sDecimal)+"mm";
				}
		  }
		
		  //browser wise se mm value - end	  
		   if($(this).css('pmscolor')=="") {
			default_pms = "PMS 0";  
		  } else {
			default_pms = $(this).css('pmscolor');
		  }
		  
		    texts[texts.length] = {text: $(this).html().replace('&amp;', '&')
                                , lines: $(this).data("lines")
                                , font: font_type
                                , color: $(this).css('color')
                                , size: curfontsize 								
                                , bold: font_weight
                                , italic: font_style
                                , underline: ($(this).css('text-decoration') == 'underline')
                                , x: x
                                , y: y								
                                , angle: angle
                                , width: $(this).width()
                                , height: $(this).height()	
								, pmscolor: default_pms
                                };
								
        }
				 
		
      });
      
	  	  
      var logos = new Array();
      var multiName = new Array();
      $('.fum_image').each(function() {
        var p = $(this).parent().position();        
		logos[logos.length] = { id: $(this).attr('id')
                              , src: $(this).attr('src')
                              , width: $(this).width()
                              , height: $(this).height()
                              , x: p.left
                              , y: p.top
                              };
      });
      
	 	 	  
       $('.fum_name').each(function() {
        var p = $(this).parent().position();
        //multiName[name.length] = { id: $(this).attr('id')					
		multiName[multiName.length] = { id: $(this).attr('id')		
                              , src: $(this).attr('src')
                              , width: $(this).width()
                              , height: $(this).height()
                              , x: p.left
                              , y: p.top
                              };
      });

      var badge_data = $.toJSON({ shape: this.currentShape
                                , border: this.currentBorder
                                , outerBgColor: this.currentOuterBgColor
								, outerBgColorPms: this.currentOuterBgColorPms //April 11 2011 - for pms entry
                                , outerBgBrush: this.currentOuterBgBrush
                                , innerBgColor: this.currentInnerBgColor
								, innerBgColorPms: this.currentInnerBgColorPms //April 11 2011 - for pms entry
                                , innerBgBrush: this.currentInnerBgBrush
                                , texts: texts
                                , logos: logos
                                , multiName: multiName
                                });
                                
      $('#badge_data').val(badge_data);
	  //alert(badge_data);
      
    },
    UpdatePreview: function () {
    
      this.PrepareSubmit();

      //var src = options.base_url + 'badge_designer.php?__bdMethod=submit&badge_data=' + escape($('#badge_data').val()) + '&temp_pict=1&rnd=' + Math.random();
      var rnd = Math.random();
      var src = options.base_url + 'badge_designer.php?__bdMethod=submit&temp_pict=2&rnd=' + rnd;
      $.post( options.base_url + 'badge_designer.php'
            , { __bdMethod: 'submit'
              , temp_pict: 1
              , badge_data: $('#badge_data').val()
              , rnd: rnd
              }
            , function(response) {
                //$('#preview_image').attr('src', src);
                //$('#preview_legend').html(response);
              }
            );
      //$('#preview_image').css('display', '');
      
    } 
  });
   

  designer.everyTime(3000, 'UpdatePreview', function() { designer.UpdatePreview(); });
  
  var shapes_selector    = $(options.shapes_selector);
  var submit_form        = $(options.submit_form);
    
  jQuery.each(options.colors, function(i, item) {
    $('#badge_color').append('<div class="badge_color" style="background-color:' + item + '" rel="' + item + '"></div>');
  });
  
  jQuery.each(options.bgColors, function(i, item) {
    if (!designer.currentOuterBgColor) { 
      designer.currentOuterBgColor = item; 
      designer.currentInnerBgColor = item; 
    }
    $('#badge_bg_color').append('<div class="badge_bg_color" style="background-color:' + item + '" rel="' + item + '"></div>');
  });
   
	//Function to get absolute path
	function getAbsolutePath() {
		var loc = window.location;
		var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
		return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
    }

	
  // submit
  submit_form.attr('action', options.base_url + 'badge_designer.php');
  submit_form.append('<input id="badge_comment" name="badge_comment" type="hidden">'); //Mar 09 2011
  submit_form.append('<input id="badge_data" name="badge_data" type="hidden">');
  submit_form.append('<input id="__bdMethod" name="__bdMethod" type="hidden" value="submit">');
  
  // shapes
  var shapes = '';
  for (i=0; i < options.shapes.length; i++) {
    if (!designer.currentShape) { designer.currentShape = options.shapes[i]; }  
    shapes = shapes + '<img src="' + options.shapes_url + options.shapes[i].src + '" rel="' + options.shapes[i].productId + '" class="badge_shape" ';
    if (options.shapes[i].max_lines_count > 0) {
      if (options.shapes[i].max_lines_count > 1) {
        shapes = shapes + ' title="Suitable for 1-' + options.shapes[i].max_lines_count + ' lines of text" ';
      } else {
        shapes = shapes + ' title="Suitable for 1 line of text" ';
      }
    }
    shapes = shapes + ' >';
  }
  
  if (options.shapes.length > 1) {
    $('#multi_size').css('display', 'block');
    shapes_selector.append(shapes);
    $('.badge_shape').tipsy();
  }
    
  //shapes_selector.append(shapes);
  
  // fittings data
     var badge_fitting_data = options.badge_fitting_data.split(";");
	 
	 //All fittings // Jan 30 2013
	 var badge_fittings = options.badge_fittings; 
  
  // load settings
  if (options.badge_data) {
    if (!changeShape(options.badge_data.shape.productId, true)) {
      changeShape(designer.currentShape.productId, true);
    }
    designer.currentBgBorder = "inner";
    if (options.badge_data.innerBgBrush) {
      changeBgBrush(options.badge_data.innerBgBrush);
    } else {
      changeBgBrush(designer.currentInnerBgBrush);
    }
    if (options.badge_data.innerBgColor) {
      changeBgColor(options.badge_data.innerBgColor);
    } else {
      changeBgColor(designer.currentInnerBgColor);
    }
    designer.currentBgBorder = "outer";
    if (options.badge_data.outerBgBrush) {
      changeBgBrush(options.badge_data.outerBgBrush);
    } else {
      changeBgBrush(designer.currentOuterBgBrush);
    }
    if (options.badge_data.outerBgColor) {
      changeBgColor(options.badge_data.outerBgColor);
    } else {
      changeBgColor(designer.currentOuterBgColor);
    }
    changeBorder(options.badge_data.border.color);
		
	//Modified Oct 13, 2010
	
	//function to load text list of badges
	function funLoadText() {
			 
		bdcount = 1;
			 
		for (i=0; i < options.badge_data.texts.length; i++) {
      	
			 loadTxtArr = options.badge_data.texts[i].lines.split("\n");
					
		 	 for(j=0;j<parseInt(loadTxtArr.length)-1;j++) {
							
				badgeID = parseInt(j)+1;
				
				if(bdcount==1) {
					
					var ctxt = $('#badge_container_default').clone().appendTo("#list_area").addClass('badge_container');	
				
					ctxt.attr('id', 'badge_container_'+badgeID);			
					
					$('#badge_container_'+badgeID+" tbody").children('tr.badge_row_title').attr("id","badge_row_title_"+badgeID);
					
					$('#badge_container_'+badgeID+" tbody").children('tr.badge_row').attr("id","badge_row_"+badgeID);
					
					$('#badge_container_'+badgeID+" tbody > tr:first").children("td").html("<b>Badge "+badgeID+"</b>");
					
					
				 }
				
				clmn_1 = $('#badge_container_'+badgeID+" tbody > tr#badge_row_"+badgeID+" td:first");
					
				clmn_2 = $('#badge_container_'+badgeID+" tbody > tr#badge_row_"+badgeID+" td:last"); 
					
				listID = parseInt(i)+1;
				
				var textval = loadTxtArr[j];			
														
				if ($("#text_list_"+badgeID+"_"+listID).length) {
					
					$("#text_list_"+badgeID+"_"+listID).html(textval);					
					
				} else {
					
					currow = Math.ceil(listID/2);						
					
					var newL = $('#text_list_default').clone().appendTo(clmn_1).addClass('text_list'); 
					
					newL.attr("id", "text_list_"+badgeID+"_"+listID);
					
					newL.html(textval);
					
					if((listID%2)==0) { 
											
						$(clmn_2).append('<br class="clean">');
						
					} 
					else { 
					
						var newE = $('#text_list_edit_default').clone().appendTo(clmn_2).addClass('text_list_edit'); 
						
						newE.attr('id', 'text_list_edit_'+badgeID+"_"+currow);					
						
						var newD = $('#text_list_delete_default').clone().appendTo(clmn_2).addClass('text_list_delete'); 		
						
						newD.attr('id', 'text_list_delete_'+badgeID+"_"+currow);	  
					
					}											
					bindEvents();			 									
				}
								
			}
			
			bdcount++;
			
		 }
		 
	}
	
	 //Load text list when editing badges ***************
	funLoadText();
	
	extxt = 1; 
	
	for (i=0; i < options.badge_data.texts.length; i++) {	
			  	
	  var newT = $('#badge_text_default').clone().appendTo(designer).removeClass('badge_text_default');
      newT.attr('id', 'badge_div_array_'+extxt);
      newT.html(options.badge_data.texts[i].text);
	 	  
      if (options.badge_data.texts[i].lines) {
        newT.data('lines', options.badge_data.texts[i].lines);
      } else {
        newT.data('lines', options.badge_data.texts[i].text);
      }
      newT.css('left', options.badge_data.texts[i].x);
      newT.css('top', options.badge_data.texts[i].y);

	  if (options.badge_data.texts[i].angle) {       
        newT.css('-webkit-transform', 'rotate(-38deg)');
        newT.css('-moz-transform', 'rotate(-38deg)');
      }
      newT.css('color', options.badge_data.texts[i].color);	  
      newT.css('font-size', options.badge_data.texts[i].size);
	  
      newT.css('font-family', options.badge_data.texts[i].font);
      if (options.badge_data.texts[i].bold) {
        newT.css('font-weight', 'bold');
      }
	  
      if (options.badge_data.texts[i].italic) {
        newT.css('font-style', 'italic');
      }
	  
      if (options.badge_data.texts[i].underline) {
        newT.css('text-decoration', 'underline');
      }
	 
	 //Retrieve text fields on editing
	  if (!$("#badge_text_array_"+extxt).length) {			  
		ctxt = $('#badge_text_array_default').clone().appendTo("#badge_text_container").addClass('badge_text_array');	
		ctxt.attr('id', 'badge_text_array_'+extxt);	
		ctxt.val("");
	  } else {
			$("#badge_text_array_"+extxt).val("");  
	  }
		  
      bindEvents();  
	  
	  extxt = extxt + 1;  
    } //end of text lines on editing 
  
	for (i=0; i < options.badge_data.logos.length; i++) {
		  var newL = $('#logo').clone(true);		  
		  $(newL).attr('id', ''); 
		  $(newL).css('left', options.badge_data.logos[i].x); 
		  $(newL).css('top', options.badge_data.logos[i].y); 
		  $(newL).insertAfter($('#logo'));
		  $(newL).attr('title', 'Click and Drag to move logo and position mouse over bottom right hand corner to adjust size');
		  $(newL).tipsy();
		  $(newL).html('<img id="' + options.badge_data.logos[i].id + '" ' + 
					   'class="fum_image" ' + 
					   'width="' + options.badge_data.logos[i].width + '" '+
					   'height="' + options.badge_data.logos[i].height + '" ' +
					   'border="0" '+
					   'rel="$(\'#' + options.badge_data.logos[i].id + '\').remove();" ' +
					   'src="' + options.shapes_url + 'users_badges/' + options.badge_data.logos[i].src + '"/>');
		
    }
	
	
	//Modified oct 08, 2010
	for (i=0; i < options.badge_data.multiName.length; i++) {
		  var newF = $('#names').clone(true);
		  //Modified Oct 11, 2010 - below 2 lines added
		  var temp = new Array();
		  temp = options.badge_data.multiName[i].id.split("-fn-");
		  var temp_1 = new Array();
		  temp_1 = temp[1].split('_xn');
		  
		  $(newF).attr('id', 'names-'+i); 
		  $(newF).css('left', options.badge_data.multiName[i].x); 
		  $(newF).css('top', options.badge_data.multiName[i].y); 
		  $(newF).insertAfter($('#names'));
		  $(newF).attr('title', temp_1[0] + "." + temp_1[1]);
		  $(newF).tipsy();		  
		  
		  $(newF).html('<img id="' + options.badge_data.multiName[i].id + '" ' + 
					   'class="fum_name" ' + 
					   'width="' + options.badge_data.multiName[i].width + '" '+
					   'height="' + options.badge_data.multiName[i].height + '" ' +
					   'border="0" '+
					   'rel="$(\'#' + options.badge_data.multiName[i].id + '\').remove();" ' +
					   'src="' + options.shapes_url + 'users_names/' + options.badge_data.multiName[i].id + "." + temp_1[1] + '"/><div style="cursor:pointer;"><b>File Uploaded : </b>' + temp_1[0] + "." + temp_1[1] + '</div>'); 
		  
    }
	
		
  } else {
    changeShape(designer.currentShape.productId, true);
    if ($("#badge_border_selector").css('display') != 'none') {
		  designer.currentBgBorder = "inner";
		  changeBgBrush(designer.currentInnerBgBrush);
		  changeBgColor(designer.currentInnerBgColor);
	}
    designer.currentBgBorder = "outer";
    changeBgBrush(designer.currentOuterBgBrush);
    changeBgColor(designer.currentOuterBgColor);
  }
  
  attachFileUploader({ baseUrl: options.base_url
                     , selectorId: 'badge_add_logo'
                     , containerId: 'logo'
                     , imagesLimit: 0
                     , imageCheck: true
                     , hideLegend: true
                     , tinyMode: true
                     , makeThumb: false
                     , renderUploaded: false
                     });

  attachNameUploader({ baseUrl: options.base_url
                     , selectorId: 'badge_add_names'
                     , containerId: 'names'					 
                     , imagesLimit: 0
                     , imageCheck: true
                     , hideLegend: true
                     , tinyMode: true
                     , makeThumb: false
                     , renderUploaded: false
                     });                     
                     
  $('#ajax_upload').css({width: 36, height: 35});
  
  bindEvents();
  bindDragEvents('badge_logo');

  $('#badge_font').styleSelector({styles: options.fonts});
  $('#badge_font_size').styleSelector({styles: options.fontSizes});

  function changeShape(productId, initial) {//src, ml, mi, pid) {
  	
    var newShape = null;
    
    for (i=0; i < options.shapes.length; i++) {
      if (options.shapes[i].productId == productId) {
        newShape = options.shapes[i];
        break;
      }
    }
    
	
	
	
    if (newShape) {
		
      max_images_count   = parseInt(newShape.max_images_count);
      max_lines_count    = parseInt(newShape.max_lines_count);
      var badge_product_name 	= jQuery.trim(newShape.productName);
	  var texts_lines    = $(".badge_text[id!='badge_text_default']");
      
      if (!options.badge_data && (texts_lines.length == 0) && (newShape.default_texts.length > 0)) {
        
	  //function to load default text already present in badge_data
	  function funLoadNewshape() {
			 
		 badgeID = 1;	bdcount = 1;	 	
				
		 for(j=0;j < newShape.default_texts.length;j++) {
					
			if(bdcount==1) {
				
				var ctxt = $('#badge_container_default').clone().appendTo("#list_area").addClass('badge_container');	
			
				ctxt.attr('id', 'badge_container_'+badgeID);			
				
				$('#badge_container_'+badgeID+" tbody").children('tr.badge_row_title').attr("id","badge_row_title_"+badgeID);
				
				$('#badge_container_'+badgeID+" tbody").children('tr.badge_row').attr("id","badge_row_"+badgeID);
				
				$('#badge_container_'+badgeID+" tbody > tr:first").children("td").html("<b>Badge "+badgeID+"</b>");
				
				
			 }
			
			clmn_1 = $('#badge_container_'+badgeID+" tbody > tr#badge_row_"+badgeID+" td:first");
				
			clmn_2 = $('#badge_container_'+badgeID+" tbody > tr#badge_row_"+badgeID+" td:last"); 
				
			listID = parseInt(j)+1;
			
			var textval = newShape.default_texts[j].text;			
									
			if ($("#text_list_"+badgeID+"_"+listID).length) {
				
				$("#text_list_"+badgeID+"_"+listID).html(textval);					
				
			} else {
				
				currow = Math.ceil(listID/2);						
				
				var newL = $('#text_list_default').clone().appendTo(clmn_1).addClass('text_list'); 
				
				newL.attr("id", "text_list_"+badgeID+"_"+listID);
				
				newL.html(textval);
				
				if((listID%2)==0) { 
										
					$(clmn_2).append('<br class="clean">');
					
				} 
				else { 
				
					var newE = $('#text_list_edit_default').clone().appendTo(clmn_2).addClass('text_list_edit'); 
					
					newE.attr('id', 'text_list_edit_'+badgeID+"_"+currow);					
					
					var newD = $('#text_list_delete_default').clone().appendTo(clmn_2).addClass('text_list_delete'); 		
					
					newD.attr('id', 'text_list_delete_'+badgeID+"_"+currow);	  
				
				}											
				bindEvents();			 									
			}
							
			bdcount++;
		  }
				
	   }
	
	   for (i=0; i < newShape.default_texts.length; i++) {
		   
			  var newT = $('#badge_text_default').clone().appendTo(designer).removeClass('badge_text_default');         
			  newT.attr('id', 'badge_div_array_'+(i+1));		  		  		  		  
			  newT.html(newShape.default_texts[i].text);
			  newT.data('lines', newShape.default_texts[i].text);
			  newT.css('left', newShape.default_texts[i].x + 'px');
			  newT.css('top', newShape.default_texts[i].y + 'px');
			  if (newShape.default_texts[i].angle) {
					newT.css('-webkit-transform', 'rotate(-' + newShape.default_texts[i].angle + 'deg)');
					newT.css('-moz-transform', 'rotate(-' + newShape.default_texts[i].angle + 'deg)');
			  }
			  if (newShape.default_texts[i].color) {
					newT.css('color', newShape.default_texts[i].color);
			  }
			  if (newShape.default_texts[i].size) {
					newT.css('font-size', newShape.default_texts[i].size);			
			  }
			  if (newShape.default_texts[i].font) {
					newT.css('font-family', newShape.default_texts[i].font);
			  }
			  if (newShape.default_texts[i].bold) {
					newT.css('font-weight', 'bold');
			  }          
			  bindEvents();
		 
        }		
		//call function to assin default text list
		funLoadNewshape();
		
      } 
	  else {
		  
        if ((max_lines_count > -1) && (texts_lines.length > max_lines_count)) {
			
          for (i = max_lines_count; i < texts_lines.length; i++) {
			  
            $(texts_lines[i]).remove();
          }
		  
        }
		
      }
      
	  
    function white_space(field)
	{
		 field.value = (field.value).replace(/^\s*|\s*$/g,'');
	}
	
	for( var i = 0; i < badge_fitting_data.length; i++ ) {
		  if(jQuery.trim(badge_product_name) == jQuery.trim(badge_fitting_data[i])){
			var badge_fitting_data_checked = jQuery.trim(badge_fitting_data[i+1]);
		  }
	}
	
	// Option Length for Text box
	if(newShape.fittes_option_length != 0){
		var fitt_length 	= newShape.fittes_option_length;
		var fitt_txt_col_length = newShape.fittes_option_length;
		var fitt_txt_row_length = '';
	}else{
		var fitt_length 	= '';
		var fitt_txt_col_length = '100';
		var fitt_txt_row_length = '';
	}
          
      var images = $(".badge_logo[id!='logo']");
      
      if ((max_images_count > -1) && (images.length > max_images_count)) {
        for(i=0;i<(images.length - max_images_count);i++){      	
          $(images[i]).remove();   
        }
      }
      
	  
	  /* Show all selected products options - Start */
	  
	  // Displaying Fitting Dynamically
	  // 0 - Drop Down
	  // 1 - Text Box
	  // 2 - Radio Button
	  // 3 - Check Box
	  // 4 - Textarea
	
	  //alert(badge_fittings.length);
	  
	  var option_error_alert = $("#hid_1").val();
	  
	  var po_text = '<table width="100%" border="0"><tr><td><b>Choose your options:</b></td></tr> <tr> <td>';
	  
	  po_text += '<table width="100%">';
	  for (i in newShape.opts) {
				
		//newShape.opts[i][0] - Options id
		//newShape.opts[i][1] - Options Name
		//newShape.opts[i][2] - Options type
		//newShape.opts[i][3] - Options mandatory
		
		//newShape.optval[j][0] - Options id
		//newShape.optval[j][1] - values id
		//newShape.optval[j][2] - Options values name
		//newShape.optval[j][3] - price
		//newShape.optval[j][4] - picture
						
		po_text += '<tr><td><p>&nbsp;</p>';
		if(newShape.opts[i][3]=='1') { po_text += '<span class="red_star">*</span>'; }
		po_text += '<b>'+newShape.opts[i][1]+'</b>';
		
		if(newShape.opts[i][3]=='1' && (newShape.opts[i][2]=='0' || newShape.opts[i][2]=='2')) { 
			po_text += '<label for="id['+newShape.opts[i][0]+']" class="error"> ' + option_error_alert +newShape.opts[i][1]+'</label>';
		} else if(newShape.opts[i][3]=='1' && (newShape.opts[i][2]=='1' || newShape.opts[i][2]=='4' || newShape.opts[i][2]=='5')) {
			po_text += '<label for="id['+newShape.opts[i][0]+'][t]" class="error"> ' + option_error_alert +newShape.opts[i][1]+'</label>';
		}		
		
		po_text += '</td></tr><tr>';
				
		switch (newShape.opts[i][2])
		{
		 case "0":
			
			//pre check option	
			checkd_opt = "";			
			if(badge_fittings.length>-1) {				
				for (n in badge_fittings) {								
					if(badge_fittings[n][2]=="0" && badge_fittings[n][0]==newShape.opts[i][0]) {
						checkd_opt = badge_fittings[n][1];
					}
				}
			}
				
			po_text += '<td style="border-bottom:1px solid #CCC;"><table width="100%" border="0"><tr>';
			
			po_text += '<td align="center" valign="top" ><select name="id['+newShape.opts[i][0]+']" style="width:200px;" onchange="javascript:toggletxtChoice(this.value);"';
			
			if(newShape.opts[i][3]=='1') { po_text += ' validate="required:true" '; } //Mandatory
			
			po_text += '>';
			
			for (j in newShape.optval) {
												
				if(newShape.optval[j][0] == newShape.opts[i][0]) {
					po_text += '<option value="'+newShape.optval[j][1]+'"';					
					if(newShape.optval[j][1]==checkd_opt) {
						po_text += ' selected ';
					}					
					po_text += '>'+ newShape.optval[j][2] + '</option>';
				}
				
			}
			po_text += '</select></td>';
			
			po_text += '</tr></table></td>';
			
		break;
			 
		 case "1":			
			
			//pre check option
			checkd_opt = "";
			if(badge_fittings.length>-1) {				
				for (n in badge_fittings) {								
					if(badge_fittings[n][2]=="1" && badge_fittings[n][0]==newShape.opts[i][0]) {
						checkd_opt = badge_fittings[n][3];
					}
				}
			}
				
			po_text += '<td style="border-bottom:1px solid #CCC;"><table><tr>';
			
			po_text += '<td align="center" valign="top"><input type="text" name="id['+newShape.opts[i][0]+ '][t]"';
			
			po_text += ' value="'+checkd_opt+'" ';
			
			po_text += ' maxlength="'+fitt_length+'" onblur="javascript:toggletxtChoice(this.value);"';
			
			if(newShape.opts[i][3]=='1') { po_text += ' validate="required:true" '; } //Mandatory
			
			po_text += '/></td>';
			
			po_text += '</tr></table></td>';
			
		break;
		
		case "2":
										
			
			po_text += '<td style="border-bottom:1px solid #CCC;"><table><tr>';
			
			//pre check option				
			checkd_opt = "";
			if(badge_fittings.length>-1) {				
				for (n in badge_fittings) {								
					if(badge_fittings[n][2]=="2" && badge_fittings[n][0]==newShape.opts[i][0]) {
						checkd_opt = badge_fittings[n][1];
					}
				}
			}
				
			for (j in newShape.optval) {
							
				if(newShape.optval[j][0] == newShape.opts[i][0]) {
									
					po_text += '<td align="center" valign="top"><input ';
					
					if(newShape.optval[j][1]==checkd_opt) {
						po_text += ' checked ';
					} 
					else if(jQuery.trim(badge_fitting_data_checked)==jQuery.trim(newShape.optval[j][2])) {
						po_text += ' checked ';
					}	
					else if(newShape.optval[j][1]=="1") { //select by default Brooch
						po_text += ' checked ';
					}
					
					po_text += ' onclick="javascript:toggletxtChoice(this.value);" type="radio" name="id['+newShape.opts[i][0]+']" value="'+newShape.optval[j][1]+'"';
			
					if(newShape.opts[i][3]=='1') { po_text += ' validate="required:true" '; } //Mandatory
			
					po_text += '><br /><span>'+newShape.optval[j][2] + '<br />';
					
					if (newShape.optval[j][3] != '0.00') {
						po_text += '('+newShape.optval[j][3]+' incl GST)';
					}
					po_text += '<br />';
					
					if (newShape.optval[j][4].length > 0){
						
						po_text += '<img width="80" src="images/product_attributes/'+newShape.optval[j][4]+'" alt="'+newShape.optval[j][4]+'"></span>';
					}  	
					po_text += '</td>';
				
				}
			} // eof for
			
			po_text += '</tr></table></td>';
			
		break;
		case "3":
						
			po_text += '<td style="border-bottom:1px solid #CCC;"><table><tr>';
						
			//pre check option	
			checkd_opt = "";			
			if(badge_fittings.length>-1) {				
				for (n in badge_fittings) {								
					if(badge_fittings[n][2]=="3" && badge_fittings[n][0]==newShape.opts[i][0]) {
						checkd_opt = badge_fittings[n][1];
					}
				}
			}
						
			for (j in newShape.optval) {
				
				if(newShape.optval[j][0] == newShape.opts[i][0]) {
				
					po_text += '<td align="center" valign="top"><input ';
					
					if(jQuery.trim(badge_fitting_data_checked) == jQuery.trim(newShape.optval[j][1])){
							po_text += 'checked="checked" ';
					}else if (j == 0) {
							po_text += 'checked="checked" ';
					}
					
					po_text += ' onclick="javascript:toggleChoice(this.value);" type="checkbox" name="id['+newShape.opts[i][0]+'][c]['+newShape.optval[j][1]+']" value="'+newShape.optval[j][1]+'"';
			
					if(newShape.opts[i][3]=='1') { po_text += ' validate="required:true" '; } //Mandatory
			
					po_text += '><br /><label>'+newShape.optval[j][2] + '<br />';
			
					if (newShape.optval[j][3] != '0.00') {
						po_text += '('+newShape.optval[j][2]+' incl GST)';
					}
					po_text += '<br />';
					
					if (newShape.optval[j][4].length > 0){
						po_text += '<img width="80" src="images/product_attributes/'+newShape.optval[j][4]+'"></label>';
					}  	
					po_text += '</td>';
				}
				
			} // eof for 
			
			po_text += '</tr></table></td>';
			
			
		break;
		
		case "4":
			
			//pre check option				
			checkd_opt = "";
			if(badge_fittings.length>-1) {				
				for (n in badge_fittings) {								
					if(badge_fittings[n][2]=="4" && badge_fittings[n][0]==newShape.opts[i][0]) {
						checkd_opt = badge_fittings[n][3];
					}
				}
			}
			
			po_text += '<td style="border-bottom:1px solid #CCC;"><table><tr>';
			
			po_text += '<td align="center" valign="top"><textarea name="id['+newShape.opts[i][0]+'][t]"  rows="'+fitt_txt_row_length+'" cols="'+fitt_txt_col_length+'" wrap="virtual" style="width:100%;" onblur="javascript:toggletxtChoice(this.value);"';
			
			if(newShape.opts[i][3]=='1') { po_text += ' validate="required:true" '; } //Mandatory
			
			po_text += '>';
			
			po_text += checkd_opt;
			
			po_text +='</textarea></td>';
			
			po_text += '</tr></table></td>';
			
		break;
		
		case "5":
			
			//pre check option
			checkd_opt = "";			
			if(badge_fittings.length>-1) {				
				for (n in badge_fittings) {								
					if(badge_fittings[n][2]=="5" && badge_fittings[n][0]==newShape.opts[i][0]) {
						checkd_opt = badge_fittings[n][3];
					}
				}
			}
			
			po_text += '<td style="border-bottom:1px solid #CCC;"><table><tr>';
			
			po_text += '<td align="center" valign="top"><input type="file" name="id['+newShape.opts[i][0]+'][t]" style="margin-bottom:4px;" ';
			
			if(newShape.opts[i][3]=='1') { po_text += ' validate="required:true" '; } //Mandatory
			
			po_text += '/><br>'+checkd_opt+'</td>';
			
			//po_text += '<td align="center" valign="top"><input type="file" name="id['+newShape.opts[i][0]+'][t]" style="margin-bottom:4px;" /><br /><textarea name="comment['+newShape.opts[i][0]+']"  style="width:210px; margin-bottom:4px;"></textarea></td>';
			
			po_text += '</tr></table></td>';
			
		break;
		
		} // eof switch
		
		po_text += "</tr>";
				
	  }
	  po_text += '</table>';
	  po_text += '</td></tr></table>';
	  
	  //alert(po_text);
	  
	  /* Show all selected products options - End */
	  
      var chbtextt = '<table><tr>';
	// Displaying Fitting Dynamically
	// 0 - Drop Down
	// 1 - Text Box
	// 2 - Radio Button
	// 3 - Check Box
	// 4 - Textarea
	
      switch (newShape.fittes_option_type)
		{
		 case 0:
			chbtextt += '<select name="fit" onchange="javascript:toggletxtChoice(this.value);">';
			for (i in newShape.fittes) {
				chbtextt += '<option value="'+newShape.fittes[i][2]+'">'+ newShape.fittes[i][0] + '</option>';
			} // eof for 
			chbtextt += '</select>';
			 break;
		 case 1:
			chbtextt += '<td align="center" valign="top"><input type="text" name="fit" value="" id="fit_0" maxlength="'+fitt_length+'" onblur="javascript:toggletxtChoice(this.value);"/></td>';
			 break;
		case 2:
			//chbtextt += '<td align="center" valign="top"><input type="radio" onclick="javascript:toggletxtChoice(0);" name="fit" value="0" id="fit_0"><br /> &nbsp; <label for="fit_0">No fitting</label> &nbsp; </td>';
			
			for(var k=0; k<newShape.fittes.length; k++) {
				if(jQuery.trim(badge_fitting_data_checked) == jQuery.trim(newShape.fittes[k][0])){
					badge_fitting_data_checked_value = newShape.fittes[k][2];
				} 
			}
			
			for (i in newShape.fittes) {
	      		//var optionType = Number(newShape.fittes[i][4]);
				chbtextt += '<td align="center" valign="top"><input ';
					if(jQuery.trim(badge_fitting_data_checked) == jQuery.trim(newShape.fittes[i][0])){
						chbtextt += 'checked="checked" ';
					}else if (i == 0) {
					   if(badge_fitting_data_checked_value != ''){
					    toggletxtChoice(badge_fitting_data_checked_value);
					   }else{
					    toggletxtChoice(newShape.fittes[i][2]); //Mar 31 2011 - added
						chbtextt += 'checked="checked" ';
					   }
                    }
				chbtextt += ' onclick="javascript:toggletxtChoice(this.value);" type="radio" name="fit" value="'+newShape.fittes[i][2]+'" id="fit_'+(i+1)+'"><br /><label for="fit_'+(i+1)+'">'+newShape.fittes[i][0] + '<br />';
					if (newShape.fittes[i][1] != '0.00') {
					chbtextt += '('+newShape.fittes[i][1]+' incl GST)';
				}
				chbtextt += '<br />';
				
				if (newShape.fittes[i][3].length > 0){
					//added sep 16, 2010
					chbalttxt = "";
					if(newShape.fittes[i][3]=="attribute_image_12.png") {
						chbalttxt = "Oval School Badge Brooch";
					}
					chbtextt += '<img width="120" src="images/product_attributes/'+newShape.fittes[i][3]+'" alt="'+chbalttxt+'"></label>';
				}  	
				chbtextt += '</td>';
			} // eof for 
		break;
		 case 3:
			//chbtextt += '<td align="center" valign="top"><input type="checkbox" onclick="javascript:toggleChoice(0);" name="fit" value="0" id="fit_0"><br /> &nbsp; <label for="fit_0">No fitting</label> &nbsp; </td>';
			for (i in newShape.fittes) {
			chbtextt += '<td align="center" valign="top"><input ';
				if(jQuery.trim(badge_fitting_data_checked) == jQuery.trim(newShape.fittes[i][0])){
				chbtextt += 'checked="checked" ';
			    }else if (i == 0) {
          			chbtextt += 'checked="checked" ';
                 }
			chbtextt += ' onclick="javascript:toggleChoice(this.value);" type="checkbox" name="fit" value="'+newShape.fittes[i][2]+'" id="fit_'+(i+1)+'"><br /><label for="fit_'+(i+1)+'">'+newShape.fittes[i][0] + '<br />';
				if (newShape.fittes[i][1] != '0.00') {
				chbtextt += '('+newShape.fittes[i][1]+' incl GST)';
			}
			chbtextt += '<br />';
			
			if (newShape.fittes[i][3].length > 0){
				chbtextt += '<img width="120" src="images/product_attributes/'+newShape.fittes[i][3]+'"></label>';
			}  	
			chbtextt += '</td>';
			} // eof for 
		break;
		 case 4:
			chbtextt += '<td align="center" valign="top"><textarea name="fit"  id="fit_0" rows="'+fitt_txt_row_length+'" cols="'+fitt_txt_col_length+'" wrap="virtual" style="width:100%;" onblur="javascript:toggletxtChoice(this.value);"></textarea></td>';
			 break;
		} // eof switch 
	///// eof Displaying Fitting Dynamically
      
      chbtextt += '</tr></table>';

      //$('#fitid').val('0');
      //$('#fits').html(chbtextt);  //Jan 29 2013
	  $('#fits').html(po_text);   		

      var oldShape = designer.currentShape;
      
      designer.currentShape = newShape;
      
      if (initial || (designer.currentShape != oldShape)) {
      
        $('.badge_shape').removeClass('badge_shape_selected');
        
        $('.badge_shape').each(function() {
          if ($(this).attr('rel') == designer.currentShape.productId) {
            $(this).addClass('badge_shape_selected');
          }
        });
        
        $('#badge_border_colors').html("");
        
        var newBorder = null;
        
        if (designer.currentShape.borders.length > 1) {            
          jQuery.each(designer.currentShape.borders, function(i, item) {
            if (designer.currentBorder && (designer.currentBorder.color == item.color)) {
              newBorder = designer.currentBorder.color;
            }
            $('#badge_border_colors').append('<div title="' + item.color + '" class="badge_border_color" style="background-color:' + item.color + '" rel="' + item.color + '"></div>');
          });
          
          $('.badge_border_color' ).tipsy();
          $('.badge_border_color' ).bind("click", function(event) {
            
            changeBorder($(this).attr('rel'));
            //designer.UpdatePreview();
          });
          $('#badge_border_color').css('display', 'inline')
        } else {
          $('#badge_border_color').css('display', 'none');
        }
        
        if (!newBorder) {
          newBorder = designer.currentShape.borders[0].color;
        }
        
        changeBorder(newBorder);
      }

      return true;
    } else {
      return false;
    }
    //designer.UpdatePreview();   
  }
  
  $('.tab_page').bind("click", function(event) {
    if ($(this).attr('id') == 'tab_rgb') {
      $('#pms_colors').css('display','none');$('#rgb_colors').css('display','block');
    } else {
      $('#rgb_colors').css('display','none');$('#pms_colors').css('display','block');
    }
    $('.tab_page').removeClass('tab_current');
    $(this).addClass('tab_current');
  });
  
  
  $('.badge_shape').bind("click", function(event) {  	
    changeShape($(this).attr('rel'));		
	
	//Modified Oct 13, 2010
	funBadgeNote(max_lines_count); //change badge note
	
	txtlen = $(".badge_text_array").length;
	//$("#badge_text_add_lines").attr("disabled","");	
		
	
	if(max_lines_count!=-1 && txtlen > max_lines_count) {
		for(i=txtlen;i>max_lines_count;i--) {
			$("#badge_text_array_"+i).remove();
			$("#badge_div_array_"+i).remove();
			
		} 
	}
	
	$('.badge_container').each(function() {
									
			var tcnt = 1; var curtxtval = "";	 	
			
			badgeID = funGetID($(this).attr("id"));
					
			if ($("#badge_container_"+badgeID).length) {
					
			  curlist = $('#badge_container_'+badgeID+' tbody > tr:nth-child(2) td:first').children().size();	
			  //alert(max_lines_count);
			  for(i=curlist;i>max_lines_count;i--) {
				  //alert(i);
					
					$("#text_list_"+badgeID+"_"+i).remove("");
					//$("#text_list_"+badgeID+"_"+i).removeClass("text_list");
					
					ccid = Math.ceil(i/2);	  
					if((i%2)==0) { 
						var prevID = parseInt(i)-1;
						
						if(($("#text_list_"+badgeID+"_"+i).length==0 && $("#text_list_"+badgeID+"_"+prevID).length==0)) {
							$("#text_list_"+badgeID+"_"+i).remove();						
						} 
						
					} else {
						var nextID = parseInt(i)+1;
						if(($("#text_list_"+badgeID+"_"+i).length==0 && ($("#text_list_"+badgeID+"_"+nextID).html()=="" || $("#text_list_"+badgeID+"_"+nextID).length==0)) ) {
							$("#text_list_edit_"+badgeID+"_"+ccid).remove();	
							$("#text_list_delete_"+badgeID+"_"+ccid).next(".clean").remove();
							$("#text_list_delete_"+badgeID+"_"+ccid).remove();	
							$("#text_list_"+badgeID+"_"+i).remove();
						}
					}
					
					
					
					
					
					
				/*	ccid = Math.ceil(cID/2);	  
		if((cID%2)==0) { 
	  		var prevID = parseInt(cID)-1;
			
			if(($("#text_list_"+cID).html()=="" && $("#text_list_"+prevID).html()=="") || max_lines_count==1) {
				$("#text_list_"+cID).remove();						
			} 
		
	  } 
	  else { 
	  		var nextID = parseInt(cID)+1;	
			
			if(($("#text_list_"+cID).html()=="" && ($("#text_list_"+nextID).html()=="" || $("#text_list_"+nextID).length==0)) || max_lines_count==1) {
				$("#text_list_"+cID).remove();
				$("#text_list_"+nextID).remove();
				$("#text_list_delete_"+ccid).remove();
				$("#text_list_edit_"+ccid).remove();
				$(".clean").remove();				
				$('#badge_text_array_'+cID).attr("disabled","");									 
				$('#badge_text_array_'+nextID).attr("disabled","");									 
			
			}
	  }	 */ 
					
					
					
					
					
					
					
			  }
			}
		});	
	
  });
  
  $('.background_color_selector').bind("click", function(event) {  	
    designer.currentBgBorder = $(this).val();
    if (designer.currentBgBorder == 'inner') {
      $('#badge_bg_brush').css('display', 'none');
    } else {
      $('#badge_bg_brush').css('display', 'inline');
    }
    CopyBgColorToRGB();
  });
    
  submit_form.bind("submit", function(event) {
  
    designer.PrepareSubmit();
    
  });
  
  function controlInDesigner(x, y, width, height) {
  
    return ((x >= designer.currentBorder.padding) &&
            (y >= designer.currentBorder.padding) &&
            (x <= designer.currentBorder.width-designer.currentBorder.padding) && 
            (y <= designer.currentBorder.height-designer.currentBorder.padding) && 
            (x + width <= designer.currentBorder.width-designer.currentBorder.padding) &&
            (y + height <= designer.currentBorder.height-designer.currentBorder.padding)
           );
           
  }
  
  function changeBorder(color) {

    if (color) {  
      var oldBorder = designer.currentBorder;
      
      for (i = 0; i < designer.currentShape.borders.length; i++) {
        if (designer.currentShape.borders[i].color == color) {
          designer.currentBorder = designer.currentShape.borders[i];
          if (designer.currentBorder != oldBorder) {

            $('.badge_border_color' ).removeClass('badge_selected');
            
            $('.badge_border_color').each(function() {
              if ($(this).attr('rel') == color) {
                $(this).addClass('badge_selected');
              }
            });
          
            if (oldBorder) {
              if ((designer.currentBorder.width < oldBorder.width) || (designer.currentBorder.height < oldBorder.height)) {
                var coefw = oldBorder.width/designer.currentBorder.width;
                var coefh = oldBorder.height/designer.currentBorder.height;
                
                function relocateControl(ctrl) {
                  var p = $(ctrl).position();
                  if (!controlInDesigner(p.left, p.top, $(ctrl).width(), $(ctrl).height())) {
                    var newP = $(ctrl).position();
                    if (designer.currentBorder.width < oldBorder.width) {
                      newP.left = Math.round(p.left/coefw-$(ctrl).width()/2/coefw);
                      if (newP.left < 0)
                        newP.left = 0;
                    }
                    if (designer.currentBorder.height < oldBorder.height) {
                      newP.top = Math.round(p.top/coefh - $(ctrl).height()/2/coefh);
                      if (newP.top < 0)
                        newP.top = 0;
                    }
                    moveControl($(ctrl), newP.left, newP.top);
                  } 
                }
                
                $('.badge_text').each(function() { relocateControl(this); });
                $('.badge_logo').each(function() { relocateControl(this); });
              }  
            }

            designer.css('background-repeat', 'no-repeat');
            designer.css('width', designer.currentBorder.width);
            designer.css('height', designer.currentBorder.height);
            designer.css('background-image', 'url(' + options.shapes_url + designer.currentBorder.src + ')');

            var designer_background = $('#badge_designer_background');
            designer_background.css('width', designer.currentBorder.width);
            designer_background.css('height', designer.currentBorder.height);
            
            if (designer.currentBorder.src_inner) {
              designer.append('<div id="designer_inner"></div>');
              
              var designer_inner = $('#designer_inner');
              designer_inner.css('position', 'absolute');
              designer_inner.css('background-repeat', 'no-repeat');
              designer_inner.css('left', designer.position().left + designer.currentBorder.width/2 - designer.currentBorder.width_inner/2);
              designer_inner.css('top', designer.position().left + designer.currentBorder.height/2 - designer.currentBorder.height_inner/2);
              designer_inner.css('width', designer.currentBorder.width_inner);
              designer_inner.css('height', designer.currentBorder.height_inner);
              designer_inner.css('background-image', 'url(' + options.shapes_url + designer.currentBorder.src_inner + ')');
            } else {
              $("#badge_border_selector").css('display', 'none');
              $("#designer_inner").css('display', 'none');
            }
            
          }
        }
      }
    }
  
  }
            
  function selectBadgeText(ctrl) {
    
	$('.badge_color').removeClass('badge_selected');

    if (designer.currentControl) {
      designer.currentControl.removeClass('badge_selected');
    }
  
    if (ctrl) {
    
      $('#badge_text').attr('disabled', '');
      
      $('#badge_text').val($(ctrl).data("lines"));
      
      $('#badge_font').attr('disabled', '');
      $('#badge_font option').each(function() {		
        //Modified Sep 28, 2010
		var curfont = $(this).css('font-family');
		var ctrlfont = $(ctrl).css('font-family');
		
		//condition added to show selected font bold/italic - June 07 2011
		if (($(ctrl).css('font-weight') == 'bold') || ($(ctrl).css('font-weight') == '700')) {			
			ctrlfont = ctrlfont + " Bold";
		} else if (($(ctrl).css('font-style') == 'italic')) {			
			ctrlfont = ctrlfont + " Italic";
		} 
		
		ctrlfont = ctrlfont.replace('"',""); ctrlfont = ctrlfont.replace('"',"");		
		ctrlfont = ctrlfont.replace("'",""); ctrlfont = ctrlfont.replace("'","");
		curfont = curfont.replace('"',"");	curfont = curfont.replace('"',"");
		curfont = curfont.replace("'",""); curfont = curfont.replace("'","");
		
		if (curfont == ctrlfont) {			
          $(this).attr('selected', 'selected');
        } else {
          $(this).attr('selected', '');
        }
      });
      
      $('#badge_font_size').attr('disabled', '');
	  
	  
	  //Modified Sep 24, 2010
	  //browser wise se mm value - start
	  if ($.browser.safari) {			
			$("#badge_font_size option[value=" + $(ctrl).css('font-size') +"]").attr('selected', 'selected');			
	  } 
	  else {
	  
	  		//if ($.browser.msie || $.browser.firefox) {			
			var cfsize = $(ctrl).css("font-size");		 
		    var cnewFSize = cfsize.replace(/px/, "");
		    var cnewMMSize = (cnewFSize*0.2645).toPrecision(3); 		
			var fnt_size = cnewMMSize+'';
			var numarr = fnt_size.split('.');
			sDecimal = parseFloat('.'+numarr[1]);
			sWhole = fnt_size - sDecimal;
						
			if(sDecimal>0 || sDecimal!="") {
				if(sDecimal<=0.3 && sDecimal>0.1) {
					sDecimal = 0.25;
				}
				else if(sDecimal>0.25 && sDecimal<0.7) {
					sDecimal = 0.5;					
				}
				else if(sDecimal>=0.71) {
					sDecimal = 0.00;
					sWhole += 1;
				}
				else if(sDecimal<=0.1) {
					sDecimal = 0.00;
				}
			}
			fnt_size = (sWhole+sDecimal)+"mm";
			//alert(fnt_size);
			$("#badge_font_size option[value=" + fnt_size +"]").attr('selected', 'selected');			
 	  }
	          
	  //$('#badge_font_size option:contains(' + $(ctrl).css('font-size') + ')').attr('selected', 'selected');
	  
	  //browser wise se mm value - end	  
	      
      $('.badge_color').each(function() {
        if (($(this).css('background-color') == $(ctrl).css('color')) || 
            ($(this).attr('rel') == $(ctrl).css('color')) ||
            ($(this).attr('rel') == $(ctrl).attr('rel'))
            ) {
          $(this).addClass('badge_selected');
        }
      });
  
      $('#badge_font_bold').attr('disabled', '');
      $('#badge_font_bold').attr('checked', '')
      if (($(ctrl).css('font-weight') == 'bold') || ($(ctrl).css('font-weight') == '700')) {  // 700 - for IE8
        $('#badge_font_bold').attr('checked', 'checked')
      }

      $('#badge_font_italic').attr('disabled', '');
      $('#badge_font_italic').attr('checked', '')
      if ($(ctrl).css('font-style') == 'italic') {
        $('#badge_font_italic').attr('checked', 'checked')
      }

      $('#badge_font_underline').attr('disabled', '');
      $('#badge_font_underline').attr('checked', '')
      if ($(ctrl).css('text-decoration') == 'underline') {
        $('#badge_font_underline').attr('checked', 'checked')
      }
      
      $('#badge_delete_text').attr('disabled', '');
      $('#badge_delete_names').attr('disabled', '');

      $('.badge_text_color_rgb').attr('disabled', '');
      CopyTextColorToRGB($(ctrl));

      designer.currentControl = $(ctrl);
      designer.currentControl.addClass('badge_selected');
      renderControlPos(designer.currentControl);
      
    } else {
    
      disableBadgeEditor();      
      designer.currentControl = null;
      $('#badge_position').html('&nbsp;');
      
    }    
    
  }

  function CopyTextColorToRGB(ctrl) {
  
    var str = $(ctrl).css('color');
    if (str.indexOf('#') == 0) {
      $('#badge_text_color_rgb_r').val(parseInt(str.substring(1, 2), 16));
      $('#badge_text_color_rgb_g').val(parseInt(str.substring(3, 4), 16));
      $('#badge_text_color_rgb_b').val(parseInt(str.substring(5, 6), 16));
    } else {
      var r = parseInt(str.substring(str.indexOf('(')+1, str.indexOf(',')));
      var tmp = str.substring(str.indexOf(',')+1);
      var g = parseInt(tmp.substring(0, tmp.indexOf(',')));
      tmp = tmp.substring(tmp.indexOf(',')+1);
      var b = parseInt(tmp.substring(0, tmp.indexOf(')')));
      $('#badge_text_color_rgb_r').val(r);
      $('#badge_text_color_rgb_g').val(g);
      $('#badge_text_color_rgb_b').val(b);
    }
    
  }
  
  function CopyBgColorToRGB() {
  
    if (designer.currentBgBorder == 'outer') {
      var str = designer.currentOuterBgColor;
    } else {
      var str = designer.currentInnerBgColor;
    }
    if (str.indexOf('#') == 0) {
      $('#badge_bg_color_rgb_r').val(parseInt(str.substring(1, 2), 16));
      $('#badge_bg_color_rgb_g').val(parseInt(str.substring(3, 4), 16));
      $('#badge_bg_color_rgb_b').val(parseInt(str.substring(5, 6), 16));
    } else {
      var r = parseInt(str.substring(str.indexOf('(')+1, str.indexOf(',')));
      var tmp = str.substring(str.indexOf(',')+1);
      var g = parseInt(tmp.substring(0, tmp.indexOf(',')));
      tmp = tmp.substring(tmp.indexOf(',')+1);
      var b = parseInt(tmp.substring(0, tmp.indexOf(')')));
      $('#badge_bg_color_rgb_r').val(r);
      $('#badge_bg_color_rgb_g').val(g);
      $('#badge_bg_color_rgb_b').val(b);
    }
    
  }
  
  function renderControlPos(ctrl) {
  
    $('#badge_position').html('X: ' + $(ctrl).position().left + ', Y: ' + $(ctrl).position().top);
    
  }
    
  function moveControl(ctrl, x, y, absolute) {
  
    $(ctrl).css({left: x, top: y});
    renderControlPos(ctrl);
    
  }
  
  function bindDragEvents(className) {  
  
    var sensitivity = 4;
    
    $('.' + className ).bind("mousemove", function(event) {
      if (($(this).children().length > 0)) { // image inside badge_logo div
        var dsgnP = $(designer).parent().position();
        var P = $(this).position();
        var newP = $(this).position();
        var pw = Math.round(($(this).outerWidth() - $(this).width()) / 2);
        var ph = Math.round(($(this).outerHeight() - $(this).height()) / 2);
        newP.left = event.pageX - dsgnP.left;
        var angle = ($(this).css('-webkit-transform')?$(this).css('-webkit-transform'):$(this).css('-moz-transform'));
        if (angle == 'none')
          angle = '';
        if ($.browser.safari && angle) {
          newP.top = event.pageY - dsgnP.top + 33;
        } else {
          newP.top = event.pageY - dsgnP.top;
        }
        //$('#debug').html('Designer left: ' + dsgnP.left + ', offsetX: ' + event.offsetX + ', new left: ' + newP.left + ', new top: ' + newP.top + ', text width: ' + $(this).width() + ', text height: '+ $(this).height());
        if ((Math.abs((P.left + $(this).width() + pw) - newP.left) <= sensitivity) && (Math.abs((P.top + $(this).height() + ph) - newP.top) <= sensitivity)) {
          $(this).css('cursor', 'move');
        } else {
          $(this).css('cursor', 'pointer');
        }  
      }  
    });

    $('.' + className ).bind("dragstart", function(event) {
      if (($(this).children().length > 0)) { // image inside badge_logo div
        $(this).attr('iwidth',  $(this).width());
        $(this).attr('iheight', $(this).height());
        var dsgnP = $(designer).parent().position();
        var P = $(this).position();
        var newP = $(this).position();
        var pw = Math.round(($(this).outerWidth() - $(this).width()) / 2);
        var ph = Math.round(($(this).outerHeight() - $(this).height()) / 2);
        newP.left = event.pageX - dsgnP.left;
        var angle = ($(this).css('-webkit-transform')?$(this).css('-webkit-transform'):$(this).css('-moz-transform'));
        if (angle == 'none')
          angle = '';
        if ($.browser.safari && angle) {
          newP.top = event.pageY - dsgnP.top + 33;
        } else {
          newP.top = event.pageY - dsgnP.top;
        }
        if ((Math.abs((P.left + $(this).width() + pw) - newP.left) <= sensitivity) && (Math.abs((P.top + $(this).height() + ph) - newP.top) <= sensitivity)) {
          $(this).attr('dmode', 'resize');
        } else {
          $(this).attr('dmode', 'move');
        }  
      }  
      return $(this).animate({opacity: .6});
    }).bind('drag', function(event) {
      var dsgnP = $(designer).parent().position();
      var P = $(this).position();
      var newP = $(this).position();
      newP.left = event.offsetX - dsgnP.left;
      var angle = ($(this).css('-webkit-transform')?$(this).css('-webkit-transform'):$(this).css('-moz-transform'));
      if (angle == 'none')
        angle = '';
      if ($.browser.safari && angle) {
        newP.top = event.offsetY - dsgnP.top + 33;
      } else {
        newP.top = event.offsetY - dsgnP.top;
      }
      newW = newP.left - P.left;
      newH = newP.top - P.top;
      if ($(this).attr('dmode') == 'resize') {
        $($(this).children()[0]).css({width: parseInt($(this).attr('iwidth')) + newW, height: parseInt($(this).attr('iheight')) + newH});
      } else {
        //$('#debug').html('Designer left: ' + dsgnP.left + ', offsetX: ' + event.offsetX + ', new left: ' + newP.left + ', new top: ' + newP.top + ', text width: ' + $(this).width() + ', text height: '+ $(this).height());
        if (controlInDesigner(newP.left, newP.top, $(this).width(), $(this).height())) {
          moveControl($(this), newP.left, newP.top);
        } 
      }  
      return $(this);
    }).bind("dragend", function(event) {
    	//designer.UpdatePreview();
    $('#badge_position').html('&nbsp;');
      return $(this).animate({opacity: 1});
    });
    
  }  

  function bindEvents() {
  
    bindDragEvents('badge_text');
    $('.badge_text' ).bind("click", function(event) {
      selectBadgeText($(this));
    });
    
  }
  
  function disableBadgeEditor() {
  
    $('#badge_text').val('');
    $('#badge_font').attr('disabled', 'disabled');
    $('#badge_font_size').attr('disabled', 'disabled');
    $('#badge_font_bold').attr('disabled', 'disabled');
    $('#badge_font_italic').attr('disabled', 'disabled');
    $('#badge_font_underline').attr('disabled', 'disabled');
    $('#badge_delete_text').attr('disabled', 'disabled');
    $('#badge_delete_names').attr('disabled', 'disabled');
    $('.badge_text_color_rgb').attr('disabled', 'disabled');
    
  }
    
  $('.badge_logo' ).bind("click", function(event) {
  
    if (designer.currentControl) {
      designer.currentControl.removeClass('badge_selected');
    }
    disableBadgeEditor();

    designer.currentControl = $(this);//.children()[0]);
    designer.currentControl.addClass('badge_selected');
    renderControlPos(designer.currentControl);

    $('#badge_position').html('X: ' + designer.currentControl.position().left + ', Y: ' + designer.currentControl.position().top);
    $('#badge_delete_text').attr('disabled', ''); 
    $('#badge_delete_names').attr('disabled', '');
    
  });
  
  //Modified 0ct 08, 2010
    $('.badge_name' ).bind("click", function(event) {
  
		if (designer.currentControl) {
		  designer.currentControl.removeClass('badge_selected');
		}
		disableBadgeEditor();
	
		designer.currentControl = $(this);//.children()[0]);
		designer.currentControl.addClass('badge_selected');
		renderControlPos(designer.currentControl);
	
		$('#badge_position').html('X: ' + designer.currentControl.position().left + ', Y: ' + designer.currentControl.position().top);
		$('#badge_delete_names').attr('disabled', '');
		
	});
  
 
  //For Text field addition
  $('#badge_text_add_lines' ).bind("click", function(event) {
													 
    var texts_lines = $(".badge_text_array[id!='badge_text_default']");	
	var current_lines = texts_lines.length;
	var txttop = texts_lines.length + 1;
	
	if (((max_lines_count > -1) && (current_lines < max_lines_count)) || max_lines_count == -1) {		
		
		if((current_lines%2)==0) { numline = 2; } else { numline = 1; } //restrict added text lines
		
		for(i=1;i<=numline;i++) {			
			if (((max_lines_count > -1) && (current_lines < max_lines_count)) || max_lines_count == -1) {
				ctxt = $('#badge_text_array_default').clone().appendTo("#badge_text_container").addClass('badge_text_array');	
				ctxt.attr('id', 'badge_text_array_'+txttop);
				ctxt.attr('value', 'Text Line '+txttop);				
				
				txttop = txttop + 1;
				current_lines = current_lines+1;
			}			
		}	
		
	} else {		
		alert('You can not add more text lines into this badge');	
		return false;		
	}
	
  });
  
  
  //For update all Text Lines
   $('#badge_text_confirm').bind("click", function(event) {
		
		$("#badge_text_add_lines").attr("disabled","disabled");	
		
		badge_count = 0;
		
		$('.badge_container').each(function() {
											
			badge_count = funGetID($(this).attr("id"));								
			
		});
				
		//call add text list function
		funAddBadge(parseInt(badge_count)+1);
			
		//call update badge function
		funUpdateBadge();
    	
  });
  

//function to get element id value

function funGetID(idstring) {
	
	var id = idstring.lastIndexOf("_")+1;	
	
	return idstring.substring(id);
	
}


//Add badge and assaign id

function funAddBadge(idvalue) {
					
	var ctxt = $('#badge_container_default').clone().appendTo("#list_area").addClass('badge_container');	
	
	ctxt.attr('id', 'badge_container_'+idvalue);			
	
	$('#badge_container_'+idvalue+" tbody").children('tr.badge_row_title').attr("id","badge_row_title_"+idvalue);
	
	$('#badge_container_'+idvalue+" tbody").children('tr.badge_row').attr("id","badge_row_"+idvalue);
	
	$('#badge_container_'+idvalue+" tbody > tr:first").children("td").html("<b>Badge "+idvalue+"</b>");
	
	clmn_1 = $('#badge_container_'+idvalue+" tbody > tr#badge_row_"+idvalue+" td:first");
	
	clmn_2 = $('#badge_container_'+idvalue+" tbody > tr#badge_row_"+idvalue+" td:last");	
	
	index = 1;
	
	listcount = 1;
				
	$('.badge_text_array').each(function() {										 
		
		var textval = $("#badge_text_array_"+listcount).val();			
												
		if ($("#text_list_"+idvalue+"_"+listcount).length) {
			
			$("#text_list_"+idvalue+"_"+listcount).html(textval);					
			
		} else {
			
			currow = Math.ceil(listcount/2);						
			
			var newL = $('#text_list_default').clone().appendTo(clmn_1).addClass('text_list'); 
			
			newL.attr("id", "text_list_"+idvalue+"_"+listcount);
			
			newL.html(textval);
			
			if((listcount%2)==0) { 
			
				brid = "br_list_"+idvalue+"_"+currow;
				
				$(clmn_2).append('<br class="clean" id="'+brid+'"');
				
			} 
			else { 
			
				var newE = $('#text_list_edit_default').clone().appendTo(clmn_2).addClass('text_list_edit'); 
				
				newE.attr('id', 'text_list_edit_'+idvalue+"_"+currow);					
				
				var newD = $('#text_list_delete_default').clone().appendTo(clmn_2).addClass('text_list_delete'); 		
				
				newD.attr('id', 'text_list_delete_'+idvalue+"_"+currow);	  
			
			}											
			bindEvents();			 
			
			newL.click();			
		}	
		
		$("#badge_text_array_"+listcount).val("");
		
		listcount = listcount + 1;
		
	});	
	
}

//update badge design with listed text

function funUpdateBadge() {

	var bdcunt = 1;		
		
	$('.badge_container').each(function() {
									
		var tcnt = 1; var curtxtval = "";	 	
		
		badgeID = funGetID($(this).attr("id"));
				
		if ($("#badge_container_"+badgeID).length) {
				
		  $('#badge_container_'+badgeID+' tbody > tr:nth-child(2) td:first').children().each(function() {	
			
			var txtarr = ""; 
						
			curtxtval = $("#text_list_"+badgeID+"_"+tcnt).html();
			
			if(bdcunt==1) {
				
				if ($("#badge_div_array_"+tcnt).length) {
									
					$("#badge_div_array_"+tcnt).html(curtxtval);							
					
				} else {			
					
					if((tcnt%2)==0) { topvar = 2; } else { topvar = 1; }
					
					var newT = $('#badge_text_default').clone().appendTo(designer).removeClass('badge_text_default');		 
					newT.attr('id', 'badge_div_array_'+tcnt);		 
					newT.data('index', tcnt);					
					newT.html(curtxtval);						
					newT.css('left', designer.width()/2 - newT.width()/2);				 
					newT.css('top', (designer.height()/4 - newT.height()/4)+topvar*25);				 
					bindEvents();			 
					newT.click();			
					
				}
				
				$(".badge_container tbody > tr:nth-child(2) td:nth-child(1) div:nth-child("+tcnt+")").each(function() {
																												
					txtarr += $(this).html()+"\n";				
					
				});				
								
				$("#badge_div_array_"+tcnt).data("lines",txtarr);
				
			}			
		
			tcnt = tcnt + 1;
			
		});			
	
		bdcunt++;
		
	  }
		
	});	
		
}

//update text list inline
$('.update_text_confirm').live("click",function(event) {												  
 	
	var idstring = $(this).attr("id");	
	
	var ids = new Array();
	
	ids = idstring.split('_');
	
	badge_id = ids[3];
	
	update_id = ids[4];

	//clmn_2 = $('#badge_container_'+badge_id+" tbody > tr#badge_row_"+badge_id+" td:last");
		
	edit_element_id = 'text_list_edit_'+badge_id+"_"+update_id;
	del_element_id = 'text_list_delete_'+badge_id+"_"+update_id;
	
	rep_content = '<input type="button" alt="Edit" id="'+edit_element_id+'" class="text_list_edit"><input type="button" alt="Delete" id="'+del_element_id+'" class="text_list_delete">'; 
	
	$("#update_text_confirm_"+badge_id+"_"+update_id).replaceWith(rep_content);
	
					
	j = 1;
	
	for(i=(update_id*2);i>0;i--) {
		
		if(j<=2) {	
		
			text_val = $("#update_text_array_"+badge_id+"_"+i).val();
						
			prev_value = "text_list_"+badge_id+"_"+i;
			
			$("#update_text_array_"+badge_id+"_"+i).replaceWith('<div class="text_list" id="'+prev_value+'">'+text_val+'</div>');
			
		}
		
		j++;
		
	}	
	
	//call badge update function
	funUpdateBadge();
	
});



//edit text list
$('.text_list_edit').live("click",function(event) {												  
 	
	var idstring = $(this).attr("id");	
	
	var ids = new Array();
	
	ids = idstring.split('_');
	
	badge_id = ids[3];
	
	edit_id = ids[4];
	
	//replace current buttons
	
	btn_confirm = "update_text_confirm_"+badge_id+"_"+edit_id;
	
	btn_add = '<input type="button" alt="Confirm" id="'+btn_confirm+'" class="update_text_confirm" />';
	
	$("#text_list_delete_"+badge_id+"_"+edit_id).remove(); //remove delete on edit
	
	$(this).replaceWith(btn_add); //replace edit by confirm button
    
	j = 1;
	
	for(i=(edit_id*2);i>0;i--) {
		
		if(j<=2) {			
			text_to_add = "update_text_array_"+badge_id+"_"+i;
			
			prev_value = $("#text_list_"+badge_id+"_"+i).html();
			
			$("#text_list_"+badge_id+"_"+i).replaceWith('<input type="text" id="'+text_to_add+'" class="update_text_array" value="'+prev_value+'"/>');
			
		}
		
		j++;
		
	}	
	
 });
 
 
 $('.text_list_delete').live("click",function(event) {											  
 		
	var idstring = $(this).attr("id");	
	
	var ids = new Array();
	
	ids = idstring.split('_');
	
	badge_id = ids[3];
	
	delete_id = ids[4];		
					
	$("#text_list_edit_"+badge_id+"_"+delete_id).remove();	
	$("#text_list_delete_"+badge_id+"_"+delete_id).next(".clean").remove();
	$("#text_list_delete_"+badge_id+"_"+delete_id).remove();					
	
	j=1;		
	
	for(i=(delete_id*2);i>0;i--) {			
	
		if(j<=2) {								
			$("#text_list_"+badge_id+"_"+i).html("");	
			$("#text_list_"+badge_id+"_"+i).removeClass("text_list");							
		}	
				
		j++;
		
	}	
	//delete badge
	funDeleteBadge(badge_id);	
	
	if($('.badge_container').length==0) {
		$("#badge_text_add_lines").attr("disabled","");	
	}
	
 });


//function to delete badge
function funDeleteBadge(badge_id) {
		
		clmn_1 = $('#badge_container_'+badge_id+" tbody > tr#badge_row_"+badge_id+" td:first");
		empty_list = 0;
		txtlist = 1;
		clmn_1.children().each(function() {										
			if($("#text_list_"+badge_id+"_"+txtlist).html()=="" && !($("#text_list_"+badge_id+"_"+txtlist).hasClass(".text_list"))) {
				empty_list++;
			} 
			txtlist++;
		});
		if(clmn_1.children().size()==empty_list) {
			$('#badge_container_'+badge_id).remove();
		}
			
		if($(".text_list").length==0) {
			 txt_len = $(".badge_text").length;			 
			 for(i=1;i<txt_len;i++) {
				$("#badge_div_array_"+i).remove();	
			 }
		}
	//call badge update function
	funUpdateBadge();
}

function funBadgeNote(max_lines_count) {
	
	if(max_lines_count==-1) {		
		
		badgeNote = "This badge can accommodate unlimited lines of Text. Please ensure you have entered all required details before you press &quot;Confirm&quot; option. You cannot add more text lines once you confirm the Badge Design by clicking the Confirm button.";
		
	} else {
		
		badgeNote = "This badge can only accommodate "+max_lines_count+" lines of Text. Please ensure you have entered all required details before you press &quot;Confirm&quot; option. You cannot add more text lines once you confirm the Badge Design by clicking the Confirm button.";
		
	}
	
	
	$("#badge_note").html(badgeNote);	
}

//Create text lines on page load
jQuery(document).ready(function($) {
	
	funBadgeNote(max_lines_count);
	
	//if(!options.badge_data) {	
		 var texts_lines = $(".badge_text_array[id!='badge_text_default']");					
		 if (((max_lines_count > -1) && (texts_lines.length > max_lines_count))) {
			$("#badge_text_array_"+texts_lines.length).remove();
			$("#badge_div_array_"+texts_lines.length).remove();
		 }
	//} //check options set or not
		
});

	
 
  
  //Function for delete Text Lines
  $('#badge_delete_text' ).bind("click", function(event) {  
    if (designer.currentControl) {
      if ((designer.currentControl.children().length > 0))// { // image inside badge_logo div
        if ($(designer.currentControl.children()[0]).attr('rel'))
          eval($(designer.currentControl.children()[0]).attr('rel'));
		  
		var cur_id = designer.currentControl.attr("id");
		
		cID = funGetID(cur_id);
		
	  	designer.currentControl.remove();
	  
	  	$('.badge_container').each(function() {
			badgeID = funGetID($(this).attr("id"));					
			if ($("#badge_container_"+badgeID).length) {			 					
				$("#text_list_"+badgeID+"_"+cID).html("");
			  	//$("#text_list_"+cID).removeClass("text_list");	 
			}
		});	
	  
		
      selectBadgeText(null);
    } 
    
  });
  
  //Function for delete uploaded files 
  $('#badge_delete_names' ).bind("click", function(event) {
  	  //alert(designer.currentControl.attr("id"));
	  if (designer.currentControl) {
        	bdname = designer.currentControl.attr("id").split("-");
			if(bdname[0]=="names") {
				designer.currentControl.remove();
	  		}
        }  
  });


  $('#badge_font').bind("change", function(event) {
  
    if (designer.currentControl) {
      	  
	  designer.currentControl.css('font-weight', "");
	  designer.currentControl.css('font-style', "");  
	  fontFamily = $(this).val();
	  var need_quote = 1;
	  
	  var bld = /Bold/.exec($(this).val());	
	  var itc = /Italic/.exec($(this).val());
	  
	  if(bld != null) {        
		need_quote = 0;
		designer.currentControl.css('font-weight', "bold");
		designer.currentControl.css('font-style', ""); 
		fontFamily = $(this).val().replace(" Bold","");
	  } else if(itc != null) { 
	     need_quote = 0;
		 designer.currentControl.css('font-weight', "");
		 designer.currentControl.css('font-style', "italic");		 
		 fontFamily = fontFamily.replace(" Italic","");
	  } 
	 
	  //fontFamily = $(this).val().replace("/Bold/","");
	 // fontFamily = fontFamily.replace("Italic","");
	  //alert(fontFamily);
	  //if(need_quote==1) {		  
		  //designer.currentControl.css('font-family', '"'+fontFamily+'"');      		
	  //} else {
		  designer.currentControl.css('font-family', fontFamily);	
	  //}
    }  
    
  });

  $('#badge_font_size' ).bind("change", function(event) {
  
    if (designer.currentControl) {
      designer.currentControl.css('font-size', $(this).val());	  
      //designer.UpdatePreview();	 
    }  
    
  });

  $('.badge_color' ).bind("click", function(event) {
  
    if (designer.currentControl) {
      designer.currentControl.css('color', $(this).css('background-color'));
      designer.currentControl.attr('rel', $(this).attr('rel'));
      $('.badge_color' ).removeClass('badge_selected');
      $(this).addClass('badge_selected');
      CopyTextColorToRGB(designer.currentControl);
      //designer.UpdatePreview();
    }  
    
  });
  
  //PMS text color - June 01 2011
  $('.badge_text_color_pms' ).bind("click", function(event) {  
    if (designer.currentControl) {
      designer.currentControl.css('color', $(this).css('background-color'));
	  designer.currentControl.css('pmscolor', $(this).attr('title'));
      designer.currentControl.attr('rel', $(this).css('background-color'));	  	  
      $('.badge_text_color_pms' ).removeClass('badge_selected');
      $(this).addClass('badge_selected');  
      CopyTextColorToRGB(designer.currentControl);
	  pms_val = $(this).attr('title');
	  
	  $('#badge_text_color_pms').val(pms_val.replace("PMS ",""));
    }
  });
  
  $('.badge_text_color_rgb' ).bind("keyup", function(event) {
    if (designer.currentControl) {
      var newColor = null;
      if (event.keyCode == 38) {
        var newValue = parseFloat($(this).val()) + 1;
        if ((newValue >=0) && (newValue <=255)) {
          $(this).val(newValue);
          newColor = 'rgb(' + $('#badge_text_color_rgb_r').val() + ','  + $('#badge_text_color_rgb_g').val() + ','  + $('#badge_text_color_rgb_b').val() + ')';
        }
      }
      if (event.keyCode == 40) {
        var newValue = parseFloat($(this).val()) - 1;
        if ((newValue >=0) && (newValue <=255)) {
          $(this).val(newValue);
          newColor = 'rgb(' + $('#badge_text_color_rgb_r').val() + ','  + $('#badge_text_color_rgb_g').val() + ','  + $('#badge_text_color_rgb_b').val() + ')';
        }
      }
      if (((event.keyCode >= 48) && (event.keyCode <= 57)) || ((event.keyCode >= 96) && (event.keyCode <= 105)) || (event.keyCode == 8) || (event.keyCode == 46)) {
        if ($(this).val()) {
          newColor = 'rgb(' + $('#badge_text_color_rgb_r').val() + ','  + $('#badge_text_color_rgb_g').val() + ','  + $('#badge_text_color_rgb_b').val() + ')';
        }
      }
      if (newColor) {
        designer.currentControl.css('color', newColor);
        designer.currentControl.attr('rel', null);
        $('.badge_color' ).removeClass('badge_selected');
      }  
    }  
    //designer.UpdatePreview();
  });
  
  $('.badge_bg_color_rgb' ).bind("keyup", function(event) {
    var newColor = null;
    if (event.keyCode == 38) {
      var newValue = parseFloat($(this).val()) + 1;
      if ((newValue >=0) && (newValue <=255)) {
        $(this).val(newValue);
        newColor = 'rgb(' + $('#badge_bg_color_rgb_r').val() + ','  + $('#badge_bg_color_rgb_g').val() + ','  + $('#badge_bg_color_rgb_b').val() + ')';
      }
    }
    if (event.keyCode == 40) {
      var newValue = parseFloat($(this).val()) - 1;
      if ((newValue >=0) && (newValue <=255)) {
        $(this).val(newValue);
        newColor = 'rgb(' + $('#badge_bg_color_rgb_r').val() + ','  + $('#badge_bg_color_rgb_g').val() + ','  + $('#badge_bg_color_rgb_b').val() + ')';
      }
    }
    if (((event.keyCode >= 48) && (event.keyCode <= 57)) || ((event.keyCode >= 96) && (event.keyCode <= 105)) || (event.keyCode == 8) || (event.keyCode == 46)) {
      if ($(this).val()) {
        newColor = 'rgb(' + $('#badge_bg_color_rgb_r').val() + ','  + $('#badge_bg_color_rgb_g').val() + ','  + $('#badge_bg_color_rgb_b').val() + ')';
      }
    }
    if (newColor) {
      changeBgColor(newColor);
    }  
    //designer.UpdatePreview();
  });
  
  
  //PMS code custom input for background color
  $('.badge_bg_color_pms' ).bind("keyup", function(event) {
    var newColor = null;      
	user_pms = $(this).val().toLowerCase(); 	
	$('.badge_color_pms').each(function() {				
		
		rgb_colors = $(this).css('background-color');
		pms_codes = $(this).attr("title").toLowerCase();
		if(pms_codes.replace("pms ","")==user_pms) {
			newColor = rgb_colors;		
			if (newColor) {
			  //alert(newColor);
			  changeBgColor(newColor);
			}
			return false;
		}

	});
	
  });
  
  //PMS for badge text color   
  $('#badge_text_color_pms').bind("keyup", function(event) {
	
	user_pms = $(this).val().toLowerCase(); 	
	//alert(user_pms);
	if (designer.currentControl) {
		
		var newColor = null;      
		
		$('.badge_text_color_pms').each(function() {				
			
			rgb_colors = $(this).css('background-color');
			pms_codes = $(this).attr("title").toLowerCase();
			if(pms_codes.replace("pms ","")==user_pms) {
				newColor = rgb_colors;		
				if (newColor) {
					//alert(newColor);
					designer.currentControl.css('color', newColor);
					designer.currentControl.css('pmscolor', $(this).attr("title"));
					designer.currentControl.attr('rel', null);
					$('.badge_color' ).removeClass('badge_selected');
				}  
				return false;
			}
	
		});
		
	}
	
  });
    
  
  function changeBgColor(color) {
  
    if (color) {
      if (designer.currentBgBorder == 'outer') {
        designer.css('background-color', color);
        designer.parent().css('background-repeat', '');
        designer.parent().css('background-position', '');
        designer.parent().css('background-image', '');
      } else {
        var designer_inner = $('#designer_inner');
        designer_inner.css('background-color', color);
      }
      $('.badge_bg_color' ).removeClass('badge_selected');
      if (designer.currentBgBorder == 'outer') {
        designer.currentOuterBgBrush = '';
        designer.currentOuterBgColor = color;
      } else {
        designer.currentInnerBgBrush = '';
        designer.currentInnerBgColor = color;
      }
      CopyBgColorToRGB();
    }
    //designer.UpdatePreview();
  }

  function changeBgBrush(brush) {
  
    if (brush && (designer.currentBgBorder == 'outer')) {
      designer.css('background-color', '');
      designer.parent().css('background-repeat', 'no-repeat');
      designer.parent().css('background-position', 'center');
      designer.parent().css('background-image', 'url(' + options.base_url + 'images/' + brush + ')');
      $('.badge_bg_color_brush' ).removeClass('badge_selected');
      $('.badge_bg_color_brush').each(function() {
        if ($(this).attr('rel') == brush) {
          $(this).addClass('badge_selected');
        }
      });
      designer.currentOuterBgBrush = brush;
      designer.currentOuterBgColor = '';
    }
    //designer.UpdatePreview();
  }
  
  $('.badge_bg_color' ).bind("click", function(event) {
    
    changeBgColor($(this).attr('rel'));
    //designer.UpdatePreview();
	//April 08 2011 - we have added below if else to set PMS value as empty.
		if(designer.currentBgBorder == 'outer') {
			designer.currentOuterBgColorPms = '';
		} else {
			designer.currentInnerBgColorPms = '';
		}
  });

  $('.badge_bg_brush' ).bind("click", function(event) {
    
    changeBgBrush($(this).attr('rel'));
    //designer.UpdatePreview();
  });
  
  $('.badge_color_pms' ).bind("click", function(event) {
  
    changeBgColor($(this).css('background-color'));
    //designer.UpdatePreview();
	//April 08 2011 - we have added below if else to set selected color title for PMS
		if(designer.currentBgBorder == 'outer') {
			designer.currentOuterBgColorPms = $(this).attr('title');
		} else {
			designer.currentInnerBgColorPms = $(this).attr('title');
		}
		
		pms_val = $(this).attr('title'); //PMS June 01 2011		
		$('#badge_bg_color_pms').val(pms_val.replace("PMS ","")); //PMS June 01 2011
    
  });  
  
  $('#badge_font_bold' ).bind("click", function(event) {
  
    if (designer.currentControl) {
      if ($(this).attr('checked')) {
        designer.currentControl.css('font-weight', 'bold');
        $(this).attr('class', 'checkboxOn');
      } else {
        designer.currentControl.css('font-weight', 'normal');
        $(this).attr('class', 'checkboxOff');
      }
      //designer.UpdatePreview();      
    }  
    
  });

  $('#badge_font_italic' ).bind("click", function(event) {
  
    if (designer.currentControl) {
      if ($(this).attr('checked')) {
        designer.currentControl.css('font-style', 'italic');
        $(this).attr('class', 'checkboxOn2');
      } else {
        designer.currentControl.css('font-style', 'normal');
        $(this).attr('class', 'checkboxOff2');
      } 
      //designer.UpdatePreview();     
    }  
    
  });

  $('#badge_font_underline' ).bind("click", function(event) {
  
    if (designer.currentControl) {
      if ($(this).attr('checked')) {
        designer.currentControl.css('text-decoration', 'underline');
        $(this).attr('class', 'checkboxOn3');
      } else {
        designer.currentControl.css('text-decoration', 'none');
        $(this).attr('class', 'checkboxOff3');
      }  
      //designer.UpdatePreview();    
    }  
    
  });
  
  //Mar 09 2011 - add badge comment
	$('#badge_cmt' ).bind("keyup", function(event) {
		badge_cmt = $('#badge_cmt').val();
		$('#badge_comment').val(badge_cmt);		
	});

}


/*
__bdMethod  submit
badge_data  {"shape":{"productId":1,"src":"size15x46.png","max_images_count":1,"max_lines_count":1,"fittes":[["Brooch Fitting","0.0000","163"],["Alligator Fitting","1.6600","162"],["Magnetic Fitting","1.3200","164"]],"borders":[{"src":"black-size15x46.png","src_med":"s_black-size15x46.png","color":"black","width":471,"height":99,"padding":8},{"src":"blue-size15x46.png","src_med":"s_blue-size15x46.png","color":"blue","width":471,"height":99,"padding":8},{"src":"green-size15x46.png","src_med":"s_green-size15x46.png","color":"green","width":471,"height":99,"padding":8},{"src":"red-size15x46.png","src_med":"s_red-size15x46.png","color":"red","width":471,"height":99,"padding":8},{"src":"white-size15x46.png","src_med":"s_white-size15x46.png","color":"white","width":471,"height":99,"padding":8},{"src":"maroon-size15x46.png","src_med":"s_maroon-size15x46.png","color":"maroon","width":471,"height":99,"padding":8}]},"border":{"src":"black-size15x46.png","src_med":"s_black-size15x46.png","color":"black","width":471,"height":99,"padding":8},"bgColor":"rgb(255,255,255)","texts":[{"text":"John Doe","font":"Times New Roman","color":"rgb(0, 0, 0)","size":"24px","bold":false,"italic":false,"underline":false,"x":86,"y":22}],"logos":[]}
badge_name  
category  27
fitid 0
user_id 

* */