/**
 * Functions for the image listing, used by images.php only    
 * @author $Author: Wei Zhuo $
 * @version $Id: images.js 26 2004-03-31 02:35:21Z Wei Zhuo $
 * @package ImageManager
 */

    function i18n(str) {
        if(I18N)
          return (I18N[str] || str);
        else
            return str;
    };

    function changeDir(newDir) 
    {
        showMessage('Loading');
        location.href = "img_images.php?dir="+newDir+getSessionID();
    }

    function getSessionID()
    {
      arg = new Object();
      href = document.location.href;
      if ( href.indexOf("?") != -1)
      {
        var params = href.split( "?")[1];
        var param = params.split("&");

        for (var i = 0; i < param.length; ++i)
        {
          var name = param[i].split( "=")[0];
          var value = param[i].split( "=")[1];

          arg[name] = value;
        }
      }
      if (arg['osCAdminID']) {
        ret = '&osCAdminID=' + arg['osCAdminID'];
      } else {
        ret = '';
      }
      return ret;
    }
    
    function newFolder(dir, newDir) 
    {
        location.href = "img_images.php?dir="+dir+"&newDir="+newDir+getSessionID();
    }

    //update the dir list in the parent window.
    function updateDir(newDir)
    {
        var selection = window.parent.document.getElementById('dirPath');
        if(selection)
        {
            for(var i = 0; i < selection.length; i++)
            {
                var thisDir = selection.options[i].text;
                if(thisDir == newDir)
                {
                    selection.selectedIndex = i;
                    showMessage('Loading');
                    break;
                }
            }        
        }
    }

    function selectImage(filename, alt, width, height) 
    {
        var topDoc = window.top.document;
        
        var obj = topDoc.getElementById('f_file');  obj.value = filename;
        var obj = topDoc.getElementById('f_url');  obj.value = filename;
        var obj = topDoc.getElementById('f_width');  obj.value = width;
        var obj = topDoc.getElementById('f_width'); obj.value = width;
        var obj = topDoc.getElementById('f_height'); obj.value = height;
        var obj = topDoc.getElementById('f_alt'); obj.value = alt;
        var obj = topDoc.getElementById('orginal_width'); obj.value = width;
        var obj = topDoc.getElementById('orginal_height'); obj.value = height;        
    }

    function showMessage(newMessage) 
    {
        var topDoc = window.top.document;

        var message = topDoc.getElementById('message');
        var messages = topDoc.getElementById('messages');
        if(message && messages)
        {
            if(message.firstChild)
                message.removeChild(message.firstChild);

            message.appendChild(topDoc.createTextNode(i18n(newMessage)));
            
            messages.style.display = "block";
        }
    }

    function addEvent(obj, evType, fn)
    { 
        if (obj.addEventListener) { obj.addEventListener(evType, fn, true); return true; } 
        else if (obj.attachEvent) {  var r = obj.attachEvent("on"+evType, fn);  return r;  } 
        else {  return false; } 
    } 

    function confirmDeleteFile(file) 
    {
        if(confirm(i18n("Delete file?")))
            return true;
    
        return false;        
    }

    function confirmDeleteDir(dir, count) 
    {
        if(count > 0)
        {
            alert(i18n("Please delete all files/folders inside the folder you wish to delete first."));
            return;
        }

        if(confirm(i18n("Delete folder?"))) 
            return true;

        return false;
    }

    addEvent(window, 'load', init);
