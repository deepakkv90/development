<?php
/*
* stats_low_stock_class
*
* version 0.1
*
* author: hakre
*
* contains helper functions for the stats_low_stock.php file
*/

class stats_low_stock_class {

  function stats_low_stock_class() {
  }

  /*
  * httpGetVars
  *
  * checks the existance of a query http get passed-by variable and returns it  
  * or returns the default value ($default) in case of non-existance
  *
  * optionally $value is checked against $validsarray values and set to $default
  * if not matching
  *
  * type: helper
  */
  function httpGetVars($name, $default='', $validsarray = false) {
    //edit: use tep function for this instead
    // get "Get" variable
    if (isset($_GET[$name]))
      $value = $_GET[$name];
    else
      $value = $default;

    // check against valid values
    if (is_array($validsarray))
      if (!in_array($value, $validsarray, true))
        $value = $default;

    return $value;
  }

  /*
  * htmlCaptionSortLink
  *
  * retruns html code of a linked caption (listing header cells)
  */
  //edit: extend description of the function
  function htmlCaptionSortLink($orderbyname, $filename, $caption) {
    $orderby = $GLOBALS['orderby'];
    $sorted = $GLOBALS['sorted'];
  
    $to_sort = ($orderby == $orderbyname && $sorted == 'ASC') ? 'DESC' : 'ASC';

    $link = tep_href_link( $filename , 'orderby=' . $orderbyname . '&sorted=' . $to_sort);
  
    $t = sprintf( '<a href="%s" class="headerLink">%s</a>', $link, $caption);

    return $t;
  }

}
?>