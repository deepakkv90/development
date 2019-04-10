<?php
 /*
  $Id: fss_uncompleted_surveys.tpl.php,v 1.0.0.0 2008/06/17 10:20:48 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI top
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('fssuncompletedsurveys', 'top');
?>
<!-- fss_uncompleted_surveys.tpl.php -->
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
  </tr>
  <?php  
  if (tep_db_num_rows(tep_db_query($listing_sql)) == 0) {
    ?>
    <tr>
      <td class="main"><?php echo TEXT_NO_UNCOMPLETED_SURVEYS; ?></td>
    </tr>
    <?php
  } else {
    ?>   
    <tr>
      <td><?php include(DIR_WS_MODULES . FILENAME_SURVEYS_LISTING_COL); ?></td>
    </tr>
    <?php
  }
  ?>
</table>
<!-- fss_uncompleted_surveys.tpl.php eof//-->
<?php
// RCI bottom
echo $cre_RCI->get('fssuncompletedsurveys', 'bottom');
echo $cre_RCI->get('global', 'bottom');
?>