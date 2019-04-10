<?php
/*
  $Id: product_info.tpl.php,v 1.2.0.0 2008/01/22 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI code start

echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('productinfo', 'top');
// RCI code eof
?>
<style type="text/css">

.error{ color:#FF0000; padding:1px;  }
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
			$("#frm_add_to").validate();	
	});
	                      
</script>

<?php

echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product' . '&' . $params),'post', 'id="frm_add_to" class="cmxform" enctype="multipart/form-data"'); 

?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
  <?php
  // added for CDS CDpath support
  $params = (isset($_SESSION['CDpath'])) ? 'CDpath=' . $_SESSION['CDpath'] : ''; 
  if ($product_check['total'] < 1) {
    ?>
    <tr>
      <td><?php  new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND))); ?></td>
    </tr>

    <tr>
      <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, $params) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <?php
  } else {
    $product_info_query = tep_db_query("select p.products_id, p.badge_data, p.default_product_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);
    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$product_info['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
    if (tep_not_null($product_info['products_model'])) {
      $products_name = $product_info['products_name'] . '&nbsp;<span class="smallText">[' . $product_info['products_model'] . ']</span>';
    } else {
      $products_name = $product_info['products_name'];
    }
    if ($product_has_sub > '0'){ // if product has sub products
      $products_price ='';// if you like to show some thing in place of price add here
    } else {
      $pf->loadProduct($product_info['products_id'],$languages_id);
      $products_price = $pf->getPriceStringShort();
    } // end sub product check
    if (SHOW_HEADING_TITLE_ORIGINAL=='yes') {
      $header_text = '';
      ?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo $products_name; ?></td>
            <td class="pageHeading" align="right"><?php echo $products_price; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php
    } else {
      $header_text =  $products_name .'</td><td class="productlisting-headingPrice">' . $products_price;
    }
    if (MAIN_TABLE_BORDER == 'yes'){
      table_image_border_top(false, false, $header_text);
    }
    ?>
    <tr>
      <td class="main"><table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="main" valign="top">
            <?php if (tep_not_null($product_info['products_image']) || tep_not_null($product_info['products_image_med'])) { ?>
            <table border="0" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td>
                  <?php
                  if ($product_info['products_image_med']!='') {
                    $new_image = $product_info['products_image_med'];
                    $image_width = MEDIUM_IMAGE_WIDTH;
                    $image_height = MEDIUM_IMAGE_HEIGHT;
                  } else {
                    $new_image = $product_info['products_image'];
                    $image_width = SMALL_IMAGE_WIDTH;
                    $image_height = SMALL_IMAGE_HEIGHT;
                  }
                  $popup_avail = tep_not_null($product_info['products_image_lrg']) ? true : false;
                  echo tep_javascript_image(DIR_WS_IMAGES . $new_image, 'product' . $product_info['products_id'], addslashes($product_info['products_name']), $image_width, $image_height, 'hspace="5" vspace="5"', $popup_avail);

                  //ini_set('display_errors', 1);
                  //error_reporting(E_ALL);
                  //var_dump($product_info);
                  $dprid = $product_info['default_product_id'];
                  if ($dprid) {
                    require_once(dirname(dirname(__FILE__)).'/'.TEMPLATE_NAME.'/bd/badge_desc.php');
                    $badge = new Badge($product_info['badge_data']);
                    echo $badge->description();
                  }                  
                  
                  if (isset($_SESSION['affiliate_id'])) {
                    echo '<br><br><a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD, 'individual_banner_id=' . $product_info['products_id'] . '&' . $params) .'" target="_self">' . tep_template_image_button('button_affiliate_build_a_link.gif', LINK_ALT) . ' </a>';       
                  }
                  ?>
                </td>
              </tr>
            </table>
            <?php    }  // end if image
            echo '<p>' .  stripslashes($product_info['products_description']) . '</p>';
            echo tep_draw_separator('pixel_trans.gif', '100%', '10');
            $products_id_tmp = $product_info['products_id'];
            if(tep_subproducts_parent($products_id_tmp)){
              $products_id_query = tep_subproducts_parent($products_id_tmp);
            } else {
              $products_id_query = $products_id_tmp;
            }
            $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . (int)$products_id_query . "' ");
            $products_attributes = tep_db_fetch_array($products_attributes_query);    
            if( ($products_attributes['total'] > 0)&&(intval($product_info['default_product_id']) < 1)) {
              // the tax rate will be needed, so get it once
              $tax_rate = tep_get_tax_rate($product_info['products_tax_class_id']);
              ?>
              <table width="100%" border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td class="main" colspan="2"><strong><?php echo TEXT_PRODUCT_OPTIONS; ?></strong></td>
                </tr>
                <?php 
                $products_options_query = tep_db_query("SELECT pa.options_id, pa.options_values_id, pa.options_values_price, pa.price_prefix, pa.mandatory,  po.options_type, po.options_length, pot.products_options_name, pot.products_options_instruct 
                                                          from " . TABLE_PRODUCTS_ATTRIBUTES  . " AS pa,
                                                               " . TABLE_PRODUCTS_OPTIONS  . " AS po,
                                                               " . TABLE_PRODUCTS_OPTIONS_TEXT  . " AS pot
                                                        WHERE pa.products_id = '" . (int)$products_id_query . "'
                                                          and pa.options_id = po.products_options_id
                                                          and po.products_options_id = pot.products_options_text_id and pot.language_id = '" . (int)$languages_id . "'
                                                        ORDER BY pa.products_options_sort_order, po.products_options_sort_order
                                                      ");
                // Store the information from the tables in arrays for easy of processing
                $options = array();
                $options_values = array();
                while ($po = tep_db_fetch_array($products_options_query)) {
                  //  we need to find the values name
                  if ( $po['options_type'] != 1  && $po['options_type'] != 4 && $po['options_type'] != 5 ) {
                    $options_values_query = tep_db_query("select products_options_values_name, picture  from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id ='". $po['options_values_id'] . "' and language_id = '" . (int)$languages_id . "'");
                    $ov = tep_db_fetch_array($options_values_query);
                  } else {
                    $ov['picture'] = '';
					$ov['products_options_values_name'] = '';
                  }
                  $options[$po['options_id']] = array('name' => $po['products_options_name'],
                                                      'type' => $po['options_type'],
                                                      'length' => $po['options_length'],
                                                      'instructions' => $po['products_options_instruct'],
                                                      'price' => $po['options_values_price'],
                                                      'prefix' => $po['price_prefix'],
													  'mandatory' => $po['mandatory'],
                                                     );

                  $options_values[$po['options_id']][$po['options_values_id']] =  array('name' => stripslashes($ov['products_options_values_name']),
                                                                                        'price' => $po['options_values_price'],
                                                                                        'prefix' => $po['price_prefix'],
																						'mandatory' => $po['mandatory'],
																						'picture' => $ov['picture']);
                } 
				$file_field = 1;
                foreach ($options as $oID => $op_data) {
                  switch ($op_data['type']) {

                    case 5:
                      $attribute_price = $currencies->display_price($op_data['price'], $tax_rate);                    				
                      ?>
                      <tr>
                        <td class="main" colspan="2">
                          <?php	
						  	//Dec 06 2010													
							if($op_data['mandatory']=='1') {
								echo "<span class='red_star'> * </span>";								
							}												  
                          	echo "<b>".$op_data['name'] . ':</b>';
                          	echo ($attribute_price >= 0 ? ' <span class="smallText">' . $op_data['prefix'] . ' ' . $attribute_price . '</span>' : '' );			  							
                          ?>					  
                        </td>
					 </tr>
					 <tr>
					 	<td width="5%" style="border-bottom:1px solid #CCC;">&nbsp;</td>
                        <td class="main" width="90%" style="border-bottom:1px solid #CCC;">													
						  <table align="center" width="100%" border="0">
						    <tr>
							   <td width="20%" valign="top"><?php echo ($op_data['instructions'] != '' ? ' &nbsp;<span class="smallText">' . $op_data['instructions'] . '</span>' : '' ); ?></td>
						       <td width="70%">
							   		 <input type="hidden" id="for_file"	name="<?php echo "id[" . $oID . "][t]"; ?>" />
							 		 <input type="file" name="<?php echo "id[" . $oID . "][t]"; ?>" id="" style="margin-bottom:4px;" 
							 			<?php if($op_data['mandatory']=='1') { echo ' validate="required:true" '; }?> />
							 		 <label for="<?php echo "id[" . $oID . "][t]"; ?>" class="error">Please select file...</label>	
							 		 <br />
							 		 <textarea name="comment[<?php echo $file_field; ?>]" style="width:210px; margin-bottom:4px;"></textarea><br />	
							 
							   </td>
							</tr>
						  </table>			  
						</td>
                      </tr>
					  <tr><td class="main" colspan="2">&nbsp;</td></tr>
                      <?php
					  $file_field = $file_field + 1;
                      break;
					  
					case 1:
                      $maxlength = ( $op_data['length'] > 0 ? ' maxlength="' . $op_data['length'] . '"' : '' );
                      $attribute_price = $currencies->display_price($op_data['price'], $tax_rate);
                      $tmp_html = '<input type="text" name="id[' . $oID . '][t]"' . $maxlength;
					  
					  if($op_data['mandatory']=='1') {
					  	$tmp_html .= ' validate="required:true" ';
					  }
					  $tmp_html .= ' />';
					  
                      ?>
                      <tr>
                        <td class="main" colspan="2">
                          <?php
						  //Dec 06 2010
							if($op_data['mandatory']=='1') {
								echo "<span class='red_star'> * </span>";
							}
							
                          echo "<b>".$op_data['name'] . ':</b>' . ($op_data['instructions'] != '' ? ' &nbsp;<span class="smallText">' . $op_data['instructions'] . '</span>' : '' );
                          echo ($attribute_price >= 0 ? '<br><span class="smallText">' . $op_data['prefix'] . ' ' . $attribute_price . '</span>' : '' );
						  							
                          ?>
						 <label for="<?php echo "id[" . $oID . "][t]"; ?>" class="error">Please Enter <?php echo $op_data['name']; ?></label>
                        </td>
					 </tr>
					 <tr>
					 	<td width="5%" style="border-bottom:1px solid #CCC;">&nbsp;</td>
                        <td class="main" width="90%" style="border-bottom:1px solid #CCC;"><?php echo $tmp_html;  ?></td>
                      </tr>
					  <tr><td class="main" colspan="2">&nbsp;</td></tr>
                      <?php
                      break;

                    case 4:
                      $text_area_array = explode(';',$op_data['length']);
                      $cols = $text_area_array[0];
                      if ( $cols == '' ) $cols = '100%';
                      if (isset($text_area_array[1])) {
                        $rows = $text_area_array[1];
                      } else {
                        $rows = '';
                      }
                      $attribute_price = $currencies->display_price($op_data['price'], $tax_rate);
                      $tmp_html = '<textarea name="id[' . $oID . '][t]" rows="'.$rows.'" cols="'.$cols.'" wrap="virtual" style="width:100%;"';
					  if($op_data['mandatory']=='1') {
					  	$tmp_html .= ' validate="required:true"';
					  }
					  $tmp_html .= ' ></textarea>';
                      ?>
                      <tr>
                        <td class="main" colspan="2">
                          <?php
						  //Dec 06 2010
							if($op_data['mandatory']=='1') {
								echo "<span class='red_star'> * </span>";
							}
							
                          echo "<b>".$op_data['name'] . ':</b>' . ($op_data['instructions'] != '' ? ' &nbsp;<span class="smallText">' . $op_data['instructions'] . '</span>' : '' );
                          echo ($attribute_price >= 0 ? '<br><span class="smallText">' . $op_data['prefix'] . ' ' . $attribute_price . '</span>' : '' );
						
                          ?>
						  <label for="<?php echo "id[" . $oID . "][t]"; ?>" class="error">Please enter <?php echo $op_data['name']; ?></label>
                        </td>
                     </tr>
					 <tr>
					 	<td width="5%" style="border-bottom:1px solid #CCC;">&nbsp;</td>
                        <td class="main" width="90%" style="border-bottom:1px solid #CCC;"><?php echo $tmp_html;  ?></td>
                     </tr>
					 <tr><td class="main" colspan="2">&nbsp;</td></tr>
                      <?php
                      break;

                    case 2:
                      $tmp_html = '';
                      foreach ( $options_values[$oID] as $vID => $ov_data ) {
                        if ( (float)$ov_data['price'] == 0 ) {
                          $price = '&nbsp;';
                        } else {
                          $price = '(&nbsp;' . $ov_data['prefix'] . '&nbsp;' . $currencies->display_price($ov_data['price'], $tax_rate) . '&nbsp;)';
                        }
                        
						$tmp_html .= '<div style="float:left; width:150px; padding:2px; margin:2px;"><input type="radio" name="id[' . $oID . ']" value="' . $vID . '"';						
						if($op_data['mandatory']=='1') {
							$tmp_html .= ' validate="required:true" ';
						}							
						$tmp_html .= ' >&nbsp;' . $ov_data['name'] . '&nbsp;' . $price . '<br />';
						$tmp_html .= tep_image(DIR_WS_IMAGES.'product_attributes/'.$ov_data['picture'], ''). '<br /></div>';
                      } // End of the for loop on the option value
                      ?>
                      <tr>
                        <td class="main" colspan="2">
							<?php 
							//Dec 06 2010
							if($op_data['mandatory']=='1') {
								echo "<span class='red_star'> * </span>";
							}
							echo "<b>".$op_data['name'] . ':</b>' . ($op_data['instructions'] != '' ? ' &nbsp;<span class="smallText">' . $op_data['instructions'] . '</span>' : '' ); 							
							?>	
							<label for="<?php echo 'id[' . $oID . ']'; ?>" class="error">Please select <?php echo $op_data['name']; ?></label>
						</td>
                      </tr>
					 <tr>
					 	<td width="5%" style="border-bottom:1px solid #CCC;">&nbsp;</td>
                        <td class="main" width="90%" style="border-bottom:1px solid #CCC;"><?php echo $tmp_html;  ?></div></td>
                      </tr>
					  <tr><td class="main" colspan="2">&nbsp;</td></tr>
                      <?php
                      break;
					  
                    case 3:
                      $tmp_html = '';
                      $i = 0;
                      foreach ( $options_values[$oID] as $vID => $ov_data ) {
                        if ( (float)$ov_data['price'] == 0 ) {
                          $price = '&nbsp;';
                        } else {
                          $price = '(&nbsp;'.$ov_data['prefix'] . '&nbsp;' . $currencies->display_price($ov_data['price'], $tax_rate).'&nbsp;)';
                        }
                        $tmp_html .= '<div style="float:left; width:150px; padding:2px; margin:2px;"><input type="checkbox" name="id[' . $oID . '][c][' . $i . ']" value="' . $vID . '"';
						if($op_data['mandatory']=='1') {
							$tmp_html .= ' validate="required:true" ';
						}		
						$tmp_html .= '>&nbsp;' . $ov_data['name'] . '&nbsp;' . $price . '<br />';
						$tmp_html .= tep_image(DIR_WS_IMAGES.'product_attributes/'.$ov_data['picture'], ''). '<br /></div>';
                        $i++;
                      }
                      ?>
                      <tr>
                        <td class="main" colspan="2">
							
							<?php 
							//Dec 06 2010
							if($op_data['mandatory']=='1') {
								echo "<span class='red_star'> * </span>";
							}
							echo "<b>".$op_data['name'] . ':</b>' . ($op_data['instructions'] != '' ? ' &nbsp;<span class="smallText">' . $op_data['instructions'] . '</span>' : '' ); 
							
						?>
						<label for="<?php echo "id[" . $oID . "][c][0]"; ?>" class="error">Please Enter <?php echo $op_data['name']; ?></label>
						</td>
                      </tr>
					 <tr>
					 	<td width="5%" style="border-bottom:1px solid #CCC;">&nbsp;</td>
                        <td class="main" width="90%" style="border-bottom:1px solid #CCC;"><?php echo $tmp_html;  ?></td>
                      </tr>
					  <tr><td class="main" colspan="2">&nbsp;</td></tr>
                      <?php
                      break;

                    case 0:
                      $tmp_html = '<select name="id[' . $oID . ']"';
					  if($op_data['mandatory']=='1') {
							$tmp_html .= ' validate="required:true" ';
						}		
					  $tmp_html .= '>';
                      foreach ( $options_values[$oID] as $vID => $ov_data ) {
                        if ( (float)$ov_data['price'] == 0 ) {
                          $price = '&nbsp;';
                        } else {
                          $price = '(&nbsp; '.$ov_data['prefix'] . '&nbsp;' . $currencies->display_price($ov_data['price'], $tax_rate).'&nbsp;)';
                        }
                        $tmp_html .= '<option value="' . $vID . '">' . $ov_data['name'] . '&nbsp;' . $price .'</option>';
                      } // End of the for loop on the option values
                      $tmp_html .= '</select>';
                      ?>
                      <tr>
                        <td class="main" colspan="2">
							<?php 
							//Dec 06 2010
							if($op_data['mandatory']=='1') {
								echo "<span class='red_star'> * </span>";
							}
							echo "<b>".$op_data['name'] . ':</b>' . ($op_data['instructions'] != '' ? ' &nbsp;<span class="smallText">' . $op_data['instructions'] . '</span>' : '' ); 
							
						?>
						<label for="<?php echo 'id[' . $oID . ']'; ?>" class="error">Please select <?php echo $op_data['name']; ?></label>
						</td>
                     </tr>
					 <tr>
					 	<td width="5%" >&nbsp;</td>
                        <td class="main" width="90%"><?php echo $tmp_html;  ?></td>
                      </tr>
					  <tr><td class="main" colspan="2" style="border-bottom:1px solid #CCC;">&nbsp;</td></tr>
                      <?php
                      break;
                  }  //end of switch
                } //end of while
                ?>
              </table>
              <?php
            } // end of ($products_attributes['total'] > 0)
            ?>
          </td>
        </tr>
      </table></td>
    </tr>
    <?php
    if (ULTIMATE_ADDITIONAL_IMAGES == 'enable') { include(DIR_WS_MODULES . 'additional_images.php'); }
    $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$product_info['products_id'] . "'");
    $reviews = tep_db_fetch_array($reviews_query);
    if ($reviews['count'] > 0) {
      ?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo TEXT_CURRENT_REVIEWS . ' ' . $reviews['count']; ?></td>
      </tr>
      <?php
    }
    // Extra Products Fields are checked and presented
    $extra_fields_query = tep_db_query("SELECT pef.products_extra_fields_status as status, pef.products_extra_fields_name as name, ptf.products_extra_fields_value as value
                                        FROM ". TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS ." ptf,
                                             ". TABLE_PRODUCTS_EXTRA_FIELDS ." pef
                                        WHERE ptf.products_id='".(int)$product_info['products_id']."'
                                          and ptf.products_extra_fields_value <> ''
                                          and ptf.products_extra_fields_id = pef.products_extra_fields_id
                                          and (pef.languages_id='0' or pef.languages_id='".$languages_id."')
                                        ORDER BY products_extra_fields_order");
    if ( tep_db_num_rows($extra_fields_query) > 0 ) {
      ?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><table border="0" cellspacing="1" cellpadding="2">
          <?php
          while ($extra_fields = tep_db_fetch_array($extra_fields_query)) {
            if (! $extra_fields['status'])  continue;  // show only enabled extra field
            ?>
            <tr>
              <td class="main" valign="top"><b><?php echo $extra_fields['name']; ?>:&nbsp;</b></td>
              <td class="main" valign="top"><?php echo $extra_fields['value']; ?></td>
            </tr>
            <?php
          }
          ?>
        </table></td>
      </tr>
      <?php
    }
    if (tep_not_null($product_info['products_url'])) {
      ?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT, 'action=url&amp;goto=' . urlencode($product_info['products_url'] . '&' . $params), 'NONSSL', true, false)); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php
    }
    if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
      ?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])); ?></td>
      </tr>
      <?php
    } else {
      ?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_DATE_ADDED, tep_date_long($product_info['products_date_added'])); ?></td>
      </tr>
      <?php
    }
    if (MAIN_TABLE_BORDER == 'yes'){
      table_image_border_bottom();
    }
    // sub product start
    if (STOCK_ALLOW_CHECKOUT =='false') {
      $allowcriteria = "";
    }
    // get sort order
    $csort_order = tep_db_fetch_array(tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'CATEGORIES_SORT_ORDER'"));
    $select_order_by = '';
    switch ($csort_order['configuration_value']) {
      case 'PRODUCT_LIST_MODEL':
        $select_order_by .= 'sp.products_model';
        break;
      case 'PRODUCT_LIST_NAME':
        $select_order_by .= 'spd.products_name';
        break;
      case 'PRODUCT_LIST_PRICE':
        $select_order_by .= 'sp.products_price';
        break;
      default:
        $select_order_by .= 'sp.products_model';
        break;
    }
    $sub_products_sql = tep_db_query("select sp.products_id, sp.products_quantity, sp.products_price, sp.products_tax_class_id, sp.products_image, spd.products_name, spd.products_description, sp.products_model from " . TABLE_PRODUCTS . " sp, " . TABLE_PRODUCTS_DESCRIPTION . " spd where sp.products_parent_id = " . (int)$product_info['products_id'] . " and spd.products_id = sp.products_id and spd.language_id = " . (int)$languages_id . " order by " . $select_order_by);
    if ( tep_db_num_rows($sub_products_sql) > 0 ) {
      if (MAIN_TABLE_BORDER == 'yes'){
        $header_text= '';
        table_image_border_top(false, false, $header_text);
      }
      ?>
      <tr>
        <td align="right"><table>
          <?php
          while ($sub_products = tep_db_fetch_array($sub_products_sql)) {
            $subname = substr( $sub_products['products_name'], strlen( $product_info['products_name'] . ' - ' ));
            $pf->loadProduct($sub_products['products_id'],$languages_id);
            $sub_products_price = $pf->getPriceStringShort();
            ?>
            <tr align="right">
              <td class="productListing-data"><?php if ($sub_products['products_image']) echo tep_image(DIR_WS_IMAGES . $sub_products['products_image'], $subname, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT,'vspace="2" hspace="2"'); ?></td>
              <td class="productListing-data"><b><?php  echo  $subname; ?></b>&nbsp;[<?php echo $sub_products['products_model']; ?>]<br /><?php echo $sub_products['products_description'];?></td>
              <td class="productListing-data"><?php echo  $sub_products_price; ?></td>
              <?php
              if (($sub_products['products_quantity'] == 0) && ( STOCK_ALLOW_CHECKOUT =='false')){
                ?>
                <td class="productListing-data"><?php echo TEXT_ENTER_QUANTITY;?> :  <?php echo TEXT_OUT_OF_STOCK . tep_draw_hidden_field('sub_products_qty[]', '0', 'size="5"') . tep_draw_hidden_field('sub_products_id[]', $sub_products['products_id']);?></td>
                <?php
              } else if ($sub_products['products_quantity'] > 0){
                ?>
                <td class="productListing-data"><?php echo TEXT_ENTER_QUANTITY;?> : <?php echo tep_draw_input_field('sub_products_qty[]', '0', 'size="5"') . tep_draw_hidden_field('sub_products_id[]', $sub_products['products_id']);?></td>
                <?php
              }// end if
              ?>
            </tr>
            <?php
          } // end while
          ?>
        </table></td>
      </tr>
      <?php
      if (MAIN_TABLE_BORDER == 'yes'){
        table_image_border_bottom();
      }
    }
    // sub product_eof
    if ($product_check['total'] > 0) {
      ?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td>
			<?php
			  	$minimum_qty = tep_get_products_min_order_qty($product_info['products_id']);
			 ?>
			 <table border="0" width="100%" cellspacing="0" cellpadding="2">
			  <?php if(PRODUCT_LIST_MIN_ORDER_QTY == 1) { ?>
			  <tr>
			  	<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
				<td colspan="2" valign="middle" align="center">
					<span style="color:#333333;"> Minimum Quantity for this product: <?php echo $minimum_qty; ?> </span>
				</td>
				<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
			  </tr>
			  <?php } ?>
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" valign="middle"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params() . $params) . '">' . tep_template_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS,'align="middle"') . '</a>'; ?></td>
                <?php
                if (DESIGN_BUTTON_WISHLIST == 'true') {
                  echo '<td align="right" class="main" valign="middle"><!-- Begin Wishlist Code -->' . "\n";
                  echo '<script type="text/javascript"><!--' . "\n";
                  echo 'function addwishlist() {' . "\n";
                  echo 'document.cart_quantity.action=\'' . tep_href_link(FILENAME_PRODUCT_INFO, 'action=add_wishlist' . '&' . $params) . '\';' . "\n";
                  echo 'document.cart_quantity.submit();' . "\n";
                  echo '}' . "\n";
                  echo '--></script>' . "\n";
                  echo '<a href="javascript:addwishlist()">' . tep_template_image_button('button_add_wishlist.gif', IMAGE_BUTTON_ADD_WISHLIST,'align="middle"') . '</a>' ;
                  echo '</td><!-- End Wishlist Code -->';
                }
              } // if products_check
              ?>
              <td class="main" align="right" valign="absmiddle"><table border="0" cellspacing="0" cellpadding="0" align="right">
                <tr>
					<td colspan="3"> 
						<!-- Entry for minimum quantity -->
						<label for='cart_quantity' class="error">* Required Minimum Quantity : <?php echo $minimum_qty; ?></label> 
					</td>
				</tr>
				<tr>
                  <?php 
                  if (tep_db_num_rows($sub_products_sql) ==0) {
                    ?>
                    
                    <?php
                            $psp = $product_info['products_id'];      
                            $product_row = tep_db_query("select products.*, products_to_categories.categories_id as cat_id from products inner join products_to_categories on products_to_categories.products_id = products.products_id where products.products_id = ".(int)$psp);                                       
                            $product_row = tep_db_fetch_array($product_row);
                            $dprid = $product_row['default_product_id'];                                 
                            
                    if(!$dprid){?>
                    <td class="main"><?php echo TEXT_ENTER_QUANTITY . ':&nbsp;&nbsp;';?></td>
                    <?php } ?>
                    <td class="main">
					  <?php /* if(!$dprid){ echo tep_draw_input_field('cart_quantity', ($dprid<1)?'1':$_SESSION['cart']->contents[$_GET['products_id']]['qty'], 'size="4" id="cqty" maxlength="4"'.($dprid)?'':'');} */ 						
					  ?>
					  <input type="hidden" name="hidminqty" id="hidminqty" value="<?php echo $product_info['products_min_order_qty']; ?>" />
   					  <input name="cart_quantity" id="cart_quantity" type="text" value="<?php echo (PRODUCT_LIST_MIN_ORDER_QTY == 1)?$minimum_qty:'1'; ?>" size="4" maxlength="9"  
					  
					  <?php if(PRODUCT_LIST_MIN_ORDER_QTY == 1) { echo " validate='required:true, min:".$minimum_qty."'"; }  ?>					  
					  />					
						&nbsp;&nbsp;										
					</td>
                    <?php 
                  }
                  ?>
<td class="main">
<?php 
     //echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_template_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART,'align="absmiddle"'.($dprid)?' onclick="document.location=\'index.php?cPath='.$product_row['cat_id'].'&product_id='.$product_row['products_id'].'&osCsid='.$_GET['osCsid'].'\';return false;"':''); 
                  echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_template_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART,'align="absmiddle"'.($dprid)?' ':'');
?>
</td>
                  
                </tr>
              </table></td>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <?php
    if ( (USE_CACHE == 'true') && !SID) {
      echo tep_cache_also_purchased(3600);
      include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS);
    } else {
      include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS_BUYNOW);
      //include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS); //Modified on Aug 24, 2010
    }
    if (B2B_PRICE_BREAK == 'true') {
      include(DIR_WS_MODULES . FILENAME_PRODUCT_QUANTITY_TABLE);
    }
  }
    ?>
</table>
</form>
<?php
// RCI code start
echo $cre_RCI->get('productinfo', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>