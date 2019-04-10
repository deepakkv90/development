<?php
/*
  $Id: datafeed_functions.php,v 1.0.0 2009/10/01 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  
  function cre_get_feed_language_name($id){
      if($id == 0){
          $language_name = 'All Languages';
      } else {
          $lang_qry = tep_db_query("select name from " . TABLE_LANGUAGES . " where languages_id = " . $id);
          $lang_name = tep_db_fetch_array($lang_qry);
          $language_name = $lang_name['name'];
      }
      
      return $language_name;
  }
  
  function cre_get_languages() {
    $languages_query = tep_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " order by sort_order");
    while ($languages = tep_db_fetch_array($languages_query)) {
      $languages_array[] = array('id' => $languages['languages_id'],
                                 'name' => $languages['name'],
                                 'code' => $languages['code'],
                                 'image' => $languages['image'],
                                 'directory' => $languages['directory']);
    }

    return $languages_array;
  }


 function tep_feeder_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
    global $request_type, $session_started, $SID, $spider_flag;

    if (!tep_not_null($page)) {
      die('<font color="#ff0000">' . TEP_HREF_LINK_ERROR1);
    }

      $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;

    if (tep_not_null($parameters)) {
      while ( (substr($parameters, -5) == '&amp;') ) $parameters = substr($parameters, 0, strlen($parameters)-5);
      $link .= $page . '?' . tep_output_string($parameters, false, true);
      $separator = '&amp;';
    } else {
      $link .= $page;
      $separator = '?';
    }

   
    if (isset($_sid) && !$spider_flag) {
      $link .= $separator . tep_output_string($_sid);
    }

    return $link;
  }

////
// Construct a category path to the product
// TABLES: products_to_categories
  function tep_get_product_path($products_id) {
    $cPath = '';

    $category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_id = p2c.products_id limit 1");
    if (tep_db_num_rows($category_query)) {
      $category = tep_db_fetch_array($category_query);

      $categories = array();
      tep_get_parent_categories($categories, $category['categories_id']);

      $categories = array_reverse($categories);

      $cPath = implode('_', $categories);

      if (tep_not_null($cPath)) $cPath .= '_';
      $cPath .= $category['categories_id'];
    }

    return $cPath;
  }

////
// Recursively go through the categories and retreive all parent categories IDs
// TABLES: categories
  function tep_get_parent_categories(&$categories, $categories_id) {
    $parent_categories_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$categories_id . "'");
    while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
      if ($parent_categories['parent_id'] == 0) return true;
      $categories[sizeof($categories)] = $parent_categories['parent_id'];
      if ($parent_categories['parent_id'] != $categories_id) {
        tep_get_parent_categories($categories, $parent_categories['parent_id']);
      }
    }
  }

     function cre_get_description($products_id, $lang_id){
         $product_query = tep_db_query("select products_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "' and language_id = '" . (int)$lang_id . "'");
         $product_desc = tep_db_fetch_array($product_query);
         $products_description = $product_desc['products_description'];
         $search = array('@<script[^>]*?>.*?</script>@si', 
               '@<[\/\!]*?[^<>]*?>@si',
               '@<style[^>]*?>.*?</style>@siU',
               '@<![\s\S]*?--[ \t\n\r]*>@'
               );

         $products_description = preg_replace($search, '', $products_description);
         return strlen($products_description) > $limit ? substr($products_description, 0, '200') : $products_description;
     }


    function cre_feed_write_to_file($output='', $mode, $outfile) {
      $output = implode("\n", $output);
      if(strtolower(CHARSET) != 'utf-8') {
        $output = utf8_encode($output);
      } else {
        $output = $output;
      }

      $fp = fopen($outfile, $mode);
      $retval = fwrite($fp, $output, '1024');
      return $retval;
    }

// Removes invalid XML
function cre_stripInvalidXml($string, $CDATA = false) {
$string = str_replace(array("\t" , "\n", "\r"), ' ', $string);
$string = tep_db_decoder($string);
$string = cre_translate_unsafe($string);
$string = htmlentities(html_entity_decode($string));

$ret = '';
    $length = strlen($string);
    for ($i=0; $i < $length; $i++)
    {
        $current = ord($string{$i});
        if (($current == 0x9) ||
            ($current == 0xA) ||
            ($current == 0xD) ||
            (($current >= 0x20) && ($current <= 0xD7FF)) ||
            (($current >= 0xE000) && ($current <= 0xFFFD)) ||
            (($current >= 0x10000) && ($current <= 0x10FFFF)))
        {
            $ret .= chr($current);
        }
        else
        {
            $ret .= " ";
        }
    }
      $string = trim($ret);

$find = array('&reg;', '&copy;', '&trade;','&lt;','&gt;','&eacute;','&quot;');
$replace = array('(r)', '(c)', '(tm)','<', '>','e','"');
$string = str_replace($find, $replace, $string);

if($CDATA){
    $string = '<![CDATA[' . $string . ']]>'; 
}
    return $string;
}

function cre_translate_unsafe($string) {
// using from seo.php
  // Convert special characters from European countries into the English alphabetic equivalent
  // Improved by Daniel S. Friehe
  $transforms = array('À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'Ae','Å'=>'A','Ç'=>'C','È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ì'=>'I',
                      'Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'Oe','Ø'=>'O','Ù'=>'U','Ú'=>'U',
                      'Û'=>'U','Ü'=>'Ue','Ý'=>'Y','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'ae','å'=>'a','ç'=>'c','è'=>'e','é'=>'e',
                      'ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'oe',
                      'ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ü'=>'ue','ý'=>'y','ÿ'=>'y','ß'=>'ss', '&nbsp;' => ' '); 
            
  return strtr($string, $transforms);

}

    function cre_check_feed_file($file){
        if (file_exists($file)) {
          chmod($file, 0777);
        } else {
          fopen($file, "w");
        }
        if(file_exists($file) && is_writable($file)){
            return true;
        } else {
            return false;
        }
    }
    
?>