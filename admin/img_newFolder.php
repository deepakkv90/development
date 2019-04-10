<?php
require('includes/application_top.php');
require_once('includes/javascript/image_manager/config.inc.php');

require(DIR_WS_LANGUAGES . $language . '/imagemanager_newfolder.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?php echo TEXT_IMANAGE_NEW_FOLDER ;?></title>
 <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

 <style type="text/css">
 /*<![CDATA[*/
 .title { background-color: #AEBDCC; color: #000; font-weight: bold; font-size: 120%; padding: 3px 10px; margin-bottom: 10px; border-bottom: 1px  solid black; letter-spacing: 2px;}
select, input, button { font: 11px Tahoma,Verdana,sans-serif; }
.buttons { width: 70px; text-align: center; }
form { padding: 0px;  margin: 0;}
form .elements{
    padding: 10px; text-align: center;
}
 /*]]>*/
 </style>
<script type="text/javascript">
/*<![CDATA[*/
    window.resizeTo(300, 185);

    if(window.opener)
      var I18N = window.opener.I18N;

    init = function ()
    {
        __dlg_init();
        __dlg_translate(I18N);
        document.getElementById("f_foldername").focus();
    }

    function onCancel()
    {
        __dlg_close(null);
        return false;
    }

    function onOK()
    {
         // pass data back to the calling window
      var fields = ["f_foldername"];
      var param = new Object();
      for (var i in fields) {
        var id = fields[i];
        var el = document.getElementById(id);
        param[id] = el.value;
      }
      __dlg_close(param);
      return false;
    }

    function addEvent(obj, evType, fn)
    {
        if (obj.addEventListener) { obj.addEventListener(evType, fn, true); return true; }
        else if (obj.attachEvent) {  var r = obj.attachEvent("on"+evType, fn);  return r;  }
        else {  return false; }
    }

    addEvent(window, 'load', init);
//-->
</script>
<script type="text/javascript" src="includes/javascript/image_manager/assets/popup.js"></script>
</head>
<body >
<div class="title"><?php echo TEXT_IMANAGE_NEW_FOLDER ;?></div>
<form action="">
<div class="elements">
    <label for="f_foldername"><?php echo TEXT_IMANAGE_FOLDER_NAME;?>:</label>
    <input type="text" id="f_foldername" />
</div>
<div style="text-align: right;">
      <hr />
      <?php echo tep_image_button('button_ok.gif', TEXT_IMANAGE_OK, 'onclick="return onOK();"');?>
      <?php echo tep_image_button('button_ok.gif', TEXT_IMANAGE_CANCEL, 'onclick="return onCancel();"');?>
</div>
</form>
</body>
</html>
