<?php
/*
  $Id: categories.php,v 1.1.1.1 2003/02/25 02:59:49 root Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

function show_category_tabs($counter) 
{
  global $foo, $categories_string, $id;
  if ($foo[$counter]['parent'] == 0) {
    $cPath_new = 'cPath=' . $counter;
  }
  if ($_GET['cPath'] != 0){
    $base = substr($_GET['cPath'], 0, strpos($_GET['cPath'], '_'));
    if ($counter == $_GET['cPath']) {
      $onpage = 1;
    } elseif ($counter == $base) {
      $onpage = 1;
    }
  }

  if ($onpage) {
    $categories_string .= '<li class="here">';
  } else {
    $categories_string .= '<li>';
  }   

  $categories_string .= '<a';
  if ($onpage) {
    $categories_string .= ' class="here" href="';
  } else {
    $categories_string .= ' href="';
  }
  $categories_string .= tep_href_link(FILENAME_DEFAULT, $cPath_new);
    $categories_string .= '">';
  // display category name
  $categories_string .= $foo[$counter]['name'];
  
  $categories_string .= '</a></li>';

  
  if ($foo[$counter]['next_id']) {
    $onpage = 0;
    show_category_tabs($foo[$counter]['next_id']);
  }
}
?>


<!-- categories //-->

<?php
  // Always add the home link first
  $categories_string = '';
  if (!$cPath) {
    $onpage = 1;
  }

  if ($onpage) {
    $categories_string .= '<li class="here">';
  } else {
    $categories_string .= '<li>';
  } 
  $categories_string .= '<a';
  if ($onpage) {
    $categories_string .= ' class="here" href="';
  } else {
    $categories_string .= ' href="';
  }   

  $categories_string .= tep_href_link(FILENAME_DEFAULT, $cPath_new);
    $categories_string .= ' ">';
  // display category name
  $categories_string .= "Home";
  
  $categories_string .= '</a></li>';
  
  if ($foo[$counter]['next_id']) {
    $onpage = 0;
    show_category_tabs($foo[$counter]['next_id']);
  }
  
  // start the tabs
  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");
  while ($categories = tep_db_fetch_array($categories_query))  {
    $foo[$categories['categories_id']] = array(
      'name' => $categories['categories_name'],
      'parent' => $categories['parent_id'],
      'level' => 0,
      'path' => $categories['categories_id'],
      'next_id' => false
    );
  
    if (isset($prev_id)) {
      $foo[$prev_id]['next_id'] = $categories['categories_id'];
    }
  
    $prev_id = $categories['categories_id'];
  
    if (!isset($first_element)) {
      $first_element = $categories['categories_id'];
    }
  }
  
  show_category_tabs($first_element); 
  echo $categories_string;

?>

          
<!-- categories_eof //-->



