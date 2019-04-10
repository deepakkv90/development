<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('shoppingcart', 'top');
// RCI code eof

?>
<style type="text/css">

.error{ color:#FF0000; }
.red_star { color:#FF0000; font-weight:bold; }
.block { display: block; }
form.cmxform label.error { display: none; }

</style>
<script type="text/javascript">
		
	//After validation submit form
	$.validator.setDefaults({
		submitHandler: function(form) {			
			form.submit();			
		}
	});		
	$.metadata.setType("attr", "validate");
	
	//onload call functions
	jQuery(document).ready(function($) {		
			//initiate();			
			$("#frm_shopping_cart").validate();	
	});
	                      
</script>

<?php

if ($cart->count_contents() > 0) {
//echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product', "SSL")); 
echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product', "SSL"), 'post', 'id="frm_shopping_cart" class="cmxform"'); 
}
?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
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
            <!--<td class="pageHeading" align="right"><?php //echo tep_image(DIR_WS_IMAGES . 'table_background_cart.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td> Modified Aug 23, 2010--> 
			<td class="pageHeading" align="right">
				<?php
				if($valid_to_checkout == true){
				echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_template_image_button('proceed-checkout.png', IMAGE_BUTTON_CHECKOUT) . '</a>';
				};
				?>
			</td>
          </tr>
        </table></td>
      </tr>

<?php
}else{
$header_text =  HEADING_TITLE;
}
?>

<?php
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
?>
<?php
  if ($cart->count_contents() > 0) {
?>
      <tr>
        <td style="padding:0px;">
<?php
    $info_box_contents = array();
    $info_box_contents[0][] = array('align' => 'center',
                                    'params' => 'class="productListing-heading"',
                                    'text' => '&nbsp;');
	
	$info_box_contents[0][] = array('params' => 'class="productListing-heading"',
                                    'text' => '&nbsp;'); //Modified Aug 23, 2010
    
	$info_box_contents[0][] = array('params' => 'class="productListing-heading"',
                                    'text' => TABLE_HEADING_PRODUCTS);

	// Eversun mod for display unit price column
    $info_box_contents[0][] = array('align' => 'right',
                                    'params' => 'class="productListing-heading"',
                                    'text' => TABLE_HEADING_UNIT_PRICE);
									
    $info_box_contents[0][] = array('align' => 'center',
                                    'params' => 'class="productListing-heading"',
                                    'text' => TABLE_HEADING_QUANTITY);

// Eversun mod end for display unit price column

    $info_box_contents[0][] = array('align' => 'right',
                                    'params' => 'class="productListing-heading"',
                                    'text' => TABLE_HEADING_SUB_TOTAL);

    $any_out_of_stock = 0;
	// START: display min. order. qty. mod - Mar 04 2011
    $any_under_min_order_qty = 0;
	// END: display min. order. qty. mod 
    $products = $cart->get_products();
	    
	for ($i=0, $n=sizeof($products); $i<$n; $i++) {
		
	   // Push all attributes information in an array
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
      //check product for parent

        while (list($option, $value) = each($products[$i]['attributes'])) {

           $products_id_tmp = tep_subproducts_parent(tep_get_prid($products[$i]['id']));
                  if(tep_subproducts_parent($products_id_tmp)){
                   	$products_id_query = tep_subproducts_parent($products_id_tmp);
                   }else{
                   	$products_id_query = tep_get_prid($products[$i]['id']);
                   }

          if ( ! is_array($value) ) {
            $attributes = tep_db_query("select op.options_id, ot.products_options_name, o.options_type, ov.products_options_values_name, op.options_values_price, op.price_prefix
                                        from " . TABLE_PRODUCTS_ATTRIBUTES . " op,
                                             " . TABLE_PRODUCTS_OPTIONS_VALUES . " ov,
                                             " . TABLE_PRODUCTS_OPTIONS . " o,
                                             " . TABLE_PRODUCTS_OPTIONS_TEXT . " ot
                                        where op.products_id = '" . $products_id_query . "'
                                          and op.options_values_id = '" . $value . "'
                                          and op.options_id = '" . $option . "'
                                          and ov.products_options_values_id = op.options_values_id
                                          and ov.language_id = '" . (int)$languages_id . "'
                                          and o.products_options_id = op.options_id
                                          and ot.products_options_text_id = o.products_options_id
                                          and ot.language_id = '" . (int)$languages_id . "'
                                       ");
            $attributes_values = tep_db_fetch_array($attributes);

            $products[$i][$option][$value]['products_options_name'] = $attributes_values['products_options_name'];
            $products[$i][$option][$value]['options_values_id'] = $value;
            $products[$i][$option][$value]['products_options_values_name'] = $attributes_values['products_options_values_name'];
            $products[$i][$option][$value]['options_values_price'] = $attributes_values['options_values_price'];
            $products[$i][$option][$value]['price_prefix'] = $attributes_values['price_prefix'];

          } elseif ( isset($value['c'] ) ) {
            foreach ($value['c'] as $v) {
                $attributes = tep_db_query("select op.options_id, ot.products_options_name, o.options_type, ov.products_options_values_name, op.options_values_price, op.price_prefix
                                            from " . TABLE_PRODUCTS_ATTRIBUTES . " op,
                                                 " . TABLE_PRODUCTS_OPTIONS_VALUES . " ov,
                                                 " . TABLE_PRODUCTS_OPTIONS . " o,
                                                 " . TABLE_PRODUCTS_OPTIONS_TEXT . " ot
                                            where op.products_id = '" . $products_id_query . "'
                                              and op.options_values_id = '" . $v . "'
                                              and op.options_id = '" . $option . "'
                                              and ov.products_options_values_id = op.options_values_id
                                              and ov.language_id = '" . (int)$languages_id . "'
                                              and o.products_options_id = op.options_id
                                              and ot.products_options_text_id = o.products_options_id
                                              and ot.language_id = '" . (int)$languages_id . "'
                                           ");
              $attributes_values = tep_db_fetch_array($attributes);

              $products[$i][$option][$v]['products_options_name'] = $attributes_values['products_options_name'];
              $products[$i][$option][$v]['options_values_id'] = $v;
              $products[$i][$option][$v]['products_options_values_name'] = $attributes_values['products_options_values_name'];
              $products[$i][$option][$v]['options_values_price'] = $attributes_values['options_values_price'];
              $products[$i][$option][$v]['price_prefix'] = $attributes_values['price_prefix'];
            }

          } elseif ( isset($value['t'] ) ) {
            $attributes = tep_db_query("select op.options_id, ot.products_options_name, o.options_type, op.options_values_price, op.price_prefix
                                        from " . TABLE_PRODUCTS_ATTRIBUTES . " op,
                                             " . TABLE_PRODUCTS_OPTIONS . " o,
                                             " . TABLE_PRODUCTS_OPTIONS_TEXT . " ot
                                        where op.products_id = '" . $products_id_query . "'
                                          and op.options_id = '" . $option . "'
                                          and o.products_options_id = op.options_id
                                          and ot.products_options_text_id = o.products_options_id
                                          and ot.language_id = '" . (int)$languages_id . "'
                                       ");
            $attributes_values = tep_db_fetch_array($attributes);

            $products[$i][$option]['t']['products_options_name'] = $attributes_values['products_options_name'];
            $products[$i][$option]['t']['options_values_id'] = '0';
            $products[$i][$option]['t']['products_options_values_name'] = $value['t'];
            $products[$i][$option]['t']['options_values_price'] = $attributes_values['options_values_price'];
            $products[$i][$option]['t']['price_prefix'] = $attributes_values['price_prefix'];
          }
        }
      }
    }

    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      if (($i/2) == floor($i/2)) {
        $info_box_contents[] = array('params' => 'class="productListing-even"');
      } else {
        $info_box_contents[] = array('params' => 'class="productListing-odd"');
      }

      $cur_row = sizeof($info_box_contents) - 1;

      $info_box_contents[$cur_row][] = array('align' => 'center',
                                             'params' => 'class="productListing-data"',
                                             'text' => tep_draw_checkbox_field('cart_delete[]', $products[$i]['id_string'], false, ' style="display:none;" id="delete_'.$i.'"').
                                             '<input type="image" title="Delete" src="images/delete.png" onclick="'. "$('#delete_".$i."').attr('checked', 'checked');" . '">'
                                             );

///////////////////////////////////////////////////////////////////////////////////////////////////////
// MOD begin of sub product

    $db_sql = "select products_parent_id from " . TABLE_PRODUCTS . " where products_id = " . (int)tep_get_prid($products[$i]['id']);
    $products_parent_id = tep_db_fetch_array(tep_db_query($db_sql));

    if ((int)$products_parent_id['products_parent_id'] != 0) {
    $products_name  = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_parent_id['products_parent_id']) . '"><b>' . $products[$i]['name'] . '</b></a>';
    } else {
    $products_name  = '<b>' . $products[$i]['name'] . '</b>';
    }

      if (STOCK_CHECK == 'true') {
        $stock_check = tep_check_stock((int)$products[$i]['id'], $products[$i]['quantity']);
        if (tep_not_null($stock_check)) {
          $any_out_of_stock = 1;
          $products_name .= $stock_check;
        }
      }
	  
	   // START: display min. order. qty. mod  - Mar 03 2011  
	  if(PRODUCT_LIST_MIN_ORDER_QTY == 1) {  	   
		  $min_order_check = tep_check_min_order_qty((int)$products[$i]['id'], $products[$i]['quantity']);
		  if (tep_not_null($min_order_check)) {
				$any_under_min_order_qty = 1;	
				//$products_name .= $min_order_check;
		  }	
	  }	
	  // END: display min. order. qty. mod
	  
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {
// BOM - Options Catagories
//          $products_name .= '<br>' . ' - ' . '<small><i>' . $products[$i][$option]['products_options_name'] . ' : ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
          if ( !is_array($value) ) {

           if ($products[$i][$option][$value]['options_values_price'] > 0 ){
               $attribute_price = $products[$i][$option][$value]['price_prefix']  . $currencies->display_price($products[$i][$option][$value]['options_values_price'], tep_get_tax_rate($products[$i]['tax_class_id']));
             } else {
              $attribute_price ='';
             }

             if ($products[$i][$option][$value]['products_options_name']) {
               $products_name .= '; <small><i>' . $products[$i][$option][$value]['products_options_name'] . ' : ' . $products[$i][$option][$value]['products_options_values_name'] . '&nbsp;&nbsp;&nbsp;' .$attribute_price . '</i></small>';
            }
          } else {
            if ( isset($value['c']) ) {
              foreach ( $value['c'] as $v ) {

            if ($products[$i][$option][$v]['options_values_price'] > 0 ){
               $attribute_price = $products[$i][$option][$v]['price_prefix']  . $currencies->display_price($products[$i][$option][$v]['options_values_price'], tep_get_tax_rate($products[$i]['tax_class_id']));
             } else {
              $attribute_price ='';
             }
             if ($products[$i][$option][$v]['products_options_name']) {
                $products_name .= '; <small><i>' . $products[$i][$option][$v]['products_options_name'] . ' : ' . $products[$i][$option][$v]['products_options_values_name'] . '&nbsp;&nbsp;&nbsp;' .$attribute_price . '</i></small>';
              }
              }
            } elseif ( isset($value['t']) ) {
            if ($products[$i][$option]['t']['options_values_price'] > 0 ){
               $attribute_price = $products[$i][$option]['t']['price_prefix']  . $currencies->display_price($products[$i][$option]['t']['options_values_price'], tep_get_tax_rate($products[$i]['tax_class_id']));
             } else {
              $attribute_price ='';
             }
             if ($products[$i][$option]['t']['products_options_name']) {
                 $products_name .= '; <small><i>' . $products[$i][$option]['t']['products_options_name'] . ' : ' . $value['t'] . '&nbsp;&nbsp;&nbsp;' . $attribute_price . '</i></small>';
             }
            }
          }
// EOM - Options Catagories
        }
      }

	/*
	if ((int)$products_parent_id['products_parent_id'] != 0) {
    $products_name .= '<br /><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_parent_id['products_parent_id']) . '">' . tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'; 
    } else {
    $products_name .= '<br /><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
    }
	*/
	
	//Modified on Aug 25, 2010
	/*
	 if ((int)$products_parent_id['products_parent_id'] != 0) {
			$products_img = '<br /><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_parent_id['products_parent_id']) . '"><img src="" alt="'.$products[$i]['name'].'"></a>'; 
    } else {
			$products_img = '<br /><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><img src="" alt="'.$products[$i]['name'].'"></a>';
    }
	*/
	
      $psp = split('{zname}', $products[$i]['id']);      
      $product_row = tep_db_query("select products.*, products_to_categories.categories_id as cat_id from products inner join products_to_categories on products_to_categories.products_id = products.products_id where products.products_id = ".(int)$psp[0]);
      $product_row = tep_db_fetch_array($product_row);
      $dprid = $product_row['default_product_id'];
      if ($dprid) {
        require_once(dirname(dirname(__FILE__)).'/'.TEMPLATE_NAME.'/bd/badge_desc.php');
        $badge = new Badge($product_row['badge_data']);
        $products_name .= $badge->description();
		//Add all dynamic products options - Dec 21 2012
		$products_name .= tep_get_badge_products_options((int)$psp[0]);
      }
	  
	  //Modified Aug 23, 2010
	 if ($dprid) {
        require_once(dirname(dirname(__FILE__)).'/'.TEMPLATE_NAME.'/bd/badge_desc.php');
        $badge = new Badge($product_row['badge_data']);
		$info_box_contents[$cur_row][] = array('align' => 'center', 'border' => '1',
                                             'params' => 'class="productListing-data"',
                                             'text' => '<br /><a href="'.tep_href_link('index.php', 'cPath='.$product_row['cat_id'].'&product_id='.$product_row['products_id'].'&osCsid='.$_GET['osCsid']).'"><img src="image_thumb.php?file='.DIR_WS_IMAGES . $products[$i]['image'].'&sizex=150&sizey=150" alt="'.$products[$i]['name'].'"></a><br /><br /><a href="'.tep_href_link('index.php','cPath='.$product_row['cat_id'].'&product_id='.$product_row['products_id'].'&osCsid='.$_GET['osCsid']).'">'.tep_template_image_button('small_edit.gif', IMAGE_BUTTON_EDIT) .'</a>'
											 );
	} else {
		
		 if ((int)$products_parent_id['products_parent_id'] != 0) {
				$products_img = '<br /><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'cPath='.$product_row['cat_id'].'&products_id=' . $products_parent_id['products_parent_id']) . '"><img src="image_thumb.php?file='.DIR_WS_IMAGES . $products[$i]['image'].'&sizex=150&sizey=150" alt="'.$products[$i]['name'].'"></a>'; 
		} else {
				$products_img = '<br /><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'cPath='.$product_row['cat_id'].'&products_id=' . $products[$i]['id']) . '"><img src="image_thumb.php?file='.DIR_WS_IMAGES . $products[$i]['image'].'&sizex=150&sizey=150" alt="'.$products[$i]['name'].'"></a>';
		}
	
		$info_box_contents[$cur_row][] = array('align' => 'center',
                                             'params' => 'class="productListing-data"',
                                             'text' => $products_img);
	}

    $info_box_contents[$cur_row][] = array('params' => 'class="productListing-data"',
                                             'text' => $products_name);

// Eversun mod for display unit price column
/* // modified aug 25, 2010
      $info_box_contents[$cur_row][] = array('align' => 'right',
                                             'params' => 'class="productListing-data"',
                                             'text' => '<b>' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id'])) . '</b>');
*/		
	$info_box_contents[$cur_row][] = array('align' => 'right',
                                             'params' => 'class="productListing-data"',
                                             'text' => '<b>' . $currencies->display_price($products[$i]['final_price']) . '</b>');									 											 
     //Min qty error label 
	   $minimum_qty = tep_get_products_min_order_qty($products[$i]['id']);
	   $min_qty_label = '<br> <label class="error" for="cart_quantity['.$i.']" ';
	   $min_qty_label .= (PRODUCT_LIST_MIN_ORDER_QTY==1  && $products[$i]['quantity']<$minimum_qty)?" style='display:block;' ":"";
	   $min_qty_label .= '> *Required <br> Min : ' .$minimum_qty. ' </label>';
	   
	   $min_qty_attr = (PRODUCT_LIST_MIN_ORDER_QTY==1)?" validate='required:true, min:".$minimum_qty."' ":'';
		
	  if ($dprid) {
        require_once(dirname(dirname(__FILE__)).'/'.TEMPLATE_NAME.'/bd/badge_desc.php');
        $badge = new Badge($product_row['badge_data']);
		
        $info_box_contents[$cur_row][] = array('align' => 'center',
                                             'params' => 'class="productListing-data"',                                            
											 'text' => tep_draw_input_field('cart_quantity['.$i.']', $products[$i]['quantity'], 'size="4" maxlength="9" '.$min_qty_attr) . $min_qty_label . tep_draw_hidden_field('products_id[]', $products[$i]['id_string']));
											 
      } else  {	  	
        $info_box_contents[$cur_row][] = array('align' => 'center',
                                               'params' => 'class="productListing-data"',
                                              'text' => tep_draw_input_field('cart_quantity['.$i.']', $products[$i]['quantity'], 'size="4" maxlength="9" '.$min_qty_attr) . $min_qty_label . tep_draw_hidden_field('products_id[]', $products[$i]['id_string']));
      }
											 
      $info_box_contents[$cur_row][] = array('align' => 'right',
                                             'params' => 'class="productListing-data"',
                                             'text' => '<b>' . $currencies->display_price($products[$i]['final_price'], '',$products[$i]['quantity']) . '</b>');
											 
	$GST_Tax = tep_get_tax_rate($products[$i]['tax_class_id']);
	
	}

    new productListingBox($info_box_contents);
?>
        </td>
      </tr>
     <!-- <tr>
        <td><?php //echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
	  -->
<?php	  
	  // RCI code start
echo $cre_RCI->get('shoppingcart', 'insideformabovebuttons');
// RCI code eof
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
?>
      <tr>
        <td style="background-color:#C5AF97;"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="infoBox">
          <tr class="infoBoxContents">
            <td ><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
<?php
if (RETURN_CART == L){
   $back = sizeof($navigation->path)-2;
    if (isset($navigation->path[$back])) {
    $nav_link = '<a href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '">' . tep_template_image_button('continue-shopping.png', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>';
        }
 } else if ((RETURN_CART == C) || (eregi('wishlist', $_SERVER['HTTP_REFERER']))){
  if (!eregi('wishlist', $_SERVER['HTTP_REFERER'])) {
    $products = $cart->get_products();
    $products = array_reverse($products);
    $cat = tep_get_product_path($products[0]['id']) ;
    $cat1= 'cPath=' . $cat;
    if ($products == '') {
      $back = sizeof($navigation->path)-2;
      if (isset($navigation->path[$back])) {
        $nav_link = '<a href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '">' . tep_template_image_button('continue-shopping.png', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>';
      }
    }else{
      $nav_link = '<a href="' . tep_href_link(FILENAME_DEFAULT, $cat1) . '">' . tep_template_image_button('continue-shopping.png', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>'  ;
    }
  }else{
    $nav_link = '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('continue-shopping.png', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>'  ;
  }
} else if (RETURN_CART == P){
  $products = $cart->get_products();
  $products = array_reverse($products);
  if ($products == '') {
    $back = sizeof($navigation->path)-2;
      if (isset($navigation->path[$back])) {
        $nav_link = '<a href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '">' . tep_template_image_button('continue-shopping.png', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>';
      }
  }else{
    $nav_link = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[0]['id']) . '">' . tep_template_image_button('continue-shopping.png', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>';
  }
}
?>
				<td class="main"><?php echo $nav_link; ?></td>
                <td class="main">&nbsp;</td>
                <td align="right" class="main">
                	<?php echo tep_template_image_submit('update-cart.png', IMAGE_BUTTON_UPDATE_CART); ?>				
				</td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
          <?php
          //RCI start
          echo $cre_RCI->get('shoppingcart', 'insideformbelowbuttons');
          //RCI end          
          ?>
        </table></form>
  		</td>
      </tr>
	  
	  
      <?php
		
		// GST Tax Calculation					
		$gst_tax = $cart->show_shopping_cart_total() * ($GST_Tax/100);  


      // RCI code start
      $offset_amount = 0;
      $returned_rci = $cre_RCI->get('shoppingcart', 'offsettotal');
      // RCI code eof
      if (trim(strip_tags($returned_rci)) != NULL) {
        echo '<tr>' . "\n";
        echo '  <td align="right"><table cellspacing="2" cellpadding="2" border="0">' . "\n";
        echo '    <tr>' . "\n";
        echo '      <td class="main bottomline" align="right"><b>' . SUB_TITLE_SUB_TOTAL . '</b></td>' . "\n";
        echo '      <td class="main bottomline" align="right"><b>' . $currencies->format($cart->show_shopping_cart_total()) . '</b></td>' . "\n";
        echo '    </tr>' . "\n";
		
		echo '    <tr>' . "\n";
        echo '      <td class="main bottomline" align="right"><b>'.SUB_TITLE_GRAND_TOTAL_EXCL_TAX.'</b></td>' . "\n";
        echo '      <td class="main bottomline" align="right"><b>' . $currencies->format($cart->show_shopping_cart_total()) . '</b></td>' . "\n";
        echo '    </tr>' . "\n";   
		
		echo '    <tr>' . "\n";
        echo '      <td class="main bottomline" align="right"><b>'.SUB_TITLE_TAX.'</b></td>' . "\n";
        echo '      <td class="main bottomline" align="right"><b>' . $currencies->format($gst_tax) . '</b></td>' . "\n";
        echo '    </tr>' . "\n";   
        echo $returned_rci;
        echo '    <tr>' . "\n";
        echo '      <td class="main bottomline" align="right"><b>' . SUB_TITLE_GRAND_TOTAL_INCL_TAX . '</b></td>' . "\n";
        echo '      <td class="main bottomline" align="right"><b>' . $currencies->format($cart->show_shopping_cart_total() + $offset_amount + $gst_tax) . '</b></td>' . "\n";
        echo '    </tr>' . "\n";
        echo '  </table></td>' . "\n";
        echo '</tr>' . "\n";
      } else {    
        /*
		echo '<tr>' . "\n";
        echo '  <td align="right" class="main"><b>' . SUB_TITLE_TOTAL . '&nbsp;&nbsp;' . $currencies->format($cart->show_total()) . '</b></td>' . "\n";
        echo '</tr>' . "\n";
		*/
		// Modified Aug 23, 2010
		echo '<tr>' . "\n";
        echo '  <td align="right"><table cellspacing="0" cellpadding="0" border="0">' . "\n";
        echo '    <tr>' . "\n";
        echo '      <td class="main bottomline" align="right"><b>' . SUB_TITLE_SUB_TOTAL . ' </b></td>' . "\n";
        echo '      <td class="main bottomline" align="right"><b>' . $currencies->format($cart->show_shopping_cart_total()) . '</b></td>' . "\n";
        echo '    </tr>' . "\n";   
		echo '    <tr>' . "\n";
        echo '      <td class="main bottomline" align="right"><b>'.SUB_TITLE_GRAND_TOTAL_EXCL_TAX.'</b></td>' . "\n";
        echo '      <td class="main bottomline" align="right"><b>' . $currencies->format($cart->show_shopping_cart_total()) . '</b></td>' . "\n";
        echo '    </tr>' . "\n";   
		
		echo '    <tr>' . "\n";
        echo '      <td class="main bottomline" align="right"><b>'.SUB_TITLE_TAX.'</b></td>' . "\n";
        echo '      <td class="main bottomline" align="right"><b>' . $currencies->format($gst_tax) . '</b></td>' . "\n";
        echo '    </tr>' . "\n";   
		
		echo '    <tr>' . "\n";
        echo '      <td class="main bottomline" align="right"><b>'.SUB_TITLE_GRAND_TOTAL_INCL_TAX.'</b></td>' . "\n";
        echo '      <td class="main bottomline" align="right"><b>' . $currencies->format($cart->show_shopping_cart_total() + $gst_tax) . '</b></td>' . "\n";
        echo '    </tr>' . "\n";
		echo '  </table></td>' . "\n";
        echo '</tr>' . "\n";   
		/*
        echo '    <tr>' . "\n";
        echo '      <td class="main" align="right"><b>' . SUB_TITLE_TOTAL . '</b></td>' . "\n";
        echo '      <td class="main" align="right"><b>' . $currencies->format($cart->show_total()) . '</b></td>' . "\n";
		echo '    </tr>' . "\n";
		*/
		
      }
	  
           
    if ($any_out_of_stock == 1) {
      if (STOCK_ALLOW_CHECKOUT == 'true') {
        $valid_to_checkout = true;
?>
      <tr>
        <td class="stockWarning" align="center"><br><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></td>
      </tr>
<?php
      } else {
    $valid_to_checkout= false;
?>
      <tr>
        <td class="stockWarning" align="center"><br><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></td>
      </tr>
<?php
      }
    }
	
	// Mar 03 2011 - added minimum quantity order
	if ($any_under_min_order_qty == 1) { ?>
      <!--<tr>
        <td class="stockWarning" align="center"><br><?php echo UNDER_MIN_ORDER_QTY_CANT_CHECKOUT; ?></td>
      </tr>-->
<?php }  ?>

	  <tr>
	  	<td align="right">
			<?php
			if($valid_to_checkout == true){
			echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_template_image_button('proceed-checkout.png', IMAGE_BUTTON_CHECKOUT) . '</a>';
			};
			?>
		</td>
	  </tr>
  <?php
  //RCI start
  echo $cre_RCI->get('shoppingcart', 'logic');
  //RCI end

  // WebMakers.com Added: Shipping Estimator
  if (SHOW_SHIPPING_ESTIMATOR=='true') {
    // always show shipping table
    ?>
    <tr>
      <td><?php require($additional_module_folder . FILENAME_SHIPPING_ESTIMATOR); ?></td>
    </tr>
    <?php
  }
} else {
  ?>
  <tr>
    <td align="center" class="main"><?php echo TEXT_CART_EMPTY; ?></td>
  </tr>
  <?php
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_bottom();
  }
  ?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="left" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('continue-shopping.png', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table>
<?php
// RCI code start
echo $cre_RCI->get('shoppingcart', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>