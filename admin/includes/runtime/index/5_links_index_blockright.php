<?php
/*
  $Id: 5_links_index_blockright.php,v 1.0.0.0 2007/07/25 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if(defined('ADMIN_BLOCKS_LINKS_STATUS') && ADMIN_BLOCKS_LINKS_STATUS == 'true'){
  //LINK_CATEGORIE Count Code
  $link_categories_query = tep_db_query("select count(link_categories_id) as link_categoriescnt from " . TABLE_LINK_CATEGORIES);
  $link_categoriescount = tep_db_fetch_array($link_categories_query);
  define('LINK_CATEGORIES_COUNT',$link_categoriescount['link_categoriescnt']);
  //LINKS Count Code
  $link_query = tep_db_query("select count(links_id) as linkcnt from " . TABLE_LINKS);
  $linkcount = tep_db_fetch_array($link_query);
  define('LINKS_COUNT',$linkcount['linkcnt']);
  //LINKS Count Code
  $linkapproved_query = tep_db_query("select count(links_id) as linkapprovedcnt from " . TABLE_LINKS." where links_status=1");
  $linkapprovedcount = tep_db_fetch_array($linkapproved_query);
  define('LINKS_APPROVAL_COUNT',$linkapprovedcount['linkapprovedcnt']);
  ?>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="Links Information">
    <tr valign="top">
      <td width="100%" style="padding-right: 12px;"><div class="form-head"><?php cre_index_block_title(BLOCK_TITLE_LINKS, tep_href_link(FILENAME_LINKS,'selected_box=links','NONSSL'), BLOCK_LINKS_HELP);?></div>
      <div class="form-body">
        <ul class="ul_index">
          <li><?php echo BLOCK_CONTENT_LINKS_TOTAL.' : '.LINKS_COUNT;?></li>  
          <li><?php echo BLOCK_CONTENT_LINKS_CATEGORIES.' : '.LINK_CATEGORIES_COUNT;?></li>
          <li><?php echo BLOCK_CONTENT_LINKS_WAITING.' : '.LINKS_APPROVAL_COUNT;?></li>
        </ul>
      </div></td>
    </tr>
  </table>
  <?php
}
?>