<?php
/*
  $Id: CDS_listmenu_applicationtop_bottom.php,v 1.0.0.0 2009/02/09 10:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded

  Released under the GNU General Public License
*/

    require_once(DIR_WS_FUNCTIONS . FILENAME_CDS_FUNCTIONS);

    function build_cds_list_menu($startid = 0, $ulstyle = ''){
        global $languages_id;
        if($ulstyle != ''){
            $ulstyle = ' id="' . $ulstyle . '"';
        }
        
        $cds_menu = '';
        //loop through category id to find all subcategories and pages
        $cds_pages_query = tep_db_query("SELECT c.categories_id as 'ID', 
                                                  cd.categories_name as 'Name',
                                                  c.categories_parent_id as 'ParentID',
                                                  c.category_append_cdpath as 'Append',
                                                  c.categories_url_override as 'URL',
                                                  c.categories_url_override_target as 'Target',
                                                  c.pages_group_access as 'Group', 'c' as 'type',
                                                  c.categories_sort_order as 'Sort'
                                          FROM pages_categories c 
                                          LEFT JOIN pages_categories_description cd 
                                          ON c.categories_id = cd.categories_id 
                                          WHERE c.categories_parent_id = '" . $startid . "' 
                                          AND c.categories_status = '1'
                                          AND c.categories_in_menu = '1'
                                          AND cd.language_id = '" . (int)$languages_id . "'
                                          UNION
                                          SELECT p.pages_id as 'ID', 
                                                  pd.pages_menu_name as 'Name',
                                                  p2c.categories_id as 'ParentID',
                                                  p.pages_append_cdpath as 'Append',
                                                  p.pages_url as 'URL',
                                                  p.pages_url_target as 'Target',
                                                  p.pages_group_access as 'Group', 'p' as 'type',
                                                  p.pages_sort_order as 'Sort'
                                            FROM pages p, 
                                                  pages_description  pd, 
                                                  pages_to_categories p2c 
                                            WHERE p.pages_id = pd.pages_id 
                                                AND pd.language_id ='" . (int)$languages_id . "'
                                                AND p.pages_id = p2c.pages_id 
                                                AND p.pages_status = '1'
                                                AND pd.pages_menu_name <> ''
                                                AND p.pages_in_menu = '1'
                                                AND p2c.categories_id ='" . $startid . "'
                                            ORDER BY Sort ");
        $rows_count = tep_db_num_rows($cds_pages_query);
        if ($rows_count > 0) {    
        $cds_menu .=  "\n" . '     <ul' . $ulstyle . '>' . "\n";
        $ulstyle = '';
            while($cds_pages_data = tep_db_fetch_array($cds_pages_query)){
                $cds_menu .= '         <li>' .  cds_build_menu_url($cds_pages_data['ID'], $cds_pages_data['Name'], $cds_pages_data['type'], $cds_pages_data['URL'], $cds_pages_data['Append'], $cds_pages_data['Target']);
                if($cds_pages_data['type'] == 'c' && $cds_pages_data['URL'] == '' ){
                    //we found a subcategory, loop, loop, loop.......
                    $cds_menu .= build_cds_list_menu($cds_pages_data['ID']);
                }
                $cds_menu .= '</li>' . "\n";
            }
        $cds_menu .= '     </ul>' . "\n";
        }
        
        return $cds_menu;
    }  //end build_cds_list_menu function


    //build link url
    function cds_build_menu_url($id, $name, $type, $url, $append = 0, $target = ''){
        if($type == 'c'){
            if($url == ''){
                $cds_url= tep_href_link(FILENAME_PAGES,'CDpath=' . cre_get_cds_category_path($id),'NONSSL');
            } else {
                $cds_url=  $url . (($append == 1) ? ((strpos($url, '?')) ? '&amp;' : '?') . 'CDpath=' . cre_get_cds_page_path($id) : '');
            }
        } else if ($type == 'p') {
            $cds_url= tep_href_link(FILENAME_PAGES,'pID=' . $id . '&amp;CDpath=' . cre_get_cds_page_path($id),'NONSSL');
        }
                
        return '<a href="' . $cds_url . '"' . (($target != '') ? ' target="' . $target . '"' : '') . '>' . $name . '</a>';
    }

/*
*  Usegae: Use the below code where you would like to show the list
*  
*  echo build_cds_list_menu('CDS Category ID', 'ULStyle');  
* 
*  Orguments:
*  CDS Category ID = If blank, the menu generation starts from Top Level = 0
*  ULStyle = If used the first <ul> is updated with style id, ie <ul id="ULStyle">
* 
*/           
?>