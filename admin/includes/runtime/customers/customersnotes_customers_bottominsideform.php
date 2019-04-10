<?php

global $customer_comments,$tmp_cust_arry;
$customer_comments[] = $tmp_cust_arry;


?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo TEXT_ADMIN_COMMENT; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
<?php
    if ( sizeof($customer_comments) > 0 ) {
?>
          <tr>
            <td colspan="2"><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
                <td class="main"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
                <td class="main" align="right"><b><?php echo TABLE_HEADING_ADMIN_NAME; ?></b></td>
              </tr>
<?php
        foreach ($customer_comments as $value) {
?>
              <tr>
                <td class="main" width="150"><?php echo $value['date_added']; ?></td>
                <td class="main"><?php echo $value['comments']; ?></td>
                <td class="main" align="right" width="150"><?php echo $value['admin_name']; ?></td>
              </tr>
<?php
        }
?>
            </table></td>
          </tr>
<?php
    }
?>    
          <tr>
            <td class="main" valign="top"><?php echo ENTRY_COMMENT; ?></td>
            <td class="main"><?php echo tep_draw_textarea_field('admin_commetns', 'soft', '60', '5','','',false) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . tep_image_submit('button_add_note.gif', IMAGE_ADD_NOTE); ?></td>
          </tr>
        </table></td>
      </tr>
