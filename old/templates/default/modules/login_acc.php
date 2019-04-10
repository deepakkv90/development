<?php
//BOF: MaxiDVD Returning Customer Info SECTION
//===========================================================
$returning_customer_title = HEADING_RETURNING_CUSTOMER; // DDB - 040620 - PWA - change TEXT by HEADING
if ($setme != '') {
$returning_customer_info = "
<!--Confirm Block-->
<td width=\"50%\" height=\"100%\" valign=\"top\"><table border=\"0\" width=\"100%\" height=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"infoBox\">
<tr class=\"infoBoxContents\">
<td>
<table border=\"0\" width=\"100%\" height=\"100%\" cellspacing=\"0\" cellpadding=\"2\">
 <tr>
   <td colspan=\"2\">".tep_draw_separator('pixel_trans.gif', '100%', '10')."</td>
 </tr>
 <tr>
   <td class=\"main\" colspan=\"2\">".TEXT_YOU_HAVE_TO_VALIDATE."</td>
 </tr>
 <tr>
   <td colspan=\"2\">".tep_draw_separator('pixel_trans.gif', '100%', '10')."</td>
 </tr>
 <tr>
   <td class=\"main\"><b>". ENTRY_EMAIL_ADDRESS."</b></td>
   <td class=\"main\">". tep_draw_input_field('email_address')."</td>
 </tr>
 <tr>
   <td class=\"main\"><b>". ENTRY_VALIDATION_CODE."</b></td>
   <td class=\"main\">".tep_draw_input_field('pass').tep_draw_input_field('password',$_POST['password'],'','hidden')."</td>
 </tr>
 <tr>
   <td colspan=\"2\">".tep_draw_separator('pixel_trans.gif', '100%', '10')."</td>
 </tr>
 <tr>
   <td class=\"smallText\" colspan=\"2\">". '<a href="' . tep_href_link('validate_new.php', '', 'SSL') . '">' . TEXT_NEW_VALIDATION_CODE . '</a>'."</td>
 </tr>
 <tr>
   <td colspan=\"2\">". tep_draw_separator('pixel_trans.gif', '100%', '10')."</td>
 </tr>
 <tr>
   <td colspan=\"2\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"2\">
     <tr>
       <td width=\"10\">". tep_draw_separator('pixel_trans.gif', '10', '1')."</td>
       <td align=\"right\">".tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE)."</td>
       <td width=\"10\">".tep_draw_separator('pixel_trans.gif', '10', '1')."</td>
     </tr>
</table>
        </table></td>
      </tr>
    </table></form></td>
<!--Confirm Block END-->
";  

} else {
$returning_customer_info = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" width=\"100%\">
  <tr>
    <td width=\"100%\" class=\"main\" colspan=\"3\">" . tep_draw_separator('pixel_trans.gif', '100%', '10') . "</td>
  </tr>
  <tr>
    <td class=\"main\"> 
<table width=\"70%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\">
  <tr>
    <td class=\"main\">" . ENTRY_EMAIL_ADDRESS . "</td>
    <td>" . tep_draw_input_field('email_address') . "</td>
  </tr>
  <tr>
    <td class=\"main\">" . ENTRY_PASSWORD . "<br><br></td>
  <td>" . tep_draw_password_field('password') . "<br><br></td>
  </tr>
</table>
<table width=\"30%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"right\">
  <tr>
  <td align=\"center\" class=\"smalltext\">" . tep_template_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN) . "<br><br>" . '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>' . "<br><br></td>
  </tr>
</table>
</td>
  </tr>
</table>

";
}
//===========================================================

// RCI code start
echo $cre_RCI->get('login', 'aboveloginbox');
// RCI code end

?>
<!-- login_acc -->
    <tr>
     <td width="100%" valign="top">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $returning_customer_title );
  new contentBoxHeading($info_box_contents);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $returning_customer_info);
  new contentBox($info_box_contents, true, true);
  
  if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){
  $info_box_contents = array();
   $info_box_contents[] = array('align' => 'left',
                                      'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                    );
  new contentBoxFooter($info_box_contents);
  }
?>
  </td>
 </tr>
<?php
//EOF: MaxiDVD Returning Customer Info SECTION
//===========================================================
 
// RCI code start
echo $cre_RCI->get('login', 'belowloginbox');
// RCI code end

//MaxiDVD New Account Sign Up SECTION
//===========================================================
$create_account_title = HEADING_NEW_CUSTOMER;
$create_account_info = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\"  width=\"100%\">
  <tr>
    <td width=\"100%\" class=\"main\" colspan=\"3\">" . TEXT_NEW_CUSTOMER_INTRODUCTION . "</td>
  </tr>
  <tr>
    <td width=\"100%\" class=\"main\" colspan=\"3\">" . tep_draw_separator('pixel_trans.gif', '100%', '10') . "</td>
  </tr>
  <tr>
    <td width=\"33%\" class=\"main\"></td>
    <td width=\"33%\"></td>
    <td width=\"34%\" rowspan=\"3\" align=\"center\">" . '<a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button('button_create_account.gif', IMAGE_BUTTON_CREATE_ACCOUNT) . '</a>' . "<br><br></td>
  </tr>
</table>";
//===========================================================
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
    <tr>
     <td width="100%" valign="top">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $create_account_title );
  new contentBoxHeading($info_box_contents);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $create_account_info);
  new contentBox($info_box_contents, true, true);
  
   if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){  
  $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                      'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                    );
  new contentBoxFooter($info_box_contents);
   }
?>
  </td>
  </tr>
<?php
//EOF: MaxiDVD New Account Sign Up SECTION
//===========================================================
?>