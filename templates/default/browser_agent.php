<?php
/*
  $Id: browser_agent.php,v 1.0 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$agent['http'] = isset($_SERVER["HTTP_USER_AGENT"]) ? strtolower($_SERVER["HTTP_USER_AGENT"]) : "";
$agent['version'] = 'unknown';
$agent['browser'] = 'unknown';
$agent['b_version'] = 0;
$agent['platform'] = 'unknown';
$oss = array('win', 'mac', 'linux', 'unix');
foreach ($oss as $os) {
  if (strstr($agent['http'], $os)) {
    $agent['platform'] = $os;
    break;
  }
}
$browsers = "mozilla msie gecko firefox ";
$browsers.= "konqueror safari netscape navigator ";
$browsers.= "opera mosaic lynx amaya omniweb";
$browsers = split(" ", $browsers);
$nua = strToLower( $_SERVER['HTTP_USER_AGENT']);
$l = strlen($nua);
for ($i=0; $i<count($browsers); $i++){
  $browser = $browsers[$i];
  $n = stristr($nua, $browser);
  if(strlen($n)>0){
   $agent["b_version"] = "";
   $agent["browser"] = $browser;
   $j=strpos($nua, $agent["browser"])+$n+strlen($agent["browser"])+1;
   for (; $j<=$l; $j++){
     $s = substr ($nua, $j, 1);
     if(is_numeric($agent["b_version"].$s) )
     $agent["b_version"] .= $s;
     else
     break;
   }
  }
}
?>