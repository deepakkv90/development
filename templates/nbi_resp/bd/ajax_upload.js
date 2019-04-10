var counter_img = -2;
var max_files_count = 1;

function attachFileUploader(options) {

  var selectorId     = options.selectorId;
  var containerId    = options.containerId;
  var extensions     = options.extensions;
  var sessionId      = options.sessionId?options.sessionId:'fum_session';
  var thumbWidth     = options.thumbWidth?options.thumbWidth:200;
  var thumbHeight    = options.thumbHeight?options.thumbHeight:80;
  var makeThumb      = options.makeThumb?1:0;
  var imageCheck     = options.imageCheck?1:0;
  var renderUploaded = options.renderUploaded==undefined?1:(options.renderUploaded?1:0);
  var imagesLimit    = options.imagesLimit?options.imagesLimit:0;
  var hideLegend     = options.hideLegend?1:0;
  var tinyMode       = options.tinyMode?1:0;
  var baseUrl        = options.baseUrl;

  UploaderReady = function() {

    if (renderUploaded) {    	
      $.get( baseUrl + 'badge_designer.php?__fumMethod=render&sessionId='  + sessionId +
                                       '&hideLegend=' + hideLegend + 
                                       '&baseUrl='    + escape(baseUrl) +
                                       '&tinyMode='   + tinyMode
           , function(response) {           	
              if (response && /^</.test(response)) {  
                if (imagesLimit > 0) {
                  $('#' + containerId).html(response);
                } else {
                	counter_img++;
                  sparr = response.toString().split("<".toString());
                  counts = sparr.length;
                  for(i=1;i<counts;i++){
                    counter_img++;
                    new_el = $('#'+containerId).clone(true);
                    $(new_el).attr('id', containerId+counter_img); 
                    $(new_el).insertAfter($('#'+containerId));
                    //$('#' + containerId).html("<"+sparr[i]);
                    $(new_el).html("<"+sparr[i]);
                  }
                  //$('#' + containerId).append(response);
                }
              }
            });
    }

    new Ajax_upload('#' + selectorId, {
        action: baseUrl + 'badge_designer.php?__fumMethod=upload&selectorId='  + selectorId + 
                                   '&sessionId='   + sessionId + 
                                   '&width='       + thumbWidth + 
                                   '&height='      + thumbHeight + 
                                   '&makeThumb='   + makeThumb +
                                   '&imagesLimit=' + imagesLimit + 
                                   '&hideLegend='  + hideLegend +
                                   '&baseUrl='     + escape(baseUrl) +
                                   '&tinyMode='    + tinyMode +
                                   '&imageCheck='  + imageCheck
      , name: selectorId
      , onSubmit: function(file, ext) {
      	
      	  images = $(".badge_logo[id!='bd-logo']");      	      
			    if ((max_images_count > -1) && (images.length >= max_images_count)) {
                  alert('You can not add more images to this badge');
			      return false;
			    }
			    
          if (imageCheck && !(ext && /^(jpg|png|jpeg|gif)$/.test(ext))) {
            alert('Please select JPEG|JPG|PNG|GIF images only');
            return false;
          }
          if (imagesLimit > 0) {
            $('#' + containerId).html('<div id="fum_upload_indicator" class="fum_upload_indicator">Uploading ' + file + '...</div>');
          } else {
            counter_img++;
            new_el = $('#'+containerId).clone(true);
            $(new_el).attr('id', containerId+counter_img); 
            $(new_el).insertAfter($('#'+containerId));
            $(new_el).append('<div id="fum_upload_indicator" class="fum_upload_indicator">Uploading ' + file + '...</div>');            
          } 
        }
      , onComplete: function(file, response) {
          //$('#fum_upload_indicator').remove();
          if (response && /^</.test(response)) {
            if (imagesLimit > 0) {
              $('#' + containerId).html(response);
            } else {
            	//counter_img++;
            	$('#'+containerId+counter_img).remove();
		          new_el = $('#'+containerId).clone(true);
		          $(new_el).attr('id', containerId+counter_img); 
		          $(new_el).insertAfter($('#'+containerId));
		          $(new_el).prepend('<div id="fum_upload_indicator">Please wait..</div>');            
		          //$(new_el).html(response);
				  $(new_el).append(response);
              $(new_el).attr('title', 'Click and Drag to move logo and position mouse over bottom right hand corner to adjust size');
              $(new_el).tipsy();
            }
			
			$('img.fum_image').load(function() { 				
				$('#fum_upload_indicator').remove();  
				//alert('Image Loaded'); 			  
			});
			
      	  } else {
            alert(response);
			$('#fum_upload_indicator').remove();
          }
        }
    });
  }

  $(document).ready(function() { 
    UploaderReady();     
  });
  
}

function attachNameUploader(options) {

  var selectorId     = options.selectorId;
  var containerId    = options.containerId;
  var extensions     = options.extensions;
  //var sessionId      = options.sessionId?options.sessionId:'fum_session'; //modified as below oct 07, 2010
  var sessionId      = options.sessionId?options.sessionId:'fum_file_session';
  var thumbWidth     = options.thumbWidth?options.thumbWidth:200;
  var thumbHeight    = options.thumbHeight?options.thumbHeight:80;
  var makeThumb      = options.makeThumb?1:0;
  var imageCheck     = options.imageCheck?1:0;
  var renderUploaded = options.renderUploaded==undefined?1:(options.renderUploaded?1:0);
  var imagesLimit    = options.imagesLimit?options.imagesLimit:0;
  var hideLegend     = options.hideLegend?1:0;
  var tinyMode       = options.tinyMode?1:0;
  var baseUrl        = options.baseUrl;

  NamesUploaderReady = function() {

    if (renderUploaded) {    	
      $.get( baseUrl + 'badge_designer.php?__fumMethodNames=new_render&sessionId='  + sessionId +
                                       '&hideLegend=' + hideLegend + 
                                       '&baseUrl='    + escape(baseUrl) +
                                       '&tinyMode='   + tinyMode
           , function(response) {           	
              if (response && /^</.test(response)) {  
                if (imagesLimit > 0) {
                  $('#' + containerId).html(response);
                } else {
                	counter_img++;
                  sparr = response.toString().split("<".toString());
                  counts = sparr.length;
                  for(i=1;i<counts;i++){
                    counter_img++;
                    new_el = $('#'+containerId).clone(true);
                    $(new_el).attr('id', containerId+counter_img); 
                    $(new_el).insertAfter($('#'+containerId));
                    //$('#' + containerId).html("<"+sparr[i]);
                    $(new_el).html("<"+sparr[i]);
                  }
                  //$('#' + containerId).append(response);
                }
              }
            });
    }

    new Ajax_upload('#' + selectorId, {
        action: baseUrl + 'badge_designer.php?__fumMethodNames=name_upload&selectorId='  + selectorId + 
                                   '&sessionId='   + sessionId + 
                                   '&width='       + thumbWidth + 
                                   '&height='      + thumbHeight + 
                                   '&makeThumb='   + makeThumb +
                                   '&imagesLimit=' + imagesLimit + 
                                   '&hideLegend='  + hideLegend +
                                   '&baseUrl='     + escape(baseUrl) +
                                   '&tinyMode='    + tinyMode +
                                   '&imageCheck='  + imageCheck
      , name: selectorId
      , onSubmit: function(file, ext) {
      	
      	  images = $(".badge_name[id!='names']");      	 
		  //images = $(".badge_logo[id!='names']");    //Modified as above oct 08, 2010
		   if ((max_files_count > -1) && (images.length >= max_files_count)) {
			  alert('You can not add more files to this badge');
			  return false;
			}
				
		    
          if (imageCheck && !(ext && /^(txt|csv|xls|xlsx|odt)$/.test(ext))) {
             alert('Please select .xls, .csv, .odt or .txt files only');
            return false;
          }
          if (imagesLimit > 0) {
            $('#' + containerId).html('<div id="fum_upload_indicator" class="fum_upload_indicator">Uploading ' + file + '...</div>');
          } else {
            counter_img++;
            new_el = $('#'+containerId).clone(true);
            $(new_el).attr('id', containerId+counter_img); 
            $(new_el).insertAfter($('#'+containerId));
            $(new_el).append('<div id="light" class="white_content" style="display:block"></div>'); 
	    $(new_el).append('<div id="fade" class="black_overlay" style="display:block"></div>'); 
            //$(new_el).append('<div id="fum_upload_indicator" class="fum_upload_indicator">Uploading ' + file + '...</div>');            
          } 
        }
      , onComplete: function(file, response) {
          $('#fum_upload_indicator').remove();
          if (response && /^</.test(response)) {
            if (imagesLimit > 0) {
              $('#' + containerId).html(response);
            } else {
            	//counter_img++;
            	$('#'+containerId+counter_img).remove();
		          new_el = $('#'+containerId).clone(true);
		          $(new_el).attr('id', containerId+"-"+counter_img); 
		          $(new_el).insertAfter($('#'+containerId));
		          $(new_el).html(response);
	      $(new_el).attr('title', file );          
              $(new_el).tipsy();
              $(new_el).append('<div style="cursor:pointer;"><b>File Uploaded:</b> ' + file + '</div>');
            }
      	  } else {
            alert(response);
			$('#fum_upload_indicator').remove();
          }
        }
    });
  }

  $(document).ready(function() { 
    NamesUploaderReady();     
  });
  
}