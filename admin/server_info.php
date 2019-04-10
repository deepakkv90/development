<?php
/*
  $Id: server_info.php,v 1.1.1.1 2004/03/04 23:38:58 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
if (function_exists(tep_get_system_information)) {
  $system = tep_get_system_information();
} else {
  $system = '';  
}
if (!defined('TEXT_FAILED')) define('TEXT_FAILED', 'Failed');
if (!defined('TEXT_SUCCESS')) define('TEXT_SUCCESS', 'Success');
if (!defined('TEXT_ON')) define('TEXT_ON', 'On');
if (!defined('TEXT_OFF')) define('TEXT_OFF', 'Off');

function checkINI($option) {
  $value = strtolower( trim( ini_get( $option ) ) );
  if ( $value == 'on' ) $value = TEXT_ON;
  elseif ( $value == 'off' )  $value = TEXT_OFF;
  elseif ( $value == '1' )  $value = TEXT_ON;
  elseif ( $value == '0' ) $value = TEXT_OFF;
  else $value = TEXT_OFF;
 
  return $value;
}
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
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
      <table border="1" cellpadding="3" width="100%" style="border: 0px; border-color: #000000;">
        <tr>
          <td><table cellpadding="2" cellspacing="2" border="0" width="100%">
            <tr><td colspan="3"><a href="http://www.creloaded.com/" target="_blank"><img border="0" src="images/loaded_header_logo.gif" alt="CRE Loaded"  /></a><h1 class="p"><?php echo PROJECT_VERSION . ' at revision:' . INSTALLED_VERSION_REVISION; ?></h1></td></tr>
            <?php
            // RCI include version files
            $returned_rci = $cre_RCI->get('serverinfo', 'version');
            ?>
            <tr><td colspan="3"><?php echo $returned_rci; ?></td></tr> 
            <tr>
              <td colspan="3"><?php echo tep_draw_separator('pixel_black.gif', '100%', '1'); ?></td>
            </tr>
            <tr>
              <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
            </tr>  
            <tr>
              <td colspan="3"><table border="0" cellspacing="0" cellpadding="3">
                <tr>
                  <td class="smallText" width="90"><b><?php echo TITLE_SERVER_HOST; ?></b></td>
                  <td class="smallText"><?php echo $system['host'] . ' (' . $system['ip'] . ')'; ?></td>
                  <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TITLE_DATABASE_HOST; ?></b></td>
                  <td class="smallText"><?php echo $system['db_server'] . ' (' . $system['db_ip'] . ')'; ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b><?php echo TITLE_SERVER_OS; ?></b></td>
                  <td class="smallText"><?php echo $system['system'] . ' ' . $system['kernel']; ?></td>
                  <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TITLE_DATABASE; ?></b></td>
                  <td class="smallText"><?php echo $system['db_version']; ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b><?php echo TITLE_SERVER_DATE; ?></b></td>
                  <td class="smallText"><?php echo $system['date']; ?></td>
                  <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TITLE_DATABASE_DATE; ?></b></td>
                  <td class="smallText"><?php echo $system['db_date']; ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b><?php echo TITLE_SERVER_UP_TIME; ?></b></td>
                  <td colspan="3" class="smallText"><?php echo $system['uptime']; ?></td>
                </tr>
                <tr>
                  <td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b><?php echo TITLE_HTTP_SERVER; ?></b></td>
                  <td colspan="3" class="smallText"><?php echo $system['http_server']; ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b><?php echo TITLE_PHP_VERSION; ?></b></td>
                  <td colspan="3" class="smallText"><?php echo $system['php'] . ' (' . TITLE_ZEND_VERSION . ' ' . $system['zend'] . ')'; ?></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
            </tr>
            <tr>
              <td colspan="3"><?php echo tep_draw_separator('pixel_black.gif', '100%', '1'); ?></td>
            </tr>
            <tr>
              <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
            </tr>
            <tr>
              <td align="center"><b>Server Requirements</b><br><?php echo TEXT_PCI; ?>
              <td align="center"><b>Required PHP Settings</b></td>
              <td align="center"><b>Writable Folder Permissions</b></td>
            </tr>        
            <tr>
              <td align="center" valign="top"><table border="1" cellspacing="0" cellpadding="3" style="border: 0px; border-color: #000000;">
                <tr>
                  <td><a href="http://www.php.net/downloads.php" target="php">PHP >= 5.2.0</a></td>
                  <td align="left">
                    <?php echo ( phpversion() < '5.2.0' ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.mysql.php" target="php">PHP MySQL</a></td>
                  <td align="left">
                    <?php echo ( !extension_loaded('mysql') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <?php
                if ( phpversion() > '5.0' ) {
                  ?>
                  <tr>
                    <td><a href="http://www.php.net/manual/en/ref.mysqli.php" target="php">PHP MySQLi</a></td>
                    <td align="left">
                      <?php echo ( !extension_loaded('mysqli') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                    </td>
                  </tr>
                  <?php
                }
                ?>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.pcre.php" target="php">PHP PCRE</a></td>
                  <td align="left">
                    <?php echo ( !extension_loaded('pcre') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.zlib.php" target="php">PHP ZLIB</a></td>
                  <td align="left">
                    <?php echo ( !function_exists( 'gzencode' ) ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.curl.php" target="php">PHP cURL</a></td>
                  <td align="left">
                    <?php echo ( !extension_loaded('curl') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.exif.php" target="php">PHP EXIF</a></td>
                  <td align="left">
                    <?php echo ( !extension_loaded('exif') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.image.php" target="php">PHP GD</a></td>
                  <td align="left">
                    <?php echo ( !extension_loaded('gd') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.mcrypt.php" target="php">PHP Mcrypt</a></td>
                  <td align="left">
                    <?php echo ( !extension_loaded('mcrypt') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.openssl.php" target="php">PHP OPENSSL</a></td>
                  <td align="left">
                    <?php echo ( !extension_loaded('openssl') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td><a href="http://www.php.net/manual/en/ref.ftp.php" target="php">PHP FTP</a></td>
                  <td align="left">
                    <?php echo ( !extension_loaded('ftp') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
              </table></td>
              <td align="center" valign="top"><table border="1" cellspacing="0" cellpadding="3" style="border: 0px; border-color: #000000;">
                <tr>
                  <td>safe_mode</td>
                  <td><?php echo ($ini_setting = checkINI('safe_mode')); ?></td>
                  <td align="left">
                    <?php echo ( $ini_setting == TEXT_ON  ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td>file_uploads</td>
                  <td><?php echo ($ini_setting = checkINI('file_uploads')); ?></td>
                  <td align="left">
                    <?php echo ( $ini_setting != TEXT_ON  ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td>session.auto_start</td>
                  <td><?php echo ($ini_setting = checkINI('session.auto_start')); ?></td>
                  <td align="left">
                    <?php echo ( $ini_setting == TEXT_ON  ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td>magic_quotes</td>
                  <td><?php echo ($ini_setting = checkINI('magic_quotes')); ?></td>
                  <td align="left">
                    <?php echo ( $ini_setting == TEXT_ON  ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
                <tr>
                  <td>allow_url_fopen</td>
                  <td><?php echo ($ini_setting = checkINI('allow_url_fopen')); ?></td>
                  <td align="left">
                    <?php echo ( $ini_setting != TEXT_ON  ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                  </td>
                </tr>
              </table></td>
              <td align="center" valign="top"><table border="1" cellspacing="0" cellpadding="3" style="border: 0px; border-color: #000000;">
                <?php 
                if (file_exists(DIR_FS_CATALOG . 'tmp/')) { 
                  ?>
                  <tr>                   
                    <td><?php echo DIR_FS_CATALOG . 'tmp/'; ?></td>
                    <td align="left">
                      <?php echo ( !is_writable(DIR_FS_CATALOG . 'tmp/') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                    </td>
                  </tr>
                  <?php 
                }
                if (file_exists(DIR_FS_CATALOG . 'temp/')) { 
                  ?>
                  <tr>                   
                    <td><?php echo DIR_FS_CATALOG . 'temp/'; ?></td>
                    <td align="left">
                      <?php echo ( !is_writable(DIR_FS_CATALOG . 'temp/') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                    </td>
                  </tr>
                  <?php 
                }
                if (file_exists(DIR_FS_CATALOG . 'cache/')) { 
                  ?>
                  <tr>                   
                    <td><?php echo DIR_FS_CATALOG . 'cache/'; ?></td>
                    <td align="left">
                      <?php echo ( !is_writable(DIR_FS_CATALOG . 'cache/') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                    </td>
                  </tr>
                  <?php 
                }
                if (file_exists(DIR_FS_CATALOG . 'images/')) { 
                  ?>
                    <tr>                   
                    <td><?php echo DIR_FS_CATALOG . 'images/'; ?></td>
                    <td align="left">
                      <?php echo ( !is_writable(DIR_FS_CATALOG . 'images/') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                    </td>
                  </tr>
                  <?php 
                }
                if (file_exists(DIR_FS_CATALOG . 'debug/')) { 
                  ?>
                  <tr>                   
                    <td><?php echo DIR_FS_CATALOG . 'debug/'; ?></td>
                    <td align="left">
                      <?php echo ( !is_writable(DIR_FS_CATALOG . 'debug/') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                    </td>
                  </tr>
                  <?php 
                }
                if (file_exists(DIR_FS_ADMIN . 'backups/')) { ?>
                  <tr>                   
                    <td><?php echo DIR_FS_ADMIN . 'backups/'; ?></td>
                    <td align="left">
                      <?php echo ( !is_writable(DIR_FS_ADMIN . 'backups/') ) ? tep_image('images/icons/cross.gif', TEXT_FAILED) : tep_image('images/icons/tick.gif', TEXT_SUCCESS); ?>
                    </td>
                  </tr>
                  <?php 
                } ?>
              </table</td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td>
              <?php
              if (function_exists('ob_start')) {
                ?>
                <style type="text/css">
                  .p {text-align: left;}
                  .e {background-color: #ccccff; font-weight: bold;}
                  .h {background-color: #9999cc; font-weight: bold;}
                  .v {background-color: #cccccc;}
                   i {color: #666666;}
                  hr {display: none;}
                </style>
                <?php
                ob_start();
                phpinfo();
                $phpinfo = ob_get_contents();
                ob_end_clean();
                $phpinfo = str_replace('border: 1px', '', $phpinfo);
                ereg('<body>(.*)</body>', $phpinfo, $regs);
                echo $regs[1];
              } else {
                phpinfo();
              }
              ?>
              </td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td>Apache Modules:<br><?php $apmods = print_r(apache_get_modules(), true); echo nl2br($apmods); ?></td>
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