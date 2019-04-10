
/** Instantiate namespace object */
var Matt = {};

/** Search DOM for a matching child of the given node
* @param Element Begin searching from this node
* @param string Tag name to match
* @returns Element */
Matt.FindChildByTag = function(using, findTag)
{
	findTag = String(findTag).toUpperCase();
	var searchList = using.getElementsByTagName(findTag);
	return searchList[0];
};

/** Search DOM for a specified parent of the given node
* @param Element Begin searching from this node
* @param string Tag name to match
* @returns Element */
Matt.FindParentByTag = function(using, findTag)
{
	findTag = String(findTag).toUpperCase();
	var found = using.parentNode;
	while (found && (found.tagName != findTag))
		{found = found.parentNode;}
	return found;
};

/** Comment 
* @param mixed Comment 
* @returns bool */
Matt.ActivateRadioButton = function(radio)
{
	var radioElem;
	if (radio.nodeName && (radio.nodeName == 'INPUT'))
		{ radioElem = radio; }
	else if (radio.toString)
		{ radioElem = document.getElementById(radio.toString()); }
	if (!radioElem)
		{ return false; }
	radioElem.checked = true;
	return true;
};

/** Comment 
* @param mixed Comment 
* @returns  */
Matt.OpenAnchorAsPopup = function(anchor, width, height)
{
	if (!anchor.href)
		{ return false; }
	window.open(anchor.href, 'PopupWindow', 'width='+width+'px, height='+height+'px');
	return true;
};

/** Comment 
* @param Element	Comment 
* @param Event		Comment 
* @returns boolean */
Matt.ReceivedClick = function(elem, evnt)
{
	var test = (evnt.target) 
		? evnt.target 
		: evnt.srcElement;
	
	while (test)
	{
		if (test == elem) {
			return true;
		}
		test = test.parentNode;
	}
	
	return false;
};


//-------------------------------------------------------------------------------------


(function(){
	
	var prevLoad = window.onload;
	window.onload = function() {
		
		var langArea = document.getElementById('langMenu');
		if (langArea)
		{
			document.body.onclick = function(evnt) {
				evnt = (evnt) ? evnt : event; // IE fix
				if (!Matt.ReceivedClick(langArea, evnt)) {
					langArea.className = '';
				}
			};
			
			Matt.FindChildByTag(langArea, 'A').onclick = function() {
				langArea.className = (langArea.className == 'open') ? '' : 'open';
				return false;
			};
		}
		
		if (prevLoad) {
			prevLoad();
		}
		
	};
	
})();

