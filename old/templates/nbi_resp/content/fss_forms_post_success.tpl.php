<?php
 /*
  $Id: fss_forms_post_success.tpl.php,v 1.0.0.0 2008/06/17 10:20:48 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI top
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('fssformspostsuccess', 'top');
?>
<!-- fss_forms_post_success.tpl.php -->
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
          </tr>
          <tr>
            <td class="main">
              <?php
              echo $confirmation_content;
              ?>
            </td>                
          </tr>
        </table></td>
      </tr>          
    </table></td>
  </tr>    
  <tr>
    <td><table width="100%">
      <tr>
        <td align="right"><?php echo '<a href="' . (isset($_SESSION['customer_id']) ? tep_href_link(FILENAME_ACCOUNT, '', 'SSL') : tep_href_link(FILENAME_FSS_FORMS_INDEX, '', 'SSL')) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- fss_forms_post_success.tpl.php eof//-->
<?php
// RCI bottom
echo $cre_RCI->get('fssformspostsuccess', 'bottom');
echo $cre_RCI->get('global', 'bottom');
?>