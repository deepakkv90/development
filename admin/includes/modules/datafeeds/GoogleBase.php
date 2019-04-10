<?php
/*
  $Id: GoogleBase.php,v 1.0.0 2009/10/01 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  class GoogleBase {
    var $title, $code, $file;

    function GoogleBase() {
      $this->code = 'GoogleBase';
      $this->title = 'Google Base'; 
      $this->description = 'Google Base Feed Generator Class';
      $this->ftp_server = 'uploads.google.com';
    }

    function buildFeedHead($creFeed_store_description = ''){
        $content['xml'] = '<?xml version="1.0" encoding="UTF-8" ?>';
        $content['rss'] = '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
        $content['channel'] = '<channel>';
        $content['title'] = '<title>' . cre_stripInvalidXml(STORE_NAME, true) . '</title>';
        $content['link'] = '<link>' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . '</link>';
        $content['description'] = '<description>' . cre_stripInvalidXml($creFeed_store_description, true) . '</description>';
        $content['managingEditor'] = '<managingEditor>' . STORE_OWNER_EMAIL_ADDRESS . ' (' . cre_stripInvalidXml(STORE_NAME, true) . ')</managingEditor>';
        $content['generator'] = '<generator>' . PROJECT_VERSION . '</generator>' . "\n";
        
        return $content;
    }

    function buildFeedFoot(){
        $content["e_channel"] = '</channel>';
        $content["e_rss"] = '</rss>';
        
        return $content;
    }

    function buildFeedNodes($data, $lang_id){
        $content['item'] = '<item>'; 
        $content['title'] = ' <title>' . cre_stripInvalidXml($data['name']) . '</title>';
        if($data['mfg_name'] != ''){
            $content['brand'] = ' <g:brand>' . cre_stripInvalidXml($data['mfg_name']) . '</g:brand>';
        }
         $content['condition'] = ' <g:condition>new</g:condition>';
         $content['product_type'] = ' <g:product_type>' . cre_stripInvalidXml($data['categories_name']) . '</g:product_type>';
         $content['weight'] = ' <g:weight>' . $data['weight'] . '</g:weight>';
         $content['id'] = ' <g:id>' . $data['id'] . '</g:id>';
//Globally Unique Identifier - make it unique
//http://www.webopedia.com/TERM/G/GUID.html
         $content['guid'] = ' <guid isPermaLink="false">' . time() . substr(md5(microtime()), 0, rand(5, 12)) . $data['id'] . '</guid>';
        if($data['image_link'] != ''){
         $content['image_link'] = ' <g:image_link>' . $data['image_url'] . '</g:image_link>';
        }
         $content['link'] = ' <link>' . tep_feeder_href_link(FILENAME_PRODUCT_INFO,'cPath=' . tep_get_product_path($data['id']) . '&amp;products_id=' . $data['id'] . '&amp;language=' . $data['lang_id'],'NONSSL') . '</link>';
         $content['price'] = ' <g:price>' . $data['price'] . '</g:price>';
         $content['model_number'] = ' <g:model_number>' . cre_stripInvalidXml($data['model']) . '</g:model_number>';
         $content['currency'] = ' <g:currency>USD</g:currency>';
//Need automation here...
//Acceptable values are: Cash, Check, Visa, MasterCard, AmericanExpress, Discover, and WireTransfer.
         $content['payment_accepted'] = ' <g:payment_accepted>Cash</g:payment_accepted>';
//         $content['payment_accepted'] = ' <g:payment_accepted>Visa</g:payment_accepted>';
//         $content['payment_accepted'] = ' <g:payment_accepted>Master Card</g:payment_accepted>';
//         $content['payment_accepted'] = ' <g:payment_accepted>POD</g:payment_accepted>';
//         $content['pickup'] = ' <g:pickup>true</g:pickup>';
         $content['quentity'] = ' <g:quantity>' . $data['quantity'] . '</g:quantity>';
         $content['description'] = ' <description>' . cre_stripInvalidXml(cre_get_description($data['id'], $data['lang_id']), true) .'</description>';
         $content['e_item'] = '</item>' . "\n";

    return $content;
    }
}//class
?>