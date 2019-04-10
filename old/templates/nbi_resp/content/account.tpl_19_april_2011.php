    <?php
    // RCI code start
    echo $cre_RCI->get('global', 'top');
    echo $cre_RCI->get('account', 'top');
    // RCI code eof
    ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
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
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
   
      <?php
      // BOF: Lango Added for template MOD
    } else {
      $header_text = HEADING_TITLE;
    }
    // EOF: Lango Added for template MOD
    // BOF: Lango Added for template MOD
    if (MAIN_TABLE_BORDER == 'yes'){
      table_image_border_top(false, false, $header_text);
    }
    // EOF: Lango Added for template MOD
    if ($messageStack->size('account') > 0) {
      ?>
      <tr>
        <td><?php echo $messageStack->output('account'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php
    }
    if (tep_count_customer_orders() > 0) {
      ?>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo OVERVIEW_TITLE; ?></b></td>
            <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '"><u>' . OVERVIEW_SHOW_ALL_ORDERS . '</u></a>'; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" align="center" valign="top" width="130"><?php echo '<b>' . OVERVIEW_PREVIOUS_ORDERS . '</b><br>' . tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <?php
                  $orders_query = tep_db_query("select o.orders_id, o.date_purchased, o.delivery_name, o.delivery_country, o.billing_name, o.billing_country, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' order by orders_id desc limit 3");
                  while ($orders = tep_db_fetch_array($orders_query)) {
                    if (tep_not_null($orders['delivery_name'])) {
                      $order_name = $orders['delivery_name'];
                      $order_country = $orders['delivery_country'];
                    } else {
                      $order_name = $orders['billing_name'];
                      $order_country = $orders['billing_country'];
                    }
                    ?>
                    <!--<tr class="moduleRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php //echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'], 'SSL'); ?>'">-->
					<tr>
                      <td class="main" width="80"><?php echo tep_date_short($orders['date_purchased']); ?></td>
                      <td class="main"><?php echo '#' . $orders['orders_id']; ?></td>
                      <td class="main"><?php echo tep_output_string_protected($order_name) . ', ' . $order_country; ?></td>
                      <td class="main"><?php echo $orders['orders_status_name']; ?></td>
                      <td class="main" align="right"><?php echo $orders['order_total']; ?><br>
                      <?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'], 'SSL') . '">' . tep_template_image_button('small_view.gif', SMALL_IMAGE_BUTTON_VIEW) . '</a>'; ?></td>
					  <td class="main" align="right">
                      <?php echo '<br><a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $orders['orders_id'], 'SSL') . '"TARGET="_blank">' . tep_template_image_button('button_invoice.gif', SMALL_IMAGE_BUTTON_INVOICE) . '</a>'; ?></td>
                    </tr>
                    <?php
                  }
                  ?>
                </table></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php
    }
    ?>
    <tr>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td class="main"><b><?php echo MY_ACCOUNT_TITLE; ?></b></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td width="60"><?php echo tep_image(DIR_WS_IMAGES . 'account_myaccount.png'); ?></td>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                  <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . MY_ACCOUNT_INFORMATION . '</a>'; ?></td>
                </tr>
                <tr>
                  <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . MY_ACCOUNT_ADDRESS_BOOK . '</a>'; ?></td>
                </tr>
                <tr>
                  <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL') . '">' . MY_ACCOUNT_PASSWORD . '</a>'; ?></td>
                </tr>
              </table></td>
              <td width="10" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
    <tr>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td class="main"><b><?php echo MY_ORDERS_TITLE; ?></b></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td width="60"><?php echo tep_image(DIR_WS_IMAGES . 'account_myorders.png'); ?></td>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                  <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . MY_ORDERS_VIEW . '</a>'; ?></td>
                </tr>
              </table></td>
              <td width="10" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
	<tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
	<tr>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td class="main"><b><?php echo MY_FILES_TITLE; ?></b></td>
        </tr>
      </table></td>
    </tr>
	<tr>
      <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td width="60"><?php echo tep_image(DIR_WS_IMAGES . 'account_myfiles.png'); ?></td>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                  <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ACCOUNT_MYFILES, '', 'SSL') . '">' . MY_FILES_VIEW . '</a>'; ?></td>
                </tr>
				 <tr>
                  <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ACCOUNT_MYFILES, '', 'SSL') . '">' . MY_FILES_UPLOAD . '</a>'; ?></td>
                </tr>
              </table></td>
              <td width="10" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>	
    <tr>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td class="main"><b><?php echo EMAIL_NOTIFICATIONS_TITLE; ?></b></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td width="60"><?php echo tep_image(DIR_WS_IMAGES . 'account_email.png'); ?></td>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                  <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') . '">' . EMAIL_NOTIFICATIONS_NEWSLETTERS . '</a>'; ?></td>
                </tr>
                <tr>
                  <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL') . '">' . EMAIL_NOTIFICATIONS_PRODUCTS . '</a>'; ?></td>
                </tr>
              </table></td>
              <td width="10" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <?php
    // RCI code start
    echo $cre_RCI->get('account', 'menu');
    // RCI code eof
    // BOF: Lango Added for template MOD
    if (MAIN_TABLE_BORDER == 'yes'){
      table_image_border_bottom();
    }
    // EOF: Lango Added for template MOD
    ?>
  </table>
  <?php
  // RCI code start
  echo $cre_RCI->get('account', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof
  ?>