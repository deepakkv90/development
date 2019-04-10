<?php
/*
  $Id: sub_products_dropdown.php,v 1.2.0.0 2008/06/20 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (MAIN_TABLE_BORDER == 'yes'){
  $header_text= '';
  table_image_border_top(false, false, $header_text);
}
// remove html, extract only text to stop javascript errors.
function cre_html2txt($string){
  //Allows letters a-z, digits, space (\\040), hyphen (\\-), underscore (\\_) and backslash (\\\\), everything else is removed from the string.
  $allowed = "/[^a-z0-9\\040\\.\\-\\_\\\\]/i";
  $text = preg_replace($allowed,"",$string);
  return $text;
}
$sub_price_array_str = '';
$n=0;
while ($sub_products = tep_db_fetch_array($sub_products_query)) {
  if ($sub_products['products_quantity'] > 0){
    $pf->loadProduct($sub_products['products_id'],$languages_id);
    $sub_dropdown[] = array('id' => $sub_products['products_id'],
                            'text' => substr( $sub_products['products_name'], strlen( $product_info['products_name'] . ' - ' )) 
                           );
    $sub_products_price[] = $pf->getPriceStringShort();
    $sub_products_blurb[] = cre_html2txt($sub_products['products_blurb']);
    $sub_products_image[] = tep_image(DIR_WS_IMAGES . $sub_products['products_image']);
    $sub_price_array_str .= "sub_price[" . $n . "]='<b>" . $pf->getPriceStringShort() . "</b>';" . "\n";
    $sub_products_blurb_array .= "sub_blurb[" . $n . "]='<br>" . cre_html2txt($sub_products['products_blurb']) . "<br>';" . "\n";
    $sub_products_image_array .= "sub_image[" . $n . "]='" . tep_image(DIR_WS_IMAGES . $sub_products['products_image']) . "';" . "\n";
    $n++;
  } 
}
?>
<tr>
  <td align="right"><table align="right" border="0">
    <tr>
      <td align="left" valign="top"><table align="right" border="0">
        <tr>
          <td align="center" valign="top"><div id="sub_products_image" class="main"><?php echo $sub_products_image[0];?></div></td>
          <td align="right" valign="top" class="productListing-data main"><?php if(defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') && PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES == 'True'){?><table width="100%"><?php $load_attributes_for = $sub_products['products_id']; include(DIR_WS_MODULES . 'product_info/product_attributes.php');?></table><br><?php } ?>
            <?php echo tep_draw_pull_down_menu('sub_products_id[]', $sub_dropdown , '', 'onchange="set_sub_price();" id="sub_products_id[]"');?>
            <?php if (PRODUCT_BLURB == 'true') { ?><div id="sub_products_blurb"><br><?php echo $sub_products_blurb[0];?><br></div><?php } ?>
          </td>
        </tr>
      </table></td>
      <td align="right" valign="top" class="main"><div id="sub_products_price" class="main"><b><?php echo $sub_products_price[0];?></b></div>
        <?php 
        if(defined('PRODUCT_INFO_SUB_PRODUCT_PURCHASE') && PRODUCT_INFO_SUB_PRODUCT_PURCHASE == 'Multi'){
          echo tep_draw_input_field('sub_products_qty[]', '1','size="3" id="sub_products_qty_input" style="display:none;"');
        } else {
          echo tep_draw_hidden_field('sub_products_qty[]', '1','id="sub_products_qty_input"');
        }
        ?>
      </td>
    </tr>
  </table></td>
</tr>
<?php
if (MAIN_TABLE_BORDER == 'yes'){
  table_image_border_bottom();
}
?>
<script type="text/javascript">
<!--
var sub_price = new Array() ;
<?php echo $sub_price_array_str; ?>
var sub_blurb = new Array() ;
<?php echo $sub_products_blurb_array; ?>
var sub_image = new Array() ;
<?php echo $sub_products_image_array; ?>

function set_sub_price() {
  selected_idx = document.getElementById('sub_products_id[]').selectedIndex;
  document.getElementById('sub_products_price').innerHTML=sub_price[selected_idx];
<?php if (PRODUCT_BLURB == 'true') { ?>
  document.getElementById('sub_products_blurb').innerHTML=sub_blurb[selected_idx];
<?php } ?>
  document.getElementById('sub_products_image').innerHTML=sub_image[selected_idx];
  document.getElementById('sub_add_check').checked = false;
  document.getElementById('sub_products_qty_input').value = 0;
  <?php
  if(defined('PRODUCT_INFO_SUB_PRODUCT_PURCHASE') && PRODUCT_INFO_SUB_PRODUCT_PURCHASE != 'Multi'){ 
    ?>
    document.getElementById('sub_products_qty_input').style.display = 'none';
    <?php
  }
  ?>
}
function sub_add_chk() {
  sub_products_qty_input.value = (document.getElementById('sub_add_check').checked) ?  '1' : '0';
  <?php
  if(defined('PRODUCT_INFO_SUB_PRODUCT_PURCHASE') && PRODUCT_INFO_SUB_PRODUCT_PURCHASE == 'Multi'){ 
    ?>
    sub_products_qty_input.style.display = (document.getElementById('sub_add_check').checked) ? '' : 'none';
    <?php
  }
  ?>
}
function check_qty() {
  if (document.getElementById('sub_products_qty[]').value > '0') {
    return true;
  } else {
    alert('<?php echo TEXT_ALERT_QTY; ?>');
    return false;
  }
}
//-->
</script>