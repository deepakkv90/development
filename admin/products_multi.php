<?php
/*
  $Id: products_multi.php,v 3.0B 2008/16/12 00:18:17 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce
  Copyright (c) 2003 Vlad Savitsky  sr@ibis-project.de
  
  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $cat_stat=0; // internal use -- 0 = no / 1 = yes
 
function tep_is_it_subproduct($products_id){
  $product_sub_product_query = tep_db_query("select products_parent_id from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
  while ($product_sub_product = tep_db_fetch_array($product_sub_product_query)){
  $product_sub_check = $product_sub_product['products_parent_id'];
  }
    if ($product_sub_check > 0){
        return $product_sub_check;
    } 
}
  
if (isset($_GET['search'])) {
  $search = $_GET['search'] ;
} else if (isset($_POST['search'])) {
  $search = $_POST['search'] ;
} else {
  $search = '' ;
}  
if (isset($_GET['checkall'])) {
  $checkall = $_GET['checkall'] ;
} else if (isset($_POST['checkall'])) {
  $checkall = $_POST['checkall'] ;
} else {
  $checkall = '' ;
}
if (isset($_GET['cID'])) {
  $cID = $_GET['cID'] ;
} else if (isset($_POST['cID'])) {
  $cID = $_POST['cID'] ;
} else {
  $cID = $current_category_id ;
}
if (isset($_GET['pID'])) {
  $pID = $_GET['pID'] ;
} else if (isset($_POST['pID'])) {
  $pID = $_POST['pID'] ;
} else {
  $pID = '' ;
}
if (isset($_GET['cPath'])) {
  $cPath = $_GET['cPath'] ;
} else if (isset($_POST['cPath'])) {
  $cPath = $_POST['cPath'] ;
} else {
  $cPath = '' ;
}
if (isset($_GET['action'])) {
  $action = $_GET['action'] ;
} else if (isset($_POST['action'])) {
  $action = $_POST['action'] ;
  } else {
  $action = '' ;
}
if (isset($_GET['choose'])) {
  $choose = $_GET['choose'] ;
} else if (isset($_POST['choose'])) {
  $choose = $_POST['choose'] ;
  } else {
  $choose = '' ;
}
if (isset($_GET['del_art'])) {
  $del_art = $_GET['del_art'] ;
} else if (isset($_POST['del_art'])) {
  $del_art = $_POST['del_art'] ;
  } else {
  $del_art = '' ;
} 
if (isset($_GET['product_categories'])) {
  $product_categories = $_GET['product_categories'] ;
} else if (isset($_POST['product_categories'])) {
  $product_categories = $_POST['product_categories'] ;
  } else {
  $product_categories = '' ;
}  

$reset_cache = false;  // do not clear cache if there is no change
$error_subproduct = false;  
              
  if (tep_not_null($action)) {
    
    switch ($action) {
      
        case 'delete_product_confirm':
        foreach ($choose as $products_id) {
            if ($del_art=='complete') {
                // sub products
                $sub_products_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_parent_id = '" . tep_db_input($products_id) . "'");
                while ($sub_products = tep_db_fetch_array($sub_products_query)) {
                    for ($i=0, $n=sizeof($product_categories); $i<$n; $i++) {
                        tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . tep_db_input($sub_products['products_id']) . "' and categories_id = '" . (int)$product_categories[$i] . "'");
                    }
                $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$sub_products['products_id'] . "'");
                $product_categories1 = tep_db_fetch_array($product_categories_query);
                if ($product_categories1['total'] == '0') {
                    tep_remove_product($sub_products['products_id']);
                }
                }
                // sub products eof
                
                for ($i=0, $n=sizeof($product_categories); $i<$n; $i++) {
                    tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$product_categories[$i] . "'");
                }
                $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "'");
                $product_categories = tep_db_fetch_array($product_categories_query);
                if ($product_categories['total'] == '0') {
                    tep_remove_product($products_id);
                }
              
            }  // del_art == complete
            elseif ($del_art=='this_cat') {
                $duplicate_check = tep_db_fetch_array(tep_db_query("select count(*) as total from ".TABLE_PRODUCTS_TO_CATEGORIES. " where products_id='".tep_db_input($products_id). "' and categories_id<>'". tep_db_input($current_category_id)."'"));
                //If product exists only in this category we remove it totally
                //If we have this product in other categories we just remove from TABLE_PRODUCTS_TO_CATEGORIES.
                if ($duplicate_check['total']>0) {
                    tep_db_query("delete from ".TABLE_PRODUCTS_TO_CATEGORIES. " where products_id='". $products_id."' and categories_id='".$current_category_id."'");
                    $reset_cache = true;
                } else {
                    tep_remove_product($products_id);
                }
            } //del_art==this_cat
        } //foreach
            
            if (USE_CACHE == 'true'  && $reset_cache) {
                tep_reset_cache_block('categories');
                tep_reset_cache_block('also_purchased');
            }
              tep_redirect(tep_href_link(FILENAME_PRODUCTS_MULTI, 'cPath=' . $cPath));
              break;
              
              
        case 'link_to_confirm':
              $categories_id = tep_db_prepare_input($_REQUEST['categories_id']);
              foreach ($choose as $products_id) {
                  if( !tep_is_it_subproduct($products_id) ){ //do not allow linking subproducts
                        if ($_REQUEST['categories_id'] != $current_category_id) {
                            $check=tep_db_fetch_array(tep_db_query("select count(*) as total from ". TABLE_PRODUCTS_TO_CATEGORIES." where products_id='".tep_db_input($products_id) . "' and categories_id='". tep_db_input($categories_id) . "'"));
                            if ($check['total']<'1')    tep_db_query("insert into ".TABLE_PRODUCTS_TO_CATEGORIES. " (products_id, categories_id) values ('".tep_db_input($products_id)."', '".tep_db_input($categories_id)."')"); 
                            $reset_cache = true;
                        } else {
                            $messageStack->add_session('search',ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
                        }
                  } else {
                      $error_subproduct = true;
                  }
              }

              if (USE_CACHE == 'true' && $reset_cache) {
                  tep_reset_cache_block('categories'); 
                  tep_reset_cache_block('also_purchased');
              }
              
              if($error_subproduct) {
                  $messageStack->add_session('search',ERROR_LINKING_SUBPRODUCT_NOT_ALLOWED, 'error');
              }

              tep_redirect(tep_href_link(FILENAME_PRODUCTS_MULTI, 'cPath=' . $categories_id . '&pID=' . $products_id));
              break;
              
      case 'move_product_confirm':
                $destination_not_selected = false;
                $new_categories_id=tep_db_prepare_input($_REQUEST['categories_id']);
                foreach ($choose as $products_id) {
                    if( !tep_is_it_subproduct($products_id) ){ //do not allow moving subproducts
                       if ($_REQUEST['categories_id'] != $current_category_id) {
                          $duplicate_check_query=tep_db_query("select count(*) as total from ".TABLE_PRODUCTS_TO_CATEGORIES. " where products_id='".tep_db_input($products_id)."' and categories_id='". tep_db_input($new_categories_id)."'");
                          $duplicate_check = tep_db_fetch_array($duplicate_check_query);
                          if($duplicate_check['total']<1)  tep_db_query("update ".TABLE_PRODUCTS_TO_CATEGORIES. " set categories_id ='".tep_db_input($new_categories_id). "' where products_id='".tep_db_input($products_id)."' and categories_id='". $current_category_id."'");   

                       $subproducts_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_parent_id  = '" . (int)$products_id . "'");
                        while($subproducts = tep_db_fetch_array($subproducts_query)){ //if moving parent, move subpro to same category
                             tep_db_query("update ".TABLE_PRODUCTS_TO_CATEGORIES. " set categories_id ='".tep_db_input($new_categories_id). "' where products_id='".$subproducts['products_id']."' and categories_id='". $current_category_id."'");
                             $error_subproduct = false;
                        }
                        $reset_cache = true;
                       } else {
                           $destination_not_selected = true;
                           $error_subproduct = false;
                       }
                    } else {
                        $error_subproduct = true;
                    }
              }
              
            if (USE_CACHE == 'true' && $reset_cache) {
                tep_reset_cache_block('categories');
                tep_reset_cache_block('also_purchased');
            }
              if($destination_not_selected) {
                  $messageStack->add_session('search',ERROR_DESTINATION_CATEGORY_NOT_SELECTED, 'error');
              }
              if($error_subproduct) {
                  $messageStack->add_session('search',ERROR_MOVING_SUBPRODUCT_NOT_ALLOWED, 'error');
              }
            
              tep_redirect(tep_href_link(FILENAME_PRODUCTS_MULTI, 'cPath='.$new_categories_id.'&pID='.$products_id));
              break;

      case 'copy_to_confirm':
              $new_old = array();
              $categories_id = tep_db_prepare_input($_REQUEST['categories_id']);
              ksort($choose);
              foreach ($choose as $products_id) {
                  // it is normal single product
                  $product_query = tep_db_fetch_array(tep_db_query('select * from '.TABLE_PRODUCTS.' where products_id="'.(int)$products_id.'"'));
                  $old_product_id = $product_query['products_id'];
                  $product_query['products_id'] = '';
                  if ($product_query['products_parent_id'] > 0) {  // if a sub product
                      if (isset($new_old[$product_query['products_parent_id']])) {  // if there is a new arent, convert it
                          $product_query['products_parent_id'] = $new_old[$product_query['products_parent_id']];
                      } else {
                          $product_query['products_parent_id'] = 0;  // where there is no new parent
                      }
                  }
                  tep_db_perform(TABLE_PRODUCTS, $product_query);
                  $dup_products_id = tep_db_insert_id();
                  $new_old[$old_product_id] = $dup_products_id;
                    
                  $description_query = tep_db_query("select language_id, products_name, products_blurb, products_description, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");
                  while ($description = tep_db_fetch_array($description_query)) {
                      tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_blurb, products_description, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_url, products_viewed) values ('" . (int)$dup_products_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['products_name']) . "', '" . tep_db_input($description['products_blurb']) . "', '" . tep_db_input($description['products_description']) . "', '" . tep_db_input($description['products_head_title_tag']) . "', '" . tep_db_input($description['products_head_desc_tag']) . "', '" . tep_db_input($description['products_head_keywords_tag']) . "', '" . tep_db_input($description['products_url']) . "', '0')");
                  }
                  tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$dup_products_id . "', '" . (int)$categories_id . "')");
                    
                  $copy_attributes_delete_first='1';
                  $copy_attributes_duplicates_skipped='1';
                  $copy_attributes_duplicates_overwrite='0';
                  if (DOWNLOAD_ENABLED == 'true') {
                      $copy_attributes_include_downloads='1';
                      $copy_attributes_include_filename='1';
                  } else {
                      $copy_attributes_include_downloads='0';
                      $copy_attributes_include_filename='0';
                  }
                  tep_copy_products_attributes($products_id,$dup_products_id);
                  $reset_cache = true;
              }
             if (USE_CACHE == 'true' && $reset_cache) {
                tep_reset_cache_block('categories');
                tep_reset_cache_block('also_purchased');
             }
              tep_redirect(tep_href_link(FILENAME_PRODUCTS_MULTI, 'cPath='.$categories_id.'&pID=' .$dup_products_id));
              break;

              case 'setflag':     
                if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {     
                  if (isset($pID)) {
                    tep_set_product_status($pID, $_GET['flag']);
                  }
                  if (USE_CACHE == 'true') {
                    tep_reset_cache_block('categories');
                    tep_reset_cache_block('also_purchased');
                  }
                }
               
                break;
    }
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

<script language="JavaScript" type="text/javascript">
<!--
function setCheckboxes(the_form, do_check) {
  var frm = document.forms[the_form];
  var checkforid = "checkbox_choose_";
  for(i=0; i < frm.elements.length; i++){
    if(frm.elements[i].id.substr(0, checkforid.length) == checkforid){            
      frm.elements[i].checked = do_check;
    }
  }
}

// Select all subproducts when parent product is selected.
checked=false;
function SelectSubProducts (parentid) {
 
    var frm = document.mainForm;
    var flag = false;
    var parentid = "checkbox_choose_"+parentid;
    for(i=0;i < frm.elements.length;i++){
            if(frm.elements[i].id == parentid){
                flag = frm.elements[i].checked;
            }
            if(frm.elements[i].id.substr(0,(parentid.length+1)) == parentid+"_"){
                
                frm.elements[i].checked=flag;
            }
        }
      }

//-->
</script>



</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<div id="spiffycalendar" class="text"></div>
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
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">
            <?php
            echo HEADING_TITLE;
            //echo $action.count($choose).$categories_id.$current_category_id; // nur zu testzwecken - only for tests
            ?>
            </td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr><?php echo tep_draw_form('search', FILENAME_PRODUCTS_MULTI, tep_get_all_get_params(), 'post'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search', $search); ?></td>
              </form></tr>
              <tr><?php echo tep_draw_form('goto', FILENAME_PRODUCTS_MULTI, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"'); ?></td>
              </form></tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php echo tep_draw_form('mainForm', FILENAME_PRODUCTS_MULTI, 'cPath='.$cPath.'&cID='. $cID, 'post','','SSL'); ?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_CHOOSE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="left">(No.) / <?php echo TABLE_HEADING_STATUS; ?></td>
                <!--<td class="dataTableHeadingContent" align="left"><?php// echo TABLE_HEADING_MANUFACTURERS_NAME; ?></td> Uncomment both HTML and PHP if you have Manufacturers -->
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_PRODUCTS_QUANTITY; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?> </td>
              </tr>
<?php
    $categories_count = 0;
    $rows = 0;
    if (tep_not_null($search)) {
      if($cat_stat==1) {
          $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' and cd.categories_name like '%" . $search . "%' order by c.sort_order, cd.categories_name");
      } else {
          $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' and cd.categories_name like '%" . $search . "%' order by c.sort_order, cd.categories_name");
      }
    } else {
      if($cat_stat==1) {
          $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.categories_name");
      } else {
          $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.categories_name");
      }
    }
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $rows++;

// Get parent_id for subcategories if search
      if (tep_not_null($search)) $cPath= $categories['parent_id'];

      if ( (!tep_not_null($cID) && !tep_not_null($pID) || $cID == $categories['categories_id']) && (!isset($cInfo) && substr($action, 0, 4) != 'new_') ) {
        $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
        $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));

        $cInfo_array = array_merge($categories, $category_childs, $category_products);
        $cInfo = new objectInfo($cInfo_array);
      }

        if ( (is_object($cInfo)) && ($categories['categories_id'] == $cInfo->categories_id) ) {
        echo '      <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS_MULTI,  tep_get_path($categories['categories_id'], 'SSL')) . '\'">' . "\n";
        } else {
        echo '      <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS_MULTI,  tep_get_path($categories['categories_id'], 'SSL')) . '\'">' . "\n";
        }
        echo '          <td class="dataTableContent"><a href="' . tep_href_link(FILENAME_PRODUCTS_MULTI, tep_get_path($categories['categories_id']),'SSL') . '">' . tep_image(DIR_WS_ICONS . 'folder.png', ICON_FOLDER) . '</a> <b>' . $categories['categories_name'] . '</b></td>' . "\n";
        echo '          <td class="dataTableContent" align="center">' . tep_childs_in_category_count($categories['categories_id']) . ' / ' . tep_products_in_category_count($categories['categories_id']) . '</td>' . "\n";
        echo '          <td class="dataTableContent" align="center"></td>' . "\n";
        echo '          <td class="dataTableContent" align="center"></td>' . "\n";
        echo '          <td class="dataTableContent" align="right">' .  ( ( (is_object($cInfo)) && ($categories['categories_id'] == $cInfo->categories_id) ) ?  tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', '') : '<a href="' . tep_href_link(FILENAME_PRODUCTS_MULTI, 'cPath=' . $cPath . '&pID=' . $products['products_id'],'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>') . '</td>' . "\n";
        echo '      </tr>' . "\n";
    }

    $products_count = 0;
    if (tep_not_null($search)) {
      //  $products_query = tep_db_query("select p.products_tax_class_id, p.products_id, p.products_parent_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_model, p2c.categories_id, m.manufacturers_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_id = pd.products_id and p.manufacturers_id=m.manufacturers_id and pd.language_id = '" . $languages_id . "' and p.products_id = p2c.products_id and pd.products_name like '%" . $_REQUEST['search'] . "%' order by pd.products_name"); // original
      $products_query = tep_db_query("select p.products_tax_class_id, p.products_id, p.products_parent_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_model, p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_id = p2c.products_id and pd.products_name like '%" . $search . "%' order by pd.products_name"); // with no Manufacturers
    
    } else {
    // $products_query = tep_db_query("select p.products_tax_class_id, p.products_id, p.products_parent_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_model, m.manufacturers_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and p.manufacturers_id=m.manufacturers_id and pd.language_id = '" . $languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . $current_category_id . "' order by pd.products_name"); // original
      $products_query = tep_db_query("select p.products_tax_class_id, p.products_id, p.products_parent_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_model from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . $current_category_id . "' order by pd.products_name"); // with no Manufacturers
    }
    
    $products_parent_id = '';
    $arr_chosen_products = array();
    if(isset($_POST['choose']) && is_array($_POST['choose'])) {
      $arr_chosen_products = $_POST['choose'];
    }

    while ($products = tep_db_fetch_array($products_query)) {
      $products_count++;
      $rows++;

// Get categories_id for product if search
      if (tep_not_null($search)) $cPath=$products['categories_id'];

      if ( (!tep_not_null($pID) && !tep_not_null($cID) || $pID == $products['products_id']) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 4) != 'new_') ) {
// find out the rating average from customer reviews
        $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . $products['products_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);
        $pInfo_array = array_merge($products, $reviews);
        $pInfo = new objectInfo($pInfo_array);
      }

      if( tep_not_null($products['products_parent_id']) && tep_subproducts_parent($products['products_id']) ){
          $checkbox_id = $products['products_parent_id'] . '_' . $products['products_id'];
          $checkbox_option = '';
          $checkbox_attach = tep_image(DIR_WS_IMAGES . 'img/joinbottom.gif');
      } else {
          $checkbox_id = $products['products_id'];
          $checkbox_option = 'onclick="SelectSubProducts(' . $products['products_id'] . ');"';
          $checkbox_attach = '';
      }
      $td_onclick = 'onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS_MULTI, 'cPath=' . $cPath . '&pID=' . $products['products_id'],'SSL') . '\'"';

      if ( (is_object($pInfo)) && ($products['products_id'] == $pInfo->products_id)) {
        echo '      <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
      } else {
        echo '      <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
      }
      
        echo '          <td class="dataTableContent">' . $checkbox_attach . '<input type="checkbox" name="choose[]" value="' . $products['products_id'] . '" id="checkbox_choose_' . $checkbox_id . '" ' . (in_array($products['products_id'],$arr_chosen_products) ? 'checked' : '' ) . ' ' . $checkbox_option . '></td>' . "\n";
        echo '          <td class="dataTableContent" ' . $td_onclick . '>' . $products['products_name'] . '</td>' . "\n";
        echo '          <td class="dataTableContent" align="center" ' . $td_onclick . '>';
      if ($products['products_status'] == '1') {
              echo tep_image(DIR_WS_IMAGES . 'accept.png', IMAGE_ICON_STATUS_GREEN, 16, 16) . '  <a href="' . tep_href_link(FILENAME_PRODUCTS_MULTI, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath,'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'cancel-off.png', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
      } else {                              
              echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_MULTI, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath,'SSL') . '"  ' . ( tep_subproducts_parent($products['products_id']) ? 'onclick="alert(\'Sub Product you can not turn it on \'); return false"'  : '') .'">' . tep_image(DIR_WS_IMAGES . 'accept-off.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>  ' . tep_image(DIR_WS_IMAGES . 'cancel.png', IMAGE_ICON_STATUS_RED, 16, 16);
      }
        echo '</td>' . "\n";
        echo '          <td class="dataTableContent" align="right" ' . $td_onclick . '>' .  $products['products_quantity'] . '</td>' . "\n";
        echo '          <td class="dataTableContent" align="right" ' . $td_onclick . '>' .  ( ( (isset($pInfo)) && (is_object($pInfo)) && ($products['products_id'] == $pInfo->products_id) ) ? tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '') : '<a href="' . tep_href_link(FILENAME_PRODUCTS_MULTI, tep_get_all_get_params(array('mainForm')) . 'cPath=' . $cPath . '&cID=' . (isset($cID) ? $cID : 0). '&pID=' . $products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>') . '</td>' . "\n";
        echo '      </tr>' . "\n";
    
    } // zu: while ($products = tep_db_fetch_array($products_query)) {

    if ($cPath_array) {
      $cPath_back = '';
      for($i=0;$i<sizeof($cPath_array)-1;$i++) {
        if ($cPath_back == '') {
          $cPath_back .= $cPath_array[$i];
        } else {
          $cPath_back .= '_' . $cPath_array[$i];
        }
      }
    }

    $cPath_back = isset($cPath_back) ? 'cPath=' . $cPath_back : '';
?>     </table>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                      <td>
                        <?php echo tep_image(DIR_WS_ICONS . 'arrow_checkall.gif', '');?>
                        <nobr><a href="<?php echo tep_href_link(FILENAME_PRODUCTS_MULTI, '&cPath='. $cPath.'&cID='.$cID.'&checkall=1');?>" onclick="setCheckboxes('mainForm', true); return false;"><?php echo TEXT_CHOOSE_ALL; ?></a> /  <a href="<?php echo tep_href_link(FILENAME_PRODUCTS_MULTI, '&cPath='. $cPath.'&cID='.$cID);?>" onclick="setCheckboxes('mainForm', false); return false;" ><?php echo TEXT_CHOOSE_ALL_REMOVE; ?></a></nobr></td>
                    <td align="right" class="smallText"></td>
                    <td align="right" class="smallText">
                        <?php if ($cPath)     echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_MULTI,  $cPath_back . '&cID=' . $current_category_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> '; ?> </td>
                  </tr>
                  <tr>
                    <td class="smallText">
                        <input type="hidden" name="cPath" value="<?php echo $cPath; ?>">
                        <input type="hidden" name="cID" value="<?php echo $cID; ?>">
                    </td>
                    <td align="right" class="smallText"></td>
                    <td align="right" class="smallText"></td>
                  </tr>
                  <tr>
                    <td class="smallText"><?php echo TEXT_CATEGORIES . ' ' . $categories_count . '<br>' . TEXT_PRODUCTS . ' ' . $products_count; ?></td>
                    <td align="right" class="smallText" colspan="2">
                    <label for="move_product_confirm"><?php echo TEXT_MOVE_TO; ?></label><input type="radio" name="action" value="move_product_confirm" id="move_product_confirm">
                    <label for="copy_to_confirm"><?php echo IMAGE_COPY_TO; ?></label><input type="radio" name="action" value="copy_to_confirm" id="copy_to_confirm">
                    <label for="link_to_confirm"><?php echo LINK_TO; ?></label><input type="radio" name="action" value="link_to_confirm" id="link_to_confirm" checked>
                     <?php echo tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id); ?></td>
                  </tr>
                  <tr>
                    <td class="smallText"></td>
                    <td align="right" class="smallText" colspan="2">
                    <label for="delete_product"><?php echo DEL_DELETE; ?></label><input type="radio" name="action" value="delete_product" id="delete_product">
                    
                    <select name="del_art" title="<?php echo DEL_CHOOSE_DELETE_ART; ?>" id="del_art"><option value="this_cat" selected><?php echo DEL_THIS_CAT; ?></option><option value="complete"><?php echo DEL_COMPLETE; ?></option></select>
                    </td>
                  </tr>
                  <tr>
                    <td class="smallText"></td>
                    <td align="right" class="smallText"></td>
                    <td align="right" class="smallText">
                    <input type="submit" name="go" value=" go! " title=" go! "></form>
                     </td>
                  </tr>
                  <tr>
                    <td class="dataTableContent" colspan="3">
                    <?php echo TEXT_ATTENTION_DANGER; ?>
                    </td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
    switch ($action) {
         case 'delete_product':
              $heading[] = array('text' => TEXT_INFO_HEADING_DELETE_PRODUCT);
              $contents = array('form' => tep_draw_form('products', FILENAME_PRODUCTS_MULTI, 'action=delete_product_confirm&cPath=' . $cPath,'post','SSL')  );
              $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
              $contents[] = array('text' => '<br><b>' . tep_get_products_name($products_id)  . '</b>' . tep_draw_hidden_field('del_art',($del_art=='complete' ? 'complete' : 'this_cat')) . tep_draw_hidden_field('cPath',$cPath) );
              $product_categories_string = '';
              
              foreach ($choose as $products_id) {
                  if ($del_art=='complete') {
                      $product_categories = tep_generate_category_path($pInfo->products_id, 'product');
                      for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
                          $category_path = '';
                          for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
                              $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
                          }
                          $category_path = substr($category_path, 0, -16);
                      $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . tep_draw_hidden_field('choose[]', $products_id) . '<br>' . "\n";
                      }
                      $product_categories_string = substr($product_categories_string, 0, -4);
                  } elseif ($del_art=='this_cat') {
                      $product_categories_string .=  tep_get_products_name($products_id) . tep_draw_hidden_field('choose[]', $products_id) . '<br>' . "\n";
                  }
              }   
                  $contents[] = array('text' => '<br>' . $product_categories_string);
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_PRODUCTS_MULTI, 'cPath=' . $cPath ) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '</form>');
                  break;

      default:
        if ($rows > 0) {
          if (isset($cInfo) && is_object($cInfo)) { // category info box contents
            $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

            $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added));
            if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified));
            $contents[] = array('text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . $cInfo->categories_image);
            $contents[] = array('text' => '<br>' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br>' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
          } elseif (is_object($pInfo)) { // product info box contents
            $heading[] = array('text' => '<b>' . tep_get_products_name($pInfo->products_id, $languages_id) . '</b>');

            $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($pInfo->products_date_added));
            if (tep_not_null($pInfo->products_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($pInfo->products_last_modified));
            if (date('Y-m-d') < $pInfo->products_date_available) $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . tep_date_short($pInfo->products_date_available));
            $contents[] = array('text' => '<br>' . tep_info_image($pInfo->products_image, tep_get_products_name($pInfo->products_id, $languages_id), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br>' . $pInfo->products_image);
            $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_PRICE_INFO . ' ' . $currencies->format($pInfo->products_price) . '<br>' . TEXT_PRODUCTS_QUANTITY_INFO . ' ' . $pInfo->products_quantity);
            $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%');
          }
        } else { // create category/product info
          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

          $contents[] = array('text' => sprintf(TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS, $parent_categories_name));
        }
        break;
    }

    if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
      echo '            <td width="25%" valign="top">' . "\n";

      $box = new box;
      echo $box->infoBox($heading, $contents);

      echo '            </td>' . "\n";
    }
?>
          </tr>
        </table></td>
      </tr>
<?php
  //}
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</form>
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