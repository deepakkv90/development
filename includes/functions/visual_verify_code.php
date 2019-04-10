<?php
/*
///////////////////////////////////////////////////
  file: visual_verify_code.php,v 1.0 26SEP03

Written for use with:
  osCommerce, Open Source E-Commerce Solutions http://www.oscommerce.com
Part of Contribution Named:
  Visual Verify Code (VVC) by William L. Peer, Jr. (wpeer@forgepower.com) for www.onlyvotives.com

[Modified By] [Date] [Mods Made]
-------------------------------------------


-------------------------------------------
*/

//////////////////////////////
/* This funtion has the responsibility of displaying the actual visual code with random results.
   It randomly picks an x and y position as well as font size for each character in the visual code
*/
function vvcode_render_code($code) {
        if (!empty($code)) {
            $imwidth = VVC_IMAGE_WIDTH;
            $imheight= VVC_IMAGE_HEIGHT;
         $font_size = $imheight * 0.75;
         $font_position = $imheight * 0.80;

            Header("Content-type: image/Jpeg");
            $im = ImageCreate ($imwidth, $imheight) or die ("Cannot Initialize new GD image stream");
    // parse RBG color for background to seperate values
         $background_color_1 = explode(',' , VVC_BACKGROUND_COLOR);
         $background_color_R = $background_color_1[0];
         $background_color_G = $background_color_1[1];
         $background_color_B = $background_color_1[2];

    // parse RBG color for text to seperate values
         $text_color_1 = explode(',' , VVC_TEXT_COLOR);
         $text_color_R = $text_color_1[0];
         $text_color_G = $text_color_1[1];
         $text_color_B = $text_color_1[2];

    // parse RBG color for text to seperate values
         $border_color_1 = explode(',' , VVC_BORDER_COLOR);
         $border_color_R = $border_color_1[0];
         $border_color_G = $border_color_1[1];
         $border_color_B = $border_color_1[2];

    // parse RBG color for text to seperate values
         $noise_color_1 = explode(',' , VVC_NOISE_COLOR);
         $noise_color_R = $noise_color_1[0];
         $noise_color_G = $noise_color_1[1];
         $noise_color_B = $noise_color_1[2];

         $background_color = ImageColorAllocate ($im, $background_color_R, $background_color_G, $background_color_B);
         $text_color = ImageColorAllocate ($im, $text_color_R, $text_color_G, $text_color_B);
         $border_color = ImageColorAllocate ($im, $border_color_R, $border_color_G, $border_color_B);
         $noise_color = ImageColorAllocate($im, $noise_color_R, $noise_color_G, $noise_color_B);

     //Sets what back groun noise to use or turn off
     if (VVC_GENERATE_BACKGROUND_NOISE == 'Off'){
       $dots_on = 'false';
       $lines_on = 'false';
          }else if (VVC_GENERATE_BACKGROUND_NOISE == 'Dots') {
       $dots_on = 'true';
       $lines_on = 'false';
     }else if (VVC_GENERATE_BACKGROUND_NOISE == 'Lines') {
       $dots_on = 'false';
       $lines_on = 'true';
     }else if (VVC_GENERATE_BACKGROUND_NOISE == 'Dots and Lines') {
       $dots_on = 'true';
       $lines_on = 'true';
     }else{
       $dots_on = 'false';
       $lines_on = 'false';
     }

     // generate random dots in background
     if($dots_on == 'true'){
          for( $n=0; $n<($imwidth*$imheight)/3; $n++ ) {
          imagefilledellipse($im, mt_rand(0,$imwidth), mt_rand(0,$imheight), 1, 1, $noise_color);
          }
         }
     // generate random lines in background
     if($lines_on == 'true'){
          for( $n=0; $n<($imwidth*$imheight)/150; $n++ ) {
          imageline($im, mt_rand(0,$imwidth), mt_rand(0,$imheight), mt_rand(0,$imwidth), mt_rand(0,$imheight), $noise_color);
          }
        }

         //strip any spaces that may have crept in
            //end-user would not know to type the space! :)
            $code = str_replace(" ", "", $code);
            $x=0;

            $stringlength = strlen($code);

            for ($i = 0; $i< $stringlength; $i++) {
               $x = $x + $font_size;
               $y = $font_position ;
                if(VVC_ROTATE_CHAR == 'On'){
               $angle = mt_rand(-20, 20);
               } else {
               $angle ='0';
               }
                 $font = 'includes/fonts/' . VVC_FONTS . '.ttf' ;
                 $image_font_size = VVC_FONT_SIZE;
                 $single_char = substr($code, $i, 1);
                //  imagechar($im, $font, $x, $y, $single_char, $text_color);
                 //imagettftext($im, $font, $x, $y, $single_char, $text_color);
                 imagettftext($im, $image_font_size, $angle, $x, $y, $text_color, $font, $single_char);
                }


            imagerectangle ($im, 0, 0, $imwidth-1, $imheight-1, $border_color);
            ImageJpeg($im);
            ImageDestroy($im);
        }
  }
?>
