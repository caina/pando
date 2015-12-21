jQuery(document).ready(function($) {
	$(".save_form_ajax").click(function(event) {
		event.preventDefault();
		$(this).find("i").show();
		var form = $("#template_data_update");

		$.post(form.attr("action"), form.serialize(), function(data, textStatus, xhr) {
			$(".fa-spinner").hide();
		});
	});

	$("#version_create").hide();
	$("#version_configuration").hide();

	$("#version_create_btn").click(function(event) {
		event.preventDefault();
		$("#version_configuration").hide('fast', function() {
			$("#version_create").show('fast');
		});
	});

	$("#version_configuration_btn").click(function(event) {
		event.preventDefault();
		$("#version_create").hide('fast', function() {
			$("#version_configuration").show('fast');
		});
	});

	$("#theme_version").change(function(event) {
		event.preventDefault();
		$("#template_data_update").submit();
	});

	$(".close_version").click(function(event) {
		event.preventDefault();
		$("#version_create").hide("fast");
		$("#version_configuration").hide("fast");
	});

	$(".maskedDate").mask("99/99/9999");


	load_page_template();
});


function load_page_template(){
	$("#template_content #loading").show();
}