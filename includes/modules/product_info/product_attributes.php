<?php
            $attributes = new creAttributes();
            if ($attributes->load($load_attributes_for)) {
              $options_HTML = $attributes->get_HTML();
              if (count($options_HTML) > 0) {
              ?>
          <tr>
            <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" colspan="2"><strong><?php echo TEXT_PRODUCT_OPTIONS; ?></strong></td>
              </tr>
<?php
                foreach ($options_HTML as $op_data) {
                ?>
              <tr>
                <td class="main"><?php echo $op_data['label']; ?></td>
                <td class="main"><?php echo $op_data['HTML'];  ?></td>
              </tr>
<?php
                } //end of foreach
                ?>
          </table>
        </td>
        </tr>
<?php
              }  // end of count
            } // end of new attributes
            ?>