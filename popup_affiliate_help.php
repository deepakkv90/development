<?php
/*
  $Id: popup_affiliate_help.php,v 1.1.1.1 2004/03/04 23:38:01 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (in_array('remove_current_page',get_class_methods($navigation)) ) $navigation->remove_current_page();

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_POPUP_AFFILIATE_HELP);
      if (isset($_GET['help_text'])) {
              $help_text = $_GET['help_text'] ;
            }else if (isset($_POST['help_text'])){
              $help_text = $_POST['help_text'] ;
            } else {
             $help_text = '' ;
        }

 if (tep_not_null($help_text)) {
    switch ($help_text) {

  case '1':
    $text_out_help = TEXT_IMPRESSIONS_HELP;
  break;
   case '2':
    $text_out_help = TEXT_VISITS_HELP;
  break;
    case '3':
    $text_out_help = TEXT_TRANSACTIONS_HELP;
  break;
    case '4':
    $text_out_help = TEXT_CONVERSION_HELP;
  break;
    case '5':
    $text_out_help = TEXT_AMOUNT_HELP;
  break;
    case '6':
    $text_out_help = TEXT_AVERAGE_HELP;
  break;
    case '7':
    $text_out_help = TEXT_COMMISSION_RATE_HELP;
  break;
   case '8':
    $text_out_help = TEXT_CLICKTHROUGH_RATE_HELP;
    break;
  case '9':
    $text_out_help = TEXT_PAY_PER_SALE_RATE_HELP;
  break;
  case '10':
    $text_out_help = TEXT_COMMISSION_HELP;
  break;
  case '12':
    $text_out_help = TEXT_DATE_HELP;
  break;
  case '13':
    $text_out_help = TEXT_CLICKED_PRODUCT_HELP;
  break;
    case '14':
      $text_out_help = TEXT_REFFERED_HELP;
  break;
    case '15':
      $text_out_help = TEXT_DATE_HELP_SALE;
  break;
      case '16':
        $text_out_help = TEXT_TIME_HELP_SALE;
    break;
    case '17':
      $text_out_help = TEXT_SALE_VALUE_HELP_SALE;
  break;
    case '18':
      $text_out_help = TEXT_COMMISSION_RATE_HELP_SALE;
  break;
    case '19':
      $text_out_help = TEXT_COMMISSION_VALUE_HELP;
  break;
    case '20':
      $text_out_help = TEXT_STATUS_HELP;
  break;
    case '21':
      $text_out_help = TEXT_PAYMENT_ID_HELP;
  break;
    case '22':
      $text_out_help = TEXT_PAYMENT_HELP_1;
  break;
    case '23':
      $text_out_help = TEXT_PAYMENT_STATUS_HELP;
  break;
    case '24':
      $text_out_help = TEXT_PAYMENT_DATE_HELP;
  break;

   }
 }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo STORE_NAME; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_STYLE;?>">
</head>
<body class="popupBody">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text'  => HEADING_SUMMARY_HELP);
  new popupBoxHeading($info_box_contents, false, false);
  
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left','text'  => $text_out_help );
  $info_box_contents[] = array('text' => '<a href="javascript:window.close()"><span class="popupClose">' . TEXT_CLOSE_WINDOW . '</span></a>');
  new popupBox($info_box_contents);
  
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
  new popupBoxFooter($info_box_contents, false, false);
?>
</body>
</html>
<?php require('includes/application_bottom.php'); ?>