jQuery(document).ready(function($) {
	$("#meta_trigger").click(function(event) {
		$("#meta_disable").toggle(function() {
			$(this).show();
		}, function() {
			$(this).hide();
		});
		
	});
});