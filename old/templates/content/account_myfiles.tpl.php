<?php
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('accounthistory', 'top');
  // RCI code eof    
  ?>
<script type="text/javascript">
	function delconfirm() {		
		if(confirm("Are you sure want to delete this file?")) {
			return true;
		}
		return false;
	}
</script>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_history.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
        </tr>
      </table></td>
  </tr>
  <tr>
  	<td>
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
		
		
		  echo tep_draw_form('frmMyfiles', tep_href_link(FILENAME_ACCOUNT_MYFILES, '', 'SSL'), 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('action', 'process'); ?>
		
		<table border="0" width="100%">
			<tr>
			  <td>&nbsp;</td>
			  <td colspan="3">Please use below form to send name list and logo  to <?php echo STORE_NAME; ?>. <br />
			    Remember to write a note about your order or any existing job in progress regarding the document you're uploading.<br />
			    Allowed file formats are jpg, gif, png, txt, pdf, xls, xlsx, csv, ai, eps, cdr and doc only. <br />
			    <br />
		      You could then re-use these file in the future to re-order.</td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td colspan="2">&nbsp;</td>
			  <td>&nbsp;</td>
		  </tr>
			<tr>
			  <td width="22%">&nbsp;</td>
				<td width="20%">
					<strong>Upload Files:				</strong></td>
				<td width="39%">
					<input type="file" name="custom_file" id="custom_file" /></td>
			    <td width="19%">&nbsp;</td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			  <td valign="top"><strong>Comments</strong></td>
			  <td><textarea name="comment" style="width:210px;"></textarea></td>
			  <td>&nbsp;</td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td><?php echo tep_template_image_submit('button_submit_link.gif', "Submit"); ?></td>
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
		
	</td>
  </tr>
  <tr>
  
  <td>
  <table border="0" width="100%" cellspacing="0" cellpadding="0" style="border-bottom:1px solid #ccc;">
    <tr>
      <td class="main" width="6%"><b><?php echo TEXT_ORDER_NUMBER; ?></b></td>
      <td class="main" width="20%" align="center"><b><?php echo TEXT_DATE_PURCHASED; ?></b></td>
      <td class="main" width="35%" align="center"><b><?php echo TEXT_LOGOS; ?></b></td>     
      <td class="main" width="15%"><b><?php echo TEXT_FILES; ?></b></td>     
	  <td class="main" width="15%"><b><?php echo TEXT_COMMENTS; ?></b></td>    
	   <td class="main" width="6%"><b>&nbsp;</b></td>   
	</tr>	  
  </table>
  </td>
  
  </tr>
  
  <?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>
  <?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
  <tr>
    <td>
			
	<?php
	//get root
	$root = $_SERVER['DOCUMENT_ROOT'].mb_substr($_SERVER['PHP_SELF'],0,-mb_strlen(strrchr($_SERVER['PHP_SELF'],"/")));
	
    $orders_total = tep_count_customer_orders();

    if ($orders_total > 0) {
    			
		$history_query_raw = "select * from ((SELECT o.orders_id, o.date_purchased, p.badge_data, o.customers_name, o.customers_company from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p where o.customers_id = '" . (int)$customer_id . "' && o.orders_id = op.orders_id && op.products_id = p.products_id && p.badge_data!='') union all (select files_id, date_uploaded, file_name, file_path, comment from ".TABLE_MYFILES." where customers_id='". (int)$customer_id ."')) as tmp order by date_purchased desc";	
		
		// $history_query_raw = "SELECT o.orders_id, o.date_purchased, p.badge_data, o.customers_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p where o.customers_id = '" . (int)$customer_id . "' && o.orders_id = op.orders_id && op.products_id = p.products_id && p.badge_data!=''";
			
		$history_split = new splitPageResults($history_query_raw, MAX_DISPLAY_ORDER_HISTORY);
		
		//echo $history_split->sql_query;
		//exit;
		
		$history_query = tep_db_query($history_split->sql_query);
	
		while ($history = tep_db_fetch_array($history_query)) {
    		  
		  $badge_data = @json_encode(@unserialize($history["badge_data"]));
		  
		  $badge_data = json_decode($badge_data);	
		 		  		  
		  $bdimg = ""; $bdfile = "";  $cust = 0; $cmt = "";
		  
		  if(count($badge_data->logos)>0) {								
			
			for($i = 0; $i < count($badge_data->logos); $i++) {
				$logo = $badge_data->logos[$i];
				$bdimg .= '<a href="admin/download.php?file=img&path='.$logo->src.'"><img src="image_thumb.php?file='.DIR_FS_CATALOG . DIR_WS_IMAGES ."users_badges/".$logo->src.'&sizex=60&sizey=60" style="border:1px solid #ccc;"></a>';
			}
		  } else if ($badge_data=="") {
			
			$cust = 1;
			
			$pathinfo = pathinfo($history["badge_data"]);
			$path2 = pathinfo($history['customers_name']);			
			$ext = strtolower($pathinfo['extension']);
			$cmt = $history['customers_company'];
			
			if($ext=="jpeg" || $ext=="gif" || $ext=="png" || $ext=="jpg") { 
				$bdimg .= '<a href="admin/download.php?file=img&type=custom&path='.$path2['basename'].'"><img src="image_thumb.php?file='.$history["customers_name"].'&sizex=60&sizey=60" style="border:1px solid #ccc;"></a>';
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
					  $bdfile .= '<a href="admin/download.php?file=txt&path='.$name->id.".".$exp2[1].'"> '.$namefile.' </a><br>';
				  } else {
					$bdfile .= $namefile."<br>";
				  }
			} 
		} else if ($badge_data=="") {
			
			$cust = 1;
			
			$pathinfo = pathinfo($history["badge_data"]);
			$path2 = pathinfo($history['customers_name']);			
			$ext = strtolower($pathinfo['extension']);
			
			$cmt = $history['customers_company'];
			
			if($ext=="txt" || $ext=="xls" || $ext=="csv" || $ext=="xlsx" || $ext=="pdf" || $ext=="doc" || $ext=="ai" || $ext=="cdr" || $ext=="eps" || $ext=='docx') { 
				$bdfile .= '<a href="admin/download.php?file=txt&type=custom&path='.$path2['basename'].'"> '.$history["badge_data"].' </a><br>';
			} 
			else {
				$bdfile .= "No Files Available.";
			}	
							
		}
		else {
			$bdfile .= "No Files Available.";
		}	   
		  
			 ?>
			 <table border="0" width="100%" cellspacing="0" cellpadding="0" style="border-bottom:1px solid #ccc;">
				<tr>
				  <td class="main" width="6%"><?php echo $history['orders_id']; ?></td>
				  <td class="main" width="20%"><?php echo tep_date_long($history['date_purchased']); ?></td>
				  <td class="main" width="35%" align="center">
					<?php				
						echo $bdimg;
					?>				
				  </td>          
				  <td class="main" width="15%"> <?php  echo $bdfile; ?>  </td>
				   <td class="main" width="15%"><?php echo $cmt; ?> </td>
				  <td class="main" width="6%">
				  	<?php 
					if($cust==1) {
						echo '<a onclick="return delconfirm()" href="' . tep_href_link(FILENAME_ACCOUNT_MYFILES, 'delete=' . $history['orders_id'] . '&amp;action=deleteconfirm', 'SSL') . '">' . tep_template_image_button('small_delete.gif', SMALL_IMAGE_BUTTON_DELETE) . '</a>'; 
					}
					?>
				  </td>				  
			   </tr>
	  </table>
      <?php
    }
  } else {
?>
      <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" width="100%" cellspacing="2" cellpadding="4">
              <tr>
                <td class="main"><?php echo TEXT_NO_PURCHASES; ?></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <?php
  }
?>
    </td>
  </tr>
  <?php
      // RCI code start
      echo $cre_RCI->get('accounthistory', 'menu');
      // RCI code eof    
      // BOF: Lango Added for template MOD
      if (MAIN_TABLE_BORDER == 'yes'){
        table_image_border_bottom();
      }
      // EOF: Lango Added for template MOD
      if ($orders_total > 0) {
        ?>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td class="smallText" valign="top"><?php echo $history_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
          <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $history_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
        </tr>
      </table></td>
  </tr>
  <?php
  }
?>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
  
  <td>
  <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
    <tr class="infoBoxContents">
      <td>
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
          <td><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
        </td>
        
        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
        </tr>
      </table>
    </td>
    
    </tr>
    
  </table>
  </td>
  
  </tr>
  
</table>
<?php
    // RCI code start
    echo $cre_RCI->get('accounthistory', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof    
    ?>
