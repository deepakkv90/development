<?php

global $error, $entry_password_error, $error_message;

?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo TEXT_CHANGE_PASWORD; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_PASSWORD; ?></td>
            <td class="main">
<?php 
            if ($error == true) {
              if ( $entry_password_error == true) {
                echo tep_draw_password_field('customers_password') . '&nbsp;<span class="fieldRequired">' . $error_message . '</span>';
              } else {
                echo tep_draw_hidden_field('customers_password', $_POST['customers_password']);
              }
            } else {
              echo tep_draw_password_field('customers_password');
            }
?>
            </td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
            <td class="main">
<?php 
            if ($error == true) {
              if ( $entry_password_error == true) {
                echo tep_draw_password_field('customers_password_confirm');
              } else {
                echo tep_draw_hidden_field('customers_password_confirm', $_POST['customers_password']);
              }
            } else {
              echo tep_draw_password_field('customers_password_confirm');
            }
?>
            </td>
          </tr>
        </table></td>
      </tr>
