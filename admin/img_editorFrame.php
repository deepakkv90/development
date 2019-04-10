<?php

/**
 * The frame that contains the image to be edited.
 * @author $Author: Wei Zhuo $
 * @version $Id: editorFrame.php 26 2004-03-31 02:35:21Z Wei Zhuo $
 * @package ImageManager
 */

require('includes/application_top.php');
require_once('includes/javascript/image_manager/config.inc.php');
require_once('includes/javascript/image_manager/Classes/ImageManager.php');
require_once('includes/javascript/image_manager/Classes/ImageEditor.php');
require(DIR_WS_LANGUAGES . $language . '/imagemanager_editorframe.php');

$manager = new ImageManager($IMConfig);
$editor = new ImageEditor($manager);
$imageInfo = $editor->processImage();

;?>

<html>
<head>
    <title></title>
<link href="includes/javascript/image_manager/assets/editorFrame.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script type="text/javascript" src="includes/javascript/image_manager/assets/wz_jsgraphics.js"></script>
<script type="text/javascript" src="includes/javascript/image_manager/assets/EditorContent.js"></script>
<script type="text/javascript">
if(window.top)
   var I18N = window.top.I18N;

function i18n(str) {
    if(I18N)
        return (I18N[str] || str);
    else
        return str;
};

    var mode = "<?php echo $editor->getAction(); ;?>" //crop, scale, measure

var currentImageFile = "<?php if(count($imageInfo)>0) echo rawurlencode($imageInfo['file']); ;?>";

<?php if ($editor->isFileSaved() == 1) { ;?>
    alert('<?php echo TEXT_IMANAGE_SAVED; ?>');
<?php } else if ($editor->isFileSaved() == 2) { ;?>
    alert('<?php echo TEXT_IMANAGE_NOT_SAVED; ?>');
<?php } ;?>

</script>
<script type="text/javascript" src="includes/javascript/image_manager/assets/pop.js"></script>
<script type="text/javascript" src="includes/javascript/image_manager/assets/editorFrame.js"></script>
</head>

<body>
<div id="status"></div>
<div id="ant" class="selection" style="visibility:hidden"><img src="img/spacer.gif" width="0" height="0" border="0" alt="" id="cropContent"></div>

<?php if ($editor->isGDEditable() == 2) { ;?>
    <div style="text-align:center; padding:10px;"><span class="error"><?php echo TEXT_IMANAGE_ERROR_1 ;?></span></div>
<?php } ;?>
<table height="100%" width="100%">
    <tr>
        <td>
<?php if(count($imageInfo) > 0 && is_file($imageInfo['fullpath'])) { ;?>
    <span id="imgCanvas" class="crop"><img src="<?php echo $imageInfo['src']; ;?>" <?php echo $imageInfo['dimensions']; ;?> alt="" id="theImage" name="theImage"></span>
<?php } else { ;?>
    <span class="error"><?php echo TEXT_IMANAGE_ERROR_2 ;?></span>
<?php } ;?>
        </td>
    </tr>
</table>
</body>
</html>
