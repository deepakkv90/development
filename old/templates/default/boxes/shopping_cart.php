<?php
/*
  $Id: shopping_cart.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
//declare and intilize variables
$products = '';
$cart_contents_string = '';
$new_products_id_in_cart = '';
?>
<!-- shopping_cart //--><!---flotting shopping cart add on 23/5/2013-->
<tr>
  <td>
<script type="text/javascript">

	
	$(function(){ // document ready
	if (!!$('#sidebar').offset()) { // make sure ".sticky" element exists
	var stickyTop = $('#sidebar').offset().top; // returns number
	$(window).scroll(function(){ // scroll event
	var windowTop = $(window).scrollTop(); // returns number
	if (stickyTop < windowTop){
        $('#sidebar').css({ position: 'fixed'});
      }
      else {
        $('#sidebar').css('position','static');
      }
	});
	}
 });<!--
function couponpopupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=280,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<style type="text/css">
	#sidebar {  width:207px; top:0px;  z-index: 1000;}
	.boxmenu:hover
	{
	background-color: rgb(255, 255, 255);
color:#CD5C5C;
	}

 </style>
<div id="sidebar">
    <?php
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => '<div style="bacground-color:#FFF;"><font color="' . $font_color . '">' . BOX_HEADING_SHOPPING_CART . '</font></div>');
    
    new $infobox_template_heading($info_box_contents, tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'), ((isset($column_location) && $column_location !='') ? $column_location : '') ); 
    
    if ($cart->count_contents() > 0) {
          
      $cart_contents_string = '<div style="height:100px; overflow:auto; background-color: rgb(255, 255, 255);"><table border="0" width="100%" cellspacing="0" cellpadding="0"
   style=" background-color: rgb(255, 255, 255);"
>';
      
      $products = $cart->get_products();
      
      for ($i=0, $n=sizeof($products); $i<$n; $i++) {
        
        $psp = explode('{zname}', $products[$i]['id']);      
        $product_row = tep_db_query("select products.*, products_to_categories.categories_id as cat_id from products inner join products_to_categories on products_to_categories.products_id = products.products_id where products.products_id = ".(int)$psp[0]);
        $product_row = tep_db_fetch_array($product_row);
        $dprid = $product_row['default_product_id'];
      
        $db_sql = "select products_parent_id from " . TABLE_PRODUCTS . " where products_id = " . (int)$products[$i]['id'];
        $products_parent_id = tep_db_fetch_array(tep_db_query($db_sql));
        
            
        $cart_contents_string .= '<tr><td width:"50%" class="boxmenu" style="font-size:11px; padding:10px;">';
        
        $cart_contents_string .= "<img src='image_thumb.php?file=images/".$products[$i]['image']."&sizex=150&sizey=30'>". "<br><br>";
        
        $edit_string = "";
        
        if ($dprid) {
            
            $cart_contents_string .= $products[$i]['quantity'] . '&nbsp;x&nbsp;<a href="'.tep_href_link('index.php', 'cPath='.$product_row['cat_id'].'&product_id='.$product_row['products_id'].'&osCsid='.$_GET['osCsid']).'">';
            
            $edit_string = '<a href="'.tep_href_link('index.php','cPath='.$product_row['cat_id'].'&product_id='.$product_row['products_id'].'&osCsid='.$_GET['osCsid']).'">'.tep_template_image_button('small_edit.gif', IMAGE_BUTTON_EDIT) .'</a><br><br>';
            
            
        } else {   
            if ((int)$products_parent_id['products_parent_id'] != 0) {
              
              $cart_contents_string .= $products[$i]['quantity'] . '&nbsp;x&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_parent_id['products_parent_id']) . '">';
              
                
            } else {
              
              $cart_contents_string .= $products[$i]['quantity'] . '&nbsp;x&nbsp;</span><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">';
              
            }
        }
        
        $cart_contents_string .= $products[$i]['name'] . '</a><br></td>
                            
                                    <td width:"50%" class="boxmenu" style="font-size:11px; padding:10px;">';
                                    
        $cart_contents_string .= $edit_string . $currencies->display_price($products[$i]['final_price'],'',$products[$i]['quantity']);
                                        
        $cart_contents_string .= '</td>
                                </tr>'; 
        
        $cart_contents_string .= '<tr> <td colspan="2" ><hr style="border-bottom:1px solid #CCC; border-style:dotted;"></td></tr>';
        
      }
                                      
      $cart_contents_string .= '</table></div>';
      
    } else {
          
      $cart_contents_string .= "<div class='boxmenu' style='padding:10px;'>".BOX_SHOPPING_CART_EMPTY."</div>";
      
    }
    
    $info_box_contents = array();
    
    $info_box_contents[] = array('text' => $cart_contents_string);
    
    if ($cart->count_contents() > 0) {
      $sub_total = $cart->show_total();
      if ($sub_total == 0) {
        $sub_total = 'Free';
      } else {
        $sub_total = $currencies->format($cart->show_total());
      }
      //$info_box_contents[] = array('text' => tep_draw_separator());
      $info_box_contents[] = array('align' => 'right',
                                   'text' => "<div class='boxmenu' style='padding:10px;background-color: rgb(255, 255, 255);'><table align='center' width='100%'><tr><td width='50%'><b>Total</b></td><td width='50%' align='right'>".$sub_total."</td></tr></table></div>");
      $info_box_contents[] = array('text' => tep_draw_separator());
      
      $info_box_contents[] = array('text' => '<div class="boxmenu" style="text-align:center; padding:10px;background-color: rgb(255, 255, 255);">
                                    <font align="center">' . '<a href="' . tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL') . '">' .'<img src="templates/nbi_au/images/buttons/english/view_my_cart.JPG" alt="shopping_cart "></img>'. '</a></font>
                                    </div>');
                                         
      // RCI insert offset
      $offset_amount = 0;
      $final_total = $currencies->format($cart->show_total() + $offset_amount);
      $returned_rci = $cre_RCI->get('shoppingcart', 'infoboxoffsettotal');
      if (($returned_rci != NULL) && $offset_amount != 0) {
        $info_box_contents[] = array('text' => $returned_rci);    
      }
    }
    if ( isset($_SESSION['customer_id']) ) {
      $gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $_SESSION['customer_id'] . "'");
      $gv_result = tep_db_fetch_array($gv_query);
      if ($gv_result['amount'] > 0 ) {
        $info_box_contents[] = array('align' => 'left','text' => tep_draw_separator());
        $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext">' . VOUCHER_BALANCE . '</td><td class="smalltext" align="right" valign="bottom">' . $currencies->format($gv_result['amount']) . '</td></tr></table>');
        $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext"><a href="'. tep_href_link(FILENAME_GV_SEND) . '">' . BOX_SEND_TO_FRIEND . '</a></td></tr></table>');
      }
    }
    if (isset($_SESSION['gv_id']) ) {
      $gv_query = tep_db_query("select coupon_amount from " . TABLE_COUPONS . " where coupon_id = '" . $_SESSION['gv_id'] . "'");
      $coupon = tep_db_fetch_array($gv_query);
      $info_box_contents[] = array('align' => 'left','text' => tep_draw_separator());
      $info_box_contents[] = array('align' => 'left','text' => '<table cellpadding="0" width="100%" cellspacing="0" border="0"><tr><td class="smalltext">' . VOUCHER_REDEEMED . '</td><td class="smalltext" align="right" valign="bottom">' . $currencies->format($coupon['coupon_amount']) . '</td></tr></table>');
    }
    if (isset($_SESSION['cc_id']) && tep_not_null($_SESSION['cc_id'])) {
      $cart_coupon_query = tep_db_query("select coupon_code, coupon_type from " . TABLE_COUPONS . " where coupon_id = '" . (int)$_SESSION['cc_id'] . "'");
      $cart_coupon_info = tep_db_fetch_array($cart_coupon_query);
      $info_box_contents[] = array('align' => 'left','text' => tep_draw_separator());
      $info_box_contents[] = array('align' => 'left','text' => CART_COUPON . ' ' . $cart_coupon_info['coupon_code'] . ' <a href="javascript:couponpopupWindow(\'' . tep_href_link(FILENAME_POPUP_COUPON_HELP, 'cID=' . $_SESSION['cc_id']) . '\')">' . tep_image(DIR_WS_ICONS . 'warning.gif', CART_COUPON_INFO) . '</a>');
      if($cart_coupon_info['coupon_type'] == 'F') {
        $info_box_contents[] = array('align' => 'center','text' => 'Free Shipping');
      }
    }
    new $infobox_template($info_box_contents, true, true, ((isset($column_location) && $column_location !='') ? $column_location : '') );
    if (TEMPLATE_INCLUDE_FOOTER =='true'){
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left',
                                   'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                  );
      new $infobox_template_footer($info_box_contents, ((isset($column_location) && $column_location !='') ? $column_location : '') );
    }
    ?>
	</div>
  </td>
</tr>
<!-- shopping_cart_eof //-->