<?php
/*
  $Id: prods_by_vendor.php,v 1.1 2008/06/22 22:50:52 datazen Exp $

  Modified for MVS V1.0 2006/03/25 JCK/CWG
  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

$vendors_id = 0;
if (isset($_GET['vendors_id']) ) {
  $vendors_id = (int)$_GET['vendors_id'];
} else if (isset($_POST['vendors_id']) ) {
  $vendors_id = (int)$_POST['vendors_id'];
}

$line2 = $line;
if (!isset($line)) {
  $line = 'p.products_price';
}
if ($line == 'prod') {
  $line = 'pd.products_name';
} elseif ($line == 'vpid'){
  $line = 'p.vendors_prod_id';
} elseif ($line == 'pid'){
  $line = 'p.products_id';
} elseif ($line == 'qty'){
  $line = 'p.products_quantity';
} elseif ($line == 'vprice'){
  $line = 'p.vendors_product_price';
} elseif ($line == 'price'){
  $line = 'p.products_price';
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
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();"> 
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top">
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                <?php
                  //vendors_email start
                  $vendors_array = array(array('id' => '1', 'text' => 'NONE'));
                  $vendors_query = tep_db_query("select vendors_id, vendors_name from " . TABLE_VENDORS . " order by vendors_name");
                  while ($vendors = tep_db_fetch_array($vendors_query)) {
                    $vendors_array[] = array('id' => $vendors['vendors_id'],
                                             'text' => $vendors['vendors_name']);
                  }
                  ?>
                  <td class="main" align="left"><?php echo TABLE_HEADING_VENDOR_CHOOSE . ' '; ?><?php echo tep_draw_form('vendors_report', FILENAME_PRODS_VENDORS) . tep_draw_pull_down_menu('vendors_id', $vendors_array,(($vendors_id > 0) ? $vendors_id : ''),'onChange="this.form.submit()";');?></form></td>
                  <td class="main" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_VENDORS) . '"><b>Go To Vendors List</a>';?><td>
                </tr>
                <tr>
                  <td class="main" align="left"><br>
                    <?php
                    if ($show_order == 'desc') {
                      echo 'Click for <a href="' . tep_href_link(FILENAME_PRODS_VENDORS, '&vendors_id=' . $vendors_id . '&line=' . $line . '&show_order=asc') . '"><b>ascending order</b></a>';
                    } else {
                      echo 'Click for <a href="' . tep_href_link(FILENAME_PRODS_VENDORS, '&vendors_id=' . $vendors_id . '&line=' . $line . '&show_order=desc') . '"><b>descending order</b></a>';
                    }
                    ?>
                  </td>
                </tr>
              </table>
              <table border="0" width="100%" cellspacing="0" cellpadding="0"> 
              <?php
              if ($vendors_id > 0) { 
                $vend_query_raw = "select vendors_name as name from " . TABLE_VENDORS . " where vendors_id = '" . $vendors_id . "'";
                $vend_query = tep_db_query($vend_query_raw);
                $vendors = tep_db_fetch_array($vend_query); 
                ?>
                <tr>
                  <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                      <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="1">
                        <tr class="dataTableHeadingRow">
                          <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_VENDOR; ?></td>
                          <td class="dataTableHeadingContent" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_PRODS_VENDORS, '&vendors_id=' . $vendors_id . '&line=prod') . '">' . TABLE_HEADING_PRODUCTS_NAME . '</a>'; ?>&nbsp;</td>
                          <td class="dataTableHeadingContent" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_PRODS_VENDORS, '&vendors_id=' . $vendors_id . '&line=vpid') . '">' . TABLE_HEADING_VENDORS_PRODUCT_ID . '</a>'; ?></td>
                          <td class="dataTableHeadingContent" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_PRODS_VENDORS, '&vendors_id=' . $vendors_id . '&line=pid') . '">' .  TABLE_HEADING_PRODUCTS_ID . '</a>'; ?></td>
                          <td class="dataTableHeadingContent" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_PRODS_VENDORS, '&vendors_id=' . $vendors_id . '&line=qty') . '">' .  TABLE_HEADING_QUANTITY . '</a>'; ?></td>
                          <td class="dataTableHeadingContent" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_PRODS_VENDORS, '&vendors_id=' . $vendors_id . '&line=vprice') . '">' .  TABLE_HEADING_VENDOR_PRICE . '</a>'; ?></td>
                          <td class="dataTableHeadingContent" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_PRODS_VENDORS, '&vendors_id=' . $vendors_id . '&line=price') . '">' .  TABLE_HEADING_PRICE . '</a>'; ?></td>
                        </tr>
                        <tr class="dataTableRow">
                          <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_VENDORS, '&vendors_id=' . $vendors_id . '&action=edit') . '" TARGET="_blank"><b>' . $vendors['name'] . '</a></b>'; ?></td>
                          <td class="dataTableContent"><?php echo ''; ?></td>
                          <td class="dataTableContent"><?php echo ''; ?></td>
                          <td class="dataTableContent"><?php echo ''; ?></td>
                          <td class="dataTableContent"><?php echo ''; ?></td>
                          <td class="dataTableContent"><?php echo ''; ?></td>
                          <td class="dataTableContent"><?php echo ''; ?></td>
                        </tr>
                        <?php
                        $rows = 0;
                        if($show_order == 'desc') {
                          $products_query_raw = "select p.products_id, p.vendors_id, pd.products_name, p.products_quantity , p.products_price, p.vendors_product_price, p.vendors_prod_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.vendors_id = " . $vendors_id . " and pd.language_id = " . $languages_id . " order by " . $line . " desc";
                        } elseif ($show_order  == 'asc') {
                          $products_query_raw = "select p.products_id, p.vendors_id, pd.products_name, p.products_quantity , p.products_price, p.vendors_product_price, p.vendors_prod_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.vendors_id = " . $vendors_id . " and pd.language_id = " . $languages_id . " order by " . $line . " asc";
                        } else {
                          $products_query_raw = "select p.products_id, p.vendors_id, pd.products_name, p.products_quantity , p.products_price, p.vendors_product_price, p.vendors_prod_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.vendors_id = " . $vendors_id . " and pd.language_id = " . $languages_id . " order by " . $line . "";
                        }
            $products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
                        $products_query = tep_db_query($products_query_raw);
                        while ($products = tep_db_fetch_array($products_query)) {
                          $rows++;
                          if (strlen($rows) < 2) {
                            $rows = '0' . $rows;
                          }
                          ?>
                          <tr class="dataTableRow">
                            <?php 
                            if($products['vendors_prod_id'] == '') {
                              $products['vendors_prod_id']= 'None Specified';
                            } 
                            ?>
                            <td class="dataTableContent"><?php echo ''; ?></td>
                            <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=new_product&pID=' . $products['products_id']) . '" TARGET="_blank"><b>' . $products['products_name'] . '</a></b>'; ?></td>
                            <td class="dataTableContent"><?php echo $products['vendors_prod_id']; ?></td>
                            <td class="dataTableContent"><?php echo $products['products_id']; ?></td>
                            <td class="dataTableContent" align="left"><?php echo $products['products_quantity']; ?>&nbsp;</td>
                            <td class="dataTableContent"><?php echo $products['vendors_product_price']; ?></td>
                            <td class="dataTableContent"><?php echo $products['products_price']; ?></td>
                          </tr>
                          <?php
                        }
                        ?>
                      </table></td>
                    </tr>
                  </table></td>
                </tr>
                <?php
              }
              ?>
              </table>
            </td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top">
                  <?php
                  if (is_object($products_split))
                    echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS);
                  ?>
                </td>
                <td class="smallText" align="right">
                  <?php
                  if (is_object($products_split))
                    echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],"vendors_id=$vendors_id");
                  ?>
                </td>
              </tr>
            </table></td>
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>