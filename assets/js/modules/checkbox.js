jQuery(document).ready(function($) {
	$(".component_checkbox input[type='checkbox']").change(function(event) {
		// console.log($(this).val()+":"+$(this).is(":checked"));

		var obj = {};
		obj.is_checked = $(this).is(":checked");
		obj.val = $(this).val();
		obj.table = $(this).parentsUntil("form").parent().attr("data-table");
		obj.field = $(this).parentsUntil("form").parent().attr("data-field");
		obj.mngr_token = $("input[name='mngr_token']").val();

		$(".status_ajax").empty();
		$.post($(this).parentsUntil("form").parent().attr("action"), {obj}, function(data, textStatus, xhr) {
			$(".status_ajax").html('<div class="alert alert-success"> <i class="fa fa-check-circle fa-fw fa-lg"></i> <strong>Ok!</strong> Alteração registrada. </div>');
		});
	});
});