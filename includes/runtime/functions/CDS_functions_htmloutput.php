<?php
/*
  $Id: functions_htmloutput.php,v 1.0.0.0 2008/01/22 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
global $link, $PHP_SELF;
                                 
// if CDpath session is set, carry CDpath to next URL
if (!eregi('CDpath=', $link)) {
  if (isset($_SESSION['CDpath']) && $_SESSION['CDpath'] != NULL) {
    $pages = array('articles.php', 
                   'articles.tpl.php',
                   'articles_new.php',
                   'articles_new.tpl.php',
                   'article_info.php',
                   'article_info.tpl.php',
                   'categories.php',
                   'categories1.php',
                   'categories2.php', 
                   'categories3.php', 
                   'categories4.php', 
                   'categories5.php', 
                   'product_info.php',
                   'product_info.tpl.php',
                   'index_nested.tpl.php',
                   'index_products.tpl.php');
    if (in_array(basename($PHP_SELF), $pages)) {
      $this_separator = (strpos($link, '?')) ? '&' : '?';
      $link .= $this_separator . 'CDpath=' . $_SESSION['CDpath'];
    }
  }
}
?>