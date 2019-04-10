<?php 
include('includes/application_top.php');

    if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) { 
        header("Content-type: application/xhtml+xml"); 
    } else { 
        header("Content-type: text/xml"); 
    } 
    
    echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
    echo '<tree id="0">' . "\n";
    getLevelFromDB(0);
    $prod_query = tep_db_query("SELECT p.products_id, pd.products_name FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c WHERE p.products_id = pd.products_id AND pd.language_id = '" . $languages_id . "' AND p2c.products_id = p.products_id AND p2c.categories_id = '0'");
    while( $prod = tep_db_fetch_array($prod_query) ) {
        echo '<item text="' . str_rep($prod['products_name']) . '" id="p_' . $prod['products_id'] . '" im0="leaf.gif" im1="leaf.gif" im2="leaf.gif"></item>' . "\n";
    }
    
    echo '</tree>';
    
    //print one level of the tree, based on parent_id
    function getLevelFromDB($parent_id){
        global $languages_id;
        $cat_query_raw = "SELECT c.categories_id, cd.categories_name FROM " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd WHERE c.categories_id = cd.categories_id AND cd.language_id = '" . $languages_id . "' AND c.parent_id = '" . $parent_id . "' AND cd.categories_name <> 'Independent Stores' ORDER BY cd.categories_name";
        $cat_query = tep_db_query($cat_query_raw);
        while($cat = tep_db_fetch_array($cat_query)){  
            echo '<item text="' . str_rep($cat['categories_name']) . '" id="c_' . tep_get_generated_category_path_ids($cat['categories_id']) . '" im0="folderClosed.gif" im1="folderOpen.gif" im2="folderClosed.gif">' . "\n";
            $prod_query = tep_db_query("SELECT p.products_id, pd.products_name FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c WHERE p.products_id = pd.products_id AND pd.language_id = '" . $languages_id . "' AND p2c.products_id = p.products_id AND p2c.categories_id = '" . $cat['categories_id'] . "'");
            while( $prod = tep_db_fetch_array($prod_query) ) {
                echo '<item text="' . str_rep($prod['products_name']) . '" id="p_' . $prod['products_id'] . '" im0="leaf.gif" im1="leaf.gif" im2="leaf.gif"></item>' . "\n";
            }
            getLevelFromDB($cat['categories_id']);
            echo '</item>' . "\n";
        }
    }

  function str_rep($row){
    $row = str_replace('&amp;', '&', $row);
    $row = str_replace('&', '&amp;', $row);
    $row = str_replace('>', '&gt;', $row);
    $row = str_replace('<', '&lt;', $row);
    return $row;
  }
?>