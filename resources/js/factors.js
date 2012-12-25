$(document).ready(function(){
	if ($("#item_list").length){
		var context = getContext();
		$("#item_list").jqGrid({
			url: 'getFactors',
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
				// search: true,
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
				var dialogClass = target.find('form').attr('class');
				$(target).dialog({
					width: 500,
					modal: true,
					dialogClass: dialogClass,
					create: function(event, ui) {
						$('div#add_item').removeClass('dhidden');
					}
				});
			});
		}
		return false;
	});
	$(document).delegate('div#add_item form input[type="submit"]', 'click', function(ev){
		ev.stopImmediatePropagation();
		var formEl = $(this).parents('form').first();
		$.post(formEl.prop('action'), formEl.serialize(), function(data){
			$('div#add_item').first().dialog('close');
			$("#item_list").jqGrid().trigger("reloadGrid");
		});
		return false;
	});
	$(document).delegate('div#add_item form input[type="button"]#cancel', 'click', function(ev){
		ev.stopImmediatePropagation();
		$('div#add_item').first().dialog('close');
		return false;
	});
 });