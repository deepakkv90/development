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
    $text_out_help = TEXT_TOTAL_AFFILIATE_COMMISSION;
    break;
  case '9':
    $text_out_help = TEXT_CLICKTHROUGH_RATE_HELP;
  break;
  case '10':
    $text_out_help = TEXT_PAY_PER_SALE_RATE_HELP;
  break;

   case '11':
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
      $text_out_help = TEXT_DATE_HELP;
  break;
      case '16':
        $text_out_help = TEXT_TIME_HELP;
    break;
    case '17':
      $text_out_help = TEXT_SALE_VALUE_HELP;
  break;
    case '18':
      $text_out_help = TEXT_COMMISSION_RATE_HELP;
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<style type="text/css"><!--
a { color:#080381; text-decoration:none; }
a:hover { color:#aabbdd; text-decoration:underline; }
a.text:link, a.text:visited { color: #000000; text-decoration: none; }
a:text:hover { color: #000000; text-decoration: underline; }



.smallText { font-family: Verdana, Arial, sans-serif; font-size: 10px; }
/* info box */
.DATAHeading { font-family: Verdana, Arial, sans-serif; font-size: 11px; color: #ffffff; background-color: #B3BAC5; }
.DATAContent { font-family: Verdana; font-size: 10pt; border: 1px outset #9B9B9B;
               padding-left: 4; padding-right: 4; padding-top: 1;
               padding-bottom: 1; background-color: #FFFFFF }
//--></style>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10" bgcolor="#DEE4E8">

<?php
 $box = new box;
  echo $box->infoBox($heading, $contents);
  $heading = array();
      $heading[] = array('text' => '<b>' . HEADING_SUMMARY_HELP . '</b>');
  $contents = array();
      $contents[] = array('text'  => $text_out_help);

 $box = new box;
  echo $box->infoBox($heading, $contents);


?>
<p class="smallText" align="right"><?php echo '<a href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?></p>
</body>
</html>