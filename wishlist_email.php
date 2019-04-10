<?php
/*
 $Id: wishlist_email.php,v 2.0  2004/08/11

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
  //check if customer is logged in
  if ( !isset($_SESSION['customer_id']) ) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  } elseif ( isset($_SESSION['customer_id']) ) {
    $account_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
    $account = tep_db_fetch_array($account_query);
    $from_name = $account['customers_firstname'] . ' ' . $account['customers_lastname'];
    $from_email_address = $account['customers_email_address'];
  }
  
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_WISHLIST_SEND);
    
  //check for valid product
   $valid_product = "false";
   
   $wish_products_id = isset($_GET['products_id']) ? (int)$_GET['products_id'] : 0;
   $friends_email_id = (isset($_GET['send_to']) && $_GET['send_to'] != '') ? $_GET['send_to'] : '';
   
   $wishlist_query_raw = "select tab2.products_id, tab1.products_name from " . TABLE_WISHLIST . " as tab2, " . TABLE_PRODUCTS_DESCRIPTION . " as tab1 WHERE tab2.customers_id='" . (int)$_SESSION['customer_id'] . "' and tab1.products_id = tab2.products_id and tab1.language_id = '" . (int)$languages_id . "' order by products_name";
   $wishlist_query = tep_db_query($wishlist_query_raw);
   if (!isset($wishliststring)) {
       $wishliststring = '';
   }
   
   while ($resultarray=tep_db_fetch_array($wishlist_query)) {
       $wishliststring .=' ' . '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?products_id=' . $resultarray['products_id'] . '">' . $resultarray['products_name'] . '</a>';
       /*******************gsr**********************/
       if ($_SESSION['customer_id'] > 0) {
           $wishlist_products_attributes_query = tep_db_query("select products_options_id as po, products_options_value_id as pov from " . TABLE_WISHLIST_ATTRIBUTES . " where customers_id='" . $_SESSION['customer_id'] . "' and products_id = '" . $resultarray['products_id'] . "'");
           while ($wishlist_products_attributes = tep_db_fetch_array($wishlist_products_attributes_query)) {
               $data1 = $wishlist_products_attributes['pov'];
               $data = unserialize(str_replace("\\",'',$data1));
               if(array_key_exists('c',$data)) {
                   foreach($data['c'] as $ak => $av) {
                       $data = $av;
                       // We now populate $id[] hidden form field with product attributes
                       echo tep_draw_hidden_field('id['.$products['products_id'].']['.$wishlist_products_attributes['po'].']', $wishlist_products_attributes['pov']);
                       // And Output the appropriate attribute name
                       $attributes = tep_db_query("select poptt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_TEXT . " poptt,  " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                where pa.products_id = '" . $resultarray['products_id'] . "'
                                and pa.options_id = '" . $wishlist_products_attributes['po'] . "'
                                and pa.options_id = popt.products_options_id
                                and pa.options_values_id = '" . $data . "'
                                and pa.options_values_id = poval.products_options_values_id
                                and poptt.products_options_text_id = popt.products_options_id
                                and poptt.language_id = '" . $languages_id . "'
                                and poval.language_id = '" . $languages_id . "'");
                                
                                  $attributes_values = tep_db_fetch_array($attributes);
                                  if ($attributes_values['price_prefix'] == '+') {
                                    $attributes_addon_price += $attributes_values['options_values_price']; 
                                  } else if ($attributes_values['price_prefix'] == '-') {
                                    $attributes_addon_price -= $attributes_values['options_values_price']; 
                                  }
                                  $att =  '<br><small><i>' . $attributes_values['products_options_name'] . '&nbsp;:&nbsp; ' . $attributes_values['products_options_values_name'] .'&nbsp; :'.$attributes_values['price_prefix'].$attributes_values['options_values_price']. '</i></small>' . "\n";
                                   $wishliststring .= $att;
                                  // end while attributes for product
                                }
                              } else {
                                $data = implode(",", $data);
                                // We now populate $id[] hidden form field with product attributes
                                echo tep_draw_hidden_field('id['.$products['products_id'].']['.$wishlist_products_attributes['po'].']', $wishlist_products_attributes['pov']);
                                // And Output the appropriate attribute name
                                $attributes = tep_db_query("select poptt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_TEXT . " poptt,  " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                where pa.products_id = '" . $resultarray['products_id'] . "'
                                and pa.options_id = '" . $wishlist_products_attributes['po'] . "'
                                and pa.options_id = popt.products_options_id
                                and pa.options_values_id = '" . $data . "'
                                and pa.options_values_id = poval.products_options_values_id
                                and poptt.products_options_text_id = popt.products_options_id
                                and poptt.language_id = '" . $languages_id . "'
                                and poval.language_id = '" . $languages_id . "'");
                                
                                $attributes_values = tep_db_fetch_array($attributes);
                                if ($attributes_values['price_prefix'] == '+') {
                                    $attributes_addon_price += $attributes_values['options_values_price']; 
                                } else if ($attributes_values['price_prefix'] == '-') {
                                    $attributes_addon_price -= $attributes_values['options_values_price']; 
                                }
                               $att =  '<br><small><i>' . $attributes_values['products_options_name'] . '&nbsp;:&nbsp; ' . $attributes_values['products_options_values_name'] .'&nbsp; :'.$attributes_values['price_prefix'].$attributes_values['options_values_price']. '</i></small>' . "\n";
                               $wishliststring .= $att; 
                              } // end while attributes for product
           }
       }
                               $wishliststring .= "\n"; 
   }


$product_info_query = tep_db_query("select pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (isset($_GET['products_id']) ? (int)$_GET['products_id'] : 0) . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
    if (tep_db_num_rows($product_info_query)) {
        $valid_product = "true";
        $product_info = tep_db_fetch_array($product_info_query);
    } else{
        $valid_product = "false";
    }

 if (isset($_GET['action']) && ($_GET['action'] == 'process')) {   
    $error = "false";
    $_POST['to_email_address'] = preg_replace( "/\n/", " ", $_POST['to_email_address'] );
    $_POST['to_name'] = preg_replace( "/\n/", " ", $_POST['to_name'] );
    $_POST['to_email_address'] = preg_replace( "/\r/", " ", $_POST['to_email_address'] );
    $_POST['to_name'] = preg_replace( "/\r/", " ", $_POST['to_name'] );
    $_POST['to_email_address'] = str_replace("Content-Type:","",$_POST['to_email_address']);
    $_POST['to_name'] = str_replace("Content-Type:","",$_POST['to_name']);
    
    $to_email_address = strtolower(tep_db_prepare_input($_POST['to_email_address']));
    $to_name = tep_db_prepare_input($_POST['to_name']);
    $from_email_address = strtolower(tep_db_prepare_input($_POST['from_email_address']));
    $from_name = tep_db_prepare_input($_POST['from_name']);
    $message = tep_db_prepare_input($_POST['message']);
    
       if (empty($from_name)) {
          $error = "true";
          $messageStack->add('friend', ERROR_FROM_NAME);
        }

        if (!tep_validate_email($from_email_address)) {
          $error = "true";
          $messageStack->add('friend', ERROR_FROM_ADDRESS);
        }
    
        if (empty($to_name)) {
          $error = "true";
          $messageStack->add('friend', ERROR_TO_NAME);
        }
    
        if (!tep_validate_email($to_email_address)) {
          $error = "true";
          $messageStack->add('friend', ERROR_TO_ADDRESS);
        }

  $email_subject = sprintf(TEXT_EMAIL_SUBJECT, $from_name, STORE_NAME);
  $email_body = sprintf(TEXT_EMAIL_INTRO, $to_name, $from_name, STORE_NAME) . "\n\n";
      if (tep_not_null($message)) {
         $email_body .= $message . "\n\n";
        }

     if (WISH_EMAIL_USE_HTML == "false") {
         $email_body .= TEXT_EMAIL_LINK_TEXT . FORM_FIELD_TEXT_AREA ;
         $email_body .= $wishliststring . "\n";
         $email_body .= TEXT_EMAIL_SIGNATURE . STORE_NAME . "\n" . '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG .'">' .  HTTP_SERVER  . DIR_WS_CATALOG . '</a>';
     }else{
         $email_body .= TEXT_EMAIL_LINK . FORM_FIELD_TEXT_AREA;
         $email_body .= nl2br($wishliststring) . '<br>';
         $email_body .= TEXT_EMAIL_SIGNATURE. STORE_NAME . "\n" . '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG .'">' .  HTTP_SERVER  . DIR_WS_CATALOG . '</a>';
     }
  
     
         $mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));
 
          if (WISH_EMAIL_USE_HTML == "false") {
              $mimemessage->add_text($email_body);
          } else {
              $mimemessage->add_html($email_body);
          }
          $mimemessage->build_message();
          $mimemessage->send($to_name, $to_email_address, $from_name, $from_email_address, $email_subject);
          $messageStack->add_session('header', sprintf(TEXT_EMAIL_SUCCESSFUL_SENT, $product_info['products_name'], tep_output_string_protected($to_name)), 'success');
          tep_redirect(tep_href_link(FILENAME_WISHLIST));
  }


  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_WISHLIST_SEND, '', 'NONSSL'));
  $content = CONTENT_WISHLIST_SEND;
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>