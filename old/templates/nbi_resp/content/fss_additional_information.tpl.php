 <?php
 /*
  $Id: fss_additional_information.tpl.php,v 1.0.0.0 2008/06/17 10:20:48 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI top
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('fssadditionalinformation', 'top');
?>
<!-- fss_forms_additional_information.tpl.php -->
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">
              <?php
              echo TEXT_PAGE_TITLE;
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
      echo tep_draw_form('Reseller_Inquiry', tep_href_link(FILENAME_FSS_ADDITIONAL_INFORMATION, tep_get_all_get_params(array('action')) . 'action=submit&forms_id=' . $forms_id), 'post', 'onSubmit="return check_form(this);" enctype="multipart/form-data"');
      if (sizeof($questions) > 0) {
        ?>
        <tr>          
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td align="left"><table border="0" cellspacing="0" cellpadding="2">
                <?php    
                $flag = false;
                foreach ($questions as $value) {
                  $ret = tep_fss_is_unanwsered_question($customer_id, $forms_id, $value['questions_id']);
                  ?>
                  <tr>
                    <td class="main"><?php echo $value['questions_label']; ?>: </td>
                    <td class="main">
                      <?php                     
                      if ( $ret === false ) {
                        echo $value['html']['str'] . '&nbsp;&nbsp;&nbsp;' . TEXT_FIELD_EXPLAIN; 
                        $flag = true;
                      } else {
                        echo $ret;
                      }
                      ?>
                    </td>
                  </tr>
                  <?php
                }
                if ( $forms['enable_vvc'] == '1' && $flag ) {
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
            <?php
            if ($flag) {
              ?>
              <tr>
                <td><table width="100%">
                  <tr>
                    <td align="right"><?php echo tep_template_image_submit('button_submit.gif', IMAGE_BUTTON_SUBMIT); ?></td>
                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
              <?php
            }
            ?>
          </table></td>
        </tr>
        </form>
        <?php
      }
      ?>
    </table></td>
  </tr>      
</table>
 <?php
// RCI bottom
echo $cre_RCI->get('fssadditionalinformation', 'bottom');
echo $cre_RCI->get('global', 'bottom');
?>
<!-- fss_forms_additional_information.tpl.php //eof--> 