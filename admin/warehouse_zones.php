<?php
/*
  $Id: warehouse_zones.php,v 1.1 2008/06/22 22:50:52 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$saction = (isset($_GET['saction']) ? $_GET['saction'] : '');
if (tep_not_null($saction)) {
  switch ($saction) {
    case 'insert_sub':
      $zID = tep_db_prepare_input($_GET['zID']);
      $zone_country_id = tep_db_prepare_input($_POST['zone_country_id']);
      $zone_id = tep_db_prepare_input($_POST['zone_id']);
        tep_db_query("delete from " . TABLE_ZONES_TO_WAREHOUSE_ZONES . " where zone_id = '" . (int)$zone_id . "'");
      tep_db_query("insert into " . TABLE_ZONES_TO_WAREHOUSE_ZONES . " (zone_country_id, zone_id, warehouse_zone_id, date_added) values ('" . (int)$zone_country_id . "', '" . (int)$zone_id . "', '" . (int)$zID . "', now())");
      $new_subzone_id = tep_db_insert_id();
      tep_redirect(tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $new_subzone_id));
      break;
    case 'save_sub':
      $sID = tep_db_prepare_input($_GET['sID']);
      $zID = tep_db_prepare_input($_GET['zID']);
      $zone_country_id = tep_db_prepare_input($_POST['zone_country_id']);
      $zone_id = tep_db_prepare_input($_POST['zone_id']);
      tep_db_query("update " . TABLE_ZONES_TO_WAREHOUSE_ZONES . " set warehouse_zone_id = '" . (int)$zID . "', zone_country_id = '" . (int)$zone_country_id . "', zone_id = " . (tep_not_null($zone_id) ? "'" . (int)$zone_id . "'" : 'null') . ", last_modified = now() where association_id = '" . (int)$sID . "'");
      tep_redirect(tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $_GET['sID']));
      break;
    case 'deleteconfirm_sub':
      $sID = tep_db_prepare_input($_GET['sID']);
      tep_db_query("delete from " . TABLE_ZONES_TO_WAREHOUSE_ZONES . " where association_id = '" . (int)$sID . "'");
      tep_redirect(tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage']));
      break;
  }
}
$action = (isset($_GET['action']) ? $_GET['action'] : '');
if (tep_not_null($action)) {
  switch ($action) {
    case 'insert_zone':
      $warehouse_zone_name = tep_db_prepare_input($_POST['warehouse_zone_name']);
      $warehouse_zone_description = tep_db_prepare_input($_POST['warehouse_zone_description']);
        $warehouse_zone_zip_code = tep_db_prepare_input($_POST['warehouse_zone_zip_code']);
      tep_db_query("insert into " . TABLE_WAREHOUSE_ZONES . " (warehouse_zone_name, warehouse_zone_description, warehouse_zone_zip_code, date_added) values ('" . tep_db_input($warehouse_zone_name) . "', '" . tep_db_input($warehouse_zone_description) . "', '" . tep_db_input($warehouse_zone_zip_code) . "', now())");
      $new_zone_id = tep_db_insert_id();
      tep_redirect(tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $new_zone_id));
      break;
    case 'save_zone':
      $zID = tep_db_prepare_input($_GET['zID']);
      $warehouse_zone_name = tep_db_prepare_input($_POST['warehouse_zone_name']);
      $warehouse_zone_description = tep_db_prepare_input($_POST['warehouse_zone_description']);
        $warehouse_zone_zip_code = tep_db_prepare_input($_POST['warehouse_zone_zip_code']);
      tep_db_query("update " . TABLE_WAREHOUSE_ZONES . " set warehouse_zone_name = '" . tep_db_input($warehouse_zone_name) . "', warehouse_zone_description = '" . tep_db_input($warehouse_zone_description) . "', warehouse_zone_zip_code = '" . $warehouse_zone_zip_code . "', last_modified = now() where warehouse_zone_id = '" . (int)$zID . "'");
      tep_redirect(tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID']));
      break;
    case 'deleteconfirm_zone':
      $zID = tep_db_prepare_input($_GET['zID']);
      tep_db_query("delete from " . TABLE_WAREHOUSE_ZONES . " where warehouse_zone_id = '" . (int)$zID . "'");
      tep_db_query("delete from " . TABLE_ZONES_TO_WAREHOUSE_ZONES . " where warehouse_zone_id = '" . (int)$zID . "'");
      tep_redirect(tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage']));
      break;
  }
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
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
<?php
if (isset($_GET['zID']) && (($saction == 'edit') || ($saction == 'new'))) {
  ?>
  <script language="javascript"><!--
  function resetZoneSelected(theForm) {
    if (theForm.state.value != '') {
      theForm.zone_id.selectedIndex = '0';
      if (theForm.zone_id.options.length > 0) {
        theForm.state.value = '<?php echo JS_STATE_SELECT; ?>';
      }
    }
  }
  function update_zone(theForm) {
    var NumState = theForm.zone_id.options.length;
    var SelectedCountry = "";
    while(NumState > 0) {
      NumState--;
      theForm.zone_id.options[NumState] = null;
    }
    SelectedCountry = theForm.zone_country_id.options[theForm.zone_country_id.selectedIndex].value;
    <?php echo tep_js_zone_list('SelectedCountry', 'theForm', 'zone_id'); ?>
  }
  //--></script>
  <?php
}
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td class="page-container" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; if (isset($_GET['zone'])) echo '<br><span class="smallText">' . tep_get_warehouse_zone_name($_GET['zone']) . '</span>'; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
              <?php
              if ($action == 'list') {
                ?>
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_COUNTRY; ?></td>
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_COUNTRY_ZONE; ?></td>
                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                  </tr>
                  <?php
                  $rows = 0;
                  $zones_query_raw = "select a.association_id, a.zone_country_id, c.countries_name, a.zone_id, a.warehouse_zone_id, a.last_modified, a.date_added, z.zone_name from " . TABLE_ZONES_TO_WAREHOUSE_ZONES . " a left join " . TABLE_COUNTRIES . " c on a.zone_country_id = c.countries_id left join " . TABLE_ZONES . " z on a.zone_id = z.zone_id where a.warehouse_zone_id = " . $_GET['zID'] . " order by association_id";
                  $zones_split = new splitPageResults($_GET['spage'], MAX_DISPLAY_SEARCH_RESULTS, $zones_query_raw, $zones_query_numrows);
                  $zones_query = tep_db_query($zones_query_raw);
                  while ($zones = tep_db_fetch_array($zones_query)) {
                    $rows++;
                    if ((!isset($_GET['sID']) || (isset($_GET['sID']) && ($_GET['sID'] == $zones['association_id']))) && !isset($sInfo) && (substr($action, 0, 3) != 'new')) {
                      $sInfo = new objectInfo($zones);
                    }
                    if (isset($sInfo) && is_object($sInfo) && ($zones['association_id'] == $sInfo->association_id)) {
                      echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $sInfo->association_id . '&saction=edit') . '\'">' . "\n";
                    } else {
                      echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $zones['association_id']) . '\'">' . "\n";
                    }
                    ?>
                    <td class="dataTableContent"><?php echo (($zones['countries_name']) ? $zones['countries_name'] : TEXT_ALL_COUNTRIES); ?></td>
                    <td class="dataTableContent"><?php echo (($zones['zone_id']) ? $zones['zone_name'] : PLEASE_SELECT); ?></td>
                    <td class="dataTableContent" align="right"><?php if (isset($sInfo) && is_object($sInfo) && ($zones['association_id'] == $sInfo->association_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $zones['association_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                    </tr>
                    <?php
                  }
                  ?>
                  <tr>
                    <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="smallText" valign="top"><?php echo $zones_split->display_count($zones_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['spage'], TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?></td>
                        <td class="smallText" align="right"><?php echo $zones_split->display_links($zones_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['spage'], 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list', 'spage'); ?></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="3"><?php if (empty($saction)) echo '<a href="' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a><a href="' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&' . (isset($sInfo) ? 'sID=' . $sInfo->association_id . '&' : '') . 'saction=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
                  </tr>
                </table>
                <?php
              } else {
                ?>
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TAX_ZONES; ?></td>
                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                  </tr>
                  <?php
                  $zones_query_raw = "select warehouse_zone_id, warehouse_zone_name, warehouse_zone_description, warehouse_zone_zip_code, last_modified, date_added from " . TABLE_WAREHOUSE_ZONES . " order by warehouse_zone_name";
                  $zones_split = new splitPageResults($_GET['zpage'], MAX_DISPLAY_SEARCH_RESULTS, $zones_query_raw, $zones_query_numrows);
                  $zones_query = tep_db_query($zones_query_raw);
                  while ($zones = tep_db_fetch_array($zones_query)) {
                    if ((!isset($_GET['zID']) || (isset($_GET['zID']) && ($_GET['zID'] == $zones['warehouse_zone_id']))) && !isset($zInfo) && (substr($action, 0, 3) != 'new')) {
                      $num_zones_query = tep_db_query("select count(*) as num_zones from " . TABLE_ZONES_TO_WAREHOUSE_ZONES . " where warehouse_zone_id = '" . (int)$zones['warehouse_zone_id'] . "' group by warehouse_zone_id");
                      $num_zones = tep_db_fetch_array($num_zones_query);
                      if ($num_zones['num_zones'] > 0) {
                        $zones['num_zones'] = $num_zones['num_zones'];
                      } else {
                        $zones['num_zones'] = 0;
                      }
                      $zInfo = new objectInfo($zones);
                    }
                    if (isset($zInfo) && is_object($zInfo) && ($zones['warehouse_zone_id'] == $zInfo->warehouse_zone_id)) {
                      echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->warehouse_zone_id . '&action=list') . '\'">' . "\n";
                    } else {
                      echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zones['warehouse_zone_id']) . '\'">' . "\n";
                    }
                    ?>
                    <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zones['warehouse_zone_id'] . '&action=list') . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a><b> ' . $zones['warehouse_zone_name'] . '</b>'; ?></td>
                    <td class="dataTableContent" align="right"><?php if (isset($zInfo) && is_object($zInfo) && ($zones['warehouse_zone_id'] == $zInfo->warehouse_zone_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zones['warehouse_zone_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                    </tr>
                    <?php
                  }
                  ?>
                  <tr>
                    <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="smallText"><?php echo $zones_split->display_count($zones_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['zpage'], TEXT_DISPLAY_NUMBER_OF_TAX_ZONES); ?></td>
                        <td class="smallText" align="right"><?php echo $zones_split->display_links($zones_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['zpage'], '', 'zpage'); ?></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="2"><?php if (!$action) echo '<a href="' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->warehouse_zone_id . '&action=new_zone') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
                  </tr>
                </table>
                <?php
              }
              ?>
            </td>
            <?php
            $heading = array();
            $contents = array();
            if ($action == 'list') {
              switch ($saction) {
                case 'new':
                  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_SUB_ZONE . '</b>');
                  $contents = array('form' => tep_draw_form('zones', FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&' . (isset($_GET['sID']) ? 'sID=' . $_GET['sID'] . '&' : '') . 'saction=insert_sub'));
                  $contents[] = array('text' => TEXT_INFO_NEW_SUB_ZONE_INTRO);
                  $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY . '<br>' . tep_draw_pull_down_menu('zone_country_id', tep_get_countries(TEXT_ALL_COUNTRIES), '', 'onChange="update_zone(this.form);"'));
                  $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_ZONE . '<br>' . tep_draw_pull_down_menu('zone_id', tep_prepare_country_zones_pull_down()));
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&' . (isset($_GET['sID']) ? 'sID=' . $_GET['sID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_insert.gif', IMAGE_INSERT));
                  break;
                case 'edit':
                  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_SUB_ZONE . '</b>');
                  $contents = array('form' => tep_draw_form('zones', FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $sInfo->association_id . '&saction=save_sub'));
                  $contents[] = array('text' => TEXT_INFO_EDIT_SUB_ZONE_INTRO);
                  $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY . '<br>' . tep_draw_pull_down_menu('zone_country_id', tep_get_countries(TEXT_ALL_COUNTRIES), $sInfo->zone_country_id, 'onChange="update_zone(this.form);"'));
                  $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_ZONE . '<br>' . tep_draw_pull_down_menu('zone_id', tep_prepare_country_zones_pull_down($sInfo->zone_country_id), $sInfo->zone_id));
                    $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $sInfo->association_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif', IMAGE_UPDATE));
                  break;
                case 'delete':
                  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_SUB_ZONE . '</b>');
                  $contents = array('form' => tep_draw_form('zones', FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $sInfo->association_id . '&saction=deleteconfirm_sub'));
                  $contents[] = array('text' => TEXT_INFO_DELETE_SUB_ZONE_INTRO);
                  $contents[] = array('text' => '<br><b>' . $sInfo->countries_name . '</b>');
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $sInfo->association_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE));
                  break;
                default:
                  if (isset($sInfo) && is_object($sInfo)) {
                    $heading[] = array('text' => '<b>' . $sInfo->countries_name . '</b>');
                    $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $sInfo->association_id . '&saction=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a><a href="' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $sInfo->association_id . '&saction=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
                    $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . '<br><b>' . tep_date_short($sInfo->date_added) . '</b>');
                    if (tep_not_null($sInfo->last_modified)) $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . '<br><b>' . tep_date_short($sInfo->last_modified) . '</b>');
                  }
                  break;
              }
            } else {
              switch ($action) {
                case 'new_zone':
                  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_ZONE . '</b>');
                  $contents = array('form' => tep_draw_form('zones', FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=insert_zone'));
                  $contents[] = array('text' => TEXT_INFO_NEW_ZONE_INTRO);
                  $contents[] = array('text' => '<br>' . TEXT_INFO_ZONE_NAME . '<br>' . tep_draw_input_field('warehouse_zone_name'));
                  $contents[] = array('text' => '<br>' . TEXT_INFO_ZONE_DESCRIPTION . '<br>' . tep_draw_input_field('warehouse_zone_description'));
                    $contents[] = array('text' => '<br>' . TEXT_INFO_ZONE_ZIP_CODE . '<br>' . tep_draw_input_field('warehouse_zone_zip_code'));
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_insert.gif', IMAGE_INSERT));
                  break;
                case 'edit_zone':
                  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_ZONE . '</b>');
                  $contents = array('form' => tep_draw_form('zones', FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->warehouse_zone_id . '&action=save_zone'));
                  $contents[] = array('text' => TEXT_INFO_EDIT_ZONE_INTRO);
                  $contents[] = array('text' => '<br>' . TEXT_INFO_ZONE_NAME . '<br>' . tep_draw_input_field('warehouse_zone_name', $zInfo->warehouse_zone_name));
                  $contents[] = array('text' => '<br>' . TEXT_INFO_ZONE_DESCRIPTION . '<br>' . tep_draw_input_field('warehouse_zone_description', $zInfo->warehouse_zone_description));
                  $contents[] = array('text' => '<br>' . TEXT_INFO_ZONE_ZIP_CODE . '<br>' . tep_draw_input_field('warehouse_zone_zip_code', $zInfo->warehouse_zone_zip_code));
                    $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->warehouse_zone_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif', IMAGE_UPDATE));
                  break;
                case 'delete_zone':
                  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ZONE . '</b>');
                  $contents = array('form' => tep_draw_form('zones', FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->warehouse_zone_id . '&action=deleteconfirm_zone'));
                  $contents[] = array('text' => TEXT_INFO_DELETE_ZONE_INTRO);
                  $contents[] = array('text' => '<br><b>' . $zInfo->warehouse_zone_name . '</b>');
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->warehouse_zone_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE));
                  break;
                default:
                  if (isset($zInfo) && is_object($zInfo)) {
                    $heading[] = array('text' => '<b>' . $zInfo->warehouse_zone_name . '</b>');
                    $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->warehouse_zone_id . '&action=edit_zone') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a><a href="' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->warehouse_zone_id . '&action=delete_zone') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a><br><a href="' . tep_href_link(FILENAME_WAREHOUSE_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->warehouse_zone_id . '&action=list') . '">' . tep_image_button('button_details.gif', IMAGE_DETAILS) . '</a>');
                    $contents[] = array('text' => '<br>' . TEXT_INFO_NUMBER_ZONES . ' <b>' . $zInfo->num_zones . '</b>');
                    $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . '<br><b>' . tep_date_short($zInfo->date_added) . '</b>');
                    if (tep_not_null($zInfo->last_modified)) $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . '<br><b>' . tep_date_short($zInfo->last_modified) . '</b>');
                    $contents[] = array('text' => '<br>' . TEXT_INFO_ZONE_DESCRIPTION . '<br><b>' . $zInfo->warehouse_zone_description . '</b>');
                      $contents[] = array('text' => '<br>' . TEXT_INFO_ZONE_ZIP_CODE . '<br><b>' . $zInfo->warehouse_zone_zip_code . '</b>');
                  }
                  break;
              }
            }
            if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
              echo '<td width="25%" valign="top">' . "\n";
              $box = new box;
              echo $box->infoBox($heading, $contents);
              echo '</td>' . "\n";
            }
            ?>
          </tr>
        </table></td>
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