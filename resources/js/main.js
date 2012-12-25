$(document).ready(function() {
	$(document).delegate('a.pop_triger', 'click', function(ev){
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
				},
				close: function( event, ui ) {
					$('div#add_item').dialog('destroy').remove();
				}
			});
		});
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