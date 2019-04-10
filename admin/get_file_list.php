<?php

/*
  $Id: categories.php,v 1.3 2008/06/15 00:18:17 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
require(DIR_WS_CLASSES . 'file_select.php');
require(DIR_WS_LANGUAGES . $_SESSION['language'] . '/categories.php');

$_GET['dir'] = rawurldecode($_GET['dir']);

$ImageLocations['base_dir'] = DIR_FS_CATALOG_IMAGES;
$ImageLocations['base_url'] = DIR_WS_CATALOG_IMAGES; 

$manage_image = new DirSelect($ImageLocations);

$image_file = $manage_image->getFiles($_GET['dir']);
$file_list = '<b>' . TEXT_CATEGORIES_IMAGE_FILE . '</b><select name=' . $_GET['field'] . '"_select" class="dirWidth" id="imgFileDirPath" onchange="previewFile(this, \'' . $_GET['field'] . '\', \''. $_GET['field2'] . '\', \'' . $_GET['field3'] . '\');"><option value=""> -- None -- </option>';
foreach ($image_file[1] as $relative => $fullpath) {
  $file_list .= '<option value="' . rawurlencode($relative) . '">' . $relative . '</option>';
}
$file_list .= '</select>';
echo $file_list;
?>