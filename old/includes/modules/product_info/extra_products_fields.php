    <?php
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
    ?>