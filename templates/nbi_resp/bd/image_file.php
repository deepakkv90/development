<?php

function image_lib_supported() {

  return ((function_exists("ImageCreateFromGIF")) and
          (function_exists("ImageCreateFromJPEG")) and
          (function_exists("ImageCreateFromPNG")) and
          (function_exists("ImageCreateFromWBMP")));

}

class image_file {
  
  var $image;
  var $format;
  var $width;
  var $height;
  var $valid;

  function image_file($imgfile) {
    
    $old_error_reporting = error_reporting();
    error_reporting(0);

    if (image_lib_supported()) {
      $this->valid = true;
      if ($this->image = ImageCreateFromPNG ($imgfile)) {
        $this->format = "PNG";
	  }
	  elseif ($this->image = ImageCreateFromGIF ($imgfile)) {
        $this->format = "GIF";
	  }	
      elseif ($this->image = ImageCreateFromJPEG ($imgfile)) {
        $this->format = "JPEG";
        $this->dpi = $this->get_jpeg_dpi($imgfile);
      }      
      elseif ($this->image = ImageCreateFromWBMP ($imgfile)) {
        $this->format = "WBMP";
	  }	
      else {
        $this->valid = false;
	  }
    } else {
      $this->valid = false;
    }


    if ($this->valid) {
      $this->width = imagesx($this->image);
      $this->height = imagesy($this->image);
    }

    error_reporting($old_error_reporting);
  }

  function get_jpeg_dpi($filename) {

    // open the file and read first 20 bytes.
    if ($a = @fopen($filename, 'r')) {
      $string = fread($a,20);
      fclose($a);

      // get the value of byte 14th up to 18th
      $data = bin2hex(substr($string,14,4));
      $x = substr($data,0,4);
      $y = substr($data,4,4);
      return array('x' => hexdec($x), 'y' => hexdec($y));
    } else {
      return array('x' => 0, 'y' => 0);
    }

  }

  function width() { 
    
    if ($this->valid) 
      return $this->width; 
      
  }
  
  function height() { 
    
    if ($this->valid) 
      return $this->height; 
      
  }

  function copy($x, $y, $width, $height, $output_file_name) {

    if (function_exists("ImageCreateTrueColor"))
      $new_image = imagecreatetruecolor($width, $height);
    else
      $new_image = imagecreate($width, $height);
      
    @imagecopy ( $new_image
               , $this->image
               , 0
               , 0
               , $x
               , $y
               , $width
               , $height
               );

    if ($this->format == "JPG" || $this->format == "JPEG") {
      return imageJPEG($new_image, $output_file_name, 750);
    } elseif ($this->format == "PNG") {
      return imagePNG($new_image, $output_file_name);
    } elseif ($this->format == "GIF") {
      return imageGIF($new_image, $output_file_name);
    } elseif ($this->format == "WBMP") {
      return imageWBMP($new_image, $output_file_name);
    } else
      return false;

  }

  function resize($width, $height, $output_file_name) {
    
    if (function_exists("ImageCreateTrueColor"))
      $new_image = ImageCreateTrueColor($width, $height);
    else
      $new_image = ImageCreate($width, $height);
      
    if (function_exists("imagecopyresampled"))
      @imagecopyresampled ( $new_image
                        , $this->image
                        , 0
                        , 0
                        , 0
                        , 0
                        , $width
                        , $height
                        , $this->width
                        , $this->height
                        );
    else
      @imagecopyresized ( $new_image
                        , $this->image
                        , 0
                        , 0
                        , 0
                        , 0
                        , $width
                        , $height
                        , $this->width
                        , $this->height
                        );

    if ($this->format == "JPG" || $this->format == "JPEG") {
      return imageJPEG($new_image, $output_file_name, 750);
    } elseif ($this->format == "PNG") {
      return imagePNG($new_image, $output_file_name);
    } elseif ($this->format == "GIF") {
      return imageGIF($new_image, $output_file_name);
    } elseif ($this->format == "WBMP") {
      return imageWBMP($new_image, $output_file_name);
    } else
      return false;
    
  }
  
}

function make_thumbnail($source_file, $desired_width, $desired_height, $destination_file = null, $overwrite = false) {

  if (!$destination_file) {
    $destination_file = $source_file;
    $pathinfo = pathinfo($source_file);
    if (isset($pathinfo['extension']))
      $destination_file = str_replace($pathinfo['extension'], $desired_width.'-'.$desired_height.'.'.$pathinfo['extension'], $destination_file);
  } 
                                  
  if (file_exists($source_file)) {
    if (!file_exists($destination_file) or $overwrite) {
      $image_file = new image_file($source_file);
      if ($image_file->valid) { 
        if ($image_file->width() > $desired_width) {
          $new_width = $desired_width;
          $new_height = round($image_file->height() * ($new_width * 100 / $image_file->width()) / 100);

          if ($new_height > $desired_height) {
            $new_height_before = $new_height;
            $new_height = $desired_height;
            $new_width = round($new_width * ($new_height * 100 / $new_height_before) / 100);
          } 

        } else 
        if ($image_file->height() > $desired_height) {
          $new_height = $desired_height;
          $new_width = round($image_file->width() * ($new_height * 100 / $image_file->height()) / 100);

          if ($new_width > $desired_width) {
            $new_width_before = $new_width;
            $new_width = $desired_width;
            $new_height = round($new_height * ($new_width * 100 / $new_width_before) / 100);
          }
        } else {
          $new_width = $desired_width;
          $new_height = round($image_file->height() * ($new_width * 100 / $image_file->width()) / 100);

          if ($new_height > $desired_height) {
            $new_height_before = $new_height;
            $new_height = $desired_height;
            $new_width = round($new_width * ($new_height * 100 / $new_height_before) / 100);
          } 
        }
        if ($image_file->resize($new_width, $new_height, $destination_file))
          return $destination_file;
        else
          return null;  
      } else
        return null;
    } else 
      return $destination_file;
  } else 
    return null;
          
}

function copy_image_part($source_file, $source_x, $source_y, $width, $height, $destination_file = null, $overwrite = false) {

  if (!$destination_file) {
    $destination_file = $source_file;
    $pathinfo = pathinfo($source_file);
    if (isset($pathinfo['extension']))
      $destination_file = str_replace($pathinfo['extension'], $width.'-'.$height.'.'.$pathinfo['extension'], $destination_file);
  } 
                                  
  if (file_exists($source_file)) {
    if (!file_exists($destination_file) or $overwrite) {
      $image_file = new image_file($source_file);
      if ($image_file->valid) { 
        if (($image_file->height() >= $height) || ($image_file->width() >= $width)) {
          if ($image_file->copy($source_x, $source_y, min($width, $image_file->width()), min($height, $image_file->height()), $destination_file))
            return $destination_file;
          else
            return null;  
        } else {
          if ($destination_file) {
            copy($source_file, $destination_file);
            return $destination_file;
          } else
            return $source_file;
        }
      } else
        return null;
    } else 
      return $destination_file;
  } else 
    return null;
          
}

function is_image_file($path) {
  
  $image = new image_file($path);
  return $image->valid;
  
}

?>