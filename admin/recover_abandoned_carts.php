<?php
/*
  $Id: recover_abandoned_carts.php,v 2.0 2008/05/05 00:36:41 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$is_62 = (INSTALLED_VERSION_MAJOR == 6 && INSTALLED_VERSION_MINOR == 2) ? true : false;
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
$custid = isset($_POST['custid']) ? $_POST['custid'] : array();

if (!defined('FILENAME_CATALOG_LOGIN')) define('FILENAME_CATALOG_LOGIN', 'account.php');
// Delete Entry Begin
if (isset($_GET['action']) && $_GET['action'] == 'delete') { 
   $reset_query_raw = "DELETE from " . TABLE_CUSTOMERS_BASKET . " WHERE customers_id=" . $_GET[customer_id]; 
   tep_db_query($reset_query_raw); 
   $reset_query_raw2 = "DELETE from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " WHERE customers_id=" . $_GET[customer_id]; 
   tep_db_query($reset_query_raw2);
   tep_redirect(tep_href_link(FILENAME_RECOVER_ABANDONED_CARTS, 'delete=1&customer_id='. $_GET['customer_id'] . '&tdate=' . $_GET['tdate'])); 
} 
if (isset($_GET['delete'])) { 
   $messageStack->add(MESSAGE_STACK_CUSTOMER_ID . $_GET['customer_id'] . MESSAGE_STACK_DELETE_SUCCESS, 'success'); 
} 
$tdate = (isset($_POST['tdate'])) ? $_POST['tdate'] : '';
$base_days = (defined('RECOVER_CARTS_BASE_DAYS') && RECOVER_CARTS_BASE_DAYS != '') ? (int)RECOVER_CARTS_BASE_DAYS : 30;
if ($tdate == '') $tdate = $base_days;
$sdate = (isset($_POST['sdate'])) ? $_POST['sdate'] : '';
$skip_days = (defined('RECOVER_CARTS_SKIP_DAYS') && RECOVER_CARTS_SKIP_DAYS != '') ? (int)RECOVER_CARTS_SKIP_DAYS : 5;
if( $sdate == '' ) $sdate = $skip_days;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php
if ($is_62) {
    echo '<script language="javascript" src="includes/menu.js"></script>' . "\n";
} else {
    echo '<script type="text/javascript" src="includes/prototype.js"></script>' . "\n";
    echo '<!--[if IE]>' . "\n";
    echo '<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">' . "\n";
    echo '<![endif]-->' . "\n";
}
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();"> 
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
 <?php
function seadate($day) {
  $rawtime = strtotime("-".$day." days");
  $ndate = date("Ymd", $rawtime);
  return $ndate;
}

function cart_date_short($raw_date) {
  if ( ($raw_date == '00000000') || ($raw_date == '') ) return false;
  $year = substr($raw_date, 0, 4);
  $month = (int)substr($raw_date, 4, 2);
  $day = (int)substr($raw_date, 6, 2);
  if (@date('Y', mktime(0, 0, 0, $month, $day, $year)) == $year) {
    return date(DATE_FORMAT, mktime(0, 0, 0, $month, $day, $year));
  } else {
    return ereg_replace('2037' . '$', $year, date(DATE_FORMAT, mktime(0, 0, 0, $month, $day, 2037)));
  }
}
// this will return a list of customers with sessions. Handles either the mysql or file case
// returns an empty array if the check sessions flag is not true (empty array means same SQL statement can be used)
function _GetCustomerSessions()	{
  $cust_ses_ids = array();
		if (defined('RECOVER_CARTS_CHECK_SESSIONS') &&  RECOVER_CARTS_CHECK_SESSIONS == 'True') {
			 if (defined('STORE_SESSIONS') && STORE_SESSIONS == 'mysql') {
				  // --- DB RECORDS --- 
				  $sesquery = tep_db_query("SELECT value 
                                  from " . TABLE_SESSIONS . " 
                                WHERE 1");
				  while ($ses = tep_db_fetch_array($sesquery)) {
					   if (ereg( "customer_id[^\"]*\"([0-9]*)\"", $ses['value'], $custval)) $cust_ses_ids[] = $custval[1];
				  }
			 } else {	// --- FILES ---
  				if($handle = opendir( tep_session_save_path())) {
					   while (false !== ($file = readdir( $handle ))) {
						    if ($file != "." && $file != "..")	{
							     $file = tep_session_save_path() . '/' . $file;	// create full path to file!
							     if($fp = fopen( $file, 'r' )) {
								      $val = fread( $fp, filesize( $file ) );
								      fclose( $fp ); 
	             if (ereg("customer_id[^\"]*\"([0-9]*)\"", $val, $custval))	$cust_ses_ids[] = $custval[1];
							     }
					     }
					   }
					   closedir( $handle );
				  }
			 }
		}
		return $cust_ses_ids;
}
?>
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <?php // are we doing an e-mail to some customers?
      if (count($custid) > 0 ) {
        ?>
        <tr>
          <td class="pageHeading" align="left" colspan=2 width="50%"><?php echo HEADING_TITLE; ?></td>
          <td class="pageHeading" align="left" colspan=4 width="50%"><?php echo HEADING_EMAIL_SENT; ?></td>
        </tr>
        <tr class="dataTableHeadingRow">
          <td class="dataTableHeadingContent" align="left" colspan="1" width="15%" nowrap><?php echo TABLE_HEADING_CUSTOMER; ?></td>
          <td class="dataTableHeadingContent" align="left" colspan="1" width="30%" nowrap>&nbsp;</td>
          <td class="dataTableHeadingContent" align="left" colspan="1" width="25%" nowrap>&nbsp;</td>
          <td class="dataTableHeadingContent" align="left" colspan="1" width="10%" nowrap>&nbsp;</td>
          <td class="dataTableHeadingContent" align="left" colspan="1" width="10%" nowrap>&nbsp;</td>
          <td class="dataTableHeadingContent" align="left" colspan="1" width="10%" nowrap>&nbsp;</td>
        </tr>
        <tr>&nbsp;<br></tr>
        <tr class="dataTableHeadingRow">
          <td class="dataTableHeadingContent" align="left"   colspan="1"  width="15%" nowrap><?php echo TABLE_HEADING_MODEL; ?></td>
          <td class="dataTableHeadingContent" align="left"   colspan="2"  width="55%" nowrap><?php echo TABLE_HEADING_DESCRIPTION; ?></td>
          <td class="dataTableHeadingContent" align="center" colspan="1"  width="10%" nowrap> <?php echo TABLE_HEADING_QUANTY; ?></td>
          <td class="dataTableHeadingContent" align="right"  colspan="1"  width="10%" nowrap><?php echo TABLE_HEADING_PRICE; ?></td>
          <td class="dataTableHeadingContent" align="right"  colspan="1"  width="10%" nowrap><?php echo TABLE_HEADING_TOTAL; ?></td>
        </tr>
        <?php
	       foreach ($custid as $cid) {
	         unset($email);
	
	         $query1 = tep_db_query("SELECT cb.products_id pid,
                                         cb.customers_basket_quantity qty,
                                         cb.customers_basket_date_added bdate,
                                         cus.customers_firstname fname,
                                         cus.customers_lastname lname,
                                         cus.customers_email_address email
                                    from " . TABLE_CUSTOMERS_BASKET . " cb,
                                         " . TABLE_CUSTOMERS . " cus
                                  WHERE cb.customers_id = cus.customers_id  
                                    and cus.customers_id = '".$cid."' 
                                  ORDER BY cb.customers_basket_date_added desc ");
	         $knt = tep_db_num_rows($query1);
	         for ($i = 0; $i < $knt; $i++) {
		          $inrec = tep_db_fetch_array($query1);
            // set new cline and curcus
		          if ($lastcid != $cid) {
			           if ($lastcid != "") {
			             $cline .= "
			             <tr>
				              <td class='dataTableContent' align='right' colspan='6' nowrap><b>" . TABLE_CART_TOTAL . "</b>" . $currencies->format($tprice) . "</td>
			             </tr>
			             <tr>
				              <td colspan='6' align='right'><a href=" . tep_href_link(FILENAME_RECOVER_ABANDONED_CARTS, "action=delete&customer_id=" . $cid . "&tdate=" . $tdate . "&sdate=" . $sdate) . ">" . tep_image_button('button_delete.gif', IMAGE_DELETE) . "</a></td>
			             </tr>\n";
			             echo $cline;
			           }
			           $cline = "<tr> <td class='dataTableContent' align='left' colspan='6' nowrap><a href='" . tep_href_link(FILENAME_CUSTOMERS, 'search=' . $inrec['lname'], 'NONSSL') . "'>" . $inrec['fname'] . " " . $inrec['lname'] . "</a>".$customer."</td></tr>";
			           $tprice = 0;
		          }
		          $lastcid = $cid;
            // get the shopping cart
		          $query2 = tep_db_query("SELECT p.products_price price,	p.products_tax_class_id taxclass,	p.products_model model, pd.products_name name 
                                      from " . TABLE_PRODUCTS . " p,
                                           " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                           " . TABLE_LANGUAGES . " l
                                    WHERE p.products_id = '" . $inrec['pid'] . "' 
                                      and pd.products_id = p.products_id 
                                      and pd.language_id = " . (int)$languages_id );
                  $inrec2 = tep_db_fetch_array($query2);
                  $pf->loadProduct($inrec['pid'], $languages_id);
                  $sprice = $pf->getPriceStringShort();
		          if( $sprice < 1) $sprice = $inrec2['price'];
		          // some users may want to include taxes in the pricing, allow that. NOTE HOWEVER that we don't have a good way to get individual tax rates based on customer location yet!
			         if (defined('RECOVER_CARTS_INCLUDE_TAX_IN_PRICES') && RECOVER_CARTS_INCLUDE_TAX_IN_PRICES  == 'True') {
              $sprice += ($sprice * tep_get_tax_rate( $inrec2['taxclass'] ) / 100);
			         } elseif (RECOVER_CARTS_USE_FIXED_TAX_IN_PRICES  == 'True' && RECOVER_CARTS_FIXED_TAX_RATE > 0 ) {
				          $sprice += ($sprice * RECOVER_CARTS_FIXED_TAX_RATE / 100);
            }
            $tprice = $tprice + ($inrec['qty'] * $sprice);
            $pprice_formated  = $currencies->format($sprice);
            $tpprice_formated = $currencies->format(($inrec['qty'] * $sprice));
            $cline .= "<tr class='dataTableRow'>
                         <td class='dataTableContent' align='left'   width='15%' nowrap>" . $inrec2['model'] . "</td>
                         <td class='dataTableContent' align='left'  colspan='2' width='55%'><a href='" . tep_href_link(FILENAME_CATEGORIES, 'action=new_product_preview&read=only&pID=' . $inrec['pid'] . '&origin=' . FILENAME_RECOVER_ABANDONED_CARTS . '?page=' . $_GET['page'], 'NONSSL') . "'>" . $inrec2['name'] . "</a></td>
                         <td class='dataTableContent' align='center' width='10%' nowrap>" . $inrec['qty'] . "</td>
                         <td class='dataTableContent' align='right'  width='10%' nowrap>" . $pprice_formated . "</td>
                         <td class='dataTableContent' align='right'  width='10%' nowrap>" . $tpprice_formated . "</td>
                       </tr>";
          		$mline .= $inrec['qty'] . ' x ' . $inrec2['name'] . "\n";
	           if (defined('EMAIL_USE_HTML') && EMAIL_USE_HTML == 'True') {
			           $mline .= '   <blockquote><a href="' . tep_catalog_href_link(FILENAME_CATALOG_PRODUCT_INFO, 'products_id='. $inrec['pid']) . '">' . tep_catalog_href_link(FILENAME_CATALOG_PRODUCT_INFO, 'products_id='. $inrec['pid']) . "</a></blockquote>\n\n"; 
            } else {
			           $mline .= '   (' . tep_catalog_href_link(FILENAME_CATALOG_PRODUCT_INFO, 'products_id='. $inrec['pid']).")\n\n";
            }
          }
          $cline .= "</td></tr>";
          // E-mail Processing
		        $cquery = tep_db_query("SELECT * from orders WHERE customers_id = '".$cid."'" );   
		        $email = EMAIL_TEXT_LOGIN;
          if (defined('EMAIL_USE_HTML') && EMAIL_USE_HTML == 'True') {
			         $email .= '  <a HREF="' . tep_catalog_href_link(FILENAME_CATALOG_LOGIN, '', 'SSL') . '">' . tep_catalog_href_link(FILENAME_CATALOG_LOGIN, '', 'SSL')  . '</a>';
          } else {
			         $email .= '  (' . tep_catalog_href_link(FILENAME_CATALOG_LOGIN, '', 'SSL') . ')';
          }
          $email .= "\n" . EMAIL_SEPARATOR . "\n\n";
          if (defined('RECOVER_CARTS_EMAIL_FRIENDLY') && RECOVER_CARTS_EMAIL_FRIENDLY == 'True') {
		          $email .= EMAIL_TEXT_SALUTATION . $inrec['fname'] . ",";
	         } else {
		          $email .= STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n";
          }
          if (tep_db_num_rows($cquery) < 1) {
		          $email .= sprintf(EMAIL_TEXT_NEWCUST_INTRO, $mline);
	         } else {
		          $email .= sprintf(EMAIL_TEXT_CURCUST_INTRO, $mline);
          }
          $email .= EMAIL_TEXT_BODY_HEADER . $mline . EMAIL_TEXT_BODY_FOOTER;
	         if (defined('EMAIL_USE_HTML') &&  EMAIL_USE_HTML == 'True') {
			         $email .= '<a HREF="' . tep_catalog_href_link('', '') . '">' . STORE_OWNER . "\n" . tep_catalog_href_link('', '')  . '</a>';
          } else {
			         $email .= STORE_OWNER . "\n" . tep_catalog_href_link('', '');
          }
          $email .= "\n\n". $_POST['message'];
		        $custname = $inrec['fname']." ".$inrec['lname'];
          $outEmailAddr = '"' . $custname . '" <' . $inrec['email'] . '>';
		        if (defined('RECOVER_CARTS_EMAIL_COPIES_TO') && RECOVER_CARTS_EMAIL_COPIES_TO != '') $outEmailAddr .= ', ' . RECOVER_CARTS_EMAIL_COPIES_TO;
          tep_mail('', $outEmailAddr, EMAIL_TEXT_SUBJECT, $email, '', STORE_OWNER . EMAIL_FROM);
          $mline = "";
          // see if a record for this customer already exists; if not create one and if so update it
		        $donequery = tep_db_query("SELECT * from ". TABLE_RECOVER_CARTS ." WHERE customers_id = '".$cid."'");
		        if (tep_db_num_rows($donequery) == 0) {
			         tep_db_query("INSERT INTO " . TABLE_RECOVER_CARTS . " (customers_id, dateadded, datemodified ) VALUES ('" . $cid . "', '" . seadate('0') . "', '" . seadate('0') . "')");
		        } else {
			         tep_db_query("UPDATE " . TABLE_RECOVER_CARTS . " SET datemodified = '" . seadate('0') . "' WHERE customers_id = " . $cid );
          }
          echo $cline;
		        $cline = "";
	       }
	       echo "<tr><td colspan=8 align='right' class='dataTableContent'><b>" . TABLE_CART_TOTAL . "</b>" . $currencies->format($tprice) . "</td> </tr>";
	       echo "<tr><td colspan=6 align='right'><a href=" . tep_href_link(FILENAME_RECOVER_ABANDONED_CARTS, "action=delete&customer_id=" . $cid . "&tdate=" . $tdate . "&sdate=" . $sdate) . ">" . tep_image_button('button_delete.gif', IMAGE_DELETE) . "</a></td>  </tr>\n";
	       echo "<tr><td colspan=6 align=center><a href=" . tep_href_link(FILENAME_RECOVER_ABANDONED_CARTS, tep_get_all_get_params(array())) . ">" . TEXT_RETURN . "</a></td></tr>";
      } else {	 //we are NOT doing an e-mail to some customers
        ?>
        <!-- REPORT TABLE BEGIN //-->
        <tr>
          <td class="pageHeading" align="left" width="50%" colspan="4"><?php echo HEADING_TITLE; ?></td>
          <td class="pageHeading" align="right" width="50%" colspan="4">
            <form method="post" action="<?php echo tep_href_link(FILENAME_RECOVER_ABANDONED_CARTS, tep_get_all_get_params(array())); ?>">
              <table align="right" width="100%">
                <tr class="dataTableContent" align="right">
                  <td><?php echo DAYS_FIELD_PREFIX; ?><input type=text size=4 width=4 value=<?php echo $sdate; ?> name=sdate> - <input type=text size=4 width=4 value=<?php echo $tdate; ?> name=tdate><?php echo DAYS_FIELD_POSTFIX; ?><input type=submit value="<?php echo DAYS_FIELD_BUTTON; ?>"></td>
                </tr>
              </table>
            </form>
          </td>
        </tr>
        <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td></tr>
        <tr>
          <td align="left" colspan="5">
            <table border="0" cellpadding="0" cellspacing="0" width="440">
              <?php
              $curcust = (defined('RECOVER_CARTS_CURCUST_COLOR')) ? RECOVER_CARTS_CURCUST_COLOR : '#0000FF';
              $contacted = (defined('RECOVER_CARTS_CONTACTED_COLOR')) ? RECOVER_CARTS_CONTACTED_COLOR : '#FF9F9F'; 
              $uncontacted = (defined('RECOVER_CARTS_UNCONTACTED_COLOR')) ? RECOVER_CARTS_UNCONTACTED_COLOR : '#9FFF9F'; 
              $matched = (defined('RECOVER_CARTS_MATCHED_ORDER_COLOR')) ? RECOVER_CARTS_MATCHED_ORDER_COLOR : '#9FFFFF'; 
              ?> 
              <tr>
                <td bgcolor="<?php echo $curcust; ?>" width="10"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
                <td class="dataTableContent">&nbsp;<?php echo TEXT_CUR_CUSTOMER; ?>&nbsp;</td>
                <td bgcolor="<?php echo $contacted; ?>" width="10"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
                <td class="dataTableContent">&nbsp;<?php echo TEXT_CONTACTED; ?>&nbsp;</td>
                <td bgcolor="<?php echo $uncontacted; ?>" width="10"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
                <td class="dataTableContent">&nbsp;<?php echo TEXT_UNCONTACTED; ?>&nbsp;</td>
                <td bgcolor="<?php echo $matched; ?>" width="10"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
                <td class="dataTableContent">&nbsp;<?php echo TEXT_MATCHED; ?></td>
              </tr>                                          
            </table>
          </td>
        </tr>
        <form method="post" action="<?php echo tep_href_link(FILENAME_RECOVER_ABANDONED_CARTS, tep_get_all_get_params(array())); ?>">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" align="left" colspan="2" width="10%" nowrap><?php echo TABLE_HEADING_CONTACT; ?></td>
            <td class="dataTableHeadingContent" align="left" colspan="1" width="15%" nowrap><?php echo TABLE_HEADING_DATE; ?></td>
            <td class="dataTableHeadingContent" align="left" colspan="1" width="30%" nowrap><?php echo TABLE_HEADING_CUSTOMER; ?></td>
            <td class="dataTableHeadingContent" align="left" colspan="2" width="30%" nowrap><?php echo TABLE_HEADING_EMAIL; ?></td>
            <td class="dataTableHeadingContent" align="left" colspan="2" width="15%" nowrap><?php echo TABLE_HEADING_PHONE; ?></td>
          </tr><tr>&nbsp;<br></tr>
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" align="left"   colspan="2"  width="10%" nowrap>&nbsp; </td>
            <td class="dataTableHeadingContent" align="left"   colspan="1"  width="15%" nowrap><?php echo TABLE_HEADING_MODEL; ?></td>
            <td class="dataTableHeadingContent" align="left"   colspan="2" width="55%" nowrap><?php echo TABLE_HEADING_DESCRIPTION; ?></td>
            <td class="dataTableHeadingContent" align="center" colspan="1" width="5%" nowrap> <?php echo TABLE_HEADING_QUANTY; ?></td>
            <td class="dataTableHeadingContent" align="right"  colspan="1"  width="5%" nowrap><?php echo TABLE_HEADING_PRICE; ?></td>
            <td class="dataTableHeadingContent" align="right"  colspan="1" width="10%" nowrap><?php echo TABLE_HEADING_TOTAL; ?></td>
          </tr>
          <?php
          $cust_ses_ids = _GetCustomerSessions();
          $bdate = seadate($sdate);
          $ndate = seadate($tdate);
          if (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) {
            $query1 = tep_db_query("SELECT cb.customers_id cid,
                                           cb.products_id pid,
                                           cb.customers_basket_quantity qty,
                                           cb.customers_basket_date_added bdate,
                                           cus.customers_firstname fname,
                                           cus.customers_lastname lname,
                                           cus.customers_telephone phone,
                                           cus.customers_email_address email
                                      from " . TABLE_CUSTOMERS_BASKET . " cb,
                                           " . TABLE_CUSTOMERS . " cus
                                    WHERE cb.customers_basket_date_added <= '" . $bdate . "' 
                                      and cb.customers_basket_date_added > '" . $ndate . "' 
                                      and cus.customers_id not in ('" . implode(", ", $cust_ses_ids) . "') 
                                      and cb.customers_id = cus.customers_id 
                                    ORDER BY cb.customers_basket_date_added desc, cb.customers_id ");
          } else {
            $query1 = tep_db_query("SELECT cb.customers_id cid,
                                           cb.products_id pid,
                                           cb.customers_basket_quantity qty,
                                           cb.customers_basket_date_added bdate,
                                           cus.customers_firstname fname,
                                           cus.customers_lastname lname,
                                           ab.entry_telephone phone,
                                           cus.customers_email_address email
                                      from " . TABLE_CUSTOMERS_BASKET . " cb,
                                           " . TABLE_CUSTOMERS . " cus
                                    LEFT JOIN " . TABLE_ADDRESS_BOOK . " ab 
                                        on (cus.customers_id = ab.customers_id)  
                                    WHERE cb.customers_basket_date_added <= '" . $bdate . "' 
                                      and cb.customers_basket_date_added > '" . $ndate . "' 
                                      and cus.customers_id not in ('" . implode(", ", $cust_ses_ids) . "') 
                                      and cb.customers_id = cus.customers_id 
                                    ORDER BY cb.customers_basket_date_added desc, cb.customers_id ");            
          }                                    
          $results = 0;
          $curcus = "";
          $tprice = 0;
          $totalAll = 0;
          $first_line = true;
          $skip = false;
          $knt = tep_db_num_rows($query1);
          for ($i = 0; $i <= $knt; $i++) {
            $inrec = tep_db_fetch_array($query1);
            // if this is a new customer, create the appropriate HTML
            if ($curcus != $inrec['cid']) {
              // output line
              $totalAll += $tprice;
              $cline .= "<tr>
                           <td class='dataTableContent' align='right' colspan='8'><b>" . TABLE_CART_TOTAL . "</b>" . $currencies->format($tprice) . "</td>    
                         </tr>
                         <tr>
                           <td colspan='6' align='right'><a href=" . tep_href_link(FILENAME_RECOVER_ABANDONED_CARTS,"action=delete&customer_id=" . $curcus . "&tdate=" . $tdate . "&sdate=" . $sdate) . ">" . tep_image_button('button_delete.gif', IMAGE_DELETE) . "</a></td>
                         </tr>\n";
              if ($curcus != "" && !$skip) echo $cline;
              // set new cline and curcus
              $curcus = $inrec['cid'];
              if ($curcus != "") {
	               $tprice = 0;
                // change the color on those we have contacted add customer tag to customers
	               $fcolor = (defined('RECOVER_CARTS_UNCONTACTED_COLOR')) ? RECOVER_CARTS_UNCONTACTED_COLOR : '#9FFF9F';
                $checked = 1;	// assume we'll send an email
                $new = 1;
                $skip = false;
	               $sentdate = "";
	               $beforeDate = (defined('RECOVER_CARTS_CARTS_MATCH_ALL_DATES') && RECOVER_CARTS_CARTS_MATCH_ALL_DATES == 'True') ? '0' : $inrect['bdate'];
                   if ($beforeDate == '') $beforeDate = '0000-00-00 00:00:00';                   
	               $customer = $inrec['fname'] . " " . $inrec['lname'];
	               $status = "";
                $donequery = tep_db_query("SELECT * from ". TABLE_RECOVER_CARTS ." WHERE customers_id = '".$curcus."'");
	               $emailttl = (defined('RECOVER_CARTS_EMAIL_TTL')) ? seadate(RECOVER_CARTS_EMAIL_TTL) : 90;
                if (tep_db_num_rows($donequery) > 0) {
		                $ttl = tep_db_fetch_array($donequery);
		                if( $ttl ) {
			                 if( tep_not_null($ttl['datemodified'])) {	// allow for older scarts that have no datemodified field data
				                  $ttldate = $ttl['datemodified'];
			                 } else {
				                  $ttldate = $ttl['dateadded'];
                    }
			                 if ($emailttl <= $ttldate) {
				                  $sentdate = $ttldate;
				                  $fcolor = (defined('RECOVER_CARTS_CONTACTED_COLOR')) ? RECOVER_CARTS_CONTACTED_COLOR : '#FF9F9F';
				                  $checked = 0;
				                  $new = 0;
			                 }
		                }
	               }
                // See if the customer has purchased from us before
	               // Customers are identified by either their customer ID or name or email address
	               // If the customer has an order with items that match the current order, assume order completed, bail on this entry!   
	               $ccquery = tep_db_query("SELECT orders_id, orders_status 
                                              from " . TABLE_ORDERS . " 
                                            WHERE (customers_id = '" . (int)$curcus . "'  
                                           or customers_email_address like '" . $inrec['email'] . "'  
                                           or customers_name like '" . $inrec['fname'] . " " . $inrec['lname'] . "') 
                                           and date_purchased >= '" . $beforeDate . "'");                                                                                      
	               if (tep_db_num_rows($ccquery) > 0) {
		                // We have a matching order; assume current customer but not for this order
		                $customer = '<font color=' . RECOVER_CARTS_CURCUST_COLOR . '><b>' . $customer . '</b></font>';
	                 // Now, look to see if one of the orders matches this current order's items
		                while($orec = tep_db_fetch_array( $ccquery )) {
			                 $ccquery = tep_db_query( 'select products_id from ' . TABLE_ORDERS_PRODUCTS . ' where orders_id = ' . (int)$orec['orders_id'] . ' AND products_id = ' . (int)$inrec['pid'] );
			                 if (tep_db_num_rows( $ccquery ) > 0 ) {
				                if ($orec['orders_status'] > RECOVER_CARTS_PENDING_SALE_STATUS) $checked = 0;
	                   // OK, we have a matching order; see if we should just skip this or show the status
				                if (RECOVER_CARTS_SKIP_MATCHED_CARTS == 'True' && !$checked) {
					                 $skip = true;	// reset flag & break us out of the while loop!
					                 break;
				                }	else {
					                 // It's rare for the same customer to order the same item twice, so we probably have a matching order, show it
					                 $fcolor = RECOVER_CARTS_MATCHED_ORDER_COLOR;
					                 $ccquery = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = " . (int)$languages_id . " AND orders_status_id = " . (int)$orec['orders_status'] );
	                     if($srec = tep_db_fetch_array( $ccquery )) {
						                  $status = ' [' . $srec['orders_status_name'] . ']';
					                 } else {
						                  $status = ' ['. TEXT_CURRENT_CUSTOMER . ']';
                      }
                 			}
			               }
		              }
		              if($skip)	continue;	// got a matched cart, skip to next one
	             }
	             $sentInfo = TEXT_NOT_CONTACTED;
              if ($sentdate != '')	$sentInfo = cart_date_short($sentdate);
	             $cline = "<tr bgcolor=" . $fcolor . ">
		                        <td class='dataTableContent' align='center' width='1%'>" . tep_draw_checkbox_field('custid[]', $curcus, RECOVER_CARTS_AUTO_CHECK == 'True' ? $checked : 0) . "</td>
		                        <td class='dataTableContent' align='left' width='9%' nowrap><b>" . $sentInfo . "</b></td>
		                        <td class='dataTableContent' align='left' width='15%' nowrap> " . cart_date_short($inrec['bdate']) . "</td>
		                        <td class='dataTableContent' align='left' width='30%' nowrap><a href='" . tep_href_link(FILENAME_CUSTOMERS, 'search=' . $inrec['lname'], 'NONSSL') . "'>" . $customer . "</a>".$status."</td>
		                        <td class='dataTableContent' align='left' colspan='2' width='30%' nowrap><a href='" . tep_href_link('mail.php', 'selected_box=tools&customer=' . $inrec['email']) . "'>" . $inrec['email'] . "</a></td>
		                        <td class='dataTableContent' align='left' colspan='2' width='15%' nowrap>" . $inrec['phone'] . "</td>
		                      </tr>";
              }
            }
            // We only have something to do for the product if the quantity selected was not zero!
            if ($inrec['qty'] != 0) {
	             // Get the product information (name, price, etc)
	             $query2 = tep_db_query("SELECT p.products_price price,
											                                  p.products_tax_class_id taxclass,
											                                  p.products_model model,
											                                  pd.products_name name
								                                from " . TABLE_PRODUCTS . " p,
											                                  " . TABLE_PRODUCTS_DESCRIPTION . " pd,
											                                  " . TABLE_LANGUAGES . " l
								                              WHERE p.products_id = '" . (int)$inrec['pid'] . "' 
                                        and	pd.products_id = p.products_id 
                                        and	pd.language_id = " . (int)$languages_id );
	             $inrec2 = tep_db_fetch_array($query2);
                 // Check to see if the product is on special, and if so use that pricing
                 $pf->loadProduct($inrec['pid'], $languages_id);
                 $sprice = $pf->getPriceStringShort();              
	             if ($sprice < 1)	$sprice = $inrec2['price'];
	             // Some users may want to include taxes in the pricing, allow that. NOTE HOWEVER that we don't have a good way to get individual tax rates based on customer location yet!
	             if (RECOVER_CARTS_INCLUDE_TAX_IN_PRICES  == 'True') {
		              $sprice += ($sprice * tep_get_tax_rate( $inrec2['taxclass'] ) / 100);
	             } elseif (RECOVER_CARTS_USE_FIXED_TAX_IN_PRICES  == 'True' && RECOVER_CARTS_FIXED_TAX_RATE > 0) {
		              $sprice += ($sprice * RECOVER_CARTS_FIXED_TAX_RATE / 100);
              }
              // BEGIN OF ATTRIBUTE DB CODE
              $prodAttribs = '';
              if (RECOVER_CARTS_SHOW_ATTRIBUTES == 'True') {
                $attribquery = tep_db_query("SELECT DISTINCT 
                                                    pot.products_options_name poname,
                                                    cba.products_id pid,
                                                    pov.products_options_values_name povname
                                               from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " cba,
                                                    " . TABLE_PRODUCTS_OPTIONS_TEXT . " pot,  
                                                    " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov,
                                                    " . TABLE_LANGUAGES . " l
                                             WHERE cba.products_id ='" . $inrec['pid'] . "' 
                                               and cba.customers_id = " . $curcus . " 
                                               and pot.products_options_text_id = cba.products_options_id 
                                               and pov.products_options_values_id = cba.products_options_value_id 
                                               and pot.language_id = " . (int)$languages_id . " 
                                               and pov.language_id = " . (int)$languages_id);                                              
                                                                                             
                $hasAttributes = false;
                if (tep_db_num_rows($attribquery)) {
                  $hasAttributes = true;
                  $prodAttribs = '<br>';
                  while ($attribrecs = tep_db_fetch_array($attribquery)) {
                    $prodAttribs .= '<small><i> - ' . $attribrecs['poname'] . ' ' . $attribrecs['povname'] . '</i></small><br>';
                  }
                }
              }
              // END OF ATTRIBUTE DB CODE
			           $tprice = $tprice + ($inrec['qty'] * $sprice);
			           $pprice_formated  = $currencies->format($sprice);
			           $tpprice_formated = $currencies->format($inrec['qty'] * $sprice);
              $cline .= "<tr class='dataTableRow'>
                           <td class='dataTableContent' align='left' vAlign='top' colspan='2' width='12%' nowrap> &nbsp;</td>
                           <td class='dataTableContent' align='left' vAlign='top' width='13%' nowrap>" . $inrec2['model'] . "</td>
                           <td class='dataTableContent' align='left' vAlign='top' colspan='2' width='55%'><a href='" . tep_href_link(FILENAME_CATEGORIES, 'action=new_product_preview&read=only&pID=' . $inrec['pid'] . '&origin=' . FILENAME_RECOVER_ABANDONED_CARTS . '?page=' . $_GET['page'], 'NONSSL') . "'><b>" . $inrec2['name'] . "</b></a>" . $prodAttribs . "</td>
                           <td class='dataTableContent' align='center' vAlign='top' width='5%' nowrap>" . $inrec['qty'] . "</td>
                           <td class='dataTableContent' align='right'  vAlign='top' width='5%' nowrap>" . $pprice_formated . "</td>
                           <td class='dataTableContent' align='right'  vAlign='top' width='10%' nowrap>" . $tpprice_formated . "</td>
                         </tr>";
	           }
          }
          $totalAll_formated = $currencies->format($totalAll);
          $cline = "<tr></tr><td class='dataTableContent' align='right' colspan='8'><hr align=right width=55><b>" . TABLE_GRAND_TOTAL . "</b>" . $totalAll_formated . "</td></tr>";
          echo $cline;
          echo "<tr><td colspan=8><hr size=1 color=000080><b>". PSMSG ."</b><br>". tep_draw_textarea_field('message', 'soft', '80', '5') ."<br>" . tep_draw_selection_field('submit_button', 'submit', TEXT_SEND_EMAIL) . "</td></tr>";   
          ?>
          </form>
          <?php 
        }
        // end footer of both e-mail and report
        ?>
        <!-- REPORT TABLE END //-->
        <tr class="main">
          <td colspan="9" class="main">
            <?php
            echo "<a href='" . tep_href_link(FILENAME_MODULES, 'set=addons&module=recovercarts&action=edit', 'NONSSL') . "'>[" . TEXT_RAC_EDIT . "]</a><br>";
            echo "<a href='" . tep_href_link(FILENAME_STATS_RECOVER_ABANDONED_CARTS, '', 'NONSSL') . "'>[" . TEXT_RAC_RUN_RECOVER_CARTS_REPORT . "]</a>";
            ?>  
            </td>
          </tr>
      </table>
    </td>
    <!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>