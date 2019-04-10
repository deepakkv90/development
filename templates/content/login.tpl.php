   <?php 
    // RCI code start
    echo $cre_RCI->get('global', 'top');
    echo $cre_RCI->get('login', 'top');
    // RCI code eof
    echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL')); ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
    <?php
    if ($messageStack->size('login') > 0) {
      ?>
      <tr>
        <td><?php echo $messageStack->output('login'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php
    }
    if ($cart->count_contents() > 0) {
      ?>
      <tr>
        <td class="smallText"><?php echo TEXT_VISITORS_CART; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php
    }
    if (PWA_ON == 'false') {
      require(DIR_WS_INCLUDES . FILENAME_PWA_ACC_LOGIN);
    } else {
      require(DIR_WS_INCLUDES . FILENAME_PWA_PWA_LOGIN);
    }
    // RCI code start
    echo $cre_RCI->get('login', 'insideformbelowbuttons');
    // RCI code eof    
    ?>
    </table></form>
    <?php 
    // RCI code start
    echo $cre_RCI->get('login', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof
    ?>