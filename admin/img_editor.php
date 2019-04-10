<?php
/**
 * The PHP Image Editor user interface.
 * @author $Author: Wei Zhuo $
 * @version $Id: editor.php 26 2004-03-31 02:35:21Z Wei Zhuo $
 * @package ImageManager
 */

require('includes/application_top.php');
require_once('includes/javascript/image_manager/config.inc.php');
require_once('includes/javascript/image_manager/Classes/ImageManager.php');
require_once('includes/javascript/image_manager/Classes/ImageEditor.php');
require(DIR_WS_LANGUAGES . $language . '/imagemanager_editor.php');

$manager = new ImageManager($IMConfig);
$editor = new ImageEditor($manager);

;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
    <title></title>
<link href="includes/javascript/image_manager/assets/editor.css" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<script type="text/javascript" src="includes/javascript/image_manager/assets/slider.js"></script>
<script type="text/javascript">
/*<![CDATA[*/
    window.resizeTo(673, 531);
    if(window.opener)
      var I18N = window.opener.I18N;
    var height = screen.height;
    var width = screen.width;
    var leftpos = width / 2 - 650 / 2;
    var toppos = height / 2 - 600 / 2; 
    window.moveTo(leftpos, toppos);
/*]]>*/
</script>
<script type="text/javascript" src="includes/javascript/image_manager/assets/pop.js"></script>
<script type="text/javascript" src="includes/javascript/image_manager/assets/editor.js"></script>
</head>

<body>
<div id="indicator">
<img src="img/spacer.gif" id="indicator_image" height="20" width="20" alt="" />
</div>
<div id="tools">
    <div id="tools_crop" style="display:none;">
        <div id="tool_inputs">
            <label for="cx"><?php echo TEXT_IMANAGE_START_X ;?></label><input type="text" id="cx"  class="textInput" onchange="updateMarker('crop')"/>
            <label for="cy"><?php echo TEXT_IMANAGE_START_Y ;?></label><input type="text" id="cy" class="textInput" onchange="updateMarker('crop')"/>
            <label for="cw"><?php echo TEXT_IMANAGE_WIDTH ;?></label><input type="text" id="cw" class="textInput" onchange="updateMarker('crop')"/>
            <label for="ch"><?php echo TEXT_IMANAGE_HEIGHT ;?></label><input type="text" id="ch" class="textInput" onchange="updateMarker('crop')"/>
            <img src="img/div.gif" height="30" width="2" class="div" alt="|" />
        </div>
        <a href="javascript: editor.doSubmit('crop');" class="buttons" title="OK"><img src="img/btn_ok.gif" height="30" width="30" alt="<?php echo TEXT_IMANAGE_OK ;?>" /></a>
        <a href="javascript: editor.reset();" class="buttons" title="Cancel"><img src="img/btn_cancel.gif" height="30" width="30" alt="<?php echo TEXT_IMANAGE_CANCEL ;?>" /></a>
    </div>
    <div id="tools_scale" style="display:none;">
        <div id="tool_inputs">
            <label for="sw"><?php echo TEXT_IMANAGE_WIDTH ;?></label><input type="text" id="sw" class="textInput" onchange="checkConstrains('width')"/>
            <a href="javascript:toggleConstraints();" title="Lock"><img src="img/islocked2.gif" id="scaleConstImg" height="14" width="8" alt="<?php echo TEXT_IMANAGE_LOCK ;?>" class="div" /></a><label for="sh"><?php echo TEXT_IMANAGE_HEIGHT ;?></label>
            <input type="text" id="sh" class="textInput" onchange="checkConstrains('height')"/>
            <input type="checkbox" id="constProp" value="1" checked="checked" onclick="toggleConstraints()"/>
            <label for="constProp"><?php echo TEXT_IMANAGE_CONSTRAIN_PROPORTIONS ;?></label>
            <img src="img/div.gif" height="30" width="2" class="div" alt="|" />
        </div>
        <a href="javascript: editor.doSubmit('scale');" class="buttons" title="OK"><img src="img/btn_ok.gif" height="30" width="30" alt="<?php echo TEXT_IMANAGE_OK ;?>" /></a>
        <a href="javascript: editor.reset();" class="buttons" title="Cancle"><img src="img/btn_cancel.gif" height="30" width="30" alt="<?php echo TEXT_IMANAGE_CANCEL ;?>" /></a>
    </div>
    <div id="tools_rotate" style="display:none;">
        <div id="tool_inputs">
            <select id="flip" name="flip" style="margin-left: 10px; vertical-align: middle;">
              <option selected><?php echo TEXT_IMANAGE_FLIP_IMAGE ;?></option>
              <option>-----------------</option>
              <option value="hoz"><?php echo TEXT_IMANAGE_FLIP_HORZ ;?></option>
              <option value="ver"><?php echo TEXT_IMANAGE_FLIP_VERT ;?></option>
         </select>
            <select name="rotate" onchange="rotatePreset(this)" style="margin-left: 20px; vertical-align: middle;">
              <option selected><?php echo TEXT_IMANAGE_ROTATE_IMAGE ;?></option>
              <option>-----------------</option>

              <option value="180"><?php echo TEXT_IMANAGE_ROTATE_180 ;?></option>
              <option value="90"><?php echo TEXT_IMANAGE_ROTATE_90_CW ;?></option>
              <option value="-90"><?php echo TEXT_IMANAGE_ROTATE_90_CCW ;?></option>
         </select>
            <label for="ra"><?php echo TEXT_IMANAGE_ANGLE ;?></label><input type="text" id="ra" class="textInput" />
            <img src="img/div.gif" height="30" width="2" class="div" alt="|" />
        </div>
        <a href="javascript: editor.doSubmit('rotate');" class="buttons" title="OK"><img src="img/btn_ok.gif" height="30" width="30" alt="<?php echo TEXT_IMANAGE_OK ;?>" /></a>
        <a href="javascript: editor.reset();" class="buttons" title="Cancle"><img src="img/btn_cancel.gif" height="30" width="30" alt="<?php echo TEXT_IMANAGE_CANCEL ;?>" /></a>
    </div>
    <div id="tools_measure" style="display:none;">
        <div id="tool_inputs">
            <label><?php echo TEXT_IMANAGE_X ;?></label><input type="text" class="measureStats" id="sx" />
            <label><?php echo TEXT_IMANAGE_Y ;?></label><input type="text" class="measureStats" id="sy" />
            <img src="img/div.gif" height="30" width="2" class="div" alt="|" />
            <label><?php echo TEXT_IMANAGE_W ;?></label><input type="text" class="measureStats" id="mw" />
            <label><?php echo TEXT_IMANAGE_H ;?></label><input type="text" class="measureStats" id="mh" />
            <img src="img/div.gif" height="30" width="2" class="div" alt="|" />
            <label><?php echo TEXT_IMANAGE_A ;?></label><input type="text" class="measureStats" id="ma" />
            <label><?php echo TEXT_IMANAGE_D ;?></label><input type="text" class="measureStats" id="md" />
            <img src="img/div.gif" height="30" width="2" class="div" alt="|" />
            <button type="button" onclick="editor.reset();" ><?php echo TEXT_IMANAGE_CLEAR ;?></button>
        </div>
    </div>
    <div id="tools_save" style="display:none;">
        <div id="tool_inputs">
            <label for="save_filename"><?php echo TEXT_IMANAGE_FILENAME ;?></label><input type="text" id="save_filename" value="<?php echo $editor->getDefaultSaveFile();;?>"/>
            <select name="format" id="save_format" style="margin-left: 10px; vertical-align: middle;" onchange="updateFormat(this)">
            <option value="" selected><?php echo TEXT_IMANAGE_IMAGE_FORMAT ;?></option>
            <option value="">---------------------</option>
            <option value="jpeg,85"><?php echo TEXT_IMANAGE_JPEG_HIGH ;?></option>
            <option value="jpeg,60"><?php echo TEXT_IMANAGE_JPEG_MED ;?></option>
            <option value="jpeg,35"><?php echo TEXT_IMANAGE_JPEG_LOW ;?></option>
            <option value="png"><?php echo TEXT_IMANAGE_PNG ;?></option>
            <?php if($editor->isGDGIFAble() != -1) { ;?>
            <option value="gif"><?php echo TEXT_IMANAGE_GIF ;?></option>
            <?php } ;?>
         </select>
            <label>Quality:</label>
            <table style="display: inline; vertical-align: middle;" cellpadding="0" cellspacing="0">
                <tr>
                <td>
                    <div id="slidercasing">
                <div id="slidertrack" style="width:100px"><img src="img/spacer.gif" width="1" height="1" border="0" alt="track"></div>
            <div id="sliderbar" style="left:85px" onmousedown="captureStart();"><img src="img/spacer.gif" width="1" height="1" border="0" alt="<?php echo TEXT_IMANAGE_TRACK ;?>"></div>
            </div>
                </td>
                </tr>
            </table>
            <input type="text" id="quality" onchange="updateSlider(this.value)" style="width: 2em;" value="85"/>
            <img src="img/div.gif" height="30" width="2" class="div" alt="|" />
        </div>
        <a href="javascript: editor.doSubmit('save');" class="buttons" title="OK"><img src="img/btn_ok.gif" height="30" width="30" alt="<?php echo TEXT_IMANAGE_OK ;?>" /></a>
        <a href="javascript: editor.reset();" class="buttons" title="Cancel"><img src="img/btn_cancel.gif" height="30" width="30" alt="<?php echo TEXT_IMANAGE_CANCEL ;?>" /></a>
    </div>
</div>
<div id="toolbar">
<a href="javascript:toggle('crop')" id="icon_crop" title="Crop"><img src="img/crop.gif" height="20" width="20" alt="<?php echo TEXT_IMANAGE_CROP ;?>" /><span><?php echo TEXT_IMANAGE_CROP ;?></span></a>
<a href="javascript:toggle('scale')" id="icon_scale" title="Resize"><img src="img/scale.gif" height="20" width="20" alt="<?php echo TEXT_IMANAGE_RESIZE ;?>" /><span><?php echo TEXT_IMANAGE_RESIZE ;?></span></a>
<a href="javascript:toggle('rotate')" id="icon_rotate" title="Rotate"><img src="img/rotate.gif" height="20" width="20" alt="<?php echo TEXT_IMANAGE_ROTATE ;?>" /><span><?php echo TEXT_IMANAGE_ROTATE ;?></span></a>
<a href="javascript:toggle('measure')" id="icon_measure" title="Measure"><img src="img/measure.gif" height="20" width="20" alt="<?php echo TEXT_IMANAGE_MEASURE ;?>" /><span><?php echo TEXT_IMANAGE_MEASURE ;?></span></a>
<a href="javascript:toggleMarker();" title="Marker"><img id="markerImg" src="img/t_black.gif" height="20" width="20" alt="<?php echo TEXT_IMANAGE_MARKER ;?>" /><span><?php echo TEXT_IMANAGE_MARKER ;?></span></a>
<a href="javascript:toggle('save')" id="icon_save" title="Save"><img src="img/save.gif" height="20" width="20" alt="<?php echo TEXT_IMANAGE_SAVE ;?>" /><span><?php echo TEXT_IMANAGE_SAVE ;?></span></a>
<a href="javascript:window.close()" id="icon_close" title="close"><img src="img/close_window.jpg" height="20" width="20" alt="<?php echo TEXT_IMANAGE_CLOSE ;?>" /><span><?php echo TEXT_IMANAGE_CLOSE ;?></span></a>

</div>
<div id="contents">
<iframe src="<?php echo tep_href_link('img_editorFrame.php', isset($_GET['img']) ? 'img=' . rawurlencode($_GET['img']) : '', 'SSL');?>" name="editor" id="editor"  scrolling="auto" title="Image Editor" frameborder="0"></iframe>
</div>
<div id="bottom"></div>
</body>
</html>
