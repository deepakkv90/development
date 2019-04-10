<?php
/*
  $Id: validate_New.php,v 1.1.1.1 2004/03/04 23:38:20 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

 THIS IS BETA - Use at your own risk!
  Step-By-Step Manual Order Entry Verion 0.5
  Customer Entry through Admin
*/

  require('includes/application_top.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->


<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
  <!-- left_navigation //-->
  <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
  <!-- left_navigation_eof //-->
<!-- body_text //-->
    <td width="100%" valign="top">
  <form name="sendmail" method="post" <?php echo 'action="' . tep_href_link(FILENAME_VALIDATE_NEW, '') . '"'; ?> >
  <input type="hidden" name="process" value="sendmail">
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>

    <?php    
    if($_GET['process']=='sendmail')
    {
    $customerid=$_GET['cID'];
    $check_customer_query = tep_db_query("select customers_firstname, customers_lastname, customers_password, customers_id,customers_email_address  from " . TABLE_CUSTOMERS . " where customers_id = " . tep_db_input($customerid));
    if (tep_db_num_rows($check_customer_query)) 
    {
      $check_customer = tep_db_fetch_array($check_customer_query);
      $email_address=$check_customer['customers_email_address'];
      $pw="ABCDEFGHJKMNOPQRSTUVWXYZabcdefghjkmnopqrstuvwxyz0123456789";
      srand((double)microtime()*1000000);
      for ($i=1;$i<=5;$i++)
      { 
      $Pass .= $pw{rand(0,strlen($pw)-1)};
      }
      $pw1="ABCDEFGHJKMNOPQRSTUVWXYZabcdefghjkmnopqrstuvwxyz0123456789";
      srand((double)microtime()*1000000);
      for ($i=1;$i<=5;$i++)
      { 
      $Pass_neu .= $pw1{rand(0,strlen($pw1)-1)};
      }     
      tep_db_query('update customers set customers_validation_code = "' . $Pass . $Pass_neu . '" where customers_id = ' .  $customerid);      
      tep_mail($check_customer['customers_firstname'] . ' ' . $check_customer['customers_lastname'], $email_address, EMAIL_PASSWORD_REMINDER_SUBJECT, 'Dear '.$check_customer['customers_firstname'] . ' ' . $check_customer['customers_lastname'].','.sprintf(EMAIL_PASSWORD_REMINDER_BODY, $Pass . $Pass_neu) . sprintf(EMAIL_PASSWORD_REMINDER_BODY2, '<a href="' . tep_href_link('pw.php', 'action=reg&pass=' . $Pass . $Pass_neu . '&id=' . (int)$check_customer['customers_id'], 'SSL', false) . '">' . tep_catalog_href_link('pw.php', 'action=reg&pass=' . $Pass . $Pass_neu . '&verifyid=' . (int)$check_customer['customers_id'], 'NONSSL', false) . '</a>'), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);  
      echo  '<tr><td class="smallText"><br><br>'.SUCCESS_REGISTRATION_CODE_SENT.'</td></tr>';
      echo "<tr><td  class='main'><br><a href='".tep_href_link(FILENAME_CUSTOMERS,"")."'>".tep_image_button('button_back.gif', IMAGE_BUTTON_BACK)."</a></td></tr>";
     }
     }    
     else
     {
    ?>    
    
      <?php 
      $customerid=$_GET['cID'];
      $check_customer_query = tep_db_query("select  customers_validation  from " . TABLE_CUSTOMERS . " where customers_id = " . tep_db_input($customerid));
      $check_customer = tep_db_fetch_array($check_customer_query);
      $checkIfValidated=$check_customer['customers_validation'];
      if($checkIfValidated==0)
      {
      ?>            
         <tr>
            <td class="smallText"><br><br><?php echo  TEXT_EMAIL_CONFIRMATION.tep_draw_hidden_field('cID',$_GET['cID']);?></td>
            </tr>
           <tr>
              <td  class="main">
               <br><a href='<?php echo tep_href_link(FILENAME_VALIDATE_NEW,"process=sendmail&cID=".$_GET['cID'])?>'><?php echo tep_image_button('button_confirm.gif', IMAGE_BUTTON_CONTINUE);?></a></td>
            </tr>
      <?php 
      }
      else
      {
         echo "<tr><td class='smallText'><br><br>".TEXT_ACCOUNT_ALREADY_EXIST."</td>
            </tr><tr><td  class='main'><br><a href='".tep_href_link(FILENAME_CUSTOMERS,"")."'>".tep_image_button('button_back.gif', IMAGE_BUTTON_BACK)."</a></td></tr>";        
      }
      ?> 
    <?php 
    }
    ?> 
    </table></form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
    </table></td>
  </tr>
</table>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php
    require(DIR_WS_INCLUDES . 'footer.php');
?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
