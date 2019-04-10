
<style type="text/css">

.error{ color:#FF0000; }
.red_star { color:#FF0000; font-weight:bold; }
.block { display: block; }
form.cmxform label.error { display: none; }
#frm_shopping_cart .cart-info{
  margin-bottom: 20px;
}
.clear{ clear: both; }
.buttons .right{ float: right; }
.buttons { margin: 30px 50px; }
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
<div id="primary">
      <div id="content" role="main">

        
          <div class="mid-cont">
  <article id="post-6" class="post-6 page type-page status-publish has-post-thumbnail hentry">

    <div class="entry-content">
      <div class="woocommerce">
  
	<h1 class="cart-title">Your Shopping Cart</h1>
	<?php
	if ($cart->count_contents() > 0) {
		echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product', "SSL"), 'post', 'id="frm_shopping_cart" class="cmxform"'); ?>

    <div class="cart-info">
	<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">
	  <thead>
		<tr>
		  <th class="image product-thumbnail">Image</th>
		  <th class="name product-name"><?php echo TABLE_HEADING_PRODUCTS; ?></th>
		  <th class="quantity product-quantity"><?php echo TABLE_HEADING_QUANTITY; ?></th>
		  <th class="price product-price"><?php echo TABLE_HEADING_UNIT_PRICE; ?></th>
		  <th class="total product-subtotal"><?php echo TABLE_HEADING_SUB_TOTAL; ?></th>
		</tr>
	  </thead>
	  <tbody>
				
<?php

    $any_out_of_stock = 0; $any_under_min_order_qty = 0;
	
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
	
	// MOD begin of sub product ///////////////////////
	
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

      $psp = split('{zname}', $products[$i]['id']);      
      $product_row = tep_db_query("select products.*, products_to_categories.categories_id as cat_id from products inner join products_to_categories on products_to_categories.products_id = products.products_id where products.products_id = ".(int)$psp[0]);
      $product_row = tep_db_fetch_array($product_row);
      $dprid = $product_row['default_product_id'];
      if ($dprid) {
        require_once(dirname(dirname(__FILE__)).'/bd/badge_desc.php');
        $badge = new Badge($product_row['badge_data']);
		
		$products_name .= '<div class="badge-desc">'.$badge->short_description() . tep_get_badge_products_options((int)$psp[0]) .'</div>';
		
		$products_img = '<a href="'.tep_href_link('index.php', 'cPath='.$product_row['cat_id'].'&product_id='.$product_row['products_id'].'&osCsid='.$_GET['osCsid']).'"><img src="'.DIR_WS_IMAGES . $products[$i]['image'].'" width="150" alt="'.$products[$i]['name'].'"></a><br /><br /><a class="button" href="'.tep_href_link('index.php','cPath='.$product_row['cat_id'].'&product_id='.$product_row['products_id'].'&osCsid='.$_GET['osCsid']).'">Edit</a>'; 
		
      } else {
		
		if ((int)$products_parent_id['products_parent_id'] != 0) {
				$products_img = '<br /><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'cPath='.$product_row['cat_id'].'&products_id=' . $products_parent_id['products_parent_id']) . '"><img src="'.DIR_WS_IMAGES . $products[$i]['image'].'" width="150" alt="'.$products[$i]['name'].'"></a>'; 
		} else {
				$products_img = '<br /><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'cPath='.$product_row['cat_id'].'&products_id=' . $products[$i]['id']) . '"><img src="'.DIR_WS_IMAGES . $products[$i]['image'].'" width="150" alt="'.$products[$i]['name'].'"></a>';
		}
		
	  }
	  
	  //Min qty error label 
	   $minimum_qty = tep_get_products_min_order_qty($products[$i]['id']);
	   $min_qty_label = '<br> <label class="error" for="cart_quantity['.$i.']" ';
	   $min_qty_label .= (PRODUCT_LIST_MIN_ORDER_QTY==1  && $products[$i]['quantity']<$minimum_qty)?" style='display:block;' ":"";
	   $min_qty_label .= '> *Required <br> Min : ' .$minimum_qty. ' </label>';
	   
	   $min_qty_attr = (PRODUCT_LIST_MIN_ORDER_QTY==1)?" validate='required:true, min:".$minimum_qty."' ":'';
	   
	   $GST_Tax = tep_get_tax_rate($products[$i]['tax_class_id']);
	   
	?>
	<tr class="woocommerce-cart-form__cart-item cart_item">
		<td class="image product-thumbnail"> <?php echo $products_img; ?></td>
		<td class="name product-name"> <?php echo $products_name; ?></td>
		<td class="quantity product-quantity"><?php echo tep_draw_input_field('cart_quantity['.$i.']', $products[$i]['quantity'], 'class="w30" size="4" '.$min_qty_attr) . $min_qty_label . tep_draw_hidden_field('products_id[]', $products[$i]['id_string']); ?>
			&nbsp;
			<img type="image" title="Update" alt="Update" src="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/image/update.png">
			&nbsp;
			<?php echo tep_draw_checkbox_field('cart_delete[]', $products[$i]['id_string'], false, ' style="display:none;" id="delete_'.$i.'"'). '<img type="image" title="Delete" src="'.DIR_WS_TEMPLATES.TEMPLATE_NAME.'/image/remove.png" onclick="'. "$('#delete_".$i."').attr('checked', 'checked');" . '">';?>
		</td>
		<td class="price product-price"><?php echo $currencies->display_price($products[$i]['final_price']); ?></td>
		<td class="total product-subtotal"><?php echo $currencies->display_price($products[$i]['final_price'], '',$products[$i]['quantity']); ?></td>
	</tr>
	<?php 
	}
	?>
    </tbody> 
	</table>
	</div>
	
<?php
if (RETURN_CART == L){
   $back = sizeof($navigation->path)-2;
    if (isset($navigation->path[$back])) {
    $nav_link = '<a class="button" href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '">Continue Shopping</a>';
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
        $nav_link = '<a class="button" href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '">Continue Shopping</a>';
      }
    }else{
      $nav_link = '<a class="button" href="' . tep_href_link(FILENAME_DEFAULT, $cat1) . '">Continue Shopping</a>'  ;
    }
  }else{
    $nav_link = '<a class="button" href="' . tep_href_link(FILENAME_DEFAULT) . '">Continue Shopping</a>'  ;
  }
} else if (RETURN_CART == P){
  $products = $cart->get_products();
  $products = array_reverse($products);
  if ($products == '') {
    $back = sizeof($navigation->path)-2;
      if (isset($navigation->path[$back])) {
        $nav_link = '<a class="button" href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '">Continue Shopping</a>';
      }
  } else{
    $nav_link = '<a class="button" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[0]['id']) . '">Continue Shopping</a>';
  }
}
?>
		<div class="buttons">
			<div class="right">
				<input class="button" type="submit" value="Update Cart" name="update-cart"/>
			</div>
      <div class="clear"></div>
		</div>
		
		</form>
	  
	  <div class="cart-total actions flexbox space-between flex-wrap">
      <div class="coupons"></div>
      <?php
		
		// GST Tax Calculation					
		$gst_tax = $cart->show_shopping_cart_total() * ($GST_Tax/100);  


      // RCI code start
      $offset_amount = 0;
      $returned_rci = $cre_RCI->get('shoppingcart', 'offsettotal');
      // RCI code eof
      if (trim(strip_tags($returned_rci)) != NULL) {
        
        echo '  <table id="total" class="cart-collaterals">' . "\n";
        echo '    <tr>' . "\n";
        echo '      <th class="right">' . SUB_TITLE_SUB_TOTAL . ' </th>' . "\n";
        echo '      <td class="right">' . $currencies->format($cart->show_shopping_cart_total()) . '</td>' . "\n";
        echo '    </tr>' . "\n";
		
		echo '    <tr>' . "\n";
        echo '      <th class="right">'.SUB_TITLE_GRAND_TOTAL_EXCL_TAX.'</th>' . "\n";
        echo '      <td class="right">' . $currencies->format($cart->show_shopping_cart_total()) . '</td>' . "\n";
        echo '    </tr>' . "\n";   
		
		echo '    <tr>' . "\n";
        echo '      <th class="right">'.SUB_TITLE_TAX.'</th>' . "\n";
        echo '      <td class="right">' . $currencies->format($gst_tax) . '</td>' . "\n";
        echo '    </tr>' . "\n";   
        echo $returned_rci;
        echo '    <tr>' . "\n";
        echo '      <th class="right">' . SUB_TITLE_GRAND_TOTAL_INCL_TAX . '</th>' . "\n";
        echo '      <td class="right">' . $currencies->format($cart->show_shopping_cart_total() + $offset_amount + $gst_tax) . '</td>' . "\n";
        echo '    </tr>' . "\n";
        echo '  </table>' . "\n";

      } else {    
       
		// Modified Aug 23, 2010
        echo '  <table id="total"><tbody>' . "\n";
        echo '    <tr>' . "\n";
        echo '      <th class="right">' . SUB_TITLE_SUB_TOTAL . '  </th>' . "\n";
        echo '      <td class="right">' . $currencies->format($cart->show_shopping_cart_total()) . '</td>' . "\n";
        echo '    </tr>' . "\n";   
		echo '    <tr>' . "\n";
        echo '      <th class="right">'.SUB_TITLE_GRAND_TOTAL_EXCL_TAX.'</th>' . "\n";
        echo '      <td class="right">' . $currencies->format($cart->show_shopping_cart_total()) . '</b></td>' . "\n";
        echo '    </tr>' . "\n";   
		
		echo '    <tr>' . "\n";
        echo '      <th class="right">'.SUB_TITLE_TAX.'</th>' . "\n";
        echo '      <td class="right">' . $currencies->format($gst_tax) . '</td>' . "\n";
        echo '    </tr>' . "\n";   
		
		echo '    <tr>' . "\n";
        echo '      <th class="right">'.SUB_TITLE_GRAND_TOTAL_INCL_TAX.'</th>' . "\n";
        echo '      <td class="right">' . $currencies->format($cart->show_shopping_cart_total() + $gst_tax) . '</td>' . "\n";
        echo '    </tr></tbody>' . "\n";
		echo '  </table>' . "\n";
		
      }
	  ?>
	  </div>
	  <?php
           
    if ($any_out_of_stock == 1) {
      if (STOCK_ALLOW_CHECKOUT == 'true') {
        $valid_to_checkout = true;
		echo '<div class="content stockWarning">'.OUT_OF_STOCK_CAN_CHECKOUT.'</div>';
      } else {
		$valid_to_checkout= false;
		echo '<div class="content stockWarning">'.OUT_OF_STOCK_CANT_CHECKOUT.'</div>';
      }
    }

	?>


	  
  <?php

	  // WebMakers.com Added: Shipping Estimator
	  if (SHOW_SHIPPING_ESTIMATOR=='true') {
		// always show shipping table
		?>
		<div class="content">
		  <?php require($additional_module_folder . FILENAME_SHIPPING_ESTIMATOR); ?>
		</div>
		<?php
	  }
	  
 } else {
	
	$nav_link = '<a class="button" href="' . tep_href_link(FILENAME_DEFAULT) . '">Continue Shopping</a>';
	echo '<div class="content">'.TEXT_CART_EMPTY.'</div>';

  }
  
?>
	
	<div class="buttons">
		<?php
		if($valid_to_checkout == true){
			echo '<div class="right"><a class="button" href="'.tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL').'">Checkout</a></div>';
		}
		?>		  
		<div class="center"><?php echo $nav_link; ?></div>
    <div class="clear"></div>
	</div>
</div></div></div></div></div></div>