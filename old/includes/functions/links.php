<?php
/*
  $Id: links.php,v 1.1.1.1 2004/03/04 23:40:51 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

////
// Construct a path to the link
// TABLES: links_to_link_categories
  function tep_get_link_path($links_id) {
    $lPath = '';

    $category_query = tep_db_query("select l2c.link_categories_id from " . TABLE_LINKS . " l, " . TABLE_LINKS_TO_LINK_CATEGORIES . " l2c where l.links_id = '" . (int)$links_id . "' and l.links_id = l2c.links_id limit 1");
    if (tep_db_num_rows($category_query)) {
      $category = tep_db_fetch_array($category_query);

      $lPath .= $category['link_categories_id'];
    }

    return $lPath;
  }


////
// The HTML image wrapper function

function tep_links_image($src, $alt = '', $width = '', $height = '', $parameters = '') {
 global $src_local;
 //if no image, or path has just theimage directory, and a image is required dont do anything
    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return ('no image');
    }
 //initialize varible
  $test_image_local = '0'; //does $src have a http://
  $test_image_local_1 = '0'; //does $src match the site
  $test_image_local = '0';
  $links_image = '';

           //if http:// check to see if local or external url
           //need to strip from $src   http:// , https:// and any trailing path info
      $test_src_image = parse_url_compat($src, PHP_URL_HOST);
          //need to strip from site url  http:// , https:// and any trailing path info
      $test_http_server_image_1 =  parse_url_compat(HTTP_SERVER, PHP_URL_HOST);
          //strip www.
      $test_http_server_image = strstr($test_http_server_image_1, 'www.');

    if (($test_http_server_image_1 == $test_src_image) || ($test_src_image == '') ){
         $test_image_local = '1';
         }

    if ($test_src_image == '')  {
    $test_image_local = '2';
    }
              $image_path = parse_url_compat($src, PHP_URL_PATH);
              $src_local = DIR_FS_CATALOG . strstr($image_path , 'images');

   if ($test_image_local == '0'){
    $links_image = tep_links_image_resize_external($src, $alt, LINKS_IMAGE_WIDTH, LINKS_IMAGE_HEIGHT, $parameters);
  }

   if ($test_image_local == '2'){
               $image_path = parse_url_compat($src, PHP_URL_PATH);
               $src_local = DIR_FS_CATALOG . strstr($image_path , 'images');
               $src_web_local =  DIR_WS_CATALOG . strstr($image_path , 'images');
  $links_image = tep_links_image_resize_local($src_web_local, $alt, LINKS_IMAGE_WIDTH, LINKS_IMAGE_HEIGHT, $parameters);
    }

   if ($test_image_local == '1'){
  $links_image = tep_links_image_resize_local($src, $alt, LINKS_IMAGE_WIDTH, LINKS_IMAGE_HEIGHT, $parameters);
   }
 return($links_image);
}

////
// do not caculate image size portion and uses $width = '', $height = ''

function tep_links_image_resize_external($src, $alt = '', $width = '', $height = '', $parameters = '') {
    $links_image = '<img src="' . tep_output_string($src) . '" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) {
      $links_image .= ' title=" ' . tep_output_string($alt) . ' "';
    }
    if (tep_not_null($width)) {
      $links_image .= ' width="' . tep_output_string($width) . '"';
    }
     if (tep_not_null($height)){
      $links_image .= ' height="' . tep_output_string($height) . '"';
    }

    if (tep_not_null($parameters)){
    $links_image .= ' ' . $parameters;
     }

    $links_image .= '>';

    return($links_image);
}


////
// calculate poportion size
 function tep_links_image_resize_local($src, $alt = '', $width = '', $height = '', $parameters = '') {
    //test to see if http in $src
     //get local path to file for getimagesize
              $image_path = parse_url_compat($src, PHP_URL_PATH);
              $src_local = DIR_FS_CATALOG . strstr($image_path , 'images');



    $image = '<img src="' . tep_output_string($src) . '" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) {
      $image .= ' title=" ' . tep_output_string($alt) . ' "';
    }

    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
      if ($image_size = getimagesize($src_local)) {
        if (empty($width) && tep_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = $image_size[0] * $ratio;
        } elseif (tep_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = $image_size[1] * $ratio;
        } elseif (empty($width) && empty($height)) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }
  //results should be whole number with no decimal points
          $width = round($width, 0);
          $height = round($height, 0);

    if (tep_not_null($width) && tep_not_null($height)) {
      $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
    }

    if (tep_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= '>';

    return $image;
  }





////////////////////////////////////////////////////////

////
// Return the links url, based on whether click count is turned on/off
  function tep_get_links_url($identifier) {
    $links_query = tep_db_query("select links_id, links_url from " . TABLE_LINKS . " where links_id = '" . (int)$identifier . "'");

    $link = tep_db_fetch_array($links_query);

    if (ENABLE_LINKS_COUNT == 'True') {
      if (ENABLE_SPIDER_FRIENDLY_LINKS == 'True') {
        $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
        $spider_flag = false;

        if (tep_not_null($user_agent)) {
          $spiders = file(DIR_WS_INCLUDES . 'spiders.txt');

          for ($i=0, $n=sizeof($spiders); $i<$n; $i++) {
            if (tep_not_null($spiders[$i])) {
              if (is_integer(strpos($user_agent, trim($spiders[$i])))) {
                $spider_flag = true;
                break;
              }
            }
          }
        }

        if ($spider_flag == true) {
          $links_string = $link['links_url'];
        } else {
          $links_string = tep_href_link(FILENAME_REDIRECT, 'action=links&amp;goto=' . $link['links_id']);
        }
      } else {
          $links_string = tep_href_link(FILENAME_REDIRECT, 'action=links&amp;goto=' . $link['links_id']);
      }
    } else {
      $links_string = $link['links_url'];
    }

    return $links_string;
  }

////
// Update the links click statistics
  function tep_update_links_click_count($links_id) {
    tep_db_query("update " . TABLE_LINKS . " set links_clicked = links_clicked + 1 where links_id = '" . (int)$links_id . "'");
  }
?>
