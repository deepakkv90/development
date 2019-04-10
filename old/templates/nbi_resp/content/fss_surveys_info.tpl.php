<?php
 /*
  $Id: fss_surveys_info.tpl.php,v 1.0.0.0 2008/06/17 10:20:48 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
 // RCI top
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('fsssurveysinfo', 'top');
?>
<!-- fss_surveys_info.tpl.php -->
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
      if (sizeof($questions) > 0) {
        ?>
        <tr>          
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td align="left"><table border="0" cellspacing="0" cellpadding="2">
                <?php      
                foreach ($questions as $value) {
                  $ret = tep_fss_is_unanwsered_question($customer_id, $forms_id, $value['questions_id']);
                  ?>
                  <tr>
                    <td class="main"><?php echo $value['questions_label']; ?>: </td>
                    <td class="main">
                      <?php                     
                      if ( $ret === false ) {
                        echo $value['html']['str']; 
                        $flag = true;
                      } else {
                        echo $ret;
                      }
                      ?>
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
              <td align="right"><a href="<?php echo tep_href_link(FILENAME_FSS_COMPLETED_SURVEYS); ?>"><?php echo tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK); ?></a></td>
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
<!-- fss_surveys_info.tpl.php eof//-->
<?php
// RCI bottom
echo $cre_RCI->get('fsssurveysinfo', 'bottom');
echo $cre_RCI->get('global', 'bottom');
?>