<?php
 /*
  $Id: fss_forms_detail.tpl.php,v 1.0.0.0 2008/06/17 10:20:48 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI top
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('fssformsdetail', 'top');
?>
<!-- fss_forms_detail.tpl.php-->
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">
              <?php
              echo $forms['forms_name'];
              ?>
            </td>                
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main">
              <?php
              echo $forms['forms_description'];
              ?>            
            </td>                
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php
      if ($messageStack->size('forms_post') > 0) {
        ?>
        <tr>
          <td><?php echo $messageStack->output('forms_post'); ?></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
        <?php
      }
      $questions = tep_fss_get_forms_questions($forms_id);
      echo tep_draw_form('Reseller_Inquiry', tep_href_link(FILENAME_FSS_FORMS_DETAIL, tep_get_all_get_params(array('action')) . 'action=submit'), 'post', 'onSubmit="return check_form(this);" enctype="multipart/form-data"');
      if (sizeof($questions) > 0) {
        ?>
        <tr>          
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td align="left"><table border="0" cellspacing="0" cellpadding="2">
                <?php      
                foreach ($questions as $value) {
                  if ($value['questions_type'] == 'Hidden') {
                    ?>
                    <tr>
                      <td class="main" colspan="2"><?php echo $value['html']['str']; ?></td>
                    </tr>
                    <?php
                  } else {
                    ?>
                    <tr>
                      <td class="main"><?php echo $value['questions_label']; ?>: </td>
                      <td class="main"><?php echo $value['html']['str']; ?></td>
                    </tr>
                    <?php
                  }
                }
                if ($forms['enable_vvc'] == '1') {
                  ?>
                  <tr>
                    <td class="main"><?php echo VISUAL_VERIFY_CODE_TEXT_INSTRUCTIONS; ?></td>
                    <td class="main">
                      <table>
                        <tr>                    
                          <?php
                          echo '<td>' . tep_draw_input_field('visual_verify_code') . '&nbsp;' . '<span class="inputRequirement">' . VISUAL_VERIFY_CODE_ENTRY_TEXT . '</span></td>'; 
                          $visual_verify_code = ""; 
                          for ($i = 1; $i <= rand(3,6); $i++){
                            $visual_verify_code = $visual_verify_code . substr(VISUAL_VERIFY_CODE_CHARACTER_POOL, rand(0, strlen(VISUAL_VERIFY_CODE_CHARACTER_POOL)-1), 1);
                          }
                          $vvcode_oscsid = tep_session_id($_GET[tep_session_name()]);
                          tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . $vvcode_oscsid . "'");
                          $sql_data_array = array('oscsid' => $vvcode_oscsid, 'code' => $visual_verify_code);
                          tep_db_perform(TABLE_VISUAL_VERIFY_CODE, $sql_data_array);
                          $visual_verify_code = "";
                          echo '<td><img src="' . FILENAME_VISUAL_VERIFY_CODE_DISPLAY . '?vvc=' . $vvcode_oscsid . '" /></td>';
                          echo '<td class="main">' . VISUAL_VERIFY_CODE_BOX_IDENTIFIER . '</td>'; 
                          ?>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <?php
                }
                ?>
              </table></td>
            </tr>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
            </tr>
            <tr>
              <td align="center"><?php echo tep_template_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE); ?></td>
            </tr>
          </table></td>
        </tr>
        </form>
        <?php
      }
      ?>
    </table></td>
  </tr>      
</table>
<!-- fss_forms_detail.tpl.php eof//-->
<?php
// RCI bottom
echo $cre_RCI->get('fssformsdetail', 'bottom');
echo $cre_RCI->get('global', 'bottom');
?>