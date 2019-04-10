<?php
//check to see if there is actually anything to be done here
if ( ($product_info['products_image_sm_1'] != '') || ($product_info['products_image_xl_1'] != '') ||
     ($product_info['products_image_sm_2'] != '') || ($product_info['products_image_xl_2'] != '') ||
     ($product_info['products_image_sm_3'] != '') || ($product_info['products_image_xl_3'] != '') ||
     ($product_info['products_image_sm_4'] != '') || ($product_info['products_image_xl_4'] != '') ||
     ($product_info['products_image_sm_5'] != '') || ($product_info['products_image_xl_5'] != '') ||
     ($product_info['products_image_sm_6'] != '') || ($product_info['products_image_xl_6'] != '') ) {
?>
<!-- // BOF MaxiDVD: Modified For Ultimate Images Pack! //-->
    <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
    <tr>
      <td style="text-align:center;"><table cellpadding="3" cellspacing="3" border="0" align="center">
        <tr>
<?php
    if (($product_info['products_image_sm_1'] != '') && ($product_info['products_image_xl_1'] == '')) {
?>
          <td style="text-align:center;"><?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_1'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?></td>
<?php
    } elseif (($product_info['products_image_sm_1'] != '') && ($product_info['products_image_sm_1'] != '')) {
?>
          <td style="text-align:center;">
            <script type="text/javascript"><!--
              document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id'] . '&image=1') . '\\\')">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_1'], addslashes($product_info['products_name']), ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br><span class="enlarge">' . TEXT_CLICK_TO_ENLARGE . '</span></a>'; ?>');
            //--></script>
            <noscript>
              <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_sm_1']) . '">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_1'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br><span class="enlarge">' . TEXT_CLICK_TO_ENLARGE . '</span></a>'; ?>
            </noscript>
          </td>
<?php
    } elseif ((isset($products_info['products_image_sm_1']) && $products_info['products_image_sm_1'] == '') && ($product_info['products_image_xl_1'] != '')) {
?>
          <td style="text-align:center;">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_1'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
<?php
    } else {
?>
          <td>&nbsp;</td>
<?php
    }

    if (($product_info['products_image_sm_2'] != '') && ($product_info['products_image_xl_2'] == '')) {
?>
          <td style="text-align:center;">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_2'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
<?php
    } elseif (($product_info['products_image_sm_2'] != '') && ($product_info['products_image_sm_2'] != '')) {
?>
          <td style="text-align:center;">
            <script type="text/javascript"><!--
              document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id'] . '&image=2') . '\\\')">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_2'], addslashes($product_info['products_name']), ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br><span class="enlarge">' . TEXT_CLICK_TO_ENLARGE . '</span></a>'; ?>');
            //--></script>
            <noscript>
              <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_sm_2']) . '">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_2'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br><span class="enlarge">' . TEXT_CLICK_TO_ENLARGE . '</span></a>'; ?>
            </noscript>
          </td>
<?php
    } elseif ((isset($products_info['products_image_sm_2']) && $products_info['products_image_sm_2'] == '') && ($product_info['products_image_xl_2'] != '')) {
?>
          <td style="text-align:center;">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_2'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
<?php
    } else {
?>
          <td>&nbsp;</td>
<?php
    }

    if (($product_info['products_image_sm_3'] != '') && ($product_info['products_image_xl_3'] == '')) {
?>
          <td style="text-align:center;">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_3'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
<?php
    } elseif (($product_info['products_image_sm_3'] != '') && ($product_info['products_image_sm_3'] != '')) {
?>
          <td style="text-align:center;">
            <script type="text/javascript"><!--
            document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id'] . '&image=3') . '\\\')">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_3'], addslashes($product_info['products_name']), ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br><span class="enlarge">' . TEXT_CLICK_TO_ENLARGE . '</span></a>'; ?>');
            //--></script>
            <noscript>
              <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_sm_3']) . '">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_3'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br><span class="enlarge">' . TEXT_CLICK_TO_ENLARGE . '</span></a>'; ?>
            </noscript>
          </td>
<?php
    } elseif ((isset($products_info['products_image_sm_3']) && $products_info['products_image_sm_3'] == '') && ($product_info['products_image_xl_3'] != '')) {
?>
          <td style="text-align:center;">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_3'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
<?php
    } else {
?>
          <td>&nbsp;</td>
<?php
    }
?>
        </tr>
        <tr>
<?php
    if (($product_info['products_image_sm_4'] != '') && ($product_info['products_image_xl_4'] == '')) {
?>
          <td style="text-align:center;">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_4'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
<?php
    } elseif (($product_info['products_image_sm_4'] != '') && ($product_info['products_image_sm_4'] != '')) {
?>
          <td style="text-align:center;">
            <script type="text/javascript"><!--
              document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id'] . '&image=4') . '\\\')">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_4'], addslashes($product_info['products_name']), ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br><span class="enlarge">' . TEXT_CLICK_TO_ENLARGE . '</span></a>'; ?>');
            //--></script>
            <noscript>
              <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_sm_4']) . '">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_4'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br><span class="enlarge">' . TEXT_CLICK_TO_ENLARGE . '</span></a>'; ?>
            </noscript>
          </td>
<?php
    } elseif ((isset($products_info['products_image_sm_4']) && $products_info['products_image_sm_4'] == '') && ($product_info['products_image_xl_4'] != '')) {
?>
          <td style="text-align:center;">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_4'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
<?php
    } else {
?>
          <td>&nbsp;</td>
<?php
    }

    if (($product_info['products_image_sm_5'] != '') && ($product_info['products_image_xl_5'] == '')) {
?>
          <td style="text-align:center;">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_5'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
<?php
    } elseif (($product_info['products_image_sm_5'] != '') && ($product_info['products_image_sm_5'] != '')) {
?>
          <td style="text-align:center;">
            <script type="text/javascript"><!--
              document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id'] . '&image=5') . '\\\')">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_5'], addslashes($product_info['products_name']), ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br><span class="enlarge">' . TEXT_CLICK_TO_ENLARGE . '</span></a>'; ?>');
            //--></script>
            <noscript>
              <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_sm_5']) . '">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_5'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br><span class="enlarge">' . TEXT_CLICK_TO_ENLARGE . '</span></a>'; ?>
            </noscript>
          </td>
<?php
    } elseif ((isset($products_info['products_image_sm_5']) && $products_info['products_image_sm_5'] == '') && ($product_info['products_image_xl_5'] != '')) {
?>
          <td style="text-align:center;">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_5'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
<?php
    } else {
?>
          <td>&nbsp;</td>
<?php
    }

    if (($product_info['products_image_sm_6'] != '') && ($product_info['products_image_xl_6'] == '')) {
?>
          <td style="text-align:center;">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_6'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
<?php
    } elseif (($product_info['products_image_sm_6'] != '') && ($product_info['products_image_sm_6'] != '')) {
?>
          <td style="text-align:center;">
            <script type="text/javascript"><!--
              document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id'] . '&image=6') . '\\\')">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_6'], addslashes($product_info['products_name']), ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br><span class="enlarge">' . TEXT_CLICK_TO_ENLARGE . '</span></a>'; ?>');
            //--></script>
            <noscript>
              <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_sm_6']) . '">' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_6'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br><span class="enlarge">' . TEXT_CLICK_TO_ENLARGE . '</span></a>'; ?>
            </noscript>
          </td>
<?php
    } elseif ((isset($products_info['products_image_sm_6']) && $products_info['products_image_sm_6'] == '') && ($product_info['products_image_xl_6'] != '')) {
?>
          <td style="text-align:center;">
            <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_6'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'hspace="1" vspace="1"'); ?>
          </td>
<?php
    } else {
?>
          <td>&nbsp;</td>
<?php
    }
?>
        </tr>
      </table></td>
    </tr>
<!-- // EOF MaxiDVD: Modified For Ultimate Images Pack! //-->
<?php
} // end of initial IF
?>