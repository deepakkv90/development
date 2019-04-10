<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// $Id: backup_mysql.php,v 1.2.0.1 2004/08/03 00:00:00 DrByteZen Exp $
//


// the following are the language definitions
define('HEADING_TITLE', 'Database Backup Manager - MySQL');
define('WARNING_NOT_SECURE_FOR_DOWNLOADS','<span class="errorText">NOTE: You do not have SSL enabled. Any downloads you do from this page will not be encrypted. Doing backups and restores will be fine, but download/upload of files from/to the server presents a security risk.');
define('TABLE_HEADING_TITLE', 'Title');
define('TABLE_HEADING_FILE_DATE', 'Date');
define('TABLE_HEADING_FILE_SIZE', 'Size');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_INFO_HEADING_NEW_BACKUP', 'New Backup');
define('TEXT_INFO_HEADING_RESTORE_LOCAL', 'Restore Local');
define('TEXT_INFO_NEW_BACKUP', 'Do not interrupt the backup process which might take a couple of minutes.');
define('TEXT_INFO_UNPACK', '<br><br>(after unpacking the file from the archive)');
define('TEXT_INFO_RESTORE', 'Do not interrupt the restoration process.<br><br>The larger the backup, the longer this process takes!<br><br>If possible, use the mysql client.<br><br>For example:<br><br><b>mysql -h' . DB_SERVER . ' -u' . DB_SERVER_USERNAME . ' -p ' . DB_DATABASE . ' < %s </b> %s');
define('TEXT_INFO_RESTORE_LOCAL', 'Do not interrupt the restoration process.<br><br>The larger the backup, the longer this process takes!');
define('TEXT_INFO_RESTORE_LOCAL_RAW_FILE', 'The file uploaded must be a raw sql (text) file.');
define('TEXT_INFO_DATE', 'Date:');
define('TEXT_INFO_SIZE', 'Size:');
define('TEXT_INFO_COMPRESSION', 'Compression:');
define('TEXT_INFO_USE_GZIP', 'Use GZIP');
define('TEXT_INFO_USE_ZIP', 'Use ZIP');
define('TEXT_INFO_USE_NO_COMPRESSION', 'No Compression (Pure SQL)');
define('TEXT_INFO_DOWNLOAD_ONLY', 'Download without storing on server');
define('TEXT_INFO_BEST_THROUGH_HTTPS', '(Safer via a secured HTTPS connection)');
define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this backup?');
define('TEXT_NO_EXTENSION', 'None');
define('TEXT_BACKUP_DIRECTORY', 'Backup Directory:');
define('TEXT_LAST_RESTORATION', 'Last Restoration:');
define('TEXT_FORGET', '(forget)');

define('ERROR_BACKUP_DIRECTORY_DOES_NOT_EXIST', 'Error: Backup directory does not exist. Please set this in configure.php.');
define('ERROR_BACKUP_DIRECTORY_NOT_WRITEABLE', 'Error: Backup directory is not writeable.');
define('ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE', 'Error: Download link not acceptable.');
define('ERROR_CANT_BACKUP_IN_SAFE_MODE','ERROR: Cannot use backup script when safe_mode is enabled.');

define('SUCCESS_LAST_RESTORE_CLEARED', 'Success: The last restoration date has been cleared.');
define('SUCCESS_DATABASE_SAVED', 'Success: The database has been saved.');
define('SUCCESS_DATABASE_RESTORED', 'Success: The database has been restored.');
define('SUCCESS_BACKUP_DELETED', 'Success: The backup has been removed.');
define('FAILURE_DATABASE_NOT_SAVED', 'Failure: The database has NOT been saved.');
define('FAILURE_DATABASE_NOT_SAVED_UTIL_NOT_FOUND', 'ERROR: Could not locate the MYSQLDUMP backup utility. BACKUP FAILED.');
define('FAILURE_DATABASE_NOT_RESTORED', 'Failure: The database may NOT have been restored properly. Please check it carefully.');
define('FAILURE_DATABASE_NOT_RESTORED_FILE_NOT_FOUND', 'Failure: The database was NOT restored.  ERROR: FILE NOT FOUND: %s');
define('FAILURE_DATABASE_NOT_RESTORED_UTIL_NOT_FOUND', 'ERROR: Could not locate the MYSQL restore utility. RESTORE FAILED.');


define('BACKUP_MYSQl_ERROR_MSG_1', 'Checking for mysql and mysql_dump: ');
define('BACKUP_MYSQl_ERROR_MSG_2', 'COMMAND FILES FOUND/SELECTED:');
define('BACKUP_MYSQl_ERROR_MSG_3', 'COMMAND: ');
define('BACKUP_MYSQl_ERROR_MSG_4', "valueA: ");
define('BACKUP_MYSQl_ERROR_MSG_5', "valueB: ");
define('BACKUP_MYSQl_ERROR_MSG_6', 'Result code: ');
define('BYTES', ' bytes');
define('BACKUP_MYSQl_ERROR_MSG_7', 'mysql path :  ');
define('BACKUP_MYSQl_ERROR_MSG_8', 'mysqlbump path: ');
define('BACKUP_MYSQl_ERROR_MSG_9', 'Debug Mode is ON: ');

define('BACKUP_MYSQl_DEBUG_MSG_1', 'Gzip found on server: ');
define('BACKUP_MYSQl_DEBUG_MSG_2', 'Gunzip found on server: ');
define('BACKUP_MYSQl_DEBUG_MSG_3', 'Zip found on server: ');
define('BACKUP_MYSQl_DEBUG_MSG_4', 'Unzip found on server: ');
define('BACKUP_MYSQl_DEBUG_MSG_5', 'Gzip was not found on the server: ');
define('BACKUP_MYSQl_DEBUG_MSG_6', 'Zip was not found on the server: ');

define('BACKUP_MYSQl_DEBUG_MSG_12', 'The Server operating system could not be detected: ');
define('BACKUP_MYSQl_DEBUG_MSG_7', 'The Server operating system appears to be FreeBSD or Linux: ');
define('BACKUP_MYSQl_DEBUG_MSG_8', 'The Server operating system Win NT based: ');

define('BACKUP_MYSQl_DEBUG_MSG_9', 'Using Zlib to sompress file: ');

define('BACKUP_MYSQl_DEBUG_MSG_10', 'Using PHP Gzip functions to compress file: ');
define('BACKUP_MYSQl_DEBUG_MSG_11', 'Using PHP Zip to compress file: ');

?>
