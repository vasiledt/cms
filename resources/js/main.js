$(document).ready(function() {
	$(document).delegate('a.pop_triger', 'click', function(ev){ // open popup trigers
		ev.stopImmediatePropagation();
		var parent = $(ev.target).parents('div.cmsObject').first();
		parent.append('<div class="dialog-add-item dhidden" id="add_item"></div>');
		$.post($(this).prop('href'), function(data){
			var target = parent.find('div#add_item').first();
			target.html(data);
			var dialogClass = target.find('form').attr('class');
			var dialogTitle = target.find('form').find('.title').text();
			$(target).dialog({
				width: 500,
				modal: true,
				dialogClass: dialogClass,
				title: dialogTitle,
				create: function(event, ui) {
					$('div#add_item').removeClass('dhidden');
					$(parent).addClass('pop_added');
				},
				close: function( event, ui ) {
					$('div#add_item').dialog('destroy').remove();
					$(parent).removeClass('pop_added');
				}
			});
		});
		return false;	
	});
	
	// submit in dialog box
	$(document).delegate('div#add_item form input[type="submit"]', 'click', function(ev){
		ev.stopImmediatePropagation();
		var formEl = $(this).parents('form').first();
		var formTarget = formEl.attr('target');
		if (formTarget) {
			var target = $('iframe#'+formTarget);
			if (target.attr('id') == formTarget) { // if target iframe detected, submit form to iframe and close the dialog. The iframe will handle other events like reload. Used for files upload, because ajax serialize is bad!
				formEl.submit();
				return false;
			}
		}
		
		$.post(formEl.prop('action'), formEl.serialize(), function(data){
			$('div.loadable.pop_added').trigger('reload');
			$('div#add_item').first().dialog('close');
			if ($("#item_list").length) {
				$("#item_list").jqGrid().trigger("reloadGrid");
			}
		});
		return false;
	});
	
	// cancel in dialog box
	$(document).delegate('div#add_item form input[type="button"]#cancel', 'click', function(ev){
		ev.stopImmediatePropagation();
		$('div#add_item').first().dialog('close');
		return false;
	});	
	
	// loadable containers
	$(document).delegate('.loadable', 'reload', function(ev){
		ev.stopImmediatePropagation();
		$(this).load($(this).attr('src'));
		return false;
	});
	
	
	
	$(document).delegate('.crop span.menu a span.move', 'click', function(ev){
		ev.stopImmediatePropagation();
		var t = $(ev.target);
		if (t.hasClass('active')) { // cropper already activated
			return false;
		}
		var cropper = $(t.parents('.crop').first());
		var img = $(cropper.find('img').first());
		var w_range = img.width() - cropper.width();
		var h_range = img.height() - cropper.height();
		var cpos = cropper.offset();
		img.draggable({ containment: [cpos.left - w_range, cpos.top - h_range, cpos.left, cpos.top] });
		t.addClass('active');
		return false;
	});
	$(document).delegate('.crop span.menu a span.save', 'click', function(ev){
		ev.stopImmediatePropagation();
		var t = $(ev.target);
		var img = $(t.parents('.crop').first().find('img').first());
		var pos = img.position();
		var form = $('#' + t.parents('a').first().prop('rel'));
		$.post(form.prop('action'), form.serialize()+'&top='+pos.top+'&left='+pos.left, function(){
			alert('image position saved!');
		});
		return false;
	});
});

function closePop() {
	$('div.loadable.pop_added').trigger('reload');
	$('div#add_item').first().dialog('close');
}