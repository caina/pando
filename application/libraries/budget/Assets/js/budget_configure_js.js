jQuery(document).ready(function($) {
	$("input").change(function(event) {
		var _this = $(this);
		$.post($("#form_configure").attr("action"),  $("#form_configure").serialize(), function(data, textStatus, xhr) {
			_this.parentsUntil("form-group").addClass('has-success');
			setTimeout(function() {$("input").parentsUntil("form-group").removeClass('has-success')}, 600);
		});
	});
});