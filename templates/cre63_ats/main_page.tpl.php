<?php
/*
  $Id: main_page.tpl.php,v 1.0 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php
if ( file_exists(DIR_WS_INCLUDES . FILENAME_HEADER_TAGS) ) {
  require(DIR_WS_INCLUDES . FILENAME_HEADER_TAGS);
} else {
  ?>
  <title><?php echo TITLE ?></title>
  <?php
}
?>
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_STYLE;?>">
<?php
// RCI code start
echo $cre_RCI->get('stylesheet', 'cre63ats');
echo $cre_RCI->get('global', 'head'); 
// RCI code eof
if (isset($javascript) && file_exists(DIR_WS_JAVASCRIPT . basename($javascript))) { require(DIR_WS_JAVASCRIPT . basename($javascript)); } 
if (defined('PRODUCT_INFO_TAB_ENABLE') && PRODUCT_INFO_TAB_ENABLE == 'True' && basename($PHP_SELF) == FILENAME_PRODUCT_INFO) {
  ?>
  <script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT;?>tabs/webfxlayout.js"></script>
  <link type="text/css" rel="stylesheet" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME;?>/tabs/tabpane.css">
  <script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT;?>tabs/tabpane.js"></script>
  <?php
}
if(defined('TEMPLATE_BUTTONS_USE_CSS') && TEMPLATE_BUTTONS_USE_CSS == 'true'){
?>
<!--[if IE]>
<style type="text/css">
.template-button-left, .template-button-middle, .template-button-right {
  height: 28px;
}
</style>
<![endif]-->
<?php
}
?>
</head>
<?php
// RCO start
if ($cre_RCO->get('mainpage', 'body') !== true) {              
  echo '<body>' . "\n";
}
// RCO end
//include languages if avaible for template
if(file_exists(TEMPLATE_FS_CUSTOM_INCLUDES . 'languages/' . $language . '/menu.php')){
require(TEMPLATE_FS_CUSTOM_INCLUDES . 'languages/' . $language . '/menu.php');
}
?>
<!-- warnings //-->
<?php 
if(file_exists(TEMPLATE_FS_CUSTOM_INCLUDES . FILENAME_WARNINGS)){
    require(TEMPLATE_FS_CUSTOM_INCLUDES . FILENAME_WARNINGS);    
} else {
    require(DIR_WS_INCLUDES . FILENAME_WARNINGS);
}
?>
<!-- warning_eof //-->
<?php
// RCI top
echo $cre_RCI->get('mainpage', 'top'); 
?>
<!-- header //-->
<?php require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . FILENAME_HEADER); ?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_MAIN; ?>" class="maincont_tb">
  <tr>
    <?php 
    if (DISPLAY_COLUMN_LEFT == 'yes' && (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_COLUMN_LEFT_OFF =='false'))  {
        $column_location = 'Left_';
    ?>
      <td width="<?php echo BOX_WIDTH_LEFT; ?>" valign="top" class="maincont_left_td">
        <table border="0" width="<?php echo BOX_WIDTH_LEFT; ?>" cellspacing="0" cellpadding="<?php echo CELLPADDING_LEFT; ?>" class="leftbar_tb">
        <!-- left_navigation //-->
        <?php require(DIR_WS_INCLUDES . FILENAME_COLUMN_LEFT); ?>
        <!-- left_navigation_eof //-->
      </table></td>
      <?php
      $column_location = '';
    }
    ?>
    <!-- content //-->
    <td width="100%" valign="top">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr class="headerNavigation">
            <td class="headerNavigation">&nbsp;&nbsp;<?php echo $breadcrumb->trail(' &raquo; '); ?></td>
        </tr>
      </table>
      <?php
      if (isset($content_template) && file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/'.  basename($content_template))) {
        require(DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/' . $content . '.tpl.php');
      } else if (file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/' . $content . '.tpl.php')) {
        require(DIR_WS_TEMPLATES . TEMPLATE_NAME.'/content/'. $content . '.tpl.php');
      } else if (isset($content_template) && file_exists(DIR_WS_CONTENT . basename($content_template)) ){
        require(DIR_WS_CONTENT . basename($content_template));
      } else {
        require(DIR_WS_CONTENT . $content . '.tpl.php');
      }
      ?>
    </td>
    <!-- content_eof //-->
    <?php
    if (DISPLAY_COLUMN_RIGHT == 'yes' && (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_COLUMN_LEFT_OFF =='false'))  {
        $column_location = 'Right_';
      ?>
      <td width="<?php echo BOX_WIDTH_RIGHT; ?>" valign="top" class="maincont_right_td">
        <table border="0" width="<?php echo BOX_WIDTH_RIGHT; ?>" cellspacing="0" cellpadding="<?php echo CELLPADDING_RIGHT; ?>" class="rightbar_tb">
        <!-- right_navigation //-->
        <?php require(DIR_WS_INCLUDES . FILENAME_COLUMN_RIGHT); ?>
        <!-- right_navigation_eof //-->
      </table></td>
      <?php
      $column_location = '';
    }
    ?>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_TEMPLATES . TEMPLATE_NAME .'/'.FILENAME_FOOTER); ?>
<!-- footer_eof //-->
<br>
<?php
// RCI global footer
echo $cre_RCI->get('global', 'footer');
?>
</body>
</html>