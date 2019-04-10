<?php
/*
  $Id: fdm_daily_downloads.php,v 1.0.0.0 2007/10/26 13:41:11 avicrw Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_WS_FUNCTIONS . 'fdm_functions.php');
$is_62 = (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) ? true : false;
$month_array = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec')
// main entry for report display
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php
if ($is_62) {
    echo '<script language="javascript" src="includes/menu.js"></script>' . "\n";
} else {
    echo '<script type="text/javascript" src="includes/prototype.js"></script>' . "\n";
    echo '<!--[if IE]>' . "\n";
    echo '<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">' . "\n";
    echo '<![endif]-->' . "\n";
}
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<?php $padding = ($is_62 == true) ? 2 : 0; ?>
<table border="1" width="100%" cellspacing="<?php echo $padding; ?>" cellpadding="<?php echo $padding; ?>" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php 
    if ($is_62 == true) echo '<td width="' . BOX_WIDTH . '" valign="top"><table border="0" width="' . BOX_WIDTH . '" cellspacing="1" cellpadding="1" class="columnLeft">' . "\n";
    require(DIR_WS_INCLUDES . 'column_left.php'); 
    if ($is_62 == true) echo '</table></td>' . "\n";
    ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
          <?php 
          // detect whether this is monthly detail request
          $month = 0;
          if (isset($_GET['month']) && isset($_GET['year'])) {
            $month = tep_db_prepare_input($_GET['month']);
            $year = tep_db_prepare_input($_GET['year']);
          }
          // get list of file names for dropdown selection
          $files_list[] = array('id' => '-1', 'text' => ' -- ');
          $files_list[] = array('id' => '0', 'text' => TEXT_ALL_FILES);
          $files_query = tep_db_query("select files_id, files_name from " . TABLE_LIBRARY_FILES . " order by files_name");
          while ($files_array = tep_db_fetch_array($files_query)) {
            $files_list[] = array('id' => $files_array['files_id'],
                                  'text' => $files_array['files_name']);
          }
          if ($month <> 0) {
            ?>
            <tr>
              <td align="right">&nbsp;
                <?php 
                echo "<a href='" . tep_href_link(FILENAME_DAILY_DOWNLOADS, tep_get_all_get_params(array('month', 'year'))) . "' title='" . TEXT_BUTTON_REPORT_BACK_DESC . "'>" . tep_image_button('button_back.gif', TEXT_BUTTON_REPORT_BACK) . "</a><br>&nbsp;";
                ?>
              </td>
            </tr>
            <?php
          }
          if ( isset($_GET['month']) ) {
            if ( $_GET['month'] == 12 ) {
              $next_link = tep_href_link(FILENAME_DAILY_DOWNLOADS, tep_get_all_get_params(array('month', 'year')) . 'month=1&year=' . ($_GET['year'] + 1));
              $prev_link = tep_href_link(FILENAME_DAILY_DOWNLOADS, tep_get_all_get_params(array('month', 'year')) . 'month=11&year=' . $_GET['year']);
            } elseif ( $_GET['month'] == 1 ) {
              $next_link = tep_href_link(FILENAME_DAILY_DOWNLOADS, tep_get_all_get_params(array('month', 'year')) . 'month=2&year=' . $_GET['year']);
              $prev_link = tep_href_link(FILENAME_DAILY_DOWNLOADS, tep_get_all_get_params(array('month', 'year')) . 'month=12&year=' . ($_GET['year'] - 1));
            } else {
              $next_link = tep_href_link(FILENAME_DAILY_DOWNLOADS, tep_get_all_get_params(array('month', 'year')) . 'month=' . ($_GET['month'] + 1) . '&year=' . $_GET['year']);
              $prev_link = tep_href_link(FILENAME_DAILY_DOWNLOADS, tep_get_all_get_params(array('month', 'year')) . 'month=' . ($_GET['month'] - 1) . '&year=' . $_GET['year']);
            }
            ?>
            <tr>
              <td class="main" align="right"><?php echo '<a href="' . $prev_link . '">&lt; Prev</a>&nbsp;&nbsp;&nbsp;<a href="' . $next_link . '">Next &gt;</a>'; ?></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <?php
          }
          ?>
          <tr>
            <td class="smallText" align="right">
              <?php 
              echo tep_draw_form('files', FILENAME_DAILY_DOWNLOADS, '', 'get');
              // get list of orders_status names for dropdown selection
              echo tep_draw_hidden_field(tep_session_name(), tep_session_id());
              echo HEADING_TITLE_FILES . ': ' . tep_draw_pull_down_menu('files', $files_list, '', 'onChange="this.form.submit();"');
              if ($month<>0) echo "<input type='hidden' name='month' value='" . $month . "'><input type='hidden' name='year' value='" . $year . "'>";
              ?>
            </td></form>
          </tr>   
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" width='10%' align='left' valign="bottom">
                      <?php 
                      if ($month == 0) echo TABLE_HEADING_MONTH; else echo TABLE_HEADING_MONTH; ?>
                    </td>
                    <td class="dataTableHeadingContent" width='10%' align='left' valign="bottom">
                      <?php if ($month == 0) echo TABLE_HEADING_YEAR; else echo TABLE_HEADING_DAY; ?>
                    </td>
                    <td class="dataTableHeadingContent" width="20%" align='right' valign="bottom"><?php echo TABLE_HEADING_DOWNLOADS; ?></td>
                    <td class="dataTableHeadingContent" width="20%" align='right' valign="bottom"><?php echo TABLE_HEADING_DOWNLOADS_AVERAGE; ?></td>
                    <td class="dataTableHeadingContent" width="20%" align='right' valign="bottom"><?php echo TABLE_HEADING_UNIQUE_DOWNLOADS; ?></td>
                    <td class="dataTableHeadingContent" width="20%" align='right' valign="bottom"><?php echo TABLE_HEADING_UNIQUE_DOWNLOADS_AVERAGE; ?></td>
                  </tr>
                  <?php 
                  // clear footer totals
                  $downloads = 0;
                  $unique_downloads = 0;
                  $downloads_query_select = "SELECT count(flfd.files_id) as count, monthname(flfd.download_time) row_month, year(flfd.download_time) row_year, month(flfd.download_time) i_month, dayofmonth(flfd.download_time) row_day";
                  $downloads_query_from = " from " . TABLE_LIBRARY_FILES_DOWNLOAD . " flfd";
                  $downloads_query_where = " WHERE 1 = 1";
                  $downloads_query_extra .= " group by year(flfd.download_time), month(flfd.download_time)";
                  if ( isset($_GET['files']) && $_GET['files'] != '0' && $_GET['files'] != '-1') {
                    $downloads_query_where .= " and flfd.files_id = '" . $_GET['files'] . "'";
                  }
                  if ($year <> 0) {
                    $downloads_query_where .= " and year(flfd.download_time) = " . $year;
                  }
                  if ($month <> 0) {
                    $downloads_query_where .= " and month(flfd.download_time) = " . $month;
                    $downloads_query_extra .= ", dayofmonth(flfd.download_time)";
                  }
                  $downloads_query_extra .=  " order by flfd.download_time desc";
                  if (isset($_GET['files']) && $_GET['files'] != '-1') {
                    $downloads_query = tep_db_query($downloads_query_select . $downloads_query_from . $downloads_query_where . $downloads_query_extra);
                    $num_rows = tep_db_num_rows($downloads_query);
                    if ($num_rows==0) echo '<tr><td class="smalltext" colspan="6">' . TEXT_NOTHING_FOUND . '</td></tr>';
                    $rows = 0;
                    // loop here for each row reported
                    $total = 0;
                    $unique_total = 0;
                    $count = 0;
                    $year_count = 0;
                    $month_count = 0;
                    while ($downloads = tep_db_fetch_array($downloads_query)) {
                      $rows++;
                      $count++;
                      $month_count++;
                      $downloads_unique_raw = "SELECT files_id 
                                                 from " . TABLE_LIBRARY_FILES_DOWNLOAD . " 
                                               WHERE year(download_time) = '" . $downloads['row_year'] . "' 
                                                 and month(download_time) = '" . $downloads['i_month'] . "' 
                                                 and customers_id <> 0";
                      $downloads_unique_raw2 = "SELECT files_id 
                                                  from " . TABLE_LIBRARY_FILES_DOWNLOAD . " 
                                                WHERE year(download_time) = '" . $downloads['row_year'] . "' 
                                                  and month(download_time) = '" . $downloads['i_month'] . "' 
                                                  and customers_id = 0";
                      if (tep_not_null($_GET['month'])) { 
                        $downloads_unique_raw .= " and dayofmonth(download_time) = '" . $downloads['row_day'] . "'";
                        $downloads_unique_raw2 .= " and dayofmonth(download_time) = '" . $downloads['row_day'] . "'";
                        if ( isset($_GET['files']) && $_GET['files'] != '0' ) {
                          $downloads_unique_raw .= " and files_id = '" . $_GET['files'] . "'";
                          $downloads_unique_raw2 .= " and files_id = '" . $_GET['files'] . "'";
                        }
                        $downloads_unique_raw .= " group by customers_id";
                        $downloads_unique_raw2 .= " group by ip_addr";
                        $downloads_unique['count'] = tep_db_num_rows(tep_db_query($downloads_unique_raw)) + tep_db_num_rows(tep_db_query($downloads_unique_raw2));
                      } else {
                        $downloads_unique['count'] = tep_fdm_get_monthly_unique_downloads($downloads['row_year'], $downloads['i_month']);
                      }
                    ?>
                    <tr <?php echo $rows % 2 == 0 ? 'class="dataTableRow"' : 'bgcolor="ffffff"'; ?>>
                      <td class="dataTableContent" align="left">
                        <?php
                        if ($month == 0) {
                          echo "<a href='" . tep_href_link(FILENAME_DAILY_DOWNLOADS, tep_get_all_get_params(array('month', 'year')) . "month=" . $downloads['i_month'] . "&year=" . $downloads['row_year']) . "' title='" . TEXT_BUTTON_REPORT_GET_DETAIL . "'>";
                        }
                        echo substr($downloads['row_month'],0,3); 
                        if ($month == 0) echo '</a>';
                        ?>
                      </td>
                      <td class="dataTableContent" align="left">
                        <?php 
                        if ($month == 0) {
                          echo $downloads['row_year'];
                        } else { 
                          echo $downloads['row_day'];
                        }      
                        ?>
                      </td>
                      <td class="dataTableContent" align="right"><?php echo $downloads['count']; ?></td>
                      <td class="dataTableContent" align="right">&nbsp;</td>
                      <td class="dataTableContent" align="right"><?php echo $downloads_unique['count']; ?></td>
                      <td class="dataTableContent" align="right">&nbsp;</td>
                    </tr>
                    <?php
                      $downloads_sum += $downloads['count'];
                      $downloads_unique_sum += $downloads_unique['count'];
                      if ( $downloads['i_month'] == 1 && !isset($_GET['month']) ) {
                      ?>
                      <tr class="dataTableHeadingRow">
                        <td class="dataTableHeadingContent" align="left">
                          <?php 
                          if ($month <> 0) {
                            echo strtoupper(substr($downloads['row_month'],0,3));
                          } else {
                            echo TABLE_FOOTER_YEAR;
                          }      
                          ?>
                        </td>
                        <td class="dataTableHeadingContent" align="left"><?php echo (isset($last_row_year) ? $last_row_year : $downloads['row_year']); ?></td>
                        <td class="dataTableHeadingContent" align="right"><?php echo $downloads_sum; ?></td>
                        <td class="dataTableHeadingContent" align="right"><?php echo (int)($downloads_sum / $count); ?></td>
                        <td class="dataTableHeadingContent" align="right"><?php echo $downloads_unique_sum; ?></td>
                        <td class="dataTableHeadingContent" align="right"><?php echo (int)($downloads_unique_sum / $count); ?></td>
                      </tr>
                      <?php
                        $total += $downloads_sum;
                        $unique_total += $downloads_unique_sum;
                        $downloads_sum = 0;
                        $downloads_unique_sum = 0;
                        $count = 0;
                        $year_count++;
                      }
                      $last_row_year = $downloads['row_year'];
                    }    
                    if ( date('m') != 1 ) {
                      $total += $downloads_sum;
                      $unique_total += $downloads_unique_sum;
                      $year_count++;
                    ?>
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent" align="left">
                        <?php 
                        if ($month <> 0) {
                          echo strtoupper($month_array[$month - 1]);
                        } else {
                          echo TABLE_FOOTER_YEAR;
                        }
                        ?>
                      </td>
                      <td class="dataTableHeadingContent" align="left"><?php echo (tep_not_null($year) ? $year : $last_row_year); ?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo $downloads_sum; ?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo ($count == 0 ? '' : (int)($downloads_sum / $count)); ?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo $downloads_unique_sum; ?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo ($count == 0 ? '' : (int)($downloads_unique_sum / $count)); ?></td>
                    </tr>
                    <?php
                  }
                  ?>
                </table></td>
              </tr>
              <?php
                if ( !isset($_GET['month']) ) {
                ?>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <?php
                    $total_avg = ($year_count > 0) ? $total / $year_count : 0;
                    $unique_tot_avg = ($year_count > 0) ? $unique_total / $year_count : 0;
                  ?>
                  <td class="main"><b><?php echo TABLE_HEADING_YEAR_AVERAGE . '</b>' . (int)($total_avg) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . TABLE_HEADING_YEAR_UNIQUE_AVERAGE . '</b>' . (int)($unique_tot_avg); ?></td>
                </tr>
                <?php
                } else {
                ?>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <?php
                    $d_sum = ($downloads_sum > 0) ? $downloads_sum : 0;
                    $unique_sum = ($downloads_unique_sum > 0) ? $downloads_unique_sum : 0;                  
                  ?>
                  <td class="main"><b><?php echo TABLE_HEADING_MONTH_TOTAL . '</b>' . $d_sum . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . TABLE_HEADING_MONTH_UNIQUE_TOTAL . '</b>' . $unique_sum; ?></td>
                </tr>
                <tr>
                  <?php
                    $download_avg = ($month_count > 0) ? $downloads_sum / $month_count : 0;
                    $unique_avg = ($month_count > 0) ? $downloads_unique_sum / $month_count : 0;
                  ?>
                  <td class="main"><b><?php echo TABLE_HEADING_DAILY_AVERAGE . '</b>' . (int)($download_avg) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . TABLE_HEADING_DAILY_UNIQUE_AVERAGE . '</b>' . (int)($unique_avg); ?></td>
                </tr>
                <?php
                }
              }
              ?>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
    <!-- body_text_eof //-->
  </tr>
</table></td>
</tr>
</table>
</div> 
<!-- body_eof //-->
<!-- footer //-->
<?php  // suppress footer for printer-friendly version
require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
