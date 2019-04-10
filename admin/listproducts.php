<?php
/*
  $Id: validproducts.php,v 0.01 2002/08/17 15:38:34 Richard Fielder

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com



  Copyright (c) 2002 Richard Fielder

  Released under the GNU General Public License
*/

require('includes/application_top.php');
?>
<html>
<head>
<title><?php echo VALID_CATEGORIES_PRODUCTS_LIST;?></title>
<script type="text/javascript" src="includes/prototype.js"></script> 
<style type="text/css">
<!--
h2 {  text-align: center}
p {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: xx-small}
th {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: xx-small; background:#000; color:#fff;}
td {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: xx-small; padding-left:4px;}
tr.n {background:#EBEBEB;}
-->
</style>
<head>
<body>
    <table width="550" border="0" cellspacing="1" cellpadding="1">
        <tr>
            <td colspan="3"><h2><?php echo TEXT_VALID_PRODUCTS_LIST;?></h2></td>
        </tr>
<?php 
   $coupon_get=tep_db_query("select restrict_to_products,restrict_to_categories from " . TABLE_COUPONS . "  where coupon_id='" . (int)tep_db_input($_GET['cid']) . "'");
   $get_result=tep_db_fetch_array($coupon_get);

    echo "<tr><th>" . TEXT_VALID_PRODUCTS_ID . "</th><th>" . TEXT_VALID_PRODUCTS_NAME . "</th><th>" . TEXT_VALID_PRODUCTS_MODEL . "</th></tr><tr>\n";
    $pr_ids = split("[,]", $get_result['restrict_to_products']);
    for ($i = 0; $i < count($pr_ids); $i++) {
      $result = tep_db_query("SELECT p.products_id, p.products_model, pd.products_name FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd WHERE p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "'and p.products_id = '" . $pr_ids[$i] . "'");
      if ($row = tep_db_fetch_array($result)) {
            echo '<tr class="n"><td><a href="' . HTTP_CATALOG_SERVER . DIR_WS_CATALOG . 'product_info.php?products_id='.$row["products_id"] .'" target="_blank">'. tep_image(DIR_WS_ICONS . 'magnifier.png', ICON_PREVIEW) . '</a> ' . $row['products_id'] . '</td>' . "\n";
            echo '<td>' . $row['products_name'] . '</td>' . "\n";
            echo '<td>' . $row['products_model'] . '</td>' . "\n";
            echo '</tr>' . "\n";
      }
    }
      echo "</table>\n";
?>
<br>
<table width="550" border="0" cellspacing="1">
    <tr>
        <td align=middle><input type="button" value="Close Window" onClick="window.close()"></td>
    </tr>
</table>
</body>
</html>
