$(document).ready(function () {
	$('.type-select').hide();
	$('.type-current-line a.change-type').click(function () {
		$('.type-select').show('fast');
		return false;
	});
	
	$('a.type-selectnew:last').css('margin-right', 0);
	
	$('.type-select a.type-selectnew').click(function () {
		$('span.type-current').text($(this).text());
		loadKeywordsType($(this).attr('rel'));
		$('.type-select').hide('fast');
		return false;
	});
	
	$('img.type-searching').hide();
	$('button.type-search').click(function () {
		searchKeyword( $('input.type-search-keyword').val() );
	});
	
	$('.type-search-keyword').keypress(function (e) {
      if (e.which == 13) {
	  	searchKeyword( $('input.type-search-keyword').val() );
	  }
	});
	
	$('.editpanel-save-details').click( saveDetails );
	
	searchKeyword();
});

function initEntitiesList() {
	$('.entity-element').hover(function () {
		if (!$(this).hasClass('entity-element-selected'))
			$(this).css('background-color', '#DFF5FF');
	}, function () {
		if (!$(this).hasClass('entity-element-selected'))
			$(this).css('background-color', '#fff');
	} );

	$('.entity-element').click( onEntityClick );	
}

function onEntityClick() {
	$('.entity-element[class*=entity-element-selected]').removeClass('entity-element-selected').css('background-color', '#fff');
	$(this).addClass('entity-element-selected').css('background-color', '#b5e7ff');
	var entity_id = $(this).find('.entity-id').text();
	$('.type-current-name').text( $(this).find('.entity-name').text() );
	// hide saved... text
	$('.editpanel-save-result').hide();
	showDetails( entity_id );
}

function saveDetails() {
	$('.editpanel-save-details').attr('disable','true');
	$('.keywords-edit-field').each(function () {
		M1SEOK.save_data_counter = 0;
		$('.details-saving').show();
		saveKeyword(
			M1SEOK.current_keywords_type,
			M1SEOK.current_entity_id,
			$(this).attr('id'),
			$(this).val(),
			$('.keywords-edit-metadesc-value[id=' + $(this).attr('id') +']').val(),
			$('.keywords-edit-metakeywords-value[id=' + $(this).attr('id') +']').val(),
			$('.keywords-edit-title-value[id=' + $(this).attr('id') +']').val()    
		);
	});
	$('.editpanel-save-details').removeAttr('disable');
}

function saveKeyword(entity_type, entity_id, language, keyword, metadescription, metakeywords, title) {
	M1SEOK.save_data_counter++;
	$('span.keyword-problem-notify').text('');
	$.getJSON(M1SEOK.details_save_url,{
		type: entity_type,
		id: entity_id,
		'language': language,
		'keyword': keyword,
		'metadescription': metadescription,
		'metakeywords': metakeywords,
        'title': title
	}, saveKeywordCallback);
}

function saveKeywordCallback(data) {
	if (!data.status) {
		$('.keyword-editing-panel #' + data.language).parents('p').find('span.keyword-problem-notify').text('* '+data.errorText);
	}
	M1SEOK.save_data_counter--;
	checkSaveResult();
}

function checkSaveResult() {
	if (M1SEOK.save_data_counter == 0) {
		$('.details-saving').hide();
		$('.editpanel-save-result').hide().text('Saved...').fadeIn('fast');
		setTimeout(function () {
			$('.editpanel-save-result').fadeOut('fast');
		}, 2000);
	}
}

Function.prototype.defaults = function()
{
  var _f = this;
  var _a = Array(_f.length-arguments.length).concat(
    Array.prototype.slice.apply(arguments));
  return function()
  {
    return _f.apply(_f, Array.prototype.slice.apply(arguments).concat(
      _a.slice(arguments.length, _a.length)));
  }
}

var searchKeyword = function ( keyword )
{
	detailsPanelStage1();
	$('button.type-search').attr('disabled', 'true').css('color','#888');
	$('img.type-searching').show();
	$.getJSON(M1SEOK.search_keywords_url, {type: M1SEOK.current_keywords_type, key: keyword}, searchKeywordCallback);
	
}.defaults('');

function loadKeywordsType( type ) {
	M1SEOK.current_keywords_type = type;
	searchKeyword();
	detailsPanelStage1();
}


function detailsPanelStage1() {
	$('.details-placeholder').show();
	$('.details-loading-block').hide();
	$('.keyword-editing-panel').hide();
}

function showDetails( entity_id ) {
	$('.keyword-editing-panel').hide();
	$('.details-placeholder').hide();
	$('.details-loading-block').show();
	$('.current-entity-id').text(entity_id);
	M1SEOK.current_entity_id = entity_id;
	
	$.getJSON(
		M1SEOK.details_url, 
		{
			type: M1SEOK.current_keywords_type, 
			id: entity_id
		}, 
		showDetailsCallback);
}

function showDetailsCallback( data ) {
	$('.details-loading-block').hide();
	$('.editpanel-keywords-controls').text('');
	for (i in data) {
		keywordInput = 
		'<span class="keywords-edit-language">' + M1SEOK.languages[ data[i].language ]+ '</span>' + ': <input type="text" value="' + data[i].keyword + '" id="' + data[i].language + '" class="keywords-edit-field"/>' +
		'<span class="keyword-problem-notify"></span>' +
		'<p class="keywords-edit-metadesc">Meta Description:<textarea id="' + data[i].language + '" class="keywords-edit-metadesc-value">' + data[i].metadescription + '</textarea><p>' +
		'<p class="keywords-edit-metakeywords">Meta Keywords:<textarea id="' + data[i].language + '" class="keywords-edit-metakeywords-value">' + data[i].metakeywords + '</textarea></p>' +
		'<p class="keywords-edit-title">Title:<textarea id="' + data[i].language + '" class="keywords-edit-title-value">' + data[i].title + '</textarea></p>' + 
		'<div class="keywords-edit-separator"></div>';
		$('.editpanel-keywords-controls').append('<p>' + keywordInput + '</p>');
	}
	$('.keyword-editing-panel').show();
	
	$('.keywords-edit-field').keypress(function (e) {
		if (e.which == 13) {
			saveDetails();
		}
	});
}

function searchKeywordCallback( data ) 
{
	$('img.type-searching').hide();
	$('button.type-search').removeAttr('disabled').css('color','#F16F31');
	$('.entity-element').remove();
	for(i in data) {	
		$('.entities-list').append('<div class="entity-element"><span class="entity-id">' + data[i].id + '</span><span class="entity-name">' + data[i].keyword + '</span></div>');
	}
	initEntitiesList();
}


