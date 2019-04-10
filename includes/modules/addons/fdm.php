<?php
/*
  $Id: fdm.php,v 1.0.0 2008/10/26 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class fdm {
    var $title, $description, $enabled;

    function fdm() {
      $this->code = 'fdm';
      $this->title = (defined('MODULE_ADDONS_FDM_TITLE')) ? MODULE_ADDONS_FDM_TITLE : '';
      $this->description = (defined('MODULE_ADDONS_FDM_DESCRIPTION')) ? MODULE_ADDONS_FDM_DESCRIPTION : '';
      $this->enabled = (defined('MODULE_ADDONS_FDM_STATUS') && MODULE_ADDONS_FDM_STATUS == 'True') ? true : false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ADDONS_FDM_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      $languages = $this->__get_languages();
      
      // insert module config values
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('Enable CRE File Distribution Management System', 'MODULE_ADDONS_FDM_STATUS', 'True', 'Select True to enable CRE File Distribution Management System.', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      
      // create tables
      tep_db_query("CREATE TABLE IF NOT EXISTS fdm_file_icons (
                    icon_id int(11) NOT NULL auto_increment,
                    file_ext varchar(32) NOT NULL default '',
                    icon_small varchar(255) NOT NULL default '',
                    icon_large varchar(255) NOT NULL default '',
                    PRIMARY KEY  (icon_id)
                    ) TYPE=MyISAM AUTO_INCREMENT=1 ;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fdm_library_files (
                    files_id int(11) NOT NULL auto_increment,
                    files_name varchar(255) NOT NULL default '',
                    files_md5 varchar(64) NOT NULL default '',
                    files_date_added datetime default NULL,
                    files_last_modified datetime default NULL,
                    files_status tinyint(1) NOT NULL default 0,
                    files_general_display tinyint(1) NOT NULL default 1,
                    files_product_display tinyint(1) NOT NULL default 1,
                    files_download int(11) NOT NULL default 0,
                    file_date_created datetime NOT NULL default '0000-00-00 00:00:00',
                    file_availability int(1) NOT NULL default 0,
                    files_icon varchar(255) NOT NULL default '',
                    require_products_id int(11) NOT NULL default 0,
                    PRIMARY KEY  (files_id)
                    ) TYPE=MyISAM AUTO_INCREMENT=1 ;");
                    
  
      $sql_query = tep_db_query("SELECT files_id, files_name FROM fdm_library_files"); 
      while ($file = tep_db_fetch_array($sql_query)) {
        $file_name = DIR_FS_CATALOG . LIBRARY_DIRECTORY . $file['files_name'];
        if (file_exists($file_name) && is_file($file_name)) {
          $md5 = md5( $file_name . filesize($file_name));
          tep_db_query("UPDATE fdm_library_files SET files_md5 = '" . $md5 . "' WHERE files_id = '" . $file['files_id'] . "'");
        }
      }                    

      tep_db_query("CREATE TABLE IF NOT EXISTS fdm_library_files_description (
                    files_id int(11) NOT NULL default 0,
                    language_id int(11) NOT NULL default 1,
                    files_descriptive_name varchar(64) NOT NULL default '',
                    files_description text,
                    files_head_title_tag varchar(80) default NULL,
                    files_head_desc_tag longtext NOT NULL,
                    files_head_keywords_tag longtext NOT NULL,
                    PRIMARY KEY  (files_id,language_id)
                    ) TYPE=MyISAM;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fdm_library_files_download (
                    files_id int(11) NOT NULL default 0,
                    customers_id int(11) NOT NULL default 0,
                    download_time datetime NOT NULL default '0000-00-00 00:00:00',
                    page varchar(255) NOT NULL default '',
                    ip_addr varchar(32) NOT NULL default '',
                    file_size int(18) NOT NULL default 0,
                    PRIMARY KEY  (files_id,customers_id,download_time),
                    KEY `download_time` (`download_time`),
                    KEY `customers_id` (`customers_id`),
                    KEY `ip_addr` (`ip_addr`)
                    ) TYPE=MyISAM;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fdm_library_files_to_folders (
                    files_id int(11) NOT NULL default 0,
                    folders_id int(11) NOT NULL default 0,
                    PRIMARY KEY  (files_id,folders_id)
                    ) TYPE=MyISAM;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fdm_library_folders (
                    folders_id int(11) NOT NULL auto_increment,
                    folders_parent_id int(11) NOT NULL default 0,
                    folders_image varchar(64) default NULL,
                    folders_sort_order int(3) default NULL,
                    folders_date_added datetime default NULL,
                    folders_last_modified datetime default NULL,
                    PRIMARY KEY  (folders_id)
                    ) TYPE=MyISAM AUTO_INCREMENT=1 ;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fdm_library_folders_description (
                    folders_id int(11) NOT NULL default 0,
                    language_id int(11) NOT NULL default 1,
                    folders_name varchar(32) NOT NULL default '',
                    folders_heading_title varchar(64) default NULL,
                    folders_description text,
                    folders_head_title_tag varchar(80) default NULL,
                    folders_head_desc_tag longtext NOT NULL,
                    folders_head_keywords_tag longtext NOT NULL,
                    PRIMARY KEY  (folders_id,language_id)
                    ) TYPE=MyISAM;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fdm_library_products (
                    products_id int(11) NOT NULL default 0,
                    library_type char(1) NOT NULL default '',
                    library_id int(11) NOT NULL default 0,
                    login_required tinyint(1) NOT NULL default 0,
                    purchase_required tinyint(1) NOT NULL default 0,
                    download_show tinyint(1) NOT NULL default 0,
                    PRIMARY KEY  (products_id,library_type,library_id)
                    ) TYPE=MyISAM;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fdm_featured_files (
                    featured_id int( 11 ) NOT NULL AUTO_INCREMENT ,
                    files_id int( 11 ) NOT NULL default 0,
                    featured_date_added datetime default NULL ,
                    featured_last_modified datetime default NULL ,
                    expires_date datetime default NULL ,
                    date_status_change datetime default NULL ,
                    status int( 1 ) default 1,
                    PRIMARY KEY (featured_id)
                    ) TYPE=MyISAM;");
                    
      // update configuration
      tep_db_query('INSERT IGNORE INTO configuration_group VALUES (450, "FDMS Configuration", "FDMS Configuration Options", 450, 1)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "Download Folder Location", "LIBRARY_DIRECTORY", "library/", "Set the path for download the folder.", 450, 1, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "Download Order Statuses", "LIBRARY_DOWNLOAD_ORDER_STATUS_CONTROL", "3, 4", "The order statuses to enable FDMS file download.", 450, 2, now(), now(), NULL, "tep_get_orders_status_selection(")');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "File Listing View", "LIBRARY_FILE_FOLDERS_LISTING", "table", "Display the File Listing in table view or detail view?", 450, 3, now(), now(), NULL, "tep_cfg_select_option(array(""table"", ""detail""),")');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "Table View: Display Action Column", "FILE_LIST_ACTION", "Yes", "Do you want to display the Action column?<br>(table view only)", 450, 4, now(), now(), NULL, "tep_cfg_select_option(array(""Yes"", ""No""),")');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "Table View: Display Filename Column", "FILE_LIST_FILE_NAME", "Yes", "Do you want to display the filename column?<br>(table view only)", 450, 5, now(), now(), NULL, "tep_cfg_select_option(array(""Yes"", ""No""),")');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "Table View: Display File Title Column", "FILE_LIST_MORE_INFO", "Yes", "Do you want to display the File Title column?<br>(table view only)", 450, 6, now(), now(), NULL, "tep_cfg_select_option(array(""Yes"", ""No""),")');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "Table View: Display Date Column", "FILE_LIST_DATE", "Yes", "Do you want to display the date column?<br>(table view only)", 450, 7, now(), now(), NULL, "tep_cfg_select_option(array(""Yes"", ""No""),")');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "Table View: Display File Size Column", "FILE_LIST_SIZE", "No", "Do you want to display the file size column?<br>(table view only)", 450, 8, now(), now(), NULL, " tep_cfg_select_option(array(""Yes"", ""No""),")');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "Table View: Display Number of Downloads Column", "FILE_LIST_DOWNLOADS", "Yes", "Do you want to display the number of downloads column?<br>(table view only)", 450, 9, now(), now(), NULL, "tep_cfg_select_option(array(""Yes"", ""No""),")');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "Detail View: Max Display Results per Page", "MAX_DISPLAY_FDM_SEARCH_RESULTS", "10", "Number of files to list on a page.<br>(detail view only)", 450, 10, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "Detail View: Location of Prev/Next Navigation Bar", "FDM_PREV_NEXT_BAR_LOCATION", "3", "Sets the location of the Prev/Next Navigation Bar (1-top, 2-bottom, 3-both).<br>(detail view only)", 450, 11, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "Small File Icon Image Width", "FDM_SMALL_ICON_IMAGE_WIDTH", "16", "The pixel width of small FDMS file icons<br>(Use empty for non-specific size)", 450, 12, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "Small File Icon Image Height", "FDM_SMALL_ICON_IMAGE_HEIGHT", "", "The pixel height of small FDMS file icons<br>(Use empty for non-specific size)", 450, 13, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "Large File Icon Image Width", "FDM_LARGE_ICON_IMAGE_WIDTH", "32", "The pixel width of large FDMS file icons<br>(Use empty for non-specific size)", 450, 14, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "Large File Icon Image Height", "FDM_LARGE_ICON_IMAGE_HEIGHT", "", "The pixel height of large FDMS file icons<br>(Use empty for non-specific size)", 450, 15, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "Required Products Listing", "FDM_REQ_PRODUCT_CONTENT_LISTING", "row", "Required product list should be in Columns (with one product per row)  or Rows (Multiple products per row)", 450, 16, now(), now(), NULL, "tep_cfg_select_option(array(""column"", ""row""),")');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "Multi Required Products Operator", "FDM_REQ_PRODUCT_OPERATORS", "or", "<br>OR = Release the file with ANY qualifying purchase.<br><br>AND = Release the file only if ALL required products have been purchased.", 450, 17, now(), now(), NULL, " tep_cfg_select_option(array(""and"", ""or""),")');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "FDMS Reports Max Display Results", "FDMS_MAX_DISPLAY_SEARCH_RESULTS", "25", "Set the maximum lines to display in FDMS Reports.", 450, 18, now(), now(), NULL, NULL)');

      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "TABLE_LIBRARY_FILES", "fdm_library_files", "", 451, 1, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "TABLE_LIBRARY_FILES_DOWNLOAD", "fdm_library_files_download", "", 451, 2, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "TABLE_LIBRARY_FILES_DESCRIPTION", "fdm_library_files_description", "", 451, 3, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "TABLE_LIBRARY_FOLDERS", "fdm_library_folders", "", 451, 4, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "TABLE_LIBRARY_FOLDERS_DESCRIPTION", "fdm_library_folders_description", "", 451, 5, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "TABLE_LIBRARY_FILES_TO_FOLDERS", "fdm_library_files_to_folders", "", 451, 6, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "TABLE_LIBRARY_PRODUCTS", "fdm_library_products", "", 451, 7, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "TABLE_FILE_ICONS", "fdm_file_icons", "", 451, 9, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "TABLE_FEATURED_FILES", "fdm_featured_files", "", 451, 9, now(), now(), NULL, NULL)');

      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_FDM_FUNCTIONS", "fdm_functions.php", "", 451, 20, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_LIBRARY_FILES", "fdm_library_files.php", "", 451, 21, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_FDM_CONFIG", "fdm_configuration.php", "", 451, 22, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_LIBRARY_FILES_EDIT", "fdm_library_files_edit.php", "", 451, 23, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_LIBRARY_FOLDERS_EDIT", "fdm_library_folders_edit.php", "", 451, 24, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_LIBRARY_SCAN", "fdm_library_scan.php", "", 451, 25, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_LIBRARY_PRODUCT", "fdm_library_product.php", "", 451, 26, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_PRODUCT_THUMB", "fdm_product_thumb.php", "", 451, 27, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_FILE_ICONS", "fdm_file_icons.php", "", 451, 28, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_CUSTOMER_DOWNLOADS", "fdm_customer_downloads.php", "", 451, 29, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_FILE_DETAIL", "fdm_file_detail.php", "", 451, 30, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_LIBRARY_BACKUP", "fdm_library_backup.php", "", 451, 31, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_LIBRARY_FILES_PRODUCTS", "fdm_library_files_products.php", "", 451, 32, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_DOWNLOAD_FILE", "fdm_download_file.php", "", 451, 32, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "CONTENT_FOLDER_FILES", "fdm_folder_files", "", 451, 33, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_FOLDER_FILES", "fdm_folder_files.php", "", 451, 34, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_FOLDER_FILES_LISTING", "fdm_folder_files_listing.php", "", 451, 35, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "CONTENT_FILE_DETAIL", "fdm_file_detail", "", 451, 36, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_PRODUCT_FILES_LISTING", "fdm_product_files_listing.php", "", 451, 37, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "CONTENT_DOWNLOADS_INDEX", "fdm_downloads_index", "", 451, 38, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_DOWNLOADS_INDEX", "fdm_downloads_index.php", "", 451, 39, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_DOWNLOADS_FILES_LISTING", "fdm_downloads_files_listing.php", "", 451, 40, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_FOLDER_FILES_LISTING_TABLE", "fdm_folder_files_listing_table.php", "", 451, 41, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_FILE_DETAIL_LISTING", "fdm_file_detail_listing.php", "", 451, 42, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_FILE_DETAIL_LISTING_COL", "fdm_file_detail_listing_col.php", "", 451, 43, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_CUSTOMER_DOWNLOADS_LOG", "fdm_customer_download_log.php", "", 451, 44, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_ADMIN_DOWNLOAD_FILE", "fdm_admin_download_file.php", "", 451, 45, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "CONTENT_FEATURED_FILES", "fdm_featured_files", "", 451, 45, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_CUSTOMER_TOP_DOWNLOADS", "fdm_customer_top_downloads.php", "", 451, 47, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_TOP_DOWNLOADS", "fdm_top_downloads.php", "", 451, 48, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_ATTACHED_FILES", "fdm_attached_files.php", "", 451, 49, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_ATTACHED_PROD_FILES", "fdm_attached_prod_files.php", "", 451, 50, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_PRODUCTS_NO_FILES", "fdm_products_no_files.php", "", 451, 51, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_DOWNLOAD_LOG", "fdm_download_log.php", "", 451, 52, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_DAILY_DOWNLOADS", "fdm_daily_downloads.php", "", 451, 53, now(), now(), NULL, NULL)');
      tep_db_query('INSERT IGNORE INTO configuration VALUES ("", "", "FILENAME_FEATURED_FILES", "fdm_featured_files.php", "", 451, 54, now(), now(), NULL, NULL)');

      tep_db_query('INSERT IGNORE INTO fdm_file_icons VALUES (1, "html", "icon-html.gif", "thumb-html.gif")');
      tep_db_query('INSERT IGNORE INTO fdm_file_icons VALUES (2, "doc", "icon-word.gif", "thumb-word.gif")');
      tep_db_query('INSERT IGNORE INTO fdm_file_icons VALUES (3, "zip", "icon-zip.gif", "thumb-zip.gif")');
      tep_db_query('INSERT IGNORE INTO fdm_file_icons VALUES (4, "xls", "icon-excel.gif", "thumb-excel.gif")');
      tep_db_query('INSERT IGNORE INTO fdm_file_icons VALUES (5, "pdf", "icon-pdf.gif", "thumb-pdf.gif")');
      tep_db_query('INSERT IGNORE INTO fdm_file_icons VALUES (6, "jpg", "icon-image.gif", "thumb-image.gif")');
      tep_db_query('INSERT IGNORE INTO fdm_file_icons VALUES (7, "gif", "icon-image.gif", "thumb-image.gif")');
      tep_db_query('INSERT IGNORE INTO fdm_file_icons VALUES (8, "mpeg", "icon-mpeg.gif", "thumb-mpeg.gif")');
      tep_db_query('INSERT IGNORE INTO fdm_file_icons VALUES (9, "mp3", "icon-mp3.gif", "thumb-mp3.gif")');
      tep_db_query('INSERT IGNORE INTO fdm_file_icons VALUES (10, "mov", "icon-mov.gif", "thumb-mov.gif")');
      tep_db_query('INSERT IGNORE INTO fdm_file_icons VALUES (11, "avi", "icon-avi.gif", "thumb-avi.gif")');
      tep_db_query('INSERT IGNORE INTO fdm_file_icons VALUES (12, "wma", "icon-wma.gif", "thumb-wma.gif")');
      tep_db_query('INSERT IGNORE INTO fdm_file_icons VALUES (14, ".mov2", "icon-quicktime.gif", "thumb-quicktime.gif")');
      tep_db_query('INSERT IGNORE INTO fdm_file_icons VALUES (15, ".cda", "icon-cdrom.gif", "thumb-cdrom.gif")');
      tep_db_query('INSERT IGNORE INTO fdm_file_icons VALUES (16, ".wma", "icon-winmedia.gif", "thumb-winmedia.gif")');
      tep_db_query('INSERT IGNORE INTO fdm_file_icons VALUES (17, ".play", "icon-play.gif", "thumb-play.gif")');
      tep_db_query('INSERT IGNORE INTO fdm_file_icons VALUES (18, ".ppt", "icon-ppt.gif", "thumb-ppt.gif")');
    }        
    
    function keys() {
      return array('MODULE_ADDONS_FDM_STATUS');
    }            
      
    function remove() {
      tep_db_query("DELETE FROM configuration WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");  
      tep_db_query("DELETE FROM configuration_group WHERE configuration_group_id = 450");
      tep_db_query("DELETE FROM configuration WHERE configuration_group_id = 450");  
      tep_db_query("DELETE FROM configuration WHERE configuration_group_id = 451");  
      tep_db_query("DELETE FROM `infobox_heading` WHERE `box_heading` = 'File Library'"); 
      tep_db_query("DELETE FROM `infobox_configuration` WHERE `infobox_define` = 'BOX_HEADING_FILE_LIBRARY'");       
    }
    
    function __get_table_rows($table) {
      $number = tep_db_fetch_array(tep_db_query("SELECT count(*) AS nbr FROM " . $table));
      return $number['nbr'];
    }
    
    function __get_languages() {
      $languages_query = tep_db_query("SELECT languages_id, name, code, image, directory FROM " . TABLE_LANGUAGES . " ORDER BY sort_order");
      while ($languages = tep_db_fetch_array($languages_query)) {
        $languages_array[] = array('id' => $languages['languages_id'],
                                   'name' => $languages['name'],
                                   'code' => $languages['code'],
                                   'image' => $languages['image'],
                                   'directory' => $languages['directory']
                                  );
      }
      return $languages_array;
    }
  }  
?>