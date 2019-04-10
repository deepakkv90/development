<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('passwordforgotten', 'top');
// RCI code eof

echo tep_draw_form('password_forgotten', tep_href_link(FILENAME_PASSWORD_FORGOTTEN, 'action=process', 'SSL')); ?>

<h2>Reset your password</h2>

<?php
  if ($messageStack->size('password_forgotten') > 0) {
?>
        <div><?php echo $messageStack->output('password_forgotten'); ?></div>
<?php
  }
?>
      <p><?php echo TEXT_MAIN; ?></p>
	  <div class="content form-content">
		<input type="text" placeholder="<?php echo ENTRY_EMAIL_ADDRESS; ?>" name="email_address" />
		<br><br>
		<?php echo '<a class="button" href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">Back</a>'; ?>
		<input type="submit" class="button" value="Reset password">
	  </div>
	  
<?php
// RCI code start
echo $cre_RCI->get('passwordforgotten', 'menu');
// RCI code eof
?>
</form>
<?php 
// RCI code start
echo $cre_RCI->get('passwordforgotten', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>