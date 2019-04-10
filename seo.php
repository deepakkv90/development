<?php
/*
  seo.php v4.2
  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded

  Released under the GNU General Public License
*/

function callback($pagecontent) {
  // find all the href thatr are part of the <a> tag
  $pagecontent = preg_replace_callback("/(<a\s+[^>]*href=['\"]{1})([^'\">]+)([^>]*>)/", 'transform_uri', $pagecontent);
  
  return $pagecontent;
}


function transform_uri($param) {
  global $languages_id, $seourlreads, $space_replacement;
  // the url in the hrref should be passed here for reformatting
  // get the complete match and break it into pieces
  // need to allow for a partical href that uses relative addressing
  $uriparts = parse_url($param[2]);
  $scheme = isset( $uriparts['scheme'] ) ? $uriparts['scheme'] : 'http';
  
  // no reformat on SSL addresses
  if ( $scheme == 'https' ) return $param[0];
  $scheme .= '://';
  
  $host = isset( $uriparts['host'] ) ? $uriparts['host'] : ''; 
  $path = isset( $uriparts['path'] ) ? $uriparts['path'] : '';
  $query = isset( $uriparts['query'] ) ? $uriparts['query'] : '';
  $fragment = isset( $uriparts['fragment'] ) ? '#' . $uriparts['fragment'] : '';
  
  // if the href is for an extrnal location, ignore it
  if ($scheme . $host != HTTP_SERVER) return $param[0];
  
  // get the page name and page path
  $path_parts = pathinfo( $path );
  $page_name = $path_parts['basename'];
  $page_path = $path_parts['dirname'];
  
  // allow for the pathinfo returning a '.' if there is no dirname
  if ( substr( $page_path, 0, 1 ) == '.' ) $page_path = '';
  
  // the page path may need a trailing /
  if ( $page_path != '' && substr( $page_path, -1 ) != '/' ) $page_path .= '/';
  
  // Find out what chacter to use if a space needs replacing
  if ( ! defined('CRE_SEO_SPACE_REPLACEMENT') ) {
    $space_replacement = '-';  // this is done for backward compatiablility, not really the best choice
  } else {
    $space_replacement = CRE_SEO_SPACE_REPLACEMENT;
  }
  
  // based on the page name, decide if reformating is required
  switch( $page_name ) {
    case 'index.php':
    case 'product_info.php':
    case 'articles.php':
    case 'article_info.php':
    case 'information.php':
    case 'pages.php':
	case 'faq.php':
    case 'product_reviews.php':
    case 'fdm_file_detail.php':
    case 'fdm_folder_files.php':
    case 'fss_forms_index.php':
    case 'fss_forms_detail.php':
      
      // change the page name and reset the path to empty
      $page_name = substr( $page_name, 0, strlen($page_name) - 4 ) . '.html';
	  $page_name = rtrim($page_name,"index.html");
	  $page_name = rtrim($page_name,"product_info");
	  $page_name = rtrim($page_name,"product_reviews");
	  $page_name = rtrim($page_name,"pages.html");
  	  $page_name = rtrim($page_name,"pag");
	  $page_name = rtrim($page_name,"articles.html");
  	  $page_name = rtrim($page_name,"artic");
	  //$page_name = rtrim($page_name,"faq.html");
	  //$page_name = rtrim($page_name,"faq");
	  
	  
      $path = '';
      
      // process the query string
      if ( $query != '' ) {
        // repalce the &amp; with & for backward compatiablility
        $query = str_replace('&amp;', '&', $query);
        $query_parts = explode( '&', $query );
      
        //reset the query and path strings
        $query = '';
      
        // prcoess each piece found
        // Here is the odd part, normally a simple loop thru the parts found would do
        // however, because the rewrite rules require the parts to be processed in a certain order
        // The order of processing is set for backward comatiablility
      
        // find all the pieces to process
        $query_array = array();
        foreach ( $query_parts as $q ) {
          list( $key, $val ) = split( '=', $q );
          if ( ! empty( $key ) && ! empty( $val ) ) $query_array[$key] = $val;
        }
        
        if ( array_key_exists( 'cPath', $query_array ) ) {
          $val = $query_array['cPath'];
          // check to see if we already have this one
          if ( ! isset( $seourlreads['cPath'][$val] ) ) {
            $cat_arr = explode( '_', $val );
            $count = false; 
            foreach( $cat_arr as $value ){ 
              $sql_query = tep_db_query("select c.categories_id, cd.categories_name,parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $value . "'  and c.categories_id = cd.categories_id and cd.language_id ='" . (int)$languages_id . "'");
              $cat_name = tep_db_fetch_array( $sql_query );
			  
			   $sql_query_seo = tep_db_query("select c.categories_id, cd.categories_name,parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $cat_name['parent_id'] . "'  and c.categories_id = cd.categories_id and cd.language_id ='" . (int)$languages_id . "'");
              $cat_name_seo = tep_db_fetch_array( $sql_query_seo );
			  
              if( !$count ){
                $result .= encode_str($cat_name['categories_name']);
                $count = true;
              } else {
                $result .= '-' . encode_str($cat_name['categories_name']);
              }
            }
            
			//$cat = encode_str($cat_name_seo['categories_name']).'/'.$result; //modified july 27 2011
			$cat = $result;
            $seourlreads['cPath'][$val] = $cat;  // cache the results
          } else {
            $cat = $seourlreads['cPath'][$val];  // use the cache value
          }
          $path .= $cat . '/c' . $val . '/';
          unset( $query_array['cPath'] );
        }
        
        if ( array_key_exists( 'manufacturers_id', $query_array ) ) {
          $val = $query_array['manufacturers_id'];
          if ( ! isset( $seourlreads['manufacturers_id'][$val] ) ) {
            $sql_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $val . "'");
            $man = tep_db_fetch_array($sql_query );
            $mname = encode_str($man['manufacturers_name']);
            $seourlreads['manufacturers_id'][$val] = $mname;  // cache the results
          } else {
            $mname = $seourlreads['manufacturers_id'][$val];  // use the cache value
          }
          $path .= 'm' . $val . '/' . $mname . '/';
          unset( $query_array['manufacturers_id'] );
        }
        
        if ( array_key_exists( 'products_id', $query_array ) ) {
          $val = $query_array['products_id'];
          if ( ! isset( $seourlreads['products_id'][$val] ) ) {
            $sql_query = tep_db_query('select products_name from ' . TABLE_PRODUCTS_DESCRIPTION . ' where products_id = "' . (int)$val . '" and language_id ="' . (int)$languages_id . '"');
            $t = tep_db_fetch_array($sql_query );
            $pname = encode_str($t['products_name']);
            $seourlreads['products_id'][$val] = $pname;  // cache the results
          } else {
            $pname = $seourlreads['products_id'][$val];  // use the cache value
          }
          $path .= 'p' . (int)$val . '/' . $pname . '/';
          unset( $query_array['products_id'] );
        }
        
        if ( array_key_exists( 'info_id', $query_array ) ) {
          $val = $query_array['info_id'];
          if ( ! isset( $seourlreads['info_id'][$val] ) ) {
            $sql_query = tep_db_query('select info_title from ' . TABLE_INFORMATION . ' where information_id = "' . (int)$val . '" and languages_id="' . (int)$languages_id . '"');
            $t = tep_db_fetch_array($sql_query );
            $pname = encode_str($t['info_title']);
            $seourlreads['info_id'][$val] = $pname;  // cache the results
          } else {
            $pname = $seourlreads['info_id'][$val];  // use the cache value
          }
          $path .= 'i' . (int)$val . '/' . $pname . '/';
          unset( $query_array['info_id'] );
        }

        if ( array_key_exists( 'cID', $query_array ) && $page_name == 'faq.html' || $page_name == 'faq.php' || $page_name == 'faq') {
          $val = $query_array['cID'];
          if ( ! isset( $seourlreads['cID'][$val] ) ) {
            $cat_arr = explode( '_', $val );
            $count = false;
            foreach( $cat_arr as $value ){
              $sql_faq_query = tep_db_query("select fcd.categories_id, fcd.categories_name from " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " fcd where fcd.categories_id = '" . $value . "'  and fcd.language_id='" . (int)$languages_id . "'");
              $faq_cat_name = tep_db_fetch_array( $sql_faq_query );
              if( !$count ){
                $result .= encode_str($faq_cat_name['categories_name']);
               $count = true;
              } else {
                $result .= '-' . encode_str($faq_cat_name['categories_name']);
              }
            }
            $cat = $result;
            $seourlreads['cID'][$val] = $cat;  // cache the results
          } else {
            $cat = $seourlreads['cID'][$val];  // use the cache value
          }
          $path .= $cat . '/c' . $val . '/';
          unset( $query_array['cID'] );
		}
		
		if ( array_key_exists( 'cID', $query_array ) ) {
          $val = $query_array['cID'];
          if ( ! isset( $seourlreads['cID'][$val] ) ) {
            $cat_arr = explode( '_', $val );
            $count = false;
            foreach( $cat_arr as $value ){
              $sql_query = tep_db_query("select ic.categories_id, icd.categories_name from " . TABLE_PAGES_CATEGORIES . " ic, " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " icd where ic.categories_id = '" . $value . "'  and ic.categories_id = icd.categories_id and icd.language_id='" . (int)$languages_id . "'");
              $cat_name = tep_db_fetch_array( $sql_query );
              if( !$count ){
                $result .= encode_str($cat_name['categories_name']);
               $count = true;
              } else {
                $result .= '-' . encode_str($cat_name['categories_name']);
              }
            }
            $cat = $result;
            $seourlreads['cID'][$val] = $cat;  // cache the results
          } else {
            $cat = $seourlreads['cID'][$val];  // use the cache value
          }
          $path .= $cat . '/c' . $val . '/';
          unset( $query_array['cID'] );
        }
		
        
        //if ( array_key_exists( 'CDpath', $query_array ) && $page_name == 'pages.html' ) {
		if ( array_key_exists( 'CDpath', $query_array )) {
          $val = $query_array['CDpath'];
          if ( ! isset( $seourlreads['CDpath'][$val] ) ) {
            $cat_arr = explode( '_', $val );
            $first = true;
            foreach( $cat_arr as $value ){
              $sql_query = tep_db_query("select ic.categories_id, icd.categories_name from " . TABLE_PAGES_CATEGORIES . " ic, " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " icd where ic.categories_id = '" . $value . "'  and ic.categories_id = icd.categories_id and icd.language_id='" . (int)$languages_id . "'");
              $cat_name = tep_db_fetch_array( $sql_query );
              if( $first ){
                $result = encode_str($cat_name['categories_name']);
                $first = false;
              } else {
                $result .= '-' . encode_str($cat_name['categories_name']);
              }
            }
            $cat = $result;
            $seourlreads['CDpath'][$val] = $cat;  // cache the results
          } else {
            $cat = $seourlreads['CDpath'][$val];  // use the cache value
          }
          $path .= $cat . '/CDpath' . $val . '/';
          unset( $query_array['CDpath'] );
        }
        
        if ( array_key_exists( 'fPath', $query_array ) && $page_name == 'fdm_folder_files.html' ) {
          $val = $query_array['fPath'];
          if ( ! isset( $seourlreads['fPath'][$val] ) ) {
            $cat_arr = explode( '_', $val );
            $first = true;
            foreach( $cat_arr as $value ){
              $sql_query = tep_db_query("select folders_name from " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " where folders_id = " . (int)$value );
              $fp_name = tep_db_fetch_array( $sql_query );
              if( $first ){
                $result = encode_str($fp_name['folders_name']);
                $first = false;
              } else {
                $result .= '-' . encode_str($fp_name['folders_name']);
              }
            }
            $cat = $result;
            $seourlreads['fPath'][$val] = $cat;  // cache the results
          } else {
            $cat = $seourlreads['fPath'][$val];  // use the cache value
          }
          $path .= $cat . '/fPath' . $val . '/';
          unset( $query_array['fPath'] );
        }
        
        if ( array_key_exists( 'file_id', $query_array ) && $page_name == 'fdm_file_detail.html' ) {
          $val = $query_array['file_id'];
          if ( ! isset( $seourlreads['file_id'][$val] ) ) {
            $sql_query = tep_db_query("select files_descriptive_name from " . TABLE_LIBRARY_FILES_DESCRIPTION . " where  files_id = " . (int)$val );
            $t = tep_db_fetch_array($sql_query );
            $fname = encode_str($t['files_descriptive_name']);
            $seourlreads['file_id'][$val] = $fname;  // cache the results
          } else {
            $fname = $seourlreads['file_id'][$val];  // use the cache value
          }
          $path .= 'f' . (int)$val . '/' . $fname . '/';
          unset( $query_array['file_id'] );
        }
        
        if ( array_key_exists( 'pID', $query_array ) ) {
          $val = $query_array['pID'];
          if ( ! isset( $seourlreads['pID'][$val] ) ) {
            $sql_query = tep_db_query('select pages_title from ' . TABLE_PAGES_DESCRIPTION . ' where pages_id = "' . (int)$val . '" and language_id = "' . (int)$languages_id . '"');
            $t = tep_db_fetch_array($sql_query );
            $pname = encode_str($t['pages_title']);
            $seourlreads['pID'][$val] = $pname;  // cache the results
          } else {
            $pname = $seourlreads['pID'][$val];  // use the cache value
          }
          $path .= 'p' . (int)$val . '/' . $pname . '/';
          unset( $query_array['pID'] );
        }
      
        if ( array_key_exists( 'reviews_id', $query_array ) ) {
          $val = $query_array['reviews_id'];
          $path .= 'review' . $val . '/';
          unset( $query_array['reviews_id'] );
        }

        if ( array_key_exists( 'tPath', $query_array ) ) {
          $val = $query_array['tPath'];
          if ( ! isset( $seourlreads['tPath'][$val] ) ) {
            $cat_arr = explode( '_', $val );
            $count = false;
            foreach( $cat_arr as $value ){
              $sql_query = tep_db_query("select t.topics_id, td.topics_name from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.topics_id = '" . $value . "'  and t.topics_id = td.topics_id and td.language_id='" . (int)$languages_id . "'");
              $cat_name = tep_db_fetch_array($sql_query );
              if( !$count ){
                $result .= encode_str($cat_name['topics_name']);
                $count = true;
             } else {
              $result .= '-' . encode_str($cat_name['topics_name']);
              }
            }
            $cat = $result;
            $seourlreads['tPath'][$val] = $cat;  // cache the results
          } else {
            $cat = $seourlreads['tPath'][$val];  // use the cache value
          }
          $path .= $cat . '/t' . $val . '/';
          unset( $query_array['tPath'] );
        }
        
        if ( array_key_exists( 'articles_id', $query_array ) ) {
          $val = $query_array['articles_id'];
          if ( ! isset( $seourlreads['articles_id'][$val] ) ) {
            $sql_query = tep_db_query('select articles_name from ' . TABLE_ARTICLES_DESCRIPTION . ' where articles_id = "' . (int)$val . '" and language_id="' . (int)$languages_id . '"');
            $t = tep_db_fetch_array($sql_query );
            $pname = encode_str($t['articles_name']);
            $seourlreads['articles_id'][$val] = $pname;  // cache the results
          } else {
            $pname = $seourlreads['articles_id'][$val];  // use the cache value
          }
          $path .= 'a' . (int)$val . '/' . $pname . '/';
          unset( $query_array['articles_id'] );
        }
        
        if ( array_key_exists( 'fPath', $query_array ) && ($page_name == 'fss_forms_index.html' || $page_name == 'fss_forms_detail.html') ) {
          $val = $query_array['fPath'];
          if ( ! isset( $seourlreads['fPath'][$val] ) ) {
            $sql_query = tep_db_query("select fss_categories_name from " . TABLE_FSS_CATEGORIES . " where fss_categories_id = " . (int)$val . " ");
            $t = tep_db_fetch_array($sql_query );
            $fname = encode_str($t['fss_categories_name']);
            $seourlreads['fPath'][$val] = $fname;  // cache the results
          } else {
            $fname = $seourlreads['fPath'][$val];  // use the cache value
          }
          $path .= $fname . '/fPath' . (int)$val . '/';
          unset( $query_array['fPath'] );
        }
      
        if ( array_key_exists( 'forms_id', $query_array ) && $page_name == 'fss_forms_detail.html' ) {
          $val = $query_array['forms_id'];
          if ( ! isset( $seourlreads['forms_id'][$val] ) ) {
            $sql_query = tep_db_query("select ffd.forms_name from " . TABLE_FSS_FORMS . " ff, " . TABLE_FSS_FORMS_DESCRIPTION . " ffd where ff.forms_id = " . (int)$val . " and ff.forms_id = ffd.forms_id and ffd.language_id = " . $_SESSION['languages_id'] . " ");
            $t = tep_db_fetch_array($sql_query );
            $fname = encode_str($t['forms_name']);
            $seourlreads['forms_id'][$val] = $fname;  // cache the results
          } else {
            $fname = $seourlreads['forms_id'][$val];  // use the cache value
          }
          $path .= 'form' . (int)$val . '/' . $fname . '/';
          unset( $query_array['forms_id'] );
        }
      
        // any remain query keys goes back into the query string
        foreach ( $query_array as $key => $val ) {
          $query .= '&amp;' . $key . '='.$val;
        }
        
        // remove leading &amp; if needed
        if ( $query != '' ) {
          if ( substr( $query, 0, 5) == '&amp;' ) $query = substr( $query, 5 );
          $query = '?' . $query;
        }
      
      }
      return $param[1] . $scheme . $host . $page_path . $path . $page_name . $query . $fragment . $param[3];
      break;
    
    default:
      return $param[0];
  }
      
}

function encode_str($str) {
  // special chanracter as defined by RFC3986
  // gen-delims  = ":" / "/" / "?" / "#" / "[" / "]" / "@"
  // sub-delims  = "!" / "$" / "&" / "'" / "(" / ")"
  //             / "*" / "+" / "," / ";" / "="
  // additionally the "%" must be encoded if it part of the text input
  //$original = array('%', ':', '/', '?', '#', '[', ']', '@', '!', '$', '&', "'", '(', ')', '*', '+', ','. ';', '=');
  //$entities = array('%25', '%3A', '%2F', '%3F', '%23', '%5B', '%5D', '%40', '%21', '%24', '%26', '%27', '%28', '%29', '%2A', '%2B', '%2C', '%3B', '%3D');

  // these special characters do not need to encoded to be accepted,
  // ':', '[', ']','@', '!', '$', '&', "'", '(', ')', '*', '+', ','. ';', '='
  // the '/' is an odd case, encoding it causes the rewite to fail.
  // The " is encoded to ensure it does not break the HTML code
  
  $original = array('%', '?', '#', '"');
  $entities = array('%25', '%3F', '%23', '%22');

  // for better support of European languages, the high ascii is converted for use
  return prepare_url(str_replace($original, $entities, $str));
}

function prepare_url($url) {
  global $space_replacement;
  // Convert special characters from European countries into the English alphabetic equivalent
  // Improved by Daniel S. Friehe
  $transforms = array('À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'Ae','Å'=>'A','Ç'=>'C','È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ì'=>'I',
                      'Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'Oe','Ø'=>'O','Ù'=>'U','Ú'=>'U',
                      'Û'=>'U','Ü'=>'Ue','Ý'=>'Y','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'ae','å'=>'a','ç'=>'c','è'=>'e','é'=>'e',
                      'ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'oe',
                      'ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ü'=>'ue','ý'=>'y','ÿ'=>'y','ß'=>'ss'); 
            
  $url = strtr($url, $transforms);

  // replace all spaces with the character selected by the admin
  $url = str_replace(' ' , $space_replacement , $url);
  $space_double = $space_replacement . $space_replacement;
  
  // Remove double spaces
  while (strstr($url, $space_double)) $url = str_replace($space_double, $space_replacement, $url);
  return $url;
}


// the seourlreads array provides a limited form of caching to prevent
// addittional queries from being done that have already been done
$seourlreads = array();

ob_start("callback");

?>
