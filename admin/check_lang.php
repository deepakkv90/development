<?php
// W Godefroy
//  OSCommerce language integrity verification
//
// For latest info check:
// http://buzzy.belgoline.com/hacks/fldr_checklang
//
// Copyright (C) 2005 W Godefroy
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
//

define("ERROR_CANNOT_OPEN_LANGUAGE_DIR","FATAL ERROR: Cannot open language dir: ");
define("CHECK_LANG_FILENAME","Filename");
define("CHECK_LANG_MISSING_IN","Missing in");
define("CHECK_LANG_NONE_FOUND","None found");
define("CHECK_LANG_FOUND_IN","Found in");
define("CHECK_LANG_DEFINITION_NAME","Definition name");
define("CHECK_LANG_EQUAL_TO","Equal to");
define("CHECK_LANG_REFERENCES","References");
define("CHECK_LANG_ALL_EQUAL","All equal");
define("CHECK_LANG_WHERE_USED","Where Used");
define("CHECK_LANG_FOUND_FOR","Found for");
define("CHECK_LANG_MISSING_FOR","Missing for");
define("CHECK_LANG_ALL_BUT","All but");
define("CHECK_LANG_FILE_NAME","File Name");
define("CHECK_LANG_SELECT_OPTION","Select option");
define("CHECK_LANG_TO_CHECK_FILE_CONSISTENCY","To check file consistency");
define("CHECK_LANG_CLICK_HERE","Click Here");
define("CHECK_LANG_TO_CHECK_DEFINITION_CONSISTENCY","To check definition consistency");
define("CHECK_LANG_TO_CHECK_EQUAL_DEFINITIONS","To check equal definitions");
define("CHECK_LANG_HOME","Home");
define("CHECK_LANG_BACK","Back");
define("CHECK_LANG_W_GODEFROY","W Godefroy");
define("CHECK_LANG_EXECUTION_TIME","Execution Time: ");
define("CHECK_LANG_TITLE","Check Language Integrity");
define("CHECK_LANG_LIST_OF_FILES_USING","List of files using");
define("CHECK_LANG_LIST_OF_FILES_PRESENT_FOR_ONE_LANGUAGE","List of files that are present for one language-definition but missing for other(s)");
define("CHECK_LANG_LIST_OF_PHRASE_PRESENT_FOR_ONE_LANGUAGE","List of phrase definitions that are present for one language-file but missing for other(s)");
define("CHECK_LANG_LIST_OF_PHRASE_DEFINITIONS_EQUAL","List of phrase definitions that are equal");



//require('includes/application_top.php');
define('DIR_WS_INCLUDES', "includes/");

$timeparts = explode(' ',microtime());
$starttime = $timeparts[1].substr($timeparts[0],1);

function tep_show_array_table(&$myarray, $title, $lev=0) 
{
    if($lev) 
  echo "<table >";
    else
  echo "<table border=1 >";

    echo "\n<tr><th colspan=2>$title</th></tr>";
    foreach($myarray as $key => $values) {
  echo "<tr><td> $key </td>\n  <td>";
  if(is_array($values)) {
      tep_show_array_table($values, $key, $lev + 1);
  } else {
      echo "$values";
  }
  echo "</td></tr>\n";
    }
    echo "</table>";
}

function tep_show_env() 
{
    
    tep_show_array_table($_SERVER, "HTTP_SERVER_VARS");
    echo"<br>";
    
    tep_show_array_table($_ENV, "HTTP_ENV_VARS");
    echo"<br>";

    tep_show_array_table($_COOKIE, "HTTP_COOCKIE_VARS");
    echo"<br>";

    tep_show_array_table($_GET, "_GET");
    echo"<br>";

    tep_show_array_table($_POST, "_POST");
    echo"<br>";

    tep_show_array_table($_SESSION, "HTTP_SESSION_VARS");
    echo"<br>";
}
  

function tep_scan_lang_dirs(&$lar, $lang_dir) {
    if(is_dir($lang_dir)) {
  if ($dh = opendir($lang_dir)) {
      while (false !== ($file = readdir($dh))) {
    $fpath = $lang_dir . $file;
      
    if(is_file($fpath)) {
        if($lang_name = tep_is_lang_file($fpath)) {
      $lar[$lang_name]["__filename__"] = $fpath;
        }
    }
      }
  closedir($dh);
  }
    } else {
  //die("FATAL ERROR: Cannot open language dir: $lang_dir");
  die(ERROR_CANNOT_OPEN_LANGUAGE_DIR.$lang_dir);
    }
}

function tep_is_lang_file($file_name) 
{
    $match_pattern = '/(\w+)\.php$/';

    if(preg_match($match_pattern,$file_name,$matches)) {
  return $matches[1];
    } else {
  return 0;
    }    
}

function tep_read_files_in_dir($dir_name) 
{
    $result = array();


    $dirlist = array($dir_name);
    
    while($dirlist) {
  $currdir = array_pop($dirlist);
  
  if($dh = opendir($currdir)) {
      
      while(false !== ($dire = readdir($dh))) {
    $fpath = $currdir . '/' . $dire;
    
    if(is_file($fpath)) {
        $result[] = $fpath;
    } elseif(is_dir($fpath) && $dire != ".." && $dire != "." && $dire != "CVS") {
        $dirlist[] = $fpath;
    }
      }
      
      
  }
    }
    
    return $result;
}


function tep_read_lang_files(&$lar) 
{
    foreach ($lar as $key => $value) {
  //read lang directory
  $landir = dirname($value["__filename__"]) . "/$key";

  $lar[$key] += tep_read_files_in_dir($landir);
    }
}


function tep_neuter_lang($lname, $str) 
{
    if(preg_match("/(.+)\/$lname(.+)/",$str,$matches)) {
  return $matches[2];
    } else {
  return basename($str);
    }
}


function tep_create_lang_neutral(&$lar) 
{
    $neutral_file_list = array();
    foreach($lar as $lang => $flist) 
  foreach($flist as $fname) {
      $neutral_file = tep_neuter_lang($lang, $fname);
      if(!(array_search($neutral_file, $neutral_file_list) !== false))
    $neutral_file_list[] = $neutral_file;
  }

    return $neutral_file_list;
}


function tep_new_show_missing_files(&$lar) 
{
    $lang_list = array_keys($lar);

    $neutral_file_list = tep_create_lang_neutral($lar);

    echo "<table border=1>";
    echo "<tr><th>".CHECK_LANG_FILENAME."</th><th>".CHECK_LANG_MISSING_IN."</th></tr>\n";
    $found_errors = 0;
    foreach($neutral_file_list as $file_name) {
  $total = 0;
  $missing = 0;
  foreach($lang_list as $lang) {
      $lang_file = DIR_WS_INCLUDES . "languages/$lang" . $file_name;

      $total++;
      if(!is_file($lang_file)) {
    $missing++;
    $missing_lang .= " [$lang]";
      } else
    $lang_found = $lang;
  }

  if($missing_lang) {
      $found_errors = 1;
      
      $gen_file = "languages/<i>[LANG_NAME]</i>" . $file_name;
      echo "<tr><td>$gen_file</td><td>";

      if($missing >= $total - 1) 
    echo "<i>".CHECK_LANG_ALL_BUT." $lang_found</i>";
      else
    echo $missing_lang;

      echo "</td></tr>\n";
  }
    }
    if(!$found_errors) 
  echo "<tr><td colspan=2><i>".CHECK_LANG_NONE_FOUND."</i></td></tr>\n";
    
    echo "</table>";
}

function tep_read_lang_words($lang, $file_base) {
    $file_name = DIR_WS_INCLUDES . "languages/$lang" . $file_base;

    $result = array();
    
    if(is_file($file_name)) {
  $handle = fopen($file_name, "r");
  while(!feof($handle)) {
      $fline = fgets($handle);
      if(preg_match("/define\s*\(\s*(\'|\")(\w+)(\'|\")\s*,\s*(.+)\s*\)\s*;/",$fline,$matches)) {
    $result[$matches[2]] = $matches[4];
      }
  }
    }

    return $result;
}

// this function is equal to tep_new_show_missing_def but a few subtile
// differences which would make a single function for both just unreadable
function tep_new_show_equal_def(&$lar) 
{
    global $my_url;

    $file_defs_checked = array();

    $lang_list = array_keys($lar);

    $neutral_file_list = tep_create_lang_neutral($lar);

    echo "<table border=1>";
    echo "<tr><th>".CHECK_LANG_FOUND_IN."</th><th>".CHECK_LANG_DEFINITION_NAME."</th><th>".CHECK_LANG_EQUAL_TO."</th><th>".CHECK_LANG_REFERENCES."</th></tr>\n";
    foreach($neutral_file_list as $neuter_file) {
  $first_missing_in_file = 1;

  //create word-definition array
  $war = array();
  foreach($lang_list as $lang) 
      $war[$lang] = tep_read_lang_words($lang, $neuter_file);
  
  $def_lang_checked = array();
  foreach($war as $lang => $lang_def) {
      foreach($lang_def as $def_name => $def_val) {
    $total = 0;
    $matches = 0;
    $lang_equal = "";

    //keep list of verified ($def_name,$lang)'s to avoid dupes
    $def_lang = "$def_name $lang";
    if(!(array_search($def_lang, $def_lang_checked) !== false)) {
        $def_lang_checked[] = $def_lang;

        foreach($lang_list as $lang_comp) 
      if($lang_comp != $lang) {
          $total++;

          if($war[$lang][$def_name] == $war[$lang_comp][$def_name]) {
        $matches++;
        $lang_equal .= " [$lang_comp]";

        //add to the list to avoid dupes
        $def_lang = "$def_name $lang_comp";
        $def_lang_checked[] = $def_lang;
          }
      }

        if($matches) {
      if($first_missing_in_file) {
          $first_missing_in_file = 0;
          echo "<tr><th colspan=3>includes/<i>[LANG_NAME]</i>$neuter_file</th><td></td></tr>\n";
      }

      echo "<tr><td>$lang</td><td>$def_name</td><td>";
      if($matches == $total) 
          echo "<i>".CHECK_LANG_ALL_EQUAL."</i>";
      else
          echo $lang_equal;

      echo "</td><td><a href='$my_url?find_def=$def_name&file_name=$neuter_file'>".CHECK_LANG_WHERE_USED."</a></td></tr>\n";   
        }
    }
      }
  }
    }
    echo "</table>";
}

function tep_new_show_missing_def(&$lar) 
{
    global $my_url;

    $file_defs_checked = array();

    $lang_list = array_keys($lar);

    $neutral_file_list = tep_create_lang_neutral($lar);

    //itterate all files to find missing files
    echo "<table border=1>";
    echo "<tr><th>".CHECK_LANG_FOUND_FOR."</th><th>".CHECK_LANG_DEFINITION_NAME."</th><th>'".CHECK_LANG_MISSING_FOR."'</th><th>".CHECK_LANG_REFERENCES."</th></tr>\n";

    foreach ($neutral_file_list as $neuter_file) {
  $first_missing_in_file = 1;
  
  //create word-definition array
  $war = array();
  foreach($lang_list as $lang) 
      $war[$lang] = tep_read_lang_words($lang, $neuter_file);

  //check definitions are present for all files
  $defs_checked = array();
  foreach($war as $lang => $lang_def) {
      foreach($lang_def as $def_name => $def_val) {
    $total = 0;
    $missing = 0;
    $lang_missing = "";

    //keep list of verified $def_name's to avoid dupes
    if(!(array_search($def_name, $defs_checked) !== false)) {
        $defs_checked[] = $def_name;

        foreach($lang_list as $lang_comp) 
      if($lang_comp != $lang) {
          $total++;

//          echo "<br>search $def_name ($lang_comp) in";
//          print_r($war[$lang_comp]);
          
          if(!isset($war[$lang_comp][$def_name])) {
        $missing++;
        $lang_missing .= " [$lang_comp]";
          }
      }

        if($missing) {
      if($first_missing_in_file) {
          $first_missing_in_file = 0;
          echo "<tr><th colspan=3>includes/<i>[LANG_NAME]</i>$neuter_file</th><td></td></tr>\n";          
      }

      echo "<tr><td>$lang</td><td>$def_name</td><td>";

      if($missing == $total) 
          echo "<i>".CHECK_LANG_ALL_BUT." $lang</i>";
      else
          echo $lang_missing;


      echo "</td><td><a href='$my_url?find_def=$def_name&file_name=$neuter_file'>".CHECK_LANG_WHERE_USED."</a></td></tr>\n";   
        }
    }
      }
  }
    }
    echo "</table>";
}

function tep_file_contains($def_name, $file_name) 
{    
    $contents = file_get_contents($file_name);

    if(strstr($contents, $def_name))
  return 1;
    else
  return 0;
}    
    
function tep_find_occurences($def_name, $dir, $bstr = "") 
{
    global $my_url;

    $file_list = tep_read_files_in_dir($dir);

    $none_found = 1;
    $line_buf = "";
    echo "<table>\n<tr><th>".CHECK_LANG_FILE_NAME."</th></tr>\n";
    foreach($file_list as $file) {
  if(!strstr($file,"/languages/") &&
     is_file($file) &&
     tep_file_contains($def_name, $file)) {
      $none_found = 0;
      
      if(strstr($file, $bstr)) 
    echo "<tr><td><b>$file</b></td></tr>\n";
      else
    $line_buf .= "<tr><td>$file</td></tr>\n";
  }
    }
    
    
    if($none_found) 
  echo "<tr><td cospan=2 align=center><i>".CHECK_LANG_NONE_FOUND."</i></td></tr>\n";
    elseif($line_buf)
  echo $line_buf;
  
    echo "</table><br>";
}

function tep_show_main_menu($my_url) 
{
    echo "<table>
<tr><th colspan=2>".CHECK_LANG_SELECT_OPTION."</th></tr>
<tr><td>".CHECK_LANG_TO_CHECK_FILE_CONSISTENCY."</td>      <td><a href='$my_url?check_file=1'>".CHECK_LANG_CLICK_HERE."</a></td></tr>
<tr><td>".CHECK_LANG_TO_CHECK_DEFINITION_CONSISTENCY."</td><td><a href='$my_url?check_def=1'>".CHECK_LANG_CLICK_HERE."</a></td></tr>
<tr><td>".CHECK_LANG_TO_CHECK_EQUAL_DEFINITIONS."</td>     <td><a href='$my_url?check_eq=1'>".CHECK_LANG_CLICK_HERE."</a></td></tr>
</table>";

}

function tep_show_footer($my_url, $my_ref, $starttime) {
    
    echo "\n\n<br><a href='$my_url'>".CHECK_LANG_HOME."</a> - <a href='$my_ref'>".CHECK_LANG_BACK."</a>";
    
    $timeparts = explode(' ',microtime());
    $endtime = $timeparts[1].substr($timeparts[0],1);
    echo "<br><h6> ".CHECK_LANG_W_GODEFROY." - check_lang.php\n";
    echo " - ".CHECK_LANG_EXECUTION_TIME." " . bcsub($endtime,$starttime,6);
    echo " s </h6>";
}


//////////////////////////// PROGRAM START \\\\\\\\\\\\\\\\\\\\\\\\\ 

echo "<html>
<head>
<title>".CHECK_LANG_TITLE."</title>
</head>
<body>\n";

//tep_show_env();

$lang_dir = DIR_WS_INCLUDES . 'languages/';
$my_url = $PHP_SELF;
$my_file = $_SERVER['PATH_TRANSLATED'];
$my_ref = $_SERVER['HTTP_REFERER'];

if(isset($_GET["find_def"])) {
    echo "<h3>".CHECK_LANG_LIST_OF_FILES_USING." " . $_GET["find_def"] . "</h3><br>\n";
    
    tep_find_occurences($_GET["find_def"],
      dirname($my_file),
      $_GET["file_name"]);

} elseif(isset($_GET["check_file"])) {
    $lar = array();

    echo "<h3>".CHECK_LANG_LIST_OF_FILES_PRESENT_FOR_ONE_LANGUAGE."</h3><br>\n";

    tep_scan_lang_dirs($lar, $lang_dir);
    
    tep_read_lang_files($lar);
    
//    tep_show_missing_files($lar);
    tep_new_show_missing_files($lar);

} elseif(isset($_GET["check_def"])) {
echo "<h3>".CHECK_LANG_LIST_OF_PHRASE_PRESENT_FOR_ONE_LANGUAGE."</h3><br>\n";

    tep_scan_lang_dirs($lar, $lang_dir);
    tep_read_lang_files($lar);    
    //tep_show_missing_equal_def($lar);
    tep_new_show_missing_def($lar);

} elseif(isset($_GET["check_eq"])) {
    echo "<h3>".CHECK_LANG_LIST_OF_PHRASE_DEFINITIONS_EQUAL."</h3><br>\n";
    tep_scan_lang_dirs($lar, $lang_dir);
    tep_read_lang_files($lar);
    //tep_show_missing_equal_def($lar, 1);
    tep_new_show_equal_def($lar);
    
}  else {
    tep_show_main_menu($my_url);
}


tep_show_footer($my_url, $my_ref, $starttime);

echo "</body></html>\n";

?>
