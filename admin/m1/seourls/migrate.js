  var isDbNotEmpty = 0;
  var M1SEO = {};
  UpdateInterval = 1500;
  //M1SEO.status = url_migrate;
  M1SEO.updateIsStarted = false;
  var resultUpdateStatusTimeout;
  var resultMessageTimeout;
$(document).ready(function () {
  /*$('.update_button').click(function (){
    $.ajax({
      type       : 'GET',
      dataType   : 'json',
      url        : url_migrate,
      error      : function (){
	$('span.loader').html('Error Upgrade');
      },
      success    : function (data){
	$('span.loader').html('');
	console.log (data);
        if (data.status == 'ready') {
          $('span.loader').html('Upgraded');
        }
      },
      beforeSend : function (){
        $('span.loader').html('<img src="m1/seourls/tpl/images/loader.gif" />');
      }
    });
    $.getJSON($(this).attr('href'), onUpdateCallback);
		requestUpdateStatus();
		return false;
  });*/
  $('#update_status').css('background-color', '#EEEEEE');
	$('.update_button').click(function () {
	  if (isDbNotEmpty==1 && !confirm('Repeated upgrade will destroy all existing seo keywords. Proceed?', 'Reupgrade to Power SEO')) {
		
	  } else {
		//$('#update_status').css('background-color', '#ccffcc');
		$('.migration_result').hide();
		$('.update_now').hide();
		$('.update_status').show();
		//$('.update_date').hide();
		$('.update_status_text').text('Migrating keywords...');
		triesCount = 0;
		$.getJSON(urlmigration, onUpdateCallback);
		requestUpdateStatus();
		return false;
	  }
	});
});

function onUpdateCallback(data)
{
	clearTimeout(resultUpdateStatusTimeout);
	$('.update_status').hide();
	$('.update_now').show();
	if (data['status'] == 'ready')
	{
		$('.migration_result')
			.css('display','inline')
			.text('Keywords successfully migrated');
		
		//$('.update_date .ud_date_time').text(data['latest_update']);
	} 
	else
	{
		$('.migration_result').css('display','inline').text('Keywords migrating now');
	}
		
	clearTimeout(resultMessageTimeout);
	/*resultMessageTimeout = setTimeout(function () {
		/*$('.migration_result').fadeOut(300).queue(function() {
			//$('.update_date').fadeIn(500);
			$(this).dequeue();
		});
	}, 3000);*/
	isDbNotEmpty = 1;
}

function requestUpdateStatus() {
	$.getJSON(urlstatus, onUpdateStatusCallback);
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
		$('.update_status_text').text('Migrating keywords...')
	}
	
	//$('.update_date .ud_date_time').text(data['latest_update']);
	
	clearTimeout(resultUpdateStatusTimeout);
	resultUpdateStatusTimeout = setTimeout(requestUpdateStatus, M1SEO.statusUpdateInterval);
}
