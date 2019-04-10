<div class="login-content form-content">
  <div class="left">
	<h2><?php echo $returning_customer_title."Returning Customer"; ?></h2>
	
	  <div class="content">
		<p>You may log in to your BBi account by entering your email and password into the form below.</p>
		<input type="text" placeholder="<?php echo ENTRY_EMAIL_ADDRESS; ?>" name="email_address" />
		<br/><br/>
		<input type="password" placeholder="<?php echo ENTRY_PASSWORD; ?>" name="password" />
		<br/><br/>
		<input type="submit" class="button" value="Login">
		<br/><br/>
		<?php echo '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>' . "<br/>"; ?>
	  </div>
  </div>
  
  <div class="right">

	  <h2><?php echo HEADING_NEW_CUSTOMER; ?></h2>
	  <div class="content">
		  <p><?php echo TEXT_NEW_CUSTOMER_INTRODUCTION; ?></p>
		  <?php echo '<a class="button" href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">Continue</a>' . "<br/>"; ?>
	  </div>
	  
  </div>
  
</div>