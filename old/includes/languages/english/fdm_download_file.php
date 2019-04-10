<?php
/*
  $Id: fdm_download_file.php,v 1.1.1.1 2006/10/13 Jagdish Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('TEXT_LOGIN_REQUIRED', '<h2>To download this file required login, please click <a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">here</a> to login</h2>');
define('TEXT_PURCHASE_REQUIRED', '<h2>To download this file required purchase, please purchase first</h2>');
define('TEXT_FILE_UNAVAILABLE','<h2>File Unavailable</h2>'); 
define('ERROR_NO_FILE_ID', 'ERROR: URL does not contain a file ID.  Please contact webmaster.');
define('ERROR_FILE_INFORMATION_DOES_NOT_EXIST', 'ERROR: File Information is missing from database.  Please contact webmaster.');
define('ERROR_FILE_DOES_NOT_EXIST', 'ERROR: The file does not exist.  Please contact webmaster.');

?>