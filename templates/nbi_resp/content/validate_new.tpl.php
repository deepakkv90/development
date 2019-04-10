<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('validatenew', 'top');
// RCI code eof
$verifycodesent="";
if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) 
{
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);    
  $check_customer_query = tep_db_query("select customers_firstname, customers_lastname, customers_password, customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
    if (tep_db_num_rows($check_customer_query)) 
  {
        $check_customer = tep_db_fetch_array($check_customer_query);
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

    tep_db_query('update customers set customers_validation_code = "' . $Pass . $Pass_neu . '" where customers_id = "' .  (int)$check_customer['customers_id'] . '"');
      tep_mail($check_customer['customers_firstname'] . ' ' . $check_customer['customers_lastname'], $email_address, EMAIL_PASSWORD_REMINDER_SUBJECT, sprintf(EMAIL_PASSWORD_REMINDER_BODY, $Pass . $Pass_neu) . sprintf(EMAIL_PASSWORD_REMINDER_BODY2, '<a href="' . tep_href_link('pw.php', 'action=reg&amp;pass=' . $Pass . $Pass_neu . '&amp;id=' . (int)$check_customer['customers_id'], 'SSL', false) . '">' . tep_href_link('pw.php', 'action=reg&amp;pass=' . $Pass . $Pass_neu . '&amp;verifyid=' . (int)$check_customer['customers_id'], 'SSL', false) . '</a>'), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);  
      $verifycodesent="success";
  } 
  else 
  {
       $messageStack->add('password_forgotten', TEXT_NO_EMAIL_ADDRESS_FOUND);
  }
}

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_VALIDATE_NEW, '', 'SSL')); 
 
?>

<?php if($verifycodesent != 'success'){?>
<!-- body_text //-->
  <?php echo tep_draw_form('password_forgotten', tep_href_link(FILENAME_VALIDATE_NEW, 'action=process', 'SSL')); ?>
  <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <tr>
  <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_login.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
  </tr>

  
  <?php
  if ($messageStack->size('password_forgotten') > 0) {
  ?>
      <tr>
        <td><?php echo $messageStack->output('password_forgotten'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
    
  <?php
    }
  ?>

  
  <tr>
        <td><table border="0" width="100%" height="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
              <tr>
                <td class="main" colspan="2"><?php echo TEXT_MAIN; ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo '<b>' . ENTRY_EMAIL_ADDRESS . '</b> ' . tep_draw_input_field('email_address'); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <?php
      // RCI code start
      echo $cre_RCI->get('validatenew', 'menu');
      // RCI code eof
      ?>
   <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo '<a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
 
  </table>
  </form>

<?php }else{?>

  <table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
  <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_login.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
      <?php
      // RCI code start
      echo $cre_RCI->get('validatenew', 'menu');
      // RCI code eof
      ?>
   <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class='main'><?php echo SUCCESS_REGISTRATION_CODE_SENT.'<br><br><a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>               
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
  </table>
<?php }
// RCI code start
echo $cre_RCI->get('validatenew', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?><!-- body_text_eof //-->