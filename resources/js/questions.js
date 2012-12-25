$(document).ready(function(){
	if ($("#item_list").length){
		var context = getContext();
		$("#item_list").jqGrid({
			url: 'getQuestions',
			datatype: "json",
			mtype: "POST",
			colNames:['title', 'status', 'actions'],
			colModel:[
			{
				name:'title',
				index:'title',
				align:"left",
				sortable:true,	
				searchoptions: {defaultValue: element('title', context)},
				width:16
			},
			{
				name:'status',
				index:'status',
				align:"center",
				sortable:true,
				stype:'select',
				searchoptions: {
					value: ':All;0:Inactive;1:Active',
					separator: ':',
					delimiter: ';',
					defaultValue: element('status', context)
				},
				width:2
			},
			{
				name:'actions',
				width:2,
				align:"center",
				search: false,
				sortable:false
			}],
			rowNum:parseInt(element('rows', context, 10)),
			rowList:[10,20,30],
			pager: '#item_pager',
			page: parseInt(element('page', context, 1)),
			postData : context,
			sortname: element('sidx', context, 'id'),
			viewrecords: true,
			sortorder: element('sord', context, 'asc'),
			beforeRequest : setContext,
			width: '960',
			height: '100%',
			rownumbers: true
		});
		$("#item_list").jqGrid('navGrid','#item_pager',{
			edit:false,
			add:false,
			del:false,
			search: false
		});
		$("#item_list").jqGrid('filterToolbar', {
			searchOnEnter: false
		});
	}
	// triggers which open dialog boxes with add/edit/delete actions
	$(document).delegate('a.edit_button', 'click', function(ev){
		ev.stopImmediatePropagation();
		if ($(this).hasClass('delete')) {
			var url = $(this).prop('href');
			$( ".dialog-confirm" ).dialog({
				width:500,
				modal: true,
				create: function(event, ui) {
					$('.dialog-confirm').removeClass('dhidden');
				},
				buttons: {
					"Yes": function() {
						$.ajax({
							url: url,
							success: function(data) {
								$("#item_list").jqGrid().trigger('reloadGrid');
							}
						});
						$(this).dialog("close");
					},
					"No": function() {
						$(this).dialog("close");
					}
				}
			});
		} else {
			$.post($(this).prop('href'), function(data){
				var target = $('div#add_item').first();
				target.html(data);
				$(target).dialog({
					width: 500,
					modal: true,
					create: function(event, ui) {
						$('div#add_item').removeClass('dhidden');
						checkForm();
					}
				});
			});
		}
		return false;
	});
	// submit in dialog box
	$(document).delegate('div#add_item form input[type="submit"]', 'click', function(ev){
		ev.stopImmediatePropagation();
		var formEl = $(this).parents('form').first();
		$.post(formEl.prop('action'), formEl.serialize(), function(data){
			$('div#add_item').first().dialog('close');
			$("#item_list").jqGrid().trigger("reloadGrid");
		});
		return false;
	});
	// cancel in dialog box
	$(document).delegate('div#add_item form input[type="button"]#cancel', 'click', function(ev){
		ev.stopImmediatePropagation();
		$('div#add_item').first().dialog('close');
		return false;
	});
	
	// custom behaviors
	$(document).delegate('form#loadItem .row label a.add', 'click', function(ev){
		ev.stopImmediatePropagation();
		var list = $('form#loadItem .answers').first();
		var last = list.find('li').filter(':last');
		last.clone().insertAfter(last).find('input[type="text"]').each(function(){
			$(this).val('');
		});
		checkForm();
		return false;
	});
	$(document).delegate('form#loadItem .row .cell .answers a.delete', 'click', function(ev){
		ev.stopImmediatePropagation();
		if ($('form#loadItem .answers li').length > 2) {
			$(this).parents('li').first().remove();
			checkForm();
		} else {
			alert('Minimum number of answers is 2!');
		}
		return false;
	});
	$(document).delegate('form#loadItem .row input[type="text"]', 'change', function(){
		checkForm();
	});
	
/* 	// business autocomplete
	if ($("#id_business_sel").length) {
		id_business_val = $("#id_business_sel").val();
		$("#id_business_sel").autocomplete({
			source: "business/search",
			minLength: 2,
			select: function( event, ui ) {
				$("#id_business").val(ui.item.id);
			},
			close: function(event, ui) {
				id_business_val = $("#id_business_sel").val();
			},
			change: function(event, ui) {
				new_id_business_val = $("#id_business_sel").val();
				if(id_business_val != new_id_business_val) {
					$("#id_business").attr('value', '');
					$("#id_business_sel").attr('value', '');
					$("#id_business_sel").text('');
				}
			}
		});
	}	
 */
 });
 
function checkAnswersCount(checkValid) {
	var nr = $('form#loadItem .answers li').length;
	if (nr > 1) {
		if (checkValid) {
			var nr = 0;
			$('form#loadItem .answers li').each(function(){
				var a_name = $(this).find('input[name^="answer["]').first().val();
				var a_value = $(this).find('input[name^="value["]').first().val();
				if ((a_name != '') && (parseInt(a_value))) {
					nr++;
				}
			});
			// alert(nr);
			if (nr > 1) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	} else {
		alert('Minimum number of valid answers is 2!');
		return false;
	}
}
function checkForm() {
	var isValid = checkAnswersCount(true);
	
	if (isValid) {
		if ($('form#loadItem input#title').first().val() == '') {
			isValid = false;
		}
	}
	
	if (isValid) {
		$('form#loadItem input#submit').removeAttr('disabled');
	} else {
		$('form#loadItem input#submit').attr('disabled', 'disabled');
	}
}