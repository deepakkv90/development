<?php

// helper functions to avoid PHP notices

function get($name, $default = null) { 
  
  if (isset($_GET[$name])) {
    if (is_array($_GET[$name])) {
      $res_array = array();
      foreach($_GET[$name] as $key => $value) {
        $res_array[$key] = $value;
      }
      return $res_array;
    } else {
      return $_GET[$name];
      //return urldecode($_GET[$name]);
    }
  } else {
    return $default;
  }
  
}
function set_get($name, $value = null) { $_GET[$name] = $value; }
function session($name, $default = null) { return isset($_SESSION[$name]) ? $_SESSION[$name] : $default; }
function cookie($name, $default = null) { return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default; }
function post($name, $default = null) { return isset($_POST[$name]) ? $_POST[$name] : $default; }
function set_post($name, $value = null) { $_POST[$name] = $value; }
function get_const($name, $default = null) { return defined($name) ? constant($name) : $default; }

function is_post_exists($tag) {
  
  foreach($_POST as $key => $value)
    if (eregi($tag, $key)) 
      return true;
  return false;
  
}

function safe($array, $name, $default = null) { 

  return (is_array($array) and array_key_exists($name, $array) and ($array[$name] or (is_scalar($array[$name]) and strlen($array[$name])))) ? $array[$name] : $default;
  
}

function uc_words($text) { 

  return ucwords(str_replace(",", ", ", strtolower($text))); 
  
}

function word_sub_str ($str, $count, $add_points = false) {
  $words = explode(" ", $str);
  $result = "";
  $words_count = count($words);
  $i = 0;
  $k = 0;
  while($k < $words_count) {
    $word = $words[$k];
    $result .= $words[$k] . " ";
    $word = trim($word);
    if (!empty($word)) {
      $i++;
      if ($i >= $count)
        break;
    }
    $k++;
  }
  if (($k < $words_count) and $add_points)
    $result .= "...";
  return $result;
}

function words_count ($str) {
  
  $words = explode(" ", $str);
  $words_count = count($words);
  $i = 0;
  $k = 0;
  while($k < $words_count) {
    $word = trim($words[$k]);
    if (!empty($word))
      $i++;
    $k++;
  }
  return $i;
  
}

function str_cut($str, $num, $mode="word"){
  
  for ($i=$num; $i<strlen($str); $i++){
    if ($mode=="word")
      if ($str[$i]==" " or $str[$i]=="," or $str[$i]=="." or $str[$i]=="!" or $str[$i]=="?" or $str[$i]=="/" or $str[$i]=="\\" or $str[$i]=="\n") 
        return substr($str, 0, $i);
    else
      if ($str[$i]=="." or $str[$i]=="!" or $str[$i]=="?" or $str[$i]=="\n") 
        return substr($str, 0, $i);
  }
  return $str;
  
}

function format($format_string) {

  $result = $format_string;

  for ($i = 1; $i < func_num_args(); ++$i) {
        $value = func_get_arg($i);
    $result = preg_replace("/%s/i", $value, $result, 1);
  }

  return $result;

}

function decorate_url_callback($match) {

  global $__tmp_url_class;
  global $decorate_ip_;
  
  if (preg_match("/^[0-9\.]*$/", $match[1].".".$match[6]) AND !$decorate_ip_){
    return $match[0];
  } else { 
    return "<a href=\"".($match[2]?"":"http://").$match[0]."\" class=\"$__tmp_url_class\" target=\"_blank\">".$match[0]."</a>";
  }

}

function decorate_and_encode_url_callback($match) {

  global $__tmp_url_class;
  global $decorate_it;
  global $decorate_ip_;

  $url = $match[1].".".$match[6];

  if (preg_match("/^[0-9\.]*$/", $url) AND !$decorate_ip_){
    return $url;
  }
  $result = "";
  $str = eregi_replace("^www", "http://www", $match[0]);
  for ($i = 0; $i < strlen($str); $i++)
    $result .= "var s$i='".htmlq($str{$i})."';";
  $result .= " return ";
  for ($i = 0; $i < strlen($str); $i++)
    $result .= "s$i+";
  $result = rtrim($result, "+").";";

  $id = substr(create_guid(), 1, 5);
  if ($decorate_it)
    return  "<script>"
           ." function get_url_".$id."() {"
           .$result
           ."} "
           ."document.write(\"<a href=\\\"\", get_url_".$id."(), \"\\\"  class=\\\"$__tmp_url_class\\\" target=\\\"_blank\\\">\",get_url_".$id."(),\"</a>\")"
           ."</script>";
  else
    return  "<script>"
           ." function get_url_".$id."() {"
           .$result
           ."} "
           ."document.write(get_url_".$id."())"
           ."</script>";
}

define("REGEXP_TLD", "(com|edu|gov|mil|net|org|biz|pro|info|name|museum|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|az|ax|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw|[0-9]+)");
define("REGEXP_URL", "((((http[s]?|ftp)[:]//)([a-zA-Z0-9.-]+([:][a-zA-Z0-9.&%$-]+)*@)?[a-zA-Z0-9.-]+|[a-zA-Z0-9]+[.][a-zA-Z0-9.-]+)[.]".REGEXP_TLD."([:][0-9]+)*(/[a-zA-Z0-9.,;?'\\+&%$#=~_-]+)*)");

function decorate_url($str, $class = "", $encrypt = true, $decorate = true, $decorate_ip = false) {

  global $__tmp_url_class;
  global $decorate_it;
  global $decorate_ip_;

  $decorate_it = $decorate;
  $decorate_ip_ = $decorate_ip;

  $__tmp_url_class = $class;
  if ($encrypt) {
    $str = preg_replace_callback(REGEXP_URL, "decorate_and_encode_url_callback", $str);
  }
  //$str = preg_replace_callback('([^">]+'.REGEXP_URL.'[^"<]+)', "decorate_url_callback", $str);  
  if ($encrypt) {
      $str = ereg_replace('([A-Za-z0-9]([A-Za-z0-9._\-]*[A-Za-z0-9]|()))@([A-Za-z0-9]([A-Za-z0-9._\-]*[A-Za-z0-9]|())\.[A-Za-z]+)',
      '<script>document.write(\'<a class="'.$class.'" href="mailto:\1\'+\'@\'+\'\4">\1\'+\'@\'+\'\4</a>\');</script>', $str);
  } else {
    $str = ereg_replace('[A-Za-z0-9]([A-Za-z0-9._\-]*[A-Za-z0-9]|())@[A-Za-z0-9]([A-Za-z0-9._\-]*[A-Za-z0-9]|())\.[A-Za-z]+',
      '<a class="'.$class.'" href="mailto:\0">\0</A>', $str);
  }      
  $str = preg_replace_callback('(([^">@ ]+|^)'.REGEXP_URL.'([^"<]+|$))', "decorate_url_callback", $str);
  return $str;

}

function check_email($email) { return (($email == '') or eregi("[_a-zA-Z0-9\-\.]+@[_a-zA-Z0-9\-]+(\.[_a-zA-Z0-9\-]+)+", $email)); }
function check_url($url) { return ((rtrim($url, "http://") == "") or eregi("(http://|https://|ftp://)[_a-zA-Z0-9\-]+(\.[_a-zA-Z0-9\-]+)+", $url)); }

function check_date($date, $format) {

  if (eregi("([0-9]*)[^0-9]*([0-9]*)[^0-9]*([0-9]*)", $date, $res)) {
    if (($res[1] <> "") and ($res[2] <> "") and ($res[3] <> "") and
        (is_numeric($res[1]) and is_numeric($res[2]) and is_numeric($res[3])))
      switch (strtoupper($format)) {
        case "DMY": return (strlen($res[3]) == 4) and checkdate($res[2], $res[1], $res[3]);
        case "DYM": return (strlen($res[2]) == 4) and checkdate($res[3], $res[1], $res[2]);
        case "YMD": return (strlen($res[1]) == 4) and checkdate($res[2], $res[3], $res[1]);
        case "YDM": return (strlen($res[1]) == 4) and checkdate($res[3], $res[2], $res[1]);
        case "MYD": return (strlen($res[2]) == 4) and checkdate($res[1], $res[3], $res[2]);
        case "MDY": return (strlen($res[3]) == 4) and checkdate($res[1], $res[2], $res[3]);
        default: return false;
      }
    else
      return false;
  } else
      return false;

}

function check_time($time, $format) {

  $res = explode(":", $time);
  if (is_numeric($res[0]) and is_numeric($res[1])) {
    if (($res[1] >= 0) and ($res[1] <= 59))
      switch ($format) {
        case "12": if (($res[0] >= 0) and ($res[0] <= 11)) return true;
        case "24": if (($res[0] >= 0) and ($res[0] <= 23)) return true;
        default: return false;
      }
    else
      return false;
  } else
      return false;

}

function get_microtime(){
  
  list($usec, $sec) = explode(" ",microtime());
  return ((float)$usec + (float)$sec);
  
}

function format_date_period($date_from, $date_till, $show_time, $separator = "-") {

  $time_format = "H:i";
  $day_time_format = "jS, H:i";
  $day_format = "jS";
  $show_year = ((date("Y") != date ("Y", $date_from)) or (date("Y") != date ("Y", $date_till)));
  if ($show_year) {
    $date_time_format = "jS M Y, H:i";
    $date_format = "jS M Y";
  } else {
    $date_time_format = "jS M, H:i";
    $date_format = "jS M";
  }

  if (substr($date_from, 0, 1) != '0') {
      $start_time = $date_from;

      if (substr($date_till, 0, 1) != '0') {
          $end_time = $date_till;
      } else
          $end_time = $start_time;

      if (date("M Y", $start_time) == date("M Y", $end_time))
          if (date("jS", $start_time) == date("jS", $end_time)) {
              if ($show_time)
                  if (date($time_format, $start_time) == date($time_format, $end_time))
                      $result = date($date_time_format, $end_time);
                  else
                      $result = date($date_time_format, $start_time)." $separator ".date($time_format, $end_time);
              else
                  $result = date($date_format, $end_time);
          } else {
              if ($show_time)
                  $result = date($date_time_format, $start_time)." $separator ".date($date_time_format, $end_time);
              else
                  $result = date($day_format, $start_time)." $separator ".date($date_format, $end_time);
          }
      else {
          if ($show_time)
              $result = date($date_time_format, $start_time)." $separator ".date($date_time_format, $end_time);
          else
              $result = date($date_format, $start_time)." $separator ".date($date_format, $end_time);
      }
  } elseif (substr($date_till, 0, 1) != '0') {
      $end_time = $date_till;
      if ($show_time)
          $result = date($date_time_format, $end_time);
      else
          $result = date($date_format, $end_time);
  } else {
      $result = "Unknown";
  }

  return $result;
}


function delete_dir($dir) {
  
  $current_dir = opendir($dir);
  while($entryname = readdir($current_dir)) {
    if (is_dir("$dir/$entryname") and ($entryname != "." and $entryname!="..")) {
      delete_dir("${dir}/${entryname}");
    } elseif ($entryname != "." and $entryname!="..") {
       unlink("${dir}/${entryname}");
    }
  }
  closedir($current_dir);
  rmdir($dir);
  
}

function force_dir($dir) {
  
  if (!file_exists($dir)) {
    mkdir($dir);
  }
  
}

function normalize_dir($dir) {
  
  return rtrim($dir, "/\\")."/";
  
}

function mk_dir($path, $mode = 0) {
  
  if (is_dir($path))
    return true;

  $npath = dirname($path);
  if (!mk_dir($npath, $mode))
    return false;

  return @mkdir($path);
  
}

function rm_dir($dir) {

  if(is_dir($dir)) {
    $d = @dir($dir);
    while ( false !== ( $entry = $d->read() ) ) {
      if($entry != '.' && $entry != '..') {
        $node = $dir.'/'.$entry;
        if(is_file($node)) {
          unlink($node);
        } else if(is_dir($node)) {
          rm_dir($node);
        }
      }
    }
    $d->close();
    rmdir($dir);
  }
  
}

function force_directories($path, $delim = "\\/"){
  
   $tmp = "";
   $folder_list = split("[".$delim."]", $path);
   $len = count($folder_list);
   for ($i=0; $i<$len; $i++) {
      $tmp .= $folder_list[$i] . $delim;
      @mkdir($tmp);
   }
   return is_dir($path);
   
}

function incs($container, $new_string, $separator = ",") {
  
  if (trim($container))
    if (trim($new_string))
      return $container.$separator.$new_string;
    else
      return $container;
  else
    return trim($new_string);
    
}

function nvl($value, $if_null) { if ($value == "") return $if_null; else return $value; }

function get_site_info($url, &$title, &$ip, &$meta_desc, &$meta_keywords) {

  @set_time_limit(300);

  $old_error_reporting = error_reporting();
  error_reporting(0);

  $parsed_url = parse_url($url);
  $ip = gethostbyname($parsed_url["host"]);

  $meta_tags = get_meta_tags($url);
  if (isset($meta_tags["description"]))
    $meta_desc = addslashes($meta_tags["description"]);
  if (isset($meta_tags["keywords"]))
    $meta_keywords = addslashes($meta_tags["keywords"]);

  $line = "";
  $site_index = fopen($url, "r");
  if ($site_index) {
    while (!feof($site_index)) {
      $buf = fgetc($site_index);
      $line .= $buf;
      if (($buf == "\n") or feof($site_index)) {
        if (eregi("<title>(.*)</title>", $line, $values)) {
          $title = addslashes($values[1]);
          break;
        } elseif (eregi("<body", $line, $values)) {
          break;
        }
      }
    }
    fclose($site_index);
    error_reporting($old_error_reporting);
    @set_time_limit(30);
    return true;
  } else {
    error_reporting($old_error_reporting);
    @set_time_limit(30);
    return false;
  }

}

function retrieve_site_info($url, $read_bytes = 8096) {
  
  @set_time_limit(0);

  $result = array();
  
  if (!eregi('^http[s]?', $url))
    $url = 'http://'.$url;
  
  require_once(dirname(__FILE__)."/http.php");
  $http = new http();
  $http->timeout = 0;
  $http->data_timeout = 0;
  $http->debug = 0;
  $http->html_debug = 1;
  $http->user_agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
  $http->follow_redirect = 1;
  $http->redirection_limit = 5;
  $http->exclude_address="";

  $error = $http->GetRequestArguments($url, $arguments);
  $arguments["Headers"]["Pragma"] = "nocache";

  $error = $http->Open($arguments);
  if (!strlen($error)) {
    $error = $http->SendRequest($arguments);
    if (!strlen($error)) {
      $headers = array();
      $error = $http->ReadReplyHeaders($headers);
      if (!strlen($error)) {
        switch ($http->response_status) {
          case "301":
          case "302":
          case "303":
          case "307":
          case "404":
            return $result;
            break;
          case "200":
            if (eregi('html', @$headers['content-type'])) {

              $page = '';
              while (strlen($page) < $read_bytes) {
                $error = $http->ReadReplyBody($portion, $read_bytes);
                $page .= $portion;
                if (strlen($error) || !strlen($portion))
                  break;
              }
              
              if ($page) {
                if (preg_match_all('/<meta([^>]+)>/ism', $page, $matches, PREG_SET_ORDER)) {
                  foreach ($matches as $match) {
                    if ($meta = @$match[1]) {
                      if (preg_match('/name.*?=.*?["]?([^"]+)/ism', $meta, $name)) {
                        if (preg_match('/content.*?=.*?["]?([^"]+)/ism', $meta, $content)) {
                          $result[$name[1]] = $content[1]; 
                        }
                      }
                    }
                  }
                }
                
                $result = array_change_key_case($result);

                if (preg_match('/<title>([^<]+)<\/title>/ism', $page, $matches))
                  $result['title'] = $matches[1];

                $parsed_url = parse_url($url);
                $result['ip'] = gethostbyname(@$parsed_url["host"]);
              }

            }
            break;  
        }

        
      }
    }
    $http->Close();
  }
  

  return $result;
  
}

function htmlize($value) {
  $value = str_replace('&quot;', '"', $value);
  $value = str_replace('&lt;',   '<', $value);
  $value = str_replace('&gt;',   '>', $value);
  $value = htmlspecialchars($value);
  $value = str_replace('&amp;#', '&#', $value);
  return $value;
}

function nl2nl($value) { 
  return ereg_replace("\n\r|\r\n|\n|\r", "\\n", $value); 
}

function for_javascript($value) {
  
  $value = htmlize($value);  
  $value = str_replace("&#39;", "'", $value);
  $value = addslashes($value);
  $value = nl2nl($value);
  return $value;
  
}

function for_regexp($value) {
  $value = str_replace('.', '[.]', $value);
  $value = str_replace('?', '[?]', $value);
  return $value;
}

function for_html($value, $options = null) {

  if (is_array($value)) {
    $result = array();
    foreach($value as $name => $onevalue) {
      $result[$name] = for_html($onevalue, $options);
    }
    return $result;
  } else {
    $no_nl2br      = safe($options, "no_nl2br");
    $no_htmlize    = safe($options, "no_htmlize");
    $class         = safe($options, "class");
    $decorate      = safe($options, "decorate");
    $encrypt       = safe($options, "encrypt");
    $max_rows      = safe($options, "max_rows");
    $max_words     = safe($options, "max_words");
    $more_text     = safe($options, "more_text");
    $more_href     = safe($options, "more_href");
    $decorate_url  = safe($options, "decorate_url");
    $decorate_ip   = safe($options, "decorate_ip");

    $add_more = false;

    if (!$no_htmlize)
      $value = htmlize($value);

    if (!$no_nl2br) {
      //$value = preg_replace("/(\n\r|\r\n|\n|\r)/ism", '<br />', $value);
      $value = nl2br($value);
    }

    if ($max_rows) {
      $value_lines = explode("<br />", $value);
      $value = "";
      for ($i = 0; $i < min($max_rows, count($value_lines)); $i++)
        $value .= ($value?"<br />":"").$value_lines[$i];
      if ($more_text and ($max_rows < count($value_lines)))
        $add_more = true;
    }

    if ($max_words) {
      $value_lines = explode(" ", $value);
      $value = "";
      for ($i = 0; $i < min($max_words, count($value_lines)); $i++)
        $value .= ($value?" ":"").$value_lines[$i];
      if ($more_text and ($max_words < count($value_lines)))
        $add_more = true;
    }

    if ($decorate_url) {
      $value = decorate_url($value, $class, $encrypt, $decorate, $decorate_ip);
    }

    if ($add_more) {
      $value .= "&nbsp;";//<br />
      if ($more_href)
        $value .= "<a href=\"$more_href\">$more_text</a>";
      else
        $value .= $more_text;
    }

    return $value;
  }

}

function for_input($value, $options = null) {

  $options['no_nl2br'] = true;
  return for_html($value, $options);

}

function for_xml($value) {

  if (is_array($value)) {
    $result = array();
    foreach($value as $name => $onevalue) {
      $result[$name] = for_xml($onevalue);
    }
    return $result;
  } else {
    $value = htmlize($value);
    return $value;
  }

}

function encode($str, $key) {
  
  $data = (string)$str;

  $from = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $to   = 'nopqrstuvwxyzabcdefghijklmNOPQRSTUVWXYZABCDEFGHIJKLM';
  $str = strtr($str, $from, $to);
  $result = "";
  for ($i = 0; $i < strlen($str); $i++)
    $result .= dechex(ord($str{$i}));
  $encoded = "";
  for ($i = 0; $i < strlen($result); $i++) {
    $encoded .= dechex(ord($result{$i}) ^ ($key{$i % strlen($key)}));
  }
  return $encoded;
  
}

function decode($str, $key) {
  
  $data = (string)$str;

  $encoded = "";
  for ($i = 0; $i < floor(strlen($str)/2); $i++) {
    $encoded .= chr(hexdec($str{$i*2}.$str{$i*2+1}) ^ ($key{$i % strlen($key)}));
  }
  $result = "";
  for ($i = 0; $i < floor(strlen($encoded)/2); $i++)
    $result .= chr(hexdec($encoded{$i*2}.$encoded{$i*2+1}));
  $from = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $to   = 'nopqrstuvwxyzabcdefghijklmNOPQRSTUVWXYZABCDEFGHIJKLM';
  $str = strtr($result, $from, $to);
  return $str;
  
}

function xor_encode($data, $key) {
  
  $data = (string)$data;
  
  $result = "";
  for ($i = 0; $i < strlen($data); $i++) {
    $c = ord($data{$i});
    if (strlen($key) > 0)
      $c = ord($key{$i - floor($i / strlen($key)) * strlen($key)} ^ $data{$i});
    $result .= str_pad(dechex($c), 2, "0", STR_PAD_LEFT);
  }
  return $result;
  
}

function xor_decode($data, $key) {
  
  $data = (string)$data;

  $result = null;
  for ($i = 0; $i < floor(strlen($data) / 2); $i++) {
    $c = hexdec($data{$i*2}.$data{$i*2+1});
    $c = chr(ord($key{$i - floor($i / strlen($key)) * strlen($key)}) ^ $c);
    $result .= $c;
  }
  return $result;
  
}

function xor_encrypt($data, $key) {
  
  $data = (string)$data;

  $tmp = xor_encode($data, $key);
  $result = "";
  for ($i = 0; $i < strlen($tmp); $i++)
    $result .= str_pad(dechex(ord($tmp{$i})), 2, "0", STR_PAD_LEFT);
  return $result;
  
}

function xor_decrypt($data, $key) {
  
  $data = (string)$data;

  $result = null;
  for ($i = 0; $i < floor(strlen($data)/2); $i++)
    $result .= chr(hexdec($data{$i*2}.$data{$i*2+1}));
  return xor_decode($result, $key);
  
}

if(!function_exists('encrypt_num')){
  function encrypt_num($num) {
  
    $rand1 = rand(100, 999);
    $rand2 = rand(100, 999);
    $key1 = ($num + $rand1) * $rand2;
    $key2 = ($num + $rand2) * $rand1;
    $result = $rand1.$rand2.$key1.$key2;
    $rand1_len = chr(ord('A') + strlen($rand1));
    $rand2_len = chr(ord('D') + strlen($rand2));
    $key1_len  = chr(ord('G') + strlen($key1));
    $rand1_pos = rand(0, floor(strlen($result)/3));
    $result1 = substr_replace($result, $rand1_len, $rand1_pos, 0);
    $rand2_pos = rand($rand1_pos + 1, floor(2*strlen($result1)/3));
    $result2 = substr_replace($result1, $rand2_len, $rand2_pos, 0);
    $key1_pos  = rand($rand2_pos + 1, strlen($result2)-1);
    $result3 = substr_replace($result2, $key1_len, $key1_pos, 0);
    //debug('Num='.$num.'; Rand1='.$rand1.'; Rand2='.$rand2.'; Key1='.$key1.'; Key2='.$key2.'; Result='.$result.'; Rand1Pos='.$rand1_pos.'; Result1='.$result1.'; Rand2Pos='.$rand2_pos.'; Result2='.$result2.'; Key1Pos='.$key1_pos.'; Result3='.$result3);
    return $result3;
    
  } 
}

function decrypt_num($num) {

  if (preg_match('/([A-Z]).*([A-Z]).*([A-Z])/', $num, $matches)) {
    $rand1_len = ord($matches[1]) - ord('A');
    $rand2_len = ord($matches[2]) - ord('D');
    $key1_len  = ord($matches[3]) - ord('G');
    $num = str_replace($matches[1], '', $num);
    $num = str_replace($matches[2], '', $num);
    $num = str_replace($matches[3], '', $num);
    //debug('Rand1_len='.$rand1_len.'; Rand2_len='.$rand2_len.'; Key1_len='.$key1_len.'; Num='.$num);
    $rand1 = substr($num, 0, $rand1_len);
    $rand2 = substr($num, $rand1_len, $rand2_len);
    $key1 = substr($num, $rand1_len + $rand2_len, $key1_len);
    $key2 = substr($num, $rand1_len + $rand2_len + $key1_len);
    //debug('Rand1='.$rand1.'; Rand2='.$rand2.'; Key1='.$key1.'; Key2='.$key2);
    if (($rand1 > 0) && ($rand2 > 0)) {
      $num1 = $key1 / $rand2 - $rand1;
      $num2 = $key2 / $rand1 - $rand2;
      //debug('Num1='.$num1.'; Num2='.$num2);
      if ($num1 == $num2) {
        return $num1;
      } else {
        return null;
      }
    } else {
      return null;
    }    
  } else {
    return null;
  }  
  
} 

function redirect($url) {
  
  if (headers_sent()) {
    js_redirect($url);
  } else { 
    header("Location: $url");
  }
  exit(); 
  
}

function refresh() { 
  
  global $url; 
  redirect($url->current_url); 
  
}

function refresh_script() { 
  
  global $url; 
  redirect($url->url_wo_params); 
  
}

function js_call($name, $args) {

  $result = "$name(";
  $arguments = "";
  foreach($args as $key => $value) {
    $arguments = incs($arguments, "'$value'");
  }
  $result .= "$arguments);";
  return $result;

}

function js_redirect($url) { 
  js_write("document.location='".$url."';"); 
  exit(); 
}

function js_refresh() { 
  
  global $url; 
  js_redirect($url->current_url); 
  
}
function js_write($js) { 
  
  echo("<script>".$js."</script>"); 
  
}

function url_decode($source) {
  $decodedStr = '';
  $pos = 0;
  $len = strlen($source);
  while ($pos < $len) {
    $charAt = substr ($source, $pos, 1);
    if (ord($charAt) == 195) {
      $char2 = substr($source, $pos, 2);
      $decodedStr .= utf8_decode($char2);
      $pos += 2;
    } elseif (ord($charAt) > 127) {
      $decodedStr .= "&#".ord($charAt).";";
      $pos++;
    } elseif ($charAt == '%') {
      $pos++;
      $hex2 = substr($source, $pos, 2);
      $dechex = chr(hexdec($hex2));
      if (ord($dechex) == 195) {
        $pos += 2;
        if (substr($source, $pos, 1) == '%') {
          $pos++;
          $char2a = chr(hexdec(substr($source, $pos, 2)));
          $decodedStr .= utf8_decode($dechex . $char2a);
        } else {
          $decodedStr .= utf8_decode($dechex);
        }
      } else {
        $decodedStr .= $dechex;
      }
      $pos += 2;
    } else {
      $decodedStr .= $charAt;
      $pos++;
    }
  }
  return $decodedStr;
}

function flush_echo($text) { echo($text); flush(); }

function nice_urldecode($url) {
  
  return $url;
  
}

function nice_urlencode($url, $encode = "_") {
  
//  return strtolower(eregi_replace('[^-!_$A-Za-z0-9!]', $encode, $url));
  return strtolower(preg_replace('/[\W]/', "_", $url));
  
}

function break_long_words($text, $max_length = 16) {
  $words = explode(" ", $text);
  $words_count = count($words);
  $result = "";
  $k = 0;
  while($k < $words_count) {
    $word = $words[$k];
    while (strlen($word) > $max_length) {
      $newword = substr($word, 0, $max_length);
      $result .= $newword . " ";
      $word = substr($word, $max_length);
    }
    if ($word)
      $result .= $word . " ";
    $k++;
  }
  return $result;
}

function str_to_date($date, $options = array())  {

  $date_format = strtolower(safe($options, "date_format", "dmy"));
  $mode        = safe($options, "mode", "m");

  if (($mode == "m") or ($mode == "d")) {
    $d = 0;
    $m = 0;
    $y = 0;
    if (eregi("([0-9]+)[^0-9]+([0-9]+)[^0-9]+([0-9]+)[^0-9]*([0-9]*)[^0-9]*([0-9]*)[^0-9]*([0-9]*)", $date, $res))
      if (($res[1] <> "") and ($res[2] <> "") and ($res[3] <> "") and (is_numeric($res[1]) and is_numeric($res[2]) and is_numeric($res[3]))) {
        $d = $res[strpos($date_format, "d")+1];
        $m = $res[strpos($date_format, "m")+1];
        $y = $res[strpos($date_format, "y")+1];
      }
  }

  if ($mode == "m") {
    $h = 0;
    $n = 0;
    $s = 0;
    if (eregi("([0-9]+)[^0-9]+([0-9]+)[^0-9]+([0-9]+)[^0-9]*([0-9]*)[^0-9]*([0-9]*)[^0-9]*([0-9]*)", $date, $res))
      if (($res[4] <> "") and ($res[5] <> "") and is_numeric($res[4]) and is_numeric($res[5]) and (($res[6] == "") or (is_numeric($res[6])))) {
        $h = $res[4];
        $n = $res[5];
        $s = $res[6];
      }
  }

  if ($mode == "t") {
    $h = 0;
    $n = 0;
    $s = 0;
    if (eregi("([0-9]+)[^0-9]+([0-9]+)[^0-9]*([0-9]*)", $date, $res))
      if (($res[1] <> "") and ($res[2] <> "") and is_numeric($res[1]) and is_numeric($res[2]) and (($res[3] == "") or (is_numeric($res[3])))) {
        $h = $res[1];
        $n = $res[2];
        $s = $res[3];
      }
  }

  switch ($mode) {
    case "d":
      if ($d and $m and $y)
        return mktime(0, 0, 0, $m, $d, $y);
      else
        return 0;
    case "t":
      return mktime($h, $n, $s, 1, 1, 2000);
    case "m":
      if ($d and $m and $y)
        return mktime($h, $n, $s, $m, $d, $y);
      else
        return 0;
  }
}

function execute_command($command) {

  global $log;

  $log->writeln("Executing:");
  $log->inc();
  $log->writeln($command);
  $log->dec();
  $result = exec($command, $output, $retval);
  $log->writeln("Output:");
  $log->inc();
  $log->writeln($output);
  $log->dec();
  if (!$retval) {
    $log->writeln("OK");
    return true;
  } else {
    $log->writeln("Error, last output line:");
    $log->inc();
    $log->writeln($exec_last_line);
    $log->dec();
    return false;

  }

}

function execute_text($text) {

  global $log;
                
  if (chdir(getcwd().'/_tmp')){
    $file_name = getcwd().'/'.guid().".cmd";
    chdir ('..');
  }
  else
    $file_name = guid().".cmd";
  $file = fopen($file_name, "w+");
  fwrite($file, $text);
  fclose($file);
  chmod($file_name, 0777);
  $result = execute_command($file_name);
  unlink($file_name);

  return $result;

}

function array_concat($in_arr, $separator=""){
    $out_arr = array();
    foreach($in_arr as $key => $val)
        $out_arr[] = $key.$separator.$val;

    return $out_arr;
}

/**
 * Generates a Universally Unique IDentifier, version 4.
 * 
 * RFC 4122 (http://www.ietf.org/rfc/rfc4122.txt) defines a special type of Globally
 * Unique IDentifiers (GUID), as well as several methods for producing them. One
 * such method, described in section 4.4, is based on truly random or pseudo-random
 * number generators, and is therefore implementable in a language like PHP.
 * 
 * We choose to produce pseudo-random numbers with the Mersenne Twister, and to always
 * limit single generated numbers to 16 bits (ie. the decimal value 65535). That is
 * because, even on 32-bit systems, PHP's RAND_MAX will often be the maximum *signed*
 * value, with only the equivalent of 31 significant bits. Producing two 16-bit random
 * numbers to make up a 32-bit one is less efficient, but guarantees that all 32 bits
 * are random.
 * 
 * The algorithm for version 4 UUIDs (ie. those based on random number generators)
 * states that all 128 bits separated into the various fields (32 bits, 16 bits, 16 bits,
 * 8 bits and 8 bits, 48 bits) should be random, except : (a) the version number should
 * be the last 4 bits in the 3rd field, and (b) bits 6 and 7 of the 4th field should
 * be 01. We try to conform to that definition as efficiently as possible, generating
 * smaller values where possible, and minimizing the number of base conversions.
 * 
 * @copyright  Copyright (c) CFD Labs, 2006. This function may be used freely for
 *              any purpose ; it is distributed without any form of warranty whatsoever.
 * @author      David Holmes <dholmes@cfdsoftware.net>
 * 
 * @return  string  A UUID, made up of 32 hex digits and 4 hyphens.
 */

function guid() {
   
   // The field names refer to RFC 4122 section 4.1.2

   return sprintf('%04x%04x-%04x-%03x4-%04x-%04x%04x%04x',
       mt_rand(0, 65535), mt_rand(0, 65535), // 32 bits for "time_low"
       mt_rand(0, 65535), // 16 bits for "time_mid"
       mt_rand(0, 4095),  // 12 bits before the 0100 of (version) 4 for "time_hi_and_version"
       bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '01', 6, 2)),
           // 8 bits, the last two of which (positions 6 and 7) are 01, for "clk_seq_hi_res"
           // (hence, the 2nd hex digit after the 3rd hyphen can only be 1, 5, 9 or d)
           // 8 bits for "clk_seq_low"
       mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535) // 48 bits for "node"  
   );  
}

function return_file($storage_file_name, $file_name, $file_type = null) {
  
  @set_time_limit(0);

  if (file_exists($storage_file_name)) { 
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    $user_agent = strtolower($_SERVER["HTTP_USER_AGENT"]);
    if ((is_integer (strpos($user_agent, "msie"))) && (is_integer (strpos($user_agent, "win")))) {
      header('Content-Disposition: filename="'.$file_name.'"');
    } else {
      header('Content-Disposition: attachment; filename="'.$file_name.'"');
    }    
    header("Content-Type: ".($file_type?$file_type:"application/octet-stream"));
    header("Content-Length: ".filesize($storage_file_name));
    header("Content-Description: ".$file_name);
    header("Cache-Control: private");

    $f = fopen($storage_file_name, "rb");
    while (!feof($f)) {
      $buf = fread($f, 4096);
      echo($buf);
    }
  } else {
    header("HTTP/1.0 404 Not Found");
  }

  exit();

}

function stripslashes_everywhere(&$element) { 
  
  if (is_array($element)) {
    foreach($element as $key => $value) 
      stripslashes_everywhere($element[$key]); 
  } else 
    $element = stripslashes($element); 
    
} 

// placeholders

@define("PLACEHOLDER_ERROR_PREFIX", "ERROR: ");

function sql_compile_placeholder($tmpl) {

  $compiled  = array();
  $p         = 0;
  $i         = 0;
  $has_named = false;

  while (false !== ($start = $p = strpos($tmpl, "?", $p))) {

    switch ($c = substr($tmpl, ++$p, 1)) {
      case '&': 
      case '%': 
      case '@': 
      case '#':
        $type = $c; 
        ++$p; 
        break;
      default:
        $type = ''; 
        break;
    }

    if (preg_match('/^((?:[^\s[:punct:]]|_)+)/', substr($tmpl, $p), $pock)) {

      $key = $pock[1];
      if ($type != '#') 
        $has_named = true;
      $p += strlen($key);

    } else {

      $key = $i;
      if ($type != '#') 
        $i++;

    }

    $compiled[] = array($key, $type, $start, $p - $start);
  }

  return array($compiled, $tmpl, $has_named);

}

function sql_placeholder_ex($tmpl, $args, &$errormsg) {

  if (is_array($tmpl)) {
    $compiled = $tmpl;
  } else {
    $compiled = sql_compile_placeholder($tmpl);
  }

  list ($compiled, $tmpl, $has_named) = $compiled;

  if ($has_named) 
    $args = @$args[0];

  $p   = 0;
  $out = '';
  $error = false;

  foreach ($compiled as $num=>$e) {

    list ($key, $type, $start, $length) = $e;

    $out .= substr($tmpl, $p, $start - $p);
    $p = $start + $length;

    $repl = '';
    $errmsg = '';

    do {
      
      if (!isset($args[$key]))
        $args[$key] = "";

      if ($type === '#') {
        $repl = @constant($key);
        if (NULL === $repl)
          $error = $errmsg = "UNKNOWN_CONSTANT_$key";
        break;
      }

      if (!isset($args[$key])) {
        $error = $errmsg = "UNKNOWN_PLACEHOLDER_$key";
        break;
      }

      $a = $args[$key];
      if ($type === '&') {
        global $db;
        if ($a === "")
          $repl = "null";
        else  
          $repl = "'".$db->addslashes($a)."'";
        break;
      } else
      if ($type === '') {
        if (is_array($a)) {
          $error = $errmsg = "NOT_A_SCALAR_PLACEHOLDER_$key";
          break;
        }
        if ($a === "")
          $repl = "null";
        else {
          global $db;
          $repl = preg_match('/^[1-9]+[0-9]*$/', $a) ? $a : "'".addslashes($a)."'";
        }
        break;
      }

      if (!is_array($a)) {
        $error = $errmsg = "NOT_AN_ARRAY_PLACEHOLDER_$key";
        break;
      }

      if ($type === '@') {
        foreach ($a as $v) {
          global $db;
          $repl .= ($repl===''? "" : ",")."'".$v."'";
        }
      } else
      if ($type === '%') {
        $lerror = array();
        foreach ($a as $k=>$v) {
          if (!is_string($k)) {
            $lerror[$k] = "NOT_A_STRING_KEY_{$k}_FOR_PLACEHOLDER_$key";
          } else {
            $k = preg_replace('/[^a-zA-Z0-9_]/', '_', $k);
          }
          global $db;
          $repl .= ($repl===''? "" : ", ").$k."='".@addslashes($v)."'";
        }
        if (count($lerror)) {
          $repl = '';
          foreach ($a as $k=>$v) {
            if (isset($lerror[$k])) {
              $repl .= ($repl===''? "" : ", ").$lerror[$k];
            } else {
              $k = preg_replace('/[^a-zA-Z0-9_-]/', '_', $k);
              $repl .= ($repl===''? "" : ", ").$k."=?";
            }
          }
          $error = $errmsg = $repl;
        }
      }

    } while (false);

    if ($errmsg) 
      $compiled[$num]['error'] = $errmsg;

    if (!$error) 
      $out .= $repl;

  }
  $out .= substr($tmpl, $p);

  if ($error) {
    $out = '';
    $p   = 0;
    foreach ($compiled as $num=>$e) {
      list ($key, $type, $start, $length) = $e;
      $out .= substr($tmpl, $p, $start - $p);
      $p = $start + $length;
      if (isset($e['error'])) {
        $out .= $e['error'];
      } else {
        $out .= substr($tmpl, $start, $length);
      }
    }
    $out .= substr($tmpl, $p);
    $errormsg = $out;
    return false;
  } else {
    $errormsg = false;
    return $out;
  }

}

function sql_pholder() {

  $args = func_get_args();
  $tmpl = array_shift($args);
  $result = sql_placeholder_ex($tmpl, $args, $error);
  if ($result === false) {
    $error = "Placeholder substitution error. Diagnostics: \"$error\"";
    if (function_exists("debug_backtrace")) {
      $bt = debug_backtrace();
      $error .= " in ".@$bt[0]['file']." on line ".@$bt[0]['line'];
    }
    trigger_error($error, E_USER_WARNING);
    return false;
  }
  return $result;

}

function placeholder() {

  $args = func_get_args();
  $tmpl = array_shift($args);
  $result = sql_placeholder_ex($tmpl, $args, $error);
  if ($result === false)
    return PLACEHOLDER_ERROR_PREFIX.$error;
  else
    return $result;

}

function sql_placeholder() {

  $args = func_get_args();
  $tmpl = array_shift($args);
  $result = sql_placeholder_ex($tmpl, $args, $error);
  if ($result === false)
    return PLACEHOLDER_ERROR_PREFIX.$error;
  else
    return $result;

}

function format_credit_card($value) {
  
  if (strlen($value) > 4)
    return str_pad(substr($value, strlen($value)-4, 4), 16, "*", STR_PAD_LEFT);
  else  
    return str_pad($value, 16, "*", STR_PAD_LEFT);
  
}

function check_credit_card($value) {  
  
  // primary check 
  $first_number = substr($value, 0, 1);
  switch ($first_number) {
    case 3:
      // American Express card
      if (!preg_match('/^3\d{3}[ \-]?\d{6}[ \-]?\d{5}$/', $value))
        return false;
      break;
    case 4:
      // Visa card
      if (!preg_match('/^4\d{3}[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $value))
        return false;
      break;
    case 5:
      // MasterCard card
      if (!preg_match('/^5\d{3}[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $value))
        return false;
      break;
    case 6:
      // Discover Card
      if (!preg_match('/^6011[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $value))
        return false;
      break;
    default:
      return false;
      break;
  }
  
  $value = str_replace('-', '', $value);
  
  $checksum = 0;
  // Alternating value of 1 or 2
  $j = 1;
  // Process each digit one by one starting at the right
  for ($i = strlen($value) - 1; $i >= 0; $i--) {
    // Extract the next digit and multiply by 1 or 2 on alternative digits.
    $calc = substr($value, $i, 1) * $j;
    // If the result is in two digits add 1 to the checksum total
    if ($calc > 9) {
      $checksum = $checksum + 1;
      $calc     = $calc - 10;
    }
    // Add the units element to the checksum total
    $checksum += $calc;
    // Switch the value of j
    if ($j == 1) {
      $j = 2;
    } else {
      $j = 1;
    }
  }

  // If checksum is divisible by 10 the credit card number is valid
  if ($checksum % 10 == 0) {
    // It's a valid credit card number
    return true;
  } else {
    // It's not valid
    return false;
  }
}

if (version_compare(phpversion(), '5.0') < 0) {
  eval('
    function clone($object) {
      return $object;
    }
    ');
}

function unescape($source, $iconv_to = 'UTF-8') {
   $decodedStr = '';
   $pos = 0;
   $len = strlen ($source);
   while ($pos < $len) {
       $charAt = substr ($source, $pos, 1);
       if ($charAt == '%') {
           $pos++;
           $charAt = substr ($source, $pos, 1);
           if ($charAt == 'u') {
               // we got a unicode character
               $pos++;
               $unicodeHexVal = substr ($source, $pos, 4);
               $unicode = hexdec ($unicodeHexVal);
               $decodedStr .= code2utf($unicode);
               $pos += 4;
           }
           else {
               // we have an escaped ascii character
               $hexVal = substr ($source, $pos, 2);
               $decodedStr .= chr (hexdec ($hexVal));
               $pos += 2;
           }
       }
       else {
           $decodedStr .= $charAt;
           $pos++;
       }
   }

   if ($iconv_to != "UTF-8") {
       $decodedStr = iconv("UTF-8", $iconv_to, $decodedStr);
   }
   
   return $decodedStr;
}

function code2utf($num){
   if($num<128)return chr($num);
   if($num<2048)return chr(($num>>6)+192).chr(($num&63)+128);
   if($num<65536)return chr(($num>>12)+224).chr((($num>>6)&63)+128).chr(($num&63)+128);
   if($num<2097152)return chr(($num>>18)+240).chr((($num>>12)&63)+128).chr((($num>>6)&63)+128) .chr(($num&63)+128);
   return '';
}

function load_from_file($file_name) {

  $result = null;

  if (file_exists($file_name)) {
    if ($f = @fopen($file_name, 'r')) {
      while (!feof($f))
        $result .= fread($f, 4096);
      fclose($f);
    }
  }

  return $result;

}

function save_to_file($file_name, $text) {

  if ($f = @fopen($file_name, 'w')) {
    fwrite($f, $text);
    fclose($f);
    return true;
  } else {
    return false;
  }

}

function __download($url, $out_file = null, $check_only = false, $params = array()) {
  global $log;
  
  if (!check_url($url) && file_exists($url)) {
    if (!$check_only)
      copy($url, $out_file);
    return true;
  } else {
    @set_time_limit(0);
    if ($parsed_url = @parse_url($url)) {
      if (safe($parsed_url, 'scheme') == 'ftp') {
        
        require_once(dirname(__FILE__)."/ftp.php");
        $ftp = new ftp();

        if ($check_only)
          return $ftp->check($url, safe($params, "timeout", 0));
          
        return $ftp->get($url, $out_file);
      } else {
        require_once(dirname(__FILE__)."/http.php");
        $http = new http();
        $http->timeout = safe($params, "timeout", 0);
        $http->data_timeout = 0;
        $http->debug = 0;
        $http->html_debug = 1;
        $http->user_agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
        $http->follow_redirect = 1;
        $http->redirection_limit = 5;
        $http->proxy_host_name = safe($params, 'proxy_host');
        $http->proxy_host_port = safe($params, 'proxy_port');
        /*
         *  If your DNS always resolves non-existing domains to a default IP
         *  address to force the redirection to a given page, specify the
         *  default IP address in this variable to make the class handle it
         *  as when domain resolution fails.
         */
        $http->exclude_address="";

        $error = $http->GetRequestArguments($url, $arguments);
        $arguments["Headers"]["Pragma"] = "nocache";

        $error = $http->Open($arguments);
        if (!strlen($error)) {
          $error = $http->SendRequest($arguments);
          if (!strlen($error)) {
            $headers = array();
            $error = $http->ReadReplyHeaders($headers);
            if (!strlen($error)) {
              switch ($http->response_status) {
                case "301":
                case "302":
                case "303":
                case "307":
                case "404":
                  return false;
                  break;
                case "200":
                  if ($check_only)
                    return true;
                  break;  
              }

              $file = '';
              if ($out_file)                       
                $fp = fopen($out_file.'.part','w');   
              if (!$out_file || $fp) {
                for(;;) {
                  $error = $http->ReadReplyBody($body, 1000);
                  if (strlen($error) || !strlen($body))
                    break;
                  if ($out_file)
                    fwrite($fp, $body);
                  else                                
                    $file .= $body;
                }
              }
            }
          }
          $http->Close();
        }
        if (strlen($error))
          return false;
        else {
          if ($out_file && $fp)
            if (fclose($fp) && rename ($out_file.'.part', $out_file))
              return true;
            else  
              return false;
          else
            return $file;    
        }
      }
    } else {
      return false;
    }
  }
  
}

function download($url, $out_file = null, $proxies = array()) {
  
  $result = __download($url, $out_file, false);
  if (!$result && $proxies) {
    foreach($proxies as $proxy) {
      $result = __download($url, $out_file, false, $proxy);
      if ($result)
        return $result;
    }
  }
  return $result;
  
}

function check_download($url, $params = array()) {
  
  return __download($url, null, true, $params);
  
}

function UTF8toCP1251($str){ // (�) SiMM 
  static $table = array("\xD0\x81" => "\xA8", // � 
                        "\xD1\x91" => "\xB8", // � 
                       ); 
  return preg_replace('#([\xD0-\xD1])([\x80-\xBF])#se', 
                      'isset($table["$0"]) ? $table["$0"] : 
                       chr(ord("$2")+("$1" == "\xD0" ? 0x30 : 0x70)) 
                      ', 
                      $str 
                     ); 
}

function format_bytes($inbytes, $show_sign = false) {
  
  $bytes = abs($inbytes);
  $prefix = ($show_sign?($inbytes>0?'+':''):'');
  $div = 1;
  $dec = 0;
  $byte_char = '';
  if (($bytes >= 1024) and ($bytes < 1024*1024)) {
    $div = 1024;
    $byte_char = 'K';
    $dec = 2;
  } 
  if (($bytes >= 1024*1024) and ($bytes < 1024*1024*1024)) {
    $div = 1024*1024;
    $byte_char = 'M';
    $dec = 2;
  } 
  if (($bytes >= 1024*1024*1024) and ($bytes < 1024*1024*1024*1024)) {
    $div = 1024*1024*1024;
    $byte_char = 'G';
    $dec = 2;
  }
 
  return $prefix.number_format($inbytes/$div, $dec).' '.$byte_char.'B';
  
}

function strftime_($format, $timestamp = null) {
  
  if (!$timestamp)
    $timestamp = mktime();
  $pos = strpos($format, '%B');
  if ($pos !== false) {     
    if ((strpos($format, '%d') !== false) or (strpos($format, '%e') !== false))
      $month = rtrim(trn(strftime('%B', $timestamp).'-'), '-');
    else
      $month = trn(strftime('%B', $timestamp));
    $format = str_replace('%B', $month, $format);
  }    
  $pos = strpos($format, '%A');
  if ($pos !== false) {     
    $weekday = trn(strftime('%A', $timestamp));
    $format = str_replace('%A', $weekday, $format);
  }    
  $result = strftime($format, $timestamp);
  return $result; 
  
}

if (!function_exists('file_put_contents') && !defined('FILE_APPEND')) {

  define('FILE_APPEND', 1);
  function file_put_contents($n, $d, $flag = false) {
    $mode = ($flag == FILE_APPEND || strtoupper($flag) == 'FILE_APPEND') ? 'a' : 'w';
    $f = @fopen($n, $mode);
    if ($f === false) {
      return 0;
    } else {
      if (is_array($d)) $d = implode($d);
        $bytes_written = fwrite($f, $d);
        fclose($f);
        return $bytes_written;
    }
  }

}

function prepare_mailer() {

  global $mailer;
    
  $mailer->ClearAddresses();
  $mailer->ClearCCs();
  $mailer->ClearBCCs();
  $mailer->ClearReplyTos();
  $mailer->ClearCustomHeaders();
  $mailer->Username = null;
  $mailer->Password = null;
  $mailer->SMTPAuth = false;
  

}

function send_mail() {
  
  global $mailer;

  $mailer->AddReplyTo($mailer->From, $mailer->FromName);

  //$mailer->Mailer = 'smtp';
  $result = $mailer->Send();
  
  if (!$result)
    logme('Error: '.$mailer->ErrorInfo, 'EML');
    
  return $result;  
  
}

function correct_war_time($time) {
  
  if (strpos($time, ":") === false) {
    $time = str_pad($time, 4, "0", STR_PAD_LEFT);
    $time = substr($time, 0, 2).":".substr($time, 2, 2);
  } 
  return $time;
  
}
  
if (!function_exists('gzdecode')) {
  function gzdecode ($data) {
    $flags = ord(substr($data, 3, 1));
    $headerlen = 10;
    $extralen = 0;
    $filenamelen = 0;
    if ($flags & 4) {
      $extralen = unpack('v' ,substr($data, 10, 2));
      $extralen = $extralen[1];
      $headerlen += 2 + $extralen;
    }
    if ($flags & 8) // Filename
      $headerlen = strpos($data, chr(0), $headerlen) + 1;
    if ($flags & 16) // Comment
      $headerlen = strpos($data, chr(0), $headerlen) + 1;
    if ($flags & 2) // CRC at end of file
      $headerlen += 2;
    $unpacked = gzinflate(substr($data, $headerlen));
    if ($unpacked === FALSE)
      $unpacked = $data;
    return $unpacked;
  }
} 

  
function array2json($i=array()) {
  
  $search = array("", " ", " ", "Z", " ");
  $replace = array( '', ' ', ' ', 'Z' , ' ');
  $o='';
  foreach ($i as $k=>$v) {
    $o .= '"'.$k.'":"'.str_replace($search, $replace, $v).'",'; 
  }
  return '({'.substr($o,0,-1).'})';
  
}       

function ip_num_to_str($ip_num) {
  if(function_exists('long2ip')) {
    return long2ip($ip_num); 
  } else {
    $w = round($ip_num / 16777216) % 256;
    $x = round($ip_num / 65536   ) % 256;
    $y = round($ip_num / 256     ) % 256;
    $z = round($ip_num           ) % 256;
   
    return $w.'.'.$x.'.'.$y.'.'.$z;
  }
}

function ip_str_to_num($ip_str) {
  if(function_exists('ip2long')) {
    return sprintf("%u",ip2long($ip_str));
  } else {
    if (!eregi('^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$', $ip_str))
      return -1;
    else { 
      $parts = explode('.', $ip_str);
      if (($parts[0] > 255) or ($parts[1] > 255) or ($parts[2] > 255) or ($parts[3] > 255))
        return -1;
      else 
        return 16777216*$parts[0] + 65536*$parts[1] + 256*$parts[2] + $parts[3];
    }
  }
}

function normalize_url($url) {
  
  if (!eregi("^(http://|https://|ftp://)", $url) && (!eregi("^/", $url)))
    return 'http://'.$url;
  else
    return $url;  
    
}

/* ***** BEGIN LICENSE BLOCK *****
 * Version: NPL 1.1/GPL 2.0/LGPL 2.1
 *
 * The contents of this file are subject to the Netscape Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/NPL/
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * The Original Code is Mozilla Communicator client code.
 *
 * The Initial Developer of the Original Code is
 * Netscape Communications Corporation.
 * Portions created by the Initial Developer are Copyright (C) 1998
 * the Initial Developer. All Rights Reserved.
 *
 * Contributor(s):
 * Henri Sivonen, hsivonen@iki.fi
 *
 *
 * Alternatively, the contents of this file may be used under the terms of
 * either the GNU General Public License Version 2 or later (the "GPL"), or
 * the GNU Lesser General Public License Version 2.1 or later (the "LGPL"),
 * in which case the provisions of the GPL or the LGPL are applicable instead
 * of those above. If you wish to allow use of your version of this file only
 * under the terms of either the GPL or the LGPL, and not to allow others to
 * use your version of this file under the terms of the NPL, indicate your
 * decision by deleting the provisions above and replace them with the notice
 * and other provisions required by the GPL or the LGPL. If you do not delete
 * the provisions above, a recipient may use your version of this file under
 * the terms of any one of the NPL, the GPL or the LGPL.
 *
 * ***** END LICENSE BLOCK ***** */

/*
 * For the original C++ code, see
 * http://lxr.mozilla.org/seamonkey/source/intl/uconv/src/nsUTF8ToUnicode.cpp
 * http://lxr.mozilla.org/seamonkey/source/intl/uconv/src/nsUnicodeToUTF8.cpp
 *
 * The latest version of this file can be obtained from
 * http://iki.fi/hsivonen/php-utf8/
 *
 * Version 1.0, 2003-05-30
 */

/**
 * Takes an UTF-8 string and returns an array of ints representing the 
 * Unicode characters. Astral planes are supported ie. the ints in the
 * output can be > 0xFFFF. Occurrances of the BOM are ignored. Surrogates
 * are not allowed.
 *
 * Returns false if the input string isn't a valid UTF-8 octet sequence.
 */
function utf8ToUnicode(&$str)
{
  $mState = 0;     // cached expected number of octets after the current octet
                   // until the beginning of the next UTF8 character sequence
  $mUcs4  = 0;     // cached Unicode character
  $mBytes = 1;     // cached expected number of octets in the current sequence

  $out = array();

  $len = strlen($str);
  for($i = 0; $i < $len; $i++) {
    $in = ord($str{$i});
    if (0 == $mState) {
      // When mState is zero we expect either a US-ASCII character or a
      // multi-octet sequence.
      if (0 == (0x80 & ($in))) {
        // US-ASCII, pass straight through.
        $out[] = $in;
        $mBytes = 1;
      } else if (0xC0 == (0xE0 & ($in))) {
        // First octet of 2 octet sequence
        $mUcs4 = ($in);
        $mUcs4 = ($mUcs4 & 0x1F) << 6;
        $mState = 1;
        $mBytes = 2;
      } else if (0xE0 == (0xF0 & ($in))) {
        // First octet of 3 octet sequence
        $mUcs4 = ($in);
        $mUcs4 = ($mUcs4 & 0x0F) << 12;
        $mState = 2;
        $mBytes = 3;
      } else if (0xF0 == (0xF8 & ($in))) {
        // First octet of 4 octet sequence
        $mUcs4 = ($in);
        $mUcs4 = ($mUcs4 & 0x07) << 18;
        $mState = 3;
        $mBytes = 4;
      } else if (0xF8 == (0xFC & ($in))) {
        /* First octet of 5 octet sequence.
         *
         * This is illegal because the encoded codepoint must be either
         * (a) not the shortest form or
         * (b) outside the Unicode range of 0-0x10FFFF.
         * Rather than trying to resynchronize, we will carry on until the end
         * of the sequence and let the later error handling code catch it.
         */
        $mUcs4 = ($in);
        $mUcs4 = ($mUcs4 & 0x03) << 24;
        $mState = 4;
        $mBytes = 5;
      } else if (0xFC == (0xFE & ($in))) {
        // First octet of 6 octet sequence, see comments for 5 octet sequence.
        $mUcs4 = ($in);
        $mUcs4 = ($mUcs4 & 1) << 30;
        $mState = 5;
        $mBytes = 6;
      } else {
        /* Current octet is neither in the US-ASCII range nor a legal first
         * octet of a multi-octet sequence.
         */
        return false;
      }
    } else {
      // When mState is non-zero, we expect a continuation of the multi-octet
      // sequence
      if (0x80 == (0xC0 & ($in))) {
        // Legal continuation.
        $shift = ($mState - 1) * 6;
        $tmp = $in;
        $tmp = ($tmp & 0x0000003F) << $shift;
        $mUcs4 |= $tmp;

        if (0 == --$mState) {
          /* End of the multi-octet sequence. mUcs4 now contains the final
           * Unicode codepoint to be output
           *
           * Check for illegal sequences and codepoints.
           */

          // From Unicode 3.1, non-shortest form is illegal
          if (((2 == $mBytes) && ($mUcs4 < 0x0080)) ||
              ((3 == $mBytes) && ($mUcs4 < 0x0800)) ||
              ((4 == $mBytes) && ($mUcs4 < 0x10000)) ||
              (4 < $mBytes) ||
              // From Unicode 3.2, surrogate characters are illegal
              (($mUcs4 & 0xFFFFF800) == 0xD800) ||
              // Codepoints outside the Unicode range are illegal
              ($mUcs4 > 0x10FFFF)) {
            return false;
          }
          if (0xFEFF != $mUcs4) {
            // BOM is legal but we don't want to output it
            $out[] = $mUcs4;
          }
          //initialize UTF8 cache
          $mState = 0;
          $mUcs4  = 0;
          $mBytes = 1;
        }
      } else {
        /* ((0xC0 & (*in) != 0x80) && (mState != 0))
         * 
         * Incomplete multi-octet sequence.
         */
        return false;
      }
    }
  }
  return $out;
}

/**
 * Takes an array of ints representing the Unicode characters and returns 
 * a UTF-8 string. Astral planes are supported ie. the ints in the
 * input can be > 0xFFFF. Occurrances of the BOM are ignored. Surrogates
 * are not allowed.
 *
 * Returns false if the input array contains ints that represent 
 * surrogates or are outside the Unicode range.
 */
function unicodeToUtf8(&$arr)
{
  $dest = '';
  foreach ($arr as $src) {
    if($src < 0) {
      return false;
    } else if ( $src <= 0x007f) {
      $dest .= chr($src);
    } else if ($src <= 0x07ff) {
      $dest .= chr(0xc0 | ($src >> 6));
      $dest .= chr(0x80 | ($src & 0x003f));
    } else if($src == 0xFEFF) {
      // nop -- zap the BOM
    } else if ($src >= 0xD800 && $src <= 0xDFFF) {
      // found a surrogate
      return false;
    } else if ($src <= 0xffff) {
      $dest .= chr(0xe0 | ($src >> 12));
      $dest .= chr(0x80 | (($src >> 6) & 0x003f));
      $dest .= chr(0x80 | ($src & 0x003f));
    } else if ($src <= 0x10ffff) {
      $dest .= chr(0xf0 | ($src >> 18));
      $dest .= chr(0x80 | (($src >> 12) & 0x3f));
      $dest .= chr(0x80 | (($src >> 6) & 0x3f));
      $dest .= chr(0x80 | ($src & 0x3f));
    } else { 
      // out of range
      return false;
    }
  }
  return $dest;
}

function like ($value, $condition) {
  
  $condition = str_replace('.', '[.]', $condition);
  $condition = str_replace('?', '[?]', $condition);
  $condition = str_replace('/', '\/', $condition);
  $condition = str_replace('-', '[-]', $condition);

  if ($condition{0} == '%')
    if ($condition{strlen($condition)-1} == '%')
      $regexpr = str_replace('%', '.*', $condition);
    else  
      $regexpr = str_replace('%', '.*', $condition).'$';
  else
  if ($condition{strlen($condition)-1} == '%')
    $regexpr = '^'.str_replace('%', '.*', $condition);
  else  
    $regexpr = '^'.str_replace('%', '.*', $condition).'$';
    
  return preg_match('/'.$regexpr.'/ism', $value);
  
}

function same_text($text1, $text2) {
  
  return (strtolower($text1) == strtolower($text2));
  
}

function hmac($data, $key, $hash = 'md5') {
  if (function_exists('hash_hmac')) {
    return hash_hmac($hash, $data, $key);
  } else {
    $blocksize = 64;
    if (strlen($key)>$blocksize) {
      $key = pack('H*', $hash($key));
    }
    $key  = str_pad($key, $blocksize, chr(0));
    $ipad = str_repeat(chr(0x36), $blocksize);
    $opad = str_repeat(chr(0x5c), $blocksize);
    return $hash(($key^$opad) . pack('H*', $hash(($key^$ipad) . $data)));
  }
}

function optimal_file_storage_path($folder, $table, $key, $field) {

  $result = rtrim($folder, '/').'/'.$table.'/';

  $key = (string) $key;

  for($i = 0; $i < strlen($key); $i++) {
    $result .= $key[$i].'/';
  } 
  
  $result .= $field.'/';

  return $result;

}



function create_image_thumbnails($image_file, $thumbnails) {

  if ($thumbnails) {

    $folder = normalize_dir(dirname($image_file));
    $file_name = basename($image_file);

    if (!is_array($thumbnails)) {
      $dimensions = explode(',', $thumbnails);
      $thumbnails = array();
      foreach($dimensions as $dimension) {
        if (eregi('([0-9]+)x([0-9]+)', $dimension, $matches)) {
          $thumbnails[$dimension] = array( 'width'  => $matches[1]
                                         , 'height' => $matches[2]
                                         );
        }
      }
    }

    foreach($thumbnails as $thumbnail_code => $thumbnail_desc) {
      $target_folder = $folder.$thumbnail_code.'/';
      if (mk_dir($target_folder))
        make_thumbnail($image_file, $thumbnail_desc['width'], $thumbnail_desc['height'], $target_folder.$file_name, true);
    }

  }

}

function format_duration($duration) {

  $secs = $mins = $hrs = 0;
  if ($duration < 60) {
    $secs = $duration;
  } else
  if ($duration < 60*60) {
    $mins = floor($duration/60);
    $secs = $duration - $mins*60;
  } else {
    $hrs  = floor($duration/60/60);
    $mins = ($duration - $hrs*60*60)/60;
    $secs = $duration - $hrs*60*60 - $mins*60;
  }

  $result = '';
  if ($secs)
    $result = number_format($secs, 3).' '.'secs';
  if ($mins)
    $result = $mins.' '.'mins'.' '.$result;
  if ($hrs)
    $result = $hrs.' '.'hrs'.' '.$result;

  return trim($result);

}

$__cyr = array( 'а','б','в','г','д','е','ж', 'з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц', 'ч', 'ш', 'щ',  'ъ','ы','ь','э','ю', 'я', 'А','Б','В','Г','Д','Е','Ж', 'З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц', 'Ч', 'Ш', 'Щ',  'Ъ','Ы','Ь','Э','Ю', 'Я' );
$__lat = array( 'a','b','v','g','d','e','zh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','ts','ch','sh','sht','a','y','y','e','yu','ya','A','B','V','G','D','E','Zh','Z','I','Y','K','L','M','N','O','P','R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','Y','Y','E','Yu','Ya' );

function cyr_to_translit($text) {
  global $__cyr, $__lat;
  return str_replace($__cyr, $__lat, $text);
}

function translit_to_cyr($text) {
  global $__cyr, $__lat;
  return str_replace($__lat, $__cyr, $text);
}

function g_strlen($str){

  if (!preg_match('/[^\x00-\x7F]/S', $str))
     return strlen($str);

  return strlen(utf8_decode($str));

}

function html_ul_to_text($html, $level = 1) {

  if (preg_match('/<ul>(.*)<\/ul>/ism', $html, $match, PREG_OFFSET_CAPTURE)) {
    $new_html = html_ul_to_text($match[1][0], $level + 1);
    $html = substr_replace($html, $new_html, $match[1][1], strlen($match[1][0]));
  } 

  $html = preg_replace('/<li>/ism', str_repeat(' ', $level).'- ', $html);
  $html = preg_replace('/<\/li>/ism', "\n", $html);
  if ($level == 1)
    $html = preg_replace('/<ul>/ism', "\n", $html);
  else  
    $html = preg_replace('/<ul>/ism', "", $html);
  $html = preg_replace('/<\/ul>/ism', '', $html);

  $result = '';
  $lines = preg_split("/[\n\r]/", $html);
  foreach($lines as $line)
    if (trim($line))
      $result .= $line."\n";

  return $result;

}

function html_to_text($html) { 

  $html = preg_replace("/&nbsp;/ism", ' ', $html);
  $html = html_entity_decode($html);
  $html = preg_replace("/(\n\n|\r\n\r\n|\r\r)/ism", '', $html);
  $html = preg_replace('/<br[^>]*>/ism', "\n", $html);
  $html = preg_replace('/<[^>]+>/ism', '', $html);
  $html = preg_replace('/<\/[^>]+>/ism', '', $html);
  return $html;

}

function is_writeable_folder($folder) {
  
   $folder = rtrim($folder, '/').'/';
   $file_name = $folder.guid().'.tmp';
   if ($file = @fopen($file_name, 'w')) {
     @fclose($file);
     @unlink($file_name);
     return true;
   }
   return false;

}

function badge_str2rgb($rgb) {
  if (!preg_match('/([0-9]+)[, ]+([0-9]+)[, ]+([0-9]+)/ism', $rgb, $colors)) 
    preg_match('/#([0-9]{2})([0-9]{2})([0-9]{2})/ism', $rgb, $colors);
  return $colors;
}

if (!function_exists('json_encode'))
{
  function json_encode($a=false)
  {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a))
    {
      if (is_float($a))
      {
        // Always use "." for floats.
        return floatval(str_replace(",", ".", strval($a)));
      }

      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = json_encode($v);
      return '[' . join(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
      return '{' . join(',', $result) . '}';
    }
  }
}

?>