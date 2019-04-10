<?php
/*
  url_rewrite.php v4.2
  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded

  Released under the GNU General Public License
*/

$path = "";
// Find out what chacter to use if a space needs replacing
if ( ! defined('CRE_SEO_SPACE_REPLACEMENT') ) {
	$space_replacement = '-';  // this is done for backward compatiablility, not really the best choice
} else {
	$space_replacement = CRE_SEO_SPACE_REPLACEMENT;
}

$pageurl= $_SERVER['REQUEST_URI'];
$cpathcount= substr_count($pageurl,'?cPath=');
$ppathcount = substr_count($pageurl,'&product_id=');
$tpathcount= substr_count($pageurl,'?tPath=');

//index.php?cPath=28&CDpath=4&osCsid=ff5ad4d9a36b049cc7f82f018e819c3dPath= http://ajparkes.com.au/Name-Badges/Executive/c28/ 
if($cpathcount > 0 && $ppathcount==0) {
	  
	  $val = $_GET['cPath'];
	  
	  $sql_query = tep_db_query("select c.categories_id, cd.categories_name,parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $_GET['cPath'] . "'  and c.categories_id = cd.categories_id");
	  if(tep_db_num_rows($sql_query)>0) {
		   $cat_name = tep_db_fetch_array( $sql_query );
		  
		   $sql_query_seo = tep_db_query("select c.categories_id, cd.categories_name,parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $cat_name['parent_id'] . "'  and c.categories_id = cd.categories_id");
		  if(tep_db_num_rows($sql_query_seo)>0) {
			  $par_cat_name = tep_db_fetch_array( $sql_query_seo );
			  
			  if($par_cat_name['parent_id']==0){
				//$result = encode_str_url($par_cat_name['categories_name']."/".$cat_name['categories_name']);  //Modified July 27 2011
				$result = encode_str_url($cat_name['categories_name']);              
			  } else {
				$result = encode_str_url($cat_name['categories_name']);
			  }
			  
				$cat = encode_str_url($result);          
				$path = HTTP_SERVER."/".$cat . '/c' . $val . '/'; 
				header("Location: ".$path);
				//echo "Path= ".$path;
				//exit;
		  }
	  }	
}

if($tpathcount >0) {		
		$val = $_GET['tPath'];		 
	 	$sql_query = tep_db_query("select t.topics_id, td.topics_name from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.topics_id = '" . $val . "'  and t.topics_id = td.topics_id");
	    if(tep_db_num_rows($sql_query)>0) {
			$cat_name = tep_db_fetch_array($sql_query );
			if($cat_name['topics_name']!=""){
				$result = encode_str_url($cat_name['topics_name']);                
			}
			$cat = encode_str_url($result);          
			$path = HTTP_SERVER."/".$cat . '/t' . $val . '/'; 
			header("Location: ".$path);
		// echo "Path= ".$path;
			//exit;	
		}
		//echo tep_db_num_rows($sql_query)."ann";
}



		
		
		/*
        
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
*/      


function encode_str_url($str) {  
  $original = array('%', '?', '#', '"');
  $entities = array('%25', '%3F', '%23', '%22');

  // for better support of European languages, the high ascii is converted for use
  return prepare_str_url(str_replace($original, $entities, $str));
}

function prepare_str_url($url) {
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



?>
