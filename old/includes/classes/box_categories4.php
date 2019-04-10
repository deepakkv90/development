<?php
/*
  $Id: box_categories4.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_categories4 {
  public $categories_string = '';
  private $tree = array();
  private $id = array();
  
  public function __construct() {
    global $cPath, $languages_id;
    
    if (isset($_SESSION['customer_id'])) {
      $customer_group_array = tep_get_customers_access_group($_SESSION['customer_id']);
    } else {
      $customer_group_array[] = 'G';
    }
    $sql = "SELECT c.categories_id, cd.categories_name, c.parent_id
            FROM " . TABLE_CATEGORIES . " c,
                 " . TABLE_CATEGORIES_DESCRIPTION . " cd
            WHERE c.parent_id = 0
              and c.categories_id = cd.categories_id
              and cd.language_id = " . (int)$languages_id;
    $sql .= tep_get_access_sql('c.products_group_access', $customer_group_array);
    $sql .= "ORDER BY sort_order, cd.categories_name";
    $query = tep_db_query($sql);
    while ($categories = tep_db_fetch_array($query))  {
      $this->tree[$categories['categories_id']] = array('name' => $categories['categories_name'],
                                                        'parent' => $categories['parent_id'],
                                                        'level' => 0,
                                                        'path' => $categories['categories_id'],
                                                        'next_id' => false
                                                       );
      if (isset($prev_id)) {
        $this->tree[$prev_id]['next_id'] = $categories['categories_id'];
      }
      $prev_id = $categories['categories_id'];
      
      if ( ! isset($first_element)) {
        $first_element = $categories['categories_id'];
      }
    }
    if ($cPath) {
      $new_path = '';
      $this->id = split('_', $cPath);
      reset($this->id);
      while (list($key, $value) = each($this->id)) {
        unset($prev_id);
        unset($first_id);
        $query = tep_db_query("SELECT c.categories_id, cd.categories_name, c.parent_id
                               FROM " . TABLE_CATEGORIES . " c,
                                    " . TABLE_CATEGORIES_DESCRIPTION . " cd
                               WHERE c.parent_id = " . $value . "
                                 and c.categories_id = cd.categories_id
                                 and cd.language_id = " . (int)$languages_id ."
                               ORDER BY sort_order, cd.categories_name");
        $category_check = tep_db_num_rows($query);
        if ($category_check > 0) {
          $new_path .= $value;
          while ($row = tep_db_fetch_array($categories_query)) {
            $this->tree[$row['categories_id']] = array('name' => $row['categories_name'],
                                                       'parent' => $row['parent_id'],
                                                       'level' => $key+1,
                                                       'path' => $new_path . '_' . $row['categories_id'],
                                                       'next_id' => false
                                                      );
            if (isset($prev_id)) {
              $this->tree[$prev_id]['next_id'] = $row['categories_id'];
            }
            $prev_id = $row['categories_id'];
            
            if (!isset($first_id)) {
              $first_id = $row['categories_id'];
            }
            $last_id = $row['categories_id'];
          }
          $this->tree[$last_id]['next_id'] = $this->tree[$value]['next_id'];
          $this->tree[$value]['next_id'] = $first_id;
          $new_path .= '_';
        } else {
          break;
        }
      }
    }
    $this->tep_show_category4($first_element);
      
  }  //end of __construct
  
  
  private function tep_show_category4($counter) {
    $aa = 0;
    
    for ($a=0; $a<$this->tree[$counter]['level']; $a++) {
      if ($a == $this->tree[$counter]['level']-1) {
        $this->categories_string .= "<font color='#ff0000'>|__</font>";
      } else {
        $this->categories_string .= "<b><font color='#ff0000'>&nbsp;&nbsp;&nbsp;&nbsp;</font></b>";
      }
    }
    if ($this->tree[$counter]['level'] == 0) {
      if ($aa == 1) {
        $this->categories_string .= "<hr>";
      } else {
        $aa = 1;
      }
    }
    $this->categories_string .= '<a href="';
    // added for CDS CDpath support
    $CDpath = (isset($_SESSION['CDpath'])) ? '&CDpath=' . $_SESSION['CDpath'] : ''; 
    if ($this->tree[$counter]['parent'] == 0) {
      $cPath_new = 'cPath=' . $counter . $CDpath;
    } else {
      $cPath_new = 'cPath=' . $this->tree[$counter]['path'] . $CDpath;
    }
    $this->categories_string .= tep_href_link(FILENAME_DEFAULT, $cPath_new);
    $this->categories_string .= '">';
    if ($this->tree[$counter]['parent'] == 0) {
      $this->categories_string .= '<b>';
    } else if ( ($this->id) && (in_array($counter, $this->id)) ) {
      $this->categories_string .= "<b><font color='#ff0000'>";
    }
    // display category name
    $this->categories_string .= tep_db_output($this->tree[$counter]['name']);
    if ($this->tree[$counter]['parent'] == 0) {
      $this->categories_string .= '</b>';
    } else if ( ($this->id) && (in_array($counter, $this->id)) ) {
      $this->categories_string .= '</font></b>';
    }
    if (tep_has_category_subcategories($counter)) {
      $this->categories_string .= '<b>-&gt; </b>';
    }
    $this->categories_string .= '</a>';
    if (SHOW_COUNTS == 'true') {
      $products_in_category = tep_count_products_in_category($counter);
      if ($products_in_category > 0) {
        $this->categories_string .= '&nbsp;(' . $products_in_category . ')';
      }
    }
    //$this->categories_string .= '<br>';
    if ($this->tree[$counter]['next_id']) {
      $this->tep_show_category4($this->tree[$counter]['next_id']);
    }
    
  }

} //end of class
?>