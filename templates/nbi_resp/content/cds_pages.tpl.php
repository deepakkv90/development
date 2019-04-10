<?php
/*
  $Id: cds_pages.php,v 1.2.0.0 2007/11/06 11:21:11 datazen Exp $

  CRE Loaded, Commercial Open Source E-Commerce
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('cdspages', 'top');
// RCI code eof
?>
<!-- cds_pages.tpl.php -->

<h1 class="textCenter"><?php echo $heading_title; ?></h1>

	<?php 
	  $displayed = false;
	  if ( ($listing_columns != 1) && (!isset($_GET['pID'])) ) {
		echo '<div class="content">';
		$product_insert = (isset($product_string) && $product_string != '') ? $product_string : '';
		if (strip_tags($product_insert . $product_string) != '') {    
			echo $product_insert . $descr . $display_string; 
		  $displayed = true;
		}
		echo '</div>';
	  }
	?>

<?php if (strip_tags($display_string) != '') { 
	
		if ($listing_columns == 1) {         
              $product_insert = (isset($product_string) && $product_string != '') ? $product_string : '';
              if((strip_tags($descr) != '') || ($product_insert != '')) {
                echo '<div class="content">'. $product_insert . $descr . '</div>';
              }
              echo '<div class="content">'. $display_string . '</div>';
        } else {
              if (!$displayed) {
                echo '<div class="content textCenter">'. $descr . $display_string .  '</div>';
              }
        }
		
		if (isset($acf_file) && $acf_file != '') {
			echo '<div class="content ">';
			@include_once($acf_file);
			echo '</div>';
		}

} ?>

<?php
// RCI code start
echo $cre_RCI->get('global', 'bottom');
echo $cre_RCI->get('cdspages', 'bottom');
// RCI code eof
?><!-- cds_pages.tpl.php-eof -->