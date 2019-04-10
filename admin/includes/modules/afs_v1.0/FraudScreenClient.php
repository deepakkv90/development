<?php

/* FraudScreenClient.php
 *
 * Copyright (C) 2004-2005 AlgoZone, Inc
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$API_VERSION = 'PHP/1.2';
class FraudScreenClient{
  var $server;
  var $numservers;
  var $url;
  var $params;
  var $allowed_fields;
  var $num_allowed_fields;
  var $outputstr;
  var $isSecure;
  var $timeout;
  var $debug;
  
  var $proxy_host; // if using code that has to pass thru proxy
  var $proxy_port;
    
  function FraudScreenClient() {
  
    $this->isSecure = 1;    // use HTTPS by default
    $this->num_allowed_fields = 8;

    //set the allowed_fields hash
    $this->allowed_fields["ip"] = 1;
    $this->allowed_fields["email_domain"] = 1;
    $this->allowed_fields["city"] = 1;
    $this->allowed_fields["region"] = 1;
    $this->allowed_fields["postal_code"] = 1;
    $this->allowed_fields["country"] = 1;
    $this->allowed_fields["bin"] = 1;
    $this->allowed_fields["bank_name"] = 1;
    $this->allowed_fields["bank_phone"] = 1;
    $this->allowed_fields["customer_phone"] = 1;
    $this->allowed_fields["requestor_license_key"] = 1;
    $this->allowed_fields["request_type"] = 1;

    //set the url of the web service
    $this->server = array("fraudscreen1.algozone.com","fraudscreen2.algozone.com","fraudscreen.algozone.com");
    $this->numservers = sizeof($this->server);
    $this->url = "index.php";

  }

  // this function sets the allowed fields
  function set_allowed_fields($i) {
    $this->allowed_fields = $i;
    $this->num_allowed_fields = count($i);
  }

  //this function queries the servers
  function query() {
    //query every server in the list
    $server_list = $this->server;
    print "<br>";
    for ($i = 0; $i < $this->numservers; $i++ ) {
      $result = $this->queryServer($this->server[$i]);
      if ($this->debug == 1) {
        //print "<p><b>AFS <i>" . $this->server[$i] . "</i> query result: </b>" . $result . "<br>";
        print "<p><b>".FRAUDSCREENCLIENT_AFS." <i>" . $this->server[$i] . "</i> ".FRAUDSCREENCLIENT_QUERY_RESULT.": </b>" . $result . "<br>";
      }
      if ($this->outputstr['afs_version'] != "" && ($result ==1 || $this->outputstr['err_message'] != "")) {
        return $result;
      }
    }
    
   // $this->outputstr['err_message'] = "Algozone Fraud Screen Server currently unavailable. Please try again later.";

    //$this->outputstr['err_message'] = "Algozone Fraud Screen Server currently unavailable. Please try again later.";
    $this->outputstr['err_message'] = FRAUDSCREENCLIENT_SERVER_UNAVAILABLE;
    return 0;
  }

  //this function takes a input hash and stores it in the hash named queries
  function input($vars) {
    $numinputkeys = count($vars);  // get the number of keys in the input hash
    $inputkeys = array_keys($vars);   // get a array of keys in the input hash
    if($numinputkeys >0){
        if ($this->debug == 1) {
          //print "<br><b>AFS Inputs:</b>";
          print "<br><b>".FRAUDSCREENCLIENT_AFS_INPUTS.":</b>";
      }
    }
    for ($i = 0; $i < $numinputkeys; $i++) {
      $key = $inputkeys[$i];
      if ($this->allowed_fields[$key] == 1) {
        //if key is a allowed field then store it in 
        //the hash named queries
        if ($this->debug == 1) {
          //print "<br>input $key = " . $vars[$key] . "";
          print "<br>".FRAUDSCREENCLIENT_INPUT." $key = " . $vars[$key] . "";
      }
        $this->params[$key] = urlencode($vars[$key]);
      } else {
        //print "<br>invalid input $key - perhaps misspelled field?";
        print "<br>".FRAUDSCREENCLIENT_INVALID_INPUT.$key .FRAUDSCREENCLIENT_MISSPELLED_FIELD;
      return 0;
      }
    }
    $this->params["clientAPI"] = $GLOBALS['API_VERSION'];
  }

  //this function returns the output from the server
  function output() {
    return $this->outputstr; 
  }

  //this function query a single server
  function queryServer($server) {

    //check if we using the Secure HTTPS proctol
    if ($this->isSecure == 1) {
      $scheme = "https://";  //Secure HTTPS proctol
    } else {
      $scheme = "http://";   //Regular HTTP proctol
    }

    //build a query string from the hash called queries
    $numquerieskeys = count($this->params);//get the number of keys in the hash called queries
    $querieskeys = array_keys($this->params);//get a array of keys in the hash called queries
    if ($this->debug == 1) {
//      print "<p>number of query keys " + $numquerieskeys + "<p>";
    }
    for ($i = 0; $i < $numquerieskeys; $i++) {
      //for each element in the hash called queries 
      //append the key and value of the element to the query string
      $key = $querieskeys[$i];
      $value = $this->params[$key];

      $query_string = $query_string . $key . "=" . $value;
      if ($i < $numquerieskeys - 1) {
        $query_string = $query_string . "&";
      }
    }
    
    $content = "";

    //check if the curl module exists
    if (extension_loaded('curl') && $this->proxy_port != '') {
      //use curl
      if ($this->debug == 1) {
        //print "<p><b>AFS using curl</b>";
        print "<p><b>".FRAUDSCREENCLIENT_AFS_USING_CURL."</b>";
      }

      //open curl
      $ch = curl_init();

      $url = $scheme . $server . "/" . $this->url;

      if ($this->debug == 1) {
       // print "<p><b>AFS curl params: ".$url."?".$query_string."</b>";
        print "<p><b>".FRAUDSCREENCLIENT_AFS_CURL_PARAMS.": ".$url."?".$query_string."</b>";
      }

      //set curl options
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

      //this option lets you store the result in a string 
      curl_setopt($ch, CURLOPT_POST,          1);
      curl_setopt($ch, CURLOPT_POSTFIELDS,    $query_string);

      // added support for curl proxy
      if (defined('CURL_PROXY_HOST') && defined('CURL_PROXY_PORT') && CURL_PROXY_HOST != '' && CURL_PROXY_PORT != '') {
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
        curl_setopt($ch, CURLOPT_PROXY, CURL_PROXY_HOST . ":" . CURL_PROXY_PORT);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      }
      if (defined('CURL_PROXY_USER') && defined('CURL_PROXY_PASSWORD') && CURL_PROXY_USER != '' && CURL_PROXY_PASSWORD != '') {
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, CURL_PROXY_USER . ':' . CURL_PROXY_PASSWORD);
      }
      
      //get the content
      $content = curl_exec($ch);

      // For some reason curl_errno returns an error even when function works
      // Until we figure this out, will ignore curl errors - (not good i know)
      $e = curl_errno($ch);//get error or sucess

      if (($e == 1) && ($this->isSecure == 1)) {
        // HTTPS does not work print error message
          //print "<br>error: this version of curl does not support HTTPS try build curl with SSL or specify \$ccfs->isSecure = 0\n";
          print FRAUDSCREENCLIENT_AFS_CURL_NOT_SUPPORT." \$ccfs->isSecure = 0\n";
      }
      if ($e > 0) {
        //we get a error msg print it
        if ($this->debug == 1) {
          //print "<p>Received error message $e from curl: " . curl_error($ch) . "\n";
          print "<p>".FRAUDSCREENCLIENT_AFS_RECEIVED_ERROR_MESSAGE_1." $e ".FRAUDSCREENCLIENT_AFS_RECEIVED_ERROR_MESSAGE_2.": " . curl_error($ch) . "\n";
       }
      return 0;
      }
      //close curl
      curl_close($ch);
     
    } else if (extension_loaded('curl')) {
      //use curl
      if ($this->debug == 1) {
        //print "<p>using curl thru proxy";
        print FRAUDSCREENCLIENT_AFS_CURL_PROXY;
      }

      //open curl
      $ch = curl_init();

      $url = $scheme . $server . "/" . $this->url;

      if ($this->debug == 1) {
        //print "<p><b>AFS curl params: </b>".$url."?".$query_string;
        print "<p><b>".FRAUDSCREENCLIENT_AFS_CURL_PARAMS.": </b>".$url."?".$query_string;
      }

      //set curl options
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

     curl_setopt($ch,CURLOPT_PROXY,$this->proxy_host); 
     curl_setopt($ch,CURLOPT_PROXY_PORT,$this->proxy_port); 

      //this option lets you store the result in a string 
      curl_setopt($ch, CURLOPT_POST,          1);
      curl_setopt($ch, CURLOPT_POSTFIELDS,    $query_string);

      //get the content
      $content = curl_exec($ch);

      // For some reason curl_errno returns an error even when function works
      // Until we figure this out, will ignore curl errors - (not good i know)
      $e = curl_errno($ch);//get error or sucess

      if (($e == 1) && ($this->isSecure == 1)) {
        // HTTPS does not work print error message
         // print "<br>error: this version of curl does not support HTTPS try build curl with SSL or specify \$ccfs->isSecure = 0\n";
          print FRAUDSCREENCLIENT_AFS_CURL_NOT_SUPPORT." \$ccfs->isSecure = 0\n";
      }
      if ($e > 0) {
        if ($this->debug == 1) {
          //print "<p>Received error message $e from curl: " . curl_error($ch) . "\n";
          print "<p>".FRAUDSCREENCLIENT_AFS_RECEIVED_ERROR_MESSAGE_1." $e ".FRAUDSCREENCLIENT_AFS_RECEIVED_ERROR_MESSAGE_2.": " . curl_error($ch) . "\n";
      }
      return 0;
      }
      //close curl
      curl_close($ch);
     
    } else {
      //curl does not exist
      //use the fsockopen function, 
      //the fgets function and the fclose function

      if ($this->debug == 1) {
       // print "<p><b>AFS using fsockopen</b>";
        print FRAUDSCREENCLIENT_AFS_USING_FSOCKOPEN;
      }

      $url = $scheme . $server . "/" . $this->url . "?" . $query_string;
      if ($this->debug == 1) {
       // print "<p><table width=100><tr><td><b>AFS socket url param: </b>" . $url . " " . "</td></tr></table>";
        print "<p><table width=100><tr><td><b>".FRAUDSCREENCLIENT_AFS_SOCKET_PARAM.": </b>" . $url . " " . "</td></tr></table>";
      }

      //now check if we are using regular HTTP
      if ($this->isSecure == 0) {
      
       //we using regular HTTP

        //parse the url to get
        //host, path and query
        $url3 = parse_url($url);
        $host = $url3["host"];
        $path = $url3["path"] . "?" . $url3["query"];
      $port = 80;

      if(!empty($this->proxy_host) && !empty($this->proxy_port))
      {
        $this->_isproxy = true;
        
        $host = $this->proxy_host;
        $port = $this->proxy_port;

          if ($this->debug == 1) {
           //print "<p><b>AFS using fsockopen proxy<b><br>";
           print FRAUDSCREENCLIENT_AFS_FSOCKOPEN_PROXY;
          }
      }
      else if(!empty($this->proxy_host))
      {
           //print "<br>error: you need to provide the proxy port number to use the proxy port provided";
           print FRAUDSCREENCLIENT_AFS_PROXY_PORT;
      }

        //open the connection
        $fp = @ fsockopen ($host, $port, $errno, $errstr, $this->timeout);
        if ($fp) {
          //send the request
          fputs ($fp, "GET $path HTTP/1.0\nHost: " . $host . "\n\n");
          while (!feof($fp)) {
            $buf .= fgets($fp, 128);
          }
          $lines = split("\n", $buf);
          
        // get the content
          $content = $lines[count($lines)-1];
        
          //close the connection
          fclose($fp);
        } else {
          return 0;
      }
      } else {
        //secure HTTPS requires CURL
       // print "<br>error: you need to install curl if you want secure HTTPS or specify the variable to be $ccfs->isSecure = 0";
        print FRAUDSCREENCLIENT_AFS_INSTALL_CURL." $ccfs->isSecure = 0";
        return 0;
      }
    }
    
    // get the keys and values from
    // the string content and store them
    // the hash named outputstr

    // split content into pairs containing both 
    // the key and the value
    $keyvaluepairs = explode(";",$content);

    //get the number of key and value pairs
    $numkeyvaluepairs = count($keyvaluepairs);

    //for each pair store key and value into the
    //hash named outputstr
    if ($this->debug == 1) {
     // print "<p><b>AFS query results: </b>";
      print FRAUDSCREENCLIENT_AFS_QUERY_RESULTS;
    }
    for ($i = 0; $i < $numkeyvaluepairs; $i++) {
      //split the pair into a key and a value
      list($key,$value) = explode("=",$keyvaluepairs[$i]);
      if ($this->debug == 1) {
        //print "<br> output " . $key . " = " . $value . "\n";
        print "<br> ".FRAUDSCREENCLIENT_AFS_OUTPUT." " . $key . " = " . $value . "\n";
      }
      //store the key and the value into the
      //hash named outputstr
      $this->outputstr[$key] = $value;
    }
    //check if outputstr has the score if outputstr does not have 
    //the score return 0
    if ($this->outputstr['err_message'] != "") {
      return 0;
    }
    return 1;
  }
}
?>
