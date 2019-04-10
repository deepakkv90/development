<?php
/*
  $Id: return_text.php,v 1.0.0.0 2008/06/04 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
require('includes/application_top.php');
$languages = tep_get_languages();


if (isset($_GET['action'])) {
  $action = $_GET['action'] ;
} else if (isset($_POST['action'])) {
  $action = $_POST['action'] ;
  } else {
  $action = '' ;
}
if (tep_not_null($action) && $action == 'update') {
    for ($i=0; $i<sizeof($languages); $i++) {
    $language_id = $languages[$i]['id'];
    $update_query = "REPLACE INTO " . TABLE_RETURNS_TEXT . " (return_text_one, return_text_id, language_id) values ( '" . tep_db_encoder( tep_db_prepare_input( $_POST['aboutus'][$language_id] ) ) . "', '1', '" . (int)$language_id . "')";
    tep_db_query($update_query);
    }
}

    $editor_elements = '';
    for ($i=0; $i<sizeof($languages); $i++) {
    $language_id = $languages[$i]['id'];
    $aboutus[$language_id] = tep_db_fetch_array(tep_db_query("SELECT * FROM return_text where return_text_id = '1' and language_id = '" . $language_id . "'"));
    $editor_elements .= 'aboutus[' . $language_id . '],';
    }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
<!-- Tabs code -->
<script type="text/javascript" src="includes/javascript/tabpane/local/webfxlayout.js"></script>
<link type="text/css" rel="stylesheet" href="includes/javascript/tabpane/tab.webfx.css">
<style type="text/css">
.dynamic-tab-pane-control h2 {
    text-align: center;
    width:    auto;
}
.dynamic-tab-pane-control h2 a {
    display:  inline;
    width:    auto;
}
.dynamic-tab-pane-control a:hover {
    background: transparent;
}
</style>
<script type="text/javascript" src="includes/javascript/tabpane/tabpane.js"></script>
<!-- End Tabs -->
<?php
// Load Editor
echo tep_load_html_editor();
echo tep_insert_html_editor($editor_elements);
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="body-table">
  <tr>
    <!-- left_navigation //-->
     <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
     <!-- left_navigation_eof //-->
     <!-- body_text //-->   
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <?php
      // RCI start
      echo $cre_RCI->get('returntext', 'top');
      // RCI eof
      ?>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>    
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE_RETURN_TEXT ; ?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td valign="top"><?php
          echo  tep_draw_form('return_text', FILENAME_RETURNS_TEXT, 'action=update', 'post', '', 'SSL');
          ?>
            <table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main"><?php echo TEXT_TITLE_RETURN_TEXT; ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td>
                  <div class="tab-pane" id="tabPane1">
                  <script type="text/javascript">
                  tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
                  </script>
                  <?php
                  for ($i=0; $i<sizeof($languages); $i++) {
                      $language_id = $languages[$i]['id'];
                  ?>
                  <div class="tab-page" id="<?php echo $languages[$i]['name'];?>">
                    <h2 class="tab"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], '', '', 'align="middle" style="height:16px; width:30px;"') . '&nbsp;' .$languages[$i]['name'];?></h2>
                    <script type="text/javascript">tp1.addTabPage( document.getElementById( "<?php echo $languages[$i]['name'];?>" ) );</script>
                    <?php echo tep_draw_textarea_field('aboutus[' . $languages[$i]['id'] . ']', 'soft', '60', '10', $aboutus[$language_id]['return_text_one'],' style="width: 100%"'); ?> </div>
                  <?php
                  }
                  ?>
                  <script type="text/javascript">
                  //<![CDATA[
                  setupAllTabs();
                  //]]>
                  </script>
                </td>
              </tr>
              <tr>
                <td align="right"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
              </tr>
            </table>
            </form>
          </td>
        </tr>
      </table></td>
    <!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>