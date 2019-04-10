<?php
/*
  $Id: treeview.php,v 1.0 05/08/2008 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <title><?php echo TREEVIEW_TXT_1;?></title>
  <script type="text/javascript" src="includes/prototype.js"></script> 
  <link rel="StyleSheet" href="includes/stylesheet.css" type="text/css" />
  
  <link rel="STYLESHEET" type="text/css" href="includes/javascript/dhtmlxTree-v43/codebase/dhtmlxtree.css">
  <script  language="javascript" src="includes/javascript/dhtmlxTree-v43/codebase/dhtmlxtree.js"></script>    
    
  
  <script type='text/javascript'><!--
    function cycleCheckboxes() {
      prod_value = "";
      cat_value = "";
      if (checked_value = tree.getAllChecked()) {
        value_array = checked_value.split(",");
        for (i = 0; i < value_array.length; i++) {
          pair = value_array[i].split("_");
          if (pair[0] == "p") {
            prod_value += pair[1] + ",";
          } else {
            cat_value += pair[1] + ",";
          }
        }
      }
<?php
    if (isset($_GET['script']) && tep_not_null($_GET['script'])) {
      echo '      ' . urldecode($_GET['script']) . "\n";
    }
?>
      window.close();
    }
//--></script>
	
</head>
<body>


<div class="dtree">
  <p><a href="javascript:void(0);" onclick="tree.openAllItems(0);"><?php echo TREEVIEW_TXT_2;?></a> | <a href="javascript:void(0);" onclick="tree.closeAllItems(0);"><?php echo TREEVIEW_TXT_3;?></a></p>
  <br>
  <div id="cat_tree" style="width:100%;height:400"></div> 
  <script>
    tree = new dhtmlXTreeObject("cat_tree", "100%", "100%", 0); 
    tree.enableCheckBoxes(1);
    tree.enableThreeStateCheckboxes(true);
    tree.setImagePath("includes/javascript/dhtmlxTree-v43/codebase/imgs/dhxtree_skyblue/"); 
    tree.loadXML("<?php echo tep_href_link('get_categories.php'); ?>"); 
  </script>
  <br><br>
  <input type="button" onClick="cycleCheckboxes()" value="OK" />
</div>
</body>
</html>
