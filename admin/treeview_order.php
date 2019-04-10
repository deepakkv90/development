<?php
/*
  $Id: treeview.php,v 1.0 10/08/2005 Beer Monster Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

   include(DIR_WS_CLASSES . 'order.php');
   require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

 if (isset($_GET['oID'])){
    $oID = tep_db_prepare_input($_GET['oID']);
    }else if (isset($_POST['oID'])){
      $oID = $_POST['oID'] ;
    } else {
     $oID = '' ;
    }

  $order = new order($oID);
  $customer_id = $order->customer['id'];
   //gets customer group ID
  if($customer_id=="")
  {
    $customer_group_id="G";
  }
  else
  {
    $getcustomer_GroupID_query = tep_db_query("select customers_group_id   from " . TABLE_CUSTOMERS . " where  customers_id = '" . (int)$customer_id . "'");
    $getcustomer_GroupID = tep_db_fetch_array($getcustomer_GroupID_query);
    $customer_group_id=$getcustomer_GroupID['customers_group_id'];
  }

?>
<!doctype html public "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
  <title><!-- Catalog Tree --><?php echo TREEVIEW_TXT_1;?></title>
<script type="text/javascript" src="includes/prototype.js"></script> 
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
  <script type="text/javascript" src="includes/javascript/dtree.js"></script>

</head>

<body>
<div class="dtree"><form>
<p><a href="javascript: d.openAll();"><!-- open all --><?php echo TREEVIEW_TXT_2;?></a> | <a href="javascript: d.closeAll();"><!-- close all --><?php echo TREEVIEW_TXT_3;?></a></p>
<?php
    $defaultlanguage_query_raw ="SELECT l.languages_id FROM " . TABLE_LANGUAGES . " as l WHERE l.code ='" . DEFAULT_LANGUAGE . "'";
    $defaultlanguage_query = tep_db_query($defaultlanguage_query_raw);
    $defaultlanguage= tep_db_fetch_array($defaultlanguage_query);


echo "<script type='text/javascript'>
    <!--
    d = new dTree('d'); \n
    d.add(0,-1,'Catalog','','');\n";

    $categories_query_raw = "SELECT c.categories_id, cd.categories_name, c.parent_id
    FROM " . TABLE_CATEGORIES_DESCRIPTION . " AS cd
    INNER JOIN categories as c ON cd.categories_id = c.categories_id
    WHERE
    cd.language_id =" . (int)$languages_id . "
    and (c.products_group_access like \"%,".$customer_group_id.",%\" or c.products_group_access like \"".$customer_group_id.",%\" or c.products_group_access like \"%,".$customer_group_id."\" or c.products_group_access='".$customer_group_id."')
     ORDER BY c.sort_order, cd.categories_name";
    $categories_query = tep_db_query($categories_query_raw);
    while ($categories = tep_db_fetch_array($categories_query)) {
      echo "d.add(" . $categories['categories_id'] . "," . $categories['parent_id'] . ",'" . addslashes($categories['categories_name']) . "','', '');\n"; //,," . $categories['categories_id'] . ",,,); \n";

    } //end while

    $products_query_raw = "SELECT distinct pc.categories_id, pd.products_id, pd.products_name
    FROM " . TABLE_PRODUCTS . " p,
    " .  TABLE_PRODUCTS_TO_CATEGORIES . " as pc INNER JOIN " . TABLE_PRODUCTS_DESCRIPTION . " as pd ON pc.products_id = pd.products_id
         where pc.products_id=p.products_id
         and (p.products_status = '1'
         or (p.products_status <> '1' and p.products_parent_id <> 0))
     and (p.products_group_access like \"%,".$customer_group_id.",%\" or p.products_group_access like \"".$customer_group_id.",%\" or p.products_group_access like \"%,".$customer_group_id."\" or p.products_group_access='".$customer_group_id."')
    ";

    $products_query = tep_db_query($products_query_raw);

    while ($products = tep_db_fetch_array($products_query)) {
      echo "d.add(" . $products['products_id'] . "0000," . $products['categories_id'] .",'" . addslashes($products['products_name']) . "','', '<input type=checkbox name=products onClick=cycleCheckboxes(this.form) value=" . $products['products_id'] . ">');\n"; //,," . $products['products_id'] . ",,,); \n";

    }//end while

?>
document.write(d);

    //-->
  </script>
 <?php
 echo '<b>Select only one product </b><br>';
 ;?>

<INPUT TYPE="BUTTON" onClick="cycleCheckboxes(this.form)" VALUE="Select Product"></form>
<script type='text/javascript'>
    <!--
function cycleCheckboxes(what) {

window.opener.document.add_product.add_product_products_id.value ="";
    for (var i = 0; i<what.elements.length; i++) {
        if ((what.elements[i].name.indexOf('products') > -1)) {
            if (what.elements[i].checked) {
                window.opener.document.add_product.add_product_products_id.value += what.elements[i].value;
            }
        }
    }

    for (var i = 0; i<what.elements.length; i++) {
        if ((what.elements[i].name.indexOf('categories') > -1)) {
            if (what.elements[i].checked) {
                window.opener.document.add_product.add_product_products_id.value += what.elements[i].value;
            }
        }
    }
window.close();
}
    //-->
</script>
</div>
</body>

</html>
