<?php
/**
 * The main GUI for the ImageManager.
 * @author $Author: Wei Zhuo $
 * @version $Id: manager.php 26 2004-03-31 02:35:21Z Wei Zhuo $
 * @package ImageManager
 */

require('includes/application_top.php');
require_once('includes/javascript/image_manager/config.inc.php');
require_once('includes/javascript/image_manager/Classes/ImageManager.php');
//    include('lang/en.php');

require(DIR_WS_LANGUAGES . $language . '/imagemanager_manger.php');

    $manager = new ImageManager($IMConfig);
    $manager->processUploads();
    $dirs = $manager->getDirs();
    
;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
    <title>Insert Image</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <base href="" />
 <link rel="stylesheet" type="text/css" href="includes/javascript/image_manager/assets/manager.css" />
 <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script type="text/javascript" src="includes/javascript/image_manager/assets/popup.js"></script>
<script type="text/javascript" src="includes/javascript/image_manager/assets/dialog.js"></script>
<script type="text/javascript">
/*<![CDATA[*/
//    window.resizeTo(720, 520);

    if(window.opener)
      var I18N = window.opener.ImageManager.I18N;

    var thumbdir = "<?php echo $IMConfig['thumbnail_dir']; ;?>";
    var base_url = "<?php echo $manager->getBaseURL(); ;?>";
/*]]>*/
</script>
<script type="text/javascript" src="includes/javascript/image_manager/assets/manager.js"></script>
</head>
<body>
<div class="title"><?php echo TEXT_IMANAGE_INSERT_IMAGE ;?></div>
<form action="<?php echo tep_href_link('img_manager.php', '', 'SSL');?>" id="uploadForm" method="post" enctype="multipart/form-data">
<fieldset><legend><?php echo TEXT_IMANAGE_IMAGE_MANAGER ;?></legend>
<div class="dirs">
    <label for="dirPath"><?php echo TEXT_IMANAGE_DIRECTORY ;?></label>
    <select name="dir" class="dirWidth" id="dirPath" onchange="updateDir(this)">
    <option value="/">/</option>
<?php foreach($dirs as $relative=>$fullpath) { ;?>
        <option value="<?php echo rawurlencode($relative); ;?>"><?php echo $relative ;?></option>
<?php } ;?>
    </select>
    <a href="#" onclick="javascript: goUpDir();" title="Directory Up"><img src="includes/javascript/image_manager/img/btnFolderUp.gif" height="15" width="15" alt="Directory Up" /></a>
<?php if($IMConfig['safe_mode'] == false && $IMConfig['allow_new_dir']) { ;?>
    <a href="#" onclick="newFolder();" title="New Folder"><img src="includes/javascript/image_manager/img/btnFolderNew.gif" height="15" width="15" alt="New Folder" /></a>
<?php } ;?>
    <div id="messages" style="display: none;"><span id="message"></span><img SRC="includes/javascript/image_manager/img/dots.gif" width="22" height="12" alt="..." /></div>
    <iframe src="<?php echo tep_href_link('img_images.php', '', 'SSL')?>" name="imgManager" id="imgManager" class="imageFrame" scrolling="auto" title="Image Selection" frameborder="0"></iframe>
</div>
</fieldset>
<!-- image properties -->
    <table class="inputTable">
        <tr>
            <td align="right"><label for="f_url"><?php echo TEXT_IMANAGE_IMAGE_FILE ;?></label></td>
            <td><input type="text" id="f_url" class="largelWidth" value="" /></td>
            <td rowspan="3" align="right">&nbsp;</td>
        </tr>
        <tr>
            <td align="right"><label for="f_alt"><?php echo TEXT_IMANAGE_ALT; ?></label></td>
            <td><input type="text" id="f_alt" class="largelWidth" value="" /></td>
        </tr>
        <tr>
<?php if($IMConfig['allow_upload'] == true) { ;?>
            <td align="right"><label for="upload"><?php echo TEXT_IMANAGE_UPLOAD; ?></label></td>
            <td>
                <table cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td><input type="file" name="upload" id="upload"/></td>
                    <td>&nbsp;</button><?php echo tep_image_submit('buttons_submit', TEXT_IMANAGE_UPLOAD, 'onclick="doUpload();"'); ?></td>
                  </tr>
                </table>
            </td>
<?php } else { ;?>
            <td colspan="2"></td>
<?php } ;?>
        </tr>
        <tr>
         <td colspan="4" align="right">
                <input type="hidden" id="orginal_width" />
                <input type="hidden" id="orginal_height" />
            <input type="checkbox" id="constrain_prop" checked="checked" onclick="javascript:toggleConstrains(this);" />
          </td>
          <td colspan="5"><label for="constrain_prop"><?php echo TEXT_IMANAGE_CONSTRAIN_PROPORTIONS; ?></label></td>
      </tr>
    </table>
<!--// image properties -->
    <div style="text-align: right;">
          <hr />
          <?php echo tep_image_button('button_refresh.gif', TEXT_IMANAGE_REFRESH, 'onclick="return refresh();"');?>
          <?php echo tep_image_button('button_ok.gif', TEXT_IMANAGE_OK, 'onclick="return onOK();"');?>
          <?php echo tep_image_button('button_ok.gif', TEXT_IMANAGE_CANCEL, 'onclick="return onCancel();"');?>
    </div>
    <input type="hidden" id="f_file" name="f_file" />
</form>
</body>
</html>
