$(function () {
	if (M1SEO.updateIsStarted)
	{
		$('.update_now').hide();
		$('.update_status').show();
		requestUpdateStatus();
	}
	
	$('.update_button').click(function () {
		$('#update_status').css('background-color', '#ccffcc');
		$('.update_now').hide();
		$('.update_status').show();
		$('.update_date').hide();
		$('.update_status_text').text('Updating keywords...');
		triesCount = 0;
		$.getJSON($(this).attr('href'), onUpdateCallback);
		requestUpdateStatus();
		return false;
	});
	
	$('a.abutton').click(function () {
		$(this).parents('.control').find('a.abutton').show()
		.end().find('span.abutton').hide();
		$(this).hide().next('span').show();
		
		if ($(this).attr('href') != '#') {
			$.getJSON($(this).attr('href'), {
				'ajax': 1
			}, function(data){
				// Process result of update
			});
		}
		
		return false;	
	});
	
	$('.urltplselect').hover(function () {
		if (!$(this).hasClass('tplselected')) {
			$(this).css({
				'background-color': '#dff5ff',
				'border': '1px #b5e7ff solid'
			});
		}
	}, function () {
		if (!$(this).hasClass('tplselected')) {
			$(this).css({
				'background-color': '#ffffff',
				'border': '1px #ffffff solid'
			});
		}
	});
	
	$('.urltplselect').click(clickUrlTemplate);
	
	$('span.abutton').click(function () {
		
	});
	$('.clearcache').click(function () {
		$('.clearstatus').html('Clearing...');
		$.getJSON($(this).attr('href'), onClearCacheCallback);
		return false;
	})
	//$('.configrow:nth-child(odd)').css('background-color', '#efefef').css('color','#333');
	//$('.configrow:nth-child(even)').css('background-color', '#fff').css('color','#333');

});
var triesCount = 0;
var resultUpdateStatusTimeout;
var resultMessageTimeout;

function clickUrlTemplate()
{
    if (confirm('Changing URL Template can affect your website SEO rankings\nAre you sure you want to change URL Template?')) {
    	$('.urltplselect').removeClass('tplselected').css({
    		'background-color': '#ffffff',
    		'border': '1px #ffffff solid'
    	});
    	
    	$(this).addClass('tplselected').css({
    		'background-color': '#dff5ff',
    		'border': '1px #5fcbfe solid'
    	});
    	
    	var updateUrl = $(this).find('span.updateurl').text();
    	$.getJSON(updateUrl, {
    				'ajax': 1
    			}, function(data){
    				// Process result of update
    			});
    }
}

function requestUpdateStatus() {
	$.getJSON(M1SEO.statusurl, onUpdateStatusCallback);
}

function onUpdateStatusCallback(data)
{
	if (data['started'] == 'false' && ++triesCount > 2) {
		clearTimeout(resultUpdateStatusTimeout);
		triesCount = 0;
		$('.update_status').hide();
		$('.update_date').show();
		$('.update_now').show();
		return;
	}
	
	if (data['status'] != '')
	{
		$('.update_status_text').text(data['status']);	
	}
	else
	{
		$('.update_status_text').text('Updating keywords...')
	}
	
	$('.update_date .ud_date_time').text(data['latest_update']);
	
	clearTimeout(resultUpdateStatusTimeout);
	resultUpdateStatusTimeout = setTimeout(requestUpdateStatus, M1SEO.statusUpdateInterval);
}

function onUpdateCallback(data)
{
	clearTimeout(resultUpdateStatusTimeout);
	$('.update_status').hide();
	$('.update_now').show();
	if (data['status'] == 'ready')
	{
		$('.update_result')
			.css('display','inline')
			.text('Keywords successfully updated');
		
		$('#update_status').css('background-color', '#ccffcc');
		$('.update_date .ud_date_time').text(data['latest_update']);
	} 
	else
	{
		$('.update_result').css('display','inline').text('Keywords updating now');
		$('#update_status').css('background-color', '#ffcccc');
	}
		
	clearTimeout(resultMessageTimeout);
	resultMessageTimeout = setTimeout(function () {
		$('.update_result').fadeOut(300).queue(function() {
			$('.update_date').fadeIn(500);
			$(this).dequeue();
		});
	}, 3000);
}

function onClearCacheCallback(data) {
	$('.clearstatus').html('Cache cleared');
	setTimeout(function () {
		$('.clearstatus').html('');
	}, 3000)
}
