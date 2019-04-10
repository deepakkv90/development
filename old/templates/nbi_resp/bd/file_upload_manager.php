<?php

/**
 * Project:     Generic: the PHP framework
 * File:        file_upload_manager.php
 *
 * @version 1.0.0.0
 * @package Generic
 */

/**
 * UPLOAD
 * @package Generic
 */


class file_upload_manager {

  function render_file($file, $hide_legend = false, $tiny_mode = false) {

    if ($tiny_mode) {
      if (safe($file, 'image_url')) {
        echo '<img id="'.$file['uid'].'" width="'.($file['width']<$file['image_width']?$file['width']:$file['image_width']).'" class="fum_image" src="'.$file['thumbnail_url'].'" border="0" rel="'.$file['remove_js'].'"';
        if (safe($file, 'remove_js')) 
          echo ' rel="'.$file['remove_js'].'" ';
        echo '/>';
      } 
    } else {
      echo '<div class="fum_container" style="width:'.$file['width'].'px;" id="'.$file['uid'].'">';
      echo '<div class="fum_container_file" style="width:'.$file['width'].'px;height:'.$file['height'].'px;">';
      echo '<center>';
      if (safe($file, 'image_url')) {
        echo '<a href="'.$file['image_url'].'" target="_blank">';
        echo '<img src="'.$file['thumbnail_url'].'" border="0" /></a><br />';
      } else {
        echo '<span>'.$file['file_type'].'</span>';
      }
      echo '</center>';
      echo '</div>';
      echo '<center>';
      if (!$hide_legend) {
        echo '<span>'.$file['file_name'].'<br />'.number_format($file['file_size']).' bytes</span><br />';
      } 
      if (safe($file, 'remove_js')) 
        echo '<a onclick="'.$file['remove_js'].'">Delete</a>';
      echo '</center>';
      echo '</div>';
    }

  }
   function render_name_file($file, $hide_legend = false, $tiny_mode = false) {

    if ($tiny_mode) {
      if (safe($file, 'image_url')) {
        echo '<img id="'.$file['uid'].'" width="'.($file['width']<$file['image_width']?$file['width']:$file['image_width']).'" class="fum_name" src="'.$file['thumbnail_url'].'" border="0" rel="'.$file['remove_js'].'"';
        if (safe($file, 'remove_js')) 
          echo ' rel="'.$file['remove_js'].'" ';
        echo '/>';
      } 
    } else {
      echo '<div class="fum_container" style="width:'.$file['width'].'px;" id="'.$file['uid'].'">';
      echo '<div class="fum_container_file" style="width:'.$file['width'].'px;height:'.$file['height'].'px;">';
      echo '<center>';
      if (safe($file, 'image_url')) {
        echo '<a href="'.$file['image_url'].'" target="_blank">';
        echo '<img src="'.$file['thumbnail_url'].'" border="0" /></a><br />';
      } else {
        echo '<span>'.$file['file_type'].'</span>';
      }
      echo '</center>';
      echo '</div>';
      echo '<center>';
      if (!$hide_legend) {
        echo '<span>'.$file['file_name'].'<br />'.number_format($file['file_size']).' bytes</span><br />';
      } 
      if (safe($file, 'remove_js')) 
        echo '<a onclick="'.$file['remove_js'].'">Delete</a>';
      echo '</center>';
      echo '</div>';
    }

  }
  
  function handler() {
    switch (get('__fumMethod')) {
      case 'upload':
        $selector_id = get('selectorId');
		$max_filesize = 5250000; //5mb
		//ini_set ( 'max_execution_time', 300);
		if(filesize($_FILES[$selector_id]['tmp_name']) < $max_filesize) {
        if (safe($_FILES, $selector_id)) {
          $uid          = guid();
          $session_id   = get('sessionId');
          $width        = get('width');
          $height       = get('height');
          $image_check  = get('imageCheck');
          $images_limit = get('imagesLimit');
          $hide_legend  = get('hideLegend');
          $tiny_mode    = get('tinyMode');
          $make_thumb   = get('makeThumb');
          $base_url     = get('baseUrl');
          require_once('image_file.php');
          $image_file = new image_file($_FILES[$selector_id]['tmp_name']);
      	  if (!$image_check || $image_file->valid) {
            if ($image_file->valid) {
              $file_type = strtolower($image_file->format);
            } else {
              $pathinfo = pathinfo($_FILES[$selector_id]['name']);
              $file_type = strtolower($pathinfo['extension']);
            } 
            $tmp_file_path = TEMPORARY_PATH.$uid.'.'.$file_type;
            if (move_uploaded_file($_FILES[$selector_id]['tmp_name'], $tmp_file_path)) {
              if ($image_file->valid && $width && $height) {
                $tmp_thumb_path = TEMPORARY_PATH.$uid.'-thumb.'.strtolower($image_file->format);
                make_thumbnail($tmp_file_path, $width, $height, $tmp_thumb_path);
              }
              if ($images_limit)
                $upload_session = array();
              else
                $upload_session = session($session_id);
              $remove_js = "$.get('".$base_url."badge_designer.php?__fumMethod=delete&uid=$uid&sessionId=$session_id');$('#$uid').remove();";
              $file = array( 'file_name'      => $_FILES[$selector_id]['name']
                           , 'file_size'      => filesize($tmp_file_path)
                           , 'tmp_file_path'  => $tmp_file_path
                           , 'remove_js'      => $remove_js
                           , 'uid'            => $uid
                           , 'width'          => $width
                           , 'height'         => $height
                           , 'file_type'      => $file_type
                           );
              if ($image_file->valid && $width && $height) {
                $file['tmp_thumb_path'] = $tmp_thumb_path;
                $file['image_format']   = $file_type;
                $file['image_width']    = $image_file->width;
                $file['image_height']   = $image_file->height;
                $file['image_url']      = $base_url."badge_designer.php?__fumMethod=show&uid=$uid&sessionId=$session_id&original=1";
                if ($make_thumb) {
                  $file['thumbnail_url']  = $base_url."badge_designer.php?__fumMethod=show&uid=$uid&sessionId=$session_id";
                } else {
                  $file['thumbnail_url']  = $file['image_url'];
                }
              }
              $upload_session[$uid] = $file;

              $_SESSION[$session_id] = $upload_session;

              $this->render_file($file, $hide_legend, $tiny_mode);

              exit();
            } else {
              echo "There is an error occurred while uploading the file";
            }
      	  } else {
            echo "Please select JPEG|JPG|PNG|GIF images only";
          }
        } else {
          echo "There is an error occurred while uploading the file";
        }
		} else {
			echo "Please upload the files less than 5MB. The file you attempted to upload is too large.";
		} 
        exit();
        break;
      case 'show':
        if ($uid = get('uid')) {
          $session_id = get('sessionId');
          $upload_session = session($session_id);
          if (safe($upload_session, $uid)) {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
            header('Content-Type: image/jpeg');
            header("Content-Type: image/".$upload_session[$uid]['image_format']);
            if (get('original')) {
              header("Content-Length: ".filesize($upload_session[$uid]['tmp_file_path']));
              readfile($upload_session[$uid]['tmp_file_path']);
            } else {
              header("Content-Length: ".filesize($upload_session[$uid]['tmp_thumb_path']));
              readfile($upload_session[$uid]['tmp_thumb_path']);
            } 
          } 
        }
        exit();
        break;
      case 'render':
        if ($session_id = get('sessionId')) {
          $upload_session = session($session_id);
          if (is_array($upload_session)) {
            $hide_legend = get('hideLegend');
            $tiny_mode   = get('tinyMode');
            $base_url    = get('baseUrl');
            foreach($upload_session as $uid => $file) {
              if (!safe($file, 'is_removed')) {
                if (!safe($file, 'remove_js')) {
                  $file['remove_js'] = "$.get('".$base_url."badge_designer.php?__fumMethod=delete&uid=".$file['uid']."&sessionId=$session_id');$('#".$file['uid']."').remove();";
                }
                $this->render_file($file, $hide_legend, $tiny_mode);
              }
            }
          }
        }
        exit();
        break;
      case 'clear':
        if ($session_id = get('sessionId')) {
          $upload_session = session($session_id);
          if (is_array($upload_session)) {
            foreach($upload_session as $uid => $file) {
              if (safe($file, 'is_preloaded')) {
                $upload_session[$uid]['is_removed'] = true;
              } else {
                if (file_exists(safe($file, 'tmp_thumb_path')))
                  unlink($file['tmp_thumb_path']);
                if (file_exists(safe($file, 'tmp_file_path')))
                  unlink($file['tmp_file_path']);
                unset($upload_session[$uid]);
              }
            }
            $_SESSION[$session_id] = $upload_session;
          }
        }
        exit();
        break;
      case 'delete':
        if ($uid = get('uid')) {
          $session_id = get('sessionId');
          $upload_session = session($session_id);
          if ($file = safe($upload_session, $uid)) {
            if (safe($file, 'is_preloaded')) {
              $upload_session[$uid]['is_removed'] = true;
            } else {
              if (file_exists(safe($file, 'tmp_thumb_path')))
                unlink($file['tmp_thumb_path']);
              if (file_exists(safe($file, 'tmp_file_path')))
                unlink($file['tmp_file_path']);
              unset($upload_session[$uid]);
            }
            $_SESSION[$session_id] = $upload_session;
          } 
        }
        exit();
        break;
      default:
        echo('');
        exit();
        break;
    } 

  }
 ////////////////////////////////// Function for multi names upload /////////////////////////////////////////////////////////
    function nameshandler() {
    switch (get('__fumMethodNames')) {
      case 'name_upload':
        $selector_id = get('selectorId');
		$max_filesize = 5250000; //5mb
		//ini_set ( 'max_execution_time', 300);
		if(filesize($_FILES[$selector_id]['tmp_name']) < $max_filesize) {
        if (safe($_FILES, $selector_id)) {
          //$uid          = guid(); //Modified Oct 11, 2010 as below assignment line - 271         
		  $session_id   = get('sessionId'); 
          $width        = get('width');
          $height       = get('height');
          $image_check  = get('imageCheck');
          $images_limit = get('imagesLimit');
          $hide_legend  = get('hideLegend');
          $tiny_mode    = get('tinyMode');
          $make_thumb   = get('makeThumb');
          $base_url     = get('baseUrl');
		 
          require_once('image_file.php');
		  
			$file_extension = array('csv','CSV','txt','TXT', 'xls','XLS','xlsx','XLSX','ODT','odt');
			$file_type = explode('.',$_FILES[$selector_id]['name']);
				if(!in_array($file_type[1],$file_extension) && $_FILES[$selector_id]['error'] != 4){
					echo "Please select .xls, .csv, .odt or .txt files only";
				}			  
		  $pathinfo 		= pathinfo($_FILES[$selector_id]['name']);
		  $file_type 		= strtolower($pathinfo['extension']);
		  $file_name 		= preg_replace("/\W+/", "-",$pathinfo['filename']); //modified oct 11, 2010
		  $uid				= guid()."-fn-".$file_name.'_xn'.$file_type;
		  $tmp_file_path 	= TEMPORARY_PATH.$uid.'.'.$file_type;
		  
            if (move_uploaded_file($_FILES[$selector_id]['tmp_name'], $tmp_file_path)) {
              if ($images_limit)
                $upload_session = array();
              else
                $upload_session = session($session_id);
              $remove_js = "$.get('".$base_url."badge_designer.php?__fumMethodNames=delete&uid=$uid&sessionId=$session_id');$('#$uid').remove();";
              $file = array( 'file_name'      => $_FILES[$selector_id]['name']
                           , 'file_size'      => filesize($tmp_file_path)
                           , 'tmp_file_path'  => $tmp_file_path
                           , 'remove_js'      => $remove_js
                           , 'uid'            => $uid
                           , 'width'          => $width
                           , 'height'         => $height
                           , 'file_type'      => $file_type
                           );
                $file['tmp_thumb_path'] = $tmp_thumb_path;
                $file['image_format']   = $file_type;
                $file['image_width']    = $image_file->width;
                $file['image_height']   = $image_file->height;
                $file['image_url']      = $base_url."badge_designer.php?__fumMethodNames=show&uid=$uid&sessionId=$session_id&original=1"; //modified as below oct 11, 2010
				//$file['image_url']      = $uid . "." . $file_type;
                $file['thumbnail_url']  = $file['image_url'];
              	
				$upload_session[$uid] = $file;

              $_SESSION[$session_id] = $upload_session;

              $this->render_name_file($file, $hide_legend, $tiny_mode);

              exit();
            } else {
              echo "There is an error occurred while uploading the file";
            }
        } else {
          echo "There is an error occurred while uploading the file";
        }
		} else {
			echo "Please upload the files less than 5MB. The file you attempted to upload is too large.";
		}
        exit();
        break;
      case 'show':
        if ($uid = get('uid')) {
          //$session_id = get('sessionId')."_file";
		  $session_id   = get('sessionId'); //modified as above oct 07, 2010
          $upload_session = session($session_id);
          if (safe($upload_session, $uid)) {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
            header("Content-Type: text/plain");
            header("Content-Type: application/".$upload_session[$uid]['image_format']);
            if (get('original')) {
              header("Content-Length: ".filesize($upload_session[$uid]['tmp_file_path']));
              readfile($upload_session[$uid]['tmp_file_path']);
            } else {
              header("Content-Length: ".filesize($upload_session[$uid]['tmp_thumb_path']));
              readfile($upload_session[$uid]['tmp_thumb_path']);
            } 
          } 
        }
        exit();
        break;
      case 'new_render':        
		if ($session_id = get('sessionId')) { 
          $upload_session = session($session_id);
          if (is_array($upload_session)) {
            $hide_legend = get('hideLegend');
            $tiny_mode   = get('tinyMode');
            $base_url    = get('baseUrl');
            foreach($upload_session as $uid => $file) {
              if (!safe($file, 'is_removed')) {
                if (!safe($file, 'remove_js')) {
                  $file['remove_js'] = "$.get('".$base_url."badge_designer.php?__fumMethodNames=delete&uid=".$file['uid']."&sessionId=$session_id');$('#".$file['uid']."').remove();";
                }
                $this->render_name_file($file, $hide_legend, $tiny_mode);
              }
            }
          }
        }
        exit();
        break;
      case 'clear':
        //if ($session_id = get('sessionId')."_file") {
		if ($session_id = get('sessionId')) { //modified as above oct 07, 2010
          $upload_session = session($session_id);
          if (is_array($upload_session)) {
            foreach($upload_session as $uid => $file) {
              if (safe($file, 'is_preloaded')) {
                $upload_session[$uid]['is_removed'] = true;
              } else {
                if (file_exists(safe($file, 'tmp_thumb_path')))
                  unlink($file['tmp_thumb_path']);
                if (file_exists(safe($file, 'tmp_file_path')))
                  unlink($file['tmp_file_path']);
                unset($upload_session[$uid]);
              }
            }
            $_SESSION[$session_id] = $upload_session;
          }
        }
        exit();
        break;
      case 'delete':
        if ($uid = get('uid')) {
          $session_id = get('sessionId'); //modifies as below oct 07, 2010
		  //$session_id = get('sessionId')."_file";
          $upload_session = session($session_id);
          if ($file = safe($upload_session, $uid)) {
            if (safe($file, 'is_preloaded')) {
              $upload_session[$uid]['is_removed'] = true;
            } else {
              if (file_exists(safe($file, 'tmp_thumb_path')))
                unlink($file['tmp_thumb_path']);
              if (file_exists(safe($file, 'tmp_file_path')))
                unlink($file['tmp_file_path']);
              unset($upload_session[$uid]);
            }
            $_SESSION[$session_id] = $upload_session;
          } 
        }
        exit();
        break;
      default:
        echo('');
        exit();
        break;
    } 

  }

}

?>