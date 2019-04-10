<?php
/*
  $Id: categories4.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/ 
if ((defined('USE_CACHE') && USE_CACHE == 'true') && !defined('SID')) {
  echo tep_cache_categories_box4();
} else {
  $categories4 = new box_categories4();  
  ?>
  <!-- categories4 //-->
  <tr>
    <td>
      <?php
      $info_box_contents = array();
      $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_CATEGORIES4 . '</font>');
      new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') );
      $categories_string4 = $categories4->categories_string;
      // added for CDS CDpath support
      $params = (isset($_SESSION['CDpath'])) ? 'CDpath=' . $_SESSION['CDpath'] : ''; 
      //coment out the below lines if you do not want to have an all products list
      $categories_string4 .= "<hr>\n";
      $categories_string4 .= '<a href="' . tep_href_link(FILENAME_ALL_PRODS, $params) . '"><b>' . BOX_INFORMATION_ALLPRODS . "</b></a>\n";
      $categories_string4 .= "-&gt;<br><hr>\n";
      $categories_string4 .= '<a href="' . tep_href_link(FILENAME_ALL_PRODCATS, $params) . '"><b>' . ALL_PRODUCTS_LINK . "</b></a>\n";
      $categories_string4 .= "-&gt;<br><hr>\n";
      $categories_string4 .= '<a href="' . tep_href_link(FILENAME_ALL_PRODMANF, $params) . '"><b>' . ALL_PRODUCTS_MANF . "</b></a>\n";
      $categories_string4 .= "-&gt;<br>\n";
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left',
                                   'text'  => $categories_string4
                                  );
      new $infobox_template($info_box_contents, true, true, ((isset($column_location) && $column_location !='') ? $column_location : '') );
      if (TEMPLATE_INCLUDE_FOOTER =='true'){
        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left',
                                     'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                    );
        new $infobox_template_footer($info_box_contents, ((isset($column_location) && $column_location !='') ? $column_location : '') );
      }
      ?>
     </td>
   </tr>
  <!-- categories4_eof //-->
  <?php
}
?>