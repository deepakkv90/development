<?php
/*
  $Id: loginbox.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require(DIR_WS_LANGUAGES . $language . '/'.FILENAME_LOGINBOX);
if ( ( ! strstr($PHP_SELF,'login.php')) && ( ! strstr($PHP_SELF,'create_account.php')) && ! isset($_SESSION['customer_id']) )  {
  if ( !isset($_SESSION['customer_id']) ) {
    ?>
    <!-- loginbox //--> 
      <div class="box">
        <?php
        /*
        $loginboxcontent = "
        <div class='your-account'>		
		<form name=\"login\" class='accnt' method=\"post\" action=\"" . tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL') . "\">
          <label>
                " . BOX_LOGINBOX_EMAIL . "
          </label>            
          <input class='input' type=\"text\" name=\"email_address\" maxlength=\"96\" size=\"20\" value=\"\" />
          <label>
                " . BOX_LOGINBOX_PASSWORD . "
          </label>   
          <input class='input' type=\"password\" name=\"password\" maxlength=\"40\" value=\"\" />         
          <font align='center'><input type='submit' name='login' value='Login' class='button' /></font>         
        </form>
		<p><a href='" . tep_href_link("forgot-password", '', 'SSL') . "'>Forgotten your password ?</a><br /><a href='" . tep_href_link("create-customer-account", '', 'SSL') . "'>Register a new account</a></p>
		</div>";
        
		echo $loginboxcontent;
		*/
		
        ?>
        <div class="box-content box-category"> <ul><li><a class="boxmenu" href="/login.php">Register / Login</a></li></ul></div>
	  
    </div>
    <!-- loginbox eof//-->
    <?php
  } else {
    // If you want to display anything when the user IS logged in, put it
    // in here...  Possibly a "You are logged in as :" box or something.
  }
} else {

  if ( isset($_SESSION['customer_id']) ) {
    $pwa_query = tep_db_query("select purchased_without_account,customers_firstname from " . TABLE_CUSTOMERS . " where customers_id = '" . $_SESSION['customer_id'] . "'");
    $pwa = tep_db_fetch_array($pwa_query);
    if ($pwa['purchased_without_account'] == '0') {
      ?>
        <!-- loginbox //-->
        <div class="box">
			<div class="box-content left-links"> <ul>
          <?php
		  echo '<li><a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">'.LOGIN_BOX_MY_ACCOUNT.'</a></li>
				<li><a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">'.LOGIN_BOX_ACCOUNT_EDIT.'</a></li>
				<li><a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">'.LOGIN_BOX_ACCOUNT_HISTORY.'</a></li>
				<li><a href="' . tep_href_link(FILENAME_ACCOUNT_MYFILES, '', 'SSL') . '">'.LOGIN_BOX_MY_FILES.'</a></li>
				<li><a href="' . tep_href_link(FILENAME_ACCOUNT_ARTWORKS, '', 'SSL') . '">'.LOGIN_BOX_ARTWORKS.'</a></li>
				<li class="end"><a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">'.LOGIN_BOX_ADDRESS_BOOK.'</a></li>
				';
          ?>
		  </ul>
		  </div>
        </div>
      <!-- loginbox eof//-->
      <?php
    }
  }
}
?>