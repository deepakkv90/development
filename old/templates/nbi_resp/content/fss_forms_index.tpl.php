<?php
 /*
  $Id: fss_forms_index.tpl.php,v 1.0.0.0 2008/06/17 10:20:48 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI top
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('fssformsindex', 'top');
?>
<!-- fss_forms_nested.tpl.php -->
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">
              <?php
              $categories = tep_db_fetch_array(tep_db_query("SELECT fss_categories_id, fss_categories_name 
                                                               from " . TABLE_FSS_CATEGORIES . " 
                                                             WHERE fss_categories_id = '" . (int)$current_category_id . "'"));
              echo $categories['fss_categories_name'];
              ?>
            </td>                
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <?php
            $sub_categories_query = tep_db_query("SELECT fss_categories_id, fss_categories_name, fss_categories_parent_id 
                                                    from " . TABLE_FSS_CATEGORIES . " 
                                                  WHERE fss_categories_parent_id = '" . (int)$current_category_id . "' 
                                                  ORDER BY sort_order, fss_categories_name");
            $rows = 0;
            $number_of_categories = tep_db_num_rows($sub_categories_query);
            while ($sub_categories = tep_db_fetch_array($sub_categories_query)) {
              $rows++;
              $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';
              echo '<td align="center" class="smallText" width="' . $width . '" valign="top"><a href="' . tep_href_link(FILENAME_FSS_FORMS_INDEX, 'fPath=' . $sub_categories['fss_categories_id']) . '">' . $sub_categories['fss_categories_name'] . '</a></td>' . "\n";
              if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories)) {
                echo '</tr>' . "\n";
                echo '<tr>' . "\n";
              }
            }
            ?>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
  </tr>
  <tr>
    <td><?php include(DIR_WS_MODULES . FILENAME_FORMS_LISTING_COL); ?></td>
  </tr>
</table>
<!-- fss_forms_nested.tpl.php eof//-->
<?php
// RCI bottom
echo $cre_RCI->get('fssformsindex', 'bottom');
echo $cre_RCI->get('global', 'bottom');
?>