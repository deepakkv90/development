<?php
/*
  $Id: define_mainpage.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$_GET['filename'] = 'homepage.php';
$action = (isset($_GET['action']) ? $_GET['action'] : '');
$lngdir = (isset($_GET['lngdir']) ? $_GET['lngdir'] : '');
switch ($action) {
  case 'save':
    if ( ($lngdir) && ($_GET['filename']) ) {
      if ($_GET['filename'] == $language . '.php') {
        $file = DIR_FS_CATALOG_LANGUAGES . $_GET['filename'];
      } else {
        $file = DIR_FS_CATALOG_LANGUAGES . $_GET['lngdir'] . '/' . $_GET['filename'];
      }
      if (file_exists($file)) {
        if (file_exists('bak' . $file)) {
          @unlink('bak' . $file);
        }
        @rename($file, 'bak' . $file);
        $new_file = fopen($file, 'w');
        $file_contents = stripslashes($_POST['file_contents']);
        fwrite($new_file, $file_contents, strlen($file_contents));
        fclose($new_file);
      }
      tep_redirect(tep_href_link(FILENAME_DEFINE_HOMEPAGE, 'lngdir=' . $_GET['lngdir']));
    }
    break;
}
if (!$lngdir) $lngdir = $language;
$languages_array = array();
$languages = tep_get_languages();
$lng_exists = false;
for ($i=0; $i<sizeof($languages); $i++) {
  if ($languages[$i]['directory'] == $lngdir) $lng_exists = true;
  $languages_array[] = array('id' => $languages[$i]['directory'],
                             'text' => $languages[$i]['name']);
}
if (!$lng_exists) $_GET['lngdir'] = $language;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<?php 
echo tep_load_html_editor();
echo tep_insert_html_editor('file_contents');
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <!-- left_navigation //-->
     <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
     <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo BOX_CATALOG_DEFINE_HOMEPAGE; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <?php
          if ( ($lngdir) && ($_GET['filename']) ) {
            if ($_GET['filename'] == $language . '.php') {
              $file = DIR_FS_CATALOG_LANGUAGES . $_GET['filename'];
            } else {
              $file = DIR_FS_CATALOG_LANGUAGES . $lngdir . '/' . $_GET['filename'];
            }
            if (file_exists($file)) {
              $file_array = @file($file);
              $file_contents = @implode('', $file_array);
              $file_writeable = true;
              if (!is_writeable($file)) {
                $file_writeable = false;
                $messageStack->reset();
                $messageStack->add('homepage', sprintf(ERROR_FILE_NOT_WRITEABLE, $file), 'error');
                if ($messageStack->size('homepage') > 0) {
                  echo $messageStack->output('homepage');
                }    
              }
              ?>
              <tr>
                <?php echo tep_draw_form('language', FILENAME_DEFINE_HOMEPAGE, 'lngdir=' . $lngdir . '&filename=' . $_GET['filename'] . '&action=save'); ?>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main"><b><?php echo $_GET['filename']; ?></b></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo tep_draw_textarea_field('file_contents', 'soft', '80', '20', $file_contents,' style="width: 100%" mce_editable="true"', (($file_writeable) ? '' : 'readonly')); ?></td>
                  </tr>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                  </tr>
                  <tr>
                    <td align="right">
                      <?php 
                      if ($file_writeable) { 
                        echo '<a href="' . tep_href_link(FILENAME_DEFINE_HOMEPAGE, 'lngdir=' . $lngdir) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_save.gif', IMAGE_SAVE); 
                      } else { 
                        echo '<a href="' . tep_href_link(FILENAME_DEFINE_HOMEPAGE, 'lngdir=' . $lngdir) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; 
                      } 
                      ?>
                    </td>
                  </tr>
                </table></td>
                </form>
              </tr>
              <?php
            } else {
              ?>
              <tr>
                <td class="main"><b><?php echo TEXT_FILE_DOES_NOT_EXIST; ?></b></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td><?php 
                echo '<a href="' . tep_href_link(FILENAME_DEFINE_HOMEPAGE, 'lngdir=' . $_GET['lngdir']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
              </tr>
              <?php
            }
          } else {
            $filename = $lngdir . '.php';
            ?>
            <tr>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="smallText"><a href="<?php echo tep_href_link(FILENAME_DEFINE_HOMEPAGE, 'lngdir=' . $_GET['lngdir'] . '&filename=' . $filename); ?>"><b><?php echo $filename; ?></b></a></td>
                  <?php
                  $dir = dir(DIR_FS_CATALOG_LANGUAGES . $lngdir);
                  $left = false;
                  if ($dir) {
                    $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
                    while ($file = $dir->read()) {
                      if (substr($file, strrpos($file, '.')) == $file_extension) {
                        echo '<td class="smallText"><a href="' . tep_href_link(FILENAME_DEFINE_HOMEPAGE, 'lngdir=' . $lngdir . '&filename=' . $file) . '">' . $file . '</a></td>' . "\n";
                        if (!$left) {
                          echo '</tr>' . "\n" . '<tr>' . "\n";
                        }
                        $left = !$left;
                      }
                    }
                    $dir->close();
                  }
                  ?>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
              <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'current_path=' . DIR_FS_CATALOG_LANGUAGES . $_GET['lngdir']) . '">' . tep_image_button('button_file_manager.gif', IMAGE_FILE_MANAGER) . '</a>'; ?></td>
            </tr>
            <?php
          }
          ?>
        </table></td>
      </tr>
    </table></td>
    <!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>