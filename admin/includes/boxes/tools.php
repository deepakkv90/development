<?php
/*
  $Id: tools.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- tools //-->
          <tr>
            <td>
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_TOOLS,
                   'link'  => tep_href_link(FILENAME_BACKUP_MYSQL, 'selected_box=tools'));
if ($_SESSION['selected_box'] == 'tools' || MENU_DHTML == 'True') {
  //RCI start
  $returned_rci_top = $cre_RCI->get('tools', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('tools', 'boxesbottom');
  $contents[] = array('text'  => $returned_rci_top .
                                 tep_admin_files_boxes(FILENAME_BACKUP_MYSQL, BOX_TOOLS_MYSQL_BACKUP, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_BACKUP,BOX_TOOLS_BACKUP, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_IMAGE_MANAGER, BOX_TOOLS_IMAGE_MANAGER, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_BLACKLIST, BOX_TOOLS_BLACKLIST, 'SSL','','2') .  
                                 tep_admin_files_boxes(FILENAME_CACHE, BOX_TOOLS_CACHE, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_EDIT_LANGUAGES, BOX_TOOLS_DEFINE_LANGUAGE, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_EMAIL_SUBJECTS, BOX_TOOLS_EMAIL_SUBJECTS, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_MAIL, BOX_TOOLS_MAIL, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_SERVER_INFO, BOX_TOOLS_SERVER_INFO, 'SSL','','2') . 
                                 tep_admin_files_boxes(FILENAME_WHOS_ONLINE, BOX_TOOLS_WHOS_ONLINE, 'SSL','','2') .
                                 $returned_rci_bottom);    
  }
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tools_eof //-->