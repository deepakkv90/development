   <?php 
    // RCI code start
    echo $cre_RCI->get('global', 'top');
    echo $cre_RCI->get('login', 'top');
    // RCI code eof
    echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL')); ?>
    <?php
    if ($messageStack->size('login') > 0) {
      ?>
        <div class="message-stack"><?php echo $messageStack->output('login'); ?></div>
      <?php
    }
    if ($cart->count_contents() > 0) {
      ?>
        <div class="smallText"><?php echo TEXT_VISITORS_CART; ?></div>
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
    </form>
    <?php 
    // RCI code start
    echo $cre_RCI->get('login', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof
    ?>