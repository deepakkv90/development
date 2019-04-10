<?php
 /*
  $Id: fss_fp_contact_us.tpl.php,v 1.0.0.0 2008/06/17 10:20:48 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI top
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('fssfpcontactus', 'top');
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <?php
  if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
    $header_text = '&nbsp;';
    $form_name = tep_db_fetch_array(tep_db_query("select form_name from " . TABLE_FSS_FORMS . " where forms_id = '" . $forms_id . "'"));
    ?>
    <tr>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><?php echo $form_name['form_name']; ?></td>
          <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_wishlist.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
    <?php
  } else {
    $header_text = HEADING_TITLE;
  }
  if ($messageStack->size('fp_contact_us') > 0 && $error) {
    ?>
    <tr>
      <td><?php echo $messageStack->output('fp_contact_us'); ?></td>
    </tr>
    <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
    <?php
  }
  ?>
  <tr>
    <td width="100%">
      <div class=Section1>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <?php
          if (MAIN_TABLE_BORDER == 'yes'){
            table_image_border_top(false, false, $header_text);
          }
          ?>
          <td style="padding: 5px 5px 5px 5px">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
              <tbody>
                <tr colspan="2">
                  <td height="40" class="pageHeading" colspan="2"><?php echo HEADING_TITLE; ?></td>
                </tr>
                <tr>
                  <td colspan="2" width="100%"><table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                      <td width="30%" align="center" valign="top">
                        <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . tep_get_product_path($product_info['products_id']) . '&products_id=' . $product_info['products_id'], 'NONSSL'); ?>"><?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT); ?></a>
                      </td>
                      <td valign="top"><table border="0" cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                          <td height="40" width="20%" class="main" valign="top" height="20"><?php echo BOX_TEXT_PRODUCTS_NAME?></td><td class="main" valign="top" height="20"><?php echo $product_info['products_name']?></td>
                        </tr>
                        <tr>
                          <td height="40" width="20%" class="main" valign="top" height="20"><?php echo BOX_TEXT_PRODUCTS_MODEL?></td><td class="main" valign="top" height="20"><?php echo $product_info['products_model']?></td>
                        </tr>
                        <tr>
                          <td height="40" width="20%" class="main" valign="top" height="20"><?php echo BOX_TEXT_PRODUCTS_PRICE?></td><td class="main" valign="top"><?php echo $currencies->format($product_info['products_price'])?></td>
                        </tr>
                      </table></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="2"><?php echo tep_draw_separator('pixel_black.gif', '100%', '1'); ?></td>
                </tr>
                <?php
                if (!isset($_GET['action']) || $error) {
                  echo tep_draw_form('fp_contact_us', tep_href_link(FILENAME_FSS_FORMPOST_CONTACT_US, tep_get_all_get_params(array('action')) . 'action=send_post'), 'post', 'enctype="multipart/form-data"').tep_draw_hidden_field('products_id', $_GET['products_id']) . tep_draw_hidden_field('products_price', $product_info['products_price']);
                  $info_box_contents = array();
                  $info_box_contents[] = array('text' => BOX_TEXT_REQUEST_BETTERPRICE);
                  new infoBoxHeading($info_box_contents, false, false);
                  $info_box_contents = array();
                  $forms_fields_query = tep_db_query("SELECT ff.fields_name, ff.fields_required 
                                                        from " . TABLE_FSS_FIELDS_TO_FORMS . " f2f, 
                                                             " . TABLE_FSS_FORMS_FIELDS . " ff 
                                                      WHERE f2f.forms_id ='" . $forms_id . "' 
                                                        and ff.fields_id = f2f.fields_id 
                                                      ORDER BY ff.sort_order");  
                  $content = '<table width="100%">';
                  while($forms_field = tep_db_fetch_array($forms_fields_query)) {
                    if ($forms_field['fields_required'] == '1') {
                      $fields_required = '&nbsp;<font color="red">*</font>';
                    } else {
                      $fields_required = '';
                    }
                    $content.="<tr>";
                    $content .= '<td height="40" width="15%" class="main">' . $forms_field['fields_name'].': </td><td class="main" width="35%">';
                    $content .= tep_draw_input_field('fields_'.str_replace(' ', '_', $forms_field['fields_name'])) . $fields_required . '</td>';
                    $content.="</tr>";
                  }
                  $content .= '</table>';
                  $info_box_contents[] = array('align' => 'left', 'text' => $content);
                  unset($forms_fields_query); unset($forms_field);
                  new infoBox($info_box_contents);
                  ?>
                  <tr>
                    <td colspan="2" width="20%" height="40" align="center" class="main"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                  </tr>
                  <?php    
                } else if ($_GET['action'] == 'send_post') {
                  ?>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td align="center" class="main" colspan="2"><h3><?php echo TEXT_REQUEST_BETTERPRICE_INFO; ?></h3></td>
                  </tr>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td align="center" class="main" colspan="2"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['products_id']); ?>"><?php echo tep_image_button('button_back.gif', IMAGE_BUTTON_BACK); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo tep_href_link(FILENAME_DEFAULT); ?>"><?php echo tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></a></td>
                  </tr>
                  <?php
                }
                ?>
                </form>
              </tbody>
            </table>
          </td>
          <?php
            if (MAIN_TABLE_BORDER == 'yes'){
              table_image_border_bottom();
            }
          ?>
        </table>
      </div>
    </td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
</table>
<?php
// RCI bottom
echo $cre_RCI->get('fssfpcontactus', 'bottom');
echo $cre_RCI->get('global', 'bottom');
?>