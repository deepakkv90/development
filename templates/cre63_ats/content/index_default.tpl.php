 <?php
 /*
  $Id: index_default.php,v 1.0 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI top
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('indexdefault', 'top');
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <?php
  if (tep_not_null(INCLUDE_MODULE_ONE)) {
    echo '<tr><td>';
    include($modules_folder . INCLUDE_MODULE_ONE);
    echo '</td></tr>';
  }
  if (tep_not_null(INCLUDE_MODULE_TWO)) {
    echo '<tr><td class="main">';
    include($modules_folder . INCLUDE_MODULE_TWO);
    echo '</td></tr>';
  }
  if (tep_not_null(INCLUDE_MODULE_THREE)) {
    echo '<tr><td>';
    include($modules_folder . INCLUDE_MODULE_THREE);
    echo '</td></tr>';
  }
  if (tep_not_null(INCLUDE_MODULE_FOUR)) {
    echo '<tr><td>';
    include($modules_folder . INCLUDE_MODULE_FOUR);
    echo '</td></tr>';
  }
  if (tep_not_null(INCLUDE_MODULE_FIVE)) {
    echo '<tr><td>';
    include($modules_folder . INCLUDE_MODULE_FIVE);
    echo '</td></tr>';
  }
  if (tep_not_null(INCLUDE_MODULE_SIX)) {
    echo '<tr><td>';
    include($modules_folder . INCLUDE_MODULE_SIX);
    echo '</td></tr>';
  }
  ?>
</table>
<?php
// RCI bottom
echo $cre_RCI->get('indexdefault', 'bottom');
echo $cre_RCI->get('global', 'bottom');
?>