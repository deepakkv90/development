<?php
ob_start();
session_cache_limiter('none');
session_start();
define('TEMPORARY_PATH', dirname(__FILE__).'/_tmp/');
define('FONTS_PATH', dirname(__FILE__).'/fonts/');

require_once(dirname(__FILE__).'/utils.php');
require_once(dirname(__FILE__).'/file_upload_manager.php');

require_once(dirname(__FILE__).'/image_file.php');

if (get('__fumMethod')) {
  $file_upload_manager = new file_upload_manager();
  $file_upload_manager->handler();
}

if (get('__fumMethodNames')) {
  $file_upload_manager = new file_upload_manager();
  $file_upload_manager->nameshandler();
}

function fun_browser_info($agent=null) {
  // Declare known browsers to look for
  $known = array('msie', 'firefox', 'safari', 'webkit', 'opera', 'netscape',
    'konqueror', 'gecko');
  $agent = strtolower($agent ? $agent : $_SERVER['HTTP_USER_AGENT']);
  $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';
	
  if (!preg_match_all($pattern, $agent, $matches)) return array();
  $i = count($matches['browser'])-1;
  return array($matches['browser'][$i] => $matches['version'][$i]);
}

function px2pt($mm) {
  //$newMM = str_replace("mm","",$mm);
  return ($mm*2.83)+0.5; 
}


if (get('__bdMethod', post('__bdMethod'))) {
  switch (get('__bdMethod', post('__bdMethod'))) {
    case "submit":    
    
      $is_preview = false;    
      if (post('temp_pict', get('temp_pict')) == 2) {
        $is_preview = true;
        $badge_data = $_SESSION['badges_data'];
        if (!$badge_data) {
          exit();
        }
      } else
      if (get_magic_quotes_gpc()) {
        $badge_data = stripslashes(get('badge_data', post('badge_data')));
      } else {
        $badge_data = get('badge_data', post('badge_data'));
      }
                                    
      require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/includes/configure.php');
    
      if (post('temp_pict', get('temp_pict')) == 1) {
        
		$_SESSION['badges_data'] = $badge_data;
        require_once(dirname(__FILE__).'/badge_desc.php');
        $badge = new Badge(serialize(json_decode($badge_data)));
		//print_r($badge_data);
		//echo "<br><br>";		
        echo($badge->description());
        exit();
		
      }
      
      if (!post('temp_pict', get('temp_pict'))) {
        mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
        mysql_select_db(DB_DATABASE);
      }
	
      $foo 	  = utf8_encode($badge_data);
      $badge_data = json_decode($foo);
      //$badge_data = json_decode($badge_data);
      //print_r(serialize($badge_data)); exit();
      //echo("\n");
      //print_r($badge_data);exit();
        
      $texts = serialize($badge_data->texts);
      
    	$max_count = 1;
    	/*
		foreach($badge_data->texts as $key => $value) {
    		if (count(split("\n", $value->lines)) > $max_count) {
    			$max_count = count(split("\n", $value->lines));
    		} 
    	} */
		//Modified Nov 01, 2010
		$qty_count = 1;
		
		foreach($badge_data->texts as $key => $value) {
    		
			$data = preg_replace("/\r?\n$/", "", $value->lines,1);
			
			$txtArr = split("\n", $data);
						
			if (count($txtArr) > $qty_count) {
    			$qty_count = count($txtArr);
    		} 
    	} 
      
    	$narr = array();
    	
    	foreach($badge_data->texts as $k => $val) {
        	$narr[$k] = $val->lines;
    	}   
    	
      	$images_gallery = array();
      
        for ($img_arr = 0; $img_arr < $max_count; $img_arr++) {
      
			foreach ($narr as $key => $value) {
			  $splitted = split("\n", $value);
			  if ($img_arr < count($splitted)) {
				$badge_data->texts[$key]->text = $splitted[$img_arr];    			
			  } else {
				$badge_data->texts[$key]->text = '';
			  }    		
			}
		  
			if (strlen($badge_data->border->src_real_size) > 0) {
			  $badge_border_image = $badge_data->border->src_real_size;
			  $size1 = getimagesize(DIR_FS_CATALOG . DIR_WS_IMAGES.$badge_border_image);      	
			  $size2 = getimagesize(DIR_FS_CATALOG . DIR_WS_IMAGES.$badge_data->border->src);
			  $res_x = $size1[0]/$size2[0];
			  $res_y = $size1[1]/$size2[1];
			} else {
			  $badge_border_image = $badge_data->border->src;
			  $res_x = 1;
			  $res_y = 1;
			}
			$badge_border_image_file = DIR_FS_CATALOG . DIR_WS_IMAGES.$badge_border_image;
	
			$badge_border_inner_image = null;
			if (strlen($badge_data->border->src_inner) > 0) {
			  if (strlen($badge_data->border->src_inner_real_size) > 0) {
				$badge_border_inner_image = $badge_data->border->src_inner_real_size;
				$size1 = getimagesize(DIR_FS_CATALOG . DIR_WS_IMAGES.$badge_border_inner_image);      	
				$size2 = getimagesize(DIR_FS_CATALOG . DIR_WS_IMAGES.$badge_data->border->src_inner);
			  } else {
				$badge_border_inner_image = $badge_data->border->src_inner;
			  }
			}
			if ($badge_border_inner_image) {
			  $badge_border_inner_image_file = DIR_FS_CATALOG . DIR_WS_IMAGES.$badge_border_inner_image;
			} else {
			  $badge_border_inner_image_file = null;
			}
			
			$image = imagecreatefrompng($badge_border_image_file);
			if (function_exists("imagecreatetruecolor"))
			  $new_image = imagecreatetruecolor(imagesx($image), imagesy($image));
			else
			  $new_image = imagecreate(imagesx($image), imagesy($image));

			if ($badge_data->outerBgBrush) {
				

			  $brush = imagecreatefromjpeg(dirname(__FILE__).'/images/'.$badge_data->outerBgBrush);
			  //echo '<pre>'; print_r($badge_data); echo dirname(__FILE__).'/images/'.$badge_data->outerBgBrush; die();
			  imagecopyresampled ( $new_image
								 , $brush
								 , 0
								 , 0
								 , 0
								 , 0
								 , imagesx($new_image)
								 , imagesy($new_image)
								 , imagesx($brush)
								 , imagesy($brush)
								 );
			  imagedestroy($brush);
			}
			  
			if ($badge_data->outerBgColor) {
			  $colors = badge_str2rgb($badge_data->outerBgColor);
			  $color = imagecolorallocate($new_image, $colors[1], $colors[2], $colors[3]);
			  imagefilledrectangle($new_image, 0, 0, imagesx($image), imagesy($image), $color);
			} else if (!$badge_data->outerBgBrush) {
			  $color = imagecolorallocate($new_image, 255, 255, 255);
			  imagefilledrectangle($new_image, 0, 0, imagesx($image), imagesy($image), $color);
			}
	
			imagecopyresampled ( $new_image
							   , $image
							   , 0
							   , 0
							   , 0
							   , 0
							   , imagesx($image)
							   , imagesy($image)
							   , imagesx($image)
							   , imagesy($image)
							   );
	
			if ($badge_border_inner_image_file) {
			  $image_inner = imagecreatefrompng($badge_border_inner_image_file);
			  $left = imagesx($image)/2-imagesx($image_inner)/2;
			  $top = imagesy($image)/2-imagesy($image_inner)/2;
			  if ($badge_data->innerBgColor) {
				$colors = badge_str2rgb($badge_data->innerBgColor);
				$color = imagecolorallocate($new_image, $colors[1], $colors[2], $colors[3]);
				imagefilledrectangle($new_image, $left, $top, $left + imagesx($image_inner) - 1, $top + imagesy($image_inner) - 1, $color);
			  } else if ($badge_data->outerBgColor) {
				$colors = badge_str2rgb($badge_data->outerBgColor);
				$color = imagecolorallocate($new_image, $colors[1], $colors[2], $colors[3]);
				imagefilledrectangle($new_image, $left, $top, $left + imagesx($image_inner) - 1, $top + imagesy($image_inner) - 1, $color);
			  } else if ($badge_data->outerBgBrush) {

				$brush = imagecreatefromjpeg(dirname(__FILE__).'/images/'.$badge_data->outerBgBrush);
				imagecopyresampled ( $new_image
								   , $brush
								   , $left
								   , $top
								   , 0
								   , 0
								   , imagesx($image_inner)
								   , imagesy($image_inner)
								   , imagesx($brush)
								   , imagesy($brush)
								   );
				imagedestroy($brush);
			  } else {
				$color = imagecolorallocate($new_image, 255, 255, 255);
				imagefilledrectangle($new_image, $left, $top, $left + imagesx($image_inner) - 1, $top + imagesy($image_inner) - 1, $color);
			  }
			  
			  imagecopyresampled ( $new_image
								 , $image_inner
								 , $left
								 , $top
								 , 0
								 , 0
								 , imagesx($image_inner)
								 , imagesy($image_inner)
								 , imagesx($image_inner)
								 , imagesy($image_inner)
								 );
			  imagedestroy($image_inner);
			}
			imagedestroy($image);
			
			$img_dir = 'users_badges/';
			$names_dir = 'users_names/';
			$Newimgfile= array();
			$Newnamefile= array();
			for($i = 0; $i < count($badge_data->logos); $i++) {
			  $logo = $badge_data->logos[$i];          
			  if (preg_match("/fum_session/", $logo->src)) {
				$logos = session('fum_session');	  	
				$imgfile = safe(safe($logos, $logo->id), 'tmp_file_path');			
				if (!$is_preview) {
				  copy($imgfile, DIR_FS_CATALOG . DIR_WS_IMAGES . $img_dir . basename($imgfile));
				}			
			  } else {
				$imgfile = DIR_FS_CATALOG . DIR_WS_IMAGES . $img_dir . basename($logo->src);							
			  }
			  
			  
			  if (file_exists($imgfile)) {
			  
				$badge_data->logos[$i]->src = basename($imgfile);
				if (($logo_image = @ImageCreateFromGIF ($imgfile)) || 
					($logo_image = @ImageCreateFromJPEG ($imgfile)) ||
					($logo_image = @ImageCreateFromPNG ($imgfile)) ||
					($logo_image = @ImageCreateFromWBMP ($imgfile))) {
				  imagecopyresampled ( $new_image
									 , $logo_image
									 , ($logo->x+2) * $res_x
									 , ($logo->y+2) * $res_y
									 , 0
									 , 0
									 , ($logo->width)* $res_x
									 , ($logo->height) * $res_y
									 , imagesx($logo_image)
									 , imagesy($logo_image)
									 );
				  imagedestroy($logo_image);
				}
			  } 
			  $Newimgfile[$i] = $imgfile;
			}
			//////////////////////// Multiname file ///////////////////////////////
			for($i = 0; $i < count($badge_data->multiName); $i++) {
			
			  $name = $badge_data->multiName[$i];         		  
			  if (preg_match("/fum_file_session/", $name->src)) {
				$names = session('fum_file_session');	
				$namefile = safe(safe($names, $name->id), 'tmp_file_path');
				copy($namefile, DIR_FS_CATALOG . DIR_WS_IMAGES . $names_dir . basename($namefile));
				$namefile = basename($namefile);
			  } else { 
				$namefile = DIR_FS_CATALOG . DIR_WS_IMAGES . $names_dir . basename($name->src);	
				$namefile = basename($namefile);		
			  }		  
			  $Newnamefile[$i] = $namefile;
			}
		    //////////////////////////////////////////////////////////
	   		$cust = 1;
			foreach ($badge_data->texts as $text) {
			  
			  $sr = $res_x;
			  if($res_x < $res_y) {
				$sr = $res_x+(($res_y-$res_x)/2);
			  }
			  if($res_y < $res_x) {
				$sr = $res_y+(($res_x-$res_y)/2);
			  }  
			  
			  /* $text_size = round(substr($text->size, 0, -2)*$sr,2).'px';  */
			 
			  //Modified Sep 23, 2010
			  //get browser to show mm  - start		  
			  $text_size = (substr($text->size, 0, -2));		  
			  $ua = fun_browser_info();			  
			  if($ua['safari']) { 	  	
				$text_size = round(($text_size*$sr),2)."mm";			
			  }
			  else {		  	
				//$text_size = round((($text_size*0.2645)*$sr),2); //Hided Nov 12, 2010
				//$text_size = $text_size."mm";			
				$text_size = round(($text_size*$sr),2)."mm";		
			  }
			  //get browser to show mm  - end
			  
			  
			  $text->font = trim(trim($text->font, '"'), "'");
			  $font_name = FONTS_PATH.strtolower(str_replace(' ', '_', $text->font));
			  if ($text->bold)
				$font_name .= 'b';
			  if ($text->italic)
				$font_name .= 'i';
			  
			  $font_name .= '.ttf';
			  if ($colors = badge_str2rgb($text->color)) {
				$color = imagecolorallocate($new_image, $colors[1], $colors[2], $colors[3]);            
				$box = @imagettfbbox(px2pt($text_size), 0, $font_name, $text->text);			
				$width = abs($box[2] - $box[0]);
				$height = abs($box[3] - $box[5]); 
				if ($text->underline) {
				  $offest = 2;
				  imageline($new_image, (($text->x)*$res_x), ((($text->y)*$res_y)+2) + $height + $offest, ((($text->x)*$res_x)) + $width, ((($text->y)*$res_y)+2) + $height + $offest, $color);
				}
				if ($text->angle && ($text->angle != 'none')) {
				  $angle = 38;
				} else {
				  $angle = 0;
				}
				if ($angle) {
				  $x = ($text->x + 2);
				  $y = ($text->y - 8);
				  //$y = ($text->y + 6);
				  $y = $y + sin(deg2rad($angle)) * $text->width;
				  //$y = $y + sin(deg2rad($angle) + atan($height/$width)) * sqrt($height*$height + $width*$width);
				  $x = $text->height * cos(deg2rad(90-$angle)) + $x;
				} else {
				  $x = ($text->x + 2);
				  $y = ($text->y - 4);
				  $y = $y + $text->height;
				}
				$x = $x*$res_x;
				$y = $y*$res_y;            
				imagettftext($new_image, px2pt($text_size), $angle, $x, $y, $color, $font_name, $text->text);						
			  }
			  $cust = $cust + 1;  
			} //End of text for loop
       		
			if ($temp_pict = post('temp_pict', get('temp_pict'))) {
			  if ($temp_pict == 2) {
				header('Content-type: image/png');
				imagepng($new_image);
			  }
			  die();
			} else {
			  if ($_POST['user_id'] != '') {
				$user_id = $_POST['user_id'];
			  } else {
				$user_id = '-1';
			  }
			  $img_guid = guid();
			  $img = 0;
			  foreach ($Newimgfile as $ImgValue){
				$img_new_guid = guid();
				if (isset($ImgValue)) {
					preg_match("/\.([\w]+)$/",$ImgValue,$matches);
					$productLogo[$img] = $img_dir.$img_new_guid.'-thumb'.$matches[0];
				} else {
					$productLogo = '';
				}
				$img ++;		
			  }
			  
			  //Mar 23 2011 - Added below code to assign all logos to $filelogos and store this var in DB.
			  $filelogos = ""; $namefiles ="";
      		  if(is_array($productLogo)) {
			  	$filelogos = implode(",",$productLogo);	
			  }
			  
			  if(is_array($Newnamefile)) {
			  	$namefiles = implode(",",$Newnamefile);	
      		  }	
			  
      
			  if (isset($imgfile)) {
				preg_match("/\.([\w]+)$/",$imgfile,$matches);
				$file_logo = $img_dir.$img_guid.'-thumb'.$matches[0];
			  } else {
				$file_logo = '';
			  }
			  $db_file_path = $img_dir.$img_guid.'.png';
			  if( !file_exists(DIR_FS_CATALOG . 'images/'.$img_dir)) {
				@mkdir(DIR_FS_CATALOG . 'images/'.$img_dir);
			  }
			  $images_gallery[] = array('path'=>DIR_FS_CATALOG . 'images/'.$db_file_path, 'name'=>$db_file_path);
			  $saved_badge_data = $badge_data;
			  $saved_badge_data->shape->fittes = array();
			  $saved_badge_data->shape->borders = array();
			  $saved_badge_data->texts = unserialize($texts);
			  //products 
			  
			  $qry = mysql_query(placeholder('SELECT * FROM products WHERE products_id = ?', (int)$badge_data->shape->productId)); 			  
			  $prod = array();
			  while($rslt = mysql_fetch_array($qry)){            
				  $prod[] =$rslt;
			  } 
          
          	  $prod = @$prod[0];
			  //print_r($prod);
			  //exit;
			  
			  $a= explode("\n",$narr[0]);
			  $bdStrArr = array();
		  	  $icount=0;			
			  $textString = "";
			  for($i=0; $i<sizeof($narr);$i+=1) {
			  
				$bstring = explode("\n",$narr[$i]);
				
				for($j=0;$j<sizeof($bstring);$j++) {
					$bdStrArr[$i][$j] = $bstring[$j];
				}
			  }	
			   
			  for ($row = 0; $row < 1; $row+=2) {					
			 
				  for ($col = 0; $col < sizeof($a); $col++) {
					
					$tmp = 0;
					
					while($tmp<=sizeof($bdStrArr)) {
						
						if($bdStrArr[$row+$tmp][$col]!="") {
						
							if($tmp%2==0) {								
								$textString .= $bdStrArr[$row+$tmp][$col];								
							}
							else {
								$textString .= $bdStrArr[$row+$tmp][$col];
								
							}
							
							$textString .= " , ";						
								
						}
						
						$tmp++;	
						
					}					
					
					$textString = substr_replace($textString,"",-2); 
					$textString .= "\r\n";
					
				  }	
				  					
			 }
			   
			  if(isset($textString)){
					$TextContent  = $textString;
			  }else{
					$TextContent  = "";
			  }
			  
			  //Mar 09 2011 - add badge comment in products table
			  $badge_comment = "";
			  if(isset($_POST['badge_comment']) && !empty($_POST['badge_comment'])) {
			  	$badge_comment = $_POST['badge_comment'];
			  }
			  
			  $languages_id = 1;
			  if(isset($_POST['lang_id']) && !empty($_POST['lang_id'])) {
			  	$languages_id = $_POST['lang_id'];
			  }
		  
			  if ($img_arr == ($max_count-1)) {
					$query = placeholder("INSERT INTO products  ( products_quantity    
														, products_tax_class_id
														, manufacturers_id
														, products_model
                                                        , products_price1
                                                      	, products_price2
                                                      	, products_price3
                                                      	, products_price4
                                                      	, products_price5
                                                      	, products_price6
                                                      	, products_price7
                                                      	, products_price8
                                                      	, products_price9
                                                      	, products_price10
                                                      	, products_price11
                                                      	
                                                      	, products_price1_qty
                                                        , products_price2_qty
                                                        , products_price3_qty
                                                        , products_price4_qty
                                                        , products_price5_qty
                                                        , products_price6_qty
                                                        , products_price7_qty
                                                        , products_price8_qty
                                                        , products_price9_qty
                                                        , products_price10_qty
                                                        , products_price11_qty
            
                                                        , products_image
                                                        , products_image_med
                                                        , products_image_lrg
                                                        , products_price
                                                        , products_date_added
                                                        , products_date_available
                                                        , products_status
                                                        , user_id 
														, badge_namefile 
                                                        , badge_logo
                                                        , badge_data
                                                        , default_product_id
														, products_group_access
														, products_nav_access
														, products_min_order_qty
														, badge_comment 
														, products_text
														, labour_cost 
														, overhead_cost 
														, material_cost
                                                        )
                                                 VALUES ( 9999
                                                        ,?
														,?
														,?
                                                        ,?
                                                        ,?
                                                        ,?
                                                        ,?
                                                        ,?
                                                        ,?
                                                        ,?
                                                        ,?
                                                        ,?
                                                        ,?
                                                        ,?
                                                        
                                                        ,?
                                                        ,?
                                                        ,?
                                                        ,?
                                                        ,?
                                                        ,?
                                                        ,?
                                                        ,?
                                                        ,?
                                                        ,?
                                                        ,?
                                                 
                                                        , ?
                                                        , ?
                                                        , ?
                                                        , ?
                                                        , ?
                                                        , ?
                                                        , 1
                                                        , ?
                                                        , ?
														, ?
                                                        , ?
                                                        , ?
														, ?
                                                        , ?
                                                        , ?
														, ?
														, ?   
														, ?
														, ?
														, ?                                                		                                
                                                        )" 
                                                        ,@$prod['products_tax_class_id']
														,@$prod['manufacturers_id']
														,@$prod['products_model']
                                                        ,@$prod['products_price1']?$prod['products_price1']:0
                                                        ,@$prod['products_price2']?$prod['products_price2']:0
                                                        ,@$prod['products_price3']?$prod['products_price3']:0
                                                        ,@$prod['products_price4']?$prod['products_price4']:0
                                                        ,@$prod['products_price5']?$prod['products_price5']:0
                                                        ,@$prod['products_price6']?$prod['products_price6']:0
                                                        ,@$prod['products_price7']?$prod['products_price7']:0
                                                        ,@$prod['products_price8']?$prod['products_price8']:0
                                                        ,@$prod['products_price9']?$prod['products_price9']:0
                                                        ,@$prod['products_price10']?$prod['products_price10']:0
                                                        ,@$prod['products_price11']?$prod['products_price11']:0
                                                        
                                                        
                                                        ,@$prod['products_price1_qty']?$prod['products_price1_qty']:0
                                                        ,@$prod['products_price2_qty']?$prod['products_price2_qty']:0
                                                        ,@$prod['products_price3_qty']?$prod['products_price3_qty']:0
                                                        ,@$prod['products_price4_qty']?$prod['products_price4_qty']:0
                                                        ,@$prod['products_price5_qty']?$prod['products_price5_qty']:0
                                                        ,@$prod['products_price6_qty']?$prod['products_price6_qty']:0
                                                        ,@$prod['products_price7_qty']?$prod['products_price7_qty']:0
                                                        ,@$prod['products_price8_qty']?$prod['products_price8_qty']:0
                                                        ,@$prod['products_price9_qty']?$prod['products_price9_qty']:0
                                                        ,@$prod['products_price10_qty']?$prod['products_price10_qty']:0
                                                        ,@$prod['products_price11_qty']?$prod['products_price11_qty']:0                                                        
            
            
                                                        ,$images_gallery[0]['name']
                                                        ,$images_gallery[0]['name']
                                                        ,$images_gallery[0]['name']
                                                        ,@$prod['products_price']?$prod['products_price']:0 
                                                        ,date('Y-m-d H:i:s')
                                                        ,date('Y-m-d 00:00:00')
                                                        ,$user_id
														,$namefiles  //Mar 21 2011														
														,$filelogos //Mar 21 2011
                                                        ,serialize($saved_badge_data)
                                                        ,$badge_data->shape->productId
														,@$prod['products_group_access']?$prod['products_group_access']:0 
														,@$prod['products_nav_access']?$prod['products_nav_access']:0 
														,@$prod['products_min_order_qty']?$prod['products_min_order_qty']:1
														,$badge_comment  
														,$TextContent?$TextContent:'' 
														,@$prod['labour_cost']?$prod['labour_cost']:0 
														,@$prod['overhead_cost']?$prod['overhead_cost']:0 
														,@$prod['material_cost']?$prod['material_cost']:0);
				//echo(serialize($badge_data));exit();
				mysql_query($query) or die("invalid query: ".$query);
				
				$res = mysql_query('select @@IDENTITY');
				$product_id = mysql_fetch_array($res);
				
				
				//For getting customer group prices of parent product - Feb 21 2011
          	    $sel_group_query = mysql_query(placeholder('SELECT * FROM products_groups WHERE products_id = ?', (int)$badge_data->shape->productId)); 			  
			    while($group_rst = mysql_fetch_array($sel_group_query)){            
				  	//insert products groups details - Feb 21 2011
					$ins_groups_query = placeholder("INSERT INTO products_groups SET 
									customers_group_id='".$group_rst['customers_group_id']."', 
									customers_group_price = '".$group_rst['customers_group_price']."', 
									customers_group_price1 = '".$group_rst['customers_group_price1']."', 
									customers_group_price2 = '".$group_rst['customers_group_price2']."', 
									customers_group_price3 = '".$group_rst['customers_group_price3']."', 
									customers_group_price4 = '".$group_rst['customers_group_price4']."', 
									customers_group_price5 = '".$group_rst['customers_group_price5']."', 
									customers_group_price6 = '".$group_rst['customers_group_price6']."', 
									customers_group_price7 = '".$group_rst['customers_group_price7']."', 
									customers_group_price8 = '".$group_rst['customers_group_price8']."', 
									customers_group_price9 = '".$group_rst['customers_group_price9']."', 
									customers_group_price10 = '".$group_rst['customers_group_price10']."', 
									customers_group_price11 = '".$group_rst['customers_group_price11']."', 
									products_id = '".$product_id[0]."'");
					mysql_query($ins_groups_query) or die("invalid query: ".$ins_groups_query);

			   }    
				
				
		   }
		}
        
        
        
			if (isset($imgfile)) {
			  copy($imgfile, DIR_FS_CATALOG . 'images/'.$file_logo);          
			}
			for ($i=0; $i<count($productLogo); $i++){
				if (isset($productLogo)) {
				  copy($Newimgfile[$i], DIR_FS_CATALOG . 'images/'.$productLogo[$i]);          
				}
			}
        	imagepng($new_image, DIR_FS_CATALOG . 'images/'.$db_file_path);        
        
			imagedestroy($new_image);
			//products_to_categories
			$category = explode('_',$_POST['category']);
			$category = $category[count($category)-1];
			if ($img_arr == ($max_count-1)){
			  $query = "INSERT INTO products_to_categories (products_id,categories_id) VALUES ('".$product_id[0]."','".$category."')";
			  mysql_query($query) or die("invalid query: ".$query);
			}
		    
			$query1 = mysql_query('SELECT pds.products_name FROM products INNER JOIN products_description pds ON (pds.products_id = products.products_id ) WHERE products.products_id = '.$badge_data->shape->productId);
			$result1 = mysql_fetch_array($query1);
			  
			$products_name= $result1['products_name']; 
			   
			//products_description
			if ($img_arr == ($max_count-1)){
			  //$bmame = $_POST['badge_name']?$_POST['badge_name']:' ';      
			  $query = sql_placeholder("INSERT INTO products_description (products_id,products_name, products_description, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag) VALUES (?,?,?,?,?,?)"
			   ,$product_id[0],$products_name,$products_name,$products_name,$products_name,$products_name
			  );
			  mysql_query($query) or die("invalid query: ".addslashes($query));
			}

			preg_match("/osCsid=([^\s]+)$/",$_SERVER['HTTP_REFERER'],$matches);

			if ($img_arr == ($max_count-1)){        
				//------------------------------------
				require_once(dirname(__FILE__) . '/CreateZipFile.inc.php');          
				$randamNumber=md5(microtime().rand(0,999999));
				$textFile=$randamNumber.'.txt';
							  
				$zipName = '';
	
			   $create_zip_file=new CreateZipFile;      
			   foreach ($images_gallery as $ig){              
				 $text_file_path =DIR_FS_CATALOG . 'images/users_badges/'.$textFile;
				 $textFileName = 'users_badges/'.$textFile;
				 //$create_zip_file->addFile(file_get_contents($ig['path']), $ig['name']); //Mar 23 2011 - Hided
				 for ($i=0; $i<count($productLogo); $i++){
					$original_logo_image = DIR_FS_CATALOG . 'images/'.$productLogo[$i];
					//$create_zip_file->addFile(file_get_contents($original_logo_image), $productLogo[$i]); //Mar 23 2011 - Hided
				}
				/////////////////// Multiname files ///////////////////////////////////////////////////////
				for ($i=0; $i<count($Newnamefile); $i++){
					$namePath = DIR_FS_CATALOG . 'images/users_names/'.$Newnamefile[$i];
					//$create_zip_file->addFile(file_get_contents($namePath), $Newnamefile[$i]); //Mar 23 2011 - Hided
				}
				/////////////////// Ends off ///////////////////////////////////////////////////////				 
				 /*$create_zip_file->addFile(file_get_contents($text_file_path), $textFileName);*/ //Mar 23 2011 - Hided
			   }   
			   
	
			   //------------------------------------ 			   			   
			   $fits  = array();
			   /** Fitting Options
				0 - Drop Down
				1 - Text Box
				2 - Radio Button
				3 - Check Box
				4 - Textarea  
			   **/  
			   
			   $name ='';
			   if(strlen(@$_POST['badge_name']) > 0){
				 $name = $_POST['badge_name'];
			   }
			   if(strlen($products_name) > 0){
				 $name .= ($name?'; ':'').$products_name;
			   }
			   
			  if(isset($_POST["id"])) {
			   
			    foreach($_POST["id"] as $opid=>$opvid) {
			
					$query = mysql_query('SELECT DISTINCT po.options_type, pot.products_options_name, pa.options_values_price  		 
						 FROM products_attributes as  pa LEFT JOIN products_options_text pot ON (pa.options_id = pot.products_options_text_id AND pot.language_id="'.$languages_id. '") 
						 LEFT JOIN products_options po ON (pa.options_id = po.products_options_id) 
						 WHERE pa.options_id = "'.$opid.'" AND pa.products_id="'.$badge_data->shape->productId.'" AND pa.options_values_id="'.$opvid.'"'); 										
				    $result = mysql_fetch_array($query);
				    $optype = $result["options_type"];
					$opname = $result["products_options_name"];
					
					//update products price by adding options price
					if(@floatval($result['options_values_price']) > 0){														
						$sql = "UPDATE products SET products_price = products_price +".floatval($result['options_values_price']);
					
						for($i=1;$i<=11;$i++){
						  $sql .= ", products_price".$i." = products_price".$i."+".floatval($result['options_values_price']);              
						}
						$sql .= " WHERE products_id = '" . $product_id[0] . "'";
						
						mysql_query($sql);	
				    }
					
					//update products description
					$pd_query = placeholder("UPDATE products_description SET 
													  products_name = ?
													, products_description = ?
													, products_head_title_tag = ?
													, products_head_desc_tag = ?
													, products_head_keywords_tag = ?
													, products_option_type = ?
													, products_option_values = ?                   
													  WHERE products_id = ?" 
													,$name
													,$name
													,$name
													,$name
													,$name
													,$badge_data->shape->fittes_option_type
													,$_POST['fitid']
													,$product_id[0]);
			  		mysql_query($pd_query);
					
					
					if($optype==0 || $optype==2) {
						
												
						//opv id not array for select and radio buttons
						$opv_qry = mysql_query("SELECT products_options_values_name, options_values_price FROM products_options_values WHERE products_options_values_id='".$opvid."'");
						$opv_arr = mysql_fetch_array($opv_qry);						
						$opvname = $opv_arr["products_options_values_name"];
						if(!empty($opvname)) {
							$opdesc = $opname . "  :  " . $opvname.";";	
							//insert or update badge options
							tep_update_products_badge_options($product_id[0],$opid,$optype,$opvid,$opvname,$opdesc);
						}	
						
					} else if($optype==1 || $optype==4 || $optype==5) {
						
						//one dimentional array - text and text area value
						if(is_array($opvid)) {
						
							foreach($opvid as $key=>$opvname) {
								$opvid = "";								
								if(!empty($opvname)) {
									$opdesc = $opname . "  :  " . $opvname.";";
									//insert or update badge options
									tep_update_products_badge_options($product_id[0],$opid,$optype,$opvid,$opvname,$opdesc);
								}														
							}
							
						}
						//echo $_POST["comment"][$opid];
					
						
					} else if($optype==4) {
						//two dimentional array - check box values
						if(is_array($opvid)) {
						
							foreach($opvid as $key=>$val) {
								
								if(is_array($val)) {
									
									foreach($val as $chk=>$cval) {
										$opv_qry = mysql_query("SELECT products_options_values_name, options_values_price FROM products_options_values WHERE products_options_values_id='".$cval."'");
										$opv_arr = mysql_fetch_array($opv_qry);						
										$opvid = $cval;
										$opvname = $opv_arr["products_options_values_name"];
										
										$opdesc = $opname . "  :  " . $opvname.";";
										//insert or update badge options
										tep_update_products_badge_options($product_id[0],$opid,$optype,$opvid,$opvname,$opdesc);
									}
									
								} 
							}
							
						}
						
					} //end of option type if loop 
										
			   }
			 }
			 //exit;
			 /*
			   // Updating Prodcut Table    
			   /////////////////// Dropdown menu or Radio Button ///////////////////
			   if($badge_data->shape->fittes_option_type == 0 || $badge_data->shape->fittes_option_type == 2){
				
				  while($result = mysql_fetch_array($query)){
					if(@$_POST['fitid'] == $result['products_attributes_id']){
					  $fits =$result;
					}
				  }  			                     			                   
			  
			  	if(@floatval($fits['options_values_price']) > 0){														
					$sql = "UPDATE products SET products_price = products_price +".floatval($fits['options_values_price']);
				
					for($i=1;$i<=11;$i++){
					  $sql .= ", products_price".$i." = products_price".$i."+".floatval($fits['options_values_price']);              
					}
					$sql .= " WHERE products_id = '" . $product_id[0] . "'";
					mysql_query($sql);	
				  }
				  $name ='';
				  if(strlen(@$_POST['badge_name']) > 0){
					$name = $_POST['badge_name'];
				  }
				  if(strlen($products_name) > 0){
					$name .= ($name?'; ':'').$products_name;
				  }
			  
				  if(strlen(@$fits['products_options_values_name']) > 0){														
						$name .= ($name?'; ':'').$fits['products_options_values_name'];	
				  } else { //else part added to show no fitting - Mar 31 2011
				  	$name .= ($name?'; ':'');	
				  }
				  
			  	  $query = placeholder("UPDATE products_description SET 
													  products_name = ?
													, products_description = ?
													, products_head_title_tag = ?
													, products_head_desc_tag = ?
													, products_head_keywords_tag = ?
													, products_option_type = ?
													, products_option_values = ?                   
													  WHERE products_id = ?" 
													,$name
													,$name
													,$name
													,$name
													,$name
													,$badge_data->shape->fittes_option_type
													,$_POST['fitid']
													,$product_id[0]);
			  		mysql_query($query);
				}
				/////////////////// Text Box or Textarea ///////////////////
				elseif($badge_data->shape->fittes_option_type == 1 || $badge_data->shape->fittes_option_type == 4){
				
					$count = 0;
					while($result = mysql_fetch_array($query)){
						  $fites[] =$result; 
						  $count++;
					} // eof while
					$fitsCount = count($fites);
					for ($i=0; $i<=count($fites); $i++){
						$fits  = $fites;
					}// eof for
					$fits  = $fits[$fitsCount-1];
			  
				  if(@floatval($fits['options_values_price']) > 0){														
					$sql = "UPDATE products SET products_price = products_price +".floatval($fits['options_values_price']);
					
					for($i=1;$i<=11;$i++){
					  $sql .= ", products_price".$i." = products_price".$i."+".floatval($fits['options_values_price']);              
					}
					$sql .= " WHERE products_id = '" . $product_id[0] . "'";
					mysql_query($sql);	
				  }
				  $name ='';
				  if(strlen(@$_POST['badge_name']) > 0){
					$name = $_POST['badge_name'];
				  }
				  if(strlen($products_name) > 0){
					$name .= ($name?'; ':'').$products_name;
				  }
				  
				  if(strlen(@$fits['products_options_values_name']) > 0){														
					$name .= ($name?'; ':'').$_POST['fitid'];	
				  }
			  
				  $query = placeholder("UPDATE products_description SET 
													  products_name = ?
													, products_description = ?
													, products_head_title_tag = ?
													, products_head_desc_tag = ?
													, products_head_keywords_tag = ?
													, products_option_type = ?
													, products_option_values = ?                   
													  WHERE products_id = ?" 
													,$name
													,$name
													,$name
													,$name
													,$name
													,$badge_data->shape->fittes_option_type
													,$_POST['fitid']
													,$product_id[0]);
			  		mysql_query($query);
				}
				/////////////////// Check Box ///////////////////
				elseif($badge_data->shape->fittes_option_type == 3){
					//echo "<pre>";print_r($_POST);
					$fites = array();
					$fits_products_options_values_names = array();
					$fits_products_name = '';
					$fits_options_values_price = 0;
					
					$count = 0;
					while($result = mysql_fetch_array($query)){
					  $fites[] = $result; 
					  $count++;
					} // eof while
					
					$option = explode(",", $_POST['fitid']);	  
					$optionValues = array_unique($option);
					for ($i=0; $i<=count($optionValues); $i++){
						$fits = $optionValues;
					} // eof for
					
					$k = 0;
					foreach($fites as $key=>$value){
						if(in_array($value['products_attributes_id'],$fits)){
							$fits_options_values_price 		   += $value['options_values_price'];
							$fits_products_options_values_names[$k]  = $value['products_options_values_name'];
							$k++;
						}
					} // eof foreach
					$fits_products_name = implode(";", $fits_products_options_values_names);
					if(@floatval($fits_options_values_price) > 0){														
				$sql = "UPDATE products SET products_price = products_price +".floatval($fits_options_values_price);
				
				for($i=1;$i<=11;$i++){
				  $sql .= ", products_price".$i." = products_price".$i."+".floatval($fits_options_values_price);              
				}
				$sql .= " WHERE products_id = '" . $product_id[0] . "'";
				mysql_query($sql);	
			  }
			  $name ='';
			  if(strlen(@$_POST['badge_name']) > 0){
				$name = $_POST['badge_name'];
			  }
			  if(strlen($products_name) > 0){
				$name .= ($name?'; ':'').$products_name;
			  }
			  
			  if(!empty($fits_products_name)){														
				$name .= ($name?'; ':'').$fits_products_name;	
			  }
			   $query = placeholder("UPDATE products_description SET 
													  products_name = ?
													, products_description = ?
													, products_head_title_tag = ?
													, products_head_desc_tag = ?
													, products_head_keywords_tag = ?
													, products_option_type = ?
													, products_option_values = ?                   
													  WHERE products_id = ?" 
													,$name
													,$name
													,$name
													,$name
													,$name
													,$badge_data->shape->fittes_option_type
													,$fits_products_name
													,$product_id[0]);
			  mysql_query($query);
				}
			
			*/			
				
			  // eof Updating
			}
        
			if ($img_arr == ($max_count-1)){			
			  unset($_SESSION['fum_session']);
			  //Modified oct 07, 2010
			  unset($_SESSION['fum_file_session']);
			  
			  header("Location:" . HTTP_SERVER . DIR_WS_HTTP_CATALOG . 'index.php?action=buy_now&products_id='.$product_id[0].'&osCsid='.$matches[1].'&add_fit=1&cart_quantity='.$qty_count.'&zname='.$zipName.'&delete_product='.@$_POST['delete_product']);
			  	
			}
       } //End of For loop $max_count
    }    
  }

function get_shapes_list($category_id,$lang_id=1) {	
  //amount of images id = 4
  //max lines = 5
  if ($category_id) {
    if (eregi('[0-9]+$', $category_id, $matches)) {
      $category_id = $matches[0];
    }
  	//------------------------- GET FITTINGS  	
  	$query = tep_db_query('SELECT products_options_text_id FROM products_options_text WHERE products_options_name = "Fitting"');
  	//error_reporting(E_ALL);
  	//ini_set('display_errors', 1);
  	$result = tep_db_fetch_array($query);
  	$id = intval($result['products_options_text_id']);
  	//--------------------------------------
    $ok = false;  
    $shapes_list = '';
    /*$shapes_query = tep_db_query("SELECT prd.*, pln.products_extra_fields_value as max_lines_count, ppe.products_extra_fields_value as max_images_count FROM products_to_categories ptg 
    		                          INNER JOIN products prd ON ptg.products_id = prd.products_id
    		                          LEFT JOIN products_to_products_extra_fields ppe ON (ppe.products_extra_fields_id = 4 AND ppe.products_id = prd.products_id)
    		                          LEFT JOIN products_to_products_extra_fields pln ON (pln.products_extra_fields_id = 5 AND pln.products_id = prd.products_id)
    		                          WHERE (prd.user_id = '' OR prd.user_id IS NULL) AND ptg.categories_id = ".$category_id. " GROUP BY prd.products_id ORDER BY prd.products_id ");    		                          */
    $shapes_query = tep_db_query("SELECT prd.products_id,
										 prd.max_images_count,
										 prd.max_lines_count,
										 prd.products_image,
										 prd.default_texts,
										 dsc.products_name
                                FROM products_to_categories ptg 
								INNER JOIN products prd ON ptg.products_id = prd.products_id 
                                                                    INNER JOIN products_description dsc ON dsc.products_id = prd.products_id AND dsc.language_id = 1
                                   WHERE (prd.user_id = '' OR prd.user_id IS NULL) AND prd.products_parent_id='0' 
                                     AND ptg.categories_id = ".$category_id. " ORDER BY prd.products_id ");    
    while ($shapes = tep_db_fetch_array($shapes_query)) {
	
		//Check group access for listed products - Feb 24 2011
		if(tep_customer_access_product($_SESSION['customer_id'], $shapes['products_id'])) {
		
    	    	$query = tep_db_query('SELECT pat.*, po.options_type, po.options_length, pov.products_options_values_name, pov.picture
  			                   FROM products_options_values_to_products_options pvp
  			                   INNER JOIN products_options_values pov ON (pov.products_options_values_id = pvp.products_options_values_id)
  			                   INNER JOIN products_attributes pat ON (pat.options_values_id = pov.products_options_values_id AND pat.products_id = '.$shapes['products_id'].')
					   INNER JOIN products_options po ON (po.products_options_id = pat.options_id)
  			                   WHERE pvp.products_options_id = '.$id.'  and pat.options_id = po.products_options_id ORDER BY products_options_sort_order ASC');  	
			  $fitts = array();
							  
				while ($values = tep_db_fetch_array($query)) {
					$fitts[] = $values;
				}
				//var_dump($fitts);
			  //die();
			  
			  //Set combo box as default Fittings - jan 18 2011  
			  $fit_data['type'] = 2;
			  $fit_data['length'] = 0;
			  
			  foreach($fitts as $fit){
				$fit_data['type'] =  $fit['options_type'];
				$fit_data['length'] =  $fit['options_length'];
			  }
				$max_images_count = -1;
				if($shapes['max_images_count'] === 0){
					$max_images_count = 0;
				} else {
					if(intval($shapes['max_images_count'])){
						$max_images_count = intval($shapes['max_images_count']); 
					}
				}    
				$max_lines_count= -1;
				if($shapes['max_lines_count'] === 0){
					$max_lines_count = 0;
				} else {
					if(intval($shapes['max_lines_count'])){
						$max_lines_count = intval($shapes['max_lines_count']); 
					}
				} 	    	
			 $shapes_list .= "{ productId: " . $shapes['products_id'].", productName: '" . $shapes['products_name']."', src: '". $shapes['products_image'] . "', max_images_count: ".$max_images_count.", max_lines_count: ".$max_lines_count.", ";
			  
			  //get badge settings from products_badge_settings table - Nov 07 2012
			 $badge_settings_arr = tep_get_badge_settings($shapes['products_id']);				
			 $shapes_list .= "allow_bgcolor: " . ($badge_settings_arr["bgcolor"]?$badge_settings_arr["bgcolor"]:'0') . ", ";
			 $shapes_list .= "allow_text_color: " . ($badge_settings_arr["text_color"]?$badge_settings_arr["text_color"]:'0') . ", ";
			 $shapes_list .= "allow_fonts: " . ($badge_settings_arr["fonts"]?$badge_settings_arr["fonts"]:'0') . ", ";
			 //Added on July 31 2014
			 $shapes_list .= "allow_bgbrush: " . ($badge_settings_arr["bgbrush"]?$badge_settings_arr["bgbrush"]:'0') . ", ";
			 
			  $shapes_list .= "fittes_option_type: " .$fit_data['type']. ", ";
			  $shapes_list .= "fittes_option_length: " .$fit_data['length']. ", ";
			  $shapes_list .= "fittes: new Array( ";
			  $i=0;
			  
			  
			  foreach($fitts as $fit){
				$i++;
				$shapes_list .= "new Array('".$fit['products_options_values_name']."', '".number_format($fit['options_values_price'] + round($fit['options_values_price']*0.10, 2),2)."', '".$fit['products_attributes_id']."', '".$fit['picture']."', '".$fit['options_type']."')";
				if($i < count($fitts)){
					$shapes_list .= ', ';
				}      	
			  }
			  $shapes_list .= '), ';
			  
			  
			  /* get all product attributes Start */
		  
				$shapes_list_opt = "";			
				$shapes_list_options_value = "";
							
				$assigned_query = tep_db_query("SELECT DISTINCT options_id FROM " . TABLE_PRODUCTS_ATTRIBUTES . " WHERE products_id = " . (int)$shapes['products_id'] ." GROUP BY options_id");
										
				while ($present_options = tep_db_fetch_array($assigned_query)) {
									
					$options_query = tep_db_query("select po.products_options_id, pot.products_options_name, po.options_type from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " pot where po.products_options_id = '".$present_options['options_id']."' AND pot.products_options_text_id = po.products_options_id and pot.language_id = '" . $lang_id . "' order by po.products_options_sort_order, pot.products_options_name");
					
					$mandatory = get_mandatory((int)$shapes['products_id'],$present_options['options_id']);
					
					//get attributes options
					while ($options_arr = tep_db_fetch_array($options_query)) {
						
						$option_id = $options_arr['products_options_id'];								
						//Build options array	
						$options[$option_id] = array('name' => $options_arr['products_options_name'],
													  'type' => $options_arr['options_type'],
													  'length' => $options_arr['options_length'],
													  'instructions' => $options_arr['products_options_instruct'],
													  'price' => $options_arr['options_values_price'],
													  'prefix' => $options_arr['price_prefix'],
													  'mandatory' => $options_arr['mandatory']
													 );
						$shapes_list_opt .= "new Array('".$options_arr['products_options_id']."', '".$options_arr['products_options_name'] ."', '".$options_arr['options_type']."', '".(($mandatory=="")?0:$mandatory)."'), ";
						
						//if ( $options_arr['options_type'] != 1 && $options_arr['options_type'] != 4 && $options_arr['options_type'] != 5 ) {
							
								//get products options values
									$values_query = tep_db_query("select distinct pov.products_options_values_id, pov.products_options_values_name, pov.options_values_price, pov.picture from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $option_id . "' and pov.language_id = '" . $lang_id . "' order by pov.products_options_values_name");
																
									$values_row = 0;
									$row = 0;
									while ($values = tep_db_fetch_array($values_query)) {
										$row++;
										$value_id = $values['products_options_values_id'];

										$products_values_query = tep_db_query("select products_attributes_id, price_prefix, options_values_price, products_options_sort_order, mandatory, calculate_price_once from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$shapes['products_id'] . "' and options_id = '" . $option_id . "' and options_values_id = '" . (int)$value_id . "'");
										
										if ($products_values = tep_db_fetch_array($products_values_query)) {
												//Build options selected values array
												$options_values[$option_id][$value_id] =  array('name' => stripslashes($values['products_options_values_name']),
												'price' => $options_arr['options_values_price'],
												'prefix' => $options_arr['price_prefix'],
												'mandatory' => $options_arr['mandatory'],
												'picture' => $values['picture']);
													
												$shapes_list_options_value .= "new Array('".$option_id."', '".$value_id ."', '".$values['products_options_values_name']."', '".number_format($products_values['options_values_price'] + round($products_values['options_values_price']*0.10, 2),2)."', '".$values['picture']."'), ";
																							
										}
									
									}	
								//get products options values							
							
						//}					
					
					}
								
				}
				
				
				if(!empty($shapes_list_opt)) {
					$shapes_list_opt = substr($shapes_list_opt,0,-2);
				}			 
				$shapes_list_options = "opts: new Array(" . $shapes_list_opt . ") ";			
				
				
				if(!empty($shapes_list_options_value)) {				
					$shapes_list_options_value = substr($shapes_list_options_value,0,-2);				
				}
				
				$shapes_list_optval = "optval: new Array( " . $shapes_list_options_value . ") ";
				
				$shapes_list .= $shapes_list_options . ", " .$shapes_list_optval .", ";		
			  
			  /* get all products attributes END */
		  
			  $shapes_list .= "default_texts: new Array( ";
			  
			  if ($shapes['default_texts']) {
					$texts = split("\n", $shapes['default_texts']);
				foreach($texts as $text) {
				  $text = trim($text);
					$elements = split(";", $text);
				  $shapes_list .= "{ ";
				  foreach($elements as $element) {
						$name_value = split(":", $element);
					$shapes_list .= $name_value[0].": '". $name_value[1] ."', ";
				  }
				  $shapes_list = rtrim($shapes_list, ', ');
				  $shapes_list .= "}, ";
				}
				$shapes_list = rtrim($shapes_list, ', ');
			  }
			  
			  $shapes_list .= '), ';
			  
			  $borders_list = '';
			  $borders_query = tep_db_query("SELECT prd.*, dsc.products_description FROM products prd INNER JOIN products_description dsc ON prd.products_id = dsc.products_id AND dsc.language_id = 1 WHERE prd.products_parent_id = ".$shapes['products_id']. " ORDER BY prd.products_id ");
			  while ($borders = tep_db_fetch_array($borders_query)) {
				$ok = true; 
				$image_file1 = new image_file(DIR_FS_CATALOG . DIR_WS_IMAGES . $borders['products_image']);
				if ($borders['products_image_lrg']) {
				  $image_file2 = new image_file(DIR_FS_CATALOG . DIR_WS_IMAGES . $borders['products_image_lrg']);
				}
				$borders_list .= "{ src: '". $borders['products_image'] . "'".
								 ", src_real_size: '". $borders['products_image_med'] . "'".
								 ", src_inner: '". $borders['products_image_lrg'] . "'".
								 ", src_inner_real_size: '". $borders['products_image_rls2'] . "'".
								 ", color: '" . $borders['products_description'] . "'".
								 ", width: " . ($image_file1->width?$image_file1->width:'0') . 
								 ", height: " . ($image_file1->height?$image_file1->height:'0') . 
								 ", width_inner: " . ($borders['products_image_lrg']?($image_file2->width?$image_file2->width:'0'):'null'). 
								 ", height_inner: " . ($borders['products_image_lrg']?($image_file2->height?$image_file2->height:'0'):'null'). 
								 ", padding: 8".
								 "}, ";
			  }
			  $shapes_list .= "borders: new Array(" . rtrim($borders_list, ', ') . ") }, ";
			  
		} //End of Group Access checking condition
		
	} // End of products shapes while loop
	
    $shapes_list = 'new Array( ' . rtrim($shapes_list, ', ') . ' ) ';
    
    if ($ok) {    	
      return $shapes_list;
    }
  }
  
  return null;

}

function get_badge_data($product_id) {	

  if ($product_id) {
  	$query = tep_db_query(placeholder('SELECT badge_data FROM products WHERE products_id = ?', $product_id));
  	$result = tep_db_fetch_array($query);
  	return $result['badge_data'];
  }
  
  return null;

}
function get_badge_fitting_data($product_id,$lang_id) {	

  if ($product_id) {
	$query = tep_db_query("SELECT products_name FROM products_description WHERE products_id = '".$product_id."' AND language_id='".$lang_id."'");
	$result = tep_db_fetch_array($query);
  	return addslashes($result['products_name']);
  }
  return null;

}

//get all options
function get_badge_fittings($product_id) {	

  if(!empty($product_id)) {
	$query = tep_db_query("SELECT * FROM products_badge_options WHERE products_id = '".$product_id."'");
	$fittings = null;
	while($result = tep_db_fetch_array($query)) {
		$fittings .= "new Array('".$result['options_id']."', '".$result['options_values_id'] ."', '".$result['options_type']."', '".$result['options_text'] ."'), ";
	}
	if(!empty($fittings)) { return substr($fittings,0,-2); }
	else { return null; }
  	
  }
  return null;
}

function delete_files($str){
      if(is_file($str)){
          return @unlink($str);
      }
      elseif(is_dir($str)){
          $scan = glob(rtrim($str,'/').'/*');
          foreach($scan as $index=>$path){
              delete_files($path);
          }
          return true;
      }
}

//badge options updation - Dec 21 2012
function tep_update_products_badge_options($products_id,$opid,$optype,$opvid,$optext,$opdesc) {
	$qry = mysql_query("SELECT * FROM products_badge_options WHERE products_id='".$products_id."' AND options_id='".$opid."'");
	if(mysql_num_rows($qry)>0) {
		while($oparr = mysql_fetch_array($qry)) {
			if($optype == $oparr["options_type"]) {
				mysql_query("UPDATE products_badge_options SET options_values_id='".$opvid."', options_text='".$optext."', options_desc='".$opdesc."' WHERE products_badge_options_id='".$oparr["products_badge_options_id"]."'");
			}
		}
	} else {
		mysql_query("INSERT INTO products_badge_options SET products_id='".$products_id."', options_id='".$opid."', options_type='".$optype."', options_values_id='".$opvid."', options_text='".$optext."', options_desc='".$opdesc."'");
	}
}

function get_mandatory($products_id,$options_id) {
	$query = mysql_query("select mandatory from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "' and options_id = '" . $options_id . "'");
	if(mysql_num_rows($query)>0) {
		$oparr = mysql_fetch_array($query);
		return $oparr["mandatory"];
	}	
	return false;
}

ob_flush();
?>