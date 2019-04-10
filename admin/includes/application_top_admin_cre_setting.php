<?php
/*
  $Id: application_top_cre_setting.php ,v 1.

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
*/
// Used in the "Backup Manager" to compress backups
// this code needs changed as these location very buy server os
// the admin will try to detect these lcoation
  define('LOCAL_EXE_GZIP', '/usr/bin/gzip');
  define('LOCAL_EXE_GUNZIP', '/usr/bin/gunzip');
  define('LOCAL_EXE_ZIP', '/usr/local/bin/zip');
  define('LOCAL_EXE_UNZIP', '/usr/local/bin/unzip');

 define('ADMIN_GZIP_COMPRESSION', 'false');
 define('ADMIN_GZIP_LEVEL', '9');

// define the locations of the mysql utilities. This is if your server is on a Windows server only. Linux and
//Free bsd OS will search for the correct locations.
// if you path is C:/aserver/xampp/mysql/bin/
// then the defines would look like
// define('LOCAL_EXE_MYSQL',     'C:/aserver/xampp/mysql/bin/mysql.exe');  // used for restores
// define('LOCAL_EXE_MYSQLDUMP', 'C:/aserver/xampp/mysql/bin/mysqldump.exe');  // used for backups
// a few knowen location for mysql and mysql dump  c:/mysql/bin/, d:/mysql/bin/,C:/aserver/xampp/mysql/bin, d:/appserv/mysql/bin/, e:/appserv/mysql/bin/
// e:/mysql/bin/,  c:/apache2triad/mysql/bin/, d:/apache2triad/mysql/bin/, e:/apache2triad/mysql/bin/, C:/aserver/xampp/mysql/bin, d:/appserv/mysql/bin/, e:/appserv/mysql/bin/

//Note if server OS is a poxis compliant system it will try to detect the location, If the server os
//is labeld as WINNT, you must place the locations here.

define('LOCAL_EXE_MYSQL',     '');  // mysql.exe used for restores
define('LOCAL_EXE_MYSQLDUMP', '');  // mysqldump.exe used for backups

define('MYSQL_BACKUP_DEBUG', 'OFF');  // used for backups OFF or ON


define('ORDER_EDIT_EDT_PRICE', '1');  // 1 = allow editing of prices for Edit order, 0 = Do not allow price to be edited

/* CSS button related setting, if needed we can move them to configurations */
//define('CSS_BUTTON_MIN_WIDTH','80');// minimum button width,
define('CSS_SUBMIT_BUTTON_WIDTH','103');
define('CSS_IMAGE_BUTTON_WIDTH','85');
define('CSS_BUTTON_CHAR_WIDTH','6.5'); // font size!
define('CSS_BUTTON_NORMAL_STYLE','cssButton');//css for normal state of button
define('CSS_BUTTON_OVER_STYLE','cssButtonHover');//css for hover state of button
define('CSS_BUTTON_SUBMIT_NORMAL_STYLE','cssButtonSubmit');
define('CSS_BUTTON_SUBMIT_OVER_STYLE','cssButtonSubmitHover');
/* end CSS button settings */

//need this for download attribute, must be part of configure.php
define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
?>