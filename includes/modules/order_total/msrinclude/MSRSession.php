<?php
  /********************************************************************************
    * AUTHOR: Vilcu Micu Alin - MyStoreRewards (www.mystorerewards.com) *
    *******************************************************************************/

class MSRSession
{
  var $serverUrl, $callName, $storeID;
  
  /**  __construct
    Constructor to make a new instance of MSRSession with the details needed to make a call
    Input:  $storeID - The authentication token for the store
        $callName  - The name of the call being made
    Output:  Response string returned by the server
  */
  function MSRSession($storeID, $callName, $serverURL, $proxy_url, $proxy_port)
  {
    $this->storeID = $storeID;
    $this->callName = $callName;
    $this->serverUrl = $serverURL;
    $this->serverProxyUrl = $proxy_url;
    $this->serverProxyPort = $proxy_port;
  }


    function sendHttpRequest($requestBody){

        $XPost="xml=".$requestBody;

        $ch = curl_init();    // initialize curl handle
        curl_setopt($ch, CURLOPT_URL,$this->serverUrl); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // times out after 4s
        curl_setopt($ch, CURLOPT_POSTFIELDS, $XPost); // add POST fields

        if ($this->serverProxyUrl !== '' && $this->serverProxyPort !== '') {
            curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt ($ch, CURLOPT_PROXY, $this->serverProxyUrl.":".$this->serverProxyPort);
        }

        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        $result = curl_exec($ch); // run the whole process
        return $result; //contains response from server

    }
}
?>