    <?php
    // RCI code start
    echo $cre_RCI->get('global', 'top');
    echo $cre_RCI->get('indexdefault', 'top');
    // RCI code eof
    ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php 
// added for show/hide customer greeting
if (SHOW_CUSTOMER_GREETING=='yes') { 
// added for show/hide customer greeting
?>
          
          <tr>
                        <td class="pageHeading">
             <?php 
               if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_heading_title'])) ) {
                 echo $category['categories_heading_title'];
               } else {
                 echo HEADING_TITLE;
               }
             ?>
            </td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_default.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
            </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main"><?php echo tep_customer_greeting(); ?></td>
          </tr>

<?php

// added for show/hide customer greeting
}
// added for show/hide customer greeting


if (tep_not_null(INCLUDE_MODULE_ONE)) {
echo '<tr><td>';
//include($modules_folder . INCLUDE_MODULE_ONE);
include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFINE_HOMEPAGE);

echo '</td></tr>';
?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>

<?php
} /*
if (tep_not_null(INCLUDE_MODULE_TWO)) {
echo '          <tr>
            <td class="main">';
include($modules_folder . INCLUDE_MODULE_TWO);
echo '</td></tr>';

?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          
<?php
} */
if (tep_not_null(INCLUDE_MODULE_THREE)) {
echo '<tr><td>';
include($modules_folder . INCLUDE_MODULE_THREE);
echo '</td></tr>';

?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
}
if (tep_not_null(INCLUDE_MODULE_FOUR)) {
echo '<tr><td>';
include($modules_folder . INCLUDE_MODULE_FOUR);
echo '</td></tr>';

?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
}
if (tep_not_null(INCLUDE_MODULE_FIVE)) {
echo '<tr><td>';
include($modules_folder . INCLUDE_MODULE_FIVE);
echo '</td></tr>';

?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
}
if (tep_not_null(INCLUDE_MODULE_SIX)) {
echo '<tr><td>';
include($modules_folder . INCLUDE_MODULE_SIX);
echo '</td></tr>';
}
?>
        </table></td>
      </tr>
    </table>
    <?php
    // RCI code start
    echo $cre_RCI->get('indexdefault', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof
    ?>