<?php
/*
White Label configuration
You can change values below and insert your company/brand information

How to configure White Label options
http://docs.google.com/View?docID=dx28fsz_23d9bpgvd6
*/

define("M1_SEOURLS_COMPANY_NAME", "MagneticOne"); //Company name
define("M1_SEOURLS_COMPANY_URL", "http://magneticone.com"); //Company website, used for links on company website, link will be disabled if empty
define("M1_SEOURLS_MAINMENU_PREFIX", "M1 "); //Prefix for module links in main menu
define("M1_SEOURLS_LICENSE_MESSAGE_URL", "http://support.magneticone.com/faq.php"); //URL for license error messages, will be disabled if empty
define("M1_SEOURLS_VERSION_URL", "http://support.magneticone.com/checkversion.php"); //URL for version checking links, will be disabled if empty

define("M1_SEOURLS_DOCUMENTATION_URL", "http://docs.magneticone.com/");//URL for module documentation, will be disabled if empty
define("M1_SEOURLS_FAQ_URL", "http://support.magneticone.com/faq.php"); //URL for FAQ messages, will be disabled if empty

define("M1_SEOURLS_FOOTER", '
 <center>
  <span class="smallText">
   <br />
   Find other modules at <a href="http://MagneticOne.com/store" target="_blank">MagneticOne.com Store</a><br>
   Need additional feature or improvement? <a href="http://MagneticOne.com/store/request-feature.php" target="_blank">Contact MagneticOne.com</a>
  </span>
 </center>
');

//define("M1_MAKEADEAL_BANNERS_URL", "http://ads.magneticone.com/product_news.php"); //URL for iframe with product news banners, will be disabled if empty

// in wizard header
define("M1_SEOURLS_COMPANY_URL", "http://magneticone.com");
define("M1_SEOURLS_COMPANY_NAME", "MagneticOne");
?>