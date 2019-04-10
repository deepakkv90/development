<script type="text/javascript">
	function delconfirm() {		
		if(confirm("Are you sure want to delete this file?")) {
			return true;
		}
		return false;
	}
</script>

<h1><?php echo HEADING_TITLE; ?></h1>

<?php
if(isset($_GET['msg']) && $_GET['msg']!="") {

	if($_GET['msg']=="upload") {
		echo "<div class='content' style='color:#0099FF;'>File uploaded successfully</div>";
	} else if($_GET['msg']=="delete") {
		echo "<div class='content' style='color:#0099FF;'>File deleted successfully</div>";
	}

}
?>

<div class="content">
		<?php
		  echo tep_draw_form('frmMyfiles', tep_href_link(FILENAME_ACCOUNT_MYFILES, '', 'SSL'), 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('action', 'process');
		?>
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
			  <td><input class="button" type="submit" name="btnSendFiles" value="Submit" /></td>
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
	  
	  <?php
	//get root
	$root = $_SERVER['DOCUMENT_ROOT'].mb_substr($_SERVER['PHP_SELF'],0,-mb_strlen(strrchr($_SERVER['PHP_SELF'],"/")));
	
    $orders_total = tep_count_customer_orders();

    if ($orders_total > 0) {
    			
		$history_query_raw = "select * from ((SELECT o.orders_id, o.date_purchased, p.badge_data, o.customers_name, o.customers_company from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p where o.customers_id = '" . (int)$customer_id . "' && o.orders_id = op.orders_id && op.products_id = p.products_id && p.badge_data!='') union all (select files_id, date_uploaded, file_name, file_path, comment from ".TABLE_MYFILES." where customers_id='". (int)$customer_id ."')) as tmp order by date_purchased desc";	
		
		// $history_query_raw = "SELECT o.orders_id, o.date_purchased, p.badge_data, o.customers_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p where o.customers_id = '" . (int)$customer_id . "' && o.orders_id = op.orders_id && op.products_id = p.products_id && p.badge_data!=''";
			
		$history_split = new splitPageResults($history_query_raw, MAX_DISPLAY_ORDER_HISTORY);
		
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
						echo '<a class="button" onclick="return delconfirm()" href="' . tep_href_link(FILENAME_ACCOUNT_MYFILES, 'delete=' . $history['orders_id'] . '&amp;action=deleteconfirm', 'SSL') . '">Delete</a>'; 
					}
					?>
				  </td>				  
			   </tr>
			</table>
      <?php
    }
  } else {
  ?>
    <div class="content">
		<?php echo TEXT_NO_PURCHASES; ?>
	</div>
  <?php
  }
  ?>
</div>

<?php
if ($orders_total > 0) {
	?>
	<div class="content">
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
			<tr>
			  <td class="smallText" valign="top"><?php echo $history_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
			  <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $history_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
			</tr>
		</table>
	</div>
<?php
}
?>

<div class="content">
	  <table border="0" width="100%" cellspacing="0" cellpadding="2">
		<tr>
		  <td><?php echo '<a class="button" href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">Back</a>'; ?></td>
		</tr>
	  </table>
</div>
