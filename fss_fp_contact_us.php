<?php
/*
  $Id: fss_fp_contact_us.php,v 1.0.0.0 2008/06/19 10:20:48 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FSS_FORMPOST_CONTACT_US);
$forms_id = '1';
$breq_products_id = $_GET['products_id'];
//check for valid product
$valid_product = false;
if ( isset($breq_products_id) ) {
  $product_info_query = tep_db_query("select pd.products_id, pd.products_name, pd.products_description, p.products_image, p.products_price, p.products_tax_class_id, p.products_model from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id='" . (int)$_GET['products_id'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
  if (tep_db_num_rows($product_info_query)) {
      $valid_product = true;
      $product_info = tep_db_fetch_array($product_info_query);
  } else {
      tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $breq_products_id));
  }
}
if (isset($_GET['action']) && ($_GET['action'] == 'send_post')) {   
  $forms_fields = array();
  $forms_fields_query = tep_db_query("SELECT ff.fields_name, ff.fields_required FROM " . TABLE_FSS_FIELDS_TO_FORMS . " f2f, " . TABLE_FSS_FORMS_FIELDS . " ff WHERE f2f.forms_id ='" . $forms_id . "' and ff.fields_id = f2f.fields_id order by ff.sort_order");
  while ($forms_fields_array = tep_db_fetch_array($forms_fields_query)) {
    $forms_fields[$forms_fields_array['fields_name']] = $forms_fields_array['fields_required'];
  }
  $error = false;
  foreach ($_POST as $id => $item) {
    if(strstr($id, 'fields_') ) {
      $value_label = str_replace('_', ' ', substr($id, (strpos($id, 'fields_')+7) ));
      if ($forms_fields[$value_label] == '1') {
        if (trim($item) == '') {
          $error = true;
          $messageStack->add('fp_contact_us', sprintf(ENTRY_FORMS_FIELDS_REQUIRED, $value_label));
        }
      }
    }
  }
  if (!$error) {
    $posts_id_array = tep_db_fetch_array(tep_db_query("SELECT max(forms_posts_id) AS maxid FROM ". TABLE_FSS_FORMS_POSTS ));
    $posts_id = ((int)$posts_id_array['maxid']) + 1;
    $posts_array = array('forms_id'=>$forms_id,
                         'forms_posts_id'=>$posts_id,
                         'posts_date'=>'now()',
                         'products_id'=>$_POST['products_id'],
                         'customers_id'=>((int)$_SESSION['customer_id'])
                        );
    tep_db_perform(TABLE_FSS_FORMS_POSTS, $posts_array);
    $emailaddress = '';
    $customers_name = '';
    foreach ( $_POST as $id =>$item ) {
      if( strstr( $id, 'fields_' ) ) {
        $value_label = str_replace('_', ' ', substr($id, (strpos($id, 'fields_')+7) ));
        $postvalue_array = array('forms_id'=>$forms_id,
                                 'forms_posts_id'=>$posts_id,
                                 'forms_fields_label'=>$value_label,
                                 'forms_fields_value'=>$item);
        tep_db_perform(TABLE_FSS_FORMS_POSTS_CONTENT, $postvalue_array);
        if( strstr(strtolower($value_label), 'email') ) {
          $emailaddress = $item;
        }
        if( strstr(strtolower($value_lable), 'name') ) {
          $customers_name = $item;
        }
      }
    }
    if($emailaddress!='' && tep_validate_email($emailaddress)) {
      if($customers_name == '') $customers_name="A customer";
      $email_subject = sprintf(TEXT_EMAIL_SUBJECT."(".$_POST['products_id'].")", $customers_name, STORE_NAME);
      $email_body = sprintf(TEXT_EMAIL_INTRO, STORE_OWNER, $customers_name, $_POST['products_id'], $product_info['products_name'], STORE_NAME) . "\n\n";
      $email_body .= sprintf(TEXT_EMAIL_LINK, tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_POST['products_id'])) . "\n\n" ;
      $email_body .= sprintf(TEXT_REQUEST_LINK, HTTP_SERVER. DIR_WS_HTTP_CATALOG. 'admin/forms_posts_admin?pID='.$posts_id)."\n\n";
      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $email_subject, $email_body, $customers_name, $requests_email_address);
    }
  }
}
$content = CONTENT_FSS_FORMPOST_CONTACT_US;
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>