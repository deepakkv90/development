<?php
class GetDiscountScriptCall
{
  var $storeToken, $buyerEmail,$content, $xmlparser;
  
  /**  __construct
  */
  function GetDiscountScriptCall($storeToken, $buyerEmail)
  {
  $this->storeToken = $storeToken;
    $this->buyerEmail = $buyerEmail;    
  }
  
  function callToXML()
  {
    ///Build the request Xml string
    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
    $requestXmlBody .= '<GetDiscountScriptRequest xmlns="urn:msr:apis:MsrBaseComponents">';
    $requestXmlBody .= "<RequesterCredentials><SiteToken>".$this->storeToken."</SiteToken></RequesterCredentials>";
    $requestXmlBody .= "<BuyerEmail>".$this->buyerEmail."</BuyerEmail>";
    $requestXmlBody .= '</GetDiscountScriptRequest>';
    return $requestXmlBody;
  }

  function getScriptBody($xmlResponse)
  {
    $this->content='';
    $this->xmlparser = xml_parser_create('UTF-8');
    xml_set_object($this->xmlparser, $this);
    $xmlResponse=eregi_replace(">"."[[:space:]]+"."< ",">< ",$xmlResponse);
    xml_set_element_handler($this->xmlparser, "start_tag", "end_tag");
    xml_set_character_data_handler($this->xmlparser, "tag_contents");
    xml_parse($this->xmlparser,$xmlResponse);
    xml_parser_free($this->xmlparser);
    return $this->content;
  }
  

     function start_tag($parser, $name, $attribs) {
    global $current;
    $current = $name;
      }

    function end_tag($parser, $name) {
     }


   function tag_contents($parser, $data) {
     global $current;

     if ($current == "REBATESCRIPT"){
    $this->content .= $data;
     }
  }
}
?>