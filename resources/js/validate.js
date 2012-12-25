$.metadata.setType("attr", "validate");
$(document).ready(function() {
	$("#surveyForm").validate({
		// debug: true,
		errorElement: "div",
		errorPlacement: function(error, element) {
			error.appendTo(element.parents("ol.letter").first().parent('li'));
		},	
		rules: {
			'test': {
				required: true
			}
		}
	});
});