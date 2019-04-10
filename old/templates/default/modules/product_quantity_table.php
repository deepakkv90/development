<?php
/*
  $Id: product_quantity_table.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

if(!isset($_SESSION['sppc_customer_group_id'])) {
  $customer_group_id = 'G';
  } else {
   $customer_group_id = $_SESSION['sppc_customer_group_id'];
  }

$defaultQtyDiscount=10;
$product_query = tep_db_query("select  products_price, products_price1, products_price2, products_price3, products_price4, products_price5, products_price6, products_price7, products_price8, products_price9, products_price10, products_price11, products_price1_qty, products_price2_qty, products_price3_qty, products_price4_qty, products_price5_qty, products_price6_qty, products_price7_qty, products_price8_qty, products_price9_qty, products_price10_qty, products_price11_qty,products_tax_class_id from " . TABLE_PRODUCTS . " where products_id = '" . (int)$_GET['products_id'] . "'");
   $product = tep_db_fetch_array($product_query);
   $product_tax_id=$product['products_tax_class_id'];
   $products_price_qty1 = $product['products_price1_qty'];          
   if ($product['products_price1_qty'] == 0) {
     $products_price_qty1 = 2;
   }
   $products_price_qty2 = $product['products_price2_qty'];
   if ($product['products_price2_qty'] == 0 || $product['products_price2_qty'] <= $product['products_price1_qty']) {
     $products_price_qty2 = $defaultQtyDiscount;
   }
   $products_price_qty3 = $product['products_price3_qty'];
   if ($product['products_price3_qty'] == 0 || $product['products_price3_qty'] <= $product['products_price2_qty']) {
     $products_price_qty3 = 2 * $defaultQtyDiscount;
   }
   $products_price_qty4 = $product['products_price4_qty'];
   if ($product['products_price4_qty'] == 0 || $product['products_price4_qty'] <= $product['products_price3_qty']) {
     $products_price_qty4 = 3 * $defaultQtyDiscount;
   }
   $products_price_qty5 = $product['products_price5_qty'];
   if ($product['products_price5_qty'] == 0 || $product['products_price5_qty'] <= $product['products_price4_qty']) {
     $products_price_qty5 = 4 * $defaultQtyDiscount;
   }
   $products_price_qty6 = $product['products_price6_qty'];
   if ($product['products_price6_qty'] == 0 || $product['products_price6_qty'] <= $product['products_price5_qty']) {
     $products_price_qty6 = 5 * $defaultQtyDiscount;
   }
   $products_price_qty7 = $product['products_price7_qty'];
   if ($product['products_price7_qty'] == 0 || $product['products_price7_qty'] <= $product['products_price6_qty']) {
     $products_price_qty7 = 6 * $defaultQtyDiscount;
   }
   $products_price_qty8 = $product['products_price8_qty'];
   if ($product['products_price8_qty'] == 0 || $product['products_price8_qty'] <= $product['products_price7_qty']) {
     $products_price_qty8 = 7 * $defaultQtyDiscount;
   }
   $products_price_qty9 = $product['products_price9_qty'];
   if ($product['products_price9_qty'] == 0 || $product['products_price9_qty'] <= $product['products_price8_qty']) {
     $products_price_qty9 = 8 * $defaultQtyDiscount;
   }
   $products_price_qty10 = $product['products_price10_qty'];
   if ($product['products_price10_qty'] == 0 || $product['products_price10_qty'] <= $product['products_price9_qty']) {
     $products_price_qty10 = 9 * $defaultQtyDiscount;
   }
   $products_price_qty11 = $product['products_price11_qty'];
   if ($product['products_price11_qty'] == 0 || $product['products_price11_qty'] <= $product['products_price10_qty']) {
     $products_price_qty11 = 10 * $defaultQtyDiscount;
   }
 
 $products_price0= $product['products_price'];
 $products_price1= $product['products_price1'];
 if ($product['products_price1'] == 0) {
   $products_price1 = $product['products_price'];
 }
 $products_price2= $product['products_price2'];
 if ($product['products_price2'] == 0) {
   $products_price2 = $products_price1;
 }
 $products_price3= $product['products_price3'];
 if ($product['products_price3'] == 0) {
   $products_price3 = $products_price2;
 }
 $products_price4= $product['products_price4'];
 if ($product['products_price4'] == 0) {
   $products_price4 = $products_price3;
 }
 $products_price5= $product['products_price5'];
 if ($product['products_price5'] == 0) {
   $products_price5 = $products_price4;
 }
 $products_price6= $product['products_price6'];
 if ($product['products_price6'] == 0) {
   $products_price6 = $products_price5;
 }
 $products_price7= $product['products_price7'];
 if ($product['products_price7'] == 0) {
   $products_price7 = $products_price6;
 }
 $products_price8= $product['products_price8'];
 if ($product['products_price8'] == 0) {
   $products_price8 = $products_price7;
 }
 $products_price9= $product['products_price9'];
 if ($product['products_price9'] == 0) {
   $products_price9 = $products_price8;
 }
 $products_price10= $product['products_price10'];
 if ($product['products_price10'] == 0) {
   $products_price10 = $products_price9;
 }
 $products_price11= $product['products_price11'];
 if ($product['products_price11'] == 0) {
   $products_price11 = $products_price10;
 }
//for other then retail
if($customer_group_id != 0)
{
  $sql1="select  pg.customers_group_price ,
  pg.customers_group_price1 ,
  pg.customers_group_price2 ,
  pg.customers_group_price3 ,
  pg.customers_group_price4 , 
  pg.customers_group_price5 ,
  pg.customers_group_price6 ,
  pg.customers_group_price7 ,
  pg.customers_group_price8 ,
  pg.customers_group_price9 ,
  pg.customers_group_price10 ,
  pg.customers_group_price11" . "
  from " .TABLE_PRODUCTS_GROUPS ." pg " .    
   " where  pg.products_id = '". (int)$_GET['products_id'] ."'".
   "   and pg.customers_group_id = '". $customer_group_id ."'"  ;
  $scustomer_group_price = tep_db_query($sql1); 
  if(tep_db_num_rows($scustomer_group_price)>0)
  {
    $scustomer_group_price= tep_db_fetch_array($scustomer_group_price); 
    $products_price0=$scustomer_group_price['customers_group_price'];
    $products_price1=$scustomer_group_price['customers_group_price1'];
    $products_price2=$scustomer_group_price['customers_group_price2'];
    $products_price3=$scustomer_group_price['customers_group_price3'];
    $products_price4=$scustomer_group_price['customers_group_price4'];
    $products_price5=$scustomer_group_price['customers_group_price5'];
    $products_price6=$scustomer_group_price['customers_group_price6'];
    $products_price7=$scustomer_group_price['customers_group_price7'];
    $products_price8=$scustomer_group_price['customers_group_price8'];
    $products_price9=$scustomer_group_price['customers_group_price9'];
    $products_price10=$scustomer_group_price['customers_group_price10'];
    $products_price11=$scustomer_group_price['customers_group_price11'];    
  } 
}


# Checking if the All the price is same as the Base Price
// becuase of the way PHP handles internal numbers, the results must be
// cast to matching types to allow a good comparsion in all cases.
if($products_price0 == (string)(($products_price0+$products_price1+$products_price2+$products_price3+$products_price4+$products_price5+$products_price6+$products_price7+$products_price8+$products_price9+$products_price10+$products_price11)/12))
{}
else
{

  $products_price0=$currencies->display_price(sprintf("%01.2f",$products_price0),tep_get_tax_rate($product_tax_id));
  $products_price1=$currencies->display_price(sprintf("%01.2f",$products_price1),tep_get_tax_rate($product_tax_id));
  $products_price2=$currencies->display_price(sprintf("%01.2f",$products_price2),tep_get_tax_rate($product_tax_id));
  $products_price3=$currencies->display_price(sprintf("%01.2f",$products_price3),tep_get_tax_rate($product_tax_id));
  $products_price4=$currencies->display_price(sprintf("%01.2f",$products_price4),tep_get_tax_rate($product_tax_id));
  $products_price5=$currencies->display_price(sprintf("%01.2f",$products_price5),tep_get_tax_rate($product_tax_id));
  $products_price6=$currencies->display_price(sprintf("%01.2f",$products_price6),tep_get_tax_rate($product_tax_id));
  $products_price7=$currencies->display_price(sprintf("%01.2f",$products_price7),tep_get_tax_rate($product_tax_id));
  $products_price8=$currencies->display_price(sprintf("%01.2f",$products_price8),tep_get_tax_rate($product_tax_id));
  $products_price9=$currencies->display_price(sprintf("%01.2f",$products_price9),tep_get_tax_rate($product_tax_id));
  $products_price10=$currencies->display_price(sprintf("%01.2f",$products_price10),tep_get_tax_rate($product_tax_id));
  $products_price11=$currencies->display_price(sprintf("%01.2f",$products_price11),tep_get_tax_rate($product_tax_id));

    
      $info_box_contents = array();
      $info_box_contents[] = array('text' => TEXT_QUALITY_PRICE_CHART);
      new contentBoxHeading($info_box_contents);

    $price_array = array();
    $price_array[] = $products_price0;
    $price_array[] = $products_price1;
    $price_array[] = $products_price2;
    $price_array[] = $products_price3;
    $price_array[] = $products_price4;
    $price_array[] = $products_price5;
    $price_array[] = $products_price6;
    $price_array[] = $products_price7;
    $price_array[] = $products_price8;
    $price_array[] = $products_price9;
    $price_array[] = $products_price10;
    $price_array[] = $products_price11;

    $qty_array = array();
    $qty_array[] = $products_price_qty0;
    $qty_array[] = $products_price_qty1;
    $qty_array[] = $products_price_qty2;
    $qty_array[] = $products_price_qty3;
    $qty_array[] = $products_price_qty4;
    $qty_array[] = $products_price_qty5;
    $qty_array[] = $products_price_qty6;
    $qty_array[] = $products_price_qty7;
    $qty_array[] = $products_price_qty8;
    $qty_array[] = $products_price_qty9;
    $qty_array[] = $products_price_qty10;
    $qty_array[] = $products_price_qty11;

$quantitytable = '<!-- QTY TABLE GENERATION BOF -->' . "\n";
for($i = 0;$i <= PRODUCT_QTY_PRICE_LEVEL ; $i++){
  if($i == 0){
      $quantitytable .= '<table border="0" cellspacing="1" cellpadding="2" width="100%">' . "\n"; 
      $quantitytable .= '  <tr>' . "\n"; 
      $quantitytable .= '    <td class="smalltext">Base</td>' . "\n"; 
      $quantitytable .= '    <td class="smalltext" align="right" valign="top">' . $price_array[0] . '</td>' . "\n"; 
      $quantitytable .= '  </tr>' . "\n";

  } else  if($i == 1){
      $quantitytable .= '  <tr>' . "\n"; 
      $quantitytable .= '    <td class="smalltext">' . $qty_array[$i]. ' - ' . $qty_array[$i+1] . '</td>' . "\n"; 
      $quantitytable .= '    <td class="smalltext" align="right" valign="top">' . $price_array[$i] . '</td>' . "\n"; 
      $quantitytable .= '  </tr>' . "\n";

  } else {
      $quantitytable .= '  <tr>' . "\n"; 
      $quantitytable .= '    <td class="smalltext">' . ($qty_array[$i]+1). ' - ';
        if($i == PRODUCT_QTY_PRICE_LEVEL){
            $quantitytable .= 'Above</td>' . "\n"; 
            $quantitytable .= '    <td class="smalltext" align="right" valign="top">' . $price_array[PRODUCT_QTY_PRICE_LEVEL] . '</td>' . "\n"; 
        } else {
            $quantitytable .= $qty_array[$i+1] . '</td>' . "\n"; 
            $quantitytable .= '    <td class="smalltext" align="right" valign="top">' . $price_array[$i] . '</td>' . "\n"; 
        }
      $quantitytable .= '  </tr>' . "\n"; 
  }
 if($i == 5){
  $quantitytable .='</table></td><td valign="top"><table border="0" align="right" cellspacing="0" cellpadding="0" width="100%">' . "\n";
 }
}
$quantitytable .='</table>' . "\n";
$quantitytable .='<!-- QTY TABLE GENERATION EOF -->' . "\n";
    
    $info_box_contents[0][0] = array('align' => 'center',
                                               'params' => 'valign="top"',
                                               'text' => $quantitytable);
                       
    new contentBox($info_box_contents, true, true);
    
    if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){
        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
        new contentBoxFooter($info_box_contents);
  }
  
}
?>