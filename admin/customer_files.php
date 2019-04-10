<?php
/*
  $Id: customer_files.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  
  // RCI code start
  echo $cre_RCI->get('global', 'top', false);
  echo $cre_RCI->get('orders', 'top', false); 
  // RCI code eof
   
  include(DIR_WS_CLASSES . 'order.php');
  
  if(isset($_GET['cID'])) {
  	$customer_id = $_GET['cID'];
  } else {
  	tep_redirect(tep_href_link(FILENAME_CUSTOMERS, '', 'SSL'));
  }
  
   if (isset($_POST['action']) && ($_POST['action'] == 'process') ) {
    	
    $custom_file = tep_db_prepare_input($_FILES['custom_file']['name']);
	$comment = tep_db_prepare_input($_POST['comment']);
	
	$fileInfo = pathinfo($custom_file);
	//print_r($fileInfo);
    $error = false;  

    if (empty($custom_file)) {
      $error = true;
      $messageStack->add('frmMyfiles', "Please select the file");
    }
		 
	
	$randamNumber=md5(microtime().rand(0,999999));
	
	$myfiles_dir = 'users_files/';
	
	$path = DIR_FS_CATALOG . DIR_WS_IMAGES . $myfiles_dir . $randamNumber.".".$fileInfo['extension'];
	$ext = strtolower($fileInfo['extension']);
	
	if($ext=="jpeg" || $ext=="jpg" || $ext=="png" || $ext=="gif" || $ext=="txt" || $ext=="xls" || $ext=="csv" || $ext=="xlsx" || $ext=="pdf" || $ext=="doc" || $ext=="ai" || $ext=="cdr" || $ext=="eps" || $ext=="docx" ) {
	
		if(move_uploaded_file($_FILES['custom_file']['tmp_name'],$path)) {
		
			if ($error == false) {
				$sqlInsFiles = "INSERT INTO my_files set file_name = '".$custom_file."', comment='".$comment."', file_path='".$path."', customers_id = '" . $customer_id . "', date_uploaded = now()";					  
			  
				$rst_files = tep_db_query($sqlInsFiles);	   
		
				tep_redirect(tep_href_link(FILENAME_CUSTOMER_FILES, 'cID=' . $customer_id.'&msg=upload'));
			  
			}
		 }
  	 }
  }
  
  //delete customer files
  if(isset($_GET['action']) && $_GET['action']=="deleteconfirm" ) {
	
	$custom_file_id = $_GET['delete'];
	
	$qry_check_id = "SELECT * FROM my_files WHERE files_id='".$custom_file_id."'";
	$qry_rst = tep_db_query($qry_check_id);
	if(tep_db_num_rows($qry_rst)>0) {
		$rst_arr = tep_db_fetch_array($qry_rst);
		$del_file_path = $rst_arr["file_path"];
		tep_db_query("DELETE FROM my_files WHERE files_id='".$custom_file_id."'");
		unlink($del_file_path);
		tep_redirect(tep_href_link(FILENAME_CUSTOMER_FILES, 'cID=' . $customer_id.'&msg=delete'));
		
	}
	
  }
  
  
  
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<style type="text/css">
.main { border-bottom:1px solid #CCC; }
</style>
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
<link type="text/css" rel="StyleSheet" href="includes/helptip.css">
<script type="text/javascript" src="includes/javascript/helptip.js"></script>

<script type="text/javascript">
	function delconfirm() {		
		if(confirm("Are you sure want to delete this file?")) {
			return true;
		}
		return false;
	}
</script>

<?php 
// rci for javascript include
echo $cre_RCI->get('orders', 'javascript');
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="body-table">
    <tr>
    
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top" class="page-container">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
     
      <?php
      	 // RCI start
     	 echo $cre_RCI->get('orders', 'listingtop');
      	 // RCI eof
	  ?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top">			  
			  
			  	<div id="msg" style="color:#0099FF;">
				<?php
				if(isset($_GET['msg']) && $_GET['msg']!="") {
				
					if($_GET['msg']=="upload") {
						echo "File uploaded successfully";
					} else if($_GET['msg']=="delete") {
						echo "File deleted successfully";
					}
				
				}
				?>
				</div>
				<?php
				
				 echo tep_draw_form('customer_file', FILENAME_CUSTOMER_FILES, tep_get_all_get_params(array('cID')) . 'cID=' . $customer_id, 'post', 'enctype="multipart/form-data"', 'SSL'). tep_draw_hidden_field('action', 'process');
				  			  
				  ?>
				
				<table border="0" width="100%">
					<tr>
					  
					  <td width="34%" rowspan="4">
					  
					  <?php
					
					  
					  	 	$customers_query = tep_db_query("select c.customers_id, c.customers_lastname, c.customers_firstname, c.customers_email_address, a.entry_country_id, a.entry_city, a.entry_state, a.entry_telephone, a.entry_postcode, a.entry_company, a.entry_street_address from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " a WHERE c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id and c.customers_id = " . (int)$customer_id);
        					
							$customers_array = tep_db_fetch_array($customers_query);
							
							$country_query = tep_db_query("SELECT countries_name FROM " . TABLE_COUNTRIES . " WHERE countries_id = " . (int)$customers_array['entry_country_id']);
							$country_array = tep_db_fetch_array($country_query);
							
					  ?>
					  		<table width="100%" border="0" cellspacing="0" cellpadding="2">
							  <tr>
								<td width="40%" valign="top"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
								<td width="60%">
									<?php  
										echo $customers_array['entry_company']."<br>" . 
										$customers_array['customers_firstname'] . 
										$customers_array['customers_lastname'] ."<br>" . 
										$customers_array['entry_street_address']."<br>" . 
										$customers_array['entry_city']. ", " .$customers_array['entry_state']."<br>" .
										$customers_array['entry_postcode']."<br>" . $country_array['countries_name'];
									?>								</td>
							  </tr>
							  <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b></td>
								<td><?php echo $customers_array['entry_telephone']; ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
								<td><?php echo '<a href="mailto:' . $customers_array['customers_email_address'] . '"><u>' . $customers_array['customers_email_address'] . '</u></a>'; ?></td>
							  </tr>							 
							</table>					  </td>
					  
					  <td colspan="3">Please use below form to send name list and logo  to ajparkes. <br />
						Remember to write a note about your order or any existing job in progress regarding the document you're uploading.<br />
						Allowed file formats are jpg, gif, png, txt, pdf, xls, xlsx and csv files only. <br />
						<br />
					  You could then re-use these file in the future to re-order.</td>
				  </tr>
					<tr>
					  <td colspan="2">&nbsp;</td>
					  <td>&nbsp;</td>
					</tr>
				  <tr>
					  <td width="12%">
							<strong>Upload Files:				</strong></td>
						<td width="51%">
							<input type="file" name="custom_file" id="custom_file" /></td>
						<td width="3%">&nbsp;</td>
					  </tr>
					<tr>					 
					  <td valign="top"><strong>Comments</strong></td>
					  <td><textarea name="comment" style="width:175px;"></textarea></td>
					  <td>&nbsp;</td>
					  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td><?php echo tep_image_submit('submit.png', "Submit"); ?></td>
					  <td>&nbsp;</td>
				  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>	
					  <td>&nbsp;</td>				 
				  </tr>
				</table>
				</form>
			  
			  
			  
			  <table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
                  <?php
    $oscid = '&' . tep_session_name() . '=' . $_GET[tep_session_name()];
    if (isset($_GET['SoID'])) {
      $oscid .= '&SoID=' . $_GET['SoID'];
    }   
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" width="8%"><?php echo TABLE_HEADING_ORDERID; ?></td>
                    <td class="dataTableHeadingContent" width="20%" align="center"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
                    <td class="dataTableHeadingContent" width="35%" align="center"><?php echo TABLE_HEADING_LOGOS; ?></td>
                    <td class="dataTableHeadingContent" width="10%" align="center"> <?php echo TABLE_HEADING_FILES; ?> </td>					
					<td class="dataTableHeadingContent" width="15%" align="center"> <?php echo TABLE_HEADING_COMMENTS; ?> </td>
					<td class="dataTableHeadingContent" width="10%">&nbsp;</td>      
                  </tr>
                  <?php   
	
    if (isset($_GET['cID'])) {
	
	$root = $_SERVER['DOCUMENT_ROOT'].mb_substr($_SERVER['PHP_SELF'],0,-mb_strlen(strrchr($_SERVER['PHP_SELF'],"/")));	
	$root = str_replace("/admin","",$root);

        $cID = tep_db_prepare_input($_GET['cID']);     	  
		$orders_query_raw = "select * from ((SELECT o.orders_id, o.date_purchased, p.badge_data, o.customers_name, o.customers_company from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p where o.customers_id = '" . (int)$cID . "' && o.orders_id = op.orders_id && op.products_id = p.products_id && p.badge_data!='') union all (select files_id, date_uploaded, file_name, file_path, comment from my_files where customers_id='". (int)$cID ."')) as tmp order by date_purchased desc";	
		
   	//echo $orders_query_raw;
	
		$orders_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
		$orders_query = tep_db_query($orders_query_raw);
		while ($orders = tep_db_fetch_array($orders_query)) {
			  $badge_data = @json_encode(@unserialize($orders["badge_data"]));		  
			  $badge_data = json_decode($badge_data);	
			  
			   $bdimg = ""; $bdfile = ""; $cust = 0; $cmt = "";
				  
				  if(count($badge_data->logos)>0) {								
					
					for($i = 0; $i < count($badge_data->logos); $i++) {
						$logo = $badge_data->logos[$i];
						$bdimg .= '<a href="download.php?file=img&path='.$logo->src.'"><img src="../image_thumb.php?file='.DIR_FS_CATALOG . DIR_WS_IMAGES ."users_badges/".$logo->src.'&sizex=60&sizey=60" style="border:1px solid #ccc;"></a>';
					}
				  } else if ($badge_data=="") {
					
					$cust = 1;
					
					$pathinfo = pathinfo($orders["badge_data"]);
					$path2 = pathinfo($orders['customers_name']);			
					$ext = strtolower($pathinfo['extension']);
					$cmt = $orders['customers_company'];
					
					if($ext=="jpeg" || $ext=="gif" || $ext=="png" || $ext=="jpg") { 
						$bdimg .= '<a href="download.php?file=img&type=custom&path='.$path2['basename'].'"><img src="../image_thumb.php?file='.$orders["customers_name"].'&sizex=60&sizey=60" style="border:1px solid #ccc;"></a>';
					} 
					else {
						$bdimg .= "No Logos Available.";
					}	
									
				  }
				  else {
					$bdimg .= "No Logos Available.";
				  }		
					 
				 // assign text files if any
				 if(count($badge_data->multiName)>0) {
								
					for($i = 0; $i < count($badge_data->multiName); $i++) {
						  $name = $badge_data->multiName[$i];
						  if (preg_match("/fum_file_session/", $name->src)) {						
							$exp1 = explode("fn-",$name->id);					
							$exp2 = explode("_xn",$exp1[1]);
							$namefile = $exp2[0].".".$exp2[1];	
							$fpath = HTTP_SERVER . "/" . DIR_WS_IMAGES . "users_names/" . $name->id.".".$exp2[1];
												
						  } else { 
							$namefile = $name->id;
							$exp1 = explode("fn-",$namefile);					
							$exp2 = explode("_xn",$exp1[1]);
							$namefile = $exp2[0].".".$exp2[1];
							$fpath = HTTP_SERVER . "/" . DIR_WS_IMAGES . "users_names/" . basename($name->src);
						  }					  
										  
						  if(file_exists($root . "/" . DIR_WS_IMAGES . "users_names/" .$name->id.".".$exp2[1])) {	  
							  $bdfile .= '<a href="download.php?file=txt&path='.$name->id.".".$exp2[1].'"> '.$namefile.' </a><br>';
						  } else {
							$bdfile .= $namefile."<br>";
						  }
					} 
				} else if ($badge_data=="") {
					
					$cust = 1;
					
					$pathinfo = pathinfo($orders["badge_data"]); //filepath
					$path2 = pathinfo($orders['customers_name']);
					$ext = strtolower($pathinfo['extension']);
					$cmt = $orders['customers_company'];
					
					if($ext=="txt" || $ext=="xls" || $ext=="csv" || $ext=="xlsx" || $ext=="pdf" || $ext=="doc" || $ext=="ai" || $ext=="cdr" || $ext=="eps" || $ext=='docx') { 
						$bdfile .= '<a href="download.php?file=txt&type=custom&path='.$path2['basename'].'"> '.$orders["badge_data"].' </a><br>';
					} 
					else {
						$bdfile .= "No Files Available.";
					}	
									
				}
				else {
					$bdfile .= "No Files Available.";
				}	   
		
			  //if($badge_data!="" && (count($badge_data->logos)>0 || count($badge_data->multiName)>0)) {
			  //if($badge_data!="") {
				?>
				<tr>
				  <td class="main" width="8%"><?php echo $orders['orders_id']; ?></td>
				  <td class="main" width="20%"><?php echo tep_date_long($orders['date_purchased']); ?></td>
				  <td class="main" width="35%" align="center"><?php	echo $bdimg; ?>  </td>          
				  <td class="main" width="10%"> <?php  echo $bdfile; ?>  </td>
				  <td class="main" width="15%"> <?php  echo $cmt; ?>  </td>
				  <td class="main" width="10%">
				  	<?php 
					if($cust==1) {
						if(isset($_GET['page'])) {
							$page = $_GET['page'];
						} else {
							$page = 1;
						}
						echo '<a onclick="return delconfirm()" href="' . tep_href_link(FILENAME_CUSTOMER_FILES, 'page='.$page.'&amp;cID='.$customer_id.'&amp;delete=' . $orders['orders_id'] . '&amp;action=deleteconfirm', 'SSL') . '">' . tep_image_button('small_delete.gif', 'Delete') . '</a>'; 
					}
					?>
				  </td>
				</tr>
	<?php				 
			 //} //End of if loop
		 }
	
	 } 
?>
                </table>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
                  <tr>
                    <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                        <tr>
                          <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                          <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                        <tr>
                          <?php
                    // RCI code start
                    echo $cre_RCI->get('orders', 'listingbottom');
                    // RCI code eof
					?>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
            </tr>
          </table></td>
      </tr>
      <?php
  
  // RCI code start
  echo $cre_RCI->get('global', 'bottom');                                        
  // RCI code eof
?>
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