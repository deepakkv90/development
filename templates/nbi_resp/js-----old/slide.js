// JavaScript Document


var slideSpeed = 10;	// Higher value = faster
var timer = 10;	// Lower value = faster

var objectIdToSlideDown = false;
var activeId = false;
var slideInProgress = false;
function showHideContent(e,inputId)
{
	if(slideInProgress)return;
	slideInProgress = true;
	if(!inputId)inputId = this.id;
	inputId = inputId + '';
	var numericId = inputId.replace(/[^0-9]/g,'');
	var answerDiv = document.getElementById('aa' + numericId);

	objectIdToSlideDown = false;
	
	if(!answerDiv.style.display || answerDiv.style.display=='none'){		
		if(activeId &&  activeId!=numericId){			
			objectIdToSlideDown = numericId;
			slideContent(activeId,(slideSpeed*-1));
		}else{
			
			answerDiv.style.display='block';
			answerDiv.style.visibility = 'visible';
			
			slideContent(numericId,slideSpeed);
		}
	}else{
		slideContent(numericId,(slideSpeed*-1));
		activeId = false;
	}	
}

function slideContent(inputId,direction)
{
	
	var obj =document.getElementById('aa' + inputId);
	var contentObj = document.getElementById('ac' + inputId);
	height = obj.clientHeight;
	if(height==0)height = obj.offsetHeight;
	height = height + direction;
	rerunFunction = true;
	if(height>contentObj.offsetHeight){
		height = contentObj.offsetHeight;
		rerunFunction = false;
	}
	if(height<=1){
		height = 1;
		rerunFunction = false;
	}

	obj.style.height = height + 'px';
	var topPos = height - contentObj.offsetHeight;
	if(topPos>0)topPos=0;
	contentObj.style.top = topPos + 'px';
	if(rerunFunction){
		setTimeout('slideContent(' + inputId + ',' + direction + ')',timer);
	}else{
		if(height<=1){
			obj.style.display='none'; 
			if(objectIdToSlideDown && objectIdToSlideDown!=inputId){
				document.getElementById('aa' + objectIdToSlideDown).style.display='block';
				document.getElementById('aa' + objectIdToSlideDown).style.visibility='visible';
				slideContent(objectIdToSlideDown,slideSpeed);				
			}else{
				slideInProgress = false;
			}
		}else{
			activeId = inputId;
			slideInProgress = false;
		}
	}
}


function initShowHideDivs()
{
	var divs = document.getElementsByTagName('DIV');
	var divCounter = 1;
	for(var no=0;no<divs.length;no++){
		if(divs[no].className=='question'){
			divs[no].onclick = showHideContent;
			divs[no].id = 'q'+divCounter;
			var answer = divs[no].nextSibling;
			while(answer && answer.tagName!='DIV'){
				answer = answer.nextSibling;
			}
			answer.id = 'aa'+divCounter;	
			contentDiv = answer.getElementsByTagName('div')[0];
			contentDiv.style.top = 0 - contentDiv.offsetHeight + 'px'; 	
			contentDiv.className='ans_content';
			contentDiv.id = 'ac' + divCounter;
			answer.style.display='none';
			answer.style.height='1px';
			divCounter++;
		}		
	}	
}
//window.onload = initShowHideDivs; //18 02 2013





