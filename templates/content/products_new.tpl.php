<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('productsnew', 'top');
// RCI code eof
?>    
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
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
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_products_new.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>

<?php
}else{
$header_text =  HEADING_TITLE;
}
?>
<?php
   // queries now in the root new_products.php

if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}


?>
   <tr>
        <td>
        <?php 
   
  //decide which product listing to use
   if (PRODUCT_LIST_CONTENT_LISTING == 'column'){
  $listing_method = FILENAME_PRODUCT_LISTING_COL;
  } else {
  $listing_method = FILENAME_PRODUCT_LISTING;
  }
  //Then show product listing
  include(DIR_WS_MODULES . $listing_method); 
   
?>
        </td>
      </tr>
<?php
// RCI code start
echo $cre_RCI->get('productsnew', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
        </td>
      </tr>
<?php
?>
    </table>
<?php 
// RCI code start
echo $cre_RCI->get('productsnew', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>