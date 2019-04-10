<?php
class CompleateCheckoutCall
{
  var $storeToken, $buyerEmail,$content, $xmlparser;
  
  /**  __construct
  */
  function CompleateCheckoutCall($storeToken, $buyerEmail)
  {
    $this->storeToken = $storeToken;
    $this->buyerEmail = $buyerEmail;    
  }
  
  function callToXML($products, $currrency, $reward, $rewardTotal, $amount, $currency)
  {
    ///Build the request Xml string
    $requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
    $requestXmlBody .= '<CompleateCheckoutRequest xmlns="urn:msr:apis:MsrBaseComponents">';
    $requestXmlBody .= "<RequesterCredentials><SiteToken>".$this->storeToken."</SiteToken></RequesterCredentials>";
    $requestXmlBody .= "<BuyerEmail>".$this->buyerEmail."</BuyerEmail>";
    $requestXmlBody .= "<BuyerRewardPercent>".$reward."</BuyerRewardPercent>";
    $requestXmlBody .= "<BuyerRewardTotal>".$rewardTotal."</BuyerRewardTotal>";
    $requestXmlBody .= "<TransactionTotal>".$amount."</TransactionTotal>";
    $requestXmlBody .= "<TransactionCurrency>".$currency."</TransactionCurrency>";

    $requestXmlBody .= "<CardProducts>";
    for ($i=0, $n=sizeof(products); $i<$n; $i++) {
        $requestXmlBody .="<Product>";
        $requestXmlBody .="<Title>".$products[$i]['name']."</Title>";
        $requestXmlBody .="<Price>".$products[$i]['final_price']."</Price>";
        $requestXmlBody .="<Currency>".$currrency."</Currency>";
        $requestXmlBody .="</Product>";
      }
    $requestXmlBody .= "</CardProducts>";
      $requestXmlBody .= '</CompleateCheckoutRequest>';
    
    return $requestXmlBody;
  }
}
?>